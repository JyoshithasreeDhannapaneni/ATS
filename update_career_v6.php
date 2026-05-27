<?php
/**
 * Career Portal V6 — Clean Blue Design
 * Matches the provided screenshot exactly:
 * - Blue header bar with brand
 * - Blue gradient hero with "Join Our Team"
 * - Build Something section with illustration
 * - Stats row, feature cards, process timeline
 * - Job table, CTA banner, blue footer
 */

$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");


// =====================================================
// CSS
// =====================================================
$css = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-darker: #1e40af;
    --primary-light: #dbeafe;
    --primary-50: #eff6ff;
    --accent: #06b6d4;
    --violet: #7c3aed;
    --violet-light: #ede9fe;
    --emerald: #059669;
    --emerald-light: #d1fae5;
    --amber: #d97706;
    --rose: #e11d48;
    --success: #16a34a;
    --success-light: #dcfce7;
    --warning: #f59e0b;
    --danger: #dc2626;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-200: #e2e8f0;
    --gray-300: #cbd5e1;
    --gray-400: #94a3b8;
    --gray-500: #64748b;
    --gray-600: #475569;
    --gray-700: #334155;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
    --font: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    --radius: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
    --radius-2xl: 24px;
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
    --shadow-lg: 0 12px 40px rgba(0,0,0,0.08), 0 4px 12px rgba(0,0,0,0.04);
    --shadow-xl: 0 24px 60px rgba(0,0,0,0.1), 0 8px 24px rgba(0,0,0,0.06);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body, html {
    font-family: var(--font);
    color: var(--gray-800);
    background: #fff;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}
body {
    display: flex; flex-direction: column; min-height: 100vh;
    opacity: 0; transition: opacity 0.5s ease;
}
body.page-loaded { opacity: 1; }
a { color: var(--primary); text-decoration: none; transition: all 0.25s ease; }
a:hover { color: var(--primary-dark); }
a:visited { color: var(--primary); }
img { border: none; }

/* ===== SCROLL REVEALS ===== */
.reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1); }
.reveal.revealed { opacity: 1; transform: translateY(0); }
.stagger-children > * { opacity: 0; transform: translateY(25px); transition: opacity 0.6s cubic-bezier(0.22,1,0.36,1), transform 0.6s cubic-bezier(0.22,1,0.36,1); }
.stagger-children > *.stagger-visible { opacity: 1; transform: translateY(0); }

/* ===== HEADER ===== */
.career-header {
    background: var(--primary);
    position: sticky;
    top: 0;
    z-index: 1000;
}
.header-inner {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 56px;
}
.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none !important;
}
.brand-icon {
    width: 32px; height: 32px;
    background: rgba(255,255,255,0.2);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: 15px;
}
.brand-text {
    font: 600 16px var(--font);
    color: #fff;
}
.brand-text span {
    font-weight: 400;
    color: rgba(255,255,255,0.8);
    margin-left: 4px;
}
.header-nav { display: flex; align-items: center; gap: 16px; }
.header-nav a {
    color: rgba(255,255,255,0.9) !important;
    font: 500 14px var(--font);
    transition: color 0.2s;
}
.header-nav a:hover { color: #fff !important; }

/* ===== HERO BANNER ===== */
.hero-banner {
    background: linear-gradient(160deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%);
    padding: 60px 40px 90px;
    text-align: center;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.hero-banner::before {
    content: '';
    position: absolute;
    top: 16px; right: 40px;
    width: 100px; height: 70px;
    background-image: radial-gradient(circle, rgba(255,255,255,0.35) 2px, transparent 2px);
    background-size: 16px 16px;
    z-index: 2;
}
.hero-banner::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 40px;
    background: #fff;
    clip-path: ellipse(55% 100% at 50% 100%);
    z-index: 3;
}
.hero-banner h1 {
    font: 800 42px var(--font) !important;
    color: #fff !important;
    letter-spacing: -0.03em;
    margin: 0 0 12px 0;
    position: relative;
    z-index: 2;
    -webkit-text-fill-color: #fff !important;
    background: none !important;
    line-height: 1.2;
    max-width: 600px;
}
.hero-banner p {
    font: 400 16px/1.6 var(--font);
    color: rgba(255,255,255,0.85);
    max-width: 500px;
    margin: 0 auto;
    position: relative;
    z-index: 2;
}

/* ===== BUILD SECTION ===== */
.build-section {
    max-width: 1100px;
    margin: 0 auto;
    padding: 64px 32px 48px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 48px;
    align-items: center;
}
.build-text .hiring-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 50px;
    background: var(--primary-50);
    border: 1px solid var(--primary-light);
    font: 600 12px var(--font);
    color: var(--primary);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 20px;
}
.hiring-badge .dot {
    width: 7px; height: 7px;
    border-radius: 50%;
    background: #34d399;
    box-shadow: 0 0 8px #34d399;
    animation: dotPulse 2s infinite;
}
@keyframes dotPulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }

.build-text h2 {
    font: 800 36px var(--font);
    color: var(--gray-900);
    letter-spacing: -0.03em;
    line-height: 1.15;
    margin-bottom: 16px;
    border: none;
    padding: 0;
}
.typing-text {
    color: var(--primary);
}
.typing-cursor {
    display: inline-block;
    width: 2px; height: 0.9em;
    background: var(--primary);
    margin-left: 2px;
    vertical-align: text-bottom;
    animation: blink 1s step-end infinite;
}
@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0;} }

.build-text p {
    font: 400 15px/1.7 var(--font);
    color: var(--gray-500);
    margin-bottom: 24px;
    max-width: 440px;
}
.build-cta {
    display: flex; gap: 24px; align-items: center;
}
.build-cta a {
    font: 600 15px var(--font);
    color: var(--primary);
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: gap 0.3s;
}
.build-cta a:hover { gap: 8px; }

.build-illustration {
    display: flex;
    align-items: center;
    justify-content: center;
}
.build-illustration svg {
    width: 100%;
    max-width: 420px;
    height: auto;
}

