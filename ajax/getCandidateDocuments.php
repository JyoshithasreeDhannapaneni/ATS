<?php
// Define LEGACY_ROOT for ajax folder context
if (!defined('LEGACY_ROOT')) {
    define('LEGACY_ROOT', realpath(dirname(__FILE__) . '/..'));
}
include_once(LEGACY_ROOT . '/constants.php');
include_once(LEGACY_ROOT . '/config.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');
include_once(LEGACY_ROOT . '/lib/Session.php');
include_once(LEGACY_ROOT . '/lib/CandidateDocuments.php');

header('Content-Type: application/json');

// Start session with the correct name (Session class must be loaded first)
@session_name(CATS_SESSION_NAME);
@session_start();

if (!isset($_SESSION['CATS']) || !$_SESSION['CATS']->isLoggedIn())
{
    echo json_encode(array('success' => false, 'error' => 'Not authenticated'));
    exit;
}

$candidateID = isset($_GET['candidateID']) ? intval($_GET['candidateID']) : 0;

if ($candidateID <= 0)
{
    echo json_encode(array('success' => false, 'error' => 'Invalid candidate ID'));
    exit;
}

$siteID = $_SESSION['CATS']->getSiteID();
$docs = new CandidateDocuments($siteID);

$documents = $docs->getDocumentsForCandidate($candidateID);
$types = CandidateDocuments::getDocumentTypes();

foreach ($documents as &$doc)
{
    $doc['typeLabel'] = isset($types[$doc['document_type']]) ? $types[$doc['document_type']] : $doc['document_type'];
}

echo json_encode(array(
    'success'   => true,
    'documents' => $documents
));
