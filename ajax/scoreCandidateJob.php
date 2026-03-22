<?php
/*
 * Neutara ATS
 * AJAX: Score a specific candidate against a specific job order
 * Returns detailed match score with breakdown.
 */

include_once(LEGACY_ROOT . '/lib/CandidateJobMatch.php');

$interface = new SecureAJAXInterface();

if (!isset($_REQUEST['candidateID']) || !isset($_REQUEST['joborderID']))
{
    echo json_encode(array('error' => 'Both candidateID and joborderID are required.'));
    die();
}

$siteID = $interface->getSiteID();
$candidateID = intval($_REQUEST['candidateID']);
$jobOrderID = intval($_REQUEST['joborderID']);

$matcher = new CandidateJobMatch($siteID);
$result = $matcher->scoreCandidate($candidateID, $jobOrderID);

header('Content-Type: application/json');
echo json_encode(array(
    'error' => 0,
    'result' => $result
));
?>
