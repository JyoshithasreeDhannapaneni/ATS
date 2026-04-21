<?php /* Bulk Import - Modern UI */ ?>
<?php TemplateUtility::printHeader('Bulk Import', array()); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, '', 'settings'); ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

.bulk-import-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 24px;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.bulk-import-header {
    margin-bottom: 32px;
}

.bulk-import-header h1 {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 12px;
}

.bulk-import-header p {
    color: #6b7280;
    font-size: 14px;
    margin: 0;
}

.import-tabs {
    display: flex;
    gap: 0;
    margin-bottom: 24px;
    border-bottom: 2px solid #e5e7eb;
}

.import-tab {
    padding: 14px 24px;
    font-size: 14px;
    font-weight: 600;
    color: #6b7280;
    background: none;
    border: none;
    cursor: pointer;
    position: relative;
    transition: all 0.2s;
}

.import-tab:hover {
    color: #2563eb;
    background: #f8fafc;
}

.import-tab.active {
    color: #2563eb;
}

.import-tab.active::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: #2563eb;
}

.import-tab svg {
    margin-right: 8px;
    vertical-align: middle;
}

.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Upload Zone */
.upload-zone {
    border: 2px dashed #d1d5db;
    border-radius: 12px;
    padding: 48px 24px;
    text-align: center;
    background: #fafbfc;
    transition: all 0.3s ease;
    cursor: pointer;
    margin-bottom: 24px;
}

.upload-zone:hover,
.upload-zone.dragover {
    border-color: #2563eb;
    background: #eff6ff;
}

.upload-zone.dragover {
    transform: scale(1.01);
}

.upload-zone-icon {
    width: 64px;
    height: 64px;
    margin: 0 auto 16px;
    background: #e0e7ff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-zone-icon svg {
    width: 32px;
    height: 32px;
    color: #2563eb;
}

.upload-zone h3 {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 8px 0;
}

.upload-zone p {
    font-size: 13px;
    color: #6b7280;
    margin: 0 0 16px 0;
}

.upload-zone .browse-btn {
    display: inline-block;
    padding: 10px 24px;
    background: #2563eb;
    color: #fff;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.upload-zone .browse-btn:hover {
    background: #1d4ed8;
}

.upload-zone .file-types {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 16px;
}

/* File List */
.file-list {
    margin-bottom: 24px;
}

.file-list-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.file-list-header h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.file-list-header .clear-all {
    font-size: 12px;
    color: #dc2626;
    cursor: pointer;
    background: none;
    border: none;
    font-weight: 500;
}

.file-list-header .clear-all:hover {
    text-decoration: underline;
}

.file-item {
    display: flex;
    align-items: center;
    padding: 12px 16px;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    margin-bottom: 8px;
    transition: all 0.2s;
}

.file-item:hover {
    border-color: #2563eb;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.1);
}

.file-item-icon {
    width: 40px;
    height: 40px;
    background: #eff6ff;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    flex-shrink: 0;
}

.file-item-icon svg {
    width: 20px;
    height: 20px;
    color: #2563eb;
}

.file-item-icon.csv {
    background: #ecfdf5;
}

.file-item-icon.csv svg {
    color: #059669;
}

.file-item-info {
    flex: 1;
    min-width: 0;
}

.file-item-name {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.file-item-size {
    font-size: 11px;
    color: #9ca3af;
}

.file-item-status {
    margin-left: 12px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
}

.file-item-status.pending {
    background: #fef3c7;
    color: #d97706;
}

.file-item-status.success {
    background: #d1fae5;
    color: #059669;
}

.file-item-status.error {
    background: #fee2e2;
    color: #dc2626;
}

.file-item-remove {
    margin-left: 12px;
    width: 28px;
    height: 28px;
    border: none;
    background: #f3f4f6;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.file-item-remove:hover {
    background: #fee2e2;
    color: #dc2626;
}

/* CSV Template Section */
.template-section {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 24px;
}

.template-section h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 8px 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.template-section p {
    font-size: 13px;
    color: #6b7280;
    margin: 0 0 16px 0;
}

.template-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: #fff;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    cursor: pointer;
    transition: all 0.2s;
}

