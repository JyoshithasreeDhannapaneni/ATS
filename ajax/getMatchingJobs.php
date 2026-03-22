<?php
/*
 * Neutara ATS
 * AJAX: Get matching job orders for a candidate
 * Returns ranked jobs with match scores.
 */

include_once(LEGACY_ROOT . '/lib/CandidateJobMatch.php');

$interface = new SecureAJAXInterface();

if (!isset($_REQUEST['candidateID']))
{
    echo json_encode(array('error' => 'No candidate ID specified.'));
    die();
}

$siteID = $interface->getSiteID();
$candidateID = intval($_REQUEST['candidateID']);
$limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 20;

$matcher = new CandidateJobMatch($siteID);
$results = $matcher->findMatchingJobs($candidateID, $limit);

header('Content-Type: application/json');
echo json_encode(array(
    'error' => 0,
    'candidateID' => $candidateID,
    'matches' => $results,
    'count' => count($results)
));
?>
