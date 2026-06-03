<?php
/*
 * CATS
 * AJAX Activity Entry Deletion Interface
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
 * $Id: deleteActivity.php 1479 2007-01-17 00:22:21Z will $
 */

include_once(LEGACY_ROOT . '/lib/ActivityEntries.php');


$interface = new SecureAJAXInterface();

if (!isset($_SESSION['CATS']) || $_SESSION['CATS']->getAccessLevel('activities') < ACCESS_LEVEL_DELETE) {
    $interface->outputXMLErrorPage(-1, 'Insufficient permissions to delete activities.');
    die();
}

if (!$interface->isRequiredIDValid('activityID'))
{
    $interface->outputXMLErrorPage(-1, 'Invalid activity ID.');
    die();
}

$siteID = $interface->getSiteID();

$activityID = $_REQUEST['activityID'];

/* Delete the activity entry. */
$activityEntries = new ActivityEntries($siteID);

$entry = $activityEntries->get($activityID);
if (!$entry || (isset($entry['enteredBy']) && $entry['enteredBy'] != $_SESSION['CATS']->getUserID()
    && $_SESSION['CATS']->getAccessLevel('activities') < ACCESS_LEVEL_DELETE)) {
    $interface->outputXMLErrorPage(-1, 'Cannot delete another user activity without admin permission.');
    die();
}

$activityEntries->delete($activityID);

/* Send back the XML data. */
$interface->outputXMLSuccessPage();

?>
