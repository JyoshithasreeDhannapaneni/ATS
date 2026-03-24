<?php
include_once('../constants.php');
include_once('../config.php');
include_once('../lib/DatabaseConnection.php');
include_once('../lib/CandidateDocuments.php');

header('Content-Type: application/json');

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
