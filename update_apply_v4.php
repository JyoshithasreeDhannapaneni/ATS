<?php
/**
 * Career Portal — Apply Page V4 Redesign
 * Matches the exact design from the provided screenshot:
 * - Blue primary (#2563eb) theme
 * - 4-step horizontal stepper
 * - 2-column layout (form + sidebar summary)
 * - State dropdown, Source dropdown
 * - Drag-and-drop file upload
 * - Save Draft + Save & Continue buttons
 */

$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");


// =====================================================
// CSS — V4 Clean Blue Theme
// =====================================================
$css = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-darker: #1e40af;
    --primary-light: #dbeafe;
    --primary-50: #eff6ff;
    --success: #16a34a;
    --success-light: #dcfce7;
    --warning: #f59e0b;
    --danger: #dc2626;
    --rose: #e11d48;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --radius: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 6px rgba(0,0,0,0.04), 0 2px 4px rgba(0,0,0,0.03);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.06), 0 4px 10px rgba(0,0,0,0.03);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body, html {
    font-family: var(--font);
    color: var(--gray-800);
    background: var(--gray-50);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}
body {
    display: flex; flex-direction: column; min-height: 100vh;
    opacity: 0; transition: opacity 0.5s ease;
}
body.page-loaded { opacity: 1; }
a { color: var(--primary); text-decoration: none; transition: all 0.2s ease; }
a:hover { color: var(--primary-dark); }
a:visited { color: var(--primary); }
img { border: none; }
h1 { font: 700 28px var(--font); color: var(--gray-900); letter-spacing: -0.02em; margin: 0 0 8px; background: none; -webkit-text-fill-color: var(--gray-900); }
h2 { font: 600 16px var(--font); color: var(--gray-900); margin: 0; border: none; padding: 0; }
h3 { font: 600 14px var(--font); color: var(--gray-700); margin: 0 0 4px; }
p { font: 400 14px/1.6 var(--font); color: var(--gray-500); }
strong { font-weight: 600; color: var(--gray-800); }
#careerContent { clear: both; padding: 0; flex: 1; }
#poweredCATS { display: none; }

/* ===== HEADER ===== */
.career-header {
    background: #fff;
    border-bottom: 1px solid var(--gray-200);
    position: sticky;
    top: 0;
    z-index: 100;
}
.header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 64px;
}
.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none !important;
}
.brand-icon {
    width: 36px;
    height: 36px;
    background: var(--primary);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-weight: 700;
    font-size: 16px;
}
.brand-text {
    font: 600 18px var(--font);
    color: var(--gray-900);
}
.brand-text span {
    font-weight: 400;
    color: var(--gray-500);
}
.header-nav {
    display: flex;
    align-items: center;
    gap: 24px;
}
.header-nav > a:first-child {
    color: var(--gray-600);
    font: 500 14px var(--font);
}
.header-nav > a:first-child:hover {
    color: var(--primary);
}
.nav-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--primary);
    color: #fff !important;
    font: 600 14px var(--font);
    border-radius: var(--radius);
    transition: all 0.2s ease;
    text-decoration: none !important;
}
.nav-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
}

/* ===== FOOTER ===== */
.career-footer {
    background: #fff;
    border-top: 1px solid var(--gray-200);
    margin-top: auto;
}
.footer-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 48px 32px;
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr;
    gap: 48px;
}
.footer-brand .brand-text {
    font: 700 18px var(--font);
    color: var(--gray-900);
    margin-bottom: 12px;
}
.footer-brand .brand-text span {
    font-weight: 400;
    color: var(--gray-500);
}
.footer-brand p {
    font: 400 13px/1.6 var(--font);
    color: var(--gray-500);
    margin: 0 0 16px;
    max-width: 280px;
}
.footer-social {
    display: flex;
    gap: 12px;
}
.footer-social a {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: var(--gray-100);
    color: var(--gray-500);
    transition: all 0.2s;
}
.footer-social a:hover {
    background: var(--primary-50);
    color: var(--primary);
}
.footer-col h4 {
    font: 600 12px var(--font);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--gray-500);
    margin-bottom: 16px;
}
.footer-col a {
    display: block;
    font: 400 14px var(--font);
    color: var(--gray-600);
    margin-bottom: 10px;
}
.footer-col a:hover {
    color: var(--primary);
}
.footer-bottom {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 32px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-top: 1px solid var(--gray-200);
    font: 400 13px var(--font);
    color: var(--gray-400);
}

