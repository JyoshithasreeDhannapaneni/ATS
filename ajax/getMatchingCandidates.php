<?php
/*
 * Neutara ATS
 * AJAX: Get matching candidates for a job order
 * Returns ranked candidates with match scores.
 */

include_once(LEGACY_ROOT . '/lib/CandidateJobMatch.php');

$interface = new SecureAJAXInterface();

if (!isset($_REQUEST['joborderID']))
{
    echo json_encode(array('error' => 'No job order ID specified.'));
    die();
}

$siteID = $interface->getSiteID();
$jobOrderID = intval($_REQUEST['joborderID']);
$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 20;

$matcher = new CandidateJobMatch($siteID);
$results = $matcher->findMatchingCandidates($jobOrderID, $limit);

header('Content-Type: application/json');
echo json_encode(array(
    'error' => 0,
    'jobOrderID' => $jobOrderID,
    'matches' => $results,
    'count' => count($results)
));
?>
