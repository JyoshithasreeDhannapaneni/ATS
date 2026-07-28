<?php
/*
 * CATS
 * Careers Module
 *
 * Copyright (C) 2005 - 2007 Cognizo Technologies, Inc.
 * All rights reserved.
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
 * $Id: CareersUI.php 3812 2007-12-05 21:33:28Z andrew $
 */

include_once(LEGACY_ROOT . '/lib/CareerPortal.php');
include_once(LEGACY_ROOT . '/lib/JobOrders.php');
include_once(LEGACY_ROOT . '/lib/Candidates.php');
include_once(LEGACY_ROOT . '/lib/Site.php');
include_once(LEGACY_ROOT . '/lib/Companies.php');
include_once(LEGACY_ROOT . '/lib/Contacts.php');
include_once(LEGACY_ROOT . '/lib/Users.php');
include_once(LEGACY_ROOT . '/lib/FileUtility.php');
include_once(LEGACY_ROOT . '/lib/ActivityEntries.php');
include_once(LEGACY_ROOT . '/lib/DocumentToText.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');
include_once(LEGACY_ROOT . '/lib/DatabaseSearch.php');
include_once(LEGACY_ROOT . '/lib/CommonErrors.php');
include_once(LEGACY_ROOT . '/lib/Questionnaire.php');
include_once(LEGACY_ROOT . '/lib/DocumentToText.php');
include_once(LEGACY_ROOT . '/lib/FileUtility.php');
include_once(LEGACY_ROOT . '/lib/ParseUtility.php');

