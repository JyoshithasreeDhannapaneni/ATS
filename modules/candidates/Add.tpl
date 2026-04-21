<?php /* $Id: Add.tpl 3746 2007-11-28 20:28:21Z andrew $ */ ?>
<?php if ($this->isModal): ?>
    <?php TemplateUtility::printModalHeader('Candidates', array('modules/candidates/validator.js', 'js/addressParser.js', 'js/listEditor.js',  'js/candidate.js', 'js/candidateParser.js'), 'Add New Candidate to this Job Order'); ?>
<?php else: ?>
    <?php TemplateUtility::printHeader('Candidates', array('modules/candidates/validator.js', 'js/addressParser.js', 'js/listEditor.js',  'js/candidate.js', 'js/candidateParser.js')); ?>
    <?php TemplateUtility::printHeaderBlock(); ?>
    <?php TemplateUtility::printTabs($this->active, $this->subActive); ?>

    <div id="main">
        <?php TemplateUtility::printQuickSearch(); ?>

        <div id="contents">
<?php endif; ?>

<style>
.add-candidate-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 24px;
}
.add-candidate-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e5e7eb;
}
.add-candidate-header h2 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: #1f2937;
}
.add-candidate-form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
.form-section {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
}
.form-section.full-width {
    grid-column: 1 / -1;
}
.form-section-title {
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 16px 0;
    padding-bottom: 12px;
    border-bottom: 1px solid #f3f4f6;
    display: flex;
    align-items: center;
    gap: 8px;
}
.form-section-title svg {
    color: #6b7280;
}
.form-group {
    margin-bottom: 16px;
}
.form-group:last-child {
    margin-bottom: 0;
}
.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 500;
    color: #374151;
    margin-bottom: 6px;
}
.form-group label .required {
    color: #dc2626;
    margin-left: 2px;
}
.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    font-size: 14px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: #fff;
    transition: all 0.2s;
    box-sizing: border-box;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}
.form-group textarea {
    min-height: 100px;
    resize: vertical;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}
