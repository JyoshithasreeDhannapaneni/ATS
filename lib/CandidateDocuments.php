<?php
/**
 * Candidate Document Upload Portal
 * Manages upload tokens and document storage for hired candidates.
 */

include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');

class CandidateDocuments
{
    private $_db;
    private $_siteID;

    public function __construct($siteID)
    {
        $this->_db = DatabaseConnection::getInstance();
        $this->_siteID = $siteID;
        
        // Ensure tables exist on first use
        $this->_ensureTablesExist();
    }

    public function generateToken($candidateID, $createdBy, $expiryDays = 7)
    {
        
        $token = bin2hex(random_bytes(32));
        $expiresDate = date('Y-m-d H:i:s', strtotime("+{$expiryDays} days"));

        $sql = "INSERT INTO candidate_upload_token
                    (candidate_id, site_id, token, created_by, created_date, expires_date)
                VALUES
                    ({$candidateID}, {$this->_siteID}, '{$token}', {$createdBy}, NOW(), '{$expiresDate}')";

        $this->_db->query($sql);
        return $token;
    }
    
    private function _ensureTablesExist()
    {
        try {
            $this->_db->query("CREATE TABLE IF NOT EXISTS candidate_upload_token (
                token_id SERIAL PRIMARY KEY,
                candidate_id INTEGER NOT NULL,
                site_id INTEGER NOT NULL DEFAULT 1,
                token VARCHAR(64) NOT NULL UNIQUE,
                created_by INTEGER NOT NULL,
                created_date TIMESTAMP NOT NULL,
                expires_date TIMESTAMP NOT NULL,
                is_active SMALLINT NOT NULL DEFAULT 1,
                max_uploads INTEGER NOT NULL DEFAULT 20,
                upload_count INTEGER NOT NULL DEFAULT 0
            )", true);

            $this->_db->query("CREATE TABLE IF NOT EXISTS candidate_document (
                document_id SERIAL PRIMARY KEY,
                candidate_id INTEGER NOT NULL,
                site_id INTEGER NOT NULL DEFAULT 1,
                token_id INTEGER DEFAULT NULL,
                document_type VARCHAR(50) NOT NULL DEFAULT 'other',
                original_filename VARCHAR(255) NOT NULL,
                stored_filename VARCHAR(255) NOT NULL,
                directory_name VARCHAR(255) NOT NULL,
                file_size_kb INTEGER NOT NULL DEFAULT 0,
                content_type VARCHAR(100) NOT NULL DEFAULT 'application/octet-stream',
                file_hash VARCHAR(64) DEFAULT NULL,
                uploaded_date TIMESTAMP NOT NULL,
                status VARCHAR(20) NOT NULL DEFAULT 'pending',
                notes TEXT
            )", true);

            // Add file_hash column if table already existed without it
            try {
                $this->_db->query("ALTER TABLE candidate_document ADD COLUMN IF NOT EXISTS file_hash VARCHAR(64) DEFAULT NULL", true);
            } catch (Exception $e) {
                // Column may already exist
            }
        } catch (Exception $e) {
            // Silently fail
        }
    }

