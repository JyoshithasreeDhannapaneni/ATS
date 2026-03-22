<?php
/*
 * Neutara ATS
 * AJAX Job Orders List
 * Returns active job orders for pipeline board dropdown.
 */

include_once(LEGACY_ROOT . '/lib/DataGrid.php');
include_once(LEGACY_ROOT . '/lib/JobOrders.php');

$interface = new SecureAJAXInterface();
$siteID = $interface->getSiteID();

$jobOrders = new JobOrders($siteID);
$rs = $jobOrders->getAll(JOBORDERS_STATUS_ALL);

$list = [];
foreach ($rs as $row)
{
    $list[] = [
        'jobOrderID' => $row['jobOrderID'],
        'title' => $row['title'],
        'companyName' => $row['companyName'] ?? '',
        'recruiterName' => trim(($row['recruiterFirstName'] ?? '') . ' ' . ($row['recruiterLastName'] ?? '')),
        'openings' => $row['openings'] ?? 0,
        'pipeline' => $row['pipeline'] ?? 0,
        'dateCreated' => $row['dateCreated'] ?? ''
    ];
}

header('Content-Type: application/json');
echo json_encode(['error' => 0, 'jobOrders' => $list]);
?>