/* ===== STATS ROW ===== */
.stats-row {
    max-width: 900px;
    margin: 0 auto 64px;
    padding: 0 32px;
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    text-align: center;
}
.stat-item {
    padding: 24px 16px;
}
.stat-icon {
    width: 48px; height: 48px;
    margin: 0 auto 12px;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary);
}
.stat-num {
    font: 800 28px var(--font);
    color: var(--gray-900);
    letter-spacing: -0.02em;
    display: block;
    margin-bottom: 4px;
}
.stat-label {
    font: 500 12px var(--font);
    color: var(--gray-400);
    text-transform: uppercase;
    letter-spacing: 0.06em;
}

/* ===== SECTION COMMON ===== */
.section-center {
    text-align: center;
    margin-bottom: 48px;
}
.section-tag {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 16px;
    border-radius: 50px;
    background: var(--primary-50);
    color: var(--primary);
    font: 700 11px var(--font);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 16px;
    border: 1px solid var(--primary-light);
}
.section-title {
    font: 800 32px var(--font);
    color: var(--gray-900);
    letter-spacing: -0.03em;
    margin-bottom: 12px;
    line-height: 1.2;
}
.section-subtitle {
    font: 400 15px/1.7 var(--font);
    color: var(--gray-500);
    max-width: 560px;
    margin: 0 auto;
}

/* ===== CONTENT WRAPPER ===== */
.main-content {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 32px;
    width: 100%;
}
.content-section {
    padding: 48px 0;
}

/* ===== FEATURE CARDS ===== */
.cards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-bottom: 48px;
}
.feature-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    padding: 32px 24px;
    text-align: center;
    transition: all 0.35s ease;
}
.feature-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--shadow-lg);
    border-color: transparent;
}
.feature-card-icon {
    width: 56px; height: 56px;
    margin: 0 auto 20px;
    display: flex; align-items: center; justify-content: center;
    color: var(--primary);
}
.feature-card h3 {
    font: 700 16px var(--font);
    color: var(--gray-900);
    margin: 0 0 10px;
}
.feature-card p {
    font: 400 13px/1.6 var(--font);
    color: var(--gray-500);
    margin: 0;
}

/* ===== PROCESS TIMELINE ===== */
.process-list {
    max-width: 640px;
    margin: 0 auto 48px;
}
.process-step {
    display: flex;
    align-items: flex-start;
    gap: 20px;
    padding: 24px 0;
    position: relative;
}
.process-step::before {
    content: '';
    position: absolute;
    left: 22px; top: 72px; bottom: 0;
    width: 2px;
    background: var(--primary-light);
}
.process-step:last-child::before { display: none; }
.process-num {
    width: 46px; height: 46px;
    border-radius: 50%;
    flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    background: var(--primary);
    color: #fff;
    font: 700 16px var(--font);
    position: relative;
    z-index: 2;
}
.process-content h3 {
    font: 700 16px var(--font);
    color: var(--gray-900);
    margin: 0 0 6px;
}
.process-content p {
    font: 400 14px/1.6 var(--font);
    color: var(--gray-500);
    margin: 0;
}

/* ===== JOB TABLE ===== */
table.sortable {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}
tr.rowHeading { background: var(--gray-50) !important; }
tr.rowHeading th {
    padding: 14px 24px;
    color: var(--gray-500);
    font: 700 11px var(--font);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    border-bottom: 1px solid var(--gray-200);
    text-align: left;
    background: transparent;
    border: none;
    border-bottom: 1px solid var(--gray-200);
}
tr.evenTableRow, tr.oddTableRow {
    background: #fff !important;
    transition: all 0.25s ease;
    cursor: pointer;
}
tr.evenTableRow:hover, tr.oddTableRow:hover {
    background: var(--primary-50) !important;
}
tr.evenTableRow td, tr.oddTableRow td {
    padding: 18px 24px;
    border-bottom: 1px solid var(--gray-100);
    font: 500 14px var(--font);
    color: var(--gray-600);
    vertical-align: middle;
    border: none;
    border-bottom: 1px solid var(--gray-100);
    background: transparent;
}
tr.evenTableRow:last-child td, tr.oddTableRow:last-child td {
    border-bottom: none;
}
tr.evenTableRow td a, tr.oddTableRow td a {
    color: var(--primary);
    font-weight: 700;
    font-size: 14px;
    -webkit-text-fill-color: var(--primary);
    background: none;
}
tr.evenTableRow td a:hover, tr.oddTableRow td a:hover {
    color: var(--primary-dark);
    -webkit-text-fill-color: var(--primary-dark);
}

/* ===== CTA BANNER ===== */
.cta-banner {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 40%, #1e3a5f 100%);
    border-radius: var(--radius-2xl);
    padding: 48px;
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 32px;
    align-items: center;
    position: relative;
    overflow: hidden;
    margin: 48px 0;
}
.cta-banner * { position: relative; z-index: 2; }
.cta-banner h2 {
    font: 800 24px var(--font);
    color: #fff;
    margin: 0 0 10px;
    -webkit-text-fill-color: #fff;
    border: none;
    padding: 0;
}
.cta-banner p {
    font: 400 14px/1.6 var(--font);
    color: rgba(255,255,255,0.6);
    margin: 0 0 24px;
    max-width: 420px;
}
.cta-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: var(--primary);
    color: #fff !important;
    font: 700 14px var(--font);
    border-radius: var(--radius);
    transition: all 0.25s;
    text-decoration: none !important;
}
.cta-btn:hover {
    background: #3b82f6;
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37,99,235,0.3);
}
.cta-illustration {
    flex-shrink: 0;
}

