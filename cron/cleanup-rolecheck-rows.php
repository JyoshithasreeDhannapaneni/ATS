<?php
/**
 * One-off cleanup: orphaned calendar_event/interview_feedback rows left
 * over from the role-verification E2E pass (job order 12 and candidates
 * 75/76 were already deleted through the normal UI, but calendar_event
 * and interview_feedback have no admin UI to browse/delete arbitrary
 * rows by ID).
 *
 * Safe to delete this script once run.
 */

if (php_sapi_name() !== 'cli')
{
    die("This script is CLI-only.\n");
}

define('LEGACY_ROOT', dirname(__DIR__));

include_once(LEGACY_ROOT . '/config.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');

$db = DatabaseConnection::getInstance();

$db->query("DELETE FROM interview_feedback WHERE calendar_event_id = 4");
echo "Deleted interview_feedback rows for calendar_event_id 4\n";

$db->query("DELETE FROM calendar_event WHERE calendar_event_id = 4");
echo "Deleted calendar_event 4\n";

echo "Done.\n";
