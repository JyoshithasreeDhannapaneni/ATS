<?php
/*
 * Neutara ATS
 * AJAX: Get analytics data for dashboards
 */

include_once(LEGACY_ROOT . '/lib/Analytics.php');

$interface = new SecureAJAXInterface();
$siteID = $interface->getSiteID();

$analytics = new Analytics($siteID);
$type = isset($_REQUEST['type']) ? trim($_REQUEST['type']) : 'summary';
$periodDays = isset($_REQUEST['period']) ? intval($_REQUEST['period']) : 90;
$jobOrderID = isset($_REQUEST['joborderID']) ? intval($_REQUEST['joborderID']) : null;

$result = array('error' => 0);

switch ($type)
{
    case 'summary':
        $result['data'] = $analytics->getDashboardSummary();
        break;

    case 'funnel':
        $result['data'] = $analytics->getPipelineFunnel($jobOrderID);
        break;

    case 'conversions':
        $result['data'] = $analytics->getConversionRates($periodDays, $jobOrderID);
        break;

    case 'timeToHire':
        $result['data'] = $analytics->getTimeToHire($periodDays);
        break;

    case 'recruiterPerformance':
        $result['data'] = $analytics->getRecruiterPerformance($periodDays);
        break;

    case 'hiringTrend':
        $months = isset($_REQUEST['months']) ? intval($_REQUEST['months']) : 12;
        $result['data'] = $analytics->getHiringTrend($months);
        break;

    case 'sources':
        $result['data'] = $analytics->getSourceEffectiveness($periodDays);
        break;

    case 'all':
        $result['summary'] = $analytics->getDashboardSummary();
        $result['funnel'] = $analytics->getPipelineFunnel($jobOrderID);
        $result['conversions'] = $analytics->getConversionRates($periodDays, $jobOrderID);
        $result['timeToHire'] = $analytics->getTimeToHire($periodDays);
        $result['recruiterPerformance'] = $analytics->getRecruiterPerformance($periodDays);
        $result['hiringTrend'] = $analytics->getHiringTrend(12);
        $result['sources'] = $analytics->getSourceEffectiveness($periodDays);
        break;

    default:
        $result = array('error' => 'Unknown analytics type: ' . htmlspecialchars($type));
}

header('Content-Type: application/json');
echo json_encode($result);
?>
