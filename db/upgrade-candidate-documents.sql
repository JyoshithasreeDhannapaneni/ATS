-- Candidate Document Upload Portal
-- Creates tables for upload tokens and document tracking

CREATE TABLE IF NOT EXISTS candidate_upload_token (
    token_id INT(11) NOT NULL AUTO_INCREMENT,
    candidate_id INT(11) NOT NULL,
    site_id INT(11) NOT NULL DEFAULT 1,
    token VARCHAR(64) NOT NULL,
    created_by INT(11) NOT NULL,
    created_date DATETIME NOT NULL,
    expires_date DATETIME NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    max_uploads INT(11) NOT NULL DEFAULT 20,
    upload_count INT(11) NOT NULL DEFAULT 0,
    PRIMARY KEY (token_id),
    UNIQUE KEY idx_token (token),
    KEY idx_candidate (candidate_id),
    KEY idx_active_expires (is_active, expires_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS candidate_document (
    document_id INT(11) NOT NULL AUTO_INCREMENT,
    candidate_id INT(11) NOT NULL,
    site_id INT(11) NOT NULL DEFAULT 1,
    token_id INT(11) DEFAULT NULL,
    document_type VARCHAR(50) NOT NULL DEFAULT 'other',
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    directory_name VARCHAR(255) NOT NULL,
    file_size_kb INT(11) NOT NULL DEFAULT 0,
    content_type VARCHAR(100) NOT NULL DEFAULT 'application/octet-stream',
    uploaded_date DATETIME NOT NULL,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    notes TEXT,
    PRIMARY KEY (document_id),
    KEY idx_candidate (candidate_id),
    KEY idx_token (token_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