/* ===== JOB DETAILS ===== */
.job-detail-card {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-2xl);
    padding: 48px;
    box-shadow: var(--shadow-md);
}
.job-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 50px; font: 600 13px var(--font);
    transition: all 0.25s;
}
.job-tag:hover { transform: translateY(-2px); }
.job-tag.location { background: #eff6ff; color: #2563eb; }
.job-tag.type { background: #f0fdf4; color: #059669; }
.job-tag.time { background: #fefce8; color: #ca8a04; }
.job-tag.openings { background: #faf5ff; color: #7c3aed; }
#detailsTable { display: none; }
div#discriptive { float: none; width: 100%; margin: 0; }
div#detailsTools { display: none; }

/* ===== FORM STYLING ===== */
.form-section {
    background: #fff;
    border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl);
    padding: 32px;
    margin-bottom: 24px;
    transition: box-shadow 0.25s ease;
}
.form-section:hover { box-shadow: var(--shadow-md); }
.form-section::before { display: none; }
.form-section h2 {
    font: 700 18px var(--font);
    color: var(--gray-900);
    margin: 0 0 24px;
    border: none;
    padding: 0;
}
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-grid .full { grid-column: 1 / -1; }
label {
    font: 600 13px var(--font);
    color: var(--gray-700);
    display: block;
    margin-bottom: 6px;
}
label .req { color: var(--danger); margin-left: 2px; }

input.inputbox, input.inputBoxName, input.inputBoxNormal, input.inputBoxArea,
input.inputBoxFile, input#documentFile, input[type="text"], input[type="email"], input[type="tel"] {
    width: 100%; padding: 11px 16px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font: 400 14px var(--font);
    color: var(--gray-800);
    transition: all 0.25s ease;
    background: #fff;
    outline: none;
}
input.inputbox:hover, input.inputBoxName:hover, input.inputBoxNormal:hover,
input[type="text"]:hover { border-color: var(--gray-400); }
input.inputbox:focus, input.inputBoxName:focus, input.inputBoxNormal:focus,
input#documentFile:focus, input[type="text"]:focus, input[type="email"]:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

textarea, textarea.inputBoxArea, textarea.inputboxlarge {
    width: 100%; padding: 11px 16px; min-height: 100px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font: 400 14px var(--font);
    color: var(--gray-800);
    resize: vertical; transition: all 0.25s;
    background: #fff; outline: none;
}
textarea:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

select, select.inputBoxNormal {
    width: 100%; padding: 11px 16px;
    border: 1px solid var(--gray-300);
    border-radius: var(--radius);
    font: 400 14px var(--font);
    color: var(--gray-800);
    background: #fff; outline: none; cursor: pointer;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 36px;
    transition: all 0.25s;
}
select:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}

input.submitbutton, input.submitButton, input[type="submit"] {
    padding: 14px 40px;
    width: auto; min-width: 240px;
    background: var(--primary);
    color: #fff;
    font: 700 15px var(--font);
    border: none;
    border-radius: var(--radius);
    cursor: pointer;
    transition: all 0.25s;
    box-shadow: 0 4px 16px rgba(37,99,235,0.25);
}
input.submitbutton:hover, input[type="submit"]:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(37,99,235,0.3);
}

.progress-bar-bg { width: 100%; height: 4px; background: var(--gray-200); border-radius: 2px; overflow: hidden; margin-bottom: 24px; }
.progress-bar-fill { height: 100%; width: 0%; background: var(--primary); border-radius: 2px; transition: width 0.5s ease; }

div.applyBoxLeft, div.applyBoxRight { float:none; width:100%; max-width:100%; border:none; box-shadow:none; padding:0; background:transparent; margin:0; }
div.applyBoxLeft div, div.applyBoxRight div { display:none; }
div.applyBoxLeft table, div.applyBoxRight table { display:none; }
td.label { display:none; }