class CareersUI extends UserInterface
{
    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = false;
        $this->_moduleDirectory = 'careers';
        $this->_moduleName = 'careers';
    }


    public function handleRequest()
    {
        $action = $this->getAction();

        switch ($action)
        {
            default:
                $this->careersPage();
                break;
        }
    }

    private function careersPage()
    {
        global $careerPage;

        /* Get information on what site we are in, our environment, etc. */

        $site = new Site(-1);

        $siteID = $site->getFirstSiteID();

        if (!eval(Hooks::get('CAREERS_SITEID'))) return;

        $siteRS = $site->getSiteBySiteID($siteID);

        if (!isset($siteRS['name']))
        {
            die('An error has occurred:  No site exists with this site name.');
        }

        $siteName = $siteRS['name'];

        /* Get information on the current template. */

        $careerPortalSettings = new CareerPortalSettings($siteID);
        $careerPortalSettingsRS = $careerPortalSettings->getAll();

        $templateName = $careerPortalSettingsRS['activeBoard'];
        $enabled = $careerPortalSettingsRS['enabled'];

        if ($enabled == 0)
        {
            // FIXME: Generate valid XHTML error pages. Create an error/fatal method!
            die('<html><body><!-- Job Board Disabled --></body></html>');
        }

        if (isset($_GET['templateName']))
        {
            $templateName = $_GET['templateName'];
        }

        $template = $careerPortalSettings->getTemplate($templateName);

        /* At this point the entire template is loaded, we just need to add data to the
           template for the specific page. */

        /* Get all public job orders for this site. */
        $jobOrders = new JobOrders($siteID);
        $rs = $jobOrders->getAll(JOBORDERS_STATUS_SHARE, -1, -1, -1, false, true);

        $useCookie = true;

        // Get the get or post page request
        $p = isset($_GET['p']) ? $_GET['p'] : '';
        $p = isset($_POST['p']) ? $_POST['p'] : $p;

        // Get the get or post sub-page request
        $pa = isset($_GET['pa']) ? $_GET['pa'] : '';
        $pa = isset($_POST['pa']) ? $_POST['pa'] : $pa;

        $isRegistrationEnabled = $careerPortalSettingsRS['candidateRegistration'];

        switch ($pa)
        {
            case 'logout':
                if ($isRegistrationEnabled)
                {
                    // Remove the saved information cookie
                    setcookie($this->getCareerPortalCookieName($siteID), '');
                    $useCookie = false;
                }
                break;

            case 'updateProfile':
                if ($isRegistrationEnabled)
                {
                    $p = 'registeredCandidateProfile';
                }
                break;
        }

        if ($p == 'showAll')
        {
            $template['Content'] = $template['Content - Search Results'];

            $template['Content'] = str_replace('<numberOfSearchResults>', count($rs), $template['Content']);
            $template['Content'] = str_replace('<registeredCandidate>', $useCookie && $isRegistrationEnabled ? $this->getRegisteredCandidateBlock($siteID, $template['Content - Candidate Registration']) : '', $template['Content']);

            if ($careerPortalSettingsRS['allowBrowse'] == 1)
            {
                /* Legacy. */
                $template['Content'] = str_replace('<searchResultsTableUnformatted>', $this->getResultsTable($rs, $careerPortalSettingsRS, true), $template['Content']);

                while (strpos($template['Content'], '<searchResultsTable') !== false)
                {
                    $searchResultsTablePosition = strpos($template['Content'], '<searchResultsTable');

                    $temp = substr($template['Content'], $searchResultsTablePosition + strlen('<searchResultsTable'));
                    $searchResultsTableParameters = trim(substr($temp, 0, strpos($temp, '>') - 1));

                    $tableHTML = $this->getResultsTable($rs, $careerPortalSettingsRS, false, $searchResultsTableParameters);

                    $template['Content'] = substr($template['Content'], 0, $searchResultsTablePosition - 1) . $tableHTML . substr($temp, strpos($temp, '>') + 1);
                }
            }
            else
            {
                $template['Content'] = str_replace('<searchResultsTable>', 'Sorry, Job Listings have been disabled by the '.$siteName.' administrator.', $template['Content']);
            }
        }
        else if ($p == 'search')
        {
        }
        else if ($p == 'registeredCandidateProfile' && $isRegistrationEnabled)
        {
            $content = $template['Content - Candidate Profile'];

            // Get information about the candidate from the cookie
            $fields = $this->getCookieFields($siteID);
            $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration'], $fields);
            if ($candidate === false)
            {
                echo '<html><body>You have not registered yet.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            // Get the candidate's latest resume attachment (if exists)
            $attachmentsLib = new Attachments($siteID);
            $attachments = $attachmentsLib->getAll(DATA_ITEM_CANDIDATE, $candidate['candidateID']);

            $latestDate = 0;
            $latestAttachment = false;
            foreach ($attachments as $attachment)
            {
                if (preg_match('/^([0-9]{2})-([0-9]{2})-([0-9]{2}) \(([0-9]{2}):([0-9]{2}):([0-9]{2}) [A-Z]{2}\)$/',
                    $attachment['dateCreated'], $matches))
                {
                    $epoch = strtotime( strval($matches[1]) . '/' . strval($matches[2]) . '/' . strval($matches[3]) );

                    if ($epoch > $latestDate)
                    {
                        $latestDate = $epoch;
                        $latestAttachment = $attachment['attachmentID'];
                    }
                }
            }

            // Get their latest resume
            if ($latestAttachment !== false)
            {
                $candidatesLib = new Candidates($siteID);
                $myResume = $candidatesLib->getResume($latestAttachment);
            }

            /* Replace input fields. */
            $content = str_replace('<input-firstName>', '<input name="firstName" id="firstName" class="inputBoxName" value="' . $candidate['firstName'] . '" />', $content);
            $content = str_replace('<input-lastName>', '<input name="lastName" id="lastName" class="inputBoxName" value="' . $candidate['lastName'] . '" />', $content);
            $content = str_replace('<input-address>', '<textarea name="address" class="inputBoxArea">'. $candidate['address'] .'</textarea>', $content);
            $content = str_replace('<input-city>', '<input name="city" id="city" class="inputBoxNormal" value="' . $candidate['city'] . '" />', $content);
            $content = str_replace('<input-state>', '<input name="state" id="state" class="inputBoxNormal" value="' . $candidate['state'] . '" />', $content);
            $content = str_replace('<input-zip>', '<input name="zip" id="zip" class="inputBoxNormal" value="' . $candidate['zip'] . '" />', $content);
            $content = str_replace('<input-phoneWork>', '<input name="phoneWork" id="phoneWork" class="inputBoxNormal" value="' . $candidate['phoneWork'] . '" />', $content);
            $content = str_replace('<input-email1>', '<input name="email1" id="email1" class="inputBoxNormal" value="' . $candidate['email1'] . '" />', $content);
            $content = str_replace('<input-phoneHome>', '<input name="phoneHome" id="phoneHome" class="inputBoxNormal" value="' . $candidate['phoneHome'] . '" />', $content);
            $content = str_replace('<input-phoneCell>', '<input name="phoneCell" id="phoneCell" class="inputBoxNormal" value="' . $candidate['phoneCell'] . '" />', $content);
            $content = str_replace('<input-bestTimeToCall>', '<input name="bestTimeToCall" id="bestTimeToCall" class="inputBoxNormal" value="' . $candidate['bestTimeToCall'] . '" />', $content);
            $content = str_replace('<input-keySkills>', '<input name="keySkills" id="keySkills" class="inputBoxNormal" value="' . $candidate['keySkills'] . '" />', $content);
            $content = str_replace('<input-source>', '<input name="source" id="source" class="inputBoxNormal" value="' . $candidate['source'] . '" />', $content);
            $content = str_replace('<input-currentEmployer>', '<input name="currentEmployer" id="currentEmployer" class="inputBoxNormal" value="' . $candidate['currentEmployer'] . '" />', $content);
            $content = str_replace('<input-resume>',
                '<strong>My Resume</strong><br />'
                . '<textarea name="resumeContents" class="inputBoxArea" style="width: 400px; height: 200px;" readonly>'
                . ($latestAttachment !== false ? DatabaseSearch::fulltextDecode($myResume['text']) : '') .'</textarea>'
                . '<br /><br /><strong>Upload new resume:</strong><br /> '
                . '<input type="file" name="file" id="file" type="file" class="inputBoxFile" size="45" />',
                $content
            );
            $content = str_replace('<input-submit>', '<input type="submit" name="submitButton" id="submitButton" class="submitButton" onclick="document.getElementById(\'submitButton\').disabled=true;" value="Save Profile" style="width: 150px;" />', $content);

            $content = sprintf(
                '<form name="updateForm" id="updateForm" enctype="multipart/form-data" method="post" '
                . 'action="%s?m=careers&p=onRegisteredCandidateProfile&attachmentID=%d">',
                CATSUtility::getIndexName(),
                $latestAttachment ? $latestAttachment : -1
            ) . $content . '</form>'
            . (isset($_GET[$id='isPostBack']) && !strcmp($_GET[$id], 'yes') ? '<script language="javascript" type="text/javascript">setTimeout(\'alert("Your changes have been saved!")\',25);</script>' : '');

            $template['Content'] = $content;
        }
        else if ($p == 'onRegisteredCandidateProfile' && $isRegistrationEnabled)
        {
            // Get information about the candidate from the cookie
            $fields = $this->getCookieFields($siteID);
            $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration'], $fields, true);
            if ($candidate === false)
            {
                echo '<html><body>You have not registered yet.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            // Get the fields (if included in the template) to update
            $fields = array('firstName', 'lastName', 'email1', 'phoneHome', 'phoneCell', 'phoneWork', 'address',
                'city', 'state', 'zip', 'keySkills', 'currentEmployer', 'bestTimeToCall'
            );
            $fieldValues = array();

            foreach ($fields as $field)
            {
                if (isset($_POST[$field]) && $_POST[$field] != '')
                {
                    eval('$'.$field.' = trim($_POST[\''.$field.'\']);');
                    $fieldValues[$field] = $_POST[$field];
                }
                else
                {
                    eval('$'.$field.' = $candidate[\''.$field.'\'];');
                    $fieldValues[$field] = $candidate[$field];
                }
            }

            // Get the attachment to replace (if exists)
            $attachmentID = isset($_GET[$id='attachmentID']) ? $_GET[$id] : -1;
            $attachmentID = $attachmentID != -1 ? $attachmentID : false;

            $attachmentsLib = new Attachments($siteID);
            $candidatesLib = new Candidates($siteID);

            // Update the candidate's information
            $candidatesLib->update(
                $candidate['candidateID'],
                $candidate['isActive'] ? true : false,
                $firstName,
                $candidate['middleName'],
                $lastName,
                $email1,
                $email1,
                $phoneHome,
                $phoneCell,
                $phoneWork,
                $address,
                $city,
                $state,
                $zip,
                $candidate['source'],
                $keySkills,
                $candidate['dateAvailable'],
                $currentEmployer,
                $candidate['canRelocate'],
                $candidate['currentPay'],
                $candidate['desiredPay'],
                $candidate['notes'],
                $candidate['webSite'],
                $bestTimeToCall,
                $candidate['owner'],
                $candidate['isHot'] ? true : false,
                $email1,
                $email1,
                $candidate['eeoGender'],
                $candidate['eeoEthnicType'],
                $candidate['eeoVeteranType'],
                $candidate['eeoDisabilityStatus']
            );

            $uploadResume = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'file');
            if ($uploadResume !== false)
            {
                $uploadPath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $uploadResume);
                if ($uploadPath !== false)
                {
                    // Replace most current resume with new uploaded resume
                    $attachmentsLib->delete($attachmentID, true);
                    $attachmentCreator = new AttachmentCreator($siteID);
                    $attachmentCreator->createFromFile(DATA_ITEM_CANDIDATE, $candidate['candidateID'],
                        $uploadPath, false, '', true, true
                    );
                }
            }

            // Set the cookie again, since some information used to verify may be changed
            $storedVal = '';
            foreach ($fieldValues as $tag => $tagData)
            {
                $storedVal .= sprintf('"%s"="%s"', urlencode($tag), urlencode($tagData));
            }
            @setcookie($this->getCareerPortalCookieName($siteID), $storedVal, time()+60*60*24*7*2);

            $template['Content'] = '<div id="careerContent"><br /><br /><h1>Please wait while you are redirected to your updated profile...</h1></div>';
            CATSUtility::transferRelativeURI('m=careers&p=showAll&pa=updateProfile&isPostBack=yes');
        }
        else if ($p == 'candidateRegistration' && $isRegistrationEnabled)
        {
            $content = $template['Content - Candidate Registration'];

            $jobID = intval($_GET['ID']);
            $jobOrderData = $jobOrders->get($jobID);
            $js = '';

            $content = str_replace(array('<applyContent>','</applyContent>'), '', $content);

            $content = str_replace('<input-submit>', '<input type="submit" id="submitButton" name="submitButton" value="Continue to Application" />', $content);
            $content = str_replace('<input-new>', '<input type="radio" id="isNewYes" name="isNew" value="yes" onchange="isCandidateRegisteredChange();" checked />', $content);
            $content = str_replace('<input-registered>', '<input type="radio" id="isNewNo" name="isNew" value="no" onchange="isCandidateRegisteredChange();" />', $content);
            $content = str_replace('<input-rememberMe>', '<input type="checkbox" id="rememberMe" name="rememberMe" value="yes" checked />', $content);
            $content = str_replace('<title>', $jobOrderData['title'], $content);

            // Process html-ish fields like <input-firstName> into the proper form
            $content = preg_replace(
                '/\<input\-([A-Za-z0-9]+)\>/',
                '<input type="text" class="inputBoxNormal" style="width: 270px;" name="$1" id="$1" onfocus="onFocusFormField(this)" />',
                $content
            );

            if (count($fields = $this->getCookieFields($siteID)))
            {
                $js = '<script language="javascript" type="text/javascript">' . "\n"
                    . 'function populateSavedFields() { var obj; obj = document.getElementById(\'isNewNo\'); '
                    . 'if (obj) { obj.checked = true; enableFormFields(true); } ' . "\n";
                foreach ($fields as $tagName => $tagValue)
                {
                    $js .= sprintf(
                        'if (obj = document.getElementById(\'%s\')) obj.value = \'%s\';%s',
                        urldecode($tagName),
                        str_replace("'", "\\'", urldecode($tagValue)),
                        "\n"
                    );
                }
                $js .= "}\n</script>\n";
            }

            // Insert the form block
            $content = sprintf(
                '%s<form name="register" id="register" method="post" onsubmit="return validateCandidateRegistration()" '
                . 'action="%s?m=careers&p=applyToJob&ID=%d">'
                . '<input type="hidden" name="applyToJobSubAction" value="processLogin" />',
                $js,
                CATSUtility::getIndexName(),
                $jobID
            ) . $content . '<script>enableFormFields(true); ' . ($js != '' ? 'populateSavedFields();' : '')
            . '</script></form>';

            $template['Content'] = $content;
        }
        else if ($p == 'applyToJob' || isset($_POST[$id='applyToJobSubAction']) && $_POST[$id] != '')
        {
            // Pre-populations
            $firstName = isset($_POST[$id='firstName']) ? $_POST[$id] : '';
            $lastName = isset($_POST[$id='lastName']) ? $_POST[$id] : '';
            $address = isset($_POST[$id='address']) ? $_POST[$id] : '';
            $city = isset($_POST[$id='city']) ? $_POST[$id] : '';
            $state = isset($_POST[$id='state']) ? $_POST[$id] : '';
            $zip = isset($_POST[$id='zip']) ? $_POST[$id] : '';
            $phone = isset($_POST[$id='phone']) ? $_POST[$id] : '';
            if ($phone === '' && isset($_POST['phoneWork']))
            {
                $phone = $_POST['phoneWork'];
            }
            $email = isset($_POST[$id='email']) ? $_POST[$id] : (isset($_POST['email1']) ? $_POST['email1'] : '');
            $phoneHome = isset($_POST[$id='phoneHome']) ? $_POST[$id] : '';
            $phoneCell = isset($_POST[$id='phoneCell']) ? $_POST[$id] : '';
            $bestTimeToCall = isset($_POST[$id='bestTimeToCall']) ? $_POST[$id] : '';
            $email2 = isset($_POST[$id='email2']) ? $_POST[$id] : '';
            $emailconfirm = isset($_POST[$id='emailconfirm']) ? $_POST[$id] : '';
            $keySkills = isset($_POST[$id='keySkills']) ? $_POST[$id] : '';
            $source = isset($_POST[$id='source']) ? $_POST[$id] : '';
            $employer = isset($_POST[$id='employer']) ? $_POST[$id] : '';
            $extraNotes = isset($_POST[$id='extraNotes']) ? $_POST[$id] : '';
            // for <input-resumeUploadPreview>
            $resumeContents = isset($_POST[$id='resumeContents']) ? $_POST[$id] : '';
            $resumeFileLocation = isset($_POST[$id='file']) ? $_POST[$id] : '';
            // for returning candidates
            $candidateID = -1;

            if ($isRegistrationEnabled)
            {
                // Check if the user is registered and logged in
                $cookieFields = $this->getCookieFields($siteID);
                $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration'], $cookieFields, true);
                if ($candidate !== false)
                {
                    // The candidate is registered
                    $firstName = $candidate['firstName']; $lastName = $candidate['lastName'];
                    $address = $candidate['address'];
                    $city = $candidate['city'];
                    $state = $candidate['state'];
                    $zip = $candidate['zip'];
                    $phone = $candidate['phoneWork'];
                    $phoneHome = $candidate['phoneHome'];
                    $phoneCell = $candidate['phoneCell'];
                    $email = $candidate['email1'];
                    $email2 = $candidate['email2'];
                    $emailconfirm = $email;
                    $keySkills = $candidate['keySkills'];
                    $source = $candidate['source'];
                    $employer = $candidate['currentEmployer'];
                    $candidateID = $candidate['candidateID'];
                }
            }

            /**
             * SUB-ACTIONS
             * These actions are called as postbacks, such as loading a resume file into the
             * "contents" textarea on the application page. All post data remains intact and
             * re-populates the fields giving the illusion of AJAX.
             */
            if (isset($_POST[$id='applyToJobSubAction']) && strlen($subAction = $_POST[$id]))
            {
                // Check if a candidate has registered and has indicated it
                if (!strcmp($subAction, 'processLogin') &&
                    isset($_POST['isNew']) && !strcmp($_POST['isNew'], 'no') && $isRegistrationEnabled)
                {
                    $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration']);
                    if ($candidate !== false)
                    {
                        // Rewrite here, I'll fix it later
                        $firstName = $candidate['firstName']; $lastName = $candidate['lastName'];
                        $address = $candidate['address'];
                        $city = $candidate['city'];
                        $state = $candidate['state'];
                        $zip = $candidate['zip'];
                        $phone = $candidate['phoneWork'];
                        $phoneHome = $candidate['phoneHome'];
                        $phoneCell = $candidate['phoneCell'];
                        $email = $candidate['email1'];
                        $email2 = $candidate['email2'];
                        $emailconfirm = $email;
                        $keySkills = $candidate['keySkills'];
                        $source = $candidate['source'];
                        $employer = $candidate['currentEmployer'];
                        $candidateID = $candidate['candidateID'];
                    }
                }

                // Check if a file has been uploaded, if so populate the contents textarea
                if (($uploadFile = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'resumeFile')) !== false)
                {
                    $uploadFilePath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $uploadFile);

                    if ($uploadFilePath !== false)
                    {
                        $d2t = new DocumentToText();
                        $docType = $d2t->getDocumentType($uploadFilePath);
                        $conversionResult = $d2t->convert($uploadFilePath, $docType);
                        
                        // Try to get text even if conversion reported failure
                        // Sometimes conversion tools return non-zero exit codes but still extract text
                        $rawOutput = $d2t->getRawOutput();
                        $resumeContents = $d2t->getString();
                        
                        if ($conversionResult !== false && !empty($resumeContents))
                        {
                            // Remove nasty things like _rATr in favor of @
                            $resumeContents = DatabaseSearch::fulltextDecode($resumeContents);
                        }
                        elseif (!empty($rawOutput) && trim($rawOutput) != '')
                        {
                            // Use raw output if available even if conversion reported failure
                            $resumeContents = DatabaseSearch::fulltextDecode($rawOutput);
                        }
                        else
                        {
                            $resumeContents = 'Unable to load your resume contents. Your resume will '
                                . 'still be uploaded and attached to your application.';
                        }
                        $resumeFileLocation = $uploadFile;
                    }
                }

                if (!strcmp($subAction, 'resumeParse'))
                {
                    // Check if the resume contents need to be parsed (user clicked parse contents button)
                    if (LicenseUtility::isParsingEnabled())
                    {
                        $pu = new ParseUtility();
                        $fileName = isset($uploadFile) ? $uploadFile : '';
                        $res = $pu->documentParse($fileName, strlen($resumeContents), '', $resumeContents);
                        if (is_array($res) && !empty($res))
                        {
                            if (isset($res[$id='first_name']) && $res[$id] != '' && $firstName == '') $firstName = $res[$id];
                            if (isset($res[$id='last_name']) && $res[$id] != '' && $lastName == '') $lastName = $res[$id];
                            if (isset($res[$id='us_address']) && $res[$id] != '' && $address == '') $address = $res[$id];
                            if (isset($res[$id='city']) && $res[$id] != '' && $city == '') $city = $res[$id];
                            if (isset($res[$id='state']) && $res[$id] != '' && $state == '') $state = $res[$id];
                            if (isset($res[$id='zip_code']) && $res[$id] != '' && $zip == '') $zip = $res[$id];
                            if (isset($res[$id='email_address']) && $res[$id] != '' && $email == '') { $email = $res[$id]; $email2 = $res[$id]; $emailconfirm = $res[$id]; }
                            if (isset($res[$id='phone_number']) && $res[$id] != '' && $phone == '') $phone = $res[$id];
                            if (isset($res[$id='skills']) && $res[$id] != '' && $keySkills == '') $keySkills = $res[$id];
                        }
                    }
                }
            }

            // Force integer
            $jobID = intval(isset($_GET['ID']) ? $_GET['ID'] : $_POST['ID']);

            $jobOrderData = $jobOrders->get($jobID);
            if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
            {
                echo '<html><body>This position is no longer available.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            // Build state dropdown HTML
            $states = ['AL'=>'Alabama','AK'=>'Alaska','AZ'=>'Arizona','AR'=>'Arkansas','CA'=>'California','CO'=>'Colorado','CT'=>'Connecticut','DE'=>'Delaware','FL'=>'Florida','GA'=>'Georgia','HI'=>'Hawaii','ID'=>'Idaho','IL'=>'Illinois','IN'=>'Indiana','IA'=>'Iowa','KS'=>'Kansas','KY'=>'Kentucky','LA'=>'Louisiana','ME'=>'Maine','MD'=>'Maryland','MA'=>'Massachusetts','MI'=>'Michigan','MN'=>'Minnesota','MS'=>'Mississippi','MO'=>'Missouri','MT'=>'Montana','NE'=>'Nebraska','NV'=>'Nevada','NH'=>'New Hampshire','NJ'=>'New Jersey','NM'=>'New Mexico','NY'=>'New York','NC'=>'North Carolina','ND'=>'North Dakota','OH'=>'Ohio','OK'=>'Oklahoma','OR'=>'Oregon','PA'=>'Pennsylvania','RI'=>'Rhode Island','SC'=>'South Carolina','SD'=>'South Dakota','TN'=>'Tennessee','TX'=>'Texas','UT'=>'Utah','VT'=>'Vermont','VA'=>'Virginia','WA'=>'Washington','WV'=>'West Virginia','WI'=>'Wisconsin','WY'=>'Wyoming'];
            $stateOptions = '<option value="">Select your state</option>';
            foreach ($states as $code => $label) {
                $stateOptions .= '<option value="'.htmlspecialchars($code).'"'.($state==$code?' selected':'').'>'.htmlspecialchars($label).'</option>';
            }
            $sourceOptions = '<option value="">Select an option</option><option value="LinkedIn">LinkedIn</option><option value="Indeed">Indeed</option><option value="Glassdoor">Glassdoor</option><option value="Company Website">Company Website</option><option value="Referral">Employee Referral</option><option value="Job Fair">Job Fair</option><option value="Social Media">Social Media</option><option value="Career Portal">Career Portal</option><option value="Other">Other</option>';

            $indexUrl = CATSUtility::getIndexName();
            $backUrl  = $indexUrl . '?m=careers&amp;p=showJob&amp;ID=' . $jobID;
            $jobTitle = htmlspecialchars($jobOrderData['title'] ?? 'Position');

            $modernApplyForm = <<<HTML
<a href="{$backUrl}" class="cp-back">
  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m15 18-6-6 6-6"/></svg>
  Back to Jobs
</a>
<div class="cp-page-title">Apply for {$jobTitle}</div>
<p class="cp-page-subtitle">Fields marked <span style="color:#ef4444;">*</span> are required.</p>

<div class="cp-progress-wrap">
  <div class="cp-progress-label"><span id="progressLabel">0% complete</span></div>
  <div class="cp-progress-bar"><div class="cp-progress-fill" id="progressFill" style="width:0%"></div></div>
</div>

<div class="cp-steps">
  <div class="cp-step active">
    <div class="cp-step-circle">1</div>
    <div class="cp-step-label">Personal Information</div>
  </div>
  <div class="cp-step-line"></div>
  <div class="cp-step">
    <div class="cp-step-circle">2</div>
    <div class="cp-step-label">Professional Background</div>
  </div>
  <div class="cp-step-line"></div>
  <div class="cp-step">
    <div class="cp-step-circle">3</div>
    <div class="cp-step-label">Resume &amp; Documents</div>
  </div>
  <div class="cp-step-line"></div>
  <div class="cp-step">
    <div class="cp-step-circle">4</div>
    <div class="cp-step-label">Review &amp; Submit</div>
  </div>
</div>

<form id="applyToJobForm" name="applyToJobForm" enctype="multipart/form-data" method="post"
  action="{$indexUrl}?m=careers&amp;p=onApplyToJobOrder" onsubmit="return cpValidateForm();">
  <input type="hidden" name="ID" value="{$jobID}">
  <input type="hidden" name="candidateID" value="-1">
  <input type="hidden" name="applied_via_career_portal" value="1">

  <div class="cp-layout">
    <div class="cp-main">

      <!-- Personal Information -->
      <div class="cp-card" id="personalInfo">
        <div class="cp-card-header">
          <div class="cp-card-icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
          </div>
          <div>
            <div class="cp-card-title">Personal Information</div>
            <div class="cp-card-sub">Tell us who you are.</div>
          </div>
        </div>
        <div class="cp-card-body">
          <div class="cp-form-grid">
            <div class="cp-form-group">
              <label class="cp-label">First Name <span class="req">*</span></label>
              <input class="cp-input" name="firstName" id="firstName" type="text" placeholder="Enter your first name" value="HTML_FIRSTNAME" required>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">Last Name <span class="req">*</span></label>
              <input class="cp-input" name="lastName" id="lastName" type="text" placeholder="Enter your last name" value="HTML_LASTNAME" required>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">Email <span class="req">*</span></label>
              <input class="cp-input" name="email" id="email" type="email" placeholder="Enter your email address" value="HTML_EMAIL" required>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">Confirm Email <span class="req">*</span></label>
              <input class="cp-input" name="emailconfirm" id="emailconfirm" type="email" placeholder="Re-enter your email address" value="HTML_EMAIL" required>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">Phone <span class="req">*</span></label>
              <input class="cp-input" name="phone" id="phone" type="tel" placeholder="Enter your phone number" value="HTML_PHONE" required>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">City <span class="req">*</span></label>
              <input class="cp-input" name="city" id="city" type="text" placeholder="Enter your city" value="HTML_CITY" required>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">State <span class="req">*</span></label>
              <select class="cp-select" name="state" id="state" required>HTML_STATES</select>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">Zip Code <span class="req">*</span></label>
              <input class="cp-input" name="zip" id="zip" type="text" placeholder="Enter zip code" value="HTML_ZIP" required>
            </div>
            <div class="cp-form-group span2">
              <label class="cp-label">Address <span class="req">*</span></label>
              <textarea class="cp-textarea" name="address" id="address" placeholder="Enter your full address" required>HTML_ADDRESS</textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Professional Background -->
      <div class="cp-card" id="professionalBg">
        <div class="cp-card-header">
          <div class="cp-card-icon">
            <svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
          </div>
          <div>
            <div class="cp-card-title">Professional Background</div>
            <div class="cp-card-sub">Help us understand your professional experience.</div>
          </div>
        </div>
        <div class="cp-card-body">
          <div class="cp-form-grid">
            <div class="cp-form-group">
              <label class="cp-label">Key Skills <span class="req">*</span></label>
              <input class="cp-input" name="keySkills" id="keySkills" type="text" placeholder="Enter your key skills (comma separated)" value="HTML_KEYSKILLS" required>
            </div>
            <div class="cp-form-group">
              <label class="cp-label">Current Employer</label>
              <input class="cp-input" name="employer" id="employer" type="text" placeholder="Enter your current employer" value="HTML_EMPLOYER">
            </div>
            <div class="cp-form-group span2">
              <label class="cp-label">How did you hear about us? <span class="req">*</span></label>
              <select class="cp-select" name="source" id="source" required>HTML_SOURCES</select>
            </div>
            <div class="cp-form-group span2">
              <label class="cp-label">Additional Information</label>
              <textarea class="cp-textarea" name="extraNotes" id="extraNotes" placeholder="Add any other relevant information">HTML_EXTRANOTES</textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Resume & Documents -->
      <div class="cp-card" id="resumeDocs">
        <div class="cp-card-header">
          <div class="cp-card-icon">
            <svg viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
          </div>
          <div>
            <div class="cp-card-title">Resume &amp; Documents</div>
            <div class="cp-card-sub">Upload your resume and add any additional documents.</div>
          </div>
        </div>
        <div class="cp-card-body">
          <div class="cp-form-group" style="margin-bottom:16px;">
            <label class="cp-label">Upload Resume <span class="req">*</span></label>
            <div class="cp-upload-zone" id="resumeDropZone">
              <input type="file" name="file" id="resumeFile" accept=".pdf,.doc,.docx,.txt,.rtf" onchange="handleFileSelect(this,'resumeFileDisplay')">
              <div class="cp-upload-icon">
                <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              </div>
              <p class="cp-upload-text"><strong>Drag and drop your file here</strong><br>or</p>
              <span class="cp-upload-btn">Choose File</span>
              <p class="cp-upload-hint">PDF, DOC, DOCX, TXT, RTF • Max 10MB</p>
            </div>
            <div class="cp-file-selected" id="resumeFileDisplay">
              <svg viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span>No file selected</span>
            </div>
          </div>
          <div class="cp-form-group">
            <label class="cp-label">Cover Letter / Notes</label>
            <textarea class="cp-textarea" name="coverLetter" id="coverLetter" rows="4" placeholder="Add your cover letter or any additional notes"></textarea>
          </div>
        </div>
      </div>

    </div><!-- /cp-main -->

    <!-- Sidebar -->
    <div class="cp-sidebar">
      <div class="cp-summary-card">
        <div class="cp-summary-header">
          <h3>Application Summary</h3>
          <p>Review your progress</p>
        </div>
        <div class="cp-summary-list">
          <div class="cp-summary-item" id="summaryPersonal">
            <div class="cp-summary-dot"></div>
            <div class="cp-summary-info">
              <div class="cp-summary-name">Personal Information</div>
              <div class="cp-summary-status">Not started</div>
            </div>
          </div>
          <div class="cp-summary-item" id="summaryProfessional">
            <div class="cp-summary-dot"></div>
            <div class="cp-summary-info">
              <div class="cp-summary-name">Professional Background</div>
              <div class="cp-summary-status">Not started</div>
            </div>
          </div>
          <div class="cp-summary-item" id="summaryResume">
            <div class="cp-summary-dot"></div>
            <div class="cp-summary-info">
              <div class="cp-summary-name">Resume &amp; Documents</div>
              <div class="cp-summary-status">Not started</div>
            </div>
          </div>
          <div class="cp-summary-item" id="summaryReview">
            <div class="cp-summary-dot"></div>
            <div class="cp-summary-info">
              <div class="cp-summary-name">Review &amp; Submit</div>
              <div class="cp-summary-status">Not started</div>
            </div>
          </div>
        </div>
        <div class="cp-tip">
          <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <p><strong>Tip</strong>Complete all sections to increase your chances of getting noticed by our team.</p>
        </div>
      </div>
    </div>
  </div><!-- /cp-layout -->

  <!-- Actions -->
  <div class="cp-actions" style="background:#fff;border:1px solid #e5e7eb;border-radius:14px;margin-top:8px;">
    <a href="{$backUrl}" class="cp-btn cp-btn-ghost">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="16" height="12" x="4" y="6" rx="2"/><path d="M8 6V4h8v2"/></svg>
      Save Draft
    </a>
    <button type="submit" class="cp-btn cp-btn-primary" id="submitBtn">
      Save &amp; Continue
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </button>
  </div>

</form>

<script>
function cpValidateForm() {
  var req = ['firstName','lastName','email','emailconfirm','phone','city','state','zip','address','keySkills','source'];
  for (var i=0; i<req.length; i++) {
    var el = document.getElementById(req[i]);
    if (el && !el.value.trim()) {
      el.focus(); el.style.borderColor='#ef4444';
      alert('Please fill in the required field: ' + (el.previousElementSibling ? el.previousElementSibling.textContent.replace('*','').trim() : req[i]));
      return false;
    }
  }
  var em = document.getElementById('email'), ec = document.getElementById('emailconfirm');
  if (em && ec && em.value !== ec.value) { alert('Email addresses do not match.'); ec.focus(); return false; }
  document.getElementById('submitBtn').disabled = true;
  document.getElementById('submitBtn').textContent = 'Submitting…';
  return true;
}
document.querySelectorAll('.cp-input, .cp-select, .cp-textarea').forEach(function(el){
  el.addEventListener('focus', function(){ el.style.borderColor=''; });
});
</script>
HTML;

            // Substitute pre-filled values
            $modernApplyForm = str_replace('HTML_FIRSTNAME',  htmlspecialchars($firstName),  $modernApplyForm);
            $modernApplyForm = str_replace('HTML_LASTNAME',   htmlspecialchars($lastName),   $modernApplyForm);
            $modernApplyForm = str_replace('HTML_EMAIL',      htmlspecialchars($email),      $modernApplyForm);
            $modernApplyForm = str_replace('HTML_PHONE',      htmlspecialchars($phone),      $modernApplyForm);
            $modernApplyForm = str_replace('HTML_CITY',       htmlspecialchars($city),       $modernApplyForm);
            $modernApplyForm = str_replace('HTML_ZIP',        htmlspecialchars($zip),        $modernApplyForm);
            $modernApplyForm = str_replace('HTML_ADDRESS',    htmlspecialchars($address),    $modernApplyForm);
            $modernApplyForm = str_replace('HTML_KEYSKILLS',  htmlspecialchars($keySkills),  $modernApplyForm);
            $modernApplyForm = str_replace('HTML_EMPLOYER',   htmlspecialchars($employer),   $modernApplyForm);
            $modernApplyForm = str_replace('HTML_EXTRANOTES', htmlspecialchars($extraNotes), $modernApplyForm);
            $modernApplyForm = str_replace('HTML_STATES',     $stateOptions,                $modernApplyForm);
            $modernApplyForm = str_replace('HTML_SOURCES',    $sourceOptions,               $modernApplyForm);

            $template['Content'] = $modernApplyForm;
            $template['_useModern'] = true;

            /* Make JavaScript validation rules. */
            $validator = $this->_makeApplyValidator($template);

            /* Translate required fields into normal fields for replacement. */
            $template['Content'] = str_replace(' req>', '>', $template['Content']);

            /* Get the attachment (friendly) file name is there is an attachment uploaded */
            if ($resumeFileLocation != '')
            {
                $attachmentHTML = '<div style="height: 20px; background-color: #e0e0e0; margin: 5px 0 0px 0; '
                    . 'padding: 0 3px 0 5px; font-size: 11px;"> '
                    . '<img src="images/parser/attachment.gif" border="0" style="padding-top: 3px;" /> '
                    . 'Attachment: <span style="font-weight: bold;">'.$resumeFileLocation.'</span> '
                    . '</div> ';
            }
            else
            {
                $attachmentHTML = '';
            }

            /* Replace input fields. */
            $template['Content'] = str_replace('<jobid>', $jobID, $template['Content']);
            $template['Content'] = str_replace('<title>', $jobOrderData['title'], $template['Content']);
            $template['Content'] = str_replace('<input-firstName>', '<input name="firstName" id="firstName" class="inputBoxName" value="' . $firstName . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-lastName>', '<input name="lastName" id="lastName" class="inputBoxName" value="' . $lastName . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-address>', '<textarea name="address" class="inputBoxArea">'. $address .'</textarea>', $template['Content']);
            $template['Content'] = str_replace('<input-city>', '<input name="city" id="city" class="inputBoxNormal" value="' . $city . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-state>', '<select name="state" id="state" class="inputBoxNormal">'
                . '<option value=""' . ($state == '' ? ' selected' : '') . '>Select your state</option>'
                . '<option value="AL"' . ($state == 'AL' ? ' selected' : '') . '>Alabama</option>'
                . '<option value="AK"' . ($state == 'AK' ? ' selected' : '') . '>Alaska</option>'
                . '<option value="AZ"' . ($state == 'AZ' ? ' selected' : '') . '>Arizona</option>'
                . '<option value="AR"' . ($state == 'AR' ? ' selected' : '') . '>Arkansas</option>'
                . '<option value="CA"' . ($state == 'CA' ? ' selected' : '') . '>California</option>'
                . '<option value="CO"' . ($state == 'CO' ? ' selected' : '') . '>Colorado</option>'
                . '<option value="CT"' . ($state == 'CT' ? ' selected' : '') . '>Connecticut</option>'
                . '<option value="DE"' . ($state == 'DE' ? ' selected' : '') . '>Delaware</option>'
                . '<option value="FL"' . ($state == 'FL' ? ' selected' : '') . '>Florida</option>'
                . '<option value="GA"' . ($state == 'GA' ? ' selected' : '') . '>Georgia</option>'
                . '<option value="HI"' . ($state == 'HI' ? ' selected' : '') . '>Hawaii</option>'
                . '<option value="ID"' . ($state == 'ID' ? ' selected' : '') . '>Idaho</option>'
                . '<option value="IL"' . ($state == 'IL' ? ' selected' : '') . '>Illinois</option>'
                . '<option value="IN"' . ($state == 'IN' ? ' selected' : '') . '>Indiana</option>'
                . '<option value="IA"' . ($state == 'IA' ? ' selected' : '') . '>Iowa</option>'
                . '<option value="KS"' . ($state == 'KS' ? ' selected' : '') . '>Kansas</option>'
                . '<option value="KY"' . ($state == 'KY' ? ' selected' : '') . '>Kentucky</option>'
                . '<option value="LA"' . ($state == 'LA' ? ' selected' : '') . '>Louisiana</option>'
                . '<option value="ME"' . ($state == 'ME' ? ' selected' : '') . '>Maine</option>'
                . '<option value="MD"' . ($state == 'MD' ? ' selected' : '') . '>Maryland</option>'
                . '<option value="MA"' . ($state == 'MA' ? ' selected' : '') . '>Massachusetts</option>'
                . '<option value="MI"' . ($state == 'MI' ? ' selected' : '') . '>Michigan</option>'
                . '<option value="MN"' . ($state == 'MN' ? ' selected' : '') . '>Minnesota</option>'
                . '<option value="MS"' . ($state == 'MS' ? ' selected' : '') . '>Mississippi</option>'
                . '<option value="MO"' . ($state == 'MO' ? ' selected' : '') . '>Missouri</option>'
                . '<option value="MT"' . ($state == 'MT' ? ' selected' : '') . '>Montana</option>'
                . '<option value="NE"' . ($state == 'NE' ? ' selected' : '') . '>Nebraska</option>'
                . '<option value="NV"' . ($state == 'NV' ? ' selected' : '') . '>Nevada</option>'
                . '<option value="NH"' . ($state == 'NH' ? ' selected' : '') . '>New Hampshire</option>'
                . '<option value="NJ"' . ($state == 'NJ' ? ' selected' : '') . '>New Jersey</option>'
                . '<option value="NM"' . ($state == 'NM' ? ' selected' : '') . '>New Mexico</option>'
                . '<option value="NY"' . ($state == 'NY' ? ' selected' : '') . '>New York</option>'
                . '<option value="NC"' . ($state == 'NC' ? ' selected' : '') . '>North Carolina</option>'
                . '<option value="ND"' . ($state == 'ND' ? ' selected' : '') . '>North Dakota</option>'
                . '<option value="OH"' . ($state == 'OH' ? ' selected' : '') . '>Ohio</option>'
                . '<option value="OK"' . ($state == 'OK' ? ' selected' : '') . '>Oklahoma</option>'
                . '<option value="OR"' . ($state == 'OR' ? ' selected' : '') . '>Oregon</option>'
                . '<option value="PA"' . ($state == 'PA' ? ' selected' : '') . '>Pennsylvania</option>'
                . '<option value="RI"' . ($state == 'RI' ? ' selected' : '') . '>Rhode Island</option>'
                . '<option value="SC"' . ($state == 'SC' ? ' selected' : '') . '>South Carolina</option>'
                . '<option value="SD"' . ($state == 'SD' ? ' selected' : '') . '>South Dakota</option>'
                . '<option value="TN"' . ($state == 'TN' ? ' selected' : '') . '>Tennessee</option>'
                . '<option value="TX"' . ($state == 'TX' ? ' selected' : '') . '>Texas</option>'
                . '<option value="UT"' . ($state == 'UT' ? ' selected' : '') . '>Utah</option>'
                . '<option value="VT"' . ($state == 'VT' ? ' selected' : '') . '>Vermont</option>'
                . '<option value="VA"' . ($state == 'VA' ? ' selected' : '') . '>Virginia</option>'
                . '<option value="WA"' . ($state == 'WA' ? ' selected' : '') . '>Washington</option>'
                . '<option value="WV"' . ($state == 'WV' ? ' selected' : '') . '>West Virginia</option>'
                . '<option value="WI"' . ($state == 'WI' ? ' selected' : '') . '>Wisconsin</option>'
                . '<option value="WY"' . ($state == 'WY' ? ' selected' : '') . '>Wyoming</option>'
                . '</select>', $template['Content']);
            $template['Content'] = str_replace('<input-zip>', '<input name="zip" id="zip" class="inputBoxNormal" value="' . $zip . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-phone>', '<input name="phone" id="phone" class="inputBoxNormal" value="' . $phone . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-email>', '<input name="email" id="email" class="inputBoxNormal" value="' . $email . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-phone-home>', '<input name="phoneHome" id="phoneHome" class="inputBoxNormal" value="' . $phoneHome . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-phone-cell>', '<input name="phoneCell" id="phoneCell" class="inputBoxNormal" value="' . $phoneCell . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-best-time-to-call>', '<input name="bestTimeToCall" id="bestTimeToCall" class="inputBoxNormal" value="' . $bestTimeToCall . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-email2>', '<input name="email2" id="email2" class="inputBoxNormal" value="' . $email2 . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-emailconfirm>', '<input name="emailconfirm" id="emailconfirm" class="inputBoxNormal" value="' . $emailconfirm . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-keySkills>', '<input name="keySkills" id="keySkills" class="inputBoxNormal" value="' . $keySkills . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-source>', '<select name="source" id="source" class="inputBoxNormal">'
                . '<option value=""' . ($source == '' ? ' selected' : '') . '>Select an option</option>'
                . '<option value="LinkedIn"' . ($source == 'LinkedIn' ? ' selected' : '') . '>LinkedIn</option>'
                . '<option value="Indeed"' . ($source == 'Indeed' ? ' selected' : '') . '>Indeed</option>'
                . '<option value="Glassdoor"' . ($source == 'Glassdoor' ? ' selected' : '') . '>Glassdoor</option>'
                . '<option value="Company Website"' . ($source == 'Company Website' ? ' selected' : '') . '>Company Website</option>'
                . '<option value="Referral"' . ($source == 'Referral' ? ' selected' : '') . '>Employee Referral</option>'
                . '<option value="Job Fair"' . ($source == 'Job Fair' ? ' selected' : '') . '>Job Fair</option>'
                . '<option value="Social Media"' . ($source == 'Social Media' ? ' selected' : '') . '>Social Media</option>'
                . '<option value="Other"' . ($source == 'Other' ? ' selected' : '') . '>Other</option>'
                . '</select>', $template['Content']);
            $template['Content'] = str_replace('<input-employer>', '<input name="employer" id="employer" class="inputBoxNormal" value="' . $employer . '" />', $template['Content']);
            $template['Content'] = str_replace('<input-resumeUpload>', '<input type="file" id="resume" name="file" class="inputBoxFile" />', $template['Content']);
            $template['Content'] = str_replace('<input-resumeUploadPreview>',
                '<input type="hidden" id="applyToJobSubAction" name="applyToJobSubAction" value="" /> '
                . '<input type="hidden" id="file" name="file" value="' . $resumeFileLocation . '" /> '
                . '<input type="file" id="resumeFile" name="resumeFile" class="inputBoxFile" size="30" onchange="resumeLoadCheck();" /> '
                . '<input type="button" id="resumeLoad" name="resumeLoad" value="Upload" onclick="resumeLoadFile();" disabled /><br /> '
                . $attachmentHTML
                . '<textarea id="resumeContents" name="resumeContents" class="inputBoxArea" onmousemove="resumeContentsChange(this);" '
                . 'onchange="resumeContentsChange(this);" onmousedown="resumeContentsChange(this);" '
                . 'style="width: 410px; height: 150px;">' . $resumeContents . '</textarea><br /> '
                . (
                // If parsing is enabled, add the image link for it
                LicenseUtility::isParsingEnabled() ?
                    '<br /><div style="text-align: right;">'
                    . '<input type="button" value="Populate Fields ->" id="resumePopulate" onclick="resumeParse();" '.(strlen($resumeContents)?'':'disabled').' />'
                :
                    ''
                ),
                $template['Content']);
            $template['Content'] = str_replace('<input-extraNotes>', '<textarea name="extraNotes" id="extraNotes" class="inputBoxArea" maxlength="450" onkeyup="mlength=this.getAttribute ? parseInt(this.getAttribute(\'maxlength\')) : \'\'; if (this.getAttribute && this.value.length>(mlength+7)) { alert(\'Sorry, you may only enter \'+mlength+\' characters into the extra notes.\');} if (this.getAttribute && this.value.length>mlength) {this.value=this.value.substring(0,mlength); this.scrollTop = this.scrollHeight;}">'.(isset($_POST[$id='extraNotes'])?$_POST[$id]:'').'</textarea>', $template['Content']);
            $template['Content'] = str_replace('<submit', '<input type="submit" class="submitButton"', $template['Content']);

            /* EEO inputs. */
            $template['Content'] = str_replace('<input-eeo-race>', '<select name="eeorace" id="eeorace" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="1">American Indian</option>
                                                                        <option value="2">Asian or Pacific Islander</option>
                                                                        <option value="3">Hispanic or Latino</option>
                                                                        <option value="4">Non-Hispanic Black</option>
                                                                        <option value="5">Non-Hispanic White</option>
                                                                    </select>', $template['Content']);

            $template['Content'] = str_replace('<input-eeo-gender>', '<select name="eeogender" id="eeogender" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="m">Male</option>
                                                                        <option value="f">Female</option>
                                                                    </select>', $template['Content']);

            $template['Content'] = str_replace('<input-eeo-veteran>', '<select name="eeoveteran" id="eeoveteran" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="1">Male</option>
                                                                        <option value="2">Eligible Veteran</option>
                                                                        <option value="3">Disabled Veteran</option>
                                                                        <option value="4">Eligible and Disabled</option>
                                                                    </select>', $template['Content']);

            $template['Content'] = str_replace('<input-eeo-disability>', '<select name="eeodisability" id="eeodisability" class="inputBoxNormal" />
                                                                        <option value="">----</option>
                                                                        <option value="No">No</option>
                                                                        <option value="Yes">Yes</option>
                                                                    </select>', $template['Content']);

            /* Extra field inputs. */
            $candidates = new Candidates($siteID);
            $extraFieldsForCandidates = $candidates->extraFields->getValuesForAdd();

            foreach($extraFieldsForCandidates as $ef)
            {
                if (isset($ef['careersAddHTML']))
                {
                    $template['Content'] = str_replace('<input-extraField-' .urlencode($ef['fieldName']) . '>', $ef['careersAddHTML'], $template['Content']);
                }
                else
                {
                    $template['Content'] = str_replace('<input-extraField-' .urlencode($ef['fieldName']) . '>', $ef['addHTML'], $template['Content']);
                }
            }

            /* Modern apply form already contains its own <form> tag and hidden inputs — skip legacy wrapping. */
            if (empty($template['_useModern']))
            {
                /* This is kindof a hack, but basically, we have to put the
                 * validation code / form below inside the <td>, which is contained
                 * in the template, as they aren't allowed in <tr>s.
                 * NOTE: Continue to use ungreedy matching or this will break!
                 */
                if (preg_match('/^.*?(<td.*?>)/i', $template['Content'], $matches))
                {
                    $startTD = $matches[1];
                    $template['Content'] = preg_replace('/^.*?(?:<td.*?>)/i', '', $template['Content']);
                }
                else
                {
                    $startTD = '';
                }

                if (preg_match('/(<\/td>).*?$/i', $template['Content'], $matches))
                {
                    $endTD = $matches[1];
                    $template['Content'] = preg_replace('/(?:<\/td>).*?$/i', '', $template['Content']);
                }
                else
                {
                    $endTD = '';
                }

                if (strpos($template['Content'], '<catsform>') === false)
                {
                    $template['Content'] = $startTD . "\n" . $validator . "\n"
                        . '<form name="applyToJobForm" id="applyToJobForm" action="'
                        . CATSUtility::getIndexName()
                        . '?m=careers&amp;p=onApplyToJobOrder" '
                        . 'enctype="multipart/form-data" method="post" onsubmit="return applyValidate();">'
                        . '<input type="hidden" name="ID" value="' . $jobID . '">'
                        . '<input type="hidden" name="candidateID" value="' . $candidateID . '">'
                        . $template['Content'] . '</form>' . "\n" . $endTD;
                }
                else
                {
                    $template['Content'] = $startTD . "\n" . $validator . "\n" .
                        str_replace('<catsform>', '<form name="applyToJobForm" id="applyToJobForm" action="'
                            . CATSUtility::getIndexName()
                            . '?m=careers&amp;p=onApplyToJobOrder" '
                            . 'enctype="multipart/form-data" method="post" onsubmit="return applyValidate();">'
                            . '<input type="hidden" name="ID" value="' . $jobID . '">'
                            . '<input type="hidden" name="candidateID" value="' . $candidateID . '">',
                            $template['Content'])
                        . "\n" . $endTD;
                }
            }
        }
        else if ($p == 'onApplyToJobOrder')
        {
            if (!$this->isRequiredIDValid('ID', $_POST))
            {
                // FIXME: Generate valid XHTML error pages. Create an error/fatal method!
                echo '<html><body>This position is invalid or no longer available. Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die();
            }

            // Check if this is a returning candidate
            $candidateID = isset($_POST['candidateID']) ? intval($_POST['candidateID']) : -1;
            if ($candidateID == -1) $candidateID = false;

            /**
             * Applicant has completed their application, check to see if a questionnaire
             * is tied to this job order. If so, present it.
             */
            $jobID = intval($_POST['ID']);
            $jobOrderData = $jobOrders->get($jobID);
            $questionnaireLib = new Questionnaire($siteID);

            $questionnaireID = $jobOrderData['questionnaireID'];
            if ($questionnaireID)
            {
                $questionnaire = $questionnaireLib->get($questionnaireID);
                if (!is_array($questionnaire) || empty($questionnaire))
                {
                    $questionnaireID = false;
                }
            }

            // Check for postback (if the applicant has completed the questionnaire) or if no questionnaire exists
            if ((isset($_GET[$id='questionnairePostBack']) && $_GET[$id] == '1') || !$questionnaireID)
            {
                // Continue on our merry way
                $this->onApplyToJobOrder($siteID, $candidateID);

                $jobOrderData = $jobOrders->get($jobID);
                if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
                {
                    // FIXME: Generate valid XHTML error pages. Create an error/fatal method!
                    echo '<html><body>This position is no longer available.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                    die();
                }

                /* Generate reference ID */
                $refID = 'NTA-' . strtoupper(substr(md5(uniqid()), 0, 6));

                /* Show a clean success page with popup */
                $jobTitle = htmlspecialchars($jobOrderData['title'] ?? 'Position');
                $backUrl  = CATSUtility::getIndexName() . '?m=careers' . (isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '') . '&p=showAll';
                echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<link href="../inter.css" rel="stylesheet">
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{font-family:Inter,sans-serif;background:#f1f5f9;display:flex;align-items:center;justify-content:center;min-height:100vh;}
.card{background:#fff;border-radius:20px;padding:52px 44px;text-align:center;max-width:480px;width:100%;box-shadow:0 8px 40px rgba(0,0,0,.10);}
.check-circle{width:80px;height:80px;background:linear-gradient(135deg,#d1fae5,#a7f3d0);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;animation:popIn .5s ease;}
@keyframes popIn{0%{transform:scale(0);opacity:0;}70%{transform:scale(1.1);}100%{transform:scale(1);opacity:1;}}
h1{font-size:24px;font-weight:800;color:#064e3b;margin-bottom:8px;}
.subtitle{font-size:15px;color:#6b7280;line-height:1.6;margin-bottom:20px;}
.ref-box{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 20px;margin-bottom:28px;}
.ref-label{font-size:11px;font-weight:600;color:#16a34a;text-transform:uppercase;letter-spacing:.06em;margin-bottom:4px;}
.ref-id{font-size:22px;font-weight:800;color:#166534;font-family:monospace;letter-spacing:.08em;}
.email-notice{font-size:13px;color:#6b7280;margin-bottom:32px;line-height:1.5;}
.email-notice strong{color:#374151;}
.btn-home{display:inline-block;padding:12px 32px;background:#2563eb;color:#fff;border-radius:10px;text-decoration:none;font-weight:700;font-size:14px;transition:background .15s;}
.btn-home:hover{background:#1d4ed8;}
.job-title{font-size:13px;color:#9ca3af;margin-top:20px;}
</style></head><body>
<div class="card">
  <div class="check-circle">
    <svg width="36" height="36" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
  </div>
  <h1>Application Submitted!</h1>
  <p class="subtitle">Thank you for applying for <strong>' . $jobTitle . '</strong>.<br>We\'ve received your application and will review it shortly.</p>
  <div class="ref-box">
    <div class="ref-label">Your Reference ID</div>
    <div class="ref-id">' . $refID . '</div>
  </div>
  <p class="email-notice">A confirmation email has been sent to your registered email address. Please save your reference ID for future correspondence.</p>
  <a href="' . htmlspecialchars($backUrl) . '" class="btn-home">View More Jobs</a>
  <p class="job-title">Applied for: ' . $jobTitle . '</p>
</div>
</body></html>';
                die();
            }
            else
            {
                ob_start();

                // get questions/answers
                $questions = $questionnaireLib->getQuestions($questionnaireID);

                $this->_template->assign('isModal', true);
                $this->_template->assign('questionnaireID', $questionnaireID);
                $this->_template->assign('data', $questionnaire);
                $this->_template->assign('questions', $questions);
                $this->_template->display('./modules/settings/CareerPortalQuestionnaireShow.tpl');

                $buffer = ob_get_contents();
                ob_end_clean();

                $formData = '<form name="postQuestionnaire" id="postQuestionnaire" '
                    . 'enctype="multipart/form-data" method="post" action="'
                    . CATSUtility::getIndexName() . '?m=careers&p=onApplyToJobOrder'
                    . '&questionnairePostBack=1">' . "\n"
                    . $this->capturePostData($siteID);

                // Collect all of the post data and resubmit it as hidden elements
                $buffer = $formData . $buffer;

                $template['Content'] = str_replace('<questionnaire>', $buffer, $template['Content - Questionnaire']);
                $template['Content'] = str_replace('<submit', '<input type="submit" class="submitButton"', $template['Content']) . '</form>';
            }
        }
        else if ($p == 'showJob')
        {
            $template['Content'] = $template['Content - Job Details'];

            $jobID = $_GET['ID'];

            /* Filter out non numeric characters */
            for ($i = 0; $i < strlen($jobID); $i++)
            {
                if (ord(substr($jobID, $i, 1)) < ord('0') || ord(substr($jobID, $i, 1)) > ord('9') )
                {
                    $jobID = str_replace(substr($jobID, $i, 1), '*', $jobID);
                }
            }
            $jobID = str_replace('*', '', $jobID);

            /* Force integer */
            $jobID = $jobID * 1;

            $jobOrderData = $jobOrders->get($jobID);
            if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
            {
                echo '<html><body>This position is no longer available.  Please wait while we direct you to the job list...<script>setTimeout("document.location.href=\'?m=careers&&p=showAll\';", 1500);</script></body></html>';
                die ();
            }

            $template['Content'] = str_replace('<registeredCandidate>', $useCookie && $isRegistrationEnabled ? $this->getRegisteredCandidateBlock($siteID, $template['Content - Candidate Registration']) : '', $template['Content']);
            $template['Content'] = str_replace('<title>',        $jobOrderData['title'], $template['Content']);
            $template['Content'] = str_replace('<city>',         $jobOrderData['city'], $template['Content']);
            $template['Content'] = str_replace('<openings>',     $jobOrderData['openings'], $template['Content']);
            $template['Content'] = str_replace('<state>',        $jobOrderData['state'], $template['Content']);
            $template['Content'] = str_replace('<type>',         $jobOrders->typeCodeToString($jobOrderData['type']), $template['Content']);
            $template['Content'] = str_replace('<created>',      $jobOrderData['dateCreated'], $template['Content']);
            $template['Content'] = str_replace('<recruiter>',    $jobOrderData['recruiterFullName'], $template['Content']);
            $template['Content'] = str_replace('<companyName>',  $jobOrderData['companyName'], $template['Content']);
            $template['Content'] = str_replace('<contactName>',  $jobOrderData['contactFullName'] ?? '', $template['Content']);
            $template['Content'] = str_replace('<contactPhone>', $jobOrderData['contactWorkPhone'] ?? '', $template['Content']);
            $template['Content'] = str_replace('<contactEmail>', $jobOrderData['contactEmail'] ?? '', $template['Content']);
            $template['Content'] = str_replace('<description>',  $jobOrderData['description'], $template['Content']);
            $template['Content'] = str_replace('<rate>',         nl2br($jobOrderData['maxRate']), $template['Content']);
            $template['Content'] = str_replace('<salary>',       nl2br($jobOrderData['salary']), $template['Content']);
            $template['Content'] = str_replace('<daysOld>',      nl2br($jobOrderData['daysOld']), $template['Content']);

            $isRegistered = $this->isCandidateRegistered($siteID, $template['Content - Candidate Registration']);

            // If candidate registration is enabled, ask them if they would like to log in first
            if ($isRegistrationEnabled && !$isRegistered)
            {
                $template['Content'] = str_replace('<a-applyToJob', '<a href="'.CATSUtility::getIndexName().'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&p=candidateRegistration&ID='.$jobID.'"', $template['Content']);
            }
            else
            {
                $template['Content'] = str_replace('<a-applyToJob', '<a href="'.CATSUtility::getIndexName().'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&p=applyToJob&ID='.$jobID.'"', $template['Content']);
            }

            $jobOrders = new JobOrders($siteID);
            $extraFieldsForJobOrders = $jobOrders->extraFields->getValuesForShow($jobID);

            foreach($extraFieldsForJobOrders as $ef)
            {
                $template['Content'] = str_replace('<extraField-' .urlencode($ef['fieldName']) . '>', $ef['display'], $template['Content']);
            }
        }
        else if ($p == 'searchResults')
        {
        }
        else
        {
            $template['Content'] = $template['Content - Main'];
            $template['Content'] = str_replace('<registeredCandidate>', $useCookie && $isRegistrationEnabled ? $this->getRegisteredCandidateBlock($siteID, $template['Content - Candidate Registration']) : '', $template['Content']);

            $isRegistered = $useCookie ? $this->isCandidateRegistered($siteID, $template['Content - Candidate Registration']) : false;

            if ($isRegistrationEnabled)
            {
                // postback
                if (isset($_GET[$id='postback']) && !strcmp($_GET[$id], 'yes'))
                {
                    $candidate = $this->ProcessCandidateRegistration($siteID, $template['Content - Candidate Registration']);

                    if ($candidate === false)
                    {
                        $isRegistered = false;
                        // Error Message
                        $template['Content'] = str_replace('<registeredLoginTitle>', '<h1 style="color: #800000;">No applicants were '
                            . 'found matching your criteria.</h1><h3>Once you apply to any of our positions, you will automatically '
                            . 'be registered.<br /><br />', $template['Content']
                        );
                    }
                    else
                    {
                        $isRegistered = true;
                    }
                }

                if (!$isRegistered)
                {
                    // If they're not logged on but registration is enabled, give them the opportunity to
                    $content = $template['Content - Candidate Registration'];
                    $js = '';

                    $content = str_replace(array('<registeredLoginTitle>', '</registeredLoginTitle>'), '', $content);
                    $content = str_replace('<applyContent>', '<div style="display: none;">', $content);
                    $content = str_replace('</applyContent>', '</div>', $content);
                    $content = str_replace('<input-submit>', '<input type="submit" id="submitButton" name="submitButton" value="Login" />', $content);
                    $content = str_replace('<input-new>', '<input type="hidden" id="isNewNo" name="isNew" value="no" />', $content);
                    $content = str_replace('<input-registered>', '', $content);
                    $content = str_replace('<input-rememberMe>', '<input type="checkbox" id="rememberMe" name="rememberMe" value="yes" checked />', $content);
                    $content = str_replace('<title>', '', $content);

                    // Process html-ish fields like <input-firstName> into the proper form
                    $content = preg_replace(
                        '/\<input\-([A-Za-z0-9]+)\>/',
                        '<input type="text" class="inputBoxNormal" style="width: 270px;" name="$1" id="$1" onfocus="onFocusFormField(this)" />',
                        $content
                    );

                    // Insert the form block
                    $content = sprintf(
                        '<form name="login" id="login" method="post" onsubmit="return validateCandidateRegistration()" '
                        . 'action="%s?postback=yes">',
                        CATSUtility::getIndexName()
                    ) . $content . '<script>enableFormFields(true);</script></form>';

                    $template['Content'] = str_replace('<registeredLogin>', $content, $template['Content']);
                }
                else
                {
                    $template['Content'] = str_replace('<registeredLoginTitle>', '<div style="display: none;">', $template['Content']);
                    $template['Content'] = str_replace('</registeredLoginTitle>', '</div>', $template['Content']);
                    $template['Content'] = str_replace(array('<registeredCandidate>', '<registeredLogin>'), '', $template['Content']);
                }
            }
            else
            {
                $template['Content'] = str_replace('<registeredLoginTitle>', '<div style="display: none;">', $template['Content']);
                $template['Content'] = str_replace('</registeredLoginTitle>', '</div>', $template['Content']);
                $template['Content'] = str_replace(array('<registeredCandidate>', '<registeredLogin>'), '', $template['Content']);
            }

        }

        $indexName = CATSUtility::getIndexName();
        foreach ($template as $index => $data)
        {
            $template[$index] = str_replace('<a-LinkMain>',   '<a href="'.$indexName.'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'">', $template[$index]);
            $template[$index] = str_replace('<a-LinkSearch>', '<a href="'.$indexName.'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&amp;p=search">', $template[$index]);
            $template[$index] = str_replace('<a-ListAll>',    '<a href="'.$indexName.'?m=careers'.(isset($_GET['templateName']) ? '&templateName='.urlencode($_GET['templateName']) : '').'&amp;p=showAll">', $template[$index]);
            $template[$index] = str_replace('<siteName>', $siteName, $template[$index]);
            $template[$index] = str_replace('<numberOfOpenPositions>', count($rs), $template[$index]);

            /* Hacks for loading from a nonstandard root directory. */
            if (isset($careerPage) && $careerPage == true)
            {
                $template[$index] = str_replace('"images/', '"../images/', $template[$index]);
                $template[$index] = str_replace('\'images/', '\'../images/', $template[$index]);
                $template[$index] = str_replace('<rssURL>', '../rss/', $template[$index]);
            }
            else
            {
                $template[$index] = str_replace('<rssURL>', 'rss/', $template[$index]);
            }
        }

        $template['_page'] = $p;
        $template['_jobCount'] = count($rs);
        $this->_template->assign('template', $template);
        $this->_template->assign('siteName', $siteName);

        if (!eval(Hooks::get('CAREERS_PAGE_BOTTOM'))) return;

        if (!empty($template['_useModern']))
        {
            $this->_template->display('./modules/careers/ApplyModern.tpl');
        }
        elseif ($careerPortalSettingsRS['useCATSTemplate'] != '')
        {
            $this->_template->display($careerPortalSettingsRS['useCATSTemplate']);
        }
        else
        {
            $this->_template->display('./modules/careers/Blank.tpl');
        }
    }


    private function _makeApplyValidator($template)
    {
        $validator = '';

        if (strpos($template['Content'], '<input-firstName>') !== false || strpos($template['Content'], '<input-firstName req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'firstName\').value == \'\')
                {
                    alert(\'Please enter a first name.\');
                    document.getElementById(\'firstName\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-lastName>') !== false || strpos($template['Content'], '<input-lastName req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'lastName\').value == \'\')
                {
                    alert(\'Please enter a last name.\');
                    document.getElementById(\'lastName\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-emailconfirm>') !== false || strpos($template['Content'], '<input-emailconfirm req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'emailconfirm\').value != document.getElementById(\'email\').value)
                {
                    alert(\'Your E-Mail address doesn\\\'t match the retyped E-Mail address.\');
                    document.getElementById(\'emailconfirm\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-email>') !== false || strpos($template['Content'], '<input-email req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'email\').value == \'\')
                {
                    alert(\'Please enter an E-Mail address.\');
                    document.getElementById(\'email\').focus();
                    return false;
                }
                if (document.getElementById(\'email\').value.indexOf(\'@\') == -1 ||
                    document.getElementById(\'email\').value.indexOf(\'.\') == -1)
                {
                    alert(\'Please enter a valid E-Mail address.\');
                    document.getElementById(\'email\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-address req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'address\').value == \'\')
                {
                    alert(\'Please enter an address.\');
                    document.getElementById(\'address\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-city req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'city\').value == \'\')
                {
                    alert(\'Please enter a city.\');
                    document.getElementById(\'city\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-state req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'state\').value == \'\')
                {
                    alert(\'Please enter a state.\');
                    document.getElementById(\'state\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-zip req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'zip\').value == \'\')
                {
                    alert(\'Please enter a zip code.\');
                    document.getElementById(\'zip\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-phone req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'phone\').value == \'\')
                {
                    alert(\'Please enter a phone number.\');
                    document.getElementById(\'phone\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-keySkills req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'keySkills\').value == \'\')
                {
                    alert(\'Please enter some key skills.\');
                    document.getElementById(\'keySkills\').focus();
                    return false;
                }';
        }

        if (strpos($template['Content'], '<input-extraNotes req>') !== false)
        {
            $validator .= '
                if (document.getElementById(\'extraNotes\').value == \'\')
                {
                    alert(\'Please enter some extra notes.\');
                    document.getElementById(\'extraNotes\').focus();
                    return false;
                }';
        }

        $validator = '<script type="text/javascript">function applyValidate() {'
            . $validator . ' return true; }' . "\n" . '</script>';

        return $validator;
    }

    /*
     * Gets HTML content for the job order response array.
     */
    // FIXME: More of this needs to be done in the template. The UI shouldn't generate HTML.
    private function getResultsTable($rs, $settings, $unformatted = false, $parameters = '')
    {
        if ($unformatted)
        {
            $html  = '<table class="sortable">' . "\n";
        }
        else
        {
            $html  = '<table class="sortable" style="width:100%;">' . "\n";
        }
        $html .= '<tr class="rowHeading" align="left">'."\n";
        if ($settings['showCompany'] == 1)
        {
            $html .= '<th nowrap="nowrap">Company</th>';
        }
        if ($settings['showDepartment'] == 1)
        {
            $html .= '<th nowrap="nowrap" align="left">Department</th>';
        }
        $html .= '<th nowrap="nowrap" align="left">Position Title</th>';
        $html .= '<th nowrap="nowrap" align="left">Location</th>';
        $html .= '</tr>'."\n";

        $rowIsEven = false;
        foreach ($rs as $index => $line)
        {
            $rowIsEven = !$rowIsEven;
            if ($rowIsEven)
            {
                $html .= '<tr class="evenTableRow">'."\n";
            }
            else
            {
                $html .= '<tr class="oddTableRow">'."\n";
            }

            if ($settings['showCompany'] == 1)
            {
                $html .= '<td>';
                $html .= htmlspecialchars($line['companyName']);
                $html .= '</td>';
            }

            if ($settings['showDepartment'] == 1)
            {
                $html .= '<td>';
                if ($line['departmentID'] == 0)
                {
                    $html .= 'General';
                }
                else
                {
                    $html .= htmlspecialchars($line['departmentName']);
                }
                $html .= '</td>';
            }

            $html .= '<td>';
            $html .= '<a href="' . CATSUtility::getIndexName() . '?m=careers' . (isset($_GET['templateName']) ? '&amp;templateName=' . urlencode($_GET['templateName']) : '').'&amp;p=showJob&amp;ID=' . $line['jobOrderID'] . '">';
            $html .= htmlspecialchars($line['title']);
            $html .= '</a>';
            $html .= '</td>';

            $html .= '<td>';
            $html .= htmlspecialchars($line['city']) . ', ' . htmlspecialchars($line['state']);
            $html .= '</td>';

            $html .= '</tr>'."\n";
        }
        $html .= '</table>';

        return $html;
    }

    /* Called by Careers Page function to handle the processing of candidate input. */
    private function onApplyToJobOrder($siteID, $candidateID = false)
    {
        $jobOrders = new JobOrders($siteID);
        $careerPortalSettings = new CareerPortalSettings($siteID);

        if (!$this->isRequiredIDValid('ID', $_POST))
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'Invalid job order ID.');
            return;
        }

        $jobOrderID = $_POST['ID'];

        $jobOrderData = $jobOrders->get($jobOrderID);
        if (!isset($jobOrderData['public']) || $jobOrderData['public'] == 0)
        {
            CommonErrors::fatal(COMMONERROR_BADINDEX, $this, 'The specified job order could not be found.');
            return;
        }
	    
    /* funciton getSanitisedInput used to fix XSS vuln in public portal */
        $lastName       = $this->getSanitisedInput('lastName', $_POST);
        $middleName     = $this->getSanitisedInput('middleName', $_POST);
        $firstName      = $this->getSanitisedInput('firstName', $_POST);
        $email          = $this->getSanitisedInput('email', $_POST);
        $email2         = $this->getSanitisedInput('email2', $_POST);
        $address        = $this->getSanitisedInput('address', $_POST);
        $city           = $this->getSanitisedInput('city', $_POST);
        $state          = $this->getSanitisedInput('state', $_POST);
        $zip            = $this->getSanitisedInput('zip', $_POST);
        $source         = $this->getSanitisedInput('source', $_POST);
        $phone          = $this->getSanitisedInput('phone', $_POST);
        $phoneHome      = $this->getSanitisedInput('phoneHome', $_POST);
        $phoneCell      = $this->getSanitisedInput('phoneCell', $_POST);
        $bestTimeToCall = $this->getSanitisedInput('bestTimeToCall', $_POST);
        $keySkills      = $this->getSanitisedInput('keySkills', $_POST);
        $extraNotes     = $this->getSanitisedInput('extraNotes', $_POST);
        $employer       = $this->getSanitisedInput('employer', $_POST);

        $gender         = $this->getSanitisedInput('eeogender', $_POST);
        $race           = $this->getSanitisedInput('eeorace', $_POST);
        $veteran        = $this->getSanitisedInput('eeoveteran', $_POST);
        $disability     = $this->getSanitisedInput('eeodisability', $_POST);

        if (empty($firstName))
        {
            CommonErrors::fatal(COMMONERROR_MISSINGFIELDS, $this, 'First Name is a required field - please have your administrator edit your templates to include the first name field.');
        }

        if (empty($lastName))
        {
            CommonErrors::fatal(COMMONERROR_MISSINGFIELDS, $this, 'Last Name is a required field - please have your administrator edit your templates to include the last name field.');
        }

        if (empty($email))
        {
            CommonErrors::fatal(COMMONERROR_MISSINGFIELDS, $this, 'E-Mail address is a required field - please have your administrator edit your templates to include the email field.');
        }

        if (!empty($_POST['applied_via_career_portal']))
        {
            $source = 'Career Portal';
        }
        elseif (empty($source))
        {
            $source = 'Online Careers Website';
        }

        $users = new Users(CATS_ADMIN_SITE);
        $automatedUser = $users->getAutomatedUser();

        /* Find if another user with same e-mail exists. If so, update the user
         * to contain the new information.
         */
        $candidates = new Candidates($siteID);

        /**
         * Save basic information in a cookie in case the site is using registration to
         * process repeated postings, etc.
         */
        $fields = array('firstName', 'lastName', 'email', 'address', 'city', 'state', 'zip', 'phone',
            'phoneHome', 'phoneCell'
        );
        $storedVal = '';
        foreach ($fields as $field)
        {
            eval('$tmp = sprintf(\'"%s"="%s"\', $field, urlencode($' . $field . '));');
            $storedVal .= $tmp;
        }
        // Store their information for an hour only (about 1 session), if they return they can log in again and
        // specify "remember me" which stores it for 2 weeks.
        @setcookie($this->getCareerPortalCookieName($siteID), $storedVal, time()+60*60);

        if ($candidateID !== false)
        {
            $candidate = $candidates->get($candidateID);

            // Candidate exists and registered. Update their profile with new values (if provided)
            $candidates->update(
                $candidateID, $candidate['isActive'] ? true : false, $firstName, $middleName,
                $lastName, $email, $email2, $phoneHome, $phoneCell, $phone, $address, $city,
                $state, $zip, $source, $keySkills, '', $employer, '', '', '', $candidate['notes'],
                '', $bestTimeToCall, $automatedUser['userID'], $automatedUser['userID'], $gender,
                $race, $veteran, $disability
            );

            /* Update extra feilds */
            $candidates->extraFields->setValuesOnEdit($candidateID);
        }
        else
        {
            // Lookup the candidate by e-mail, use that candidate instead if found (but don't update profile)
            $candidateID = $candidates->getIDByEmail($email);
        }

        if ($candidateID === false || $candidateID < 0)
        {
            /* New candidate. */
            $candidateID = $candidates->add(
                $firstName,
                $middleName,
                $lastName,
                $email,
                $email2,
                $phoneHome,
                $phoneCell,
                $phone,
                $address,
                $city,
                $state,
                $zip,
                $source,
                $keySkills,
                '',
                $employer,
                '',
                '',
                '',
                'Candidate submitted these notes with first application: '
                . "\n\n" . $extraNotes,
                '',
                $bestTimeToCall,
                $automatedUser['userID'],
                $automatedUser['userID'],
                $gender,
                $race,
                $veteran,
                $disability
            );

            /* Update extra fields. */
            $candidates->extraFields->setValuesOnEdit($candidateID);
        }

        // If the candidate was added and a questionnaire exists for the job order
        if ($candidateID > 0 && ($questionnaireID = $jobOrderData['questionnaireID']))
        {
            $questionnaireLib = new Questionnaire($siteID);
            // Perform any actions specified by the questionnaire
            $questionnaireLib->doActions($questionnaireID, $candidateID, $_POST);
        }

        $fileUploaded = false;

        /* Upload resume (no questionnaire) */
        if (isset($_FILES['file']) && !empty($_FILES['file']['name']))
        {
            $attachmentCreator = new AttachmentCreator($siteID);
            $attachmentCreator->createFromUpload(
                DATA_ITEM_CANDIDATE, $candidateID, 'file', false, true
            );

            if ($attachmentCreator->isError())
            {
                CommonErrors::fatal(COMMONERROR_FILEERROR, $this, $attachmentCreator->getError());
                return;
            }

            $duplicatesOccurred = $attachmentCreator->duplicatesOccurred();

            $isTextExtractionError = $attachmentCreator->isTextExtractionError();
            $textExtractionErrorMessage = $attachmentCreator->getTextExtractionError();

            // FIXME: Show parse errors!

            $fileUploaded = true;
            $resumePath = $attachmentCreator->getNewFilePath();
        }
        /* Upload resume (with questionnaire) */
        else if (isset($_POST['file']) && !empty($_POST['file']))
        {
            $resumePath = '';

            $newFilePath = FileUtility::getUploadFilePath($siteID, 'careerportaladd', $_POST['file']);

            if ($newFilePath !== false)
            {
                $attachmentCreator = new AttachmentCreator($siteID);
                $attachmentCreator->createFromFile(
                    DATA_ITEM_CANDIDATE, $candidateID, $newFilePath, false, '', true, true
                );

                if ($attachmentCreator->isError())
                {
                    CommonErrors::fatal(COMMONERROR_FILEERROR, $this, $attachmentCreator->getError());
                    return;
                }

                $duplicatesOccurred = $attachmentCreator->duplicatesOccurred();

                $isTextExtractionError = $attachmentCreator->isTextExtractionError();
                $textExtractionErrorMessage = $attachmentCreator->getTextExtractionError();

                // FIXME: Show parse errors!

                $fileUploaded = true;
                $resumePath = $attachmentCreator->getNewFilePath();
            }
        }

        $pipelines = new Pipelines($siteID);
        $activityEntries = new ActivityEntries($siteID);

        /* Check 3-month cooling period before allowing reapplication */
        if ($pipelines->isInCoolingPeriod($candidateID, $jobOrderID))
        {
            /* Show a friendly "already applied" page instead of generic error */
            $jobOrderData2 = $jobOrders->get($jobOrderID);
            $jobTitle2 = htmlspecialchars($jobOrderData2['title'] ?? 'this position');
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8">
<link href="../inter.css" rel="stylesheet">
<style>body{font-family:Inter,sans-serif;background:#f8fafc;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
.box{background:#fff;border-radius:16px;padding:48px 40px;text-align:center;max-width:440px;box-shadow:0 4px 24px rgba(0,0,0,.08);}
.icon{width:64px;height:64px;background:#fef3c7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;}
h2{font-size:20px;font-weight:700;color:#111827;margin:0 0 10px;}
p{font-size:14px;color:#6b7280;margin:0 0 28px;line-height:1.6;}
a{display:inline-block;padding:10px 24px;background:#2563eb;color:#fff;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;}
a:hover{background:#1d4ed8;}</style></head><body>
<div class="box">
  <div class="icon"><svg width="28" height="28" fill="none" stroke="#d97706" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></div>
  <h2>Already Applied</h2>
  <p>You have already applied for <strong>' . $jobTitle2 . '</strong>.<br>Please wait 3 months before reapplying for this position.</p>
  <a href="' . CATSUtility::getIndexName() . '?m=careers&amp;p=showAll">View Other Jobs</a>
</div></body></html>';
            die();
        }

        /* Is the candidate already in the pipeline for this job order? */
        $rs = $pipelines->get($candidateID, $jobOrderID);
        if (count($rs) == 0)
        {
            /* Attempt to add the candidate to the pipeline. */
            $pipelineAddResult = $pipelines->add($candidateID, $jobOrderID);
            if (!$pipelineAddResult)
            {
                CommonErrors::fatal(COMMONERROR_RECORDERROR, $this, 'Failed to add candidate to job order.');
            }

            // FIXME: For some reason, pipeline entries like to disappear between
            //        the above add() and this get(). WTF?
            $rs = $pipelines->get($candidateID, $jobOrderID);
            if (isset($rs['candidateJobOrderID']))
                $pipelines->updateRatingValue($rs['candidateJobOrderID'], -1);

            $newApplication = true;
        }
        else
        {
            $newApplication = false;
        }

        /* Build activity note. */
        if (!$newApplication)
        {
            $activityNote = 'User re-applied through candidate portal';
        }
        else
        {
            $activityNote = 'User applied through candidate portal';
        }

        if ($fileUploaded)
        {
            if (!$duplicatesOccurred)
            {
                $activityNote .= ' <span style="font-weight: bold;">and'
                    . ' attached a new resume (<a href="' . $resumePath
                    . '">Download</a>)</span>';
            }
            else
            {
                $activityNote .= ' and attached an existing resume (<a href="'
                    . $resumePath . '">Download</a>)';
            }
        }

		if (!empty($extraNotes))
		{
        	$activityNote .= '; added these notes: ' . $extraNotes;
		}

        /* Add the activity note. */
        $activityID = $activityEntries->add(
            $candidateID,
            DATA_ITEM_CANDIDATE,
            ACTIVITY_OTHER,
            $activityNote,
            $automatedUser['userID'],
            $jobOrderID
        );

        /* Send an E-Mail describing what happened. */
        $emailTemplates = new EmailTemplates($siteID);
        $candidatesEmailTemplateRS = $emailTemplates->getByTag(
            'EMAIL_TEMPLATE_CANDIDATEAPPLY'
        );

        if (!isset($candidatesEmailTemplateRS['textReplaced']) ||
            empty($candidatesEmailTemplateRS['textReplaced']) ||
            $candidatesEmailTemplateRS['disabled'] == 1)
        {
            $candidatesEmailTemplate = '';
        }
        else
        {
            $candidatesEmailTemplate = $candidatesEmailTemplateRS['textReplaced'];
        }

        /* Replace e-mail template variables. */
        /* E-Mail #1 - to candidate */
        $stringsToFind = array(
            '%CANDFIRSTNAME%',
            '%CANDFULLNAME%',
            '%JBODOWNER%',
            '%JBODTITLE%',
            '%JBODCLIENT%'
        );
        $replacementStrings = array(
            $firstName,
            $firstName . ' ' . $lastName,
            $jobOrderData['ownerFullName'],
            $jobOrderData['title'],
            $jobOrderData['companyName']

            //'<a href="http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '">'.
              //  'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '</a>'
        );
        $candidatesEmailTemplate = str_replace(
            $stringsToFind,
            $replacementStrings,
            $candidatesEmailTemplate
        );

        $emailContents = $candidatesEmailTemplate;

        /* Fallback: always send HTML confirmation email to candidate */
        if (empty($emailContents))
        {
            $siteName = defined('SITE_NAME') ? htmlspecialchars(SITE_NAME) : 'Neutara ATS';
            $jobTitleHtml = htmlspecialchars($jobOrderData['title'] ?? 'the position');
            $companyHtml  = htmlspecialchars($jobOrderData['companyName'] ?? '');
            $emailContents = '<html><body style="font-family:Arial,sans-serif;background:#f8fafc;padding:0;margin:0;">'
                . '<div style="max-width:520px;margin:40px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.07);">'
                . '<div style="background:#0d2488;padding:28px 32px;">'
                . '<span style="font-size:22px;font-weight:800;color:#fff;">N</span>'
                . '<span style="font-size:16px;font-weight:700;color:#fff;margin-left:8px;">' . $siteName . '</span>'
                . '</div>'
                . '<div style="padding:32px;">'
                . '<h2 style="font-size:20px;color:#064e3b;margin:0 0 12px;">Application Received ✓</h2>'
                . '<p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 16px;">Dear <strong>' . htmlspecialchars($firstName) . '</strong>,</p>'
                . '<p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 16px;">Thank you for applying for the position of <strong>' . $jobTitleHtml . '</strong>'
                . ($companyHtml ? ' at <strong>' . $companyHtml . '</strong>' : '') . '.</p>'
                . '<p style="color:#374151;font-size:14px;line-height:1.7;margin:0 0 24px;">We have received your application and our team will review it shortly. We will be in touch if your profile matches our requirements.</p>'
                . '<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:16px 20px;margin-bottom:24px;">'
                . '<div style="font-size:11px;font-weight:600;color:#16a34a;text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px;">Application Details</div>'
                . '<div style="font-size:14px;color:#166534;"><strong>Position:</strong> ' . $jobTitleHtml . '</div>'
                . '</div>'
                . '<p style="color:#6b7280;font-size:13px;line-height:1.6;margin:0;">Best regards,<br><strong>The ' . $siteName . ' Team</strong></p>'
                . '</div>'
                . '<div style="background:#f9fafb;padding:16px 32px;border-top:1px solid #e5e7eb;font-size:11px;color:#9ca3af;">This is an automated message. Please do not reply to this email.</div>'
                . '</div></body></html>';
        }

        if (!empty($emailContents))
        {
            $careerPortalSettings->sendEmail(
                $automatedUser['userID'],
                $email,
                'Application Received: ' . ($jobOrderData['title'] ?? 'Position'),
                $emailContents
            );
        }

        /* E-Mail #2 - to owner */

        $candidatesEmailTemplateRS = $emailTemplates->getByTag(
            'EMAIL_TEMPLATE_CANDIDATEPORTALNEW'
        );

        if (!isset($candidatesEmailTemplateRS['textReplaced']) ||
            empty($candidatesEmailTemplateRS['textReplaced']) ||
            $candidatesEmailTemplateRS['disabled'] == 1)
        {
            $candidatesEmailTemplate = '';
        }
        else
        {
            $candidatesEmailTemplate = $candidatesEmailTemplateRS['textReplaced'];
        }

        // FIXME: This will break if 'http' is elsewhere in the URL.
        $uri = str_replace('employment', '', $_SERVER['REQUEST_URI']);
        $uri = str_replace('http://', 'http', $uri);
        $uri = str_replace('//', '/', $uri);
        $uri = str_replace('http', 'http://', $uri);
        $uri = str_replace('/careers', '', $uri);

        /* Replace e-mail template variables. */
        $stringsToFind = array(
            '%CANDFIRSTNAME%',
            '%CANDFULLNAME%',
            '%JBODOWNER%',
            '%CANDOWNER%',     // Because the candidate was just added, we assume
            '%JBODTITLE%',     // the candidate owner = job order owner.
            '%JBODCLIENT%',
            '%CANDCATSURL%',
            '%JBODID%',
            '%JBODCATSURL%'
        );
        $replacementStrings = array(
            $firstName,
            $firstName . ' ' . $lastName,
            $jobOrderData['ownerFullName'],
            $jobOrderData['ownerFullName'],
            $jobOrderData['title'],
            $jobOrderData['companyName'],
            '<a href="http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '">'.
                'http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=candidates&amp;a=show&amp;candidateID=' . $candidateID . '</a>',
            $jobOrderData['jobOrderID'],
            '<a href="http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=joborders&amp;a=show&amp;jobOrderID=' . $jobOrderData['jobOrderID'] . '">'.
                'http://' . $_SERVER['HTTP_HOST'] . substr($uri, 0, strpos($uri, '?')) . '?m=joborders&amp;a=show&amp;jobOrderID=' . $jobOrderData['jobOrderID'] . '</a>',
        );
        $candidatesEmailTemplate = str_replace(
            $stringsToFind,
            $replacementStrings,
            $candidatesEmailTemplate
        );

        $emailContents = $candidatesEmailTemplate;

        if (!empty($emailContents))
        {
            $careerPortalSettings->sendEmail(
                $automatedUser['userID'],
                $jobOrderData['owner_email'],
                CAREERS_OWNERAPPLY_SUBJECT,
                $emailContents
            );


            if ($jobOrderData['owner_email'] != $jobOrderData['recruiter_email'])
            {
                $careerPortalSettings->sendEmail(
                    $automatedUser['userID'],
                    $jobOrderData['recruiter_email'],
                    CAREERS_OWNERAPPLY_SUBJECT,
                    $emailContents
                );
            }
        }
    }

    public function capturePostData($siteID, $ignore = array())
    {
        $hiddenTags = '';

        foreach ($_POST as $name => $value)
        {
            if (in_array($name, $ignore)) continue;
            $hiddenTags .= sprintf('<input type="hidden" name="%s" value="%s" />%s',
                $name,
                htmlspecialchars($value),
                "\n"
            );
        }

        if (($uploadFile = FileUtility::getUploadFileFromPost($siteID, 'careerportaladd', 'file')) !== false)
        {
            $hiddenTags .= sprintf('<input type="hidden" name="file" value="%s" />%s',
                $uploadFile, "\n"
            );
        }

        return $hiddenTags;
    }

    private function isCandidateRegistered($siteID, $template)
    {
        $fields = $this->getCookieFields($siteID);
        return $this->ProcessCandidateRegistration($siteID, $template, $fields, true) ? true : false;
    }

    private function ProcessCandidateRegistration($siteID, $template, $cookieFields = array(), $ignorePost = false)
    {
        $db = DatabaseConnection::getInstance();

        $numMatches = preg_match_all('/\<input\-([A-Za-z0-9]+)\>/', $template, $matches);
        if (!$numMatches) return false;
        $fields = array();

        foreach ($matches[1] as $tag)
        {
            // Default tags, NOT verification fields
            if (!strcasecmp('submit', $tag) || !strcasecmp('new', $tag) || !strcasecmp('registered', $tag) ||
                !strcasecmp('rememberMe', $tag))
            {
                continue;
            }

            // All verification tags MUST exist and be completed (javascript validates this)
            if (!isset($_POST[$tag]) || empty($_POST[$tag]) || $ignorePost)
            {
                // There is no post, but this call might be coming from saved cookie data
                if (!isset($cookieFields[$tag]))
                {
                    // Some fields may have different naming
                    if (!strcmp($tag, 'email') && isset($cookieFields[$id='email1'])) $fields[$tag] = $cookieFields[$id];
                    else if (!strcmp($tag, 'employer') && isset($cookieFields[$id='currentEmployer'])) $fields[$tag] = $cookieFields[$id];
                    else if (!strcmp($tag, 'phone') && isset($cookieFields[$id='phoneWork'])) $fields[$tag] = $cookieFields[$id];
                    else return false;
                }
                else
                {
                    $fields[$tag] = $cookieFields[$tag];
                }
            }
            else
            {
                $fields[$tag] = trim($_POST[$tag]);
            }
        }

        // Get a list of candidate fields to compare against
        $sql = 'SHOW COLUMNS FROM candidate';
        $columns = $db->getAllAssoc($sql);
        for ($i = 0; $i < count($columns); $i++)
        {
            // Convert out of _ notation to camel notation
            $columns[$i]['CamelField'] = str_replace('_', '', $columns[$i]['Field']);
        }

        $verificationFields = 0;
        $sql = 'SELECT candidate_id FROM candidate WHERE ';

        foreach ($fields as $tag => $tagData)
        {
            foreach ($columns as $column => $columnData)
            {
                if (!strcasecmp($columnData['CamelField'], $tag))
                {
                    $sql .= 'LCASE(' . $columnData['Field'] . ') = '
                        . $db->makeQueryString(strtolower($tagData)) . ' AND ';
                    $verificationFields++;
                }
            }
        }

        // There needs to be 1 verification field (equivilant of a "password"), otherwise anyone
        // could change anyone else's candidate information with as little as an e-mail address.
        if ($verificationFields < 1)
        {
            return false;
        }

        $sql .= sprintf('site_id = %d AND (LCASE(email1) = %s OR LCASE(email2) = %s) LIMIT 1',
            $siteID,
            $db->makeQueryString(strtolower($fields['email'])),
            $db->makeQueryString(strtolower($fields['email']))
        );

        $rs = $db->getAssoc($sql);

        if ($db->getNumRows())
        {
            $candidates = new Candidates($siteID);
            $candidate = $candidates->get($rs['candidate_id']);

            // Setup a cookie to remember the user by for the next 2 weeks
            if (isset($_POST['rememberMe']) && !strcasecmp($_POST['rememberMe'], 'yes'))
            {
                $storedVal = '';
                foreach ($fields as $tag => $tagData)
                {
                    $storedVal .= sprintf('"%s"="%s"', urlencode($tag), urlencode($tagData));
                }
                @setcookie($this->getCareerPortalCookieName($siteID), $storedVal, time()+60*60*24*7*2);
            }

            return $candidate;
        }

        return false;
    }

    private function getCareerPortalCookieName($siteID)
    {
        return sprintf('cats%dcw', $siteID);
    }

    private function getCookieFields($siteID)
    {
        $fields = array();

        // Check if there's a cookie to prefill the fields with
        if (isset($_COOKIE[$id=$this->getCareerPortalCookieName($siteID)]))
        {
            if (preg_match_all('/"([^"]+)"="([^"]*)"/', $_COOKIE[$id], $matches) > 0)
            {
                for ($i = 0; $i < count($matches[1]); $i++)
                {
                    $fields[urldecode($matches[1][$i])] = urldecode($matches[2][$i]);
                    // Some fields have multiple meanings:
                    if (!strcmp($matches[1][$i], 'email1')) $fields['email'] = urldecode($matches[2][$i]);
                    else if (!strcmp($matches[1][$i], 'currentEmployer')) $fields['employer'] = urldecode($matches[2][$i]);
                    else if (!strcmp($matches[1][$i], 'phoneWork')) $fields['phone'] = urldecode($matches[2][$i]);
                }
            }
        }

        return $fields;
    }

    private function getRegisteredCandidateBlock($siteID, $template)
    {
        $fields = $this->getCookieFields($siteID);
        $candidate = $this->ProcessCandidateRegistration($siteID, $template, $fields);

        if ($candidate !== false)
        {
            return sprintf(
                '<form style="padding:0;margin:0;border:0;" name="logout" id="logout" method="post" '
                . 'action="%s%s"><input type="hidden" id="pa" name="pa" value="" />%s<div style="margin: 20px 0 20px 0; '
                . 'line-height: 18px;"> '
                . '<h3 style="font-weight: normal;"><b>Welcome back %s.</b>&nbsp;&nbsp;Not %s? '
                . '<a href="javascript:void(0);" onclick="document.getElementById(\'pa\').value=\'logout\'; '
                . 'document.logout.submit();">Log Out</a>.'
                . '&nbsp;&nbsp;Need to update your information? <a href="javascript:void(0);" onclick="document.getElementById(\'pa\').value=\'updateProfile\'; '
                . 'document.logout.submit();">Update Profile</a>.'
                . '</h3></div>',
                CATSUtility::getIndexName(),
                $_SERVER['QUERY_STRING'] != '' ? '?' . $_SERVER['QUERY_STRING'] : '',
                $this->capturePostData($siteID, array('pa')),
                $candidate['firstName'],
                $candidate['firstName']
            );
        }

        return '';
    }
}

?>
