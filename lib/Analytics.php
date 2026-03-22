<?php
/**
 * Neutara ATS
 * Analytics & Reporting Engine
 *
 * Provides hiring funnel metrics, conversion rates, time-to-hire analytics,
 * and recruiter performance data.
 */

class Analytics
{
    private $_db;
    private $_siteID;

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
    }

    /**
     * Get pipeline funnel metrics — how many candidates are at each stage.
     */
    public function getPipelineFunnel($jobOrderID = null)
    {
        $jobFilter = '';
        if ($jobOrderID !== null)
        {
            $jobFilter = sprintf(' AND cjo.joborder_id = %s', intval($jobOrderID));
        }

        $sql = sprintf(
            "SELECT
                cjs.short_description AS status,
                cjs.candidate_joborder_status_id AS statusID,
                COUNT(cjo.candidate_joborder_id) AS count
             FROM candidate_joborder_status cjs
             LEFT JOIN candidate_joborder cjo
                ON cjo.status = cjs.candidate_joborder_status_id
                AND cjo.site_id = %s %s
             WHERE cjs.is_enabled = 1
             GROUP BY cjs.candidate_joborder_status_id, cjs.short_description
             ORDER BY cjs.candidate_joborder_status_id ASC",
            $this->_siteID,
            $jobFilter
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Get conversion rates between pipeline stages.
     */
    public function getConversionRates($periodDays = 90, $jobOrderID = null)
    {
        $jobFilter = '';
        if ($jobOrderID !== null)
        {
            $jobFilter = sprintf(' AND joborder_id = %s', intval($jobOrderID));
        }

        $sql = sprintf(
            "SELECT
                status_to AS statusID,
                COUNT(*) AS transitions
             FROM candidate_joborder_status_history
             WHERE site_id = %s
             AND date >= DATE_SUB(NOW(), INTERVAL %d DAY)
             %s
             GROUP BY status_to
             ORDER BY status_to ASC",
            $this->_siteID,
            intval($periodDays),
            $jobFilter
        );

        $rs = $this->_db->getAllAssoc($sql);

        // Calculate conversion rates between consecutive stages
        $stages = array(100, 200, 250, 300, 400, 500, 600, 800);
        $stageNames = array(
            100 => 'No Contact', 200 => 'Contacted', 250 => 'Responded',
            300 => 'Qualifying', 400 => 'Submitted', 500 => 'Interviewing',
            600 => 'Offered', 800 => 'Placed'
        );

        $counts = array();
        foreach ($rs as $row)
        {
            $counts[$row['statusID']] = intval($row['transitions']);
        }

        $conversions = array();
        for ($i = 0; $i < count($stages) - 1; $i++)
        {
            $from = $stages[$i];
            $to = $stages[$i + 1];
            $fromCount = $counts[$from] ?? 0;
            $toCount = $counts[$to] ?? 0;

            $conversions[] = array(
                'from' => $stageNames[$from],
                'to'   => $stageNames[$to],
                'fromCount' => $fromCount,
                'toCount'   => $toCount,
                'rate' => $fromCount > 0 ? round(($toCount / $fromCount) * 100, 1) : 0
            );
        }

        return $conversions;
    }

    /**
     * Get time-to-hire metrics.
     */
    public function getTimeToHire($periodDays = 180)
    {
        // Average days from first pipeline entry to Placed (800)
        $sql = sprintf(
            "SELECT
                AVG(DATEDIFF(placed.date, created.date_created)) AS avgDays,
                MIN(DATEDIFF(placed.date, created.date_created)) AS minDays,
                MAX(DATEDIFF(placed.date, created.date_created)) AS maxDays,
                COUNT(*) AS totalPlacements
             FROM candidate_joborder created
             INNER JOIN candidate_joborder_status_history placed
                ON placed.candidate_id = created.candidate_id
                AND placed.joborder_id = created.joborder_id
                AND placed.status_to = 800
                AND placed.site_id = created.site_id
             WHERE created.site_id = %s
             AND placed.date >= DATE_SUB(NOW(), INTERVAL %d DAY)",
            $this->_siteID,
            intval($periodDays)
        );

        $rs = $this->_db->getAllAssoc($sql);
        if (empty($rs) || $rs[0]['totalPlacements'] == 0)
        {
            return array(
                'avgDays' => 0,
                'minDays' => 0,
                'maxDays' => 0,
                'totalPlacements' => 0
            );
        }

        return array(
            'avgDays' => round(floatval($rs[0]['avgDays']), 1),
            'minDays' => intval($rs[0]['minDays']),
            'maxDays' => intval($rs[0]['maxDays']),
            'totalPlacements' => intval($rs[0]['totalPlacements'])
        );
    }

    /**
     * Get recruiter performance metrics.
     */
    public function getRecruiterPerformance($periodDays = 90)
    {
        $sql = sprintf(
            "SELECT
                u.user_id AS userID,
                u.first_name AS firstName,
                u.last_name AS lastName,
                COUNT(DISTINCT cjo.candidate_id) AS candidatesAdded,
                SUM(CASE WHEN cjo.status >= 400 THEN 1 ELSE 0 END) AS submitted,
                SUM(CASE WHEN cjo.status >= 500 THEN 1 ELSE 0 END) AS interviewing,
                SUM(CASE WHEN cjo.status = 800 THEN 1 ELSE 0 END) AS placed
             FROM user u
             LEFT JOIN candidate_joborder cjo
                ON cjo.added_by = u.user_id
                AND cjo.site_id = %s
                AND cjo.date_created >= DATE_SUB(NOW(), INTERVAL %d DAY)
             WHERE u.site_id = %s AND u.is_demo = 0
             GROUP BY u.user_id, u.first_name, u.last_name
             HAVING candidatesAdded > 0
             ORDER BY placed DESC, submitted DESC",
            $this->_siteID,
            intval($periodDays),
            $this->_siteID
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Get hiring trend over time (placements per month).
     */
    public function getHiringTrend($months = 12)
    {
        $sql = sprintf(
            "SELECT
                DATE_FORMAT(h.date, '%%Y-%%m') AS month,
                COUNT(*) AS placements
             FROM candidate_joborder_status_history h
             WHERE h.site_id = %s
             AND h.status_to = 800
             AND h.date >= DATE_SUB(NOW(), INTERVAL %d MONTH)
             GROUP BY DATE_FORMAT(h.date, '%%Y-%%m')
             ORDER BY month ASC",
            $this->_siteID,
            intval($months)
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Get candidate source effectiveness.
     */
    public function getSourceEffectiveness($periodDays = 180)
    {
        $sql = sprintf(
            "SELECT
                COALESCE(cs.name, 'Unknown') AS source,
                COUNT(DISTINCT c.candidate_id) AS totalCandidates,
                SUM(CASE WHEN cjo.status >= 500 THEN 1 ELSE 0 END) AS interviewed,
                SUM(CASE WHEN cjo.status = 800 THEN 1 ELSE 0 END) AS placed
             FROM candidate c
             LEFT JOIN candidate_source cs ON c.source = cs.source_id
             LEFT JOIN candidate_joborder cjo
                ON cjo.candidate_id = c.candidate_id AND cjo.site_id = c.site_id
             WHERE c.site_id = %s
             AND c.date_created >= DATE_SUB(NOW(), INTERVAL %d DAY)
             GROUP BY COALESCE(cs.name, 'Unknown')
             ORDER BY totalCandidates DESC",
            $this->_siteID,
            intval($periodDays)
        );

        return $this->_db->getAllAssoc($sql);
    }

    /**
     * Get summary dashboard stats.
     */
    public function getDashboardSummary()
    {
        $summary = array();

        // Active candidates
        $sql = sprintf(
            "SELECT COUNT(*) AS cnt FROM candidate WHERE site_id = %s AND is_active = 1 AND is_admin_hidden = 0",
            $this->_siteID
        );
        $rs = $this->_db->getAllAssoc($sql);
        $summary['activeCandidates'] = intval($rs[0]['cnt']);

        // Active job orders
        $sql = sprintf(
            "SELECT COUNT(*) AS cnt FROM joborder WHERE site_id = %s AND status = 'Active' AND is_admin_hidden = 0",
            $this->_siteID
        );
        $rs = $this->_db->getAllAssoc($sql);
        $summary['activeJobOrders'] = intval($rs[0]['cnt']);

        // Open positions (total openings)
        $sql = sprintf(
            "SELECT COALESCE(SUM(openings_available), 0) AS cnt FROM joborder WHERE site_id = %s AND status = 'Active'",
            $this->_siteID
        );
        $rs = $this->_db->getAllAssoc($sql);
        $summary['openPositions'] = intval($rs[0]['cnt']);

        // Placements this month
        $sql = sprintf(
            "SELECT COUNT(*) AS cnt FROM candidate_joborder_status_history
             WHERE site_id = %s AND status_to = 800
             AND date >= DATE_FORMAT(NOW(), '%%Y-%%m-01')",
            $this->_siteID
        );
        $rs = $this->_db->getAllAssoc($sql);
        $summary['placementsThisMonth'] = intval($rs[0]['cnt']);

        // Interviews scheduled (upcoming)
        $sql = sprintf(
            "SELECT COUNT(*) AS cnt FROM calendar_event
             WHERE site_id = %s AND type = 400 AND date >= NOW()",
            $this->_siteID
        );
        $rs = $this->_db->getAllAssoc($sql);
        $summary['upcomingInterviews'] = intval($rs[0]['cnt']);

        return $summary;
    }
}

?>