    public function validateToken($token)
    {
        $tokenEscaped = $this->_db->makeQueryString($token);

        $sql = "SELECT t.*, c.first_name AS firstName, c.last_name AS lastName,
                       c.email1, c.email2
                FROM candidate_upload_token t
                LEFT JOIN candidate c ON c.candidate_id = t.candidate_id
                WHERE t.token = {$tokenEscaped}
                  AND t.is_active = 1
                  AND t.expires_date > NOW()
                  AND t.upload_count < t.max_uploads";

        try {
            $rs = $this->_db->getAssoc($sql);
            return (!empty($rs)) ? $rs : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function deactivateToken($tokenID)
    {
        $sql = "UPDATE candidate_upload_token SET is_active = 0 WHERE token_id = {$tokenID}";
        $this->_db->query($sql);
    }

    public function incrementUploadCount($tokenID)
    {
        $sql = "UPDATE candidate_upload_token SET upload_count = upload_count + 1 WHERE token_id = {$tokenID}";
        $this->_db->query($sql);
    }

    public function getTokensForCandidate($candidateID)
    {
        $sql = "SELECT t.*, u.first_name AS createdByFirst, u.last_name AS createdByLast
                FROM candidate_upload_token t
                LEFT JOIN user u ON u.user_id = t.created_by AND u.site_id = t.site_id
                WHERE t.candidate_id = {$candidateID}
                  AND t.site_id = {$this->_siteID}
                ORDER BY t.created_date DESC";

        try {
            return $this->_db->getAllAssoc($sql);
        } catch (Exception $e) {
            return array();
        }
    }

    public function getActiveTokenForCandidate($candidateID)
    {
        $sql = "SELECT token
                FROM candidate_upload_token
                WHERE candidate_id = {$candidateID}
                  AND site_id = {$this->_siteID}
                  AND is_active = 1
                  AND expires_date > NOW()
                ORDER BY created_date DESC
                LIMIT 1";

        try {
            $rs = $this->_db->getAssoc($sql);
            return (!empty($rs) && isset($rs['token'])) ? $rs['token'] : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function isDuplicateFile($candidateID, $fileHash)
    {
        if (empty($fileHash)) return false;

        $sql = sprintf(
            "SELECT document_id, original_filename FROM candidate_document
             WHERE candidate_id = %d AND file_hash = %s",
            intval($candidateID),
            $this->_db->makeQueryString($fileHash)
        );

        try {
            $rs = $this->_db->getAssoc($sql);
            return (!empty($rs)) ? $rs : false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function saveDocument($candidateID, $tokenID, $docType, $originalFilename,
                                  $storedFilename, $directoryName, $fileSizeKB, $contentType,
                                  $fileHash = '')
    {
        $originalFilename = $this->_db->makeQueryString($originalFilename);
        $storedFilename = $this->_db->makeQueryString($storedFilename);
        $directoryName = $this->_db->makeQueryString($directoryName);
        $docType = $this->_db->makeQueryString($docType);
        $contentType = $this->_db->makeQueryString($contentType);
        $fileHashVal = !empty($fileHash) ? $this->_db->makeQueryString($fileHash) : 'NULL';

        $sql = "INSERT INTO candidate_document
                    (candidate_id, site_id, token_id, document_type, original_filename,
                     stored_filename, directory_name, file_size_kb, content_type, file_hash, uploaded_date)
                VALUES
                    ({$candidateID}, {$this->_siteID}, " . ($tokenID ? $tokenID : 'NULL') . ",
                     {$docType}, {$originalFilename}, {$storedFilename},
                     {$directoryName}, {$fileSizeKB}, {$contentType}, {$fileHashVal}, NOW())";

        $this->_db->query($sql);
        return $this->_db->getLastInsertID();
    }

    public function getDocumentsForCandidate($candidateID)
    {
        // Don't filter by site_id - documents belong to a candidate regardless of site
        // This ensures documents uploaded via public link are visible to all site users
        $sql = "SELECT d.*,
                       DATE_FORMAT(d.uploaded_date, '%b %d, %Y %h:%i %p') AS uploadedDateFormatted
                FROM candidate_document d
                WHERE d.candidate_id = " . intval($candidateID) . "
                ORDER BY d.uploaded_date DESC";

        try {
            return $this->_db->getAllAssoc($sql);
        } catch (Exception $e) {
            return array();
        }
    }

    public function getDocument($documentID)
    {
        // Don't filter by site_id - allow access if document exists
        $sql = "SELECT * FROM candidate_document
                WHERE document_id = " . intval($documentID);

        try {
            $rs = $this->_db->getAssoc($sql);
            return (!empty($rs)) ? $rs : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function updateDocumentStatus($documentID, $status, $notes = '')
    {
        $notes = $this->_db->makeQueryString($notes);
        $status = $this->_db->makeQueryString($status);

        $sql = "UPDATE candidate_document
                SET status = '{$status}', notes = '{$notes}'
                WHERE document_id = " . intval($documentID);

        $this->_db->query($sql);
    }

    public function deleteDocument($documentID)
    {
        $doc = $this->getDocument($documentID);
        if (!$doc) return false;

        // Use LEGACY_ROOT for consistent path resolution
        $filePath = self::getUploadDirectory($doc['candidate_id']) . '/' . $doc['stored_filename'];
        if (file_exists($filePath))
        {
            @unlink($filePath);
        }

        $sql = "DELETE FROM candidate_document WHERE document_id = {$documentID}";
        $this->_db->query($sql);
        return true;
    }

    public static function getDocumentTypes()
    {
        return array(
            'id_proof'       => 'ID Proof (Aadhaar/Passport/PAN)',
            'education'      => 'Education Certificate',
            'experience'     => 'Experience Letter',
            'payslip'        => 'Recent Payslips',
            'offer_letter'   => 'Offer Letter (Previous)',
            'relieving'      => 'Relieving Letter',
            'photo'          => 'Passport Photo',
            'address_proof'  => 'Address Proof',
            'bank_details'   => 'Bank Details / Cancelled Cheque',
            'medical'        => 'Medical Certificate',
            'other'          => 'Other Document'
        );
    }

    public static function getUploadDirectory($candidateID)
    {
        // Use LEGACY_ROOT for consistent path resolution from any location
        $baseDir = defined('LEGACY_ROOT') ? LEGACY_ROOT : '.';
        $dir = $baseDir . '/uploads/documents/' . $candidateID;
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }
}