/* ===== V4 APPLY PAGE CLASSES ===== */
.step-indicator {
    display: flex; align-items: center; gap: 0;
    padding: 24px 0; border-bottom: 1px solid var(--gray-200);
    margin-bottom: 32px; overflow-x: auto;
}
.step-item { display: flex; align-items: center; gap: 10px; white-space: nowrap; flex: 1; }
.step-item:not(:last-child)::after {
    content: ''; flex: 1; height: 1px;
    background: var(--gray-200); margin: 0 16px; min-width: 24px;
}
.step-number {
    width: 32px; height: 32px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font: 600 13px var(--font); flex-shrink: 0;
}
.step-number.active { background: var(--primary); color: #fff; }
.step-number.inactive { background: var(--gray-100); color: var(--gray-500); border: 1.5px solid var(--gray-300); }
.step-number.completed { background: var(--success); color: #fff; }
.step-label { font: 500 14px var(--font); color: var(--gray-700); }
.step-label.inactive { color: var(--gray-400); }

.apply-layout { display: grid; grid-template-columns: 1fr 340px; gap: 32px; align-items: start; }
.progress-section { display: flex; align-items: center; gap: 12px; margin-bottom: 8px; }
.progress-label { font: 500 13px var(--font); color: var(--gray-500); white-space: nowrap; }

.form-section-header { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 24px; }
.form-section-icon {
    width: 40px; height: 40px; background: var(--primary-50); border-radius: 10px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.form-section-icon svg { color: var(--primary); }
.form-section-title h2 { font: 600 16px var(--font); color: var(--gray-900); margin-bottom: 2px; }
.form-section-title p { font: 400 13px var(--font); color: var(--gray-500); margin: 0; }

.file-upload-area {
    border: 2px dashed var(--gray-300); border-radius: var(--radius-lg);
    padding: 32px 24px; text-align: center;
    transition: all 0.2s; cursor: pointer; background: var(--gray-50);
}
.file-upload-area:hover { border-color: var(--primary); background: var(--primary-50); }
.file-upload-area .upload-icon { margin-bottom: 8px; color: var(--gray-400); }
.file-upload-area p { font: 400 14px var(--font); color: var(--gray-500); margin: 0 0 4px; }
.file-upload-area .or-text { font: 400 13px var(--font); color: var(--gray-400); margin: 4px 0 12px; }
.choose-file-btn {
    display: inline-block; padding: 8px 20px;
    background: var(--primary); color: #fff;
    font: 600 13px var(--font); border-radius: 6px;
    cursor: pointer; transition: all 0.2s; border: none;
}
.choose-file-btn:hover { background: var(--primary-dark); }
.file-types { font: 400 12px var(--font); color: var(--gray-400); margin-top: 12px; }
input.inputBoxFile, input[type="file"] { display: none; }

.form-actions { display: flex; align-items: center; justify-content: space-between; padding: 24px 0 48px; }
.btn-draft {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 24px; border: 1px solid var(--gray-300);
    border-radius: var(--radius); background: #fff;
    color: var(--gray-700); font: 600 14px var(--font);
    cursor: pointer; transition: all 0.2s;
}
.btn-draft:hover { background: var(--gray-50); border-color: var(--gray-400); }
.btn-submit {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 32px; background: var(--primary);
    color: #fff; font: 600 14px var(--font);
    border: none; border-radius: var(--radius);
    cursor: pointer; transition: all 0.2s;
}
.btn-submit:hover { background: var(--primary-dark); box-shadow: 0 4px 12px rgba(37,99,235,0.25); transform: translateY(-1px); }

.apply-sidebar { position: sticky; top: 80px; }
.summary-card {
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl); padding: 24px;
}
.summary-card h3 { font: 600 16px var(--font); color: var(--gray-900); margin-bottom: 4px; }
.summary-card .summary-subtitle { font: 400 13px var(--font); color: var(--gray-500); margin-bottom: 20px; }
.summary-item { display: flex; align-items: flex-start; gap: 12px; padding: 12px 0; }
.summary-item:not(:last-child) { border-bottom: 1px solid var(--gray-100); }
.summary-dot { width: 20px; height: 20px; border-radius: 50%; border: 2px solid var(--gray-300); flex-shrink: 0; margin-top: 2px; }
.summary-dot.completed { border-color: var(--success); background: var(--success); }
.summary-dot.active { border-color: var(--primary); background: var(--primary); }
.summary-item-text h4 { font: 500 14px var(--font); color: var(--gray-700); margin: 0 0 2px; }
.summary-item-text p { font: 400 12px var(--font); color: var(--gray-400); margin: 0; }

.tip-box {
    margin-top: 20px; padding: 16px;
    background: var(--primary-50); border-radius: var(--radius-lg);
    border: 1px solid var(--primary-light);
}
.tip-box .tip-header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.tip-box .tip-header svg { color: var(--primary); }
.tip-box .tip-header span { font: 600 13px var(--font); color: var(--primary-dark); }
.tip-box p { font: 400 13px/1.5 var(--font); color: var(--primary-dark); margin: 0; }

/* ===== FOOTER ===== */
.career-footer {
    background: var(--primary);
    margin-top: auto;
}
.footer-inner {
    max-width: 1200px; margin: 0 auto;
    padding: 0 32px;
    display: flex; align-items: center; justify-content: space-between;
    height: 56px;
}
.footer-inner .brand-text { color: #fff; font: 600 15px var(--font); }
.footer-inner .brand-text span { font-weight: 400; color: rgba(255,255,255,0.8); }
.footer-inner a { color: rgba(255,255,255,0.9) !important; font: 500 14px var(--font); }
.footer-inner a:hover { color: #fff !important; }
.career-copyright {
    background: var(--gray-50);
    text-align: center;
    padding: 16px 32px;
    font: 400 13px var(--font);
    color: var(--gray-400);
    border-top: 1px solid var(--gray-200);
}

/* ===== GENERIC ===== */
h1 { font: 800 32px var(--font); color: var(--gray-900); letter-spacing: -0.03em; margin: 0 0 12px; background: none; -webkit-text-fill-color: var(--gray-900); }
h2 { font: 700 24px var(--font); color: var(--gray-800); margin: 0 0 12px; border: none; padding: 0; }
h3 { font: 600 16px var(--font); color: var(--gray-700); margin: 0 0 6px; }
p { font: 400 14px/1.7 var(--font); color: var(--gray-500); }
strong { font-weight: 700; color: var(--gray-800); }
#careerContent { clear: both; padding: 0; flex: 1; }
#poweredCATS { display: none; }
.clearfix::after { content:''; display:table; clear:both; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .build-section { grid-template-columns: 1fr; padding: 40px 24px; }
    .build-illustration { display: none; }
    .cards-grid { grid-template-columns: repeat(2, 1fr); }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
    .apply-layout { grid-template-columns: 1fr; }
    .apply-sidebar { position: static; }
    .cta-banner { grid-template-columns: 1fr; }
    .cta-illustration { display: none; }
}
@media (max-width: 600px) {
    .hero-banner { padding: 40px 20px 70px; }
    .hero-banner h1 { font-size: 28px !important; }
    .cards-grid { grid-template-columns: 1fr; }
    .stats-row { grid-template-columns: repeat(2, 1fr); }
    .form-grid { grid-template-columns: 1fr; }
    .form-section { padding: 20px; }
    .form-actions { flex-direction: column; gap: 12px; }
    .step-label { display: none; }
    .header-inner { padding: 0 16px; }
    .main-content { padding: 0 16px; }
    .footer-inner { padding: 0 16px; }
    .build-text h2 { font-size: 26px; }
    .section-title { font-size: 24px; }
    .process-step { gap: 14px; }
    .cta-banner { padding: 32px 24px; }
}
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
            <div class="brand-text">default_site<span>Careers</span></div>
        </a>
        <nav class="header-nav">
            <a href="index.php?m=careers&p=showAll">All Jobs</a>
        </nav>
    </div>
</div>
TPL;
$stmt->execute(['val' => $header, 'setting' => 'Header']);
echo "Header updated\n";


// =====================================================
// CONTENT - MAIN
// =====================================================
$main = <<<'TPL'
<script>window.location.replace('index.php?m=careers&p=showAll');</script>
<div style="text-align:center;padding:120px 24px;"><p>Redirecting...</p></div>
TPL;
$stmt->execute(['val' => $main, 'setting' => 'Content - Main']);
echo "Content - Main updated\n";


// =====================================================
// CONTENT - SEARCH RESULTS (Job Listing Page)
// =====================================================
$search = <<<'TPL'
<!-- Hero Banner -->
<div class="hero-banner">
    <h1>Join Our Team</h1>
    <p>Explore open positions and find your next opportunity at default_site.</p>
</div>

<!-- Build Something Section -->
<div class="build-section">
    <div class="build-text reveal">
        <div class="hiring-badge"><span class="dot"></span> Now Hiring &mdash; Multiple Openings</div>
        <h2>Build Something<br>Extraordinary with <span class="typing-text"></span><span class="typing-cursor"></span></h2>
        <p>Join a team that turns ambitious ideas into products that shape industries. Your talent, our mission &mdash; let&rsquo;s build the future together.</p>
        <div class="build-cta">
            <a href="#positions">View Open Roles &rarr;</a>
            <a href="#culture">Why Neutara &rarr;</a>
        </div>
    </div>
    <div class="build-illustration reveal">
        <svg viewBox="0 0 500 400" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Background card/screen -->
            <rect x="160" y="40" width="220" height="280" rx="20" fill="#EFF6FF" stroke="#DBEAFE" stroke-width="2"/>
            <rect x="180" y="70" width="180" height="24" rx="6" fill="#DBEAFE"/>
            <rect x="180" y="106" width="140" height="16" rx="4" fill="#E2E8F0"/>
            <rect x="180" y="130" width="160" height="16" rx="4" fill="#E2E8F0"/>
            <rect x="180" y="154" width="120" height="16" rx="4" fill="#E2E8F0"/>
            <rect x="180" y="190" width="180" height="80" rx="10" fill="#DBEAFE"/>
            <!-- Checkmark badge -->
            <circle cx="340" cy="100" r="36" fill="#2563EB"/>
            <circle cx="340" cy="100" r="28" fill="#3B82F6"/>
            <path d="M326 100L336 110L356 90" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            <!-- Person sitting -->
            <!-- Chair -->
            <ellipse cx="180" cy="350" rx="60" ry="10" fill="#E2E8F0"/>
            <rect x="130" y="260" width="100" height="80" rx="16" fill="#93C5FD"/>
            <!-- Body -->
            <rect x="150" y="210" width="60" height="70" rx="12" fill="#60A5FA"/>
            <!-- Head -->
            <circle cx="180" cy="190" r="28" fill="#FCD34D"/>
            <circle cx="172" cy="185" r="3" fill="#1E293B"/>
            <circle cx="190" cy="185" r="3" fill="#1E293B"/>
            <path d="M175 197Q180 202 186 197" stroke="#1E293B" stroke-width="2" stroke-linecap="round"/>
            <!-- Hair -->
            <path d="M152 185Q155 155 180 158Q205 155 208 185" stroke="#1E293B" stroke-width="3" fill="#1E293B" opacity="0.8"/>
            <!-- Laptop -->
            <rect x="195" y="248" width="90" height="55" rx="6" fill="#1E40AF"/>
            <rect x="200" y="253" width="80" height="42" rx="4" fill="#60A5FA"/>
            <rect x="185" y="303" width="110" height="6" rx="3" fill="#94A3B8"/>
            <!-- Arms -->
            <rect x="190" y="235" width="40" height="12" rx="6" fill="#FCD34D"/>
            <rect x="155" y="235" width="40" height="12" rx="6" fill="#FCD34D"/>
            <!-- Decorative dots -->
            <circle cx="420" cy="60" r="4" fill="#DBEAFE"/>
            <circle cx="440" cy="60" r="4" fill="#DBEAFE"/>
            <circle cx="460" cy="60" r="4" fill="#DBEAFE"/>
            <circle cx="420" cy="80" r="4" fill="#DBEAFE"/>
            <circle cx="440" cy="80" r="4" fill="#DBEAFE"/>
            <circle cx="460" cy="80" r="4" fill="#DBEAFE"/>
            <!-- Floating elements -->
            <circle cx="80" cy="120" r="16" fill="#DBEAFE" opacity="0.6"/>
            <circle cx="430" cy="300" r="12" fill="#DBEAFE" opacity="0.6"/>
            <rect x="60" y="260" width="30" height="30" rx="6" fill="#EFF6FF" stroke="#DBEAFE" stroke-width="1.5" transform="rotate(-12 75 275)"/>
        </svg>
    </div>
</div>

<!-- Stats Row -->
<div class="stats-row reveal">
    <div class="stat-item">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <span class="stat-num counter" data-target="10" data-suffix="+">0</span>
        <span class="stat-label">Team Members</span>
    </div>
    <div class="stat-item">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
        </div>
        <span class="stat-num counter" data-target="12" data-suffix="+">0</span>
        <span class="stat-label">Countries</span>
    </div>
    <div class="stat-item">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        </div>
        <span class="stat-num counter" data-target="98" data-suffix="%">0</span>
        <span class="stat-label">Satisfaction</span>
    </div>
    <div class="stat-item">
        <div class="stat-icon">
            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <span class="stat-num">4.8&#9733;</span>
        <span class="stat-label">Glassdoor</span>
    </div>
</div>

<div class="main-content" id="culture">
    <!-- Why Neutara -->
    <div class="content-section">
        <div class="section-center reveal">
            <span class="section-tag">&#10024; Why Neutara</span>
            <h2 class="section-title">Where Great Careers Are Built</h2>
            <p class="section-subtitle">We don&rsquo;t just offer jobs &mdash; we create a launchpad for extraordinary careers with world-class benefits and culture.</p>
        </div>
        <div class="cards-grid stagger-children">
            <div class="feature-card">
                <div class="feature-card-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <h3>Continuous Learning</h3>
                <p>$5K annual learning budget, conference sponsorships, and access to premium education platforms for every team member.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
                </div>
                <h3>Rapid Career Growth</h3>
                <p>Clear advancement paths with quarterly performance reviews and dedicated 1-on-1 mentorship from senior leaders.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                </div>
                <h3>Remote First Culture</h3>
                <p>Work from anywhere in the world. We believe great work happens when you have the freedom to choose your environment.</p>
            </div>
            <div class="feature-card">
                <div class="feature-card-icon">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                </div>
                <h3>Premium Benefits</h3>
                <p>Top-tier health coverage, equity packages, unlimited PTO, parental leave, and comprehensive family-friendly policies.</p>
            </div>
        </div>
    </div>

    <!-- Process -->
    <div class="content-section">
        <div class="section-center reveal">
            <span class="section-tag">&#9881; Our Process</span>
            <h2 class="section-title">Simple &amp; Transparent Hiring</h2>
            <p class="section-subtitle">We respect your time. Our streamlined process takes 2&ndash;3 weeks from application to offer.</p>
        </div>
        <div class="process-list stagger-children">
            <div class="process-step">
                <div class="process-num">1</div>
                <div class="process-content">
                    <h3>Submit Application</h3>
                    <p>Apply online in under 5 minutes. Upload your resume and tell us why you&rsquo;re excited about the role.</p>
                </div>
            </div>
            <div class="process-step">
                <div class="process-num">2</div>
                <div class="process-content">
                    <h3>Technical Assessment</h3>
                    <p>A take-home or live assessment designed to showcase your skills &mdash; no trick questions, just real work.</p>
                </div>
            </div>
            <div class="process-step">
                <div class="process-num">3</div>
                <div class="process-content">
                    <h3>Team Interview</h3>
                    <p>Meet the team you&rsquo;d work with. We focus on collaboration, communication, and culture alignment.</p>
                </div>
            </div>
            <div class="process-step">
                <div class="process-num">4</div>
                <div class="process-content">
                    <h3>Welcome Aboard</h3>
                    <p>Receive your offer and start your journey. We make onboarding smooth and exciting from day one.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Open Roles -->
    <div class="content-section" id="positions" style="scroll-margin-top:80px;">
        <div class="section-center reveal">
            <span class="section-tag">&#128188; Open Roles</span>
            <h2 class="section-title">Find Your Perfect Role</h2>
            <p class="section-subtitle"><numberOfSearchResults> position(s) currently available &mdash; each one an opportunity to make a real impact.</p>
        </div>
        <div class="reveal">
            <searchResultsTableUnformatted>
        </div>
    </div>

    <!-- CTA Banner -->
    <div class="cta-banner reveal">
        <div>
            <div style="width:44px;height:44px;background:rgba(37,99,235,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#60A5FA" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <h2>Don&rsquo;t See Your Dream Role?</h2>
            <p>We&rsquo;re always looking for exceptional talent. Send us your resume and we&rsquo;ll reach out when the right opportunity opens.</p>
            <a href="index.php?m=careers&p=showAll" class="cta-btn">
                Stay Connected &rarr;
            </a>
        </div>
        <div class="cta-illustration">
            <svg width="200" height="160" viewBox="0 0 200 160" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Document stack -->
                <rect x="50" y="30" width="100" height="120" rx="10" fill="#1E3A5F" stroke="#2563EB" stroke-width="1"/>
                <rect x="60" y="20" width="100" height="120" rx="10" fill="#1E4D7B" stroke="#2563EB" stroke-width="1"/>
                <rect x="70" y="10" width="100" height="120" rx="10" fill="#fff" stroke="#DBEAFE" stroke-width="1.5"/>
                <!-- Document lines -->
                <rect x="85" y="30" width="70" height="8" rx="3" fill="#DBEAFE"/>
                <rect x="85" y="46" width="55" height="6" rx="3" fill="#E2E8F0"/>
                <rect x="85" y="58" width="60" height="6" rx="3" fill="#E2E8F0"/>
                <rect x="85" y="70" width="45" height="6" rx="3" fill="#E2E8F0"/>
                <rect x="85" y="86" width="70" height="24" rx="6" fill="#EFF6FF"/>
                <!-- Checkmark on doc -->
                <circle cx="155" cy="35" r="16" fill="#2563EB"/>
                <path d="M148 35L153 40L163 30" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                <!-- Stars -->
                <path d="M30 50L33 43L36 50L30 50Z" fill="#FBBF24" opacity="0.8"/>
                <path d="M170 80L173 73L176 80L170 80Z" fill="#FBBF24" opacity="0.6"/>
                <circle cx="25" cy="100" r="4" fill="#60A5FA" opacity="0.4"/>
                <circle cx="180" cy="120" r="3" fill="#60A5FA" opacity="0.4"/>
                <!-- Arrow/send -->
                <path d="M140 95L165 80L140 65" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" fill="none" opacity="0.5"/>
            </svg>
        </div>
    </div>
</div>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-loaded');

        // Scroll reveals
        var els = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            var observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
            els.forEach(function(el) { observer.observe(el); });
        } else {
            els.forEach(function(el) { el.classList.add('revealed'); });
        }

        // Stagger children
        var staggerContainers = document.querySelectorAll('.stagger-children');
        if ('IntersectionObserver' in window) {
            staggerContainers.forEach(function(container) {
                var obs = new IntersectionObserver(function(entries) {
                    if (entries[0].isIntersecting) {
                        var children = container.children;
                        for (var i = 0; i < children.length; i++) {
                            (function(idx) {
                                setTimeout(function() {
                                    children[idx].classList.add('stagger-visible');
                                }, idx * 120);
                            })(i);
                        }
                        obs.unobserve(container);
                    }
                }, { threshold: 0.1 });
                obs.observe(container);
            });
        }

        // Typing animation
        var typingEl = document.querySelector('.typing-text');
        if (typingEl) {
            var words = ['Innovation', 'Passion', 'Purpose', 'Excellence'];
            var wordIdx = 0, charIdx = 0, deleting = false;
            function typeStep() {
                var word = words[wordIdx];
                if (!deleting) {
                    typingEl.textContent = word.substring(0, charIdx + 1);
                    charIdx++;
                    if (charIdx === word.length) {
                        deleting = true;
                        setTimeout(typeStep, 2000);
                        return;
                    }
                    setTimeout(typeStep, 80);
                } else {
                    typingEl.textContent = word.substring(0, charIdx - 1);
                    charIdx--;
                    if (charIdx === 0) {
                        deleting = false;
                        wordIdx = (wordIdx + 1) % words.length;
                        setTimeout(typeStep, 400);
                        return;
                    }
                    setTimeout(typeStep, 40);
                }
            }
            setTimeout(typeStep, 800);
        }

        // Counter animation
        var counters = document.querySelectorAll('.counter');
        counters.forEach(function(el) {
            var target = parseInt(el.getAttribute('data-target')) || 0;
            var suffix = el.getAttribute('data-suffix') || '';
            if (!target) return;
            el.textContent = '0' + suffix;
            var counterObs = new IntersectionObserver(function(entries) {
                if (entries[0].isIntersecting) {
                    var start = null;
                    var duration = 1200;
                    function animate(ts) {
                        if (!start) start = ts;
                        var progress = Math.min((ts - start) / duration, 1);
                        var eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent = Math.floor(eased * target) + suffix;
                        if (progress < 1) requestAnimationFrame(animate);
                    }
                    requestAnimationFrame(animate);
                    counterObs.unobserve(el);
                }
            }, { threshold: 0.5 });
            counterObs.observe(el);
        });

        // Header scroll effect
        var header = document.querySelector('.career-header');
        var lastScroll = 0;
        window.addEventListener('scroll', function() {
            var y = window.scrollY;
            if (y > 200 && y > lastScroll) {
                header.style.transform = 'translateY(-100%)';
                header.style.transition = 'transform 0.3s ease';
            } else {
                header.style.transform = 'translateY(0)';
            }
            lastScroll = y;
        });
    });
})();
</script>
TPL;
$stmt->execute(['val' => $search, 'setting' => 'Content - Search Results']);
echo "Content - Search Results updated\n";


