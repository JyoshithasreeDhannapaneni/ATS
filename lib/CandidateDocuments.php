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

    public function validateToken($token)
    {
        $token = $this->_db->makeQueryString($token);
        $sql = "SELECT t.*, c.first_name AS firstName, c.last_name AS lastName,
                       c.email1, c.email2
                FROM candidate_upload_token t
                JOIN candidate c ON c.candidate_id = t.candidate_id AND c.site_id = t.site_id
                WHERE t.token = '{$token}'
                  AND t.is_active = 1
                  AND t.expires_date > NOW()
                  AND t.upload_count < t.max_uploads";

        return $this->_db->getAssoc($sql);
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

        return $this->_db->getAllAssoc($sql);
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

        $row = $this->_db->getAssoc($sql);
        return $row ? $row['token'] : null;
    }

    public function saveDocument($candidateID, $tokenID, $docType, $originalFilename,
                                  $storedFilename, $directoryName, $fileSizeKB, $contentType)
    {
        $originalFilename = $this->_db->makeQueryString($originalFilename);
        $storedFilename = $this->_db->makeQueryString($storedFilename);
        $directoryName = $this->_db->makeQueryString($directoryName);
        $docType = $this->_db->makeQueryString($docType);
        $contentType = $this->_db->makeQueryString($contentType);

        $sql = "INSERT INTO candidate_document
                    (candidate_id, site_id, token_id, document_type, original_filename,
                     stored_filename, directory_name, file_size_kb, content_type, uploaded_date)
                VALUES
                    ({$candidateID}, {$this->_siteID}, " . ($tokenID ? $tokenID : 'NULL') . ",
                     '{$docType}', '{$originalFilename}', '{$storedFilename}',
                     '{$directoryName}', {$fileSizeKB}, '{$contentType}', NOW())";

        $this->_db->query($sql);
        return $this->_db->getLastInsertID();
    }

    public function getDocumentsForCandidate($candidateID)
    {
        $sql = "SELECT d.*,
                       DATE_FORMAT(d.uploaded_date, '%b %d, %Y %h:%i %p') AS uploadedDateFormatted
                FROM candidate_document d
                WHERE d.candidate_id = {$candidateID}
                  AND d.site_id = {$this->_siteID}
                ORDER BY d.uploaded_date DESC";

        return $this->_db->getAllAssoc($sql);
    }

    public function getDocument($documentID)
    {
        $sql = "SELECT * FROM candidate_document
                WHERE document_id = {$documentID}
                  AND site_id = {$this->_siteID}";

        return $this->_db->getAssoc($sql);
    }

    public function updateDocumentStatus($documentID, $status, $notes = '')
    {
        $notes = $this->_db->makeQueryString($notes);
        $status = $this->_db->makeQueryString($status);

        $sql = "UPDATE candidate_document
                SET status = '{$status}', notes = '{$notes}'
                WHERE document_id = {$documentID}
                  AND site_id = {$this->_siteID}";

        $this->_db->query($sql);
    }

    public function deleteDocument($documentID)
    {
        $doc = $this->getDocument($documentID);
        if (!$doc) return false;

        $filePath = './uploads/documents/' . $doc['directory_name'] . '/' . $doc['stored_filename'];
        if (file_exists($filePath))
        {
            unlink($filePath);
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
        $dir = './uploads/documents/' . $candidateID;
        if (!is_dir($dir))
        {
            mkdir($dir, 0755, true);
        }
        return $dir;
    }
}
