<?php
/**
 * One-off cleanup: remove the E2E careers-portal test data created this
 * session - job order 10 ("E2E Careers Portal Test Role") and candidates
 * 62-66 (all created via test applications to that job order).
 *
 * Mirrors Candidates::delete() / JobOrders::delete(), but without the
 * site_id constraint those methods apply - career-portal applicants get
 * created under the job order's own site_id, which does not match the
 * site_id of an admin session under CATS_ADMIN_SITE, so the normal UI
 * Delete buttons silently no-op for these specific records.
 *
 * Safe to delete this script once run.
 */

if (php_sapi_name() !== 'cli')
{
    die("This script is CLI-only.\n");
}

define('LEGACY_ROOT', dirname(__DIR__));

include_once(LEGACY_ROOT . '/config.php');
include_once(LEGACY_ROOT . '/constants.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');
include_once(LEGACY_ROOT . '/lib/CATSUtility.php');
include_once(LEGACY_ROOT . '/lib/Attachments.php');

$db = DatabaseConnection::getInstance();

$candidateIDs = array(62, 63, 64, 65, 66);
$jobOrderID = 10;

/* Attachments::getAll()/delete() are site-scoped, so look up each record's
 * actual site_id rather than assume - this is exactly the mismatch that
 * broke the normal UI Delete buttons for these rows. */
foreach ($candidateIDs as $candidateID)
{
    $siteRS = $db->getAllAssoc(sprintf("SELECT site_id FROM candidate WHERE candidate_id = %d", $candidateID));
    if (empty($siteRS)) { continue; }
    $attachments = new Attachments(intval($siteRS[0]['site_id']));
    $attachmentsRS = $attachments->getAll(DATA_ITEM_CANDIDATE, $candidateID);
    foreach ($attachmentsRS as $row)
    {
        $attachments->delete($row['attachmentID']);
    }
}
$jobSiteRS = $db->getAllAssoc(sprintf("SELECT site_id FROM joborder WHERE joborder_id = %d", $jobOrderID));
if (!empty($jobSiteRS))
{
    $jobAttachments = new Attachments(intval($jobSiteRS[0]['site_id']));
    $jobOrderAttachmentsRS = $jobAttachments->getAll(DATA_ITEM_JOBORDER, $jobOrderID);
    foreach ($jobOrderAttachmentsRS as $row)
    {
        $jobAttachments->delete($row['attachmentID']);
    }
}

foreach ($candidateIDs as $candidateID)
{
    $db->query(sprintf("DELETE FROM activity WHERE data_item_id = %d AND data_item_type = %d", $candidateID, DATA_ITEM_CANDIDATE));
    $db->query(sprintf("DELETE FROM candidate_joborder_status_history WHERE candidate_id = %d", $candidateID));
    $db->query(sprintf("DELETE FROM candidate_joborder WHERE candidate_id = %d", $candidateID));
    $db->query(sprintf("DELETE FROM saved_list_entry WHERE data_item_id = %d AND data_item_type = %d", $candidateID, DATA_ITEM_CANDIDATE));
    $db->query(sprintf("DELETE FROM candidate_duplicates WHERE old_candidate_id = %d OR new_candidate_id = %d", $candidateID, $candidateID));
    $db->query(sprintf("DELETE FROM candidate WHERE candidate_id = %d", $candidateID));
    echo "Cleaned up candidate {$candidateID}\n";
}

$db->query(sprintf("DELETE FROM activity WHERE data_item_id = %d AND data_item_type = %d", $jobOrderID, DATA_ITEM_JOBORDER));
$db->query(sprintf("DELETE FROM candidate_joborder_status_history WHERE joborder_id = %d", $jobOrderID));
$db->query(sprintf("DELETE FROM candidate_joborder WHERE joborder_id = %d", $jobOrderID));
$db->query(sprintf("DELETE FROM saved_list_entry WHERE data_item_id = %d AND data_item_type = %d", $jobOrderID, DATA_ITEM_JOBORDER));
$db->query(sprintf("DELETE FROM joborder WHERE joborder_id = %d", $jobOrderID));
echo "Cleaned up job order {$jobOrderID}\n";

echo "Done.\n";