// =====================================================
// JOB DETAILS
// =====================================================
$jobDetails = <<<'TPL'
<div style="max-width:900px;margin:0 auto;padding:32px;">
    <div style="margin-bottom:24px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-400);display:inline-flex;align-items:center;gap:6px;padding:8px 18px;border-radius:var(--radius);background:#fff;border:1px solid var(--gray-200);font-weight:600;transition:all 0.25s;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            All Positions
        </a>
    </div>

    <div class="job-detail-card reveal">
        <h1 style="font-size:32px;margin-bottom:16px;"><title></h1>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:32px;">
            <span class="job-tag location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <city>, <state>
            </span>
            <span class="job-tag type">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                <type>
            </span>
            <span class="job-tag time">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Posted <daysOld> days ago
            </span>
            <span class="job-tag openings">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
                <openings> opening(s)
            </span>
        </div>

        <div style="margin-bottom:40px;">
            <a-applyToJob style="display:inline-flex;align-items:center;gap:8px;padding:14px 36px;background:var(--primary);color:#fff !important;font:700 15px var(--font);border-radius:var(--radius);box-shadow:0 4px 16px rgba(37,99,235,0.25);transition:all 0.25s;">
                Apply Now
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div style="border-top:1px solid var(--gray-200);padding-top:32px;">
            <h2 style="font-size:22px;margin:0 0 20px;">About This Role</h2>
            <div style="color:var(--gray-600);line-height:1.9;font-size:15px;"><description></div>
        </div>

        <div style="margin-top:40px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Location</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><city>, <state></div>
            </div>
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Type</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><type></div>
            </div>
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Recruiter</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><recruiter></div>
            </div>
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;">
                <div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Posted</div>
                <div style="font:700 14px var(--font);color:var(--gray-800);"><created></div>
            </div>
        </div>

        <div style="margin-top:40px;text-align:center;padding:40px;background:var(--primary-50);border-radius:var(--radius-xl);">
            <h3 style="font-size:20px;margin:0 0 10px;color:var(--gray-900);">Ready to Make an Impact?</h3>
            <p style="margin:0 0 20px;color:var(--gray-500);">Your next chapter starts with a single click.</p>
            <a-applyToJob style="display:inline-flex;align-items:center;gap:8px;padding:14px 36px;background:var(--primary);color:#fff !important;font:700 15px var(--font);border-radius:var(--radius);box-shadow:0 4px 16px rgba(37,99,235,0.25);transition:all 0.25s;">
                Submit Your Application &rarr;
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.body.classList.add('page-loaded');
    var els = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } });
        }, { threshold: 0.1 });
        els.forEach(function(el) { obs.observe(el); });
    } else { els.forEach(function(el) { el.classList.add('revealed'); }); }
});
</script>
TPL;
$stmt->execute(['val' => $jobDetails, 'setting' => 'Content - Job Details']);
echo "Job Details updated\n";