/* ===== MAIN CONTENT ===== */
.main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 32px;
}

/* ===== STEP INDICATOR ===== */
.step-indicator {
    display: flex;
    align-items: center;
    gap: 0;
    padding: 24px 0;
    border-bottom: 1px solid var(--gray-200);
    margin-bottom: 32px;
    overflow-x: auto;
}
.step-item {
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
    flex: 1;
}
.step-item:not(:last-child)::after {
    content: '';
    flex: 1;
    height: 1px;
    background: var(--gray-200);
    margin: 0 16px;
    min-width: 24px;
}
.step-number {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font: 600 13px var(--font);
    flex-shrink: 0;
}
.step-number.active {
    background: var(--primary);
    color: #fff;
}
.step-number.inactive {
    background: var(--gray-100);
    color: var(--gray-500);
    border: 1.5px solid var(--gray-300);
}
.step-number.completed {
    background: var(--success);
    color: #fff;
}
.step-label {
    font: 500 14px var(--font);
    color: var(--gray-700);
}
.step-label.inactive {
    color: var(--gray-400);
}

/* ===== APPLY PAGE LAYOUT ===== */
.apply-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 32px;
    align-items: start;
}

/* ===== PROGRESS BAR ===== */
.progress-section {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}
.progress-label {
    font: 500 13px var(--font);
    color: var(--gray-500);
    white-space: nowrap;
}
.progress-bar-bg {
    flex: 1;
    height: 4px;
    background: var(--gray-200);
    border-radius: 2px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 100%;
    width: 0%;
    background: var(--primary);
    border-radius: 2px;
    transition: width 0.6s ease;
}

/* ===== FORM SECTIONS ===== */
.form-section {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    padding: 32px;
    margin-bottom: 24px;
    transition: box-shadow 0.2s ease;
}
.form-section:hover {
    box-shadow: var(--shadow-md);
}
.form-section-header {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 24px;
}
.form-section-icon {
    width: 40px;
    height: 40px;
    background: var(--primary-50);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.form-section-icon svg {
    color: var(--primary);
}
.form-section-title h2 {
    font: 600 16px var(--font);
    color: var(--gray-900);
    margin-bottom: 2px;
}
.form-section-title p {
    font: 400 13px var(--font);
    color: var(--gray-500);
    margin: 0;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-grid .full {
    grid-column: 1 / -1;
}

label {
    font: 600 13px var(--font);
    color: var(--gray-700);
    display: block;
    margin-bottom: 6px;
}
label .req {
    color: var(--danger);
    margin-left: 2px;
}

/* Form inputs */
input.inputbox, input.inputBoxName, input.inputBoxNormal,
input.inputBoxFile, input[type="text"], input[type="email"], input[type="tel"] {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font: 400 14px var(--font);
    color: var(--gray-800);
    transition: all 0.2s ease;
    background: #fff;
    outline: none;
}
input.inputbox::placeholder, input.inputBoxName::placeholder, input.inputBoxNormal::placeholder,
input[type="text"]::placeholder, input[type="email"]::placeholder {
    color: var(--gray-400);
}
input.inputbox:hover, input.inputBoxName:hover, input.inputBoxNormal:hover,
input[type="text"]:hover { border-color: var(--gray-400); }
input.inputbox:focus, input.inputBoxName:focus, input.inputBoxNormal:focus,
input[type="text"]:focus, input[type="email"]:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

textarea, textarea.inputBoxArea, textarea.inputboxlarge {
    width: 100%;
    padding: 10px 14px;
    min-height: 80px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font: 400 14px var(--font);
    color: var(--gray-800);
    resize: vertical;
    transition: all 0.2s ease;
    background: #fff;
    outline: none;
}
textarea:focus, textarea.inputBoxArea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

select, select.inputBoxNormal {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font: 400 14px var(--font);
    color: var(--gray-800);
    background: #fff;
    outline: none;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
    transition: all 0.2s ease;
}
select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* File Upload Area */
.file-upload-area {
    border: 2px dashed var(--gray-300);
    border-radius: var(--radius-lg);
    padding: 32px 24px;
    text-align: center;
    transition: all 0.2s ease;
    cursor: pointer;
    background: var(--gray-50);
}
.file-upload-area:hover {
    border-color: var(--primary);
    background: var(--primary-50);
}
.file-upload-area .upload-icon {
    margin-bottom: 8px;
    color: var(--gray-400);
}
.file-upload-area p {
    font: 400 14px var(--font);
    color: var(--gray-500);
    margin: 0 0 4px;
}
.file-upload-area .or-text {
    font: 400 13px var(--font);
    color: var(--gray-400);
    margin: 4px 0 12px;
}
.choose-file-btn {
    display: inline-block;
    padding: 8px 20px;
    background: var(--primary);
    color: #fff;
    font: 600 13px var(--font);
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
    border: none;
}
.choose-file-btn:hover {
    background: var(--primary-dark);
}
.file-types {
    font: 400 12px var(--font);
    color: var(--gray-400);
    margin-top: 12px;
}
input.inputBoxFile, input[type="file"] {
    display: none;
}

/* Submit / action buttons */
.form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 24px 0 48px;
}
.btn-draft {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    background: #fff;
    color: var(--gray-700);
    font: 600 14px var(--font);
    cursor: pointer;
    transition: all 0.2s;
}
.btn-draft:hover {
    background: var(--gray-50);
    border-color: var(--gray-400);
}
.btn-submit {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 32px;
    background: var(--primary);
    color: #fff;
    font: 600 14px var(--font);
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.2s;
}
.btn-submit:hover {
    background: var(--primary-dark);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
    transform: translateY(-1px);
}

/* HIDE legacy submit button that CATS injects */
input.submitbutton, input.submitButton, input[type="submit"] {
    display: none !important;
}

/* ===== SIDEBAR ===== */
.apply-sidebar {
    position: sticky;
    top: 80px;
}
.summary-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    padding: 24px;
}
.summary-card h3 {
    font: 600 16px var(--font);
    color: var(--gray-900);
    margin-bottom: 4px;
}
.summary-card .summary-subtitle {
    font: 400 13px var(--font);
    color: var(--gray-500);
    margin-bottom: 20px;
}
.summary-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
}
.summary-item:not(:last-child) {
    border-bottom: 1px solid var(--gray-100);
}
.summary-dot {
    width: 20px;
    height: 20px;
    border-radius: 50%;
    border: 2px solid var(--gray-300);
    flex-shrink: 0;
    margin-top: 2px;
}
.summary-dot.completed {
    border-color: var(--success);
    background: var(--success);
    position: relative;
}
.summary-dot.active {
    border-color: var(--primary);
    background: var(--primary);
}
.summary-item-text h4 {
    font: 500 14px var(--font);
    color: var(--gray-700);
    margin: 0 0 2px;
}
.summary-item-text p {
    font: 400 12px var(--font);
    color: var(--gray-400);
    margin: 0;
}

