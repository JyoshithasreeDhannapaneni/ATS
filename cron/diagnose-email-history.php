<?php
/**
 * One-off diagnostic: dump the most recent email_history rows.
 * Checks whether the careers-portal apply flow's confirmation/notification
 * emails logged for the test application (candidate 63 / job order 10).
 * Invoked directly by deploy.yml; safe to delete once no longer needed.
 */

if (php_sapi_name() !== 'cli')
{
    die("This script is CLI-only.\n");
}

define('LEGACY_ROOT', dirname(__DIR__));

include_once(LEGACY_ROOT . '/config.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');

$db = DatabaseConnection::getInstance();
$rs = $db->getAllAssoc(
    "SELECT email_history_id, recipients, subject, candidate_id, date FROM email_history ORDER BY date DESC LIMIT 15"
);

foreach ($rs as $row)
{
    echo implode(' | ', $row) . PHP_EOL;
}

if (empty($rs))
{
    echo "NO ROWS FOUND in email_history at all" . PHP_EOL;
}