.template-btn:hover {
    border-color: #2563eb;
    color: #2563eb;
    background: #eff6ff;
}

/* Import Button */
.import-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 24px;
    border-top: 1px solid #e5e7eb;
}

.import-summary {
    font-size: 13px;
    color: #6b7280;
}

.import-summary strong {
    color: #1f2937;
}

.import-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.import-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.import-btn:disabled {
    background: #d1d5db;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Progress Section */
.import-progress {
    display: none;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.import-progress.active {
    display: block;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.progress-header h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.progress-header .progress-percent {
    font-size: 14px;
    font-weight: 700;
    color: #2563eb;
}

.progress-bar {
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 12px;
}

.progress-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
    border-radius: 4px;
    transition: width 0.3s ease;
    width: 0%;
}

.progress-status {
    font-size: 12px;
    color: #6b7280;
}

/* Results Section */
.import-results {
    display: none;
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 24px;
    margin-bottom: 24px;
}

.import-results.active {
    display: block;
}

.results-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.results-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.results-icon.success {
    background: #d1fae5;
    color: #059669;
}

.results-icon.partial {
    background: #fef3c7;
    color: #d97706;
}

.results-header h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
}

.results-stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}

.stat-card {
    background: #f8fafc;
    border-radius: 8px;
    padding: 16px;
    text-align: center;
}

.stat-card .stat-value {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
}

.stat-card .stat-label {
    font-size: 12px;
    color: #6b7280;
    margin-top: 4px;
}

