<?php
$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");


// =====================================================
// CSS - Premium with 3D, glassmorphism, animations
// =====================================================
$css = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

:root {
    --primary: #2563eb;
    --primary-dark: #1d4ed8;
    --primary-darker: #1e3a8a;
    --primary-light: #dbeafe;
    --primary-50: #eff6ff;
    --accent: #06b6d4;
    --accent-dark: #0891b2;
    --violet: #7c3aed;
    --emerald: #059669;
    --emerald-light: #d1fae5;
    --amber: #d97706;
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
    --radius: 12px;
    --radius-lg: 20px;
    --radius-xl: 28px;
    --glass: rgba(255,255,255,0.7);
    --glass-border: rgba(255,255,255,0.3);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }
body, html {
    font-family: var(--font);
    color: var(--gray-800);
    background: #f0f4f8;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    overflow-x: hidden;
}
body {
    display: flex; flex-direction: column; min-height: 100vh;
    opacity: 0; transition: opacity 0.6s ease;
}
body.page-loaded { opacity: 1; }

a { color: var(--primary); text-decoration: none; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
a:hover { color: var(--primary-dark); }
a:visited { color: var(--primary); }
img { border: none; }

/* ===== REVEAL ANIMATIONS ===== */
.reveal {
    opacity: 0; transform: translateY(40px);
    transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1);
}
.reveal.revealed { opacity: 1; transform: translateY(0); }
.reveal-left {
    opacity: 0; transform: translateX(-60px);
    transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1);
}
.reveal-left.revealed { opacity: 1; transform: translateX(0); }
.reveal-right {
    opacity: 0; transform: translateX(60px);
    transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1);
}
.reveal-right.revealed { opacity: 1; transform: translateX(0); }
.reveal-scale {
    opacity: 0; transform: scale(0.85);
    transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1);
}
.reveal-scale.revealed { opacity: 1; transform: scale(1); }

/* Stagger children */
.stagger-children > * {
    opacity: 0; transform: translateY(30px);
    transition: opacity 0.6s cubic-bezier(0.22,1,0.36,1), transform 0.6s cubic-bezier(0.22,1,0.36,1);
}
.stagger-children > *.stagger-visible { opacity: 1; transform: translateY(0); }

/* ===== HEADER - Glassmorphism ===== */
.career-header {
    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255,255,255,0.5);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    padding: 0;
}
.career-header.scrolled {
    background: rgba(255,255,255,0.95);
    box-shadow: 0 8px 32px rgba(0,0,0,0.08), 0 2px 8px rgba(0,0,0,0.04);
    border-bottom-color: rgba(0,0,0,0.06);
}
.header-inner {
    max-width: 1280px; margin: 0 auto;
    padding: 14px 32px;
    display: flex; align-items: center; justify-content: space-between;
}
.brand { display: flex; align-items: center; gap: 14px; text-decoration: none; }
.brand-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--violet) 100%);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 900; font-size: 20px;
    box-shadow: 0 4px 16px rgba(37,99,235,0.35);
    transition: transform 0.3s, box-shadow 0.3s;
}
.brand:hover .brand-icon { transform: rotate(-5deg) scale(1.05); box-shadow: 0 6px 24px rgba(37,99,235,0.4); }
.brand-text { font-size: 22px; font-weight: 800; color: var(--gray-900); letter-spacing: -0.03em; }
.brand-text span { background: linear-gradient(135deg, var(--primary), var(--violet)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.header-nav { display: flex; align-items: center; gap: 8px; }
.header-nav a {
    font-size: 14px; font-weight: 500; color: var(--gray-600); padding: 10px 16px;
    border-radius: 10px; transition: all 0.25s;
}
.header-nav a:hover { color: var(--primary); background: var(--primary-50); }
.nav-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 24px; border-radius: var(--radius);
    background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
    color: #fff !important; font-weight: 600; font-size: 14px;
    box-shadow: 0 4px 16px rgba(37,99,235,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    position: relative; overflow: hidden;
}
.nav-btn::before {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15), transparent);
    opacity: 0; transition: opacity 0.3s;
}
.nav-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(37,99,235,0.45); }
.nav-btn:hover::before { opacity: 1; }
.nav-btn:active { transform: translateY(0); }

