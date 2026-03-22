<?php
/**
 * Neutara ATS
 * Candidate-Job Matching & Scoring Engine
 *
 * Scores candidates against job orders based on:
 * - Skill keyword overlap (primary factor)
 * - Location proximity
 * - Experience level estimation
 * - Resume text relevance to job description
 *
 * Returns a 0-100 match score with breakdown.
 */

include_once(LEGACY_ROOT . '/lib/DataGrid.php');
include_once(LEGACY_ROOT . '/lib/Candidates.php');
include_once(LEGACY_ROOT . '/lib/JobOrders.php');
include_once(LEGACY_ROOT . '/lib/Attachments.php');

class CandidateJobMatch
{
    private $_db;
    private $_siteID;

    /* Scoring weights (must sum to 1.0) */
    const WEIGHT_SKILLS    = 0.45;
    const WEIGHT_KEYWORDS  = 0.25;
    const WEIGHT_LOCATION  = 0.15;
    const WEIGHT_RECENCY   = 0.15;

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
    }

    /**
     * Score a single candidate against a single job order.
     *
     * @param int $candidateID
     * @param int $jobOrderID
     * @return array Match result with score and breakdown
     */
    public function scoreCandidate($candidateID, $jobOrderID)
    {
        $candidate = $this->_getCandidateData($candidateID);
        $jobOrder  = $this->_getJobOrderData($jobOrderID);

        if ($candidate === false || $jobOrder === false)
        {
            return array('score' => 0, 'error' => 'Candidate or job order not found');
        }

        $breakdown = array();

        // 1. Skills match
        $breakdown['skills'] = $this->_scoreSkillMatch(
            $candidate['keySkills'],
            $jobOrder['description'] . ' ' . $jobOrder['title']
        );

        // 2. Keyword/text relevance
        $breakdown['keywords'] = $this->_scoreKeywordRelevance(
            $candidate['resumeText'],
            $jobOrder['description']
        );

        // 3. Location match
        $breakdown['location'] = $this->_scoreLocationMatch(
            $candidate['city'], $candidate['state'],
            $jobOrder['city'], $jobOrder['state']
        );

        // 4. Recency (how recently was the candidate active)
        $breakdown['recency'] = $this->_scoreRecency($candidate['dateModified']);

        // Calculate weighted total
        $totalScore = round(
            ($breakdown['skills']['score']   * self::WEIGHT_SKILLS +
             $breakdown['keywords']['score'] * self::WEIGHT_KEYWORDS +
             $breakdown['location']['score'] * self::WEIGHT_LOCATION +
             $breakdown['recency']['score']  * self::WEIGHT_RECENCY) * 100
        );

        $totalScore = min(100, max(0, $totalScore));

        return array(
            'score'       => $totalScore,
            'grade'       => $this->_scoreToGrade($totalScore),
            'candidateID' => $candidateID,
            'jobOrderID'  => $jobOrderID,
            'candidateName' => $candidate['firstName'] . ' ' . $candidate['lastName'],
            'jobTitle'    => $jobOrder['title'],
            'breakdown'   => $breakdown
        );
    }

    /**
     * Find and rank top matching candidates for a job order.
     *
     * @param int $jobOrderID
     * @param int $limit Max candidates to return
     * @return array Ranked list of candidates with scores
     */
    public function findMatchingCandidates($jobOrderID, $limit = 20)
    {
        $jobOrder = $this->_getJobOrderData($jobOrderID);
        if ($jobOrder === false) return array();

        // Get all active candidates
        $sql = sprintf(
            "SELECT candidate_id FROM candidate
             WHERE site_id = %s AND is_active = 1 AND is_admin_hidden = 0
             ORDER BY date_modified DESC
             LIMIT 200",
            $this->_siteID
        );
        $candidateRows = $this->_db->getAllAssoc($sql);

        $candidates = array();
        foreach ($candidateRows as $row)
        {
            $candidateID = $row['candidate_id'];

            // Skip candidates already in this job's pipeline
            if ($this->_isInPipeline($candidateID, $jobOrderID)) continue;

            $result = $this->scoreCandidate($candidateID, $jobOrderID);
            if ($result['score'] > 10) // Only include meaningful matches
            {
                $candidates[] = $result;
            }
        }

        // Sort by score descending
        usort($candidates, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        return array_slice($candidates, 0, $limit);
    }

    /**
     * Find and rank top matching job orders for a candidate.
     *
     * @param int $candidateID
     * @param int $limit Max jobs to return
     * @return array Ranked list of job orders with scores
     */
    public function findMatchingJobs($candidateID, $limit = 20)
    {
        $candidate = $this->_getCandidateData($candidateID);
        if ($candidate === false) return array();

        // Get active job orders
        $sql = sprintf(
            "SELECT joborder_id FROM joborder
             WHERE site_id = %s AND status = 'Active' AND is_admin_hidden = 0
             ORDER BY date_modified DESC
             LIMIT 100",
            $this->_siteID
        );
        $jobRows = $this->_db->getAllAssoc($sql);

        $jobs = array();
        foreach ($jobRows as $row)
        {
            $jobOrderID = $row['joborder_id'];
            $result = $this->scoreCandidate($candidateID, $jobOrderID);
            if ($result['score'] > 10)
            {
                $jobs[] = $result;
            }
        }

        usort($jobs, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        return array_slice($jobs, 0, $limit);
    }

    /**
     * Score skill overlap between candidate skills and job requirements.
     */
    private function _scoreSkillMatch($candidateSkills, $jobText)
    {
        if (empty($candidateSkills) || empty($jobText))
        {
            return array('score' => 0, 'matched' => array(), 'total' => 0);
        }

        // Normalize and tokenize candidate skills
        $skills = array_map('trim', preg_split('/[,;|]+/', strtolower($candidateSkills)));
        $skills = array_filter($skills, function($s) { return strlen($s) > 1; });

        $jobTextLower = strtolower($jobText);
        $matched = array();

        foreach ($skills as $skill)
        {
            // Check if the skill appears in the job description
            if (strpos($jobTextLower, $skill) !== false)
            {
                $matched[] = $skill;
            }
            else
            {
                // Try partial match for multi-word skills
                $words = explode(' ', $skill);
                if (count($words) > 1)
                {
                    $partialMatch = 0;
                    foreach ($words as $word)
                    {
                        if (strlen($word) > 2 && strpos($jobTextLower, $word) !== false)
                        {
                            $partialMatch++;
                        }
                    }
                    if ($partialMatch >= ceil(count($words) * 0.6))
                    {
                        $matched[] = $skill;
                    }
                }
            }
        }

        $totalSkills = count($skills);
        $matchedCount = count($matched);
        $score = $totalSkills > 0 ? min(1.0, $matchedCount / max(1, $totalSkills * 0.5)) : 0;

        return array(
            'score'   => round($score, 2),
            'matched' => $matched,
            'total'   => $totalSkills,
            'matchedCount' => $matchedCount
        );
    }

    /**
     * Score keyword relevance between resume text and job description.
     * Uses TF-IDF-like approach with common word filtering.
     */
    private function _scoreKeywordRelevance($resumeText, $jobDescription)
    {
        if (empty($resumeText) || empty($jobDescription))
        {
            return array('score' => 0, 'matchedTerms' => 0, 'totalTerms' => 0);
        }

        // Extract significant keywords from job description
        $stopWords = array(
            'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for',
            'of', 'with', 'by', 'from', 'as', 'is', 'was', 'are', 'were', 'be',
            'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will',
            'would', 'could', 'should', 'may', 'might', 'shall', 'can', 'need',
            'this', 'that', 'these', 'those', 'it', 'its', 'we', 'you', 'they',
            'he', 'she', 'them', 'their', 'our', 'your', 'his', 'her', 'my',
            'not', 'no', 'nor', 'so', 'if', 'then', 'than', 'too', 'very',
            'just', 'about', 'above', 'after', 'again', 'all', 'also', 'any',
            'both', 'each', 'more', 'most', 'other', 'some', 'such', 'only',
            'own', 'same', 'into', 'over', 'under', 'out', 'up', 'down',
            'must', 'able', 'etc', 'per', 'experience', 'work', 'working',
            'required', 'preferred', 'strong', 'good', 'new', 'well', 'job'
        );

        // Get meaningful words from job description (3+ chars, not stop words)
        $jobWords = preg_split('/[\s,.\-:;()\/]+/', strtolower($jobDescription));
        $jobWords = array_filter($jobWords, function($w) use ($stopWords) {
            return strlen($w) >= 3 && !in_array($w, $stopWords) && !is_numeric($w);
        });

        // Count word frequency in job description
        $jobFreq = array_count_values($jobWords);
        arsort($jobFreq);

        // Take top keywords (most frequent in job desc)
        $topKeywords = array_slice(array_keys($jobFreq), 0, 30);

        if (empty($topKeywords))
        {
            return array('score' => 0, 'matchedTerms' => 0, 'totalTerms' => 0);
        }

        $resumeLower = strtolower($resumeText);
        $matchedTerms = 0;

        foreach ($topKeywords as $keyword)
        {
            if (strpos($resumeLower, $keyword) !== false)
            {
                $matchedTerms++;
            }
        }

        $totalTerms = count($topKeywords);
        $score = $totalTerms > 0 ? $matchedTerms / $totalTerms : 0;

        return array(
            'score'        => round(min(1.0, $score * 1.3), 2), // Boost slightly
            'matchedTerms' => $matchedTerms,
            'totalTerms'   => $totalTerms
        );
    }

    /**
     * Score location match between candidate and job.
     */
    private function _scoreLocationMatch($candCity, $candState, $jobCity, $jobState)
    {
        if (empty($jobCity) && empty($jobState))
        {
            // Remote / no location specified — perfect match for everyone
            return array('score' => 1.0, 'match' => 'no_requirement');
        }

        if (empty($candCity) && empty($candState))
        {
            return array('score' => 0.3, 'match' => 'unknown');
        }

        $candCity  = strtolower(trim($candCity));
        $candState = strtolower(trim($candState));
        $jobCity   = strtolower(trim($jobCity));
        $jobState  = strtolower(trim($jobState));

        // Exact city + state match
        if ($candCity === $jobCity && $candState === $jobState)
        {
            return array('score' => 1.0, 'match' => 'exact');
        }

        // Same state, different city
        if (!empty($candState) && $candState === $jobState)
        {
            return array('score' => 0.7, 'match' => 'same_state');
        }

        // Different state
        return array('score' => 0.2, 'match' => 'different_state');
    }

    /**
     * Score based on how recently the candidate was active.
     */
    private function _scoreRecency($dateModified)
    {
        if (empty($dateModified) || $dateModified === '1000-01-01 00:00:00')
        {
            return array('score' => 0.3, 'days' => 999);
        }

        $days = (time() - strtotime($dateModified)) / 86400;

        if ($days <= 7)       $score = 1.0;
        else if ($days <= 30) $score = 0.9;
        else if ($days <= 90) $score = 0.7;
        else if ($days <= 180) $score = 0.5;
        else if ($days <= 365) $score = 0.3;
        else                   $score = 0.1;

        return array('score' => $score, 'days' => round($days));
    }

    /**
     * Convert numeric score to letter grade.
     */
    private function _scoreToGrade($score)
    {
        if ($score >= 85) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 55) return 'C';
        if ($score >= 40) return 'D';
        return 'F';
    }

    /**
     * Get candidate data for scoring.
     */
    private function _getCandidateData($candidateID)
    {
        $sql = sprintf(
            "SELECT
                candidate.candidate_id, candidate.first_name AS firstName,
                candidate.last_name AS lastName, candidate.key_skills AS keySkills,
                candidate.city, candidate.state, candidate.date_modified AS dateModified,
                candidate.current_employer AS currentEmployer,
                candidate.notes
             FROM candidate
             WHERE candidate.candidate_id = %s AND candidate.site_id = %s",
            intval($candidateID),
            $this->_siteID
        );

        $rs = $this->_db->getAllAssoc($sql);
        if (empty($rs)) return false;

        $candidate = $rs[0];

        // Get resume text from attachments
        $sql2 = sprintf(
            "SELECT text FROM attachment
             WHERE data_item_id = %s AND data_item_type = %s
             AND resume = 1 AND site_id = %s
             ORDER BY date_modified DESC LIMIT 1",
            intval($candidateID),
            DATA_ITEM_CANDIDATE,
            $this->_siteID
        );

        $attachRS = $this->_db->getAllAssoc($sql2);
        $candidate['resumeText'] = !empty($attachRS) ? $attachRS[0]['text'] : '';

        return $candidate;
    }

    /**
     * Get job order data for scoring.
     */
    private function _getJobOrderData($jobOrderID)
    {
        $sql = sprintf(
            "SELECT
                joborder.joborder_id, joborder.title, joborder.description,
                joborder.city, joborder.state, joborder.notes,
                joborder.salary, joborder.type, joborder.duration
             FROM joborder
             WHERE joborder.joborder_id = %s AND joborder.site_id = %s",
            intval($jobOrderID),
            $this->_siteID
        );

        $rs = $this->_db->getAllAssoc($sql);
        if (empty($rs)) return false;

        return $rs[0];
    }

    /**
     * Check if candidate is already in the pipeline for this job.
     */
    private function _isInPipeline($candidateID, $jobOrderID)
    {
        $sql = sprintf(
            "SELECT COUNT(*) AS cnt FROM candidate_joborder
             WHERE candidate_id = %s AND joborder_id = %s AND site_id = %s",
            intval($candidateID),
            intval($jobOrderID),
            $this->_siteID
        );

        $rs = $this->_db->getAllAssoc($sql);
        return (!empty($rs) && $rs[0]['cnt'] > 0);
    }
}

?>
