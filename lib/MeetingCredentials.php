<?php
/**
 * Meeting Credentials Manager
 * 
 * Handles storage and retrieval of meeting platform credentials.
 * Supports both config.php and database storage with encryption.
 *
 * @package    CATS
 * @subpackage Library
 */

class MeetingCredentials
{
    private $_db;
    private $_siteID;
    private $_encryptionKey;
    private $_credentialsCache = array();
    
    const SOURCE_CONFIG = 'config_file';
    const SOURCE_DATABASE = 'database';
    
    const PLATFORM_TEAMS = 'teams';
    const PLATFORM_ZOOM = 'zoom';
    const PLATFORM_GOOGLE = 'google_meet';

    public function __construct($siteID)
    {
        $this->_siteID = $siteID;
        $this->_db = DatabaseConnection::getInstance();
        
        // Use LICENSE_KEY as encryption key base (always available)
        $this->_encryptionKey = defined('LICENSE_KEY') ? LICENSE_KEY : 'default_encryption_key_change_me';
    }

    /**
     * Get the credential storage source (config_file or database)
     *
     * @return string
     */
    public function getCredentialSource()
    {
        $sql = sprintf(
            "SELECT setting_value FROM settings 
             WHERE setting_key = 'meeting_credentials_source' 
             AND site_id = %s",
            $this->_siteID
        );
        
        $result = @$this->_db->query($sql);
        if ($result && @mysqli_num_rows($result) > 0) {
            $row = $this->_db->getAssoc($result);
            return $row['setting_value'];
        }
        
        // If table doesn't exist, fall back to config
        if (!$this->tableExists()) {
            return self::SOURCE_CONFIG;
        }
        
        // Default to database if setting doesn't exist but table does
        return self::SOURCE_DATABASE;
    }

    /**
     * Set the credential storage source
     *
     * @param string $source 'config_file' or 'database'
     * @return boolean
     */
    public function setCredentialSource($source)
    {
        if (!in_array($source, array(self::SOURCE_CONFIG, self::SOURCE_DATABASE))) {
            return false;
        }
        
        $sql = sprintf(
            "SELECT setting_id FROM settings 
             WHERE setting_key = 'meeting_credentials_source' 
             AND site_id = %s",
            $this->_siteID
        );
        
        $result = @$this->_db->query($sql);
        
        if ($result && @mysqli_num_rows($result) > 0) {
            $sql = sprintf(
                "UPDATE settings SET setting_value = %s 
                 WHERE setting_key = 'meeting_credentials_source' 
                 AND site_id = %s",
                $this->_db->makeQueryString($source),
                $this->_siteID
            );
        } else {
            $sql = sprintf(
                "INSERT INTO settings (setting_key, setting_value, site_id) 
                 VALUES ('meeting_credentials_source', %s, %s)",
                $this->_db->makeQueryString($source),
                $this->_siteID
            );
        }
        
        return $this->_db->query($sql) !== false;
    }

    /**
     * Get a credential value for a platform
     *
     * @param string $platform Platform identifier
     * @param string $key Credential key (e.g., 'client_id', 'client_secret')
     * @return string|null Credential value or null if not found
     */
    public function getCredential($platform, $key)
    {
        $source = $this->getCredentialSource();
        
        if ($source === self::SOURCE_CONFIG) {
            return $this->getCredentialFromConfig($platform, $key);
        }
        
        return $this->getCredentialFromDatabase($platform, $key);
    }

    /**
     * Get credential from config.php constants
     */
    private function getCredentialFromConfig($platform, $key)
    {
        $constantMap = array(
            'teams' => array(
                'client_id' => 'MS_TEAMS_CLIENT_ID',
                'client_secret' => 'MS_TEAMS_CLIENT_SECRET',
                'tenant_id' => 'MS_TEAMS_TENANT_ID',
                'user_id' => 'MS_TEAMS_USER_ID',
                'enabled' => 'MS_TEAMS_ENABLED'
            ),
            'zoom' => array(
                'account_id' => 'ZOOM_ACCOUNT_ID',
                'client_id' => 'ZOOM_CLIENT_ID',
                'client_secret' => 'ZOOM_CLIENT_SECRET',
                'user_id' => 'ZOOM_USER_ID',
                'enabled' => 'ZOOM_ENABLED'
            ),
            'google_meet' => array(
                'client_id' => 'GOOGLE_CLIENT_ID',
                'client_secret' => 'GOOGLE_CLIENT_SECRET',
                'redirect_uri' => 'GOOGLE_REDIRECT_URI',
                'calendar_id' => 'GOOGLE_CALENDAR_ID',
                'enabled' => 'GOOGLE_MEET_ENABLED'
            )
        );
        
        if (!isset($constantMap[$platform][$key])) {
            return null;
        }
        
        $constant = $constantMap[$platform][$key];
        return defined($constant) ? constant($constant) : null;
    }