.stat-card.success .stat-value { color: #059669; }
.stat-card.error .stat-value { color: #dc2626; }
.stat-card.skipped .stat-value { color: #d97706; }

.results-actions {
    display: flex;
    gap: 12px;
}

.results-btn {
    padding: 10px 20px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.results-btn.primary {
    background: #2563eb;
    color: #fff;
    border: none;
}

.results-btn.primary:hover {
    background: #1d4ed8;
}

.results-btn.secondary {
    background: #fff;
    color: #374151;
    border: 1px solid #d1d5db;
}

.results-btn.secondary:hover {
    border-color: #2563eb;
    color: #2563eb;
}

/* Preview Table */
.preview-section {
    display: none;
    margin-bottom: 24px;
}

.preview-section.active {
    display: block;
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.preview-header h4 {
    font-size: 14px;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
}

.preview-table-wrapper {
    overflow-x: auto;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.preview-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}

.preview-table th {
    background: #f8fafc;
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    font-size: 10px;
    letter-spacing: 0.05em;
    border-bottom: 1px solid #e5e7eb;
    white-space: nowrap;
}

.preview-table td {
    padding: 10px 12px;
    border-bottom: 1px solid #f3f4f6;
    color: #374151;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.preview-table tr:hover td {
    background: #f8fafc;
}

.preview-table .row-status {
    padding: 3px 8px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 600;
}

.preview-table .row-status.valid {
    background: #d1fae5;
    color: #059669;
}

.preview-table .row-status.invalid {
    background: #fee2e2;
    color: #dc2626;
}

.preview-table .row-status.duplicate {
    background: #fef3c7;
    color: #d97706;
}

/* Hidden file inputs */
.hidden-input {
    display: none;
}
</style>

<div id="main">
    <?php TemplateUtility::printQuickSearch(); ?>
    <div id="contents">
        <div class="bulk-import-container">
            <div class="bulk-import-header">
                <h1>
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Bulk Import Candidates
                </h1>
                <p>Import multiple candidates at once using CSV/Excel files or by uploading multiple resumes</p>
            </div>

            <!-- Import Type Tabs -->
            <div class="import-tabs">
                <button class="import-tab active" data-tab="csv" onclick="switchTab('csv')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                    </svg>
                    CSV / Excel Import
                </button>
                <button class="import-tab" data-tab="resume" onclick="switchTab('resume')">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    Resume Upload
                </button>
            </div>

            <!-- CSV Import Tab -->
            <div id="csvTab" class="tab-content active">
                <!-- Template Download Section -->
                <div class="template-section">
                    <h4>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download Template
                    </h4>
                    <p>Download our CSV template with all the required columns, fill in your candidate data, then upload it below.</p>
                    <button class="template-btn" onclick="downloadTemplate()">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7 10 12 15 17 10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Download CSV Template
                    </button>
                </div>

                <!-- CSV Upload Zone -->
                <div class="upload-zone" id="csvUploadZone" onclick="document.getElementById('csvFileInput').click()">
                    <input type="file" id="csvFileInput" class="hidden-input" accept=".csv,.xlsx,.xls" onchange="handleCSVFiles(this.files)">
                    <div class="upload-zone-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="17 8 12 3 7 8"/>
                            <line x1="12" y1="3" x2="12" y2="15"/>
                        </svg>
                    </div>
                    <h3>Upload CSV or Excel File</h3>
                    <p>Drag and drop your file here, or click to browse</p>
                    <span class="browse-btn">Browse Files</span>
                    <div class="file-types">Supported formats: CSV, XLSX, XLS</div>
                </div>

                <!-- CSV Preview -->
                <div id="csvPreview" class="preview-section">
                    <div class="preview-header">
                        <h4>Preview (<span id="csvRowCount">0</span> candidates)</h4>
                    </div>
                    <div class="preview-table-wrapper">
                        <table class="preview-table" id="csvPreviewTable">
                            <thead>
                                <tr id="csvPreviewHeader"></tr>
                            </thead>
                            <tbody id="csvPreviewBody"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Resume Upload Tab -->
            <div id="resumeTab" class="tab-content">
                <!-- Resume Upload Zone -->
                <div class="upload-zone" id="resumeUploadZone" onclick="document.getElementById('resumeFileInput').click()">
                    <input type="file" id="resumeFileInput" class="hidden-input" accept=".pdf,.doc,.docx,.txt,.rtf" multiple onchange="handleResumeFiles(this.files)">
                    <div class="upload-zone-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                            <polyline points="14 2 14 8 20 8"/>
                        </svg>
                    </div>
                    <h3>Upload Resume Files</h3>
                    <p>Drag and drop multiple resume files here, or click to browse</p>
                    <span class="browse-btn">Browse Files</span>
                    <div class="file-types">Supported formats: PDF, DOC, DOCX, TXT, RTF (Max 50 files at once)</div>
                </div>

                <!-- Resume File List -->
                <div id="resumeFileList" class="file-list" style="display: none;">
                    <div class="file-list-header">
                        <h4>Selected Files (<span id="resumeFileCount">0</span>)</h4>
                        <button class="clear-all" onclick="clearResumeFiles()">Clear All</button>
                    </div>
                    <div id="resumeFileItems"></div>
                </div>
            </div>

            <!-- Progress Section -->
            <div id="importProgress" class="import-progress">
                <div class="progress-header">
                    <h4>Importing Candidates...</h4>
                    <span class="progress-percent" id="progressPercent">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar-fill" id="progressBarFill"></div>
                </div>
                <div class="progress-status" id="progressStatus">Preparing import...</div>
            </div>

            <!-- Results Section -->
            <div id="importResults" class="import-results">
                <div class="results-header">
                    <div class="results-icon success" id="resultsIcon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <h3 id="resultsTitle">Import Complete!</h3>
                </div>
                <div class="results-stats">
                    <div class="stat-card success">
                        <div class="stat-value" id="successCount">0</div>
                        <div class="stat-label">Imported Successfully</div>
                    </div>
                    <div class="stat-card error">
                        <div class="stat-value" id="errorCount">0</div>
                        <div class="stat-label">Failed</div>
                    </div>
                    <div class="stat-card skipped">
                        <div class="stat-value" id="skippedCount">0</div>
                        <div class="stat-label">Skipped (Duplicates)</div>
                    </div>
                </div>
                <div class="results-actions">
                    <button class="results-btn primary" onclick="window.location.href='<?php echo CATSUtility::getIndexName(); ?>?m=candidates'">View Candidates</button>
                    <button class="results-btn secondary" onclick="resetImport()">Import More</button>
                </div>
            </div>

            <!-- Import Actions -->
            <div class="import-actions" id="importActions">
                <div class="import-summary">
                    <span id="importSummary">No files selected</span>
                </div>
                <div class="job-order-selector">
                    <label for="jobOrderID">Add to Job Order Pipeline (Optional):</label>
                    <select name="jobOrderID" id="jobOrderID">
                        <option value="">-- Select Job Order --</option>
                        <?php foreach ($jobOrders as $jobOrder): ?>
                            <option value="<?php echo $jobOrder['jobOrderID']; ?>"><?php echo htmlspecialchars($jobOrder['title']); ?> (<?php echo htmlspecialchars($jobOrder['companyName']); ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button class="import-btn" id="importBtn" onclick="startImport()" disabled>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="17 8 12 3 7 8"/>
                        <line x1="12" y1="3" x2="12" y2="15"/>
                    </svg>
                    Start Import
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// State
let currentTab = 'csv';
let csvData = [];
let resumeFiles = [];

// Tab switching
function switchTab(tab) {
    currentTab = tab;
    document.querySelectorAll('.import-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelector(`.import-tab[data-tab="${tab}"]`).classList.add('active');
    document.getElementById(tab + 'Tab').classList.add('active');
    updateSummary();
}

// Drag and drop for CSV
const csvZone = document.getElementById('csvUploadZone');
csvZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    csvZone.classList.add('dragover');
});
csvZone.addEventListener('dragleave', () => {
    csvZone.classList.remove('dragover');
});
csvZone.addEventListener('drop', (e) => {
    e.preventDefault();
    csvZone.classList.remove('dragover');
    handleCSVFiles(e.dataTransfer.files);
});

// Drag and drop for Resumes
const resumeZone = document.getElementById('resumeUploadZone');
resumeZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    resumeZone.classList.add('dragover');
});
resumeZone.addEventListener('dragleave', () => {
    resumeZone.classList.remove('dragover');
});
resumeZone.addEventListener('drop', (e) => {
    e.preventDefault();
    resumeZone.classList.remove('dragover');
    handleResumeFiles(e.dataTransfer.files);
});

// Handle CSV file upload
function handleCSVFiles(files) {
    if (files.length === 0) return;
    
    const file = files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const content = e.target.result;
        parseCSV(content);
    };
    
    if (file.name.endsWith('.csv')) {
        reader.readAsText(file);
    } else {
        alert('Please upload a CSV file. Excel files will be supported soon.');
    }
}

// Parse CSV content
function parseCSV(content) {
    const lines = content.split('\n').filter(line => line.trim());
    if (lines.length < 2) {
        alert('CSV file must have a header row and at least one data row.');
        return;
    }
    
    const headers = parseCSVLine(lines[0]);
    csvData = [];
    
    for (let i = 1; i < lines.length; i++) {
        const values = parseCSVLine(lines[i]);
        if (values.length > 0 && values.some(v => v.trim())) {
            const row = {};
            headers.forEach((h, idx) => {
                row[h.trim().toLowerCase().replace(/\s+/g, '_')] = values[idx] || '';
            });
            csvData.push(row);
        }
    }
    
    showCSVPreview(headers, csvData);
    updateSummary();
}

// Parse a single CSV line (handles quoted values)
function parseCSVLine(line) {
    const result = [];
    let current = '';
    let inQuotes = false;
    
    for (let i = 0; i < line.length; i++) {
        const char = line[i];
        if (char === '"') {
            inQuotes = !inQuotes;
        } else if (char === ',' && !inQuotes) {
            result.push(current.trim());
            current = '';
        } else {
            current += char;
        }
    }
    result.push(current.trim());
    return result;
}

// Show CSV preview
function showCSVPreview(headers, data) {
    const preview = document.getElementById('csvPreview');
    const headerRow = document.getElementById('csvPreviewHeader');
    const body = document.getElementById('csvPreviewBody');
    const rowCount = document.getElementById('csvRowCount');
    
    headerRow.innerHTML = '<th>Status</th>' + headers.map(h => `<th>${escapeHtml(h)}</th>`).join('');
    
    body.innerHTML = data.slice(0, 10).map((row, idx) => {
        const hasName = row.first_name || row.firstname || row.name;
        const status = hasName ? 'valid' : 'invalid';
        const statusText = hasName ? 'Valid' : 'Missing Name';
        
        return `<tr>
            <td><span class="row-status ${status}">${statusText}</span></td>
            ${headers.map(h => `<td>${escapeHtml(row[h.trim().toLowerCase().replace(/\s+/g, '_')] || '')}</td>`).join('')}
        </tr>`;
    }).join('');
    
    if (data.length > 10) {
        body.innerHTML += `<tr><td colspan="${headers.length + 1}" style="text-align: center; color: #6b7280; font-style: italic;">... and ${data.length - 10} more rows</td></tr>`;
    }
    
    rowCount.textContent = data.length;
    preview.classList.add('active');
}

// Handle resume files
function handleResumeFiles(files) {
    const validExtensions = ['.pdf', '.doc', '.docx', '.txt', '.rtf'];
    
    for (let file of files) {
        const ext = '.' + file.name.split('.').pop().toLowerCase();
        if (validExtensions.includes(ext) && resumeFiles.length < 50) {
            if (!resumeFiles.find(f => f.name === file.name)) {
                resumeFiles.push(file);
            }
        }
    }
    
    renderResumeFileList();
    updateSummary();
}

// Render resume file list
function renderResumeFileList() {
    const container = document.getElementById('resumeFileList');
    const items = document.getElementById('resumeFileItems');
    const count = document.getElementById('resumeFileCount');
    
    if (resumeFiles.length === 0) {
        container.style.display = 'none';
        return;
    }
    
    container.style.display = 'block';
    count.textContent = resumeFiles.length;
    
    items.innerHTML = resumeFiles.map((file, idx) => {
        const ext = file.name.split('.').pop().toLowerCase();
        const size = formatFileSize(file.size);
        
        return `<div class="file-item">
            <div class="file-item-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                    <polyline points="14 2 14 8 20 8"/>
                </svg>
            </div>
            <div class="file-item-info">
                <div class="file-item-name">${escapeHtml(file.name)}</div>
                <div class="file-item-size">${size}</div>
            </div>
            <span class="file-item-status pending">Pending</span>
            <button class="file-item-remove" onclick="removeResumeFile(${idx})">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>`;
    }).join('');
}

// Remove a resume file
function removeResumeFile(idx) {
    resumeFiles.splice(idx, 1);
    renderResumeFileList();
    updateSummary();
}

// Clear all resume files
function clearResumeFiles() {
    resumeFiles = [];
    renderResumeFileList();
    updateSummary();
}

// Update summary
function updateSummary() {
    const summary = document.getElementById('importSummary');
    const btn = document.getElementById('importBtn');
    
    if (currentTab === 'csv') {
        if (csvData.length > 0) {
            summary.innerHTML = `<strong>${csvData.length}</strong> candidates ready to import`;
            btn.disabled = false;
        } else {
            summary.textContent = 'No CSV file uploaded';
            btn.disabled = true;
        }
    } else {
        if (resumeFiles.length > 0) {
            summary.innerHTML = `<strong>${resumeFiles.length}</strong> resume files ready to upload`;
            btn.disabled = false;
        } else {
            summary.textContent = 'No resume files selected';
            btn.disabled = true;
        }
    }
}

// Download CSV template
function downloadTemplate() {
    const headers = ['First Name', 'Last Name', 'Email', 'Phone', 'City', 'State', 'Key Skills', 'Current Employer', 'Notes', 'Source'];
    const sampleRow = ['John', 'Doe', 'john.doe@example.com', '555-123-4567', 'New York', 'NY', 'JavaScript, React, Node.js', 'Tech Corp', 'Experienced developer', 'LinkedIn'];
    
    const csvContent = headers.join(',') + '\n' + sampleRow.join(',') + '\n';
    
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'candidate_import_template.csv';
    a.click();
    URL.revokeObjectURL(url);
}

// Start import
function startImport() {
    if (currentTab === 'csv') {
        importCSVData();
    } else {
        importResumeFiles();
    }
}

// Import CSV data
function importCSVData() {
    if (csvData.length === 0) return;
    
    document.getElementById('importActions').style.display = 'none';
    document.getElementById('importProgress').classList.add('active');
    
    let imported = 0;
    let failed = 0;
    let skipped = 0;
    let current = 0;
    
    function processNext() {
        if (current >= csvData.length) {
            showResults(imported, failed, skipped);
            return;
        }
        
        const row = csvData[current];
        const percent = Math.round((current / csvData.length) * 100);
        
        document.getElementById('progressPercent').textContent = percent + '%';
        document.getElementById('progressBarFill').style.width = percent + '%';
        document.getElementById('progressStatus').textContent = `Processing candidate ${current + 1} of ${csvData.length}...`;
        
        // Send to server
        const formData = new FormData();
        formData.append('action', 'importCandidate');
        formData.append('firstName', row.first_name || row.firstname || '');
        formData.append('lastName', row.last_name || row.lastname || '');
        formData.append('email', row.email || row.email1 || '');
        formData.append('phone', row.phone || row.phone_cell || row.mobile || '');
        formData.append('city', row.city || '');
        formData.append('state', row.state || '');
        formData.append('keySkills', row.key_skills || row.skills || '');
        formData.append('currentEmployer', row.current_employer || row.employer || row.company || '');
        formData.append('notes', row.notes || '');
        formData.append('source', row.source || 'Bulk Import');
        formData.append('jobOrderID', document.getElementById('jobOrderID').value);
        
        fetch('<?php echo CATSUtility::getIndexName(); ?>?m=import&a=bulkImportCandidate', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                imported++;
            } else if (data.duplicate) {
                skipped++;
            } else {
                failed++;
            }
            current++;
            setTimeout(processNext, 50);
        })
        .catch(() => {
            failed++;
            current++;
            setTimeout(processNext, 50);
        });
    }
    
    processNext();
}

