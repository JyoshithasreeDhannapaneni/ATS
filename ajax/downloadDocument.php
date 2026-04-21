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

// Start session with the correct name (Session class must be loaded first)
@session_name(CATS_SESSION_NAME);
@session_start();

if (!isset($_SESSION['CATS']) || !$_SESSION['CATS']->isLoggedIn())
{
    header('HTTP/1.1 403 Forbidden');
    echo 'Not authenticated';
    exit;
}

$documentID = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($documentID <= 0)
{
    header('HTTP/1.1 400 Bad Request');
    echo 'Invalid document ID';
    exit;
}

$siteID = $_SESSION['CATS']->getSiteID();
$docs = new CandidateDocuments($siteID);
$doc = $docs->getDocument($documentID);

if (!$doc)
{
    header('HTTP/1.1 404 Not Found');
    echo 'Document not found';
    exit;
}

// Use LEGACY_ROOT for consistent path resolution
$filePath = LEGACY_ROOT . '/uploads/documents/' . $doc['directory_name'] . '/' . $doc['stored_filename'];

if (!file_exists($filePath))
{
    header('HTTP/1.1 404 Not Found');
    echo 'File not found on server';
    exit;
}

header('Content-Type: ' . $doc['content_type']);
header('Content-Disposition: inline; filename="' . basename($doc['original_filename']) . '"');
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: private, max-age=3600');

readfile($filePath);
exit;
