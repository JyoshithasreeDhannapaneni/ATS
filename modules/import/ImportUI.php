<?php
/*
 * CATS
 * Import Module
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
 * $Id: ImportUI.php 3833 2007-12-12 18:18:09Z brian $
 */

include_once(LEGACY_ROOT . '/lib/Statistics.php');
include_once(LEGACY_ROOT . '/lib/StringUtility.php');
include_once(LEGACY_ROOT . '/modules/import/Import.php');
include_once(LEGACY_ROOT . '/lib/Companies.php');
include_once(LEGACY_ROOT . '/lib/Contacts.php');
include_once(LEGACY_ROOT . '/lib/Candidates.php');
include_once(LEGACY_ROOT . '/lib/JobOrders.php');
include_once(LEGACY_ROOT . '/lib/DatabaseSearch.php');
include_once(LEGACY_ROOT . '/lib/FileUtility.php');
include_once(LEGACY_ROOT . '/lib/ExtraFields.php');
include_once(LEGACY_ROOT . '/lib/Attachments.php');
include_once(LEGACY_ROOT . '/lib/ParseUtility.php');
include_once(LEGACY_ROOT . '/lib/ImportUtility.php');
include_once(LEGACY_ROOT . '/lib/CandidatesImport.php');
include_once(LEGACY_ROOT . '/lib/CompaniesImport.php');
include_once(LEGACY_ROOT . '/lib/ContactsImport.php');
include_once(LEGACY_ROOT . '/lib/Pipelines.php');
include_once(LEGACY_ROOT . '/lib/ActivityEntries.php');