// Import resume files
function importResumeFiles() {
    if (resumeFiles.length === 0) return;
    
    document.getElementById('importActions').style.display = 'none';
    document.getElementById('importProgress').classList.add('active');
    
    let imported = 0;
    let failed = 0;
    let current = 0;
    
    function processNext() {
        if (current >= resumeFiles.length) {
            showResults(imported, failed, 0);
            return;
        }
        
        const file = resumeFiles[current];
        const percent = Math.round((current / resumeFiles.length) * 100);
        
        document.getElementById('progressPercent').textContent = percent + '%';
        document.getElementById('progressBarFill').style.width = percent + '%';
        document.getElementById('progressStatus').textContent = `Uploading ${file.name}...`;
        
        const formData = new FormData();
        formData.append('action', 'importResume');
        formData.append('resumeFile', file);
        formData.append('jobOrderID', document.getElementById('jobOrderID').value);
        
        fetch('<?php echo CATSUtility::getIndexName(); ?>?m=import&a=bulkImportResume', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('HTTP error ' + response.status);
            }
            return response.text();
        })
        .then(text => {
            console.log('Server response for ' + file.name + ':', text);
            try {
                const data = JSON.parse(text);
                if (data.success) {
                    imported++;
                    updateFileStatus(current, 'success', data);
                    console.log('%c Successfully imported: ' + (data.name || file.name), 'color: green; font-weight: bold');
                    if (data.email) console.log('  Email: ' + data.email);
                    if (data.phone) console.log('  Phone: ' + data.phone);
                    if (data.city) console.log('  City: ' + data.city);
                    if (data.state) console.log('  State: ' + data.state);
                    if (data.skills) console.log('  Skills: ' + data.skills);
                    if (data.warning) console.warn('  Warning: ' + data.warning);
                    if (data.debug) {
                        console.log('  Debug Info:');
                        console.log('    - Extraction Method: ' + data.debug.extractionMethod);
                        console.log('    - Text Length: ' + data.debug.textLength);
                        console.log('    - Text Preview: ' + (data.debug.textPreview || '(empty)'));
                    }
                } else {
                    failed++;
                    updateFileStatus(current, 'error', data);
                    console.error('%c Import failed for ' + file.name + ': ' + (data.error || 'Unknown error'), 'color: red; font-weight: bold');
                }
            } catch (e) {
                failed++;
                updateFileStatus(current, 'error');
                console.error('%c JSON parse error for ' + file.name, 'color: red; font-weight: bold');
                console.error('  Parse Error:', e);
                console.error('  Raw Response:', text.substring(0, 500));
            }
            current++;
            setTimeout(processNext, 100);
        })
        .catch((err) => {
            failed++;
            updateFileStatus(current, 'error');
            console.error('Fetch error for ' + file.name + ':', err);
            current++;
            setTimeout(processNext, 100);
        });
    }
    
    processNext();
}

