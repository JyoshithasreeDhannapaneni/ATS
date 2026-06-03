<?php
/**
 * Generate Company Job ID automatically
 * Called via AJAX when company is selected
 */

include_once('../config.php');
include_once('../constants.php');
include_once('../lib/DatabaseConnection.php');
include_once('../lib/Companies.php');

session_start();
if (!isset($_SESSION['CATS']) || !$_SESSION['CATS']->isLoggedIn()) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

header('Content-Type: application/json');

$companyID = isset($_POST['companyID']) ? intval($_POST['companyID']) : 0;
$siteID = isset($_POST['siteID']) ? intval($_POST['siteID']) : 1;

if ($companyID <= 0) {
    echo json_encode(['error' => 'Invalid company ID']);
    exit;
}

try {
    $db = DatabaseConnection::getInstance();
    $companies = new Companies($siteID);

    // Get company details
    $companySQL = sprintf(
        "SELECT company_name FROM company WHERE company_id = %d AND site_id = %d",
        $companyID,
        $siteID
    );
    $companyResult = $db->getAllAssoc($companySQL);

    if (empty($companyResult)) {
        echo json_encode(['error' => 'Company not found']);
        exit;
    }

    $companyName = $companyResult[0]['company_name'];

    // Generate abbreviation from company name (first letters of words, max 2-3 chars)
    $abbrev = '';
    $words = explode(' ', $companyName);
    foreach ($words as $word) {
        if (!empty($word) && strlen($abbrev) < 3) {
            $abbrev .= strtoupper(substr($word, 0, 1));
        }
    }

    if (empty($abbrev)) {
        $abbrev = strtoupper(substr($companyName, 0, 2));
    }

    // Get the highest numbered job ID for this company+abbrev combination
    $maxNumSQL = sprintf(
        "SELECT client_job_id FROM joborder WHERE company_id = %d AND site_id = %d AND client_job_id REGEXP '^[A-Z]+[0-9]+$' ORDER BY CAST(SUBSTRING(client_job_id, %d) AS UNSIGNED) DESC LIMIT 10",
        $companyID, $siteID, strlen($abbrev) + 1
    );

    $maxNumResults = $db->getAllAssoc($maxNumSQL);

    // Find the highest number matching the current abbrev prefix
    $nextNumber = 1;
    $maxNum = 0;
    foreach ($maxNumResults as $row) {
        if (!empty($row['client_job_id']) && strpos($row['client_job_id'], $abbrev) === 0) {
            $suffix = substr($row['client_job_id'], strlen($abbrev));
            if (ctype_digit($suffix)) {
                $num = intval($suffix);
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
    }
    $nextNumber = $maxNum + 1;

    // Generate the Company Job ID
    $generatedJobID = $abbrev . $nextNumber;

    echo json_encode([
        'success' => true,
        'companyJobID' => $generatedJobID,
        'abbreviation' => $abbrev,
        'nextNumber' => $nextNumber,
        'companyName' => $companyName
    ]);

} catch (Exception $e) {
    echo json_encode(['error' => 'Error: ' . $e->getMessage()]);
}
?>
