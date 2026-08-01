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
include_once(LEGACY_ROOT . '/lib/JobOrders.php');

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

$db = DatabaseConnection::getInstance();
$ownerCheck = $db->getAllAssoc(sprintf(
    "SELECT candidate_joborder_id FROM candidate_joborder WHERE candidate_joborder_id = %d AND candidate_id = %d AND joborder_id = %d AND site_id = %d LIMIT 1",
    intval($candidateJobOrderID), intval($candidateID), intval($jobOrderID), $siteID
));
if (empty($ownerCheck)) {
    $interface->outputXMLErrorPage(-1, 'Invalid pipeline record.');
    die();
}

$pipelines = new Pipelines($siteID);

// Get the status description for activity logging, and double as a whitelist --
// previously statusID was written straight through with no check that it's one
// of the site's actual configured statuses.
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

if ($statusDescription === '')
{
    $interface->outputXMLErrorPage(-1, 'Unknown status ID.');
    die();
}

// Fetch full pipeline row (old status + current openings_available) before the
// update, matching the fields CandidatesUI's status-change modal relies on.
$data = $pipelines->get($candidateID, $jobOrderID);
if (empty($data))
{
    $interface->outputXMLErrorPage(-1, 'The specified pipeline entry could not be found.');
    die();
}

$oldStatus = $data['status'];
$oldStatusID = $data['statusID'];

// Update the status (this already handles history logging)
$pipelines->setStatus($candidateID, $jobOrderID, $statusID, '', '');

// Mirror CandidatesUI::_addActivityChangeStatus()'s openings-count bookkeeping --
// the drag-and-drop path previously never touched openings_available, silently
// desyncing it from candidates actually marked Placed.
if ($statusID == PIPELINE_STATUS_PLACED && is_numeric($data['openingsAvailable']) && $data['openingsAvailable'] > 0)
{
    $jobOrders = new JobOrders($siteID);
    $jobOrders->updateOpeningsAvailable($jobOrderID, $data['openingsAvailable'] - 1);
}
if ($statusID != PIPELINE_STATUS_PLACED && $oldStatusID == PIPELINE_STATUS_PLACED && is_numeric($data['openingsAvailable']))
{
    $jobOrders = new JobOrders($siteID);
    $jobOrders->updateOpeningsAvailable($jobOrderID, $data['openingsAvailable'] + 1);
}

// Log an Activity entry for the status change -- previously this path included
// ActivityEntries but never instantiated it, so Kanban drags left no trace on
// the candidate's Activity tab (only the low-level status-history rows).
if ($oldStatusID != $statusID)
{
    $activityEntries = new ActivityEntries($siteID);
    $activityEntries->add(
        $candidateID,
        DATA_ITEM_CANDIDATE,
        ACTIVITY_OTHER,
        htmlspecialchars('Status change: ' . $oldStatus . ' -> ' . $statusDescription),
        $_SESSION['CATS']->getUserID(),
        $jobOrderID
    );
}

// Trigger email automation for status change
if ($oldStatusID != $statusID)
{
    include_once(LEGACY_ROOT . '/lib/PipelineEmailAutomation.php');
    $emailAutomation = new PipelineEmailAutomation($siteID);
    $emailAutomation->onStatusChange($candidateID, $jobOrderID, $statusID, $_SESSION['CATS']->getUserID());
}

// Auto-generate upload link when status changes to "Onboarded" (1060)
$uploadLinkURL = '';
if ($statusID == 1060 && $oldStatusID != 1060)
{
    include_once(LEGACY_ROOT . '/lib/CandidateDocuments.php');
    $docs = new CandidateDocuments($siteID);

    $existingToken = $docs->getActiveTokenForCandidate($candidateID);
    if (!$existingToken)
    {
        $existingToken = $docs->generateToken($candidateID, $_SESSION['CATS']->getUserID(), 7);
    }

    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = preg_replace('/[^a-zA-Z0-9.\-:_]/', '', $_SERVER['HTTP_HOST'] ?? 'localhost');
    $baseURL = $protocol . '://' . $host . dirname(dirname($_SERVER['SCRIPT_NAME']));
    $uploadLinkURL = rtrim($baseURL, '/') . '/candidate-upload.php?token=' . $existingToken;
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
    "    <uploadlink>" . htmlspecialchars($uploadLinkURL) . "</uploadlink>\n" .
    "</data>\n";

/* Send back the XML data. */
$interface->outputXMLPage($output);

?>
