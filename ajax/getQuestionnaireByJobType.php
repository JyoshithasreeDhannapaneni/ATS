<?php
/**
 * Get questionnaire by job order type
 * Called via AJAX when job order type is selected
 */

include_once('../config.php');
include_once('../constants.php');
include_once('../lib/DatabaseConnection.php');
include_once('../lib/JobOrderTypeQuestionnaires.php');

header('Content-Type: application/json');

$jobOrderType = isset($_POST['jobOrderType']) ? trim($_POST['jobOrderType']) : '';

if (empty($jobOrderType)) {
    echo json_encode(['error' => 'Invalid job order type']);
    exit;
}

try {
    $typeQuestionnaireMapper = new JobOrderTypeQuestionnaires();
    $mapping = $typeQuestionnaireMapper->getMappingForType($jobOrderType);

    if (!$mapping) {
        echo json_encode(['error' => 'Job order type not configured']);
        exit;
    }

    $questionnaireID = $mapping['questionnaireID'];
    $title = $mapping['title'];

    echo json_encode([
        'success' => true,
        'questionnaireID' => $questionnaireID,
        'title' => $title,
        'jobOrderType' => $jobOrderType,
        'message' => $questionnaireID
            ? 'Questionnaire auto-selected for ' . $jobOrderType . ' positions'
            : 'No questionnaire configured for this job type - please select manually'
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