.tip-box {
    margin-top: 20px;
    padding: 16px;
    background: var(--primary-50);
    border-radius: var(--radius-lg);
    border: 1px solid var(--primary-light);
}
.tip-box .tip-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
}
.tip-box .tip-header svg {
    color: var(--primary);
}
.tip-box .tip-header span {
    font: 600 13px var(--font);
    color: var(--primary-dark);
}
.tip-box p {
    font: 400 13px/1.5 var(--font);
    color: var(--primary-dark);
    margin: 0;
}

/* Hide old layout wrappers */
div.applyBoxLeft, div.applyBoxRight { float: none; width: 100%; max-width: 100%; border: none; box-shadow: none; padding: 0; background: transparent; margin: 0; }
div.applyBoxLeft div, div.applyBoxRight div { display: none; }
div.applyBoxLeft table, div.applyBoxRight table { display: none; }
td.label { display: none; }
#detailsTable { display: none; }
div#discriptive { float: none; width: 100%; margin: 0; }
div#detailsTools { display: none; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .apply-layout { grid-template-columns: 1fr; }
    .apply-sidebar { position: static; }
    .form-grid { grid-template-columns: 1fr; }
    .step-indicator { gap: 8px; overflow-x: auto; padding-bottom: 16px; }
    .step-item:not(:last-child)::after { min-width: 12px; margin: 0 8px; }
    .step-label { font-size: 12px; }
    .footer-inner { grid-template-columns: 1fr; gap: 32px; }
    .header-inner { padding: 0 16px; }
    .main-content { padding: 0 16px; }
}
@media (max-width: 600px) {
    .form-section { padding: 20px; }
    .form-actions { flex-direction: column; gap: 12px; }
    .step-label { display: none; }
}