class ImportUI extends UserInterface
{
    const MAX_ERRORS = 100;


    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = true;
        $this->_moduleDirectory = 'import';
        $this->_moduleName = 'import';
        $this->_subTabs = array();
    }


    public function handleRequest()
    {
        $action = $this->getAction();
        switch ($action)
        {
            case 'revert':
                $this->revert();
                break;

            case 'viewerrors':
                $this->viewErrors();
                break;

            case 'viewpending':
                $this->viewPending();
                break;

            case 'importSelectType':
                $this->importSelectType();
                break;

            case 'importUploadFile':
                $this->importUploadFile();
                break;

            case 'whatIsBulkResumes':
                $this->whatIsBulkResumes();
                break;

            case 'showMassImport':
                $this->showMassImport();
                break;

            case 'massImport':
                $this->massImport();
                break;

            case 'massImportDocument':
                $this->massImportDocument();
                break;

            case 'massImportEdit':
                $this->massImportEdit();
                break;

            case 'importBulkResumes':
                $this->importBulkResumes();
                break;

            case 'deleteBulkResumes':
                $this->deleteBulkResumes();
                break;

            case 'bulkImport':
                $this->showBulkImport();
                break;

            case 'bulkImportCandidate':
                $this->bulkImportCandidate();
                break;

            case 'bulkImportResume':
                $this->bulkImportResume();
                break;

            case 'import':
            default:
                if ($this->isPostBack())
                {
                    $this->onImport();
                }
                else
                {
                    $this->import();
                }
                break;
        }
    }

   /*
    * Called by handleRequest() to revert an import.
    */
    private function revert()
    {
        if (!$this->isRequiredIDValid('importID', $_GET))
        {
            $this->import();
            return;
        }

        $importID = $_GET['importID'];

        $import = new Import($this->_siteID);
        $tableName = $import->get($importID);
        if (!$tableName)
        {
            $this->import();
            return;
        }
        $tableName = $import->revert(
            $tableName['moduleName'],
            $importID
        );
        $tableName = $import->delete($importID);

        if (!eval(Hooks::get('IMPORT_REVERT'))) return;

        $message = 'The revert was successful.';

        $this->_template->assign('successMessage', $message);
        $this->viewPending();
        return;
    }


   /*
    * Called by handleRequest() to view the errors of a previous import.
    */
    private function viewErrors()
    {
        $importID = $_GET['importID'];

        if ($importID <= 0 || $importID == '')
        {
            $this->import();
            return;
        }
    
        $import = new Import($this->_siteID);
        $importData = $import->get($importID);
    
        if (!eval(Hooks::get('IMPORT_VIEW_ERRORS'))) return;
    
        if (isset($importData['importErrors']))
        {
            $importErrors = htmlspecialchars($importData['importErrors'], ENT_QUOTES, 'UTF-8');
            $this->_template->assign('importErrors', $importErrors);
        }
        else
        {
            $this->_template->assign('importErrors', '');
        }
    
        $importID = htmlspecialchars($importID, ENT_QUOTES, 'UTF-8');
        $this->_template->assign('importID', $importID);
        $this->viewPending();
        return;
    }    

   /*
    * Called by handleRequest() and viewErrors() to view pending imports and to display relavent information.
    */
    private function viewPending()
    {
        $import = new Import($this->_siteID);
        $data = $import->getAll();

        if (count($data) == 0)
        {
            $this->import();
        }
        else
        {
            if (!eval(Hooks::get('IMPORT_VIEW_PENDING'))) return;

            $this->_template->assign('data', $data);
            $this->_template->assign('active', $this);
            $this->_template->display('./modules/import/ImportRecent.tpl');
        }

        return;
    }

   /*
    * Sets a variety of constants in the instantiated object.
    */
    private function setImportTypes()
    {
        $this->candidatesTypes = array(
            'Full Name',        'name',
            'First Name',       'first_name',
            'Last Name',        'last_name',
            'Address',          'address',
            'City',             'city',
            'State',            'state',
            'Zip',              'zip',
            'Home Phone',       'phone_home',
            'Cell Phone',       'phone_cell',
            'Work Phone',       'phone_work',
            'Notes',            'notes',
            'Current Employer', 'current_employer',
            'Email',            'email1',
            'Email 2',          'email2',
            'Web Site',         'web_site',
            'Key Skills',       'key_skills'
        );
        
        $this->jobOrdersTypes = array(
            'Reference',        'client_job_id',
            'Title',            'title',
            'Company',          'company',
            'City',             'city',
            'State',            'state',
            'Type',             'type',
            'Description',      'description',
            'Notes',            'notes',
            'Openings',         'openings',
            'Public',           'public',
            'Is Hot',           'is_hot'
        );
        
        $this->contactsTypes = array(
            'Company',      'company_id',
            'Full Name',   'name',
            'First Name',  'first_name',
            'Last Name',   'last_name',
            'Address',     'address',
            'City',        'city',
            'State',       'state',
            'Zip',         'zip',
            'Cell Phone',  'phone_cell',
            'Work Phone',  'phone_work',
            'Other Phone', 'phone_other',
            'Notes',       'notes',
            'Email',       'email1',
            'Email 2',     'email2',
            'Title',       'title'
        );
        $this->companiesTypes = array(
            'Name',             'name',
            'Billing Contact',  'billing_contact',
            'Address',          'address',
            'City',             'city',
            'State',            'state',
            'Zip',              'zip',
            'Phone',            'phone1',
            'Phone 2',          'phone2',
            'URL',              'url',
            'Key Technologies', 'key_technologies',
            'Notes',            'notes',
            'Fax Number',       'fax_number'
        );

        if (!eval(Hooks::get('IMPORT_TYPES_2'))) return;

        $companies = new Companies($this->_siteID);
        $candidates = new Candidates($this->_siteID);
        $contacts = new Contacts($this->_siteID);
        $jobOrders = new JobOrders($this->_siteID);

        $rs = $companies->extraFields->getSettings();
        foreach ($rs as $data)
        {
            $this->companiesTypes[] = $data['fieldName'];
            $this->companiesTypes[] = '#' . $data['fieldName'];
        }

        $rs = $jobOrders->extraFields->getSettings();
        foreach ($rs as $data)
        {
            $this->jobOrdersTypes[] = $data['fieldName'];
            $this->jobOrdersTypes[] = '#' . $data['fieldName'];
        }
        
        $rs = $candidates->extraFields->getSettings();
        foreach ($rs as $data)
        {
            $this->candidatesTypes[] = $data['fieldName'];
            $this->candidatesTypes[] = '#' . $data['fieldName'];
        }

        $rs = $contacts->extraFields->getSettings();
        foreach ($rs as $data)
        {
            $this->contactsTypes[] = $data['fieldName'];
            $this->contactsTypes[] = '#' . $data['fieldName'];
        }
    }

   /*
    * First page (also used to display errors.)
    */
    private function import()
    {
        $import = new Import($this->_siteID);
        $data = $import->getAll();

        $attachments = new Attachments($this->_siteID);
        $bulk = $attachments->getBulkAttachmentsInfo();

        if (count($data) > 0)
        {
            $this->_template->assign('pendingCommits', true);
        }

        if (!eval(Hooks::get('IMPORT2_SHOW'))) return;

        $this->_template->assign('active', $this);
        $this->_template->assign('bulk', $bulk);
        $this->_template->display('./modules/import/Import1.tpl');
    }

   /*
    * Second page (upload a file, select file format).
    */
   private function importSelectType()
   {
       $typeOfImport = $this->getTrimmedInput('typeOfImport', $_REQUEST);

       if ($typeOfImport == '')
       {
           $this->import();
           return;
       }
       else if ($typeOfImport == 'resume')
       {
           // Start the new mass import/parser
           $this->massImport();
       }
       else
       {
           $this->_template->assign('active', $this);
           $this->_template->assign('typeOfImport', $typeOfImport);

           if (!eval(Hooks::get('IMPORT_UPLOAD'))) return;

           $this->_template->display('./modules/import/Import2.tpl');
       }
   }

   /*
    * 3rd page for CSV data (After uploading a file).  Sets environment to behave like old style import.
    */
   private function importUploadFile()
   {
       /* Change passed in settings to settings the old importer knows how to handle. */
       $_POST['dataType'] = 'Text File';
       $_POST['importInto'] = $this->getTrimmedInput('typeOfImport', $_POST);
       $_POST['delimitedType'] = $this->getTrimmedInput('typeOfFile', $_POST);

       $this->onImport();
   }

   /*
    * 3rd page for resume data. (After uploading a file).  Sets environment to behave like old style import.
    */
   private function importUploadResume()
   {
       $_POST['dataType'] = 'Resume';

       $this->onImport();
   }

   /*
    * Called by handleRequest() to process an import both on step #2 (choose
    * fields) and step #3 (process import).
    */
    private function onImport()
    {
        if ($this->getUserAccessLevel('import.import') < ACCESS_LEVEL_EDIT)
        {
            CommonErrors::fatal(COMMONERROR_PERMISSION, $this, 'Invalid user level for action.');
        }

        if( ini_get('safe_mode') )
        {
			//don't do anything in safe mode
		}
		else
        {
            /* limit the execution time of import to 500 secs. */
            set_time_limit(500);
        }

        $this->setImportTypes();

        $dataType   = $this->getTrimmedInput('dataType', $_POST);
        $importInto = $this->getTrimmedInput('importInto', $_POST);

        if (empty($dataType))
        {
            $this->_template->assign('errorMessage', 'No data type was specified.');
            $this->importSelectType();
            return;
        }

        if (empty($importInto) && $dataType != 'Resume')
        {
            $this->_template->assign('errorMessage', 'No destination was specified.');
            $this->importSelectType();
            return;
        }

        /* If a file was submitted, then the user sent what colums he wanted to use already. */
        if (isset($_POST['fileName']))
        {
            if ($_SESSION['CATS']->isDemo())
            {
                CommonErrors::fatal(COMMONERROR_PERMISSION, $this, 'Demo user can not import data.');
            }

            if (!eval(Hooks::get('IMPORT_ON_IMPORT_1'))) return;

            switch($dataType)
            {
                case 'Text File':
                    $this->onImportFieldsDelimited();
                    return;

                default:
                    $this->_template->assign(
                        'errorMessage',
                        'No 2nd parser has been included for the specified data type.'
                    );
                    $this->import();
                    return;
            }
        }

        /* Otherwise, parse the file... */

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_2'))) return;

        if (!isset($_FILES['file']) || empty($_FILES['file']['name']))
        {
            $errorMessage = sprintf(
                'No file was uploaded.'
            );
            $this->_template->assign('errorMessage', $errorMessage);
            $this->importSelectType();
            return;
        }

        /* Get file metadata. */
        $originalFilename = $_FILES['file']['name'];
        $tempFilename     = $_FILES['file']['tmp_name'];
        $contentType      = $_FILES['file']['type'];
        $fileSize         = $_FILES['file']['size'];
        $fileUploadError  = $_FILES['file']['error'];

        /* Recover from magic quotes. Note that tmp_name doesn't appear to
         * get escaped, and stripslashes() on it breaks on Windows. - Will
         */
        /* Magic quotes were removed in PHP 5.4.0, function removed in PHP 8.0 */
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
        {
            $originalFilename = stripslashes($originalFilename);
            $contentType      = stripslashes($contentType);
        }

        if ($fileUploadError != UPLOAD_ERR_OK)
        {
            $this->_template->assign(
                'errorMessage', FileUtility::getErrorMessage($fileUploadError)
            );
            $this->importSelectType();
            return;
        }

        if ($fileSize <= 0)
        {
            $this->_template->assign(
                'errorMessage', 'File size is less than 1 byte.'
            );
            $this->importSelectType();
            return;
        }
        if (!is_dir(CATS_TEMP_DIR))
        {
            @mkdir(CATS_TEMP_DIR);
        }
        /* Make sure the attachments directory exists and create it if not. */
        if (!is_dir(CATS_TEMP_DIR))
        {
            $errorMessage = sprintf(
                'Directory \'%s\' does not exist and can\'t be created. CATS is not configured correctly.',
                CATS_TEMP_DIR
            );
            $this->_template->assign('errorMessage', $errorMessage);
            $this->importSelectType();
            return;
        }

        /* Make a blind attempt to recover from invalid permissions. */
        @chmod(CATS_TEMP_DIR, 0777);

        /* Make a random file name for the file. */
        if ($dataType != 'Resume')
        {
            $randomFile = FileUtility::makeRandomFilename($tempFilename) . '.tmp';
        }
        else
        {
            $randomFile = $originalFilename;
        }

        /* Build new path information for the file. */
        $newFileFullPath  = CATS_TEMP_DIR . '/' . $randomFile;

        if (!@copy($tempFilename, $newFileFullPath))
        {
            $errorMessage = sprintf(
                'Cannot copy temporary file from %s to %s.',
                $tempFilename,
                $newFileFullPath
            );
            $this->_template->assign('errorMessage', $errorMessage);
            $this->importSelectType();
            return;
        }

        /* Try to remove the temp file; if it fails it doesn't matter. */
        @unlink($tempFilename);

        /* Store the file ID as a valid file ID (so users can't inject other file ids to read
           files they shouldn't be reading. */
        $_SESSION['CATS']->validImportFileIDs[] = $randomFile;

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_3'))) return;

        switch($dataType)
        {
            case 'Text File':
                $this->onImportDelimited($randomFile);
                break;

            default:
                $this->_template->assign(
                    'errorMessage',
                    'No parser exists for the specified data type.'
                );
                $this->importSelectType();
                break;
        }
    }

    /*
     * Called by onImport() to process an import for Step 2 (decide what
     * fields go where).
     */
    private function onImportDelimited($fileID)
    {
        $filePath = CATS_TEMP_DIR . '/'. $fileID;

        $dataContaining = $this->getTrimmedInput('delimitedType', $_POST);
        $importInto     = $this->getTrimmedInput('importInto', $_POST);
        $dataType       = $this->getTrimmedInput('dataType', $_POST);

        if ($dataType == 'ACT')
        {
            $dataType = 'Text File';
            $dataContaining = $this->getTrimmedInput('ACTType', $_POST);
        }

        /* Parse data */

        $theFile = fopen($filePath, 'r');
        if (!$theFile)
        {
            $this->_template->assign('errorMessage', 'Cannot open the copied file (Internal error).');
            $this->import();
            return;
        }

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_1'))) return;

        switch ($dataContaining)
        {
            case 'tab':
                $theFields = fgetcsv($theFile, null, "\t");
                break;

            case 'csv':
                $theFields = fgetcsv($theFile, null, ',', '"');
                break;

            default:
                $this->_template->assign(
                    'errorMessage', 'Cannot handle that data type.'
                );
                $this->import();
                return;
        }

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_2'))) return;

        switch ($importInto)
        {
            case 'Candidates':
                $types = $this->candidatesTypes;
                break;
            
            case 'JobOrders':
                $types = $this->jobOrdersTypes;
                break;

            case 'Contacts':
                $types = $this->contactsTypes;
                $this->_template->assign('contactsUploadNotice', true);
                break;

            case 'Companies':
                $types = $this->companiesTypes;
                break;

            default:
                $this->_template->assign(
                    'errorMessage', 'Cannot handle that destination.'
                );
                $this->import();
                return;
        }

        /* Figure out what fields match already */

        $matchingFields = array();

        foreach ($theFields AS $theField)
        {
            for ($i = 0; $i < count($types); $i += 2)
            {
                $lField = trim(strtolower($theField));
                $lType  = strtolower($types[$i]);

                if ($lField == $lType ||
                    ($lField == 'company' && $lType == 'company') ||
                    ($lField == 'company' && $lType == 'name' &&
                    $importInto == 'Companies'))
                {
                    $matchingFields[] = $theField;
                }
            }
        }

        /* Get some sample data */
        $ArrayOfData = array();
        for ($i = 0; $i < 20; $i++)
        {
            if (feof($theFile))
            {
                continue;
            }

            if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_3'))) return;

            switch ($dataContaining)
            {
                case 'tab':
                    $someData = fgetcsv($theFile, null, "\t");
                    break;

                case 'csv':
                    $someData = fgetcsv($theFile, null, ",", '"');
                    break;

                default:
                    $this->_template->assign('errorMessage', 'Cannot handle that data type for sample data.');
                    $this->import();
                    return;
            }
            $ArrayOfData[] = $someData;
        }

        $highlightModule = strtolower($importInto);

        $isSA = ($this->getUserAccessLevel('import.import') >= ACCESS_LEVEL_SA);

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_4'))) return;

        $this->_template->assign('isSA', $isSA);
        $this->_template->assign('arrayOfData', $ArrayOfData);
        $this->_template->assign('isUploaded', true);
        $this->_template->assign('fileName', $fileID);
        $this->_template->assign('dataType', $dataType);
        $this->_template->assign('typeOfImport', $_REQUEST['typeOfImport']);
        $this->_template->assign('importInto', $importInto);
        $this->_template->assign('highlightModule', $highlightModule);
        $this->_template->assign('dataContaining', $dataContaining);
        $this->_template->assign('theFields', $theFields);
        $this->_template->assign('matchingFields', $matchingFields);
        $this->_template->assign('importTypes', $types);
        $this->_template->assign('active', $this);
        $this->_template->display('./modules/import/Import.tpl');
    }

    /*
     * Called by onImport() to physically insert the data into the
     * database (Step 3).
     */
    public function onImportFieldsDelimited()
    {
        if ($this->getUserAccessLevel('import.import') < ACCESS_LEVEL_EDIT)
        {
            CommonErrors::fatal(COMMONERROR_PERMISSION, $this, 'Invalid user level for action.');
        }

        $filePath = CATS_TEMP_DIR . '/' . $_POST['fileName'];
        if (!is_file($filePath))
        {
            $this->_template->assign('errorMessage', 'Invalid filename. (Internal error)');
            $this->import();
        }

        $dataContaining = $this->getTrimmedInput('dataContaining', $_POST);
        $importInto     = $this->getTrimmedInput('importInto', $_POST);

        $importID = -1;
        $totalRows = 0;
        $totalImported = 0;
        $totalImportedCompany = 0;
        $errorHtml = '';

        $importErrors = array();

        /* Parse data. */

        $theFile = fopen($filePath, 'r');
        if (!$theFile)
        {
            $this->_template->assign('errorMessage', 'Cannot open the copied file (Internal error).');
            $this->import();
            return;
        }

        $contents = fread($theFile, filesize($filePath));
        rewind($theFile); //move pointer to the beginning of file so fgetcsv can read it too

        if(defined('IMPORT_FILE_ENCODING') && count(IMPORT_FILE_ENCODING) > 0)
        {
            $encoding = mb_detect_encoding($contents, IMPORT_FILE_ENCODING);
        }
        else
        {
            $encoding = mb_detect_encoding($contents, mb_detect_order());
        }

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_5'))) return;

        switch ($dataContaining)
        {
            case 'tab':
                $theFields = fgetcsv($theFile, null, "\t");
                break;

            case 'csv':
                $theFields = fgetcsv($theFile, null, ",", '"');
                break;

            default:
                $this->_template->assign('errorMessage', 'Cannot handle that data type.');
                $this->import();
                return;
        }

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_6'))) return;

        /* Set up a new import record, and set table types. */
        $import = new Import($this->_siteID);
        switch ($importInto)
        {
            case 'Candidates':
                $types = $this->candidatesTypes;
                $importID = $import->add('candidate');
                break;
                
            case 'JobOrders':
                $types = $this->jobOrdersTypes;
                $importID = $import->add('joborder');
                break;

            case 'Companies':
                $types = $this->companiesTypes;
                $importID = $import->add('company');
                break;

            case 'Contacts':
                $types = $this->contactsTypes;
                $importID = $import->add('contact');
                break;

            default:
                $this->_template->assign(
                    'errorMessage', 'Cannot handle the specified destination.'
                );
                $this->import();
                return;
        }

        /* Get user preference for what do to with each field and convert each field into UTF-8*/
        foreach ($theFields AS $fieldID => $theField)
        {
            $theFieldPreference[$fieldID] = $_POST['importType' . $fieldID];
        }

        /* Build the sql and alien parameters for each new item, and execute. */
        while (!feof($theFile))
        {
            $totalRows++;
            // FIXME: This decision should be made outside the loop.

            if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_7'))) return;

            switch ($dataContaining)
            {
                case 'tab':
                    $theData = fgetcsv($theFile, null, "\t");
                    break;

                case 'csv':
                    $theData = fgetcsv($theFile, null, ',', '"');
                    break;

                default:
                    $this->_template->assign('errorMessage', 'Cannot read that data type.');
                    $this->import();
                    return;
            }

            if($encoding) {
                foreach ($theData AS $index => $data) {
                    $theData[$index] = iconv($encoding, 'UTF-8', $data);
                }
            }

            $catsEntriesRows = array();
            $catsEntriesValuesNamed = array();
            $foreignEntries = array();

            /* Put the data where the user picked for it to go. */
            foreach ($theFieldPreference AS $fieldID => $theFieldPreferenceValue)
            {
                if (count($theData) <= $fieldID || trim($theData[$fieldID]) == '')
                {
                    continue;
                }

                if ($theFieldPreferenceValue == 'cats')
                {
                    if (substr($_POST['importIntoField' . $fieldID], 0, 1) == '#')
                    {
                        /* This is an extra field. */
                        $foreignEntries[substr($_POST['importIntoField' . $fieldID], 1)] = $theData[$fieldID];
                    }
                    else
                    {
                        $catsEntriesRows[] = $_POST['importIntoField' .$fieldID];
                        $catsEntriesValuesNamed[$_POST['importIntoField' . $fieldID]] = trim($theData[$fieldID]);
                    }
                }
                else if ($theFieldPreferenceValue == 'foreign' || $theFieldPreferenceValue == 'foreignAdded')
                {
                    /* Before we do this, ensure that we have permision and the field is in the database. */
                    if ($this->getUserAccessLevel('import.import') >= ACCESS_LEVEL_SA)
                    {
                        $import = new Import($this->_siteID);
                        if ($theFieldPreferenceValue == 'foreign')
                        {
                            if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_8'))) return;

                            switch ($importInto)
                            {
                                case 'Candidates':
                                    $import->addForeignSettingUnique(DATA_ITEM_CANDIDATE, $theFields[$fieldID], $importID);
                                    break;
                                    
                                 case 'JobOrders':
                                    $import->addForeignSettingUnique(DATA_ITEM_JOBORDER, $theFields[$fieldID], $importID);
                                    break;

                                case 'Contacts':
                                    $import->addForeignSettingUnique(DATA_ITEM_CONTACT, $theFields[$fieldID], $importID);
                                    break;

                                case 'Companies':
                                    $import->addForeignSettingUnique(DATA_ITEM_COMPANY, $theFields[$fieldID], $importID);
                                    break;

                                default:
                                    $this->_template->assign('errorMessage', 'Cannot handle that destination for new foreign entry setting.');
                                    $this->import();
                                    return;
                            }
                        }

                        $foreignEntries[$theFields[$fieldID]] = $theData[$fieldID];

                        /* Future entries will be set to add directly to the table without trying to make the entry. */
                        $theFieldPreference[$fieldID] = 'foreignAdded';
                    }
                }
            }

            $result = '';

            if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_9'))) return;

            /* Execute the add data command. */
            switch ($importInto)
            {
                case 'Candidates':
                    $result = $this->addToCandidates($catsEntriesRows, $catsEntriesValuesNamed, $foreignEntries, $importID);
                    break;
                    
                case 'JobOrders':
                    $result = $this->addToJobOrders($catsEntriesRows, $catsEntriesValuesNamed, $foreignEntries, $importID);
                    break;

                case 'Contacts':
                    $result = $this->addToContacts($catsEntriesRows, $catsEntriesValuesNamed, $foreignEntries, $importID);
                    break;

                case 'Companies':
                    $result = $this->addToCompanies($catsEntriesRows, $catsEntriesValuesNamed, $foreignEntries, $importID);
                    break;

                default:
                    $this->_template->assign('errorMessage', 'Cannot handle that destination.');
                    $this->import();
                    return;
            }

            if ($result == '' || $result == 'newCompany')
            {
                /* Add data successful. */
                $totalImported++;
                if ($result == 'newCompany')
                {
                    $totalImportedCompany++;
                }
            }
            else if ($totalRows - $totalImported <= self::MAX_ERRORS) /* Errors <= MAX_ERRORS */
            {
                /* Add data failed, record the result */
                $errorHtml .= '<span id="errorPlus'.$totalRows.'"><a href="javascript:void(0);" onclick="showErrorId('.$totalRows.');">[+]</a></span>';
                $errorHtml .= '<span id="errorMinus'.$totalRows.'" style="display:none;"><a href="javascript:void(0);" onclick="hideErrorId('.$totalRows.');">[-]</a></span>';
                $errorHtml .= '&nbsp;Record # '.$totalRows.': '.$result.'<br />';
                $errorHtml .= '<span id="errorId'.$totalRows.'" style="display:none;">';
                foreach ($theFields AS $fieldID => $theField)
                {
                    if (count($theData) > $fieldID)
                    {
                        $errorHtml .= '<span class="bold">' . htmlspecialchars($theField) . ':</span> ' . htmlspecialchars($theData[$fieldID]) . '<br />';
                    }
                }
                $errorHtml .= '</span>';
            }
        }

        /* Put a header on the error output, then update the import record with our errors. */
        if ($totalRows - $totalImported <= self::MAX_ERRORS)
        {
            $errorHtml = '<span class="bold">' . ($totalRows - $totalImported) . ' errors:</span><br /><br />' . $errorHtml;
        }
        else
        {
            $errorHtml = '<span class="bold">First ' . self::MAX_ERRORS . ' errors (of ' . ($totalRows - $totalImported) . '):</span><br /><br />' . $errorHtml;
        }

        $import->updateErrors($importID, $errorHtml, $totalImported);

        /* Generate a response. */
        $message =  'The import was successful.  Of a total ' . $totalRows;
        $message .= ' rows of data, ' . $totalImported . ' were imported into ' . $importInto . '.';

        if ($totalImportedCompany > 0)
        {
            $message .= ' In addition, ' . $totalImportedCompany . ' companies were created.';
        }

        if ($totalImported != $totalRows)
        {
            $message .= ' The dropped rows either had bad data, or were missing required fields (at least 1 name field).<br /><br />';
        }

        $message .= 'You will have 1 week to review the import before the changes become permanent.<br /><br />';

        $message .= '<input type="button" onclick="document.location.href=\'';
        $message .= CATSUtility::getIndexName() . '?m=import&amp;a=revert&amp;importID=' . $importID . '\';" value="Revert Import" class="button">';

        if ($totalRows != $totalImported)
        {
            $message .= '<input type="button" onclick="document.location.href=\'';
            $message .= CATSUtility::getIndexName() . '?m=import&amp;a=viewerrors&amp;importID=' . $importID . '\';" value="View Import Errors" class="button">';
        }

        if (!eval(Hooks::get('IMPORT_ON_IMPORT_DELIMITED_10'))) return;

        /* Send off to the import template. */
        $this->_template->assign('successMessage', $message);
        $this->import(strtolower($importInto));
    }

   /*
    * Generic function to add a extra field to any foreign table.
    */
    private function addForeign($dataTable, $data, $assocID, $importID)
    {
        if (!eval(Hooks::get('IMPORT_ADD_FOREIGN'))) return;

        $import = new Import($this->_siteID);
        $import->addForeign($dataTable, $data, $assocID, $importID);
    }

   /*
    * Inserts a record into candidates.
    */
    private function addToCandidates($dataFields, $dataNamed, $dataForeign, $importID)
    {
        $dateAvailable = '01/01/0001';

        /* Bail out if any of the required fields are empty. */

        if (!empty($dataNamed['name']))
        {
            $nameArray = explode(' ', $dataNamed['name']);
            $dataNamed['first_name'] = $nameArray[0];
            $dataNamed['last_name'] = $nameArray[count($nameArray) - 1];
            unset($dataNamed['name']);
        }

        if (!isset($dataNamed['first_name']) &&
            !isset($dataNamed['last_name']) &&
            !isset($dataNamed['company_id']))
        {
            return 'Required fields (first name, last name) are missing.';
        }

        if (!eval(Hooks::get('IMPORT_ADD_CANDIDATE'))) return;

        $candidatesImport = new CandidatesImport($this->_siteID);
        $candidateID = $candidatesImport->add($dataNamed, $this->_userID, $importID);

        if ($candidateID <= 0)
        {
            return 'Failed to add candidate.';
        }

        $this->addForeign(DATA_ITEM_CANDIDATE, $dataForeign, $candidateID, $importID);

        if (!eval(Hooks::get('IMPORT_ADD_CANDIDATE_POST'))) return;

        return '';
    }
    
    /*
    * Inserts a record into job orders.
    */
    private function addToJobOrders($dataFields, $dataNamed, $dataForeign, $importID)
    {
        $dateAvailable = '01/01/0001';

        /* Bail out if any of the required fields are empty. */


        if (!isset($dataNamed['title']))
        {
            return 'Required field (title) is missing.';
        }

        if (!eval(Hooks::get('IMPORT_ADD_JOBORDER'))) return;

        $jobOrdersImport = new JobOrdersImport($this->_siteID);
        $jobOrderID = $jobOrdersImport->add($dataNamed, $this->_userID, $importID);

        if ($jobOrderID <= 0)
        {
            return 'Failed to add job order.';
        }

        $this->addForeign(DATA_ITEM_JOBORDER, $dataForeign, $jobOrderID, $importID);

        if (!eval(Hooks::get('IMPORT_ADD_JOBORDER_POST'))) return;

        return '';
    }


   /*
    * Inserts a record into Companies.
    */
    private function addToCompanies($dataFields, $dataNamed, $dataForeign, $importID)
    {
        $companiesImport = new CompaniesImport($this->_siteID);
        $companies = new Companies($this->_siteID);

        /* Bail out if any of the required fields are empty. */

        if (!isset($dataNamed['name']))
        {
            return 'Required fields (Company Name) are missing.';
        }

        /* check for duplicates */

        $cID = $companies->companyByName($dataNamed['name']);
        if ($cID != -1)
        {
            return 'Duplicate entry.';
        }

        if (!eval(Hooks::get('IMPORT_ADD_CLIENT'))) return;

        $companyID = $companiesImport->add($dataNamed, $this->_userID, $importID);

        if ($companyID <= 0)
        {
            return 'Failed to add candidate.';
        }

        $this->addForeign(DATA_ITEM_COMPANY, $dataForeign, $companyID, $importID);

        if (!eval(Hooks::get('IMPORT_ADD_CLIENT_POST'))) return;

        return '';
    }

   /*
    * Inserts a record into Contacts.
    */
    private function addToContacts($dataFields, $dataNamed, $dataForeign, $importID)
    {
        $contactImport = new ContactImport($this->_siteID);
        $companies = new Companies($this->_siteID);

        /* Try to find the company. */
        if (!isset($dataNamed['company_id']))
        {
            return 'Unable to add company - no company name.';
        }

        $companyID = $companies->companyByName($dataNamed['company_id']);

        $genCompany = false;

        /* The company does not exist. What do we do? */
        if ($companyID == -1)
        {
            if ($_POST['generateCompanies'] == 'yes')
            {
                /* Build data for the new company. */
                $dataCompany = array();
                $dataCompany['name'] = $dataNamed['company_id'];

                if (isset($dataNamed['phone_work']))
                {
                    $dataCompany['phone1'] = $dataNamed['phone_work'];
                }

                foreach (array('address', 'city', 'state', 'zip') as $field)
                {
                    if (isset($dataNamed[$field]))
                    {
                        $dataCompany[$field] = $dataNamed[$field];
                    }
                }

                if (!eval(Hooks::get('IMPORT_ADD_CONTACT_CLIENT'))) return;

                $companiesImport = new CompaniesImport($this->_siteID);
                $companyID = $companiesImport->add($dataCompany, $this->_userID, $importID);
                if ($companyID == -1)
                {
                    return 'Unable to add company.';
                }
                $genCompany = true;

                if (!eval(Hooks::get('IMPORT_ADD_CONTACT_CLIENT_POST'))) return;
            }
            else
            {
                /* Bail out of add - no company. */
                return 'Invalid company name.';
            }
        }

        $dataNamed['company_id'] = $companyID;

        /* Bail out if any of the required fields are empty. */

        if (!empty($dataNamed['name']))
        {
            $nameArray = explode(' ', $dataNamed['name']);
            $dataNamed['first_name'] = $nameArray[0];
            $dataNamed['last_name'] = $nameArray[count($nameArray)-1];
            unset($dataNamed['name']);
        }

        if (!isset($dataNamed['first_name']) && !isset($dataNamed['last_name']))
        {
            if ($_POST['unnamedContacts'] == 'yes' && $genCompany)
            {
                $dataNamed['first_name'] = 'nobody';
            }
            else
            {
                $error = 'Required fields (first name, last name) are missing.';
                if ($genCompany)
                {
                    $error .= '  However, the company was generated.';
                }
                return $error;
            }

        }

        if (!eval(Hooks::get('IMPORT_ADD_CONTACT'))) return;

        $contactID = $contactImport->add($dataNamed, $this->_userID, $importID);

        if ($contactID <= 0)
        {
            return 'Failed to add candidate.';
        }

        $this->addForeign(DATA_ITEM_CONTACT, $dataForeign, $contactID, $importID);

        if (!eval(Hooks::get('IMPORT_ADD_CONTACT_POST'))) return;

        if ($genCompany)
        {
            return 'newCompany';
        }

        return '';
    }

    /*
     * Modal popup describing how to use bulk resumes.
     */
    function whatIsBulkResumes()
    {
       $this->_template->assign('active', $this);
       $this->_template->display('./modules/import/BulkResumesHelp.tpl');
    }

   /*
    * Scan the upload directory for files to import, show the files to the user.
    * save the files found so a ajax interface can import 1 file at a time later.
    */
    function showMassImport()
    {
        $directoryRoot = './upload/';
        $foundFiles = array();
        $numberOfFiles = 0;

        $directoriesToWalk = array('');

        while (count($directoriesToWalk) != 0)
        {
            $directoryName = array_pop($directoriesToWalk);
            $fullDirectoryName = $directoryRoot . $directoryName;

            if ($handle = @opendir($fullDirectoryName))
            {
                while (false !== ($file = readdir($handle)))
                {
                    $fileWithDirectory = $directoryName . $file;
                    $fullFileWithDirectory = $fullDirectoryName . $file;

                    if ($file != "." && $file != ".." && $file != ".svn" && filetype($fullFileWithDirectory) == "dir")
                    {
                        array_push ($directoriesToWalk, $fileWithDirectory . '/');
                    }
                    else if ($file != "." && $file != ".." && $file != ".svn")
                    {
                        $numberOfFiles++;
                        $foundFiles[] = $directoryName . $file;
                    }
                }
                closedir($handle);
            }
        }

        sort($foundFiles);

        $_SESSION['CATS']->massImportFiles = $foundFiles;
        $_SESSION['CATS']->massImportDirectory = $directoryRoot;

        $this->_template->assign('active', $this);
        $this->_template->assign('foundFiles', $foundFiles);
        $this->_template->display('./modules/import/ImportResumesBulk.tpl');
    }

    /**
     * AJAX:
     *   This function is called by the javascript progress bar page (step 2) of the
     *   mass resume importer. It parses the resume set in $_POST and saves the
     *   results to a temporary session.
     */
    public function massImportDocument()
    {
        // Find the files the user has uploaded and put them in an array
        if (isset($_SESSION['CATS']) && !empty($_SESSION['CATS']))
        {
            $siteID = $_SESSION['CATS']->getSiteID();
        }
        else
        {
             echo 'Fail';
             return;
        }

        if (isset($_GET['name'])) $name = $_GET['name']; else { echo 'Fail'; return; }
        if (isset($_GET['realName'])) $realName = $_GET['realName']; else { echo 'Fail'; return; }
        if (isset($_GET['ext'])) $ext = $_GET['ext']; else { echo 'Fail'; return; }
        if (isset($_GET['cTime'])) $cTime = intval($_GET['cTime']); else { echo 'Fail'; return; }
        if (isset($_GET['type'])) $type = intval($_GET['type']); else { echo 'Fail'; return; }

        if (!isset($_SESSION['CATS_PARSE_TEMP']))
        {
            $_SESSION['CATS_PARSE_TEMP'] = array();
        }

        $mp = array(
            'name' => $name,
            'realName' => $realName,
            'ext' => $ext,
            'type' => $type,
            'cTime' => $cTime,
        );

        $doc2text = new DocumentToText();
        $pu = new ParseUtility();
        if (LicenseUtility::isParsingEnabled())
        {
            $parsingEnabled = true;
        }
        else
        {
            $parsingEnabled = false;
        }

        if ($doc2text->convert($name, $type) === false)
        {
            $mp['success'] = false;
            $_SESSION['CATS_PARSE_TEMP'][] = $mp;
            echo 'Fail';
            return;
        }
        $contents = $doc2text->getString();

        // Decode things like _rATr to @ so the parser can accurately find things
        $contents = DatabaseSearch::fulltextDecode($contents);

        if ($parsingEnabled)
        {
            switch ($type)
            {
                case DOCUMENT_TYPE_DOC:
                    $contents = str_replace('|', "\n", $contents);
                    $contents = str_replace(' ? ', "\n", $contents);
                    break;
            }
            while (strpos($contents, '  ') !== false)
            {
                $contents = str_replace('  ', ' ', $contents);
            }
        }

        $mp['contents'] = $contents;

        if ($parsingEnabled)
        {
            $parseData = $pu->documentParse($realName, strlen($contents), 'application/text',
                $contents
            );
            $mp['parse'] = $parseData;
        }

        $mp['success'] = true;
        $_SESSION['CATS_PARSE_TEMP'][] = $mp;

        echo 'Ok';
        return;
    }

    public function massImportEdit()
    {
        if (isset($_GET['documentID']))
        {
            $documentID = intval($_GET['documentID']);
        }
        else
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this);
        }

        list($documents, $success, $failed) = $this->getMassImportDocuments();
        if (!count($documents))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this);
        }

        if (isset($_GET['postback']) && $_GET['postback'] == '1')
        {
            // User is saving changes
            if (!isset($_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse'] = array(
                    'firstName' => '', 'lastName' => '', 'address' => '', 'city' => '', 'state' => '',
                    'zipCode' => '', 'email' => '', 'phone' => '', 'skills' => '', 'education' => '',
                    'experience' => ''
            );
            if (isset($_POST['firstName']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['first_name'] = $_POST['firstName'];
            if (isset($_POST['lastName']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['last_name'] = $_POST['lastName'];
            if (isset($_POST['address']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['us_address'] = $_POST['address'];
            if (isset($_POST['city']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['city'] = $_POST['city'];
            if (isset($_POST['state']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['state'] = $_POST['state'];
            if (isset($_POST['zipCode']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['zip_code'] = $_POST['zipCode'];
            if (isset($_POST['homePhone']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['phone_number'] = $_POST['homePhone'];
            if (isset($_POST['email']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['email_address'] = $_POST['email'];
            if (isset($_POST['skills']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['skills'] = $_POST['skills'];
            if (isset($_POST['education']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['education'] = $_POST['education'];
            if (isset($_POST['experience']))
                $_SESSION['CATS_PARSE_TEMP'][$documentID]['parse']['experience'] = $_POST['experience'];

            // Step 3 is the review step
            $this->massImport(3);
            return;
        }

        $document = null;
        foreach ($documents as $doc)
        {
            if ($doc['id'] == $documentID)
            {
                $document = $doc;
            }
        }

        $this->_template->assign('active', $this);
        $this->_template->assign('document', $document);
        $this->_template->assign('documentID', $documentID);
        $this->_template->display('./modules/import/MassImportEdit.tpl');
    }

    public function massImport($step = 1)
    {
        if (isset($_SESSION['CATS']) && !empty($_SESSION['CATS']))
        {
            $siteID = $_SESSION['CATS']->getSiteID();
        }
        else
        {
            CommonErrors::fatal(COMMONERROR_NOTLOGGEDIN, $this);
        }

        if ($this->getUserAccessLevel('import.massImport') < ACCESS_LEVEL_EDIT)
        {
            CommonErrors::fatal(COMMONERROR_PERMISSION, $this, 'You do not have permission to import '
                . 'mass resume documents.'
            );
        }

        // Figure out what stage of the process we're on
        if (isset($_GET['step']) && ($step = intval($_GET['step'])) >= 1 && $step <= 4) {}

        $this->_template->assign('step', $step);

        if ($step == 1)
        {
            if (isset($_SESSION['CATS_PARSE_TEMP'])) unset($_SESSION['CATS_PARSE_TEMP']);
            $uploadDir = FileUtility::getUploadPath($siteID, 'massimport');
            $files = ImportUtility::getDirectoryFiles($uploadDir);
            if (is_array($files) && count($files))
            {
                // User already has files for upload
                $this->_template->assign('documents', $files);
            }

            // Figure out the path to post resumes
            $script = $_SERVER['SCRIPT_FILENAME'];
            $mp = explode('/', $script);
            $rootPath = implode('/', array_slice($mp, 0, count($mp) - 1));
            $subPath = FileUtility::getUploadPath($siteID, 'massimport');
            if ($subPath !== false)
            {
                $uploadPath = $rootPath . '/' . $subPath . '/';
            }
            else
            {
                $uploadPath = false;
            }

            $this->_template->assign('multipleFilesEnabled', true);
            $this->_template->assign('uploadPath', $uploadPath);
        }
        else if ($step == 2)
        {
            /**
             * Step 1: Find any uploaded files and get them into an array.
             */
            if (isset($_SESSION['CATS_PARSE_TEMP'])) unset($_SESSION['CATS_PARSE_TEMP']);
            $uploadDir = FileUtility::getUploadPath($siteID, 'massimport');
            $files = ImportUtility::getDirectoryFiles($uploadDir);
            if ($files === -1 || !is_array($files) || !count($files))
            {
                $this->_template->assign('errorMessage', 'You didn\'t upload any files or there was a '
                    . 'problem working with any files you uploaded. Please use the '
                    . '<a href="javascript:history.back()"><b>Back button</b></a> on your web browser '
                    . 'and select one or more files to import.'
                );

                $this->_template->assign('files', array());
                $this->_template->assign('js', '');
            }
            else
            {
                if (!eval(Hooks::get('MASS_IMPORT_SPACE_CHECK'))) return;

                // Build the javascript to handle the ajax parsing (for progress bar)
                $js = '';
                foreach ($files as $fileData)
                {
                    $js .= sprintf('addDocument(\'%s\', \'%s\', \'%s\', %d, %d);%s',
                        addslashes($fileData['name']), addslashes($fileData['realName']), addslashes($fileData['ext']),
                        $fileData['type'], $fileData['cTime'], "\n"
                    );
                }

                $this->_template->assign('files', $files);
                $this->_template->assign('js', $js);
            }
        }
        else if ($step == 3)
        {
            // Make sure the processed files exists, is an array, and is not empty
            list($documents, $success, $failed) = $this->getMassImportDocuments();
            if (!count($documents))
            {
                $this->_template->assign('errorMessage', 'None of the files you uploaded were able '
                    . 'to be imported!'
                );
            }

            $this->_template->assign('documents', $documents);
        }
        else if ($step == 4)
        {
            // Final step, import all applicable candidates
            list($importedCandidates, $importedDocuments, $importedFailed, $importedDuplicates) =
                $this->getMassImportCandidates();

            if (!count($importedCandidates) && !count($importedDocuments) && !count($importedFailed) &&
                !count($importedDuplicates))
            {
                $this->_template->assign('errorMessage', '<b style="font-size: 20px;">Information no Longer '
                    . 'Available</b><br /><br />'
                    . 'Ooops! You probably used the <b>back</b> or <b>refresh</b> '
                    . 'buttons on your browser. The information you previously had here is no longer '
                    . 'available. To start a new '
                    . 'mass resume import, <a style="font-size: 16px;" href="' . CATSUtility::getIndexName() . '?m=import&a=massImport&'
                    . 'step=1">click here</a>.'
                );
            }

            //if (!eval(Hooks::get('IMPORT_NOTIFY_DEV'))) return;

            $this->_template->assign('importedCandidates', $importedCandidates);
            $this->_template->assign('importedDocuments', $importedDocuments);
            $this->_template->assign('importedFailed', $importedFailed);
            $this->_template->assign('importedDuplicates', $importedDuplicates);

            unset($_SESSION['CATS_PARSE_TEMP']);
        }
        else if ($step == 99)
        {
            // User wants to delete all files in their upload folder
            $uploadDir = FileUtility::getUploadPath($siteID, 'massimport');
            $files = ImportUtility::getDirectoryFiles($uploadDir);
            if (is_array($files) && count($files))
            {
                foreach ($files as $file)
                {
                    @unlink($file['name']);
                }
            }
            echo 'Ok';
            return;
        }

        $this->_template->assign('active', $this);
        // ->isDemo() doesn't work here... oddly.
        $this->_template->assign('isDemo', $_SESSION['CATS']->getSiteID() == 201);

        // Build the sub-template to pass to the container
        ob_start();
        $this->_template->display(sprintf('./modules/import/MassImportStep%d.tpl', $step));
        $subTemplateContents = ob_get_contents();
        ob_end_clean();

        // Show the main template (the container with the large status sections)
        $this->_template->assign('subTemplateContents', $subTemplateContents);
        $this->_template->display('./modules/import/MassImport.tpl');
    }

    private function getMassImportCandidates()
    {
        $db = DatabaseConnection::getInstance();

        // Find the files the user has uploaded and put them in an array
        if (isset($_SESSION['CATS']) && !empty($_SESSION['CATS']))
        {
            $siteID = $_SESSION['CATS']->getSiteID();
            $userID = $_SESSION['CATS']->getUserID();
        }
        else
        {
            CommonErrors::fatal(COMMONERROR_NOTLOGGEDIN, $this);
        }

        list($documents, $success, $failed) = $this->getMassImportDocuments();
        if (!count($documents))
        {
            return array( array(), array(), array(), array() );
        }

        $importedCandidates = array();
        $importedDocuments = array();
        $importedFailed = array();
        $importedDuplicates = array();

        for ($ind=0; $ind<count($_SESSION['CATS_PARSE_TEMP']); $ind++)
        {
            $doc = $_SESSION['CATS_PARSE_TEMP'][$ind];

            // Get parsed information instead (if available)
            for ($ind2=0; $ind2<count($documents); $ind2++)
            {
                if ($documents[$ind2]['id'] == $ind)
                {
                    $doc = $documents[$ind2];
                }
            }

            if (isset($doc['success']) && $doc['success'])
            {
                $candidateAdded = false;

                if (isset($doc['lastName']) && $doc['lastName'] != '' && isset($doc['firstName']) && $doc['firstName'] != '')
                {
                    $isCandidateUnique = true;

                    /**
                     * We need to check for duplicate candidate entries before adding a new
                     * candidate into CATS. The criteria is as follows:
                     * - if email is present, does it match an existing e-mail
                     * - if last name and zip code or last name and phone numbers are present, do they match likewise
                     */
                    if (strpos($doc['email'], '@') !== false)
                    {
                        $sql = sprintf('SELECT count(*) '
                            . 'FROM candidate '
                            . 'WHERE (candidate.email1 = %s OR candidate.email2 = %s) '
                            . 'AND candidate.site_id = %d',
                            $db->makeQueryString($doc['email']),
                            $db->makeQueryString($doc['email']),
                            $this->_siteID
                        );
                        if ($db->getColumn(0, 0, $sql) > 0)
                        {
                            $isCandidateUnique = false;
                        }
                    }

                    if (strlen($doc['lastName']) > 3 && isset($doc['phone']) && strlen($doc['phone']) >= 10)
                    {
                        $sql = sprintf('SELECT count(*) '
                            . 'FROM candidate '
                            . 'WHERE candidate.last_name = %s '
                            . 'AND (candidate.phone_home = %s '
                            . 'OR candidate.phone_work = "%s '
                            . 'OR candidate.phone_cell = "%s) '
                            . 'AND candidate.site_id = %d',
                            $db->makeQueryString($doc['lastName']),
                            $db->makeQueryString($doc['phone']),
                            $db->makeQueryString($doc['phone']),
                            $db->makeQueryString($doc['phone']),
                            $this->_siteID
                        );
                        if ($db->getColumn(0, 0, $sql) > 0)
                        {
                            $isCandidateUnique = false;
                        }
                    }

                    if (strlen($doc['lastName']) > 3 && isset($doc['zip']) && strlen($doc['zip']) >= 5)
                    {
                        $sql = sprintf('SELECT count(*) '
                            . 'FROM candidate '
                            . 'WHERE candidate.last_name = %s '
                            . 'AND candidate.zip = %s '
                            . 'AND candidate.site_id = %d',
                            $db->makeQueryString($doc['lastName']),
                            $db->makeQueryString($doc['zipCode']),
                            $this->_siteID
                        );
                        if ($db->getColumn(0, 0, $sql) > 0)
                        {
                            $isCandidateUnique = false;
                        }
                    }

                    if ($isCandidateUnique)
                    {
                        // This was parsed data
                        $candidates = new Candidates($siteID);

                        $candidateID = $candidates->add(
                            $doc['firstName'],
                            '',
                            $doc['lastName'],
                            $doc['email'],
                            '',
                            $doc['phone'],
                            '',
                            '',
                            $doc['address'],
                            $doc['city'],
                            $doc['state'],
                            $doc['zipCode'],
                            '',
                            $doc['skills'],
                            NULL,
                            '',
                            false,
                            '',
                            '',
                            'This resume was parsed automatically. You should review it for errors.',
                            '',
                            '',
                            $userID,
                            $userID,
                            '',
                            0,
                            0,
                            '',
                            true
                        );

                        if ($candidateID > 0)
                        {
                            $candidateAdded = true;

                            // set the date created to the file modification date
                            $db->query(sprintf('UPDATE candidate SET date_created = "%s", date_modified = "%s" '
                                . 'WHERE candidate_id = %d AND site_id = %d',
                                date('c', $doc['cTime']), date('c', $doc['cTime']), $candidateID, $siteID
                            ));

                            // Success, attach resume to candidate as attachment
                            $ac = new AttachmentCreator($siteID);
                            if ($ac->createFromFile(DATA_ITEM_CANDIDATE, $candidateID, $doc['name'], $doc['realName'],
                                '', true, true))
                            {
                                // FIXME: error checking on fail?
                            }

                            $importedCandidates[] = array(
                                'name' => trim($doc['firstName'] . ' ' . $doc['lastName']),
                                'resume' => $doc['realName'],
                                'url' => sprintf(
                                    '%s?m=candidates&a=show&candidateID=%d',
                                    CATSUtility::getIndexName(),
                                    $candidateID
                                ),
                                'location' => trim($doc['city'] . ' ' . $doc['state'] . ' ' . $doc['zipCode'])
                            );
                        }
                    }
                    else
                    {
                        $importedDuplicates[] = array(
                            'name' => trim($doc['firstName'] . ' ' . $doc['lastName']),
                            'resume' => $doc['realName']
                        );
                        @unlink($doc['name']);
                        $candidateAdded = true;
                    }
                }

                /**
                 * A candidate was unable to be automatically added, add them as a
                 * bulk resume document which is still searchable and can be manually
                 * converted into a candidate later.
                 */
                if (!$candidateAdded)
                {
                    $brExists = false;
                    $error = false;

                    /**
                     * Bulk resumes can be "rescanned", make sure this particular file isn't a
                     * rescan before adding another copy.
                     */
                    if (preg_match('/^_BulkResume_(.*)\.txt$/', $doc['realName'], $matches))
                    {
                        $attachments = new Attachments($this->_siteID);
                        $bulkResumes = $attachments->getBulkAttachments();
                        foreach ($bulkResumes as $bulkResume)
                        {
                            $mp = explode('.', $bulkResume['originalFileName']);
                            $fileName = implode('.', array_slice($mp, 0, -1));

                            if (!strcmp($fileName, $matches[1]))
                            {
                                $brExists = true;
                                if (FileUtility::isUploadFileSafe($siteID, 'massimport', $doc['name']))
                                {
                                    @unlink($doc['name']);
                                }
                                break;
                            }
                        }
                    }

                    if (!$brExists)
                    {
                        $error = false;
                        $attachmentCreator = new AttachmentCreator($siteID);
                        $attachmentCreator->createFromFile(
                            DATA_ITEM_BULKRESUME, 0, $doc['name'], $doc['realName'], '', true, true
                        );

                        if ($attachmentCreator->isError())
                        {
                            $error = true;
                        }
                        if ($attachmentCreator->duplicatesOccurred())
                        {
                            $error = true;
                        }
                    }

                    // For use later on debugging
                    //$isTextExtractionError = $attachmentCreator->isTextExtractionError();
                    //$textExtractionErrorMessage = $attachmentCreator->getTextExtractionError();

                    if (!$error || $brExists)
                    {
                        $importedDocuments[] = array(
                            'name' => $doc['realName']
                        );
                    }
                    else
                    {
                        $importedFailed[] = array(
                            'name' => $doc['realName']
                        );
                    }
                }
                /**
                 * The candidate was successfully added. If this candidate came from an
                 * existing bulk resume rescan, that document should be deleted.
                 */
                else
                {
                    if (preg_match('/^_BulkResume_(.*)\.txt$/', $doc['realName'], $matches))
                    {
                        $attachments = new Attachments($this->_siteID);
                        $bulkResumes = $attachments->getBulkAttachments();
                        foreach ($bulkResumes as $bulkResume)
                        {
                            $mp = explode('.', $bulkResume['originalFileName']);
                            $fileName = implode('.', array_slice($mp, 0, -1));

                            if (!strcmp($fileName, $matches[1]))
                            {
                                // Delete the permanent file
                                $attachments->delete($bulkResume['attachmentID'], true);
                                // Delete the temporary file
                                if (FileUtility::isUploadFileSafe($siteID, 'massimport', $doc['name']))
                                {
                                    @unlink($doc['name']);
                                }
                                break;
                            }
                        }
                    }
                }
            }
            else
            {
                // This document failed to convert to a text-format using doc2text
                $importedFailed[] = array(
                    'name' => $doc['realName']
                );

                // Make sure it's a safe filename to delete and located in the site's upload directory
                if (FileUtility::isUploadFileSafe($siteID, 'massimport', $doc['name']))
                {
                    @unlink($doc['name']);
                }
            }
        }

        return array($importedCandidates, $importedDocuments, $importedFailed, $importedDuplicates);
    }

    private function getMassImportDocuments()
    {
        if (!isset($_SESSION['CATS_PARSE_TEMP']) || empty($_SESSION['CATS_PARSE_TEMP']) ||
            !is_array($_SESSION['CATS_PARSE_TEMP']))
        {
            return array(array(), 0, 0);
        }

        $mp = $_SESSION['CATS_PARSE_TEMP'];

        // Clean up the documents for the next stage
        $documents = array();
        $failed = $success = 0;
        for ($ind=0; $ind<count($mp); $ind++)
        {
            $doc = $mp[$ind];

            if (isset($doc['success']) && $doc['success'])
            {
                if (isset($doc['parse']) && is_array($doc['parse']))
                {
                    if (isset($doc['parse'][$id = 'first_name']))
                        $doc['firstName'] = $doc['parse'][$id]; else $doc['firstName'] = '';
                    if (isset($doc['parse'][$id = 'last_name']))
                        $doc['lastName'] = $doc['parse'][$id]; else $doc['lastName'] = '';
                    if (isset($doc['parse'][$id = 'us_address']))
                        $doc['address'] = $doc['parse'][$id]; else $doc['address'] = '';
                    if (isset($doc['parse'][$id = 'city']))
                        $doc['city'] = $doc['parse'][$id]; else $doc['city'] = '';
                    if (isset($doc['parse'][$id = 'state']))
                        $doc['state'] = $doc['parse'][$id]; else $doc['state'] = '';
                    if (isset($doc['parse'][$id = 'zip_code']))
                        $doc['zipCode'] = $doc['parse'][$id]; else $doc['zipCode'] = '';
                    if (isset($doc['parse'][$id = 'email_address']))
                        $doc['email'] = $doc['parse'][$id]; else $doc['email'] = '';
                    if (isset($doc['parse'][$id = 'phone_number']))
                        $doc['phone'] = $doc['parse'][$id]; else $doc['phone'] = '';
                    if (isset($doc['parse'][$id = 'education']))
                        $doc['education'] = $doc['parse'][$id]; else $doc['education'] = '';
                    if (isset($doc['parse'][$id = 'skills']))
                        $doc['skills'] = $doc['parse'][$id]; else $doc['skills'] = '';
                    if (isset($doc['parse'][$id = 'experience']))
                        $doc['experience'] = $doc['parse'][$id]; else $doc['experience'] = '';
                }
                else
                {
                    $doc['firstName'] = $doc['lastName'] = $doc['address'] = $doc['city'] =
                        $doc['state'] = $doc['zipCode'] = $doc['email'] = $doc['phone'] =
                        $doc['education'] = $doc['skills'] = $doc['experience'] = '';
                }
                $doc['id'] = $ind;
                $documents[] = $doc;
                $success++;
            }
            else
            {
                $failed++;
            }
        }
        return array($documents, $success, $failed);
    }

    private function deleteBulkResumes()
    {
        if (!isset($_SESSION['CATS']) || empty($_SESSION['CATS']))
        {
            CommonErrors::fatal(COMMONERROR_NOTLOGGEDIN, $this);
        }
        if ($this->getUserAccessLevel('import.bulkResumes') < ACCESS_LEVEL_SA)
        {
            CommonErrors::fatal(COMMONERROR_PERMISSION, $this);
        }

        $uploadPath = FileUtility::getUploadPath($this->_siteID, 'massimport');

        $attachments = new Attachments($this->_siteID);
        $bulkResumes = $attachments->getBulkAttachments();

        if (!count($bulkResumes))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this);
        }

        /**
         * Write the parsed resume contents to the new file which will
         * be created as a text document for each bulk attachment.
         */
        foreach ($bulkResumes as $bulkResume)
        {
            $attachments->delete($bulkResume['attachmentID'], true);
        }

        $this->import();
    }

    private function importBulkResumes()
    {
        if (!isset($_SESSION['CATS']) || empty($_SESSION['CATS']))
        {
            CommonErrors::fatal(COMMONERROR_NOTLOGGEDIN, $this);
        }
        if ($this->getUserAccessLevel('import.bulkResumes') < ACCESS_LEVEL_SA)
        {
            CommonErrors::fatal(COMMONERROR_PERMISSION, $this);
        }

        $uploadPath = FileUtility::getUploadPath($this->_siteID, 'massimport');

        $attachments = new Attachments($this->_siteID);
        $bulkResumes = $attachments->getBulkAttachments();

        if (!count($bulkResumes))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this);
        }

        /**
         * Write the parsed resume contents to the new file which will
         * be created as a text document for each bulk attachment.
         */
        foreach ($bulkResumes as $bulkResume)
        {
            $fullName = $bulkResume['originalFileName'];
            if (!strlen(trim($fullName)))
            {
                $fullName = 'Untitled';
            }

            $mp = explode('.', $fullName);
            $fileName = implode('.', array_slice($mp, 0, -1));

            if (!@file_exists($newFileName = $uploadPath . '/_BulkResume_' . $fileName . '.txt'))
            {
                // Some old files are fulltext encoded which makes them a pain for the parser, fixing here:
                $contents = DatabaseSearch::fulltextDecode($bulkResume['text']);

                @file_put_contents($newFileName, $contents);
                chmod($newFileName, 0777);
            }
        }

        CATSUtility::transferRelativeURI('m=import&a=massImport&step=2');
    }

    /**
     * Shows the modern bulk import page
     */
    private function showBulkImport()
    {
        if (!isset($_SESSION['CATS']) || empty($_SESSION['CATS']))
        {
            CommonErrors::fatal(COMMONERROR_NOTLOGGEDIN, $this);
        }

        $jobOrders = new JobOrders($this->_siteID);
        $jobOrdersRS = $jobOrders->getAll(JOBORDERS_STATUS_ACTIVE);
        $this->_template->assign('jobOrders', $jobOrdersRS);

        $this->_template->assign('active', $this);
        $this->_template->display('./modules/import/BulkImport.tpl');
    }

    /**
     * AJAX handler for bulk importing a single candidate from CSV data
     */
    private function bulkImportCandidate()
    {
        header('Content-Type: application/json');

        if (!isset($_SESSION['CATS']) || empty($_SESSION['CATS']))
        {
            echo json_encode(['success' => false, 'error' => 'Not logged in']);
            return;
        }

        $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
        $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
        $city = isset($_POST['city']) ? trim($_POST['city']) : '';
        $state = isset($_POST['state']) ? trim($_POST['state']) : '';
        $keySkills = isset($_POST['keySkills']) ? trim($_POST['keySkills']) : '';
        $currentEmployer = isset($_POST['currentEmployer']) ? trim($_POST['currentEmployer']) : '';
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
        $source = isset($_POST['source']) ? trim($_POST['source']) : 'Bulk Import';
        $jobOrderID = isset($_POST['jobOrderID']) ? (int)$_POST['jobOrderID'] : 0;

        // Validate required fields
        if (empty($firstName) && empty($lastName))
        {
            echo json_encode(['success' => false, 'error' => 'First name or last name is required']);
            return;
        }

        // Check for duplicates by email
        if (!empty($email))
        {
            $db = DatabaseConnection::getInstance();
            $sql = sprintf(
                "SELECT candidate_id FROM candidate WHERE email1 = %s AND site_id = %s LIMIT 1",
                $db->makeQueryString($email),
                $this->_siteID
            );
            $rs = $db->query($sql);
            if ($rs && $db->getNumRows($rs) > 0)
            {
                echo json_encode(['success' => false, 'duplicate' => true, 'error' => 'Duplicate email']);
                return;
            }
        }

        // Create the candidate
        $candidates = new Candidates($this->_siteID);
        
        $candidateID = $candidates->add(
            $firstName,
            '',  // middleName
            $lastName,
            $email,
            '',  // email2
            '',  // phoneHome
            $phone,  // phoneCell
            '',  // phoneWork
            '',  // address
            $city,
            $state,
            '',  // zip
            $source,
            $keySkills,
            '',  // dateAvailable
            $currentEmployer,
            0,   // canRelocate
            '',  // currentPay
            '',  // desiredPay
            $notes,
            '',  // webSite
            '',  // bestTimeToCall
            $_SESSION['CATS']->getUserID(),
            $_SESSION['CATS']->getUserID()
        );

        if ($candidateID > 0)
        {
            // Add to pipeline if job order selected
            if ($jobOrderID > 0) {
                $pipelines = new Pipelines($this->_siteID);
                // Cooling period check: 90 days (3 months)
                if ($pipelines->isInCoolingPeriod($candidateID, $jobOrderID)) {
                    echo json_encode([
                        'success'       => true,
                        'candidateID'   => $candidateID,
                        'coolingPeriod' => true,
                        'warning'       => 'Candidate is within the 3-month reapplication cooling period for this job order and was not added to the pipeline.'
                    ]);
                    return;
                }
                $pipelines->add($candidateID, $jobOrderID, $_SESSION['CATS']->getUserID());

                $activityEntries = new ActivityEntries($this->_siteID);
                $activityEntries->add(
                    $candidateID,
                    DATA_ITEM_CANDIDATE,
                    400,
                    'Added candidate to job order.',
                    $_SESSION['CATS']->getUserID(),
                    $jobOrderID
                );
            }

            echo json_encode(['success' => true, 'candidateID' => $candidateID]);
        }
        else
        {
            echo json_encode(['success' => false, 'error' => 'Failed to create candidate']);
        }
    }

    /**
     * Extracts a candidate's full name from a resume filename.
     * Strips resume/CV keywords, parenthetical numbers, replaces separators with spaces.
     * Returns [$firstName, $lastName].
     */
    private function extractNameFromFilename($fileName)
    {
        $name = pathinfo($fileName, PATHINFO_FILENAME);

        // Remove separator-prefixed resume/cv suffixes: _Resume, -CV, etc.
        $name = preg_replace('/[\s_\-]+(?:resume|cv|curriculum[\s_\-]*vitae)\b[\s_\-]*/i', ' ', $name);
        // Remove standalone Resume / CV words
        $name = preg_replace('/\b(?:resume|cv|curriculum\s+vitae)\b/i', '', $name);
        // Remove parenthetical noise: (1), (2), ( 1 ), etc.
        $name = preg_replace('/\(\s*\d+\s*\)/u', '', $name);
        // Remove trailing standalone digits
        $name = preg_replace('/\s+\d+\s*$/', '', $name);
        // Replace underscores and hyphens with spaces
        $name = str_replace(['_', '-'], ' ', $name);
        // Collapse multiple spaces
        $name = trim(preg_replace('/\s+/', ' ', $name));

        // Fallback: raw filename without extension (just separator-to-space)
        if (strlen($name) < 2) {
            $name = trim(str_replace(['_', '-'], ' ', pathinfo($fileName, PATHINFO_FILENAME)));
        }

        if (strlen($name) < 2) {
            return ['', ''];
        }

        // Title-case the full name
        $name = mb_convert_case($name, MB_CASE_TITLE, 'UTF-8');

        // Split into firstName / lastName
        $words = array_values(array_filter(preg_split('/\s+/', $name)));
        if (count($words) === 1) {
            return [$words[0], ''];
        }
        $lastName  = array_pop($words);
        $firstName = implode(' ', $words);
        return [$firstName, $lastName];
    }

    /**
     * AJAX handler for bulk importing a resume file
     * Parses the resume to extract candidate data and attaches the file
     */
    private function bulkImportResume()
    {
        // Suppress ALL PHP warnings/notices/deprecations — they break JSON output
        $oldErrorReporting = error_reporting(0);
        @ini_set('display_errors', '0');

        // Prevent any output before JSON
        while (ob_get_level()) { ob_end_clean(); }
        ob_start();
        header('Content-Type: application/json');
        header('Cache-Control: no-cache, must-revalidate');

        // Set error handler to catch PHP errors and convert to exceptions
        set_error_handler(function($errno, $errstr, $errfile, $errline) {
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        });

        try {
            if (!isset($_SESSION['CATS']) || empty($_SESSION['CATS']))
            {
                echo json_encode(['success' => false, 'error' => 'Not logged in. Session may have expired.']);
                return;
            }

            if (!isset($_FILES['resumeFile']))
            {
                echo json_encode(['success' => false, 'error' => 'No file in request']);
                return;
            }
            
            if ($_FILES['resumeFile']['error'] !== UPLOAD_ERR_OK)
            {
                $uploadErrors = [
                    UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
                    UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
                    UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
                    UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                    UPLOAD_ERR_NO_TMP_DIR => 'Missing temp folder',
                    UPLOAD_ERR_CANT_WRITE => 'Failed to write to disk',
                    UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
                ];
                $errorCode = $_FILES['resumeFile']['error'];
                $errorMsg = isset($uploadErrors[$errorCode]) ? $uploadErrors[$errorCode] : 'Unknown error';
                echo json_encode(['success' => false, 'error' => $errorMsg]);
                return;
            }

            $file = $_FILES['resumeFile'];
            $fileName = $file['name'];
            $fileName = basename($fileName);
            $tmpPath = $file['tmp_name'];
            
            $jobOrderID = isset($_POST['jobOrderID']) ? (int)$_POST['jobOrderID'] : 0;
            $jobOrderID = isset($_POST['jobOrderID']) ? (int)$_POST['jobOrderID'] : 0;
            
            // Verify the temp file exists and is readable
            if (!file_exists($tmpPath) || !is_readable($tmpPath)) {
                echo json_encode(['success' => false, 'error' => 'Uploaded file is not accessible']);
                return;
            }

            // Determine content type and document type
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $contentTypes = [
                'pdf' => 'application/pdf',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'txt' => 'text/plain',
                'rtf' => 'application/rtf'
            ];
            $contentType = isset($contentTypes[$ext]) ? $contentTypes[$ext] : 'application/octet-stream';

            // Pre-populate name from filename — text parsing may override this below
            list($firstName, $lastName) = $this->extractNameFromFilename($fileName);
            $email = '';
            $phone = '';
            $city = '';
            $state = '';
            $zip = '';
            $address = '';
            $keySkills = '';
            $notes = '';
            $extractedText = '';
            $currentEmployer = '';
            $linkedin = '';
            $github = '';
            $website = '';

            // Try multiple methods to extract text from the resume
            $documentToText = new DocumentToText();
            $documentType = $documentToText->getDocumentType($fileName, $contentType);
            
            $extractionMethod = 'none';
            
            // Temporarily restore default error handler for text extraction
            // (some functions like gzuncompress may throw warnings we want to suppress)
            restore_error_handler();
            
            // Method 1: Try DocumentToText (external tools like pdftotext, antiword)
            if ($documentType !== false && $documentToText->convert($tmpPath, $documentType))
            {
                $extractedText = $documentToText->getString();
                if (!empty($extractedText)) {
                    $extractionMethod = 'DocumentToText';
                }
            }
            
            // Method 2: For DOCX files, try PHP-based extraction
            if (empty($extractedText) && $ext === 'docx')
            {
                $extractedText = $this->extractTextFromDocx($tmpPath);
                if (!empty($extractedText)) {
                    $extractionMethod = 'PHP-DOCX';
                }
            }
            
            // Method 3: For PDF files, try PHP-based extraction
            if (empty($extractedText) && $ext === 'pdf')
            {
                $extractedText = $this->extractTextFromPdf($tmpPath);
                if (!empty($extractedText)) {
                    $extractionMethod = 'PHP-PDF';
                }
            }
            
            // Method 4: For TXT files, just read the content
            if (empty($extractedText) && $ext === 'txt')
            {
                $extractedText = @file_get_contents($tmpPath);
                if (!empty($extractedText)) {
                    $extractionMethod = 'TXT-Read';
                }
            }
            
            // Method 5: For DOC files, try reading as text (some DOC files have readable text)
            if (empty($extractedText) && $ext === 'doc')
            {
                $extractedText = $this->extractTextFromDoc($tmpPath);
                if (!empty($extractedText)) {
                    $extractionMethod = 'PHP-DOC';
                }
            }
            
            // Re-enable custom error handler
            set_error_handler(function($errno, $errstr, $errfile, $errline) {
                throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
            });
            
            // For DOCX: pull email from hyperlink relationships (mailto:) before cleaning text
            // — email hyperlinks are NOT embedded in the word/document.xml text nodes
            $docxEmail = ($ext === 'docx') ? $this->extractEmailFromDocx($tmpPath) : '';

            // Clean the extracted text to remove garbage
            $extractedText = $this->cleanExtractedText($extractedText);

            // Sanitize to valid UTF-8 — DOCX/ZIP extraction can produce invalid byte sequences
            // that cause json_encode() to silently return false, breaking the AJAX response.
            if (!empty($extractedText)) {
                if (function_exists('mb_convert_encoding')) {
                    $extractedText = mb_convert_encoding($extractedText, 'UTF-8', 'UTF-8');
                }
                // Strip null bytes and non-UTF-8 sequences
                $extractedText = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $extractedText);
                if (!mb_check_encoding($extractedText, 'UTF-8')) {
                    $extractedText = iconv('UTF-8', 'UTF-8//IGNORE', $extractedText);
                }
                $extractedText = $extractedText ?: '';
            }

            // Parse the extracted text to get structured data
            if (!empty($extractedText))
            {
                // Wrap ParseUtility in try-catch to handle any SOAP/network errors gracefully
                try {
                    $parseUtility = new ParseUtility();
                    $parsedData = $parseUtility->documentParse(
                        $fileName,
                        filesize($tmpPath),
                        $contentType,
                        $extractedText
                    );
                } catch (\Throwable $parseException) {
                    // If ParseUtility fails (e.g., SOAP network error), fall back to local parsing
                    $parsedData = false;
                    error_log('ParseUtility failed for ' . $fileName . ': ' . $parseException->getMessage());
                }
                
                if ($parsedData && is_array($parsedData))
                {
                    if (!empty($parsedData['first_name'])) {
                        $firstName = $parsedData['first_name'];
                    }
                    if (!empty($parsedData['last_name'])) {
                        $lastName = $parsedData['last_name'];
                    }
                    if (!empty($parsedData['email_address'])) {
                        $email = $parsedData['email_address'];
                    }
                    if (!empty($parsedData['phone_number'])) {
                        $phone = $parsedData['phone_number'];
                    }
                    if (!empty($parsedData['city'])) {
                        $city = $parsedData['city'];
                    }
                    if (!empty($parsedData['state'])) {
                        $state = $parsedData['state'];
                    }
                    if (!empty($parsedData['zip_code'])) {
                        $zip = $parsedData['zip_code'];
                    }
                    if (!empty($parsedData['us_address'])) {
                        $address = $parsedData['us_address'];
                    }
                    if (!empty($parsedData['skills'])) {
                        $keySkills = $parsedData['skills'];
                    }
                    if (!empty($parsedData['current_employer'])) {
                        $currentEmployer = $parsedData['current_employer'];
                    }
                    if (!empty($parsedData['linkedin'])) {
                        $linkedin = $parsedData['linkedin'];
                    }
                    if (!empty($parsedData['github'])) {
                        $github = $parsedData['github'];
                    }
                    if (!empty($parsedData['website'])) {
                        $website = $parsedData['website'];
                    }
                    
                    // Build comprehensive notes
                    $noteParts = [];
                    if (!empty($parsedData['education'])) {
                        $noteParts[] = "EDUCATION:\n" . $parsedData['education'];
                    }
                    if (!empty($parsedData['experience'])) {
                        $noteParts[] = "EXPERIENCE:\n" . $parsedData['experience'];
                    }
                    if (!empty($parsedData['years_experience']) && $parsedData['years_experience'] > 0) {
                        $noteParts[] = "Estimated Years of Experience: " . $parsedData['years_experience'];
                    }
                    if (!empty($noteParts)) {
                        $notes = implode("\n\n", $noteParts);
                    }
                }
                
                // If ParseUtility didn't find a name (or failed), try direct extraction from text
                if (empty($firstName) && empty($lastName)) {
                    $directName = $this->extractNameDirectly($extractedText);
                    if (!empty($directName['first'])) {
                        $firstName = $directName['first'];
                    }
                    if (!empty($directName['last'])) {
                        $lastName = $directName['last'];
                    }
                }
                
                // Email: prefer DOCX mailto hyperlink, then text regex
                if (empty($email) && !empty($docxEmail)) {
                    $email = $docxEmail;
                }
                if (empty($email)) {
                    if (preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $extractedText, $emailMatch)) {
                        $email = strtolower(trim($emailMatch[0]));
                    }
                }

                // Skills: local section parser when ParseUtility missed it
                if (empty($keySkills)) {
                    $keySkills = $this->extractSkillsFromText($extractedText);
                }

                // Phone: direct regex fallback
                if (empty($phone)) {
                    $phonePatterns = array(
                        '/\+91[\s\-]?\d{5}[\s\-]?\d{5}/',
                        '/\b\d{10}\b/',
                        '/(?:\+\d{1,3}[\s\-]?)?\(?\d{3}\)?[\s.\-]?\d{3}[\s.\-]?\d{4}/',
                    );
                    foreach ($phonePatterns as $pattern) {
                        if (preg_match($pattern, $extractedText, $phoneMatch)) {
                            $phone = trim($phoneMatch[0]);
                            break;
                        }
                    }
                }
            }

            // Apply DOCX mailto email even when extractedText was empty
            if (empty($email) && !empty($docxEmail)) {
                $email = $docxEmail;
            }

            // Quality gate: reject garbage names (too short, like "Tx Ct")
            // A real first name should be at least 3 chars
            if (!empty($firstName) && strlen($firstName) <= 2) {
                $firstName = '';
            }
            if (!empty($lastName) && strlen($lastName) <= 2) {
                $lastName = '';
            }

            // If no valid name was extracted, try to derive from email address
            if (empty($firstName) && empty($lastName) && !empty($email)) {
                $emailName = $this->extractNameFromEmail($email);
                if (!empty($emailName['first'])) {
                    $firstName = $emailName['first'];
                }
                if (!empty($emailName['last'])) {
                    $lastName = $emailName['last'];
                }
            }

            // Final fallback: if name is still empty after all parsing, use email prefix or "Candidate"
            if (empty($firstName) && empty($lastName)) {
                if (!empty($email)) {
                    $emailPrefix = strtolower(explode('@', $email)[0]);
                    $emailPrefix = preg_replace('/\d+$/', '', $emailPrefix);
                    $firstName = (strlen($emailPrefix) >= 3) ? ucfirst($emailPrefix) : 'Candidate';
                } else {
                    $firstName = 'Candidate';
                }
            }
            // If only first name is missing but last is set, derive from email
            if (empty($firstName) && !empty($lastName)) {
                $firstName = 'Candidate';
            }
            // If only last name is missing, that's OK — leave it empty

            // Build website field with LinkedIn/GitHub if available
            $webSiteField = '';
            if (!empty($linkedin)) {
                $webSiteField = $linkedin;
            } elseif (!empty($github)) {
                $webSiteField = $github;
            } elseif (!empty($website)) {
                $webSiteField = $website;
            }
            
            // Append social links to notes if multiple exist
            $socialLinks = [];
            if (!empty($linkedin)) $socialLinks[] = "LinkedIn: " . $linkedin;
            if (!empty($github)) $socialLinks[] = "GitHub: " . $github;
            if (!empty($website)) $socialLinks[] = "Website: " . $website;
            if (count($socialLinks) > 1 || (!empty($webSiteField) && count($socialLinks) > 0)) {
                $notes = (!empty($notes) ? $notes . "\n\n" : '') . "SOCIAL PROFILES:\n" . implode("\n", $socialLinks);
            }
            
            // Create the candidate with parsed data
            $candidates = new Candidates($this->_siteID);
            
            $candidateID = $candidates->add(
                $firstName,
                '',  // middleName
                $lastName,
                $email,
                '',  // email2
                '',  // phoneHome
                $phone,  // phoneCell
                '',  // phoneWork
                $address,  // address
                $city,
                $state,
                $zip,  // zip
                'Bulk Resume Upload',  // source
                $keySkills,
                '',  // dateAvailable
                $currentEmployer,  // currentEmployer
                0,   // canRelocate
                '',  // currentPay
                '',  // desiredPay
                $notes ?: 'Imported via bulk resume upload. Original file: ' . $fileName,
                $webSiteField,  // webSite (LinkedIn/GitHub/Portfolio)
                '',  // bestTimeToCall
                $_SESSION['CATS']->getUserID(),
                $_SESSION['CATS']->getUserID()
            );

            if ($candidateID <= 0)
            {
                echo json_encode(['success' => false, 'error' => 'Failed to create candidate record']);
                return;
            }

            // Candidate created successfully — attachment and pipeline steps run independently.
            // Errors here must never suppress the success response.
            $attachmentID = 0;
            $warning = '';
            $attachmentPath = '';

            // Restore default error handler so warnings from attachment/pipeline code
            // do NOT bubble up as exceptions and silently discard the success response.
            restore_error_handler();

            try {
                // Add to pipeline if job order selected
                if ($jobOrderID > 0) {
                    $pipelines = new Pipelines($this->_siteID);
                    // Cooling period check: 90 days (3 months)
                    if ($pipelines->isInCoolingPeriod($candidateID, $jobOrderID)) {
                        $warning = 'Candidate is within the 3-month reapplication cooling period for this job order and was not added to the pipeline.';
                    } else {
                        $pipelines->add($candidateID, $jobOrderID, $_SESSION['CATS']->getUserID());
                    }

                    if (empty($warning)) {
                        $activityEntries = new ActivityEntries($this->_siteID);
                        $activityEntries->add(
                            $candidateID,
                            DATA_ITEM_CANDIDATE,
                            400,
                            'Added candidate to job order.',
                            $_SESSION['CATS']->getUserID(),
                            $jobOrderID
                        );
                    }
                }

                // Attach the uploaded file to the candidate record
                $attachmentCreator = new AttachmentCreator($this->_siteID);

                $attachSuccess = $attachmentCreator->createFromFile(
                    DATA_ITEM_CANDIDATE,
                    $candidateID,
                    $tmpPath,
                    $fileName,
                    $contentType,
                    false,  // extractText — already done above
                    true    // fileExists
                );

                if ($attachSuccess) {
                    $attachmentID   = $attachmentCreator->getAttachmentID();
                    $attachmentPath = $attachmentCreator->getContainingDirectory();
                    $this->markAttachmentAsResume($attachmentID, $extractedText);
                } else {
                    // Fall back to manual copy
                    $altResult = $this->attachResumeManually(
                        $candidateID, $tmpPath, $fileName, $contentType, $extractedText
                    );
                    if ($altResult['success']) {
                        $attachmentID = $altResult['attachmentID'];
                        $warning = 'Used alternative attachment method';
                    } else {
                        $warning = 'Resume attached but file could not be saved: ' . $attachmentCreator->getError();
                        error_log('Bulk import attachment error for ' . $fileName . ': ' . $attachmentCreator->getError());
                    }
                }
            } catch (\Throwable $attachErr) {
                // Attachment failure — candidate is already saved; log and continue.
                $warning = 'Attachment error (candidate saved): ' . $attachErr->getMessage();
                error_log('Bulk import attachment exception for ' . $fileName . ': ' . $attachErr->getMessage());
            }

            $jsonFlags = defined('JSON_INVALID_UTF8_SUBSTITUTE') ? JSON_INVALID_UTF8_SUBSTITUTE : 0;
            echo json_encode([
                'success'         => true,
                'candidateID'     => $candidateID,
                'attachmentID'    => $attachmentID,
                'name'            => trim($firstName . ' ' . $lastName),
                'email'           => $email,
                'phone'           => $phone,
                'city'            => $city,
                'state'           => $state,
                'zip'             => $zip,
                'address'         => $address,
                'currentEmployer' => $currentEmployer,
                'linkedin'        => $linkedin,
                'github'          => $github,
                'website'         => $website,
                'skills'          => $keySkills ? substr($keySkills, 0, 150) . (strlen($keySkills) > 150 ? '...' : '') : '',
                'warning'         => $warning,
                'debug'           => [
                    'parsed'            => !empty($extractedText),
                    'textLength'        => strlen($extractedText),
                    'extractionMethod'  => $extractionMethod,
                    'textPreview'       => substr($extractedText, 0, 300),
                    'attachmentPath'    => $attachmentPath
                ]
            ], $jsonFlags);
            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fallback: strip non-ASCII and retry
                echo json_encode([
                    'success'     => true,
                    'candidateID' => $candidateID,
                    'name'        => trim($firstName . ' ' . $lastName),
                    'email'       => $email,
                    'warning'     => 'Text encoding issue - candidate saved without full details'
                ]);
            }
            
        } catch (Exception $e) {
            restore_error_handler();
            echo json_encode([
                'success' => false, 
                'error' => 'Exception: ' . $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine()
            ]);
            return;
        } catch (Error $e) {
            restore_error_handler();
            echo json_encode([
                'success' => false, 
                'error' => 'PHP Error: ' . $e->getMessage(),
                'file' => basename($e->getFile()),
                'line' => $e->getLine()
            ]);
            return;
        }
        
        restore_error_handler();

        // Flush output buffer, strip any PHP warnings that leaked before JSON
        $output = ob_get_clean();
        if ($output && strpos($output, '{') !== false) {
            $output = substr($output, strpos($output, '{'));
        }
        echo $output;
        error_reporting($oldErrorReporting);
    }

    /**
     * Extract the email address from a DOCX file by reading mailto: hyperlinks
     * stored in word/_rels/document.xml.rels (they are NOT in the text nodes).
     */
    private function extractEmailFromDocx($filePath)
    {
        if (!class_exists('ZipArchive')) return '';

        $zip = new ZipArchive();
        if ($zip->open($filePath) !== true) return '';

        // Relationships file that holds external hyperlinks
        $relsXml = $zip->getFromName('word/_rels/document.xml.rels');
        $zip->close();

        if (empty($relsXml)) return '';

        // Extract all mailto: targets
        if (preg_match_all('/Target="mailto:([^"]+)"/i', $relsXml, $m)) {
            foreach ($m[1] as $addr) {
                $addr = trim($addr);
                if (filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                    return strtolower($addr);
                }
            }
        }
        return '';
    }

    /**
     * Extract skills from plain text using heuristic section detection.
     * Looks for sections labelled SKILLS / TECHNICAL SKILLS / KEY SKILLS / COMPETENCIES.
     */
    private function extractSkillsFromText($text)
    {
        if (empty($text)) return '';

        $lines  = preg_split('/\r?\n/', $text);
        $skills = [];
        $inSkills = false;
        $sectionPattern = '/^(?:technical\s+skills?|key\s+skills?|skills?\s*(?:&|and)?\s*(?:competencies|expertise)?|competencies|soft\s+skills?|core\s+skills?|areas?\s+of\s+expertise|proficiencies?)\s*:?\s*$/i';
        // Pattern that looks like a new major section (all-caps, or known section keywords)
        $sectionBreakPattern = '/^\s*(?:EDUCATION|EXPERIENCE|PROFILE|SUMMARY|OBJECTIVE|EMPLOYMENT|WORK\s+HISTORY|CERTIFICATIONS?|PROJECTS?|AWARDS?|ACHIEVEMENTS?|ACTIVITIES?|REFERENCES?|INTERNSHIP|VOLUNTEERING)\b/i';

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (preg_match($sectionPattern, $trimmed)) {
                $inSkills = true;
                continue;
            }

            if ($inSkills) {
                // Stop at the next major section heading
                if (!empty($trimmed) && preg_match($sectionBreakPattern, $trimmed)) {
                    break;
                }
                // Skip empty lines
                if (empty($trimmed)) continue;
                // Skip lines that look like section sub-headings (very short, all caps)
                if (strlen($trimmed) < 3) continue;
                // Strip leading bullet characters
                $skill = preg_replace('/^[\-\*\•\◦\▪\➢\✓\✔\>\·o]+\s*/', '', $trimmed);
                $skill = trim($skill);
                if (!empty($skill) && strlen($skill) < 120) {
                    $skills[] = $skill;
                }
            }
        }

        // Also try inline comma/semicolon-separated lists if no bullet skills found
        if (empty($skills)) {
            // Reset and look for "Skills: X, Y, Z" style on one line
            foreach ($lines as $line) {
                if (preg_match('/(?:skills?|competencies|expertise)\s*:\s*(.+)/i', $line, $m)) {
                    $inline = trim($m[1]);
                    if (!empty($inline)) {
                        $parts = preg_split('/[,;|]+/', $inline);
                        foreach ($parts as $p) {
                            $p = trim($p);
                            if (!empty($p)) $skills[] = $p;
                        }
                    }
                }
            }
        }

        return implode(', ', array_unique($skills));
    }

    /**
     * Extract text from DOCX file using PHP ZipArchive
     */
    private function extractTextFromDocx($filePath)
    {
        if (!class_exists('ZipArchive')) {
            error_log('ZipArchive class not available for DOCX extraction');
            return '';
        }

        $zip = new ZipArchive();
        $openResult = $zip->open($filePath);
        if ($openResult !== true) {
            error_log('Failed to open DOCX file: ' . $filePath . ' - Error code: ' . $openResult);
            return '';
        }

        $text = '';

        // Try to get document.xml
        $content = $zip->getFromName('word/document.xml');
        
        if (empty($content)) {
            // Try alternative paths
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = $zip->getNameIndex($i);
                if (stripos($name, 'document.xml') !== false) {
                    $content = $zip->getFromIndex($i);
                    break;
                }
            }
        }
        
        $zip->close();
        
        if (empty($content)) {
            error_log('No document.xml found in DOCX file: ' . $filePath);
            return '';
        }
        
        // Method 1: Use DOMDocument for proper XML parsing (paragraph-aware)
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadXML($content);
        libxml_clear_errors();

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');

        // Extract text paragraph by paragraph to preserve structure
        $paraNodes = $xpath->query('//w:p');
        $paragraphTexts = array();

        if ($paraNodes->length > 0) {
            foreach ($paraNodes as $para) {
                $paraText = '';
                $textInPara = $xpath->query('.//w:t', $para);
                foreach ($textInPara as $tNode) {
                    $paraText .= $tNode->textContent;
                }
                $paraText = trim($paraText);
                if (!empty($paraText)) {
                    $paragraphTexts[] = $paraText;
                }
            }
            $text = implode("\n", $paragraphTexts);
        }

        // Fallback: try flat extraction if paragraph method got nothing
        if (empty(trim($text))) {
            $textNodes = $xpath->query('//w:t');
            if ($textNodes->length > 0) {
                $parts = array();
                foreach ($textNodes as $node) {
                    $parts[] = $node->textContent;
                }
                $text = implode(' ', $parts);
            }
        }
        
        // Method 2: Fallback to regex if DOMDocument didn't work
        if (empty(trim($text))) {
            // Use regex to extract text from w:t tags
            if (preg_match_all('/<w:t[^>]*>([^<]*)<\/w:t>/i', $content, $matches)) {
                $text = implode(' ', $matches[1]);
            }
        }
        
        // Method 3: Strip all tags as last resort
        if (empty(trim($text))) {
            $content = str_replace('</w:p>', "\n", $content);
            $content = str_replace('</w:r>', ' ', $content);
            $text = strip_tags($content);
        }
        
        // Clean up — preserve newlines so paragraph structure is kept
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = preg_replace('/[ \t]+/', ' ', $text);          // collapse spaces/tabs only, not newlines
        $text = preg_replace('/[ \t]*\n[ \t]*/', "\n", $text); // trim spaces around newlines
        $text = preg_replace('/\n{3,}/', "\n\n", $text);       // max two consecutive blank lines

        return trim($text);
    }
    
    /**
     * Extract name directly from resume text using multiple strategies
     */
    /**
     * Extract a likely first/last name from an email address
     * e.g., anushreddydasari@gmail.com → Anushreddy Dasari
     *        john.doe@company.com → John Doe
     *        sanjayvemula15@gmail.com → Sanjay Vemula
     */
    private function extractNameFromEmail($email)
    {
        $result = array('first' => '', 'last' => '');
        if (empty($email)) return $result;

        // Get the part before @
        $prefix = strtolower(explode('@', $email)[0]);

        // Remove trailing numbers (sanjay15 → sanjay)
        $prefix = preg_replace('/\d+$/', '', $prefix);

        // Remove common prefixes/suffixes
        $prefix = preg_replace('/^(mr|ms|mrs|dr)[\.\-_]?/', '', $prefix);

        if (empty($prefix)) return $result;

        // Strategy 1: Split on dots, underscores, or hyphens (john.doe, john_doe, john-doe)
        if (preg_match('/^([a-z]+)[._\-]([a-z]+)$/', $prefix, $m)) {
            $result['first'] = ucfirst($m[1]);
            $result['last'] = ucfirst($m[2]);
            return $result;
        }

        // Strategy 2: Split on camelCase boundary (unlikely in emails, but check)
        if (preg_match('/^([a-z]+)([A-Z][a-z]+)$/', explode('@', $email)[0], $m)) {
            $result['first'] = ucfirst(strtolower($m[1]));
            $result['last'] = ucfirst(strtolower($m[2]));
            return $result;
        }

        // Strategy 3: Try to split a run of lowercase letters into first+last name
        // by matching against common Indian/Western last name endings
        $lastNamePatterns = array(
            'kumar', 'reddy', 'sharma', 'gupta', 'singh', 'verma', 'patel', 'das',
            'nair', 'menon', 'iyer', 'rao', 'naidu', 'chandra', 'prasad', 'murthy',
            'varma', 'pillai', 'sethi', 'mehta', 'shah', 'jain', 'mishra', 'pandey',
            'tiwari', 'chauhan', 'yadav', 'thakur', 'saxena', 'agarwal', 'bansal',
            'dasari', 'vemula', 'chimita', 'oggu', 'sri', 'dhannapaneni',
            'smith', 'jones', 'brown', 'wilson', 'taylor', 'anderson', 'thomas',
            'jackson', 'white', 'harris', 'martin', 'thompson', 'garcia', 'martinez',
            'robinson', 'clark', 'rodriguez', 'lewis', 'lee', 'walker', 'hall', 'allen',
            'young', 'king', 'wright', 'lopez', 'hill', 'scott', 'green', 'adams',
            'baker', 'nelson', 'carter', 'mitchell', 'perez', 'roberts', 'turner',
            'phillips', 'campbell', 'parker', 'evans', 'edwards', 'collins', 'stewart',
            'morris', 'murphy', 'cook', 'rogers', 'morgan', 'cooper', 'reed', 'bailey',
            'bell', 'howard', 'ward', 'cox', 'james', 'watson', 'brooks', 'kelly'
        );

        foreach ($lastNamePatterns as $last) {
            if (strlen($prefix) > strlen($last) && substr($prefix, -strlen($last)) === $last) {
                $first = substr($prefix, 0, -strlen($last));
                if (strlen($first) >= 2) {
                    $result['first'] = ucfirst($first);
                    $result['last'] = ucfirst($last);
                    return $result;
                }
            }
        }

        // Strategy 4: If prefix is long enough, try splitting roughly in half
        // looking for vowel-consonant boundaries (min 3 chars each side)
        if (strlen($prefix) >= 6) {
            $mid = intval(strlen($prefix) / 2);
            // Look for a good split point near the middle (consonant after vowel)
            // Ensure both parts are at least 3 chars
            $bestSplit = $mid;
            for ($i = max(3, $mid - 3); $i <= min(strlen($prefix) - 3, $mid + 3); $i++) {
                if (preg_match('/[aeiou]/i', $prefix[$i-1]) && preg_match('/[^aeiou]/i', $prefix[$i])) {
                    $bestSplit = $i;
                    break;
                }
            }
            $result['first'] = ucfirst(substr($prefix, 0, $bestSplit));
            $result['last'] = ucfirst(substr($prefix, $bestSplit));
            return $result;
        }

        // Strategy 5: Just use the whole prefix as first name
        $result['first'] = ucfirst($prefix);
        return $result;
    }

    private function extractNameDirectly($text)
    {
        $result = array('first' => '', 'last' => '');
        
        if (empty($text)) {
            return $result;
        }
        
        // Strategy 1: Look for ALL CAPS name at the beginning (common in resumes)
        // Require 3+ chars per word to avoid garbage like "TX CT" from PDF
        if (preg_match('/^[\s]*([A-Z]{3,}(?:\s+[A-Z]{3,})+)/m', $text, $matches)) {
            $nameParts = preg_split('/\s+/', trim($matches[1]));
            if (count($nameParts) >= 2 && strlen($nameParts[0]) >= 3 && strlen(end($nameParts)) >= 3) {
                $result['first'] = ucfirst(strtolower($nameParts[0]));
                $result['last'] = ucfirst(strtolower(end($nameParts)));
                return $result;
            }
        }
        
        // Strategy 2: Look for "Name: Firstname Lastname" pattern
        if (preg_match('/(?:name|candidate)\s*[:]\s*([A-Za-z]+)\s+([A-Za-z]+)/i', $text, $matches)) {
            $result['first'] = ucfirst(strtolower($matches[1]));
            $result['last'] = ucfirst(strtolower($matches[2]));
            return $result;
        }
        
        // Strategy 3: Find name near email - look for "Name email@domain.com" pattern
        if (preg_match('/([A-Z][a-z]+(?:\s+[A-Z][a-z]+)+)\s*[\|\-]?\s*[a-zA-Z0-9._%+\-]+@/i', $text, $matches)) {
            $nameParts = preg_split('/\s+/', trim($matches[1]));
            if (count($nameParts) >= 2) {
                $result['first'] = ucfirst(strtolower($nameParts[0]));
                $result['last'] = ucfirst(strtolower(end($nameParts)));
                return $result;
            }
        }
        
        // Strategy 4: Look for capitalized words in the first 200 characters
        $firstPart = substr($text, 0, 200);
        $lines = preg_split('/[\n\r]+/', $firstPart);
        
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || strlen($line) > 60) continue;
            
            // Skip lines with common non-name content
            if (preg_match('/@|http|www\.|phone|email|address|resume|cv|curriculum|objective|summary|profile|experience|education|skills/i', $line)) {
                continue;
            }
            
            // Skip lines with too many numbers
            if (preg_match('/\d{3,}/', $line)) continue;
            
            // Clean the line
            $cleanLine = preg_replace('/[^A-Za-z\s]/', '', $line);
            $words = preg_split('/\s+/', trim($cleanLine));
            $words = array_filter($words, function($w) { return strlen($w) >= 2; });
            $words = array_values($words);
            
            if (count($words) >= 2 && count($words) <= 4) {
                // Check if words look like names (capitalized)
                $allCapitalized = true;
                foreach ($words as $word) {
                    if (!preg_match('/^[A-Z]/', $word)) {
                        $allCapitalized = false;
                        break;
                    }
                }
                
                if ($allCapitalized) {
                    $result['first'] = ucfirst(strtolower($words[0]));
                    $result['last'] = ucfirst(strtolower(end($words)));
                    return $result;
                }
            }
        }
        
        // Strategy 5: Just take the first two capitalized words
        if (preg_match('/([A-Z][a-z]{1,15})\s+([A-Z][a-z]{1,15})/', $text, $matches)) {
            // Make sure these aren't common words
            $commonWords = array('the', 'and', 'for', 'are', 'but', 'not', 'you', 'all', 'can', 'her', 'was', 'one', 'our', 'out', 'Resume', 'Profile', 'Summary', 'Objective', 'Education', 'Experience', 'Skills', 'Contact', 'Address', 'Phone', 'Email', 'Bachelor', 'Master', 'University', 'College', 'School', 'Company', 'Manager', 'Developer', 'Engineer', 'Designer', 'Analyst');
            
            if (!in_array($matches[1], $commonWords) && !in_array($matches[2], $commonWords)) {
                $result['first'] = $matches[1];
                $result['last'] = $matches[2];
                return $result;
            }
        }
        
        return $result;
    }
    
    /**
     * Extract text from PDF file using basic PHP methods
     */
    private function extractTextFromPdf($filePath)
    {
        $content = @file_get_contents($filePath);
        if (empty($content)) {
            return '';
        }
        
        $text = '';
        $allTextParts = array();
        
        // Try to extract text from PDF streams
        if (preg_match_all('/stream\s*(.+?)\s*endstream/s', $content, $matches)) {
            foreach ($matches[1] as $stream) {
                // Try to decompress - use try/catch to handle errors gracefully
                $decompressedStream = $stream;
                try {
                    $decompressed = @gzuncompress($stream);
                    if ($decompressed !== false) {
                        $decompressedStream = $decompressed;
                    }
                } catch (Exception $e) {
                    // gzuncompress failed, try gzinflate
                    try {
                        $decompressed = @gzinflate($stream);
                        if ($decompressed !== false) {
                            $decompressedStream = $decompressed;
                        }
                    } catch (Exception $e2) {
                        // Both failed, use original stream
                    }
                }
                
                // Extract text between parentheses (PDF text objects)
                if (preg_match_all('/\(([^)]+)\)/', $decompressedStream, $textMatches)) {
                    foreach ($textMatches[1] as $match) {
                        // Skip metadata markers
                        if (strpos($match, 'en-US') !== false || 
                            strpos($match, 'en-GB') !== false ||
                            preg_match('/^[A-Z]{2,3}$/', $match)) {
                            continue;
                        }
                        // Decode PDF escape sequences
                        $match = $this->decodePdfString($match);
                        if (strlen(trim($match)) > 0) {
                            $allTextParts[] = $match;
                        }
                    }
                }
                
                // Extract text from Tj/TJ operators
                if (preg_match_all('/\[([^\]]+)\]\s*TJ/i', $decompressedStream, $tjMatches)) {
                    foreach ($tjMatches[1] as $tj) {
                        if (preg_match_all('/\(([^)]+)\)/', $tj, $innerMatches)) {
                            $decoded = array_map(array($this, 'decodePdfString'), $innerMatches[1]);
                            $allTextParts[] = implode('', $decoded);
                        }
                    }
                }
                
                // Also try BT...ET text blocks
                if (preg_match_all('/BT\s*(.+?)\s*ET/s', $decompressedStream, $btMatches)) {
                    foreach ($btMatches[1] as $btContent) {
                        if (preg_match_all('/\(([^)]+)\)\s*Tj/i', $btContent, $tjMatches2)) {
                            $decoded = array_map(array($this, 'decodePdfString'), $tjMatches2[1]);
                            $allTextParts[] = implode(' ', $decoded);
                        }
                    }
                }
            }
        }
        
        // Join all text parts
        $text = implode(' ', $allTextParts);
        
        // If we didn't get much text, try a simpler approach - look for readable strings
        if (strlen($text) < 100) {
            // Look for sequences of printable characters
            if (preg_match_all('/[\x20-\x7E]{4,}/', $content, $readableMatches)) {
                $readable = array();
                foreach ($readableMatches[0] as $match) {
                    // Skip binary-looking strings and PDF commands
                    if (preg_match('/^[A-Za-z\s\.\,\-\'\@\(\)0-9]+$/', $match) && 
                        !preg_match('/^(obj|endobj|stream|endstream|xref|trailer)$/i', trim($match))) {
                        $readable[] = $match;
                    }
                }
                if (count($readable) > 0) {
                    $text = implode(' ', $readable);
                }
            }
        }
        
        // Clean up the text - remove non-printable characters but keep letters, numbers, punctuation
        $text = preg_replace('/[^\x20-\x7E\n\r\t]/', ' ', $text);
        // Remove language tags like en-US
        $text = preg_replace('/\ben-[A-Z]{2}\b/i', '', $text);
        // Remove PDF operators that leaked through
        $text = preg_replace('/\b(Tj|TJ|BT|ET|Tm|Td|Tf|Tc|Tw|Tz|TL|Ts|Tr)\b/', '', $text);
        // Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }
    
    /**
     * Decode PDF escape sequences in a string
     */
    private function decodePdfString($str)
    {
        // Handle PDF escape sequences
        $str = str_replace('\\n', "\n", $str);
        $str = str_replace('\\r', "\r", $str);
        $str = str_replace('\\t', "\t", $str);
        $str = str_replace('\\(', '(', $str);
        $str = str_replace('\\)', ')', $str);
        $str = str_replace('\\\\', '\\', $str);
        
        // Handle octal escapes like \000
        $str = preg_replace_callback('/\\\\([0-7]{1,3})/', function($m) {
            return chr(octdec($m[1]));
        }, $str);
        
        return $str;
    }
    
    /**
     * Extract text from DOC file (older Word format)
     */
    private function extractTextFromDoc($filePath)
    {
        $content = @file_get_contents($filePath);
        if (empty($content)) {
            return '';
        }
        
        // Check if it's actually an RTF file
        if (substr($content, 0, 5) === '{\rtf') {
            return $this->extractTextFromRtf($content);
        }
        
        // Try to extract readable ASCII text from DOC
        $text = '';
        
        // DOC files have text in certain positions - try to find readable chunks
        if (preg_match_all('/[\x20-\x7E]{20,}/', $content, $matches)) {
            $text = implode(' ', $matches[0]);
        }
        
        return trim($text);
    }
    
    /**
     * Extract text from RTF content
     */
    private function extractTextFromRtf($content)
    {
        // Remove RTF control words and groups
        $text = preg_replace('/\{[^}]*\}/', '', $content);
        $text = preg_replace('/\\\\[a-z]+\d*\s?/i', '', $text);
        $text = preg_replace('/[{}]/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        
        return trim($text);
    }
    
    /**
     * Clean extracted text to remove garbage and normalize
     */
    private function cleanExtractedText($text)
    {
        if (empty($text)) {
            return '';
        }
        
        // Remove language tags (en-US, en-GB, etc.)
        $text = preg_replace('/\ben-[A-Z]{2}\b/i', '', $text);
        $text = preg_replace('/\b[a-z]{2}-[A-Z]{2}\b/', '', $text);
        
        // Remove PDF metadata markers
        $text = preg_replace('/\bPDF-\d+\.\d+\b/', '', $text);
        $text = preg_replace('/\b(Producer|Creator|Author|Title|Subject|Keywords)\s*:/i', '', $text);
        
        // Remove excessive special characters
        $text = preg_replace('/[^\w\s@.\-,;:\'\"()\[\]\/\\\\+&%$#!?=*<>]+/', ' ', $text);
        
        // Remove isolated single characters that are likely garbage
        // But keep: I, A, and characters near punctuation (e.g., "C++", "R&D")
        $text = preg_replace('/(?<!\w)\b[b-hj-zB-HJ-Z]\b(?!\w)(?!\s*[.@+&])/', '', $text);
        
        // Normalize whitespace — preserve newlines so paragraph structure is kept
        $text = preg_replace('/[ \t]+/', ' ', $text);          // collapse spaces/tabs only
        $text = preg_replace('/[ \t]*\n[ \t]*/', "\n", $text); // trim spaces around newlines
        $text = preg_replace('/\n{3,}/', "\n\n", $text);       // max two consecutive blank lines

        return trim($text);
    }
    
    /**
     * Mark an attachment as a resume in the database
     */
    private function markAttachmentAsResume($attachmentID, $resumeText = '')
    {
        $db = DatabaseConnection::getInstance();
        
        $sql = sprintf(
            "UPDATE attachment SET resume = 1, text = %s WHERE attachment_id = %d",
            $db->makeQueryString($resumeText),
            $attachmentID
        );
        
        $db->query($sql);
    }
    
    /**
     * Manually attach a resume file when AttachmentCreator fails
     */
    private function attachResumeManually($candidateID, $tmpPath, $fileName, $contentType, $extractedText = '')
    {
        $result = ['success' => false, 'error' => '', 'attachmentID' => 0];
        
        try {
            // Make a safe filename
            $safeFileName = FileUtility::makeSafeFilename($fileName);
            
            // First, add the attachment record with empty directory (like AttachmentCreator does)
            $attachments = new Attachments($this->_siteID);
            
            // Calculate file size and MD5
            $fileSize = intval(@filesize($tmpPath) / 1024);
            $md5sum = @md5_file($tmpPath);
            
            $attachmentID = $attachments->add(
                DATA_ITEM_CANDIDATE,
                $candidateID,
                pathinfo($fileName, PATHINFO_FILENAME),  // title
                $fileName,                               // originalFilename
                $safeFileName,                           // storedFilename
                $contentType,                            // contentType
                true,                                    // isResume
                $extractedText,                          // resumeText
                false,                                   // isProfileImage
                '',                                      // directoryName (empty initially)
                $fileSize,                               // fileSize
                $md5sum                                  // md5sum
            );
            
            if ($attachmentID <= 0) {
                $result['error'] = 'Failed to insert attachment record';
                return $result;
            }
            
            // Now create the directory structure like AttachmentCreator does
            // ./attachments/site_X/Yxxx/uniquename/
            
            // Make sure attachments directory exists
            if (!is_dir('./attachments')) {
                @mkdir('./attachments', 0777);
                @touch('./attachments/index.php');
            }
            
            // Site directory
            $siteDirectory = './attachments/site_' . $this->_siteID;
            if (!is_dir($siteDirectory)) {
                @mkdir($siteDirectory, 0777);
                @file_put_contents($siteDirectory . '/index.php', "\n");
            }
            
            // ID group directory (groups of 1000)
            $IDGroupDirectory = sprintf('%s/%sxxx', $siteDirectory, ((int) ($attachmentID / 1000)));
            if (!is_dir($IDGroupDirectory)) {
                @mkdir($IDGroupDirectory, 0777);
                @file_put_contents($IDGroupDirectory . '/index.php', "\n");
            }
            
            // Unique directory for this attachment
            $uniqueDirName = FileUtility::getUniqueDirectory($IDGroupDirectory, $safeFileName);
            $uniqueDirectory = $IDGroupDirectory . '/' . $uniqueDirName . '/';
            
            if (!is_dir($uniqueDirectory)) {
                @mkdir($uniqueDirectory, 0777);
            }
            
            if (!is_dir($uniqueDirectory)) {
                $attachments->delete($attachmentID, false);
                $result['error'] = 'Failed to create attachment directory: ' . $uniqueDirectory;
                return $result;
            }
            
            // Copy the file to the unique directory
            $destPath = $uniqueDirectory . $safeFileName;
            
            if (is_uploaded_file($tmpPath)) {
                if (!@move_uploaded_file($tmpPath, $destPath)) {
                    if (!@copy($tmpPath, $destPath)) {
                        $attachments->delete($attachmentID, false);
                        $result['error'] = 'Failed to move/copy uploaded file';
                        return $result;
                    }
                }
            } else {
                if (!@copy($tmpPath, $destPath)) {
                    $attachments->delete($attachmentID, false);
                    $result['error'] = 'Failed to copy file';
                    return $result;
                }
            }
            
            // Verify file was copied
            if (!file_exists($destPath)) {
                $attachments->delete($attachmentID, false);
                $result['error'] = 'File does not exist after copy';
                return $result;
            }
            
            // Update the attachment record with the directory name
            // The directory name stored should be relative: site_X/Yxxx/uniquename
            $relativeDir = sprintf('site_%s/%sxxx/%s', $this->_siteID, ((int) ($attachmentID / 1000)), $uniqueDirName);
            $attachments->setDirectoryName($attachmentID, $relativeDir);
            
            $result['success'] = true;
            $result['attachmentID'] = $attachmentID;
            
        } catch (Exception $e) {
            $result['error'] = 'Exception: ' . $e->getMessage();
        }
        
        return $result;
    }
}

?>
