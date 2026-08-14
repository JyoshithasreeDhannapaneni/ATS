<?php
/*
 * Neutara ATS
 * AJAX: Get the "Recent Hires" placement count for a given trailing window,
 * for the Dashboard's Recent Hires stat tile period selector.
 */

include_once(LEGACY_ROOT . '/lib/Dashboard.php');

$interface = new SecureAJAXInterface();

$siteID = $interface->getSiteID();
$period = isset($_REQUEST['period']) ? $_REQUEST['period'] : 'month';

$periodDaysMap = array(
    'week'  => 7,
    'month' => 30,
    'year'  => 365,
);
$periodDays = isset($periodDaysMap[$period]) ? $periodDaysMap[$period] : 30;

$dashboard = new Dashboard($siteID);
$count = $dashboard->getPlacementsCount($periodDays);

header('Content-Type: application/json');
echo json_encode(array('error' => 0, 'count' => $count));
?>