    /**
     * Check if the meeting_credentials table exists
     */
    private function tableExists($resetCache = false)
    {
        static $exists = null;
        
        if ($resetCache) {
            $exists = null;
        }
        
        if ($exists !== null) {
            return $exists;
        }
        
        $sql = "SHOW TABLES LIKE 'meeting_credentials'";
        $result = @$this->_db->query($sql);
        
        if ($result === false) {
            $exists = false;
            return false;
        }
        
        $exists = (@mysqli_num_rows($result) > 0);
        return $exists;
    }

    /**
     * Get credential from database
     */
    private function getCredentialFromDatabase($platform, $key)
    {
        // Check if table exists first
        if (!$this->tableExists()) {
            return $this->getCredentialFromConfig($platform, $key);
        }
        
        // Check cache first
        $cacheKey = $platform . '_' . $key;
        if (isset($this->_credentialsCache[$cacheKey])) {
            return $this->_credentialsCache[$cacheKey];
        }
        
        $sql = sprintf(
            "SELECT credential_value, is_encrypted FROM meeting_credentials 
             WHERE site_id = %s AND platform = %s AND credential_key = %s",
            $this->_siteID,
            $this->_db->makeQueryString($platform),
            $this->_db->makeQueryString($key)
        );
        
        $result = @$this->_db->query($sql);
        
        if ($result && @mysqli_num_rows($result) > 0) {
            $row = $this->_db->getAssoc($result);
            $value = $row['credential_value'];
            
            // Decrypt if encrypted
            if ($row['is_encrypted'] && !empty($value)) {
                $value = $this->decrypt($value);
            }
            
            // Cache the result
            $this->_credentialsCache[$cacheKey] = $value;
            
            return $value;
        }
        
        // Fall back to config.php if not in database
        return $this->getCredentialFromConfig($platform, $key);
    }

    /**
     * Save a credential to the database
     *
     * @param string $platform Platform identifier
     * @param string $key Credential key
     * @param string $value Credential value
     * @param boolean $encrypt Whether to encrypt the value
     * @return boolean Success status
     */
    public function saveCredential($platform, $key, $value, $encrypt = true)
    {
        // Check if table exists
        if (!$this->tableExists()) {
            // Try to create the table
            $this->createCredentialsTable();
            if (!$this->tableExists()) {
                return false;
            }
        }
        
        // Clear cache
        $cacheKey = $platform . '_' . $key;
        unset($this->_credentialsCache[$cacheKey]);
        
        // Encrypt if needed
        $storedValue = $value;
        if ($encrypt && !empty($value)) {
            $storedValue = $this->encrypt($value);
        }
        
        // Check if exists
        $sql = sprintf(
            "SELECT credential_id FROM meeting_credentials 
             WHERE site_id = %s AND platform = %s AND credential_key = %s",
            $this->_siteID,
            $this->_db->makeQueryString($platform),
            $this->_db->makeQueryString($key)
        );
        
        $result = @$this->_db->query($sql);
        
        if ($result && @mysqli_num_rows($result) > 0) {
            // Update existing
            $sql = sprintf(
                "UPDATE meeting_credentials 
                 SET credential_value = %s, is_encrypted = %d, date_modified = NOW()
                 WHERE site_id = %s AND platform = %s AND credential_key = %s",
                $this->_db->makeQueryString($storedValue),
                $encrypt ? 1 : 0,
                $this->_siteID,
                $this->_db->makeQueryString($platform),
                $this->_db->makeQueryString($key)
            );
        } else {
            // Insert new
            $sql = sprintf(
                "INSERT INTO meeting_credentials 
                 (site_id, platform, credential_key, credential_value, is_encrypted, date_created, date_modified)
                 VALUES (%s, %s, %s, %s, %d, NOW(), NOW())",
                $this->_siteID,
                $this->_db->makeQueryString($platform),
                $this->_db->makeQueryString($key),
                $this->_db->makeQueryString($storedValue),
                $encrypt ? 1 : 0
            );
        }
        
        return @$this->_db->query($sql) !== false;
    }
    
