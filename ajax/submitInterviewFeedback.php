<?php
/*
 * Neutara ATS
 * AJAX: Submit interview feedback
 */

include_once(LEGACY_ROOT . '/lib/InterviewFeedback.php');

$interface = new SecureAJAXInterface();
$siteID = $interface->getSiteID();

if (!isset($_REQUEST['feedbackID']))
{
    echo json_encode(array('error' => 'Missing feedbackID.'));
    die();
}

$feedbackID = intval($_REQUEST['feedbackID']);

$data = array(
    'overallRating'       => isset($_REQUEST['overallRating']) ? intval($_REQUEST['overallRating']) : null,
    'technicalRating'     => isset($_REQUEST['technicalRating']) ? intval($_REQUEST['technicalRating']) : null,
    'communicationRating' => isset($_REQUEST['communicationRating']) ? intval($_REQUEST['communicationRating']) : null,
    'culturalFitRating'   => isset($_REQUEST['culturalFitRating']) ? intval($_REQUEST['culturalFitRating']) : null,
    'problemSolvingRating'=> isset($_REQUEST['problemSolvingRating']) ? intval($_REQUEST['problemSolvingRating']) : null,
    'strengths'           => isset($_REQUEST['strengths']) ? trim($_REQUEST['strengths']) : '',
    'weaknesses'          => isset($_REQUEST['weaknesses']) ? trim($_REQUEST['weaknesses']) : '',
    'notes'               => isset($_REQUEST['notes']) ? trim($_REQUEST['notes']) : '',
    'recommendation'      => isset($_REQUEST['recommendation']) ? trim($_REQUEST['recommendation']) : ''
);

$feedback = new InterviewFeedback($siteID);
$feedback->submitFeedback($feedbackID, $data);

header('Content-Type: application/json');
echo json_encode(array('error' => 0, 'message' => 'Feedback submitted successfully.'));
?>
