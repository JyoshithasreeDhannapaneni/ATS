<?php
/*
 * CATS
 * AJAX Add Candidate to Pipeline Interface
 *
 * Adds a candidate to a job order pipeline with an optional initial status.
 */

include_once(LEGACY_ROOT . '/lib/Pipelines.php');
include_once(LEGACY_ROOT . '/lib/ActivityEntries.php');

$interface = new SecureAJAXInterface();

if ($_SESSION['CATS']->getAccessLevel('pipelines.addActivityChangeStatus') < ACCESS_LEVEL_EDIT)
{
    $interface->outputXMLErrorPage(-1, ERROR_NO_PERMISSION);
    die();
}

if (!$interface->isRequiredIDValid('candidateID'))
{
    $interface->outputXMLErrorPage(-1, 'Invalid candidate ID.');
    die();
}

if (!$interface->isRequiredIDValid('jobOrderID'))
{
    $interface->outputXMLErrorPage(-1, 'Invalid job order ID.');
    die();
}

$siteID = $interface->getSiteID();

$candidateID = $_REQUEST['candidateID'];
$jobOrderID  = $_REQUEST['jobOrderID'];
$statusID    = isset($_REQUEST['statusID']) ? intval($_REQUEST['statusID']) : PIPELINE_STATUS_NOCONTACT; // Default to "No Contact"

$pipelines = new Pipelines($siteID);

// Check if candidate is already in this pipeline
$existingPipeline = $pipelines->getCandidatePipeline($candidateID);
foreach ($existingPipeline as $pipeline)
{
    if ($pipeline['jobOrderID'] == $jobOrderID)
    {
        $interface->outputXMLErrorPage(-1, 'Candidate is already in this pipeline.');
        die();
    }
}

// Check 3-month cooling period
if ($pipelines->isInCoolingPeriod($candidateID, $jobOrderID))
{
    $interface->outputXMLErrorPage(-1, 'Candidate is within the 3-month cooling period for this job order. They can reapply after 90 days from their last application.');
    die();
}

// Add candidate to pipeline
$result = $pipelines->add($candidateID, $jobOrderID, $_SESSION['CATS']->getUserID());

if (!$result)
{
    $interface->outputXMLErrorPage(-1, 'Failed to add candidate to pipeline.');
    die();
}

// If a custom status was provided (not the default), update it
if ($statusID != PIPELINE_STATUS_NOCONTACT)
{
    $pipelines->setStatus($candidateID, $jobOrderID, $statusID, '', '');
}

// Get the status description for activity logging
$statuses = $pipelines->getStatuses();
$statusDescription = 'No Contact';
foreach ($statuses as $status)
{
    if ($status['statusID'] == $statusID)
    {
        $statusDescription = $status['status'];
        break;
    }
}

// Add activity entry
$activityEntries = new ActivityEntries($siteID);
$activityTypeID = 400; // "Other" type
$activityTypes = $activityEntries->getTypes();
foreach ($activityTypes as $type)
{
    if (strtolower($type['type']) == 'other' || strtolower($type['type']) == 'status change')
    {
        $activityTypeID = $type['typeID'];
        break;
    }
}

$activityNote = 'Added to pipeline with status: ' . $statusDescription;
$activityEntries->add(
    $candidateID,
    DATA_ITEM_CANDIDATE,
    $activityTypeID,
    $activityNote,
    $_SESSION['CATS']->getUserID(),
    $jobOrderID
);

// Get the candidate_joborder_id for the new pipeline entry
$pipelineData = $pipelines->getCandidatePipeline($candidateID);
$candidateJobOrderID = 0;
$newStatus = $statusDescription;
$newStatusID = $statusID;
foreach ($pipelineData as $pipeline)
{
    if ($pipeline['jobOrderID'] == $jobOrderID)
    {
        $candidateJobOrderID = $pipeline['candidateJobOrderID'];
        $newStatus = $pipeline['status'];
        $newStatusID = $pipeline['statusID'];
        break;
    }
}

$output =
    "<data>\n" .
    "    <errorcode>0</errorcode>\n" .
    "    <errormessage></errormessage>\n" .
    "    <candidateJobOrderID>" . $candidateJobOrderID . "</candidateJobOrderID>\n" .
    "    <status>" . htmlspecialchars($newStatus) . "</status>\n" .
    "    <statusID>" . $newStatusID . "</statusID>\n" .
    "</data>\n";

$interface->outputXMLPage($output);

?>
