<?php
/*
 * CATS
 * Attachments Module
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
 * $Id: HomeUI.php 2969 2007-08-29 23:33:39Z brian $
 */

include_once(LEGACY_ROOT . '/lib/CommonErrors.php');
include_once(LEGACY_ROOT . '/lib/Attachments.php');

class AttachmentsUI extends UserInterface
{
    /* This is how many bytes at a time we read and output from an attachment. */
    const ATTACHMENT_BLOCK_SIZE = 80192;


    public function __construct()
    {
        parent::__construct();

        $this->_authenticationRequired = true;
        $this->_moduleDirectory = 'attachments';
        $this->_moduleName = 'attachments';
        $this->_moduleTabText = '';
        $this->_subTabs = array();
    }


    public function handleRequest()
    {
        $action = $this->getAction();

        if (!eval(Hooks::get('ATTACHMENTS_HANDLE_REQUEST'))) return;

        switch ($action)
        {
            case 'getAttachment':
                $this->getAttachment();
                break;
                
            default:
                break;
        }
    }


    private function getAttachment()
    {
        @ini_set('memory_limit', '128M'); 
        
        if (!$this->isRequiredIDValid('id', $_GET))
        {
            CommonErrors::fatal(
                COMMONERROR_BADINDEX, $this, 'No attachment ID specified.'
            );
        }

        $attachmentID = $_GET['id'];

        $attachments = new Attachments(-1);
        $rs = $attachments->get($attachmentID, false);

        if (empty($rs) || md5($rs['directoryName']) != $_GET['directoryNameHash'])
        {
            CommonErrors::fatal(
                COMMONERROR_BADFIELDS,
                $this,
                'Invalid id / directory / filename, or you do not have permission to access this attachment.'
            );
        }
        
        $directoryName = $rs['directoryName'];
        $fileName      = $rs['storedFilename'];
        $filePath      = sprintf('attachments/%s/%s', $directoryName, $fileName);

        if ($rs['contentType'] == 'catsbackup' && !file_exists($filePath))
        {
            CommonErrors::fatal(
                COMMONERROR_FILENOTFOUND,
                $this,
                'The specified backup file no longer exists. Please go back and regenerate the backup before downloading. We are sorry for the inconvenience.'
            );
        }
        
        if (!eval(Hooks::get('ATTACHMENT_RETRIEVAL'))) return;

        $contentType = Attachments::fileMimeType($fileName);

        $fp = @fopen($filePath, 'r');
        if ($fp === false)
        {
            $this->_serveTextFallback($attachmentID, $fileName);
            return;
        }

        header('Content-Disposition: inline; filename="' . $fileName . '"');
        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: no-cache');
        header('Expires: 0');
        
        while (!feof($fp))
        {
            print fread($fp, self::ATTACHMENT_BLOCK_SIZE);
        }
        
        fclose($fp);
        exit();
    }

    /**
     * When the physical file is missing (e.g. Render ephemeral filesystem),
     * serve the extracted text stored in the database instead.
     */
    private function _serveTextFallback($attachmentID, $originalFileName)
    {
        $db = DatabaseConnection::getInstance();

        $sql = sprintf(
            "SELECT text FROM attachment WHERE attachment_id = %s",
            $db->makeQueryInteger($attachmentID)
        );
        $row = $db->getAssoc($sql);

        if (!empty($row) && !empty($row['text']))
        {
            $textFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '.txt';

            header('Content-Disposition: inline; filename="' . $textFileName . '"');
            header('Content-Type: text/plain; charset=utf-8');
            header('Pragma: no-cache');
            header('Expires: 0');
            echo $row['text'];
            exit();
        }

        CommonErrors::fatal(
            COMMONERROR_BADFIELDS,
            $this,
            'The attachment file is not available on this server. '
            . 'Files uploaded locally are not available on cloud deployments. '
            . 'Please re-upload the attachment.'
        );
    }

}

?>