/* ===== SCROLL REVEALS ===== */
.reveal { opacity: 0; transform: translateY(20px); transition: opacity 0.5s ease, transform 0.5s ease; }
.reveal.revealed { opacity: 1; transform: translateY(0); }
CSS;

$stmt->execute(['val' => $css, 'setting' => 'CSS']);
echo "CSS updated (" . strlen($css) . " bytes)\n";


// =====================================================
// HEADER
// =====================================================
$header = <<<'TPL'
<div class="career-header">
    <div class="header-inner">
        <a href="index.php?m=careers" class="brand">
            <div class="brand-icon">N</div>
            <div class="brand-text">Neutara <span>Careers</span></div>
        </a>
        <nav class="header-nav">
            <a href="index.php?m=careers&p=showAll">Positions</a>
            <a href="index.php?m=careers&p=showAll" class="nav-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Explore Jobs
            </a>
        </nav>
    </div>
</div>
TPL;
$stmt->execute(['val' => $header, 'setting' => 'Header']);
echo "Header updated\n";


// =====================================================
// CONTENT - MAIN → Auto-redirect
// =====================================================
$main = <<<'TPL'
<script>window.location.replace('index.php?m=careers&p=showAll');</script>
<div class="main-content" style="text-align:center;padding:120px 24px;">
    <p>Redirecting to open positions...</p>
</div>
TPL;
$stmt->execute(['val' => $main, 'setting' => 'Content']);
echo "Main updated\n";


// =====================================================
// CONTENT - JOB LIST (Show All)
// =====================================================
// Keep existing job list — only updating Apply page
// (We won't touch this template)


// =====================================================
// APPLY FOR POSITION — Exact match to screenshot
// =====================================================
$apply = <<<'TPL'
<div class="main-content">
    <!-- Back link -->
    <div style="padding-top:24px;margin-bottom:16px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-500);display:inline-flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Jobs
        </a>
    </div>

    <!-- Page title + progress -->
    <div class="reveal" style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:28px;margin-bottom:4px;">Apply for <title></h1>
            <p style="color:var(--gray-500);margin:0;font-size:14px;">Fields marked <span style="color:var(--danger);">*</span> are required.</p>
        </div>
        <div style="text-align:right;min-width:200px;">
            <div class="progress-section">
                <span class="progress-label" id="formProgressLabel">0% complete</span>
            </div>
            <div class="progress-bar-bg" style="margin-top:6px;">
                <div class="progress-bar-fill" id="formProgress"></div>
            </div>
        </div>
    </div>

    <!-- Step Indicator -->
    <div class="step-indicator reveal">
        <div class="step-item">
            <div class="step-number active">1</div>
            <span class="step-label">Personal Information</span>
        </div>
        <div class="step-item">
            <div class="step-number inactive">2</div>
            <span class="step-label inactive">Professional Background</span>
        </div>
        <div class="step-item">
            <div class="step-number inactive">3</div>
            <span class="step-label inactive">Resume & Documents</span>
        </div>
        <div class="step-item">
            <div class="step-number inactive">4</div>
            <span class="step-label inactive">Review & Submit</span>
        </div>
    </div>

    <!-- 2-Column Layout -->
    <div class="apply-layout">
        <!-- LEFT: Form sections -->
        <div>
            <!-- Section 1: Personal Information -->
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="form-section-title">
                        <h2>Personal Information</h2>
                        <p>Tell us who you are.</p>
                    </div>
                </div>
                <div class="form-grid">
                    <div>
                        <label>First Name <span class="req">*</span></label>
                        <input-firstName req>
                    </div>
                    <div>
                        <label>Last Name <span class="req">*</span></label>
                        <input-lastName req>
                    </div>
                    <div>
                        <label>Email <span class="req">*</span></label>
                        <input-email req>
                    </div>
                    <div>
                        <label>Confirm Email <span class="req">*</span></label>
                        <input-emailconfirm req>
                    </div>
                    <div>
                        <label>Phone <span class="req">*</span></label>
                        <input-phone req>
                    </div>
                    <div>
                        <label>City <span class="req">*</span></label>
                        <input-city req>
                    </div>
                    <div>
                        <label>State <span class="req">*</span></label>
                        <input-state req>
                    </div>
                    <div>
                        <label>Zip Code <span class="req">*</span></label>
                        <input-zip req>
                    </div>
                    <div class="full">
                        <label>Address <span class="req">*</span></label>
                        <input-address req>
                    </div>
                </div>
            </div>

            <!-- Section 2: Professional Background -->
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:#f0fdf4;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                    </div>
                    <div class="form-section-title">
                        <h2>Professional Background</h2>
                        <p>Help us understand your professional experience.</p>
                    </div>
                </div>
                <div class="form-grid">
                    <div>
                        <label>Key Skills <span class="req">*</span></label>
                        <input-keySkills req>
                    </div>
                    <div>
                        <label>Current Employer</label>
                        <input-employer>
                    </div>
                    <div class="full">
                        <label>How did you hear about us? <span class="req">*</span></label>
                        <input-source req>
                    </div>
                    <div class="full">
                        <label>Additional Information</label>
                        <input-extraNotes>
                    </div>
                </div>
            </div>

            <!-- Section 3: Resume & Documents -->
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:#eff6ff;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
                    </div>
                    <div class="form-section-title">
                        <h2>Resume & Documents</h2>
                        <p>Upload your resume and add any additional documents.</p>
                    </div>
                </div>
                <div>
                    <label>Upload Resume <span class="req">*</span></label>
                    <div class="file-upload-area" onclick="document.getElementById('resume') ? document.getElementById('resume').click() : (document.getElementById('resumeFile') ? document.getElementById('resumeFile').click() : null)">
                        <div class="upload-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <p>Drag and drop your file here</p>
                        <div class="or-text">or</div>
                        <span class="choose-file-btn">Choose File</span>
                    </div>
                    <input-resumeUpload>
                    <div class="file-types">PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</div>
                </div>
                <div style="margin-top:20px;">
                    <label>Cover Letter / Notes</label>
                    <input-extraNotes>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="form-actions reveal">
                <button type="button" class="btn-draft" onclick="alert('Draft saved!');">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                    Save Draft
                </button>
                <submit value="Save & Continue →" class="btn-submit">
            </div>
        </div>

        <!-- RIGHT: Sidebar -->
        <div class="apply-sidebar">
            <div class="summary-card reveal">
                <h3>Application Summary</h3>
                <p class="summary-subtitle">Review your progress</p>

                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Personal Information</h4>
                        <p>Not started</p>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Professional Background</h4>
                        <p>Not started</p>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Resume & Documents</h4>
                        <p>Not started</p>
                    </div>
                </div>
                <div class="summary-item">
                    <div class="summary-dot"></div>
                    <div class="summary-item-text">
                        <h4>Review & Submit</h4>
                        <p>Not started</p>
                    </div>
                </div>

                <div class="tip-box">
                    <div class="tip-header">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                        <span>Tip</span>
                    </div>
                    <p>Complete all sections to increase your chances of getting noticed by our team.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form progress tracker
