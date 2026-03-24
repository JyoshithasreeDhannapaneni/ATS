<?php
/**
 * Generate Company Job ID automatically
 * Called via AJAX when company is selected
 */

include_once('../config.php');
include_once('../constants.php');
include_once('../lib/DatabaseConnection.php');
include_once('../lib/Companies.php');

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

    // Get the highest number for this company's job orders
    $maxIDSQL = sprintf(
        "SELECT job_order_id FROM job_order
         WHERE company_id = %d AND site_id = %d
         ORDER BY job_order_id DESC LIMIT 1",
        $companyID,
        $siteID
    );

    $maxIDResult = $db->getAllAssoc($maxIDSQL);

    // Extract the number from existing company job IDs or start from 1
    $nextNumber = 1;
    if (!empty($maxIDResult)) {
        $lastJobID = $maxIDResult[0]['job_order_id'];
        // Try to extract numeric suffix from existing company job IDs
        $jobIDSQL = sprintf(
            "SELECT company_job_id FROM job_order
             WHERE company_id = %d AND site_id = %d
             AND company_job_id IS NOT NULL
             AND company_job_id != ''
             ORDER BY job_order_id DESC LIMIT 5",
            $companyID,
            $siteID
        );

        $jobIDResults = $db->getAllAssoc($jobIDSQL);
        $maxNum = 0;

        foreach ($jobIDResults as $row) {
            if (!empty($row['company_job_id'])) {
                // Extract number from pattern like "NT1", "NT2", etc.
                preg_match('/(\d+)$/', $row['company_job_id'], $matches);
                if (!empty($matches[1])) {
                    $num = intval($matches[1]);
                    if ($num > $maxNum) {
                        $maxNum = $num;
                    }
                }
            }
        }

        $nextNumber = $maxNum + 1;
    }

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
