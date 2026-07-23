<?php
/**
 * Generate Company Job ID automatically
 * Called via AJAX when company is selected
 */

/* This endpoint is fetched directly (not routed through ajax.php?f=...
 * like every other AJAX call in this app), so PHP's working directory is
 * this file's own folder rather than the project root. That broke relative
 * includes inside Companies.php (e.g. ./vendor/autoload.php). Fix the
 * working directory first so all downstream relative includes resolve
 * the same way they do when entered through the normal front controller. */
chdir(__DIR__ . '/..');

include_once('config.php');
include_once('constants.php');
include_once('lib/DatabaseConnection.php');
/* CATSSession (stored in $_SESSION['CATS']) must be defined before
 * session_start() triggers unserialize() on the existing session data. */
include_once('lib/Session.php');
include_once('lib/Companies.php');

session_name(CATS_SESSION_NAME);
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
        "SELECT name AS company_name FROM company WHERE company_id = %d AND site_id = %d",
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

    // Get existing job IDs for this company that start with the abbreviation;
    // the loop below re-validates the numeric suffix and finds the max, so this
    // just needs a portable (MySQL- and PostgreSQL-safe) prefix filter.
    $maxNumSQL = sprintf(
        "SELECT client_job_id FROM joborder WHERE company_id = %d AND site_id = %d AND client_job_id LIKE %s",
        $companyID, $siteID, $db->makeQueryString($abbrev . '%')
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
