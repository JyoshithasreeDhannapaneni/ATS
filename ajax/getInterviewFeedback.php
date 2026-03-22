<?php
/*
 * Neutara ATS
 * AJAX: Get interview feedback and stage summary
 */

include_once(LEGACY_ROOT . '/lib/InterviewFeedback.php');

$interface = new SecureAJAXInterface();
$siteID = $interface->getSiteID();

$feedback = new InterviewFeedback($siteID);

// Get feedback for a specific candidate + job order
if (isset($_REQUEST['candidateID']) && isset($_REQUEST['joborderID']))
{
    $candidateID = intval($_REQUEST['candidateID']);
    $jobOrderID = intval($_REQUEST['joborderID']);

    $feedbackList = $feedback->getFeedbackByJobCandidate($jobOrderID, $candidateID);
    $stageSummary = $feedback->getStageSummary($candidateID, $jobOrderID);
    $aggregate = $feedback->getAggregateScore($candidateID, $jobOrderID);

    header('Content-Type: application/json');
    echo json_encode(array(
        'error' => 0,
        'feedback' => $feedbackList,
        'stages' => $stageSummary,
        'aggregate' => $aggregate
    ));
    die();
}

// Get pending feedback for current user
if (isset($_REQUEST['pending']) && $_REQUEST['pending'] == '1')
{
    $userID = $_SESSION['CATS']->getUserID();
    $pending = $feedback->getPendingForUser($userID);

    header('Content-Type: application/json');
    echo json_encode(array(
        'error' => 0,
        'pending' => $pending,
        'count' => count($pending)
    ));
    die();
}

// Get single feedback entry
if (isset($_REQUEST['feedbackID']))
{
    $entry = $feedback->get(intval($_REQUEST['feedbackID']));

    header('Content-Type: application/json');
    echo json_encode(array(
        'error' => 0,
        'feedback' => $entry
    ));
    die();
}

echo json_encode(array('error' => 'Missing required parameters.'));
?>