// =====================================================
// APPLY FOR POSITION — V4 layout with V6 styling
// =====================================================
$apply = <<<'TPL'
<div style="max-width:1100px;margin:0 auto;padding:0 32px;">
    <div style="padding-top:24px;margin-bottom:16px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-500);display:inline-flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Jobs
        </a>
    </div>

    <div class="reveal" style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
        <div>
            <h1 style="font-size:26px;margin-bottom:4px;">Apply for <title></h1>
            <p style="color:var(--gray-500);margin:0;font-size:14px;">Fields marked <span style="color:var(--danger);">*</span> are required.</p>
        </div>
        <div style="text-align:right;min-width:180px;">
            <div class="progress-section">
                <span class="progress-label" id="formProgressLabel">0% complete</span>
            </div>
            <div class="progress-bar-bg" style="margin-top:6px;">
                <div class="progress-bar-fill" id="formProgress"></div>
            </div>
        </div>
    </div>

    <div class="step-indicator reveal">
        <div class="step-item"><div class="step-number active">1</div><span class="step-label">Personal Information</span></div>
        <div class="step-item"><div class="step-number inactive">2</div><span class="step-label inactive">Professional Background</span></div>
        <div class="step-item"><div class="step-number inactive">3</div><span class="step-label inactive">Resume & Documents</span></div>
        <div class="step-item"><div class="step-number inactive">4</div><span class="step-label inactive">Review & Submit</span></div>
    </div>

    <div class="apply-layout">
        <div>
            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <div class="form-section-title"><h2>Personal Information</h2><p>Tell us who you are.</p></div>
                </div>
                <div class="form-grid">
                    <div><label>First Name <span class="req">*</span></label><input-firstName req></div>
                    <div><label>Last Name <span class="req">*</span></label><input-lastName req></div>
                    <div><label>Email <span class="req">*</span></label><input-email req></div>
                    <div><label>Confirm Email <span class="req">*</span></label><input-emailconfirm req></div>
                    <div><label>Phone <span class="req">*</span></label><input-phone req></div>
                    <div><label>City <span class="req">*</span></label><input-city req></div>
                    <div><label>State <span class="req">*</span></label><input-state req></div>
                    <div><label>Zip Code <span class="req">*</span></label><input-zip req></div>
                    <div class="full"><label>Address <span class="req">*</span></label><input-address req></div>
                </div>
            </div>

            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:#f0fdf4;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg></div>
                    <div class="form-section-title"><h2>Professional Background</h2><p>Help us understand your experience.</p></div>
                </div>
                <div class="form-grid">
                    <div><label>Key Skills <span class="req">*</span></label><input-keySkills req></div>
                    <div><label>Current Employer</label><input-employer></div>
                    <div class="full"><label>How did you hear about us? <span class="req">*</span></label><input-source req></div>
                    <div class="full"><label>Additional Information</label><input-extraNotes></div>
                </div>
            </div>

            <div class="form-section reveal">
                <div class="form-section-header">
                    <div class="form-section-icon" style="background:#eff6ff;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div>
                    <div class="form-section-title"><h2>Resume & Documents</h2><p>Upload your resume.</p></div>
                </div>
                <div>
                    <label>Upload Resume <span class="req">*</span></label>
                    <div class="file-upload-area" onclick="document.getElementById('resume') ? document.getElementById('resume').click() : (document.getElementById('resumeFile') ? document.getElementById('resumeFile').click() : null)">
                        <div class="upload-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div>
                        <p>Drag and drop your file here</p>
                        <div class="or-text">or</div>
                        <span class="choose-file-btn">Choose File</span>
                    </div>
                    <input-resumeUpload>
                    <div class="file-types">PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</div>
                </div>
                <div style="margin-top:20px;"><label>Cover Letter / Notes</label><input-extraNotes></div>
            </div>

            <div class="form-actions reveal">
                <button type="button" class="btn-draft" onclick="alert('Draft saved!');">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
                    Save Draft
                </button>
                <submit value="Save & Continue →" class="btn-submit">
            </div>
        </div>

        <div class="apply-sidebar">
            <div class="summary-card reveal">
                <h3>Application Summary</h3>
                <p class="summary-subtitle">Review your progress</p>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Personal Information</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Professional Background</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Resume & Documents</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Review & Submit</h4><p>Not started</p></div></div>
                <div class="tip-box">
                    <div class="tip-header"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg><span>Tip</span></div>
                    <p>Complete all sections to increase your chances of getting noticed by our team.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    function updateProgress() {
        var inputs = document.querySelectorAll('#applyToJobForm input[type="text"], #applyToJobForm input[type="email"], #applyToJobForm input[type="tel"], #applyToJobForm textarea, #applyToJobForm select');
        var filled = 0, total = 0;
        inputs.forEach(function(input) {
            if (input.type !== 'hidden' && input.offsetParent !== null) { total++; if (input.value && input.value.trim() !== '') filled++; }
        });
        var pct = total > 0 ? Math.round((filled / total) * 100) : 0;
        var bar = document.getElementById('formProgress');
        var label = document.getElementById('formProgressLabel');
        if (bar) bar.style.width = pct + '%';
        if (label) label.textContent = pct + '% complete';
        var dots = document.querySelectorAll('.summary-dot');
        var statuses = document.querySelectorAll('.summary-item-text p');
        var s1 = ['firstName','lastName','email','phone'].filter(function(id) { var el = document.getElementById(id); return el && el.value.trim(); }).length;
        if (s1 >= 4) { dots[0].style.borderColor = '#16a34a'; dots[0].style.background = '#16a34a'; statuses[0].textContent = 'Completed'; }
        else if (s1 > 0) { dots[0].style.borderColor = '#2563eb'; dots[0].style.background = '#2563eb'; statuses[0].textContent = 'In progress'; }
        var s2 = ['keySkills','source'].filter(function(id) { var el = document.getElementById(id); return el && el.value.trim(); }).length;
        if (s2 >= 2) { dots[1].style.borderColor = '#16a34a'; dots[1].style.background = '#16a34a'; statuses[1].textContent = 'Completed'; }
        else if (s2 > 0) { dots[1].style.borderColor = '#2563eb'; dots[1].style.background = '#2563eb'; statuses[1].textContent = 'In progress'; }
        var fileEl = document.getElementById('resume') || document.getElementById('resumeFile');
        if (fileEl && fileEl.value) { dots[2].style.borderColor = '#16a34a'; dots[2].style.background = '#16a34a'; statuses[2].textContent = 'Completed'; }
        var steps = document.querySelectorAll('.step-number');
        if (s1 >= 4 && steps[0]) { steps[0].className = 'step-number completed'; steps[0].innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>'; }
        if (s2 >= 2 && steps[1]) { steps[1].className = 'step-number completed'; steps[1].innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>'; }
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-loaded');
        setInterval(updateProgress, 500);
        var els = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function(entries) { entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } }); }, { threshold: 0.1 });
            els.forEach(function(el) { obs.observe(el); });
        } else { els.forEach(function(el) { el.classList.add('revealed'); }); }
        var ph = { 'firstName':'Enter your first name','lastName':'Enter your last name','email':'Enter your email','emailconfirm':'Re-enter email','phone':'Phone number','city':'City','zip':'Zip code','address':'Full address','keySkills':'Key skills (comma separated)','employer':'Current employer','extraNotes':'Additional info' };
        Object.keys(ph).forEach(function(id) { var el = document.getElementById(id); if (el) el.placeholder = ph[id]; });
    });
})();
</script>
TPL;
$stmt->execute(['val' => $apply, 'setting' => 'Content - Apply for Position']);
echo "Apply page updated\n";


