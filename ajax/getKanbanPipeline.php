<?php
/*
 * Neutara ATS
 * AJAX Kanban Pipeline Board Data
 * Returns pipeline candidates grouped by status for a job order.
 */

include_once(LEGACY_ROOT . '/lib/DataGrid.php');
include_once(LEGACY_ROOT . '/lib/Pipelines.php');
include_once(LEGACY_ROOT . '/lib/JobOrders.php');
include_once(LEGACY_ROOT . '/lib/Candidates.php');

$interface = new SecureAJAXInterface();

if (!isset($_REQUEST['joborderID']))
{
    echo json_encode(['error' => 'Invalid input.']);
    die();
}

$siteID = $interface->getSiteID();
$jobOrderID = intval($_REQUEST['joborderID']);

$pipelines = new Pipelines($siteID);
$statuses = $pipelines->getStatusesForPicking();
$pipelineRS = $pipelines->getJobOrderPipeline($jobOrderID);

// Group candidates by status
$columns = [];
foreach ($statuses as $status)
{
    $columns[$status['statusID']] = [
        'statusID' => $status['statusID'],
        'status' => $status['status'],
        'candidates' => []
    ];
}

foreach ($pipelineRS as $row)
{
    $statusID = 0;
    // Match status text to ID
    foreach ($statuses as $status)
    {
        if ($status['status'] === $row['status'])
        {
            $statusID = $status['statusID'];
            break;
        }
    }

    if (!isset($columns[$statusID]))
    {
        continue;
    }

    $columns[$statusID]['candidates'][] = [
        'candidateID' => $row['candidateID'],
        'candidateJobOrderID' => $row['candidateJobOrderID'],
        'firstName' => $row['firstName'],
        'lastName' => $row['lastName'],
        'email' => $row['candidateEmail'] ?? '',
        'state' => $row['state'] ?? '',
        'status' => $row['status'],
        'ratingValue' => $row['ratingValue'],
        'dateCreated' => $row['dateCreated'],
        'isHot' => $row['isHotCandidate'] ?? 0,
        'hasAttachment' => $row['attachmentPresent'] ?? 0,
        'ownerName' => trim(($row['ownerFirstName'] ?? '') . ' ' . ($row['ownerLastName'] ?? '')),
        'addedBy' => trim(($row['addedByFirstName'] ?? '') . ' ' . ($row['addedByLastName'] ?? ''))
    ];
}

header('Content-Type: application/json');
echo json_encode([
    'error' => 0,
    'jobOrderID' => $jobOrderID,
    'columns' => array_values($columns)
]);
?>
