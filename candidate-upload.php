<?php
/**
 * Public Candidate Document Upload Page
 * No login required — accessed via a unique token link.
 */
include_once('./constants.php');
include_once('./config.php');
include_once('./lib/DatabaseConnection.php');
include_once('./lib/CandidateDocuments.php');

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$valid = false;
$candidateName = '';
$tokenData = null;
$remaining = 0;

if (!empty($token))
{
    $docs = new CandidateDocuments(1);
    $tokenData = $docs->validateToken($token);
    if ($tokenData)
    {
        $valid = true;
        $candidateName = htmlspecialchars($tokenData['firstName'] . ' ' . $tokenData['lastName']);
        $remaining = intval($tokenData['max_uploads']) - intval($tokenData['upload_count']);
    }
}

$documentTypes = CandidateDocuments::getDocumentTypes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload - Neutara ATS</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: #f0f2f5;
            color: #1f2937;
            min-height: 100vh;
        }
        .upload-header {
            background: #fff;
            border-bottom: 1px solid #e5e7eb;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .upload-header h1 {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
        }
        .upload-header .logo-dot {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, #2563eb, #7c3aed);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 800; font-size: 16px;
        }
        .container {
            max-width: 640px;
            margin: 32px auto;
            padding: 0 16px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-radius: 12px;
            padding: 20px 24px;
            margin-bottom: 24px;
            text-align: center;
        }
        .welcome-box h2 {
            font-size: 20px;
            font-weight: 700;
            color: #1e40af;
            margin-bottom: 4px;
        }
        .welcome-box p {
            font-size: 13px;
            color: #3b82f6;
        }
        .upload-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 16px;
        }
        .upload-card h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-group select,
        .form-group input[type="file"] {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            background: #fff;
        }
        .drop-zone {
            border: 2px dashed #d1d5db;
            border-radius: 10px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #fafbfc;
        }
        .drop-zone:hover, .drop-zone.drag-over {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .drop-zone svg {
            display: block;
            margin: 0 auto 10px;
        }
        .drop-zone p {
            font-size: 13px;
            color: #6b7280;
        }
        .drop-zone .browse-link {
            color: #2563eb;
            font-weight: 600;
            text-decoration: underline;
            cursor: pointer;
        }
        .drop-zone .file-hint {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 6px;
        }
        .btn-upload {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            font-family: inherit;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-upload:hover { background: #1d4ed8; }
        .btn-upload:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        .uploaded-list {
            list-style: none;
        }
        .uploaded-list li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 8px;
            font-size: 13px;
            background: #f9fafb;
        }
        .uploaded-list li .file-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .uploaded-list li .file-icon {
            width: 32px; height: 32px;
            background: #eff6ff;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
        }
        .uploaded-list li .file-name {
            font-weight: 600;
            color: #1f2937;
        }
        .uploaded-list li .file-type {
            font-size: 11px;
            color: #6b7280;
        }
        .status-badge {
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .status-badge.success {
            background: #ecfdf5;
            color: #065f46;
        }
        .error-page, .expired-page {
            text-align: center;
            padding: 80px 20px;
        }
        .error-page svg, .expired-page svg {
            display: block;
            margin: 0 auto 16px;
        }
        .error-page h2, .expired-page h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .error-page p, .expired-page p {
            font-size: 14px;
            color: #6b7280;
        }
        .progress-bar {
            width: 100%;
            height: 4px;
            background: #e5e7eb;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
            display: none;
        }
        .progress-bar .fill {
            height: 100%;
            background: #2563eb;
            width: 0%;
            transition: width 0.3s;
            border-radius: 4px;
        }
        .upload-footer {
            text-align: center;
            padding: 24px;
            font-size: 12px;
            color: #9ca3af;
        }
        .selected-file-name {
            margin-top: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
            display: none;
        }
    </style>
</head>
<body>
    <div class="upload-header">
        <div class="logo-dot">N</div>
        <h1>Neutara ATS - Document Upload</h1>
    </div>

    <div class="container">
    <?php if (!$valid): ?>
        <div class="upload-card">
            <div class="expired-page">
                <svg width="48" height="48" fill="none" stroke="#ef4444" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                <h2>Link Expired or Invalid</h2>
                <p>This document upload link is no longer valid. Please contact your recruiter for a new link.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="welcome-box">
            <h2>Welcome, <?php echo $candidateName; ?></h2>
            <p>Please upload your documents below. You can upload up to <?php echo $remaining; ?> more files.</p>
        </div>

        <div class="upload-card">
            <h3>
                <svg width="20" height="20" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                Upload Document
            </h3>
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>" />

                <div class="form-group">
                    <label>Document Type</label>
                    <select name="documentType" id="documentType" required>
                        <option value="">-- Select document type --</option>
                        <?php foreach ($documentTypes as $key => $label): ?>
                            <option value="<?php echo $key; ?>"><?php echo htmlspecialchars($label); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>File</label>
                    <div class="drop-zone" id="dropZone" onclick="document.getElementById('fileInput').click();">
                        <svg width="36" height="36" fill="none" stroke="#9ca3af" stroke-width="1.5" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <p>Drag & drop your file here, or <span class="browse-link">browse</span></p>
                        <p class="file-hint">PDF, DOC, JPG, PNG, XLS up to 10MB</p>
                    </div>
                    <div class="selected-file-name" id="selectedFileName"></div>
                    <input type="file" id="fileInput" name="document" style="display: none;"
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif,.bmp,.tif,.tiff,.xls,.xlsx,.txt,.rtf,.odt,.ods,.zip,.rar" />
                </div>

                <div class="progress-bar" id="progressBar">
                    <div class="fill" id="progressFill"></div>
                </div>

                <button type="submit" class="btn-upload" id="uploadBtn" disabled>
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload Document
                </button>
            </form>
        </div>

        <div class="upload-card">
            <h3>
                <svg width="20" height="20" fill="none" stroke="#059669" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Uploaded Documents
            </h3>
            <ul class="uploaded-list" id="uploadedList">
                <li id="emptyState" style="justify-content: center; color: #9ca3af; border: none; background: none;">
                    No documents uploaded yet. Use the form above to start.
                </li>
            </ul>
        </div>

        <div class="upload-footer">
            Powered by Neutara ATS Tool
        </div>
    <?php endif; ?>
    </div>

    <?php if ($valid): ?>
    <script>
        var dropZone = document.getElementById('dropZone');
        var fileInput = document.getElementById('fileInput');
        var uploadBtn = document.getElementById('uploadBtn');
        var uploadForm = document.getElementById('uploadForm');
        var progressBar = document.getElementById('progressBar');
        var progressFill = document.getElementById('progressFill');
        var selectedFileName = document.getElementById('selectedFileName');
        var uploadedList = document.getElementById('uploadedList');
        var emptyState = document.getElementById('emptyState');

        ['dragenter', 'dragover'].forEach(function(evt) {
            dropZone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropZone.classList.add('drag-over');
            });
        });
        ['dragleave', 'drop'].forEach(function(evt) {
            dropZone.addEventListener(evt, function(e) {
                e.preventDefault();
                dropZone.classList.remove('drag-over');
            });
        });
        dropZone.addEventListener('drop', function(e) {
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                onFileSelected();
            }
        });

        fileInput.addEventListener('change', onFileSelected);

        function onFileSelected() {
            if (fileInput.files.length > 0) {
                selectedFileName.textContent = fileInput.files[0].name +
                    ' (' + (fileInput.files[0].size / 1024).toFixed(1) + ' KB)';
                selectedFileName.style.display = 'block';
                uploadBtn.disabled = false;
            }
        }

        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();

            var docType = document.getElementById('documentType').value;
            if (!docType) { alert('Please select a document type.'); return; }
            if (fileInput.files.length === 0) { alert('Please select a file.'); return; }

            var formData = new FormData();
            formData.append('token', '<?php echo htmlspecialchars($token); ?>');
            formData.append('documentType', docType);
            formData.append('document', fileInput.files[0]);

            uploadBtn.disabled = true;
            uploadBtn.innerHTML = 'Uploading...';
            progressBar.style.display = 'block';
            progressFill.style.width = '0%';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'ajax/uploadDocument.php', true);

            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    var pct = Math.round((e.loaded / e.total) * 100);
                    progressFill.style.width = pct + '%';
                }
            });

            xhr.onload = function() {
                progressFill.style.width = '100%';
                try {
                    var resp = JSON.parse(xhr.responseText);
                    if (resp.success) {
                        addUploadedFile(resp.filename, resp.type, resp.size);
                        fileInput.value = '';
                        selectedFileName.style.display = 'none';
                        document.getElementById('documentType').value = '';
                        uploadBtn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg> Upload Document';
                        uploadBtn.disabled = true;
                    } else {
                        alert('Upload failed: ' + resp.error);
                        uploadBtn.innerHTML = 'Upload Document';
                        uploadBtn.disabled = false;
                    }
                } catch(ex) {
                    alert('Upload failed. Please try again.');
                    uploadBtn.innerHTML = 'Upload Document';
                    uploadBtn.disabled = false;
                }
                setTimeout(function() { progressBar.style.display = 'none'; }, 1000);
            };

            xhr.onerror = function() {
                alert('Network error. Please check your connection.');
                uploadBtn.innerHTML = 'Upload Document';
                uploadBtn.disabled = false;
                progressBar.style.display = 'none';
            };

            xhr.send(formData);
        });

        function addUploadedFile(name, type, size) {
            if (emptyState) emptyState.style.display = 'none';
            var li = document.createElement('li');
            li.innerHTML = '<div class="file-info">' +
                '<div class="file-icon"><svg width="16" height="16" fill="none" stroke="#2563eb" stroke-width="2" viewBox="0 0 24 24"><path d="M14.5 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg></div>' +
                '<div><div class="file-name">' + name + '</div>' +
                '<div class="file-type">' + type + ' &middot; ' + size + '</div></div></div>' +
                '<span class="status-badge success">Uploaded</span>';
            uploadedList.prepend(li);
        }
    </script>
    <?php endif; ?>
</body>
</html>
