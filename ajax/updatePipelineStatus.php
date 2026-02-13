<?php
/*
 * CATS
 * AJAX Pipeline Status Update Interface
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 *
 *
 * The contents of this file are subject to the CATS Public License
 * Version 1.1a (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.catsone.com/.
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * The Original Code is "CATS Standard Edition".
 *
 * The Initial Developer of the Original Code is Cognizo Technologies, Inc.
 * Portions created by the Initial Developer are Copyright (C) 2005 - 2007
 * (or from the year in which this file was created to the year 2007) by
 * Cognizo Technologies, Inc. All Rights Reserved.
 *
 *
 * $Id: updatePipelineStatus.php
 */

include_once(LEGACY_ROOT . '/lib/Pipelines.php');
include_once(LEGACY_ROOT . '/lib/ActivityEntries.php');

$interface = new SecureAJAXInterface();

if ($_SESSION['CATS']->getAccessLevel('pipelines.addActivityChangeStatus') < ACCESS_LEVEL_EDIT)
{
    $interface->outputXMLErrorPage(-1, ERROR_NO_PERMISSION);
    die();
}

if (!$interface->isRequiredIDValid('candidateJobOrderID'))
{
    $interface->outputXMLErrorPage(-1, 'Invalid candidate-joborder ID.');
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

if (!$interface->isRequiredIDValid('statusID', true, true))
{
    $interface->outputXMLErrorPage(-1, 'Invalid status ID.');
    die();
}

$siteID = $interface->getSiteID();

$candidateJobOrderID = $_REQUEST['candidateJobOrderID'];
$candidateID         = $_REQUEST['candidateID'];
$jobOrderID          = $_REQUEST['jobOrderID'];
$statusID             = $_REQUEST['statusID'];

$pipelines = new Pipelines($siteID);

// Get old status before update
$oldStatusData = $pipelines->getCandidatePipeline($candidateID);
$oldStatus = '';
$oldStatusID = 0;
foreach ($oldStatusData as $pipeline)
{
    if ($pipeline['candidateJobOrderID'] == $candidateJobOrderID)
    {
        $oldStatus = $pipeline['status'];
        $oldStatusID = $pipeline['statusID'];
        break;
    }
}

// Get the status description for activity logging
$statuses = $pipelines->getStatuses();
$statusDescription = '';
foreach ($statuses as $status)
{
    if ($status['statusID'] == $statusID)
    {
        $statusDescription = $status['status'];
        break;
    }
}

// Update the status (this already handles history logging)
$pipelines->setStatus($candidateID, $jobOrderID, $statusID, '', '');

// Add activity entry for status change (only if status actually changed)
if ($oldStatusID != $statusID && $oldStatusID != 0)
{
    $activityEntries = new ActivityEntries($siteID);
    $activityTypes = $activityEntries->getTypes();
    
    // Find "Other" activity type (usually type ID 400 or similar)
    $activityTypeID = 400; // Default to "Other" type
    foreach ($activityTypes as $type)
    {
        if (strtolower($type['type']) == 'other' || strtolower($type['type']) == 'status change')
        {
            $activityTypeID = $type['typeID'];
            break;
        }
    }
    
    $activityNote = 'Status change: ' . $oldStatus . ' → ' . $statusDescription;
    $activityEntries->add(
        $candidateID,
        DATA_ITEM_CANDIDATE,
        $activityTypeID,
        $activityNote,
        $_SESSION['CATS']->getUserID(),
        $jobOrderID
    );
}

// Get the updated status for response
$pipelineData = $pipelines->getCandidatePipeline($candidateID);
$newStatus = '';
$newStatusID = 0;
foreach ($pipelineData as $pipeline)
{
    if ($pipeline['candidateJobOrderID'] == $candidateJobOrderID)
    {
        $newStatus = $pipeline['status'];
        $newStatusID = $pipeline['statusID'];
        break;
    }
}

$output =
    "<data>\n" .
    "    <errorcode>0</errorcode>\n" .
    "    <errormessage></errormessage>\n" .
    "    <newstatus>" . htmlspecialchars($newStatus) . "</newstatus>\n" .
    "    <newstatusid>" . $newStatusID . "</newstatusid>\n" .
    "</data>\n";

/* Send back the XML data. */
$interface->outputXMLPage($output);

?>