.form-actions {
    grid-column: 1 / -1;
    display: flex;
    gap: 12px;
    justify-content: flex-start;
    padding-top: 16px;
    border-top: 1px solid #e5e7eb;
    margin-top: 8px;
}
.btn {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 500;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}
.btn-primary {
    background: #2563eb;
    color: #fff;
}
.btn-primary:hover {
    background: #1d4ed8;
}
.btn-secondary {
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
}
.btn-secondary:hover {
    background: #e5e7eb;
}
.resume-upload-area {
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    padding: 24px;
    text-align: center;
    background: #f9fafb;
    transition: all 0.2s;
    position: relative;
}
.resume-upload-area:hover {
    border-color: #2563eb;
    background: #eff6ff;
}
.resume-upload-area.parsing {
    border-color: #f59e0b;
    background: #fffbeb;
}
.resume-upload-area.success {
    border-color: #10b981;
    background: #ecfdf5;
}
.resume-upload-area input[type="file"] {
    display: none;
}
.resume-upload-area label {
    cursor: pointer;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
}
.resume-upload-area .upload-icon {
    width: 40px;
    height: 40px;
    color: #9ca3af;
}
.resume-upload-area .upload-text {
    font-size: 14px;
    color: #6b7280;
}
.resume-upload-area .upload-text span {
    color: #2563eb;
    font-weight: 500;
}
.parsing-indicator {
    display: none;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 16px;
}
.parsing-indicator.show {
    display: flex;
}
.parsing-spinner {
    width: 24px;
    height: 24px;
    border: 3px solid #e5e7eb;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
.parsing-status {
    font-size: 14px;
    color: #6b7280;
}
.parsed-success {
    display: none;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 16px;
}
.parsed-success.show {
    display: flex;
}
.parsed-success svg {
    color: #10b981;
}
.parsed-success .file-name {
    font-size: 14px;
    font-weight: 500;
    color: #374151;
}
.parsed-success .parsed-info {
    font-size: 12px;
    color: #6b7280;
}
.parsed-success .change-file {
    font-size: 12px;
    color: #2563eb;
    cursor: pointer;
    text-decoration: underline;
}
.bulk-import-link {
    text-align: center;
    margin-top: 12px;
    font-size: 13px;
    color: #6b7280;
}
.bulk-import-link a {
    color: #2563eb;
    font-weight: 500;
    text-decoration: none;
}
.bulk-import-link a:hover {
    text-decoration: underline;
}
.field-autofilled {
    animation: highlight 2s ease-out;
}
@keyframes highlight {
    0% { background-color: #fef3c7; }
    100% { background-color: #fff; }
}
.duplicate-warning {
    display: none;
    background: #fef3c7;
    border: 1px solid #f59e0b;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 16px;
    font-size: 13px;
    color: #92400e;
}
.duplicate-warning a {
    color: #d97706;
    font-weight: 500;
}
.show-more-fields {
    grid-column: 1 / -1;
    text-align: center;
}
.show-more-fields button {
    background: none;
    border: none;
    color: #2563eb;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    margin: 0 auto;
    padding: 8px 16px;
}
.show-more-fields button:hover {
    text-decoration: underline;
}
.additional-fields {
    display: none;
    grid-column: 1 / -1;
}
.additional-fields.show {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
@media (max-width: 768px) {
    .add-candidate-form {
        grid-template-columns: 1fr;
    }
    .form-row {
        grid-template-columns: 1fr;
    }
    .additional-fields.show {
        grid-template-columns: 1fr;
    }
}
</style>

<script type="text/javascript">
    window.CATSUserDateFormat = '<?php echo($_SESSION['CATS']->isDateDMY() ? 'DD-MM-YY' : 'MM-DD-YY'); ?>';
    
    function toggleMoreFields() {
        var fields = document.getElementById('additionalFields');
        var btn = document.getElementById('toggleFieldsBtn');
        if (fields.classList.contains('show')) {
            fields.classList.remove('show');
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg> Show More Fields';
        } else {
            fields.classList.add('show');
            btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"/></svg> Show Less Fields';
        }
    }
    
    function handleResumeUpload() {
        var input = document.getElementById('file');
        if (!input.files || !input.files[0]) return;
        
        var file = input.files[0];
        var uploadArea = document.getElementById('resumeUploadArea');
        var uploadContent = document.getElementById('uploadContent');
        var parsingIndicator = document.getElementById('parsingIndicator');
        var parsedSuccess = document.getElementById('parsedSuccess');
        var fileNameSpan = document.getElementById('parsedFileName');
        
        // Show parsing state
        uploadArea.classList.add('parsing');
        uploadArea.classList.remove('success');
        uploadContent.style.display = 'none';
        parsingIndicator.classList.add('show');
        parsedSuccess.classList.remove('show');
        
        // Create form data for upload
        var formData = new FormData();
        formData.append('resumeFile', file);
        
        // Send to server for parsing
        fetch('<?php echo CATSUtility::getIndexName(); ?>?m=candidates&a=parseResumeAjax', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(result) {
            parsingIndicator.classList.remove('show');
            
            if (result.success && result.data) {
                // Show success state
                uploadArea.classList.remove('parsing');
                uploadArea.classList.add('success');
                parsedSuccess.classList.add('show');
                fileNameSpan.textContent = file.name;
                
                // Fill in the form fields
                fillFormFields(result.data);
                
                // Show info about what was parsed
                var parsedInfo = document.getElementById('parsedInfo');
                var fieldsFound = [];
                if (result.data.firstName || result.data.lastName) fieldsFound.push('Name');
                if (result.data.email) fieldsFound.push('Email');
                if (result.data.phone) fieldsFound.push('Phone');
                if (result.data.skills) fieldsFound.push('Skills');
                if (result.data.city || result.data.state) fieldsFound.push('Location');
                
                if (fieldsFound.length > 0) {
                    parsedInfo.textContent = 'Extracted: ' + fieldsFound.join(', ');
                } else {
                    parsedInfo.textContent = 'File uploaded (no data extracted)';
                }
            } else {
                // Show error but keep the file
                uploadArea.classList.remove('parsing');
                uploadArea.classList.add('success');
                parsedSuccess.classList.add('show');
                fileNameSpan.textContent = file.name;
                document.getElementById('parsedInfo').textContent = result.error || 'Could not parse resume data';
            }
        })
        .catch(function(error) {
            console.error('Parse error:', error);
            parsingIndicator.classList.remove('show');
            uploadArea.classList.remove('parsing');
            uploadArea.classList.add('success');
            parsedSuccess.classList.add('show');
            fileNameSpan.textContent = file.name;
            document.getElementById('parsedInfo').textContent = 'File uploaded (parsing failed)';
        });
    }
    
    function fillFormFields(data) {
        var fieldMappings = {
            'firstName': 'firstName',
            'lastName': 'lastName',
            'email': 'email1',
            'phone': 'phoneCell',
            'city': 'city',
            'state': 'state',
            'address': 'address',
            'zip': 'zip',
            'skills': 'keySkills',
            'currentEmployer': 'currentEmployer',
            'notes': 'notes'
        };
        
        var filledFields = [];
        
        for (var key in fieldMappings) {
            if (data[key] && data[key].trim() !== '') {
                var field = document.getElementById(fieldMappings[key]);
                if (field) {
                    // Only fill if field is empty or we have better data
                    if (!field.value || field.value.trim() === '') {
                        if (field.tagName === 'TEXTAREA') {
                            field.value = data[key];
                        } else {
                            field.value = data[key];
                        }
                        field.classList.add('field-autofilled');
                        filledFields.push(key);
                        
                        // Remove highlight after animation
                        setTimeout(function(f) {
                            return function() { f.classList.remove('field-autofilled'); };
                        }(field), 2000);
                    }
                }
            }
        }
        
        // If we filled location fields, show the additional fields section
        if (data.city || data.state || data.address || data.zip || data.currentEmployer) {
            var additionalFields = document.getElementById('additionalFields');
            if (!additionalFields.classList.contains('show')) {
                toggleMoreFields();
            }
        }
        
        // Check for duplicates if email was filled
        if (data.email && typeof checkEmailAlreadyInSystem === 'function') {
            checkEmailAlreadyInSystem(data.email);
        }
        
        // Set the temp file path for attachment when form is submitted
        if (data.tempFile) {
            var tempFileField = document.getElementById('documentTempFile');
            if (tempFileField) {
                tempFileField.value = data.tempFile;
            }
        }
        
        return filledFields;
    }
    
    function resetUploadArea() {
        var input = document.getElementById('file');
        var uploadArea = document.getElementById('resumeUploadArea');
        var uploadContent = document.getElementById('uploadContent');
        var parsingIndicator = document.getElementById('parsingIndicator');
        var parsedSuccess = document.getElementById('parsedSuccess');
        
        input.value = '';
        uploadArea.classList.remove('parsing', 'success');
        uploadContent.style.display = 'flex';
        parsingIndicator.classList.remove('show');
        parsedSuccess.classList.remove('show');
    }
</script>

<div class="add-candidate-container">
    <div class="add-candidate-header">
        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
            <circle cx="12" cy="7" r="4"/>
            <line x1="12" y1="11" x2="12" y2="17"/>
            <line x1="9" y1="14" x2="15" y2="14"/>
        </svg>
        <h2>Add New Candidate</h2>
    </div>

    <div id="candidateAlreadyInSystemTable" class="duplicate-warning">
        This profile may already be in the system. Possible duplicate: 
        <a href="javascript:void(0);" onclick="window.open('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates&amp;a=show&amp;candidateID='+candidateIsAlreadyInSystemID);">
            <span id="candidateAlreadyInSystemName"></span>
        </a>
    </div>

    <?php if ($this->isModal): ?>
        <?php $URI = CATSUtility::getIndexName() . '?m=joborders&amp;a=addCandidateModal&jobOrderID=' . $this->jobOrderID; ?>
    <?php else: ?>
        <?php $URI = CATSUtility::getIndexName() . '?m=candidates&amp;a=add'; ?>
    <?php endif; ?>

    <form name="addCandidateForm" id="addCandidateForm" enctype="multipart/form-data" action="<?php echo($URI); ?>" method="post" onsubmit="return (checkAddForm(document.addCandidateForm) && onSubmitEmailInSystem() && onSubmitPhoneInSystem());" autocomplete="off">
        <?php if ($this->isModal): ?>
            <input type="hidden" name="jobOrderID" id="jobOrderID" value="<?php echo($this->jobOrderID); ?>" />
        <?php endif; ?>
        <input type="hidden" name="postback" id="postback" value="postback" />
        
        <?php if ($this->isParsingEnabled): ?>
            <input type="hidden" name="loadDocument" id="loadDocument" value="" />
            <input type="hidden" name="parseDocument" id="parseDocument" value="" />
            <input type="hidden" name="documentTempFile" id="documentTempFile" value="<?php echo (isset($this->preassignedFields['documentTempFile']) ? $this->preassignedFields['documentTempFile'] : ''); ?>" />
        <?php endif; ?>

        <div class="add-candidate-form">
            <!-- Basic Information -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    Basic Information
                </h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstName">First Name <span class="required">*</span></label>
                        <input type="text" name="firstName" id="firstName" value="<?php if(isset($this->preassignedFields['firstName'])) $this->_($this->preassignedFields['firstName']); ?>" required />
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name <span class="required">*</span></label>
                        <input type="text" name="lastName" id="lastName" value="<?php if(isset($this->preassignedFields['lastName'])) $this->_($this->preassignedFields['lastName']); ?>" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="email1">Email</label>
                    <input type="email" name="email1" id="email1" value="<?php if(isset($this->preassignedFields['email'])) $this->_($this->preassignedFields['email']); elseif (isset($this->preassignedFields['email1'])) $this->_($this->preassignedFields['email1']); ?>" onchange="checkEmailAlreadyInSystem(this.value);" />
                </div>
                <div class="form-group">
                    <label for="phoneCell">Phone</label>
                    <input type="tel" name="phoneCell" id="phoneCell" value="<?php if (isset($this->preassignedFields['phoneCell'])) $this->_($this->preassignedFields['phoneCell']); ?>" onchange="checkPhoneAlreadyInSystem(this.value);" />
                </div>
            </div>

            <?php if (!$this->isModal): ?>
            <!-- Job Order Pipeline -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4"/>
                        <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1"/>
                        <path d="M3 21h18"/>
                        <path d="M5 21V12"/>
                        <path d="M19 21V12"/>
                    </svg>
                    Pipeline Assignment
                </h3>
                <div class="form-group">
                    <label for="jobOrderID">Add to Job Order Pipeline (Optional)</label>
                    <select name="jobOrderID" id="jobOrderID">
                        <option value="">-- Select Job Order --</option>
                        <?php foreach ($jobOrders as $jobOrder): ?>
                            <option value="<?php echo $jobOrder['jobOrderID']; ?>"><?php echo htmlspecialchars($jobOrder['title']); ?> (<?php echo htmlspecialchars($jobOrder['companyName']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php endif; ?>

            <!-- Resume Upload -->
            <div class="form-section">
                <h3 class="form-section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Resume
                    <span style="font-size: 11px; font-weight: 400; color: #6b7280; margin-left: auto;">Auto-fills form fields</span>
                </h3>
                <div class="resume-upload-area" id="resumeUploadArea">
                    <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                    <input type="file" id="file" name="file" onchange="handleResumeUpload();" accept=".pdf,.doc,.docx,.txt,.rtf" />
                    
                    <!-- Default upload state -->
                    <label for="file" id="uploadContent" style="display: flex; flex-direction: column; align-items: center; gap: 8px; cursor: pointer;">
                        <svg class="upload-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                        <span class="upload-text"><span>Click to upload</span> or drag and drop</span>
                        <span style="font-size: 12px; color: #9ca3af;">PDF, DOC, DOCX, TXT (max 10MB)</span>
                    </label>
                    
                    <!-- Parsing indicator -->
                    <div class="parsing-indicator" id="parsingIndicator">
                        <div class="parsing-spinner"></div>
                        <span class="parsing-status">Parsing resume and extracting data...</span>
                    </div>
                    
                    <!-- Success state -->
                    <div class="parsed-success" id="parsedSuccess">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <polyline points="9 15 11 17 15 13"/>
                        </svg>
                        <span class="file-name" id="parsedFileName"></span>
                        <span class="parsed-info" id="parsedInfo">Data extracted and filled</span>
                        <span class="change-file" onclick="resetUploadArea();">Choose different file</span>
                    </div>
                </div>
                <div class="bulk-import-link">
                    Need to upload multiple resumes? <a href="<?php echo CATSUtility::getIndexName(); ?>?m=import&a=bulkImport">Use Bulk Import</a>
                </div>
            </div>

            <!-- Skills & Source -->
            <div class="form-section full-width">
                <h3 class="form-section-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Skills & Details
                </h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="keySkills">Key Skills</label>
                        <input type="text" name="keySkills" id="keySkills" placeholder="e.g., Java, Python, React, SQL" value="<?php if (isset($this->preassignedFields['keySkills'])) $this->_($this->preassignedFields['keySkills']); ?>" />
                    </div>
                    <div class="form-group">
                        <label for="sourceSelect">Source</label>
                        <select id="sourceSelect" name="source">
                            <option value="(none)" <?php if (!isset($this->preassignedFields['source'])): ?>selected<?php endif; ?>>(None)</option>
                            <?php if (isset($this->preassignedFields['source'])): ?>
                                <option value="<?php $this->_($this->preassignedFields['source']); ?>" selected><?php $this->_($this->preassignedFields['source']); ?></option>
                            <?php endif; ?>
                            <?php foreach ($this->sourcesRS AS $index => $source): ?>
                                <option value="<?php $this->_($source['name']); ?>"><?php $this->_($source['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" id="sourceCSV" name="sourceCSV" value="<?php $this->_($this->sourcesString); ?>" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes" placeholder="Add any additional notes about this candidate..."><?php if (isset($this->preassignedFields['notes'])) $this->_($this->preassignedFields['notes']); ?></textarea>
                </div>
            </div>

            <!-- Show More Fields Toggle -->
            <div class="show-more-fields">
                <button type="button" id="toggleFieldsBtn" onclick="toggleMoreFields();">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14"/>
                    </svg>
                    Show More Fields
                </button>
            </div>

            <!-- Additional Fields (Hidden by default) -->
            <div id="additionalFields" class="additional-fields">
                <div class="form-section">
                    <h3 class="form-section-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        Location
                    </h3>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <textarea name="address" id="address" rows="2"><?php if(isset($this->preassignedFields['address'])) $this->_($this->preassignedFields['address']); ?></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" value="<?php if(isset($this->preassignedFields['city'])) $this->_($this->preassignedFields['city']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" name="state" id="state" value="<?php if(isset($this->preassignedFields['state'])) $this->_($this->preassignedFields['state']); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="zip">Postal Code</label>
                        <input type="text" name="zip" id="zip" value="<?php if(isset($this->preassignedFields['zip'])) $this->_($this->preassignedFields['zip']); ?>" />
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                        Employment
                    </h3>
                    <div class="form-group">
                        <label for="currentEmployer">Current Employer</label>
                        <input type="text" name="currentEmployer" id="currentEmployer" value="<?php if (isset($this->preassignedFields['currentEmployer'])) $this->_($this->preassignedFields['currentEmployer']); ?>" />
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="currentPay">Current Pay</label>
                            <input type="text" name="currentPay" id="currentPay" value="<?php if (isset($this->preassignedFields['currentPay'])) $this->_($this->preassignedFields['currentPay']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="desiredPay">Desired Pay</label>
                            <input type="text" name="desiredPay" id="desiredPay" value="<?php if (isset($this->preassignedFields['desiredPay'])) $this->_($this->preassignedFields['desiredPay']); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="canRelocate">
                            <input type="checkbox" id="canRelocate" name="canRelocate" value="1" <?php if (isset($this->preassignedFields['canRelocate']) && $this->preassignedFields['canRelocate'] == '1') echo ' checked'; ?> style="width: auto; margin-right: 8px;" />
                            Can Relocate
                        </label>
                    </div>
                </div>

                <div class="form-section">
                    <h3 class="form-section-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        Additional Contact
                    </h3>
                    <div class="form-group">
                        <label for="email2">Secondary Email</label>
                        <input type="email" name="email2" id="email2" value="<?php if (isset($this->preassignedFields['email2'])) $this->_($this->preassignedFields['email2']); ?>" />
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="phoneHome">Home Phone</label>
                            <input type="tel" name="phoneHome" id="phoneHome" value="<?php if (isset($this->preassignedFields['phoneHome'])) $this->_($this->preassignedFields['phoneHome']); ?>" />
                        </div>
                        <div class="form-group">
                            <label for="phoneWork">Work Phone</label>
                            <input type="tel" name="phoneWork" id="phoneWork" value="<?php if (isset($this->preassignedFields['phoneWork'])) $this->_($this->preassignedFields['phoneWork']); ?>" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="webSite">Website / LinkedIn</label>
                        <input type="text" name="webSite" id="webSite" value="<?php if (isset($this->preassignedFields['webSite'])) $this->_($this->preassignedFields['webSite']); ?>" />
                    </div>
                </div>

                <!-- Extra Fields -->
                <?php if (count($this->extraFieldRS) > 0): ?>
                <div class="form-section">
                    <h3 class="form-section-title">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        Additional Information
                    </h3>
                    <?php for ($i = 0; $i < count($this->extraFieldRS); $i++): ?>
                        <div class="form-group">
                            <label><?php $this->_($this->extraFieldRS[$i]['fieldName']); ?></label>
                            <?php echo($this->extraFieldRS[$i]['addHTML']); ?>
                        </div>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Hidden fields for compatibility -->
            <input type="hidden" name="middleName" id="middleName" value="<?php if(isset($this->preassignedFields['middleName'])) $this->_($this->preassignedFields['middleName']); ?>" />
            <input type="hidden" name="bestTimeToCall" id="bestTimeToCall" value="" />
            <input type="hidden" name="dateAvailable" id="dateAvailable" value="" />

            <!-- Form Actions -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Add Candidate</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
                <?php if ($this->isModal): ?>
                    <button type="button" class="btn btn-secondary" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=joborders&amp;a=considerCandidateSearch&amp;jobOrderID=<?php echo($this->jobOrderID); ?>');">Back to Search</button>
                <?php else: ?>
                    <button type="button" class="btn btn-secondary" onclick="javascript:goToURL('<?php echo(CATSUtility::getIndexName()); ?>?m=candidates');">Back to Candidates</button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    document.addCandidateForm.firstName.focus();
    <?php if(isset($this->preassignedFields['email']) || isset($this->preassignedFields['email1'])): ?>
        checkEmailAlreadyInSystem(urlDecode("<?php if(isset($this->preassignedFields['email'])) echo(urlencode($this->preassignedFields['email'])); else if(isset($this->preassignedFields['email1'])) echo(urlencode($this->preassignedFields['email1'])); ?>"));
    <?php endif; ?>
</script>

<?php if ($this->isModal): ?>
    </body>
</html>
<?php else: ?>
        </div>
    </div>
<?php TemplateUtility::printFooter(); ?>
<?php endif; ?>