// Update file status in list
function updateFileStatus(idx, status, data) {
    const items = document.querySelectorAll('.file-item');
    if (items[idx]) {
        const statusEl = items[idx].querySelector('.file-item-status');
        statusEl.className = 'file-item-status ' + status;
        
        if (status === 'success' && data) {
            statusEl.textContent = 'Imported';
            // Show parsed name if different from filename
            if (data.name) {
                const nameEl = items[idx].querySelector('.file-item-name');
                if (nameEl) {
                    nameEl.innerHTML = escapeHtml(data.name) + 
                        '<span style="color: #6b7280; font-weight: 400; font-size: 11px; margin-left: 8px;">' + 
                        (data.email ? data.email : '') + '</span>';
                }
            }
        } else {
            statusEl.textContent = 'Failed';
            // Show error message if available
            if (data && data.error) {
                const nameEl = items[idx].querySelector('.file-item-name');
                if (nameEl) {
                    nameEl.innerHTML = nameEl.textContent + 
                        '<span style="color: #dc2626; font-weight: 400; font-size: 11px; margin-left: 8px;">' + 
                        escapeHtml(data.error) + '</span>';
                }
            }
        }
    }
}

// Show results
function showResults(imported, failed, skipped) {
    document.getElementById('importProgress').classList.remove('active');
    document.getElementById('importResults').classList.add('active');
    
    document.getElementById('successCount').textContent = imported;
    document.getElementById('errorCount').textContent = failed;
    document.getElementById('skippedCount').textContent = skipped;
    
    const icon = document.getElementById('resultsIcon');
    const title = document.getElementById('resultsTitle');
    
    if (failed === 0 && skipped === 0) {
        icon.className = 'results-icon success';
        title.textContent = 'Import Complete!';
    } else {
        icon.className = 'results-icon partial';
        title.textContent = 'Import Completed with Issues';
    }
    
    document.getElementById('progressBarFill').style.width = '100%';
    document.getElementById('progressPercent').textContent = '100%';
}

// Reset import
function resetImport() {
    csvData = [];
    resumeFiles = [];
    
    document.getElementById('csvPreview').classList.remove('active');
    document.getElementById('resumeFileList').style.display = 'none';
    document.getElementById('importProgress').classList.remove('active');
    document.getElementById('importResults').classList.remove('active');
    document.getElementById('importActions').style.display = 'flex';
    document.getElementById('progressBarFill').style.width = '0%';
    
    updateSummary();
}

// Utility functions
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatFileSize(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}
</script>

<?php TemplateUtility::printFooter(); ?>
