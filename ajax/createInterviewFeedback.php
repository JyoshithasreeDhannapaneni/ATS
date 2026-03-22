<?php
/*
 * Neutara ATS
 * AJAX: Create a new interview feedback entry
 */

include_once(LEGACY_ROOT . '/lib/InterviewFeedback.php');

$interface = new SecureAJAXInterface();
$siteID = $interface->getSiteID();

if (!isset($_REQUEST['calendarEventID']) || !isset($_REQUEST['candidateID']) || !isset($_REQUEST['interviewerUserID']))
{
    echo json_encode(array('error' => 'Missing required fields: calendarEventID, candidateID, interviewerUserID.'));
    die();
}

$calendarEventID = intval($_REQUEST['calendarEventID']);
$candidateID = intval($_REQUEST['candidateID']);
$jobOrderID = isset($_REQUEST['joborderID']) ? intval($_REQUEST['joborderID']) : 0;
$interviewerUserID = intval($_REQUEST['interviewerUserID']);
$stage = isset($_REQUEST['stage']) ? trim($_REQUEST['stage']) : 'L1';

$feedback = new InterviewFeedback($siteID);
$feedbackID = $feedback->create($calendarEventID, $candidateID, $jobOrderID, $interviewerUserID, $stage);

header('Content-Type: application/json');
echo json_encode(array(
    'error' => 0,
    'feedbackID' => $feedbackID,
    'message' => 'Interview feedback entry created.'
));
?>
