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
$expiryDays  = isset($_GET['expiryDays']) ? intval($_GET['expiryDays']) : 7;

if ($candidateID <= 0)
{
    echo json_encode(array('success' => false, 'error' => 'Invalid candidate ID'));
    exit;
}

if ($expiryDays < 1) $expiryDays = 7;
if ($expiryDays > 30) $expiryDays = 30;

$siteID = $_SESSION['CATS']->getSiteID();
$userID = $_SESSION['CATS']->getUserID();
$docs = new CandidateDocuments($siteID);

$existingToken = $docs->getActiveTokenForCandidate($candidateID);
if ($existingToken)
{
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $baseURL = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME']));
    $uploadURL = rtrim($baseURL, '/') . '/candidate-upload.php?token=' . $existingToken;

    echo json_encode(array(
        'success' => true,
        'token'   => $existingToken,
        'url'     => $uploadURL,
        'reused'  => true
    ));
    exit;
}

$token = $docs->generateToken($candidateID, $userID, $expiryDays);

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$baseURL = $protocol . '://' . $_SERVER['HTTP_HOST'] . dirname(dirname($_SERVER['SCRIPT_NAME']));
$uploadURL = rtrim($baseURL, '/') . '/candidate-upload.php?token=' . $token;

echo json_encode(array(
    'success' => true,
    'token'   => $token,
    'url'     => $uploadURL,
    'reused'  => false
));