// =====================================================
// THANKS
// =====================================================
$thanks = <<<'TPL'
<div style="max-width:560px;margin:0 auto;text-align:center;padding:80px 24px;">
    <div class="reveal">
        <div style="width:72px;height:72px;background:var(--success-light);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:20px;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1 style="font-size:24px;margin-bottom:8px;">Application Submitted!</h1>
        <p style="font-size:15px;color:var(--gray-500);max-width:380px;margin:0 auto 24px;">Thank you for applying. We have received your application and will review it shortly.</p>
        <a href="index.php?m=careers&p=showAll" style="display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:var(--primary);color:#fff !important;font:700 14px var(--font);border-radius:var(--radius);transition:all 0.25s;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Positions
        </a>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded',function(){document.body.classList.add('page-loaded');var e=document.querySelectorAll('.reveal');e.forEach(function(el){el.classList.add('revealed');});});</script>
TPL;
$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Thanks page updated\n";


// =====================================================
// FOOTER
// =====================================================
$footer = <<<'TPL'
<div class="career-footer">
    <div class="footer-inner">
        <div style="display:flex;align-items:center;gap:10px;">
            <div class="brand-icon">N</div>
            <div class="brand-text">default_site<span>Careers</span></div>
        </div>
        <a href="index.php?m=careers&p=showAll">All Jobs</a>
    </div>
</div>
<div class="career-copyright">
    &copy; 2026 default_site &mdash; Powered by Neutara ATS
</div>
TPL;
$stmt->execute(['val' => $footer, 'setting' => 'Footer']);
echo "Footer updated\n";


echo "\n=== Career Portal V6 Deployed! ===\n";
echo "Visit: http://localhost:8000/careers/index.php?m=careers&p=showAll\n";