/* ===== HERO - Premium with particles ===== */
.hero-premium {
    position: relative; overflow: hidden;
    background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 30%, #1d4ed8 60%, #4f46e5 100%);
    padding: 140px 32px 100px; text-align: center; color: #fff;
    min-height: 600px; display: flex; align-items: center; justify-content: center;
}
.hero-premium canvas { position: absolute; inset: 0; z-index: 1; }
.fl-shape {
    position: absolute; border-radius: 50%; opacity: 0.08;
    background: linear-gradient(135deg, #fff, rgba(255,255,255,0));
    transition: transform 0.15s ease-out; z-index: 2;
    animation: floatShape 8s ease-in-out infinite;
}
@keyframes floatShape {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}
.hero-inner {
    position: relative; z-index: 10; max-width: 800px; margin: 0 auto;
    animation: heroIn 1s cubic-bezier(0.22,1,0.36,1) both;
}
@keyframes heroIn {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 20px; border-radius: 50px;
    background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
    backdrop-filter: blur(10px); font-size: 13px; font-weight: 600;
    color: rgba(255,255,255,0.9); margin-bottom: 28px;
    animation: heroIn 1s 0.2s cubic-bezier(0.22,1,0.36,1) both;
}
.hero-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #34d399; animation: pulse 2s infinite; }
@keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

.hero-premium h1 {
    font-size: 56px; font-weight: 900; letter-spacing: -0.04em;
    line-height: 1.1; margin-bottom: 20px;
    color: #fff; background: none; -webkit-text-fill-color: #fff;
    animation: heroIn 1s 0.3s cubic-bezier(0.22,1,0.36,1) both;
}
.typing-text {
    background: linear-gradient(135deg, #38bdf8, #a78bfa, #fb7185);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    font-weight: 900;
}
.typing-cursor {
    display: inline-block; width: 3px; height: 1em; background: #a78bfa;
    margin-left: 4px; vertical-align: text-bottom;
    animation: blink 1s step-end infinite;
}
@keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0; } }

.hero-premium p {
    font-size: 20px; color: rgba(255,255,255,0.75); line-height: 1.7;
    max-width: 600px; margin: 0 auto 40px;
    animation: heroIn 1s 0.5s cubic-bezier(0.22,1,0.36,1) both;
}
.hero-cta {
    display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;
    animation: heroIn 1s 0.7s cubic-bezier(0.22,1,0.36,1) both;
}
.hero-btn-primary {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 16px 36px; border-radius: 14px;
    background: #fff; color: var(--primary-dark) !important; font-weight: 700; font-size: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
}
.hero-btn-primary:hover { transform: translateY(-3px); box-shadow: 0 16px 48px rgba(0,0,0,0.25); }
.hero-btn-secondary {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 16px 36px; border-radius: 14px;
    background: rgba(255,255,255,0.1); border: 1.5px solid rgba(255,255,255,0.25);
    color: #fff !important; font-weight: 600; font-size: 16px;
    backdrop-filter: blur(10px); transition: all 0.3s;
}
.hero-btn-secondary:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); transform: translateY(-2px); }