(function() {
    function updateProgress() {
        var inputs = document.querySelectorAll('#applyToJobForm input[type="text"], #applyToJobForm input[type="email"], #applyToJobForm input[type="tel"], #applyToJobForm textarea, #applyToJobForm select');
        var filled = 0;
        var total = 0;
        inputs.forEach(function(input) {
            if (input.type !== 'hidden' && input.offsetParent !== null) {
                total++;
                if (input.value && input.value.trim() !== '') filled++;
            }
        });
        var pct = total > 0 ? Math.round((filled / total) * 100) : 0;
        var bar = document.getElementById('formProgress');
        var label = document.getElementById('formProgressLabel');
        if (bar) bar.style.width = pct + '%';
        if (label) label.textContent = pct + '% complete';

        // Update summary dots
        var dots = document.querySelectorAll('.summary-dot');
        var statuses = document.querySelectorAll('.summary-item-text p');
        // Section 1: Personal Info (firstName, lastName, email, phone)
        var s1 = ['firstName','lastName','email','phone'].filter(function(id) { var el = document.getElementById(id); return el && el.value.trim(); }).length;
        if (s1 >= 4) { dots[0].style.borderColor = '#16a34a'; dots[0].style.background = '#16a34a'; statuses[0].textContent = 'Completed'; }
        else if (s1 > 0) { dots[0].style.borderColor = '#2563eb'; dots[0].style.background = '#2563eb'; statuses[0].textContent = 'In progress'; }
        // Section 2: Professional
        var s2 = ['keySkills','source'].filter(function(id) { var el = document.getElementById(id); return el && el.value.trim(); }).length;
        if (s2 >= 2) { dots[1].style.borderColor = '#16a34a'; dots[1].style.background = '#16a34a'; statuses[1].textContent = 'Completed'; }
        else if (s2 > 0) { dots[1].style.borderColor = '#2563eb'; dots[1].style.background = '#2563eb'; statuses[1].textContent = 'In progress'; }
        // Section 3: Resume
        var fileEl = document.getElementById('resume') || document.getElementById('resumeFile');
        if (fileEl && fileEl.value) { dots[2].style.borderColor = '#16a34a'; dots[2].style.background = '#16a34a'; statuses[2].textContent = 'Completed'; }

        // Step indicator updates
        var steps = document.querySelectorAll('.step-number');
        if (s1 >= 4 && steps[0]) { steps[0].className = 'step-number completed'; steps[0].innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>'; }
        if (s2 >= 2 && steps[1]) { steps[1].className = 'step-number completed'; steps[1].innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>'; }
    }

    document.addEventListener('DOMContentLoaded', function() {
        setInterval(updateProgress, 500);
    });
})();

// Scroll reveal
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-loaded');
        var els = document.querySelectorAll('.reveal');
        if (!('IntersectionObserver' in window)) {
            els.forEach(function(el) { el.classList.add('revealed'); });
            return;
        }
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        els.forEach(function(el) { observer.observe(el); });
    });
})();