    /**
     * Create the meeting_credentials table if it doesn't exist
     */
    private function createCredentialsTable()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `meeting_credentials` (
            `credential_id` INT(11) NOT NULL AUTO_INCREMENT,
            `site_id` INT(11) NOT NULL DEFAULT 1,
            `platform` VARCHAR(50) NOT NULL,
            `credential_key` VARCHAR(100) NOT NULL,
            `credential_value` TEXT,
            `is_encrypted` TINYINT(1) NOT NULL DEFAULT 1,
            `date_created` DATETIME NOT NULL,
            `date_modified` DATETIME NOT NULL,
            PRIMARY KEY (`credential_id`),
            UNIQUE KEY `idx_platform_key` (`site_id`, `platform`, `credential_key`),
            KEY `idx_site_platform` (`site_id`, `platform`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
        
        @$this->_db->query($sql);
        
        // Reset the static cache
        $this->tableExists(true);
    }

    /**
     * Save all credentials for a platform at once
     *
     * @param string $platform Platform identifier
     * @param array $credentials Array of key => value pairs
     * @return boolean Success status
     */
    public function savePlatformCredentials($platform, $credentials)
    {
        $success = true;
        
        foreach ($credentials as $key => $value) {
            // Don't encrypt 'enabled' flag or empty values
            $encrypt = ($key !== 'enabled' && !empty($value));
            if (!$this->saveCredential($platform, $key, $value, $encrypt)) {
                $success = false;
            }
        }
        
        return $success;
    }

    /**
     * Get all credentials for a platform
     *
     * @param string $platform Platform identifier
     * @return array Credentials array
     */
    public function getPlatformCredentials($platform)
    {
        $keys = $this->getPlatformCredentialKeys($platform);
        $credentials = array();
        
        foreach ($keys as $key) {
            $credentials[$key] = $this->getCredential($platform, $key);
        }
        
        return $credentials;
    }

    /**
     * Get credential keys for a platform
     */
    private function getPlatformCredentialKeys($platform)
    {
        $keys = array(
            'teams' => array('client_id', 'client_secret', 'tenant_id', 'user_id', 'enabled'),
            'zoom' => array('account_id', 'client_id', 'client_secret', 'user_id', 'enabled'),
            'google_meet' => array('client_id', 'client_secret', 'redirect_uri', 'calendar_id', 'enabled')
        );
        
        return isset($keys[$platform]) ? $keys[$platform] : array();
    }

    /**
     * Check if a platform is configured (has required credentials)
     *
     * @param string $platform Platform identifier
     * @return boolean
     */
    public function isPlatformConfigured($platform)
    {
        switch ($platform) {
            case self::PLATFORM_TEAMS:
                return !empty($this->getCredential('teams', 'client_id')) &&
                       !empty($this->getCredential('teams', 'client_secret')) &&
                       !empty($this->getCredential('teams', 'tenant_id'));
                       
            case self::PLATFORM_ZOOM:
                return !empty($this->getCredential('zoom', 'account_id')) &&
                       !empty($this->getCredential('zoom', 'client_id')) &&
                       !empty($this->getCredential('zoom', 'client_secret'));
                       
            case self::PLATFORM_GOOGLE:
                return !empty($this->getCredential('google_meet', 'client_id')) &&
                       !empty($this->getCredential('google_meet', 'client_secret'));
                       
            default:
                return false;
        }
    }

    /**
     * Delete all credentials for a platform
     *
     * @param string $platform Platform identifier
     * @return boolean Success status
     */
    public function deletePlatformCredentials($platform)
    {
        // Clear cache for this platform
        foreach ($this->_credentialsCache as $key => $value) {
            if (strpos($key, $platform . '_') === 0) {
                unset($this->_credentialsCache[$key]);
            }
        }
        
        $sql = sprintf(
            "DELETE FROM meeting_credentials 
             WHERE site_id = %s AND platform = %s",
            $this->_siteID,
            $this->_db->makeQueryString($platform)
        );
        
        return $this->_db->query($sql) !== false;
    }

    /**
     * Encrypt a value using AES-256
     *
     * @param string $value Value to encrypt
     * @return string Encrypted value (base64 encoded)
     */
    private function encrypt($value)
    {
        if (empty($value)) {
            return $value;
        }
        
        $key = hash('sha256', $this->_encryptionKey, true);
        $iv = openssl_random_pseudo_bytes(16);
        
        $encrypted = openssl_encrypt($value, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        
        if ($encrypted === false) {
            return $value; // Return unencrypted if encryption fails
        }
        
        // Combine IV and encrypted data, then base64 encode
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt a value
     *
     * @param string $encryptedValue Encrypted value (base64 encoded)
     * @return string Decrypted value
     */
    private function decrypt($encryptedValue)
    {
        if (empty($encryptedValue)) {
            return $encryptedValue;
        }
        
        $data = base64_decode($encryptedValue);
        
        if ($data === false || strlen($data) < 17) {
            return $encryptedValue; // Return as-is if not valid encrypted data
        }
        
        $key = hash('sha256', $this->_encryptionKey, true);
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        
        if ($decrypted === false) {
            return $encryptedValue; // Return as-is if decryption fails
        }
        
        return $decrypted;
    }

    /**
     * Mask a credential value for display (show only last 4 chars)
     *
     * @param string $value Credential value
     * @return string Masked value
     */
    public static function maskCredential($value)
    {
        if (empty($value) || strlen($value) <= 4) {
            return '****';
        }
        
        return str_repeat('*', strlen($value) - 4) . substr($value, -4);
    }
}

?>
