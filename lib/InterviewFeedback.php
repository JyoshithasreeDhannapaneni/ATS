<?php
/**
 * Neutara ATS
 * Interview Feedback Library
 *
 * Manages interview feedback forms, ratings, and stage tracking.
 * Supports multi-stage interviews (L1, L2, L3) with per-interviewer feedback.
 */

class InterviewFeedback
{
    private $_db;
    private $_siteID;

    const STAGE_L1 = 'L1';
    const STAGE_L2 = 'L2';
    const STAGE_L3 = 'L3';
    const STAGE_HR = 'HR';
    const STAGE_FINAL = 'Final';

    const STATUS_PENDING = 'pending';
    const STATUS_SUBMITTED = 'submitted';

    const REC_STRONG_HIRE = 'strong_hire';
    const REC_HIRE = 'hire';
    const REC_MAYBE = 'maybe';
    const REC_NO_HIRE = 'no_hire';
    const REC_STRONG_NO_HIRE = 'strong_no_hire';

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
    }

    /**
     * Create a new feedback entry for an interview.
     */
    public function create($calendarEventID, $candidateID, $jobOrderID, $interviewerUserID, $stage = 'L1')
    {
        $sql = sprintf(
            "INSERT INTO interview_feedback
                (calendar_event_id, candidate_id, joborder_id, interviewer_user_id,
                 interview_stage, status, site_id, date_created, date_modified)
             VALUES (%s, %s, %s, %s, %s, 'pending', %s, NOW(), NOW())",
            intval($calendarEventID),
            intval($candidateID),
            intval($jobOrderID),
            intval($interviewerUserID),
            $this->_db->makeQueryString($stage),
            $this->_siteID
        );

        $this->_db->query($sql);
        return $this->_db->getLastInsertID();
    }

    /**
     * Submit feedback with ratings and notes.
     */
    public function submitFeedback($feedbackID, $data)
    {
        $sql = sprintf(
            "UPDATE interview_feedback SET
                overall_rating = %s,
                technical_rating = %s,
                communication_rating = %s,
                cultural_fit_rating = %s,
                problem_solving_rating = %s,
                strengths = %s,
                weaknesses = %s,
                notes = %s,
                recommendation = %s,
                status = 'submitted',
                date_modified = NOW()
             WHERE feedback_id = %s AND site_id = %s",
            isset($data['overallRating']) ? intval($data['overallRating']) : 'NULL',
            isset($data['technicalRating']) ? intval($data['technicalRating']) : 'NULL',
            isset($data['communicationRating']) ? intval($data['communicationRating']) : 'NULL',
            isset($data['culturalFitRating']) ? intval($data['culturalFitRating']) : 'NULL',
            isset($data['problemSolvingRating']) ? intval($data['problemSolvingRating']) : 'NULL',
            $this->_db->makeQueryString($data['strengths'] ?? ''),
            $this->_db->makeQueryString($data['weaknesses'] ?? ''),
            $this->_db->makeQueryString($data['notes'] ?? ''),
            $this->_db->makeQueryString($data['recommendation'] ?? ''),
            intval($feedbackID),
            $this->_siteID
        );

        return $this->_db->query($sql);
    }

    /**
     * Get all feedback for a candidate across all interviews.
     */
    public function getFeedbackByCandidate($candidateID)
    {
        $sql = sprintf(
            "SELECT
                f.*,
                u.first_name AS interviewerFirstName,
                u.last_name AS interviewerLastName,
                ce.title AS eventTitle,
                ce.date AS eventDate,
                jo.title AS jobTitle
             FROM interview_feedback f
             LEFT JOIN user u ON f.interviewer_user_id = u.user_id
             LEFT JOIN calendar_event ce ON f.calendar_event_id = ce.calendar_event_id
             LEFT JOIN joborder jo ON f.joborder_id = jo.joborder_id
             WHERE f.candidate_id = %s AND f.site_id = %s
             ORDER BY f.interview_stage ASC, f.date_created DESC",
            intval($candidateID),
            $this->_siteID
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Get all feedback for a specific job order + candidate pipeline entry.
     */
    public function getFeedbackByJobCandidate($jobOrderID, $candidateID)
    {
        $sql = sprintf(
            "SELECT
                f.*,
                u.first_name AS interviewerFirstName,
                u.last_name AS interviewerLastName,
                ce.title AS eventTitle,
                ce.date AS eventDate
             FROM interview_feedback f
             LEFT JOIN user u ON f.interviewer_user_id = u.user_id
             LEFT JOIN calendar_event ce ON f.calendar_event_id = ce.calendar_event_id
             WHERE f.candidate_id = %s AND f.joborder_id = %s AND f.site_id = %s
             ORDER BY f.interview_stage ASC, f.date_created DESC",
            intval($candidateID),
            intval($jobOrderID),
            $this->_siteID
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Get a single feedback entry.
     */
    public function get($feedbackID)
    {
        $sql = sprintf(
            "SELECT
                f.*,
                u.first_name AS interviewerFirstName,
                u.last_name AS interviewerLastName,
                ce.title AS eventTitle,
                ce.date AS eventDate,
                c.first_name AS candidateFirstName,
                c.last_name AS candidateLastName,
                jo.title AS jobTitle
             FROM interview_feedback f
             LEFT JOIN user u ON f.interviewer_user_id = u.user_id
             LEFT JOIN calendar_event ce ON f.calendar_event_id = ce.calendar_event_id
             LEFT JOIN candidate c ON f.candidate_id = c.candidate_id
             LEFT JOIN joborder jo ON f.joborder_id = jo.joborder_id
             WHERE f.feedback_id = %s AND f.site_id = %s",
            intval($feedbackID),
            $this->_siteID
        );

        $rs = $this->_db->getAllAssoc($sql);
        return !empty($rs) ? $rs[0] : false;
    }

    /**
     * Get pending feedback for a specific interviewer user.
     */
    public function getPendingForUser($userID)
    {
        $sql = sprintf(
            "SELECT
                f.*,
                c.first_name AS candidateFirstName,
                c.last_name AS candidateLastName,
                ce.title AS eventTitle,
                ce.date AS eventDate,
                jo.title AS jobTitle
             FROM interview_feedback f
             LEFT JOIN candidate c ON f.candidate_id = c.candidate_id
             LEFT JOIN calendar_event ce ON f.calendar_event_id = ce.calendar_event_id
             LEFT JOIN joborder jo ON f.joborder_id = jo.joborder_id
             WHERE f.interviewer_user_id = %s AND f.status = 'pending' AND f.site_id = %s
             ORDER BY ce.date ASC",
            intval($userID),
            $this->_siteID
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Get interview stage summary for a candidate+job pipeline entry.
     * Returns which stages are complete, pending, or not started.
     */
    public function getStageSummary($candidateID, $jobOrderID)
    {
        $stages = array(self::STAGE_L1, self::STAGE_L2, self::STAGE_L3, self::STAGE_HR, self::STAGE_FINAL);
        $summary = array();

        foreach ($stages as $stage)
        {
            $sql = sprintf(
                "SELECT
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'submitted' THEN 1 ELSE 0 END) as submitted,
                    AVG(CASE WHEN overall_rating IS NOT NULL THEN overall_rating ELSE NULL END) as avgRating,
                    GROUP_CONCAT(recommendation) as recommendations
                 FROM interview_feedback
                 WHERE candidate_id = %s AND joborder_id = %s
                 AND interview_stage = %s AND site_id = %s",
                intval($candidateID),
                intval($jobOrderID),
                $this->_db->makeQueryString($stage),
                $this->_siteID
            );

            $rs = $this->_db->getAllAssoc($sql);
            $row = $rs[0];

            $status = 'not_started';
            if ($row['total'] > 0)
            {
                $status = ($row['submitted'] == $row['total']) ? 'completed' : 'in_progress';
            }

            $summary[$stage] = array(
                'stage'       => $stage,
                'status'      => $status,
                'total'       => intval($row['total']),
                'submitted'   => intval($row['submitted']),
                'avgRating'   => $row['avgRating'] !== null ? round(floatval($row['avgRating']), 1) : null,
                'recommendations' => $row['recommendations'] ? explode(',', $row['recommendations']) : array()
            );
        }

        return $summary;
    }

    /**
     * Calculate aggregate interview score for a candidate on a job order.
     */
    public function getAggregateScore($candidateID, $jobOrderID)
    {
        $sql = sprintf(
            "SELECT
                AVG(overall_rating) as avgOverall,
                AVG(technical_rating) as avgTechnical,
                AVG(communication_rating) as avgCommunication,
                AVG(cultural_fit_rating) as avgCulturalFit,
                AVG(problem_solving_rating) as avgProblemSolving,
                COUNT(*) as totalFeedback,
                SUM(CASE WHEN recommendation IN ('strong_hire','hire') THEN 1 ELSE 0 END) as positiveRecs,
                SUM(CASE WHEN recommendation IN ('no_hire','strong_no_hire') THEN 1 ELSE 0 END) as negativeRecs
             FROM interview_feedback
             WHERE candidate_id = %s AND joborder_id = %s
             AND status = 'submitted' AND site_id = %s",
            intval($candidateID),
            intval($jobOrderID),
            $this->_siteID
        );

        $rs = $this->_db->getAllAssoc($sql);
        if (empty($rs)) return false;

        $row = $rs[0];
        return array(
            'avgOverall'       => $row['avgOverall'] !== null ? round(floatval($row['avgOverall']), 1) : null,
            'avgTechnical'     => $row['avgTechnical'] !== null ? round(floatval($row['avgTechnical']), 1) : null,
            'avgCommunication' => $row['avgCommunication'] !== null ? round(floatval($row['avgCommunication']), 1) : null,
            'avgCulturalFit'   => $row['avgCulturalFit'] !== null ? round(floatval($row['avgCulturalFit']), 1) : null,
            'avgProblemSolving'=> $row['avgProblemSolving'] !== null ? round(floatval($row['avgProblemSolving']), 1) : null,
            'totalFeedback'    => intval($row['totalFeedback']),
            'positiveRecs'     => intval($row['positiveRecs']),
            'negativeRecs'     => intval($row['negativeRecs'])
        );
    }

    /**
     * Delete a feedback entry.
     */
    public function delete($feedbackID)
    {
        $sql = sprintf(
            "DELETE FROM interview_feedback WHERE feedback_id = %s AND site_id = %s",
            intval($feedbackID),
            $this->_siteID
        );
        return $this->_db->query($sql);
    }
}

?>