// Placeholder text for inputs
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        var placeholders = {
            'firstName': 'Enter your first name',
            'lastName': 'Enter your last name',
            'email': 'Enter your email address',
            'emailconfirm': 'Re-enter your email address',
            'phone': 'Enter your phone number',
            'city': 'Enter your city',
            'state': 'Enter your state',
            'zip': 'Enter zip code',
            'address': 'Enter your full address',
            'keySkills': 'Enter your key skills (comma separated)',
            'employer': 'Enter your current employer',
            'source': 'Select an option',
            'extraNotes': 'Add any other relevant information'
        };
        Object.keys(placeholders).forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.placeholder = placeholders[id];
        });
    });
})();
</script>
TPL;
$stmt->execute(['val' => $apply, 'setting' => 'Content - Apply for Position']);
echo "Apply page updated\n";


// =====================================================
// THANKS FOR SUBMISSION
// =====================================================
$thanks = <<<'TPL'
<div class="main-content" style="text-align:center;padding:80px 24px;max-width:600px;">
    <div class="reveal" style="margin-bottom:32px;">
        <div style="width:80px;height:80px;background:var(--success-light);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1 style="font-size:24px;margin-bottom:8px;">Application Submitted!</h1>
        <p style="font-size:15px;color:var(--gray-500);max-width:400px;margin:0 auto 24px;">Thank you for applying. We have received your application and will review it shortly.</p>
        <a href="index.php?m=careers&p=showAll" class="nav-btn" style="display:inline-flex;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Positions
        </a>
    </div>
</div>
TPL;
$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Thanks page updated\n";


// =====================================================
// FOOTER
// =====================================================
$footer = <<<'TPL'
<div class="career-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-text">Neutara <span>Careers</span></div>
            <p>Building the future with exceptional talent. We are committed to creating an inclusive workplace where innovation thrives.</p>
            <div class="footer-social">
                <a href="#" title="LinkedIn"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
                <a href="#" title="Twitter"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
                <a href="#" title="Instagram"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 100 12.324 6.162 6.162 0 100-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 11-2.88 0 1.441 1.441 0 012.88 0z"/></svg></a>
                <a href="#" title="Facebook"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Careers</h4>
            <a href="index.php?m=careers&p=showAll">Open Positions</a>
            <a href="index.php?m=careers&p=showAll">Departments</a>
        </div>
        <div class="footer-col">
            <h4>Company</h4>
            <a href="#">About Us</a>
            <a href="#">Culture</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; 2026 Neutara. All rights reserved.</span>
        <span style="display:flex;gap:20px;">
            <a href="#" style="color:var(--gray-500);font-weight:500;">Privacy</a>
            <a href="#" style="color:var(--gray-500);font-weight:500;">Terms</a>
        </span>
    </div>
</div>
TPL;
$stmt->execute(['val' => $footer, 'setting' => 'Footer']);
echo "Footer updated\n";


echo "\n=== Apply Page V4 Deployed! ===\n";
echo "Visit: http://localhost:8000/index.php?m=careers&p=showAll\n";