.stats-row {
    display: flex; gap: 0; justify-content: center; margin-top: 56px;
    animation: heroIn 1s 0.9s cubic-bezier(0.22,1,0.36,1) both;
}
.stat-box {
    padding: 24px 40px; text-align: center; position: relative;
}
.stat-box:not(:last-child)::after {
    content: ''; position: absolute; right: 0; top: 20%; height: 60%;
    width: 1px; background: rgba(255,255,255,0.15);
}
.stat-box .stat-num { font-size: 36px; font-weight: 900; display: block; color: #fff; letter-spacing: -0.02em; }
.stat-box .stat-label { font-size: 13px; color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 0.08em; margin-top: 4px; }

/* ===== MAIN CONTENT ===== */
.main-content {
    max-width: 1280px; margin: 0 auto; padding: 80px 32px; flex: 1; width: 100%;
    margin-top: 72px;
}

.section-tag {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 6px 16px; border-radius: 50px;
    background: var(--primary-50); color: var(--primary); font-size: 13px; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 16px;
}
.section-title { font-size: 40px; font-weight: 800; color: var(--gray-900); margin-bottom: 12px; letter-spacing: -0.03em; line-height: 1.15; }
.section-subtitle { font-size: 18px; color: var(--gray-500); margin-bottom: 48px; max-width: 560px; line-height: 1.7; }

/* ===== 3D TILT CARDS ===== */
.tilt-card {
    background: #fff; border-radius: var(--radius-lg); padding: 36px;
    border: 1px solid var(--gray-100);
    box-shadow: 0 4px 24px rgba(0,0,0,0.04), 0 1px 4px rgba(0,0,0,0.04);
    transition: transform 0.2s ease-out, box-shadow 0.3s;
    position: relative; overflow: hidden;
    transform-style: preserve-3d; will-change: transform;
}
.tilt-card:hover { box-shadow: 0 20px 60px rgba(0,0,0,0.08), 0 4px 16px rgba(0,0,0,0.04); }
.card-shine { position: absolute; inset: 0; pointer-events: none; transition: background 0.2s; z-index: 1; }
.card-icon {
    width: 56px; height: 56px; border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; margin-bottom: 20px; position: relative; z-index: 2;
}
.card-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
.card-icon.violet { background: linear-gradient(135deg, #ede9fe, #ddd6fe); }
.card-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
.card-icon.amber { background: linear-gradient(135deg, #fef3c7, #fde68a); }
.tilt-card h3 { font-size: 20px; font-weight: 700; color: var(--gray-900); margin: 0 0 10px; position: relative; z-index: 2; }
.tilt-card p { font-size: 15px; color: var(--gray-500); line-height: 1.7; margin: 0; position: relative; z-index: 2; }

/* ===== JOB TABLE (premium) ===== */
table.sortable {
    width: 100%; border-collapse: separate; border-spacing: 0;
    background: #fff; border-radius: var(--radius-lg); overflow: hidden;
    box-shadow: 0 8px 40px rgba(0,0,0,0.06), 0 2px 8px rgba(0,0,0,0.03);
    border: 1px solid var(--gray-100);
}
tr.rowHeading {
    background: linear-gradient(135deg, var(--gray-900), var(--primary-darker));
}
tr.rowHeading th {
    padding: 18px 24px; color: #fff; font-weight: 600; font-size: 12px;
    text-transform: uppercase; letter-spacing: 0.08em; border: none; text-align: left;
}
tr.evenTableRow, tr.oddTableRow { transition: all 0.3s cubic-bezier(0.4,0,0.2,1); cursor: pointer; }
tr.evenTableRow { background: #fff; }
tr.oddTableRow { background: var(--gray-50); }
tr.evenTableRow:hover, tr.oddTableRow:hover {
    background: var(--primary-50);
    box-shadow: inset 4px 0 0 var(--primary);
}
tr.evenTableRow td, tr.oddTableRow td {
    padding: 20px 24px; border-bottom: 1px solid var(--gray-100);
    font-size: 15px; color: var(--gray-700);
}
tr.evenTableRow:last-child td, tr.oddTableRow:last-child td { border-bottom: none; }
tr.evenTableRow td a, tr.oddTableRow td a { color: var(--primary); font-weight: 700; font-size: 15px; }
tr.evenTableRow td a:hover, tr.oddTableRow td a:hover { color: var(--primary-dark); }

/* ===== JOB DETAILS ===== */
.job-detail-card {
    background: #fff; border-radius: var(--radius-xl); padding: 48px;
    border: 1px solid var(--gray-100);
    box-shadow: 0 8px 40px rgba(0,0,0,0.05);
}
.job-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 16px; border-radius: 50px; font-size: 13px; font-weight: 600;
}
.job-tag.location { background: #eff6ff; color: #2563eb; }
.job-tag.type { background: #f0fdf4; color: #059669; }
.job-tag.time { background: #fefce8; color: #ca8a04; }
.job-tag.openings { background: #faf5ff; color: #7c3aed; }

#detailsTable { display: none; }
div#discriptive { float: none; width: 100%; margin: 0; }
div#detailsTools { display: none; }

/* ===== APPLICATION FORM (Premium) ===== */
.form-section {
    background: #fff; border-radius: var(--radius-xl); padding: 40px;
    border: 1px solid var(--gray-100);
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    margin-bottom: 28px; position: relative; overflow: hidden;
}
.form-section::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--violet), var(--accent));
}
.form-section h2 { font-size: 20px; color: var(--gray-900); margin: 0 0 28px; font-weight: 700; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.form-grid .full { grid-column: 1 / -1; }

label {
    font-weight: 600; color: var(--gray-700); display: block;
    margin-bottom: 8px; font-size: 14px;
}

input.inputbox, input.inputBoxName, input.inputBoxNormal, input.inputBoxArea,
input.inputBoxFile, input#documentFile, input[type="text"], input[type="email"], input[type="tel"] {
    width: 100%; padding: 14px 18px;
    border: 2px solid var(--gray-200); border-radius: var(--radius);
    font: 15px var(--font); color: var(--gray-800);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    background: var(--gray-50);
}
input.inputbox:hover, input.inputBoxName:hover, input.inputBoxNormal:hover,
input[type="text"]:hover, input[type="email"]:hover {
    border-color: var(--gray-300); background: #fff;
}
input.inputbox:focus, input.inputBoxName:focus, input.inputBoxNormal:focus,
input#documentFile:focus, input[type="text"]:focus, input[type="email"]:focus {
    outline: none; border-color: var(--primary); background: #fff;
    box-shadow: 0 0 0 4px rgba(37,99,235,0.1), 0 2px 8px rgba(37,99,235,0.06);
}

textarea, textarea.inputBoxArea, textarea.inputboxlarge {
    width: 100%; padding: 14px 18px; min-height: 120px;
    border: 2px solid var(--gray-200); border-radius: var(--radius);
    font: 15px var(--font); color: var(--gray-800);
    resize: vertical; transition: all 0.3s; background: var(--gray-50);
}
textarea:focus {
    outline: none; border-color: var(--primary); background: #fff;
    box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
}

input.submitbutton, input.submitButton, input[type="submit"] {
    padding: 16px 40px; width: auto; min-width: 240px;
    background: linear-gradient(135deg, var(--primary) 0%, #4f46e5 100%);
    color: #fff; font: 700 16px var(--font); border: none;
    border-radius: 14px; cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 8px 28px rgba(37,99,235,0.35), inset 0 1px 0 rgba(255,255,255,0.15);
}
input.submitbutton:hover, input.submitButton:hover, input[type="submit"]:hover {
    transform: translateY(-3px);
    box-shadow: 0 14px 40px rgba(37,99,235,0.4);
}
input.submitbutton:active, input.submitButton:active, input[type="submit"]:active {
    transform: translateY(0);
}

/* Progress bar */
.progress-bar-bg {
    width: 100%; height: 6px; background: var(--gray-100);
    border-radius: 3px; margin-bottom: 32px; overflow: hidden;
}
.progress-bar-fill {
    height: 100%; width: 0%;
    background: linear-gradient(90deg, var(--primary), var(--violet));
    border-radius: 3px; transition: width 0.5s cubic-bezier(0.22,1,0.36,1);
}

/* Remove old table-based form styles */
div.applyBoxLeft, div.applyBoxRight { float: none; width: 100%; max-width: 100%; border: none; box-shadow: none; padding: 0; background: transparent; margin: 0; }
div.applyBoxLeft div, div.applyBoxRight div { display: none; }
div.applyBoxLeft table, div.applyBoxRight table { display: none; }
td.label { display: none; }

/* ===== FOOTER ===== */
.career-footer {
    background: linear-gradient(180deg, var(--gray-900) 0%, #0a0f1a 100%);
    color: var(--gray-400); padding: 64px 32px 32px; margin-top: auto;
    position: relative;
}
.career-footer::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(37,99,235,0.3), var(--violet), rgba(37,99,235,0.3), transparent);
}
.footer-inner {
    max-width: 1280px; margin: 0 auto;
    display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 48px;
}
.footer-brand .brand-text { color: #fff; font-size: 22px; }
.footer-brand .brand-text span { background: linear-gradient(135deg, #38bdf8, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.footer-brand p { font-size: 15px; margin-top: 12px; color: var(--gray-400); line-height: 1.7; max-width: 360px; }
.footer-col h4 {
    color: #fff; font-size: 13px; font-weight: 700; margin-bottom: 20px;
    text-transform: uppercase; letter-spacing: 0.1em;
}
.footer-col a {
    display: block; font-size: 15px; color: var(--gray-400); padding: 6px 0;
    transition: all 0.3s;
}
.footer-col a:hover { color: #fff; transform: translateX(4px); }
.footer-bottom {
    max-width: 1280px; margin: 48px auto 0; padding-top: 28px;
    border-top: 1px solid rgba(255,255,255,0.08);
    display: flex; justify-content: space-between; align-items: center;
    font-size: 14px; color: var(--gray-500);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .header-inner { padding: 12px 16px; }
    .header-nav a:not(.nav-btn) { display: none; }
    .hero-premium { padding: 120px 20px 70px; min-height: 500px; }
    .hero-premium h1 { font-size: 32px; }
    .hero-premium p { font-size: 16px; }
    .stats-row { flex-direction: column; gap: 8px; }
    .stat-box { padding: 16px; }
    .stat-box:not(:last-child)::after { display: none; }
    .main-content { padding: 40px 16px; margin-top: 60px; }
    .section-title { font-size: 28px; }
    .form-grid { grid-template-columns: 1fr; }
    .footer-inner { grid-template-columns: 1fr; gap: 32px; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
    .hero-cta { flex-direction: column; align-items: center; }
    .hero-btn-primary, .hero-btn-secondary { width: 100%; max-width: 300px; justify-content: center; }
}

/* Generic overrides */
h1 { font: 800 32px var(--font); color: var(--gray-900); letter-spacing: -0.03em; margin: 0 0 16px; background: none; -webkit-text-fill-color: var(--gray-900); }
h2 { font: 700 24px var(--font); color: var(--gray-800); margin: 0 0 16px; border: none; padding: 0; }
h3 { font: 700 18px var(--font); color: var(--gray-700); margin: 0 0 8px; }
p { font: 400 15px/1.7 var(--font); color: var(--gray-600); }
strong { font-weight: 700; color: var(--gray-800); }
#careerContent { clear: both; padding: 24px 0; flex: 1; }
#poweredCATS { display: none; }
.clearfix::after { content: ''; display: table; clear: both; }
CSS;

$stmt->execute(['val' => $css, 'setting' => 'CSS']);
echo "Updated CSS (" . strlen($css) . " chars)\n";


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
            <a href="index.php?m=careers">Home</a>
            <a href="index.php?m=careers&p=showAll">Positions</a>
            <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                Browse Jobs
            </a>
        </nav>
    </div>
</div>
TPL;

$stmt->execute(['val' => $header, 'setting' => 'Header']);
echo "Updated Header\n";


// =====================================================
// CONTENT - MAIN (Hero + Features)
// =====================================================
$main = <<<'TPL'
<div class="hero-premium">
    <canvas id="particleCanvas"></canvas>
    <div class="fl-shape" style="width:300px;height:300px;top:-60px;left:-80px;"></div>
    <div class="fl-shape" style="width:200px;height:200px;bottom:-40px;right:-60px;animation-delay:-3s;"></div>
    <div class="fl-shape" style="width:150px;height:150px;top:30%;right:10%;animation-delay:-5s;"></div>
    <div class="fl-shape" style="width:100px;height:100px;bottom:20%;left:15%;animation-delay:-2s;"></div>

    <div class="hero-inner">
        <div class="hero-badge"><span class="dot"></span> We're Hiring - Join Our Team</div>
        <h1>Build Your Future<br>Through <span class="typing-text"></span><span class="typing-cursor"></span></h1>
        <p>Join a world-class team shaping the future of technology. We offer challenging projects, mentorship from industry leaders, and a culture that celebrates bold ideas.</p>

        <div class="hero-cta">
            <a href="index.php?m=careers&p=showAll" class="hero-btn-primary magnetic-btn">
                View Open Positions
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
            <a href="#why-join" class="hero-btn-secondary">
                Learn About Us
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
        </div>

        <div class="stats-row">
            <div class="stat-box">
                <span class="stat-num counter" data-target="50" data-suffix="+">0+</span>
                <span class="stat-label">Team Members</span>
            </div>
            <div class="stat-box">
                <span class="stat-num counter" data-target="12" data-suffix="+">0+</span>
                <span class="stat-label">Countries</span>
            </div>
            <div class="stat-box">
                <span class="stat-num counter" data-target="98" data-suffix="%">0%</span>
                <span class="stat-label">Satisfaction</span>
            </div>
            <div class="stat-box">
                <span class="stat-num counter" data-target="4" data-suffix=".8">0</span>
                <span class="stat-label">Glassdoor</span>
            </div>
        </div>
    </div>
</div>

<div class="main-content" id="why-join">
    <div style="text-align:center;margin-bottom:56px;" class="reveal">
        <span class="section-tag">Why Join Us</span>
        <h2 class="section-title">Where Talent Meets Opportunity</h2>
        <p class="section-subtitle" style="margin:0 auto;">We invest in our people because we know that exceptional teams build exceptional products.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:28px;margin-bottom:72px;" class="stagger-children">
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon blue">&#127891;</div>
            <h3>Continuous Learning</h3>
            <p>Annual learning budget, conference sponsorships, and access to premium educational platforms for every team member.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon violet">&#128640;</div>
            <h3>Career Growth</h3>
            <p>Clear advancement paths with quarterly reviews, 1-on-1 mentorship, and leadership development programs.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon emerald">&#127758;</div>
            <h3>Remote First</h3>
            <p>Work from anywhere in the world. We believe great work happens when you have the freedom to choose your environment.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon amber">&#128150;</div>
            <h3>Comprehensive Benefits</h3>
            <p>Premium health coverage, equity participation, generous PTO, home office setup, and family-friendly policies.</p>
        </div>
    </div>

    <div class="reveal" style="text-align:center;padding:48px 0;">
        <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn" style="padding:16px 40px;font-size:16px;border-radius:14px;">
            Explore Open Positions
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>
TPL;

$stmt->execute(['val' => $main, 'setting' => 'Content - Main']);
echo "Updated Content - Main\n";


// =====================================================
// CONTENT - SEARCH RESULTS
// =====================================================
$search = <<<'TPL'
<div class="main-content">
    <div style="text-align:center;margin-bottom:48px;" class="reveal">
        <span class="section-tag">Open Positions</span>
        <h2 class="section-title">Find Your Next Role</h2>
        <p class="section-subtitle" style="margin:0 auto;"><numberOfSearchResults> position(s) currently available</p>
    </div>
    <div class="reveal">
        <searchResultsTableUnformatted>
    </div>
</div>
TPL;

$stmt->execute(['val' => $search, 'setting' => 'Content - Search Results']);
echo "Updated Content - Search Results\n";


// =====================================================
// CONTENT - JOB DETAILS
// =====================================================
$jobDetails = <<<'TPL'
<div class="main-content" style="max-width:960px;">
    <div style="margin-bottom:28px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-500);display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:10px;background:var(--gray-50);transition:all 0.3s;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            All Positions
        </a>
    </div>

    <div class="job-detail-card reveal">
        <h1 style="font-size:36px;margin-bottom:16px;"><title></h1>
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
            <span class="job-tag openings"><openings> opening(s)</span>
        </div>

        <div style="display:flex;gap:16px;flex-wrap:wrap;margin-bottom:40px;">
            <a-applyToJob class="nav-btn magnetic-btn" style="padding:14px 36px;font-size:15px;border-radius:14px;">
                Apply for this Position
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div style="border-top:1px solid var(--gray-100);padding-top:32px;">
            <h2 style="font-size:22px;margin:0 0 20px;">About This Role</h2>
            <div style="color:var(--gray-600);line-height:2;font-size:15px;"><description></div>
        </div>

        <div style="margin-top:40px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;">
            <div style="background:var(--gray-50);padding:20px;border-radius:var(--radius);border:1px solid var(--gray-100);">
                <div style="font-size:12px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Location</div>
                <div style="font-weight:700;color:var(--gray-800);"><city>, <state></div>
            </div>
            <div style="background:var(--gray-50);padding:20px;border-radius:var(--radius);border:1px solid var(--gray-100);">
                <div style="font-size:12px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Employment Type</div>
                <div style="font-weight:700;color:var(--gray-800);"><type></div>
            </div>
            <div style="background:var(--gray-50);padding:20px;border-radius:var(--radius);border:1px solid var(--gray-100);">
                <div style="font-size:12px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Recruiter</div>
                <div style="font-weight:700;color:var(--gray-800);"><recruiter></div>
            </div>
            <div style="background:var(--gray-50);padding:20px;border-radius:var(--radius);border:1px solid var(--gray-100);">
                <div style="font-size:12px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Date Posted</div>
                <div style="font-weight:700;color:var(--gray-800);"><created></div>
            </div>
        </div>

        <div style="margin-top:40px;text-align:center;padding:32px;background:linear-gradient(135deg,var(--primary-50),#f5f3ff);border-radius:var(--radius-lg);border:1px solid var(--primary-light);">
            <h3 style="font-size:20px;margin:0 0 10px;color:var(--gray-900);">Ready to make an impact?</h3>
            <p style="margin:0 0 20px;color:var(--gray-500);">Join our team and help us build the future.</p>
            <a-applyToJob class="nav-btn magnetic-btn" style="padding:14px 36px;font-size:15px;border-radius:14px;">
                Apply Now
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>
TPL;

$stmt->execute(['val' => $jobDetails, 'setting' => 'Content - Job Details']);
echo "Updated Content - Job Details\n";


// =====================================================
// CONTENT - APPLY FOR POSITION
// =====================================================
$apply = <<<'TPL'
<div class="main-content" style="max-width:800px;">
    <div style="margin-bottom:28px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-500);display:inline-flex;align-items:center;gap:8px;padding:8px 16px;border-radius:10px;background:var(--gray-50);">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back to Positions
        </a>
    </div>

    <div class="reveal" style="text-align:center;margin-bottom:36px;">
        <span class="section-tag">Application</span>
        <h1 style="font-size:32px;margin-bottom:8px;">Apply for <title></h1>
        <p style="color:var(--gray-500);margin:0;">Complete the form below. Fields marked with * are required.</p>
    </div>

    <div class="progress-bar-bg reveal">
        <div class="progress-bar-fill" id="formProgress"></div>
    </div>
    <div style="text-align:right;margin:-24px 0 24px;font-size:13px;color:var(--gray-400);" id="formProgressLabel" class="reveal">0% complete</div>

    <div class="form-section reveal">
        <h2>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="vertical-align:-3px;margin-right:8px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Personal Information
        </h2>
        <div class="form-grid">
            <div><label>First Name <span style="color:var(--rose);">*</span></label><input-firstName req></div>
            <div><label>Last Name <span style="color:var(--rose);">*</span></label><input-lastName req></div>
            <div><label>Email Address <span style="color:var(--rose);">*</span></label><input-email req></div>
            <div><label>Confirm Email <span style="color:var(--rose);">*</span></label><input-emailconfirm req></div>
            <div><label>Phone Number <span style="color:var(--rose);">*</span></label><input-phone req></div>
            <div><label>City</label><input-city></div>
            <div><label>State / Province</label><input-state></div>
            <div><label>Zip / Postal Code</label><input-zip></div>
            <div class="full"><label>Street Address</label><input-address></div>
        </div>
    </div>

    <div class="form-section reveal">
        <h2>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--violet)" stroke-width="2" style="vertical-align:-3px;margin-right:8px;"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            Professional Background
        </h2>
        <div class="form-grid">
            <div><label>Key Skills</label><input-keySkills></div>
            <div><label>Current Employer</label><input-employer></div>
            <div class="full"><label>How did you hear about this position?</label><input-source></div>
        </div>
    </div>

    <div class="form-section reveal">
        <h2>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="2" style="vertical-align:-3px;margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Resume &amp; Documents
        </h2>
        <div style="margin-bottom:24px;">
            <label>Upload Your Resume</label>
            <input-resumeUpload>
            <p style="font-size:13px;color:var(--gray-400);margin-top:8px;">Accepted: PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</p>
        </div>
        <div>
            <label>Cover Letter / Additional Notes</label>
            <input-extraNotes>
        </div>
    </div>

    <div class="reveal" style="text-align:center;padding:16px 0 48px;">
        <submit value="Submit Application" class="magnetic-btn" style="padding:16px 48px;min-width:300px;font-size:16px;border-radius:14px;">
    </div>
</div>
TPL;

$stmt->execute(['val' => $apply, 'setting' => 'Content - Apply for Position']);
echo "Updated Content - Apply for Position\n";


// =====================================================
// CONTENT - THANKS
// =====================================================
$thanks = <<<'TPL'
<div class="main-content" style="text-align:center;padding:100px 24px;max-width:700px;">
    <div class="reveal-scale" style="margin-bottom:32px;">
        <div style="width:100px;height:100px;background:linear-gradient(135deg,#059669,#34d399);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 16px 48px rgba(5,150,105,0.3);">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
    </div>
    <h1 class="reveal" style="font-size:38px;margin-bottom:16px;color:var(--gray-900);">Application Submitted!</h1>
    <p class="reveal" style="font-size:19px;color:var(--gray-500);max-width:520px;margin:0 auto 20px;line-height:1.7;">Thank you for your interest. Our talent team will review your application and reach out within 3-5 business days.</p>
    <p class="reveal" style="font-size:15px;color:var(--gray-400);max-width:460px;margin:0 auto 40px;">A confirmation has been sent to your email. Please check your inbox (and spam folder) for next steps.</p>
    <div class="reveal" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
        <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn" style="padding:14px 32px;font-size:15px;border-radius:14px;">
            Browse More Jobs
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
        <a href="index.php?m=careers" class="magnetic-btn" style="display:inline-flex;align-items:center;gap:8px;padding:14px 32px;border:2px solid var(--gray-200);border-radius:14px;font-weight:600;font-size:15px;color:var(--gray-700);transition:all 0.3s;">Back to Home</a>
    </div>
</div>
TPL;

$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Updated Content - Thanks\n";


// =====================================================
// FOOTER
// =====================================================
$footer = <<<'TPL'
<div class="career-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-text">Neutara <span>Careers</span></div>
            <p>Building the future with exceptional talent. We are committed to creating an inclusive workplace where innovation thrives.</p>
        </div>
        <div class="footer-col">
            <h4>Careers</h4>
            <a href="index.php?m=careers">Home</a>
            <a href="index.php?m=careers&p=showAll">Open Positions</a>
        </div>
        <div class="footer-col">
            <h4>Company</h4>
            <a href="index.php?m=careers">About Us</a>
            <a href="index.php?m=careers">Culture</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; 2026 Neutara. All rights reserved.</span>
        <span style="display:flex;gap:16px;">
            <a href="#" style="color:var(--gray-500);">Privacy</a>
            <a href="#" style="color:var(--gray-500);">Terms</a>
        </span>
    </div>
</div>
TPL;

$stmt->execute(['val' => $footer, 'setting' => 'Footer']);
echo "Updated Footer\n";

echo "\n=== DONE! Premium career portal deployed ===\n";
echo "Visit: http://localhost:8000/index.php?m=careers\n";
