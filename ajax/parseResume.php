<?php
/*
 * Neutara ATS
 * AJAX Resume Parsing Endpoint
 *
 * Accepts resume text and returns parsed structured data.
 * Used by the candidate add form for auto-fill.
 */

if (!defined('LEGACY_ROOT')) {
    define('LEGACY_ROOT', realpath(dirname(__FILE__) . '/..'));
}
include_once(LEGACY_ROOT . '/constants.php');
include_once(LEGACY_ROOT . '/config.php');

include_once(LEGACY_ROOT . '/lib/LocalParseUtility.php');

$interface = new SecureAJAXInterface();

if (!isset($_REQUEST['resumeText']) || trim($_REQUEST['resumeText']) === '')
{
    echo json_encode(array('error' => 'No resume text provided.'));
    die();
}

$resumeText = trim($_REQUEST['resumeText']);

$parser = new LocalParseUtility();
$result = $parser->parse($resumeText);
$confidence = $parser->getConfidenceScores($result);
$yearsExperience = $parser->estimateYearsOfExperience($resumeText);

header('Content-Type: application/json');
echo json_encode(array(
    'error' => 0,
    'parsed' => $result,
    'confidence' => $confidence,
    'yearsExperience' => $yearsExperience
));
?>
