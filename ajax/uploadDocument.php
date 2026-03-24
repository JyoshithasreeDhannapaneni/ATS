<?php
include_once('../constants.php');
include_once('../config.php');
include_once('../lib/DatabaseConnection.php');
include_once('../lib/CandidateDocuments.php');

header('Content-Type: application/json');

$token = isset($_POST['token']) ? trim($_POST['token']) : '';
$docType = isset($_POST['documentType']) ? trim($_POST['documentType']) : 'other';

if (empty($token))
{
    echo json_encode(array('success' => false, 'error' => 'Missing token'));
    exit;
}

$docs = new CandidateDocuments(1);
$tokenData = $docs->validateToken($token);

if (!$tokenData)
{
    echo json_encode(array('success' => false, 'error' => 'Invalid or expired upload link'));
    exit;
}

if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK)
{
    $errorMessages = array(
        UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload limit',
        UPLOAD_ERR_FORM_SIZE  => 'File exceeds form upload limit',
        UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE    => 'No file was selected',
        UPLOAD_ERR_NO_TMP_DIR => 'Server temporary folder missing',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
    );
    $code = isset($_FILES['document']) ? $_FILES['document']['error'] : UPLOAD_ERR_NO_FILE;
    $msg = isset($errorMessages[$code]) ? $errorMessages[$code] : 'Upload error';
    echo json_encode(array('success' => false, 'error' => $msg));
    exit;
}

$file = $_FILES['document'];
$maxSizeMB = 10;
if ($file['size'] > $maxSizeMB * 1024 * 1024)
{
    echo json_encode(array('success' => false, 'error' => "File size exceeds {$maxSizeMB}MB limit"));
    exit;
}

$allowedExtensions = array(
    'pdf','doc','docx','jpg','jpeg','png','gif','bmp','tif','tiff',
    'xls','xlsx','txt','rtf','odt','ods','zip','rar'
);
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowedExtensions))
{
    echo json_encode(array('success' => false, 'error' => 'File type not allowed: .' . $ext));
    exit;
}

$candidateID = intval($tokenData['candidate_id']);
$tokenID = intval($tokenData['token_id']);
$uploadDir = CandidateDocuments::getUploadDirectory($candidateID);

$storedFilename = time() . '_' . bin2hex(random_bytes(8)) . '.' . $ext;
$targetPath = $uploadDir . '/' . $storedFilename;

if (!move_uploaded_file($file['tmp_name'], $targetPath))
{
    echo json_encode(array('success' => false, 'error' => 'Failed to save file'));
    exit;
}

$fileSizeKB = intval($file['size'] / 1024);
$contentType = $file['type'] ?: 'application/octet-stream';

$documentID = $docs->saveDocument(
    $candidateID, $tokenID, $docType,
    $file['name'], $storedFilename, (string)$candidateID,
    $fileSizeKB, $contentType
);

$docs->incrementUploadCount($tokenID);

$validTypes = CandidateDocuments::getDocumentTypes();
$typeLabel = isset($validTypes[$docType]) ? $validTypes[$docType] : $docType;

echo json_encode(array(
    'success'    => true,
    'documentID' => $documentID,
    'filename'   => $file['name'],
    'type'       => $typeLabel,
    'size'       => $fileSizeKB . ' KB'
));
