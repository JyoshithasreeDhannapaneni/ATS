<?php
$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);
$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");


// =====================================================
// CSS - Ultra Premium with 3D, depth, glassmorphism
// =====================================================
$css = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root {
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --primary-darker: #3730a3;
    --primary-light: #e0e7ff;
    --primary-50: #eef2ff;
    --accent: #06b6d4;
    --violet: #7c3aed;
    --violet-light: #ede9fe;
    --indigo: #4f46e5;
    --emerald: #059669;
    --emerald-light: #d1fae5;
    --amber: #d97706;
    --rose: #e11d48;
    --sky: #0ea5e9;
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
    --font: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif;
    --radius: 12px;
    --radius-lg: 20px;
    --radius-xl: 28px;
    --radius-2xl: 32px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body, html {
    font-family: var(--font);
    color: var(--gray-800);
    background: #f0f2f7;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}
body {
    display: flex; flex-direction: column; min-height: 100vh;
    opacity: 0; transition: opacity 0.5s ease;
}
body.page-loaded { opacity: 1; }
a { color: var(--primary); text-decoration: none; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); }
a:hover { color: var(--primary-dark); }
a:visited { color: var(--primary); }
img { border: none; }

/* ===== SCROLL REVEAL ===== */
.reveal { opacity:0; transform:translateY(50px); transition: opacity 0.9s cubic-bezier(0.22,1,0.36,1), transform 0.9s cubic-bezier(0.22,1,0.36,1); }
.reveal.revealed { opacity:1; transform:translateY(0); }
.reveal-left { opacity:0; transform:translateX(-70px); transition: opacity 0.9s cubic-bezier(0.22,1,0.36,1), transform 0.9s cubic-bezier(0.22,1,0.36,1); }
.reveal-left.revealed { opacity:1; transform:translateX(0); }
.reveal-right { opacity:0; transform:translateX(70px); transition: opacity 0.9s cubic-bezier(0.22,1,0.36,1), transform 0.9s cubic-bezier(0.22,1,0.36,1); }
.reveal-right.revealed { opacity:1; transform:translateX(0); }
.reveal-scale { opacity:0; transform:scale(0.8); transition: opacity 0.9s cubic-bezier(0.22,1,0.36,1), transform 0.9s cubic-bezier(0.22,1,0.36,1); }
.reveal-scale.revealed { opacity:1; transform:scale(1); }
.stagger-children > * { opacity:0; transform:translateY(35px); transition: opacity 0.7s cubic-bezier(0.22,1,0.36,1), transform 0.7s cubic-bezier(0.22,1,0.36,1); }
.stagger-children > *.stagger-visible { opacity:1; transform:translateY(0); }

/* ===== HEADER - Premium Glass ===== */
.career-header {
    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
    background: rgba(255,255,255,0.72);
    backdrop-filter: blur(24px) saturate(180%); -webkit-backdrop-filter: blur(24px) saturate(180%);
    border-bottom: 1px solid rgba(255,255,255,0.5);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
}
.career-header.scrolled {
    background: rgba(255,255,255,0.92);
    box-shadow: 0 4px 30px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.04);
}
.header-inner {
    max-width: 1320px; margin: 0 auto;
    padding: 14px 40px;
    display: flex; align-items: center; justify-content: space-between;
}
.brand { display: flex; align-items: center; gap: 14px; text-decoration: none; }
.brand-icon {
    width: 46px; height: 46px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--violet) 100%);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 900; font-size: 20px;
    box-shadow: 0 4px 20px rgba(79,70,229,0.4);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
}
.brand:hover .brand-icon { transform: rotate(-8deg) scale(1.08); box-shadow: 0 8px 30px rgba(79,70,229,0.5); }
.brand-text { font-size: 23px; font-weight: 800; color: var(--gray-900); letter-spacing: -0.03em; }
.brand-text span { background: linear-gradient(135deg, var(--primary), var(--violet)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
.header-nav { display: flex; align-items: center; gap: 6px; }
.header-nav a { font-size: 14px; font-weight: 600; color: var(--gray-500); padding: 10px 18px; border-radius: var(--radius); transition: all 0.25s; }
.header-nav a:hover { color: var(--primary); background: var(--primary-50); }
.nav-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 26px; border-radius: var(--radius);
    background: linear-gradient(135deg, var(--primary) 0%, var(--violet) 100%);
    color: #fff !important; font-weight: 700; font-size: 14px;
    box-shadow: 0 4px 20px rgba(79,70,229,0.4), inset 0 1px 0 rgba(255,255,255,0.2);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    position: relative; overflow: hidden;
}
.nav-btn::after {
    content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
    background: linear-gradient(45deg, transparent 40%, rgba(255,255,255,0.15) 50%, transparent 60%);
    transform: translateX(-100%); transition: transform 0.6s;
}
.nav-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 32px rgba(79,70,229,0.5); }
.nav-btn:hover::after { transform: translateX(100%); }
.nav-btn:active { transform: translateY(0); }

/* ===== HERO SECTION ===== */
.hero-premium {
    position: relative; overflow: hidden;
    background: linear-gradient(160deg, #0f172a 0%, #1e1b4b 30%, #312e81 55%, #4f46e5 80%, #6366f1 100%);
    padding: 160px 40px 100px; text-align: center; color: #fff;
    min-height: 520px;
}
.hero-premium canvas { position: absolute; inset: 0; z-index: 1; }
.fl-shape {
    position: absolute; border-radius: 50%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    transition: transform 0.12s ease-out; z-index: 2;
    animation: floatShape 10s ease-in-out infinite;
}
@keyframes floatShape {
    0%,100% { transform: translateY(0) scale(1); }
    33% { transform: translateY(-25px) scale(1.05); }
    66% { transform: translateY(15px) scale(0.95); }
}
.hero-inner { position: relative; z-index: 10; max-width: 820px; margin: 0 auto; }
.hero-inner > * { animation: heroSlideUp 1s cubic-bezier(0.22,1,0.36,1) both; }
.hero-inner > *:nth-child(1) { animation-delay: 0.1s; }
.hero-inner > *:nth-child(2) { animation-delay: 0.25s; }
.hero-inner > *:nth-child(3) { animation-delay: 0.4s; }
.hero-inner > *:nth-child(4) { animation-delay: 0.55s; }
.hero-inner > *:nth-child(5) { animation-delay: 0.7s; }
@keyframes heroSlideUp { from { opacity:0; transform:translateY(40px); } to { opacity:1; transform:translateY(0); } }

.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 8px 22px; border-radius: 50px;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
    backdrop-filter: blur(12px); font-size: 13px; font-weight: 700;
    color: rgba(255,255,255,0.85); margin-bottom: 24px;
    letter-spacing: 0.02em;
}
.hero-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 12px #34d399; animation: glow 2s infinite; }
@keyframes glow { 0%,100% { opacity:1; box-shadow:0 0 12px #34d399; } 50% { opacity:0.5; box-shadow:0 0 4px #34d399; } }

.hero-premium h1 {
    font-size: 52px; font-weight: 900; letter-spacing: -0.045em;
    line-height: 1.08; margin-bottom: 20px;
    color: #fff; background: none; -webkit-text-fill-color: #fff;
}
.typing-text {
    background: linear-gradient(135deg, #38bdf8, #a78bfa, #fb7185, #fbbf24);
    background-size: 300% 300%;
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    animation: gradientShift 4s ease infinite;
}
@keyframes gradientShift { 0%{background-position:0 50%} 50%{background-position:100% 50%} 100%{background-position:0 50%} }
.typing-cursor { display:inline-block; width:3px; height:0.9em; background:#a78bfa; margin-left:3px; vertical-align:text-bottom; animation:blink 1s step-end infinite; }
@keyframes blink { 0%,100%{opacity:1;} 50%{opacity:0;} }
.hero-premium p { font-size: 19px; color: rgba(255,255,255,0.65); line-height: 1.7; max-width: 580px; margin: 0 auto 36px; }

.hero-cta { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; }
.hero-btn-primary {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 16px 38px; border-radius: 16px;
    background: #fff; color: var(--primary-dark) !important; font-weight: 800; font-size: 16px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.2), 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
    letter-spacing: -0.01em;
}
.hero-btn-primary:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 20px 60px rgba(0,0,0,0.25); }
.hero-btn-secondary {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 16px 38px; border-radius: 16px;
    background: rgba(255,255,255,0.08); border: 1.5px solid rgba(255,255,255,0.2);
    color: #fff !important; font-weight: 700; font-size: 16px;
    backdrop-filter: blur(12px); transition: all 0.35s;
}
.hero-btn-secondary:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.35); transform: translateY(-3px); }

/* ===== STATS ROW - Glassmorphism ===== */
.stats-glass {
    max-width: 900px; margin: -52px auto 0; position: relative; z-index: 20;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
    border-radius: var(--radius-2xl); padding: 6px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1), 0 4px 16px rgba(0,0,0,0.05);
    border: 1px solid rgba(255,255,255,0.8);
    display: grid; grid-template-columns: repeat(4, 1fr);
}
.stat-glass {
    padding: 28px 20px; text-align: center;
    border-radius: var(--radius-xl); transition: all 0.3s;
}
.stat-glass:hover { background: var(--primary-50); }
.stat-glass .stat-num { font-size: 34px; font-weight: 900; color: var(--gray-900); display: block; letter-spacing: -0.03em; }
.stat-glass .stat-label { font-size: 13px; color: var(--gray-500); font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.06em; }

/* ===== MAIN CONTENT ===== */
.main-content {
    max-width: 1320px; margin: 0 auto; padding: 80px 40px; flex: 1; width: 100%;
    margin-top: 72px;
}
.section-tag {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 7px 18px; border-radius: 50px;
    background: linear-gradient(135deg, var(--primary-50), var(--violet-light));
    color: var(--primary); font-size: 13px; font-weight: 800;
    text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 16px;
    border: 1px solid var(--primary-light);
}
.section-title { font-size: 42px; font-weight: 900; color: var(--gray-900); margin-bottom: 12px; letter-spacing: -0.04em; line-height: 1.1; }
.section-subtitle { font-size: 18px; color: var(--gray-500); margin-bottom: 48px; max-width: 560px; line-height: 1.7; }

/* ===== 3D TILT CARDS ===== */
.tilt-card {
    background: #fff; border-radius: var(--radius-xl); padding: 36px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 8px 32px rgba(0,0,0,0.04);
    transition: transform 0.15s ease-out, box-shadow 0.4s;
    position: relative; overflow: hidden;
    transform-style: preserve-3d; will-change: transform;
}
.tilt-card:hover { box-shadow: 0 24px 80px rgba(0,0,0,0.1), 0 8px 24px rgba(0,0,0,0.06); }
.card-shine { position: absolute; inset: 0; pointer-events: none; transition: background 0.15s; z-index: 1; border-radius: var(--radius-xl); }
.card-icon {
    width: 60px; height: 60px; border-radius: 18px;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; margin-bottom: 22px; position: relative; z-index: 2;
}
.card-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
.card-icon.violet { background: linear-gradient(135deg, #ede9fe, #ddd6fe); }
.card-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
.card-icon.amber { background: linear-gradient(135deg, #fef3c7, #fde68a); }
.tilt-card h3 { font-size: 20px; font-weight: 800; color: var(--gray-900); margin: 0 0 10px; z-index: 2; position: relative; }
.tilt-card p { font-size: 15px; color: var(--gray-500); line-height: 1.7; margin: 0; z-index: 2; position: relative; }

/* ===== JOB TABLE → 3D CARD ROWS ===== */
table.sortable {
    width: 100%; border-collapse: separate; border-spacing: 0 14px;
    background: transparent;
    border: none; box-shadow: none; border-radius: 0; overflow: visible;
}
tr.rowHeading {
    background: transparent !important;
}
tr.rowHeading th {
    padding: 8px 28px; color: var(--gray-400); font-weight: 700; font-size: 11px;
    text-transform: uppercase; letter-spacing: 0.12em; border: none; text-align: left;
    background: transparent;
}
tr.evenTableRow, tr.oddTableRow {
    background: #fff !important;
    border-radius: var(--radius-lg);
    transition: all 0.4s cubic-bezier(0.4,0,0.2,1);
    cursor: pointer;
    box-shadow: 0 2px 8px rgba(0,0,0,0.03), 0 1px 2px rgba(0,0,0,0.02);
    position: relative;
}
tr.evenTableRow:hover, tr.oddTableRow:hover {
    transform: translateY(-4px) scale(1.01);
    box-shadow: 0 20px 50px rgba(79,70,229,0.1), 0 8px 20px rgba(0,0,0,0.06);
    z-index: 2;
}
tr.evenTableRow td, tr.oddTableRow td {
    padding: 24px 28px;
    border-top: 1px solid var(--gray-100);
    border-bottom: 1px solid var(--gray-100);
    font-size: 15px; color: var(--gray-600); font-weight: 500;
    background: #fff;
    vertical-align: middle;
}
tr.evenTableRow td:first-child, tr.oddTableRow td:first-child {
    border-left: 1px solid var(--gray-100);
    border-radius: var(--radius-lg) 0 0 var(--radius-lg);
    border-left: 3px solid var(--primary);
}
tr.evenTableRow td:last-child, tr.oddTableRow td:last-child {
    border-right: 1px solid var(--gray-100);
    border-radius: 0 var(--radius-lg) var(--radius-lg) 0;
}
tr.evenTableRow:hover td, tr.oddTableRow:hover td { border-color: rgba(79,70,229,0.15); }
tr.evenTableRow:hover td:first-child, tr.oddTableRow:hover td:first-child { border-left-color: var(--primary); }

tr.evenTableRow td a, tr.oddTableRow td a {
    color: var(--primary); font-weight: 800; font-size: 16px;
    letter-spacing: -0.01em;
    background: linear-gradient(135deg, var(--primary), var(--violet));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
tr.evenTableRow td a:hover, tr.oddTableRow td a:hover {
    -webkit-text-fill-color: var(--primary-dark);
}

/* ===== JOB DETAILS ===== */
.job-detail-card {
    background: #fff; border-radius: var(--radius-2xl); padding: 52px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 8px 48px rgba(0,0,0,0.06), 0 2px 8px rgba(0,0,0,0.03);
}
.job-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 700;
}
.job-tag.location { background: #eff6ff; color: #2563eb; }
.job-tag.type { background: #f0fdf4; color: #059669; }
.job-tag.time { background: #fefce8; color: #ca8a04; }
.job-tag.openings { background: #faf5ff; color: #7c3aed; }
#detailsTable { display: none; }
div#discriptive { float: none; width: 100%; margin: 0; }
div#detailsTools { display: none; }

/* ===== FORM ===== */
.form-section {
    background: #fff; border-radius: var(--radius-2xl); padding: 44px;
    border: 1px solid rgba(0,0,0,0.04);
    box-shadow: 0 4px 24px rgba(0,0,0,0.04);
    margin-bottom: 24px; position: relative; overflow: hidden;
}
.form-section::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
    background: linear-gradient(90deg, var(--primary), var(--violet), var(--accent), var(--emerald));
    background-size: 300% 100%; animation: gradientShift 5s ease infinite;
}
.form-section h2 { font-size: 20px; color: var(--gray-900); margin: 0 0 28px; font-weight: 800; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
.form-grid .full { grid-column: 1 / -1; }
label { font-weight: 700; color: var(--gray-700); display: block; margin-bottom: 8px; font-size: 14px; }

input.inputbox, input.inputBoxName, input.inputBoxNormal, input.inputBoxArea,
input.inputBoxFile, input#documentFile, input[type="text"], input[type="email"], input[type="tel"] {
    width: 100%; padding: 14px 18px;
    border: 2px solid var(--gray-200); border-radius: var(--radius);
    font: 500 15px var(--font); color: var(--gray-800);
    transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
    background: var(--gray-50);
}
input.inputbox:hover, input.inputBoxName:hover, input.inputBoxNormal:hover,
input[type="text"]:hover { border-color: var(--gray-300); background: #fff; }
input.inputbox:focus, input.inputBoxName:focus, input.inputBoxNormal:focus,
input#documentFile:focus, input[type="text"]:focus, input[type="email"]:focus {
    outline: none; border-color: var(--primary); background: #fff;
    box-shadow: 0 0 0 4px rgba(79,70,229,0.08), 0 4px 12px rgba(79,70,229,0.06);
}
textarea, textarea.inputBoxArea, textarea.inputboxlarge {
    width: 100%; padding: 14px 18px; min-height: 120px;
    border: 2px solid var(--gray-200); border-radius: var(--radius);
    font: 500 15px var(--font); color: var(--gray-800);
    resize: vertical; transition: all 0.3s; background: var(--gray-50);
}
textarea:focus { outline: none; border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(79,70,229,0.08); }

input.submitbutton, input.submitButton, input[type="submit"] {
    padding: 16px 44px; width: auto; min-width: 260px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--violet) 100%);
    color: #fff; font: 800 16px var(--font); border: none;
    border-radius: 16px; cursor: pointer;
    transition: all 0.35s cubic-bezier(0.4,0,0.2,1);
    box-shadow: 0 8px 32px rgba(79,70,229,0.4), inset 0 1px 0 rgba(255,255,255,0.2);
    letter-spacing: -0.01em;
}
input.submitbutton:hover, input.submitButton:hover, input[type="submit"]:hover {
    transform: translateY(-4px) scale(1.02);
    box-shadow: 0 16px 48px rgba(79,70,229,0.45);
}
input.submitbutton:active, input[type="submit"]:active { transform: translateY(0); }

.progress-bar-bg { width: 100%; height: 6px; background: var(--gray-100); border-radius: 3px; overflow: hidden; margin-bottom: 32px; }
.progress-bar-fill { height: 100%; width: 0%; background: linear-gradient(90deg, var(--primary), var(--violet)); border-radius: 3px; transition: width 0.5s cubic-bezier(0.22,1,0.36,1); }

div.applyBoxLeft, div.applyBoxRight { float:none; width:100%; max-width:100%; border:none; box-shadow:none; padding:0; background:transparent; margin:0; }
div.applyBoxLeft div, div.applyBoxRight div { display:none; }
div.applyBoxLeft table, div.applyBoxRight table { display:none; }
td.label { display:none; }

/* ===== FOOTER ===== */
.career-footer {
    background: linear-gradient(180deg, var(--gray-900) 0%, #060a14 100%);
    color: var(--gray-400); padding: 72px 40px 36px; margin-top: auto;
    position: relative;
}
.career-footer::before {
    content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(79,70,229,0.4), rgba(124,58,237,0.4), transparent);
}
.footer-inner {
    max-width: 1320px; margin: 0 auto;
    display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 64px;
}
.footer-brand .brand-text { color: #fff; font-size: 24px; }
.footer-brand .brand-text span { background: linear-gradient(135deg, #38bdf8, #a78bfa); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.footer-brand p { font-size: 15px; margin-top: 14px; color: var(--gray-400); line-height: 1.8; max-width: 380px; }
.footer-col h4 { color: #fff; font-size: 12px; font-weight: 800; margin-bottom: 22px; text-transform: uppercase; letter-spacing: 0.14em; }
.footer-col a { display: block; font-size: 15px; color: var(--gray-400); padding: 7px 0; transition: all 0.3s; }
.footer-col a:hover { color: #fff; transform: translateX(6px); }
.footer-bottom {
    max-width: 1320px; margin: 56px auto 0; padding-top: 28px;
    border-top: 1px solid rgba(255,255,255,0.06);
    display: flex; justify-content: space-between; align-items: center;
    font-size: 14px; color: var(--gray-500);
}

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .header-inner { padding: 12px 16px; }
    .header-nav a:not(.nav-btn) { display: none; }
    .hero-premium { padding: 130px 20px 70px; min-height: auto; }
    .hero-premium h1 { font-size: 30px; }
    .hero-premium p { font-size: 16px; }
    .hero-cta { flex-direction: column; align-items: center; }
    .hero-btn-primary, .hero-btn-secondary { width: 100%; max-width: 300px; justify-content: center; }
    .stats-glass { grid-template-columns: repeat(2, 1fr); margin: -40px 16px 0; }
    .main-content { padding: 40px 16px; margin-top: 60px; }
    .section-title { font-size: 28px; }
    .form-grid { grid-template-columns: 1fr; }
    .footer-inner { grid-template-columns: 1fr; gap: 32px; }
    .footer-bottom { flex-direction: column; gap: 12px; text-align: center; }
    table.sortable { border-spacing: 0 10px; }
    tr.evenTableRow td, tr.oddTableRow td { padding: 16px 18px; }
}

/* Generic overrides */
h1 { font: 900 36px var(--font); color: var(--gray-900); letter-spacing: -0.04em; margin: 0 0 16px; background:none; -webkit-text-fill-color: var(--gray-900); }
h2 { font: 800 24px var(--font); color: var(--gray-800); margin: 0 0 16px; border:none; padding:0; }
h3 { font: 700 18px var(--font); color: var(--gray-700); margin: 0 0 8px; }
p { font: 400 15px/1.7 var(--font); color: var(--gray-500); }
strong { font-weight: 800; color: var(--gray-800); }
#careerContent { clear:both; padding:24px 0; flex:1; }
#poweredCATS { display:none; }
.clearfix::after { content:''; display:table; clear:both; }
CSS;

$stmt->execute(['val' => $css, 'setting' => 'CSS']);
echo "CSS updated (" . strlen($css) . ")\n";


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
            <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn">
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
// CONTENT - MAIN → Auto-redirect to job listings
// =====================================================
$main = <<<'TPL'
<script>window.location.replace('index.php?m=careers&p=showAll');</script>
<div class="main-content" style="text-align:center;padding:120px 24px;">
    <p>Redirecting to open positions...</p>
</div>
TPL;
$stmt->execute(['val' => $main, 'setting' => 'Content - Main']);
echo "Content - Main updated (redirect)\n";


// =====================================================
// CONTENT - SEARCH RESULTS (THE MAIN LANDING PAGE)
// =====================================================
$search = <<<'TPL'
<div class="hero-premium">
    <canvas id="particleCanvas"></canvas>
    <div class="fl-shape" style="width:350px;height:350px;top:-80px;left:-100px;"></div>
    <div class="fl-shape" style="width:250px;height:250px;bottom:-60px;right:-80px;animation-delay:-3s;"></div>
    <div class="fl-shape" style="width:180px;height:180px;top:25%;right:8%;animation-delay:-6s;"></div>
    <div class="fl-shape" style="width:120px;height:120px;bottom:25%;left:12%;animation-delay:-2s;"></div>
    <div class="fl-shape" style="width:80px;height:80px;top:50%;left:30%;animation-delay:-4s;"></div>

    <div class="hero-inner">
        <div class="hero-badge"><span class="dot"></span> We're Hiring</div>
        <h1>Shape the Future<br>Through <span class="typing-text"></span><span class="typing-cursor"></span></h1>
        <p>Join a world-class team where bold ideas become breakthrough solutions. Your next chapter starts here.</p>
        <div class="hero-cta">
            <a href="#positions" class="hero-btn-primary magnetic-btn">
                View Positions
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M19 12l-7 7-7-7"/></svg>
            </a>
            <a href="#culture" class="hero-btn-secondary">
                Our Culture
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>

<div style="max-width:1320px;margin:0 auto;padding:0 40px;">
    <div class="stats-glass">
        <div class="stat-glass"><span class="stat-num counter" data-target="50" data-suffix="+">0</span><span class="stat-label">Team Members</span></div>
        <div class="stat-glass"><span class="stat-num counter" data-target="12" data-suffix="+">0</span><span class="stat-label">Countries</span></div>
        <div class="stat-glass"><span class="stat-num counter" data-target="98" data-suffix="%">0</span><span class="stat-label">Satisfaction</span></div>
        <div class="stat-glass"><span class="stat-num">4.8<span style="font-size:18px;">&#9733;</span></span><span class="stat-label">Glassdoor</span></div>
    </div>
</div>

<div class="main-content" id="culture" style="margin-top:0;">
    <div style="text-align:center;margin-bottom:56px;" class="reveal">
        <span class="section-tag">Why Neutara</span>
        <h2 class="section-title">Built for Exceptional People</h2>
        <p class="section-subtitle" style="margin:0 auto;">We create an environment where talent thrives, ideas flourish, and careers accelerate.</p>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;margin-bottom:80px;" class="stagger-children">
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon blue">&#127891;</div>
            <h3>Continuous Learning</h3>
            <p>Annual learning budget, conference sponsorships, and premium educational platforms.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon violet">&#128640;</div>
            <h3>Rapid Career Growth</h3>
            <p>Clear advancement paths, quarterly reviews, and 1-on-1 mentorship programs.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon emerald">&#127758;</div>
            <h3>Remote First Culture</h3>
            <p>Work from anywhere. We believe great work happens with the freedom to choose.</p>
        </div>
        <div class="tilt-card">
            <div class="card-shine"></div>
            <div class="card-icon amber">&#128150;</div>
            <h3>Premium Benefits</h3>
            <p>Top-tier health coverage, equity, unlimited PTO, and family-friendly policies.</p>
        </div>
    </div>

    <div id="positions" style="scroll-margin-top:100px;">
        <div style="text-align:center;margin-bottom:48px;" class="reveal">
            <span class="section-tag">Open Roles</span>
            <h2 class="section-title">Find Your Next Role</h2>
            <p class="section-subtitle" style="margin:0 auto;"><numberOfSearchResults> position(s) currently available &mdash; discover your perfect fit.</p>
        </div>
        <div class="reveal">
            <searchResultsTableUnformatted>
        </div>
    </div>
</div>
TPL;
$stmt->execute(['val' => $search, 'setting' => 'Content - Search Results']);
echo "Content - Search Results updated\n";


// =====================================================
// JOB DETAILS
// =====================================================
$jobDetails = <<<'TPL'
<div class="main-content" style="max-width:960px;">
    <div style="margin-bottom:28px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-400);display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--radius);background:#fff;border:1px solid var(--gray-200);transition:all 0.3s;font-weight:600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            All Positions
        </a>
    </div>

    <div class="job-detail-card reveal">
        <h1 style="font-size:38px;margin-bottom:16px;letter-spacing:-0.04em;"><title></h1>
        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:36px;">
            <span class="job-tag location">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <city>, <state>
            </span>
            <span class="job-tag type">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
                <type>
            </span>
            <span class="job-tag time">Posted <daysOld> days ago</span>
            <span class="job-tag openings"><openings> opening(s)</span>
        </div>

        <div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:44px;">
            <a-applyToJob class="nav-btn magnetic-btn" style="padding:15px 40px;font-size:16px;border-radius:16px;">
                Apply Now
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div style="border-top:1px solid var(--gray-100);padding-top:36px;">
            <h2 style="font-size:24px;margin:0 0 20px;">About This Role</h2>
            <div style="color:var(--gray-600);line-height:2;font-size:15px;"><description></div>
        </div>

        <div style="margin-top:44px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
            <div style="background:var(--gray-50);padding:22px;border-radius:var(--radius-lg);border:1px solid var(--gray-100);">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">Location</div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><city>, <state></div>
            </div>
            <div style="background:var(--gray-50);padding:22px;border-radius:var(--radius-lg);border:1px solid var(--gray-100);">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">Type</div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><type></div>
            </div>
            <div style="background:var(--gray-50);padding:22px;border-radius:var(--radius-lg);border:1px solid var(--gray-100);">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">Recruiter</div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><recruiter></div>
            </div>
            <div style="background:var(--gray-50);padding:22px;border-radius:var(--radius-lg);border:1px solid var(--gray-100);">
                <div style="font-size:11px;color:var(--gray-400);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:8px;font-weight:700;">Posted</div>
                <div style="font-weight:800;color:var(--gray-800);font-size:15px;"><created></div>
            </div>
        </div>

        <div style="margin-top:44px;text-align:center;padding:40px;background:linear-gradient(135deg,var(--primary-50),#f5f3ff,#ecfdf5);border-radius:var(--radius-xl);border:1px solid var(--primary-light);">
            <h3 style="font-size:22px;margin:0 0 10px;color:var(--gray-900);font-weight:800;">Ready to make an impact?</h3>
            <p style="margin:0 0 24px;color:var(--gray-500);font-size:16px;">Your next chapter starts with a single click.</p>
            <a-applyToJob class="nav-btn magnetic-btn" style="padding:15px 40px;font-size:16px;border-radius:16px;">
                Submit Your Application
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</div>
TPL;
$stmt->execute(['val' => $jobDetails, 'setting' => 'Content - Job Details']);
echo "Job Details updated\n";


// =====================================================
// APPLY FOR POSITION
// =====================================================
$apply = <<<'TPL'
<div class="main-content" style="max-width:800px;">
    <div style="margin-bottom:28px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-400);display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:var(--radius);background:#fff;border:1px solid var(--gray-200);font-weight:600;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>

    <div class="reveal" style="text-align:center;margin-bottom:36px;">
        <span class="section-tag">Application</span>
        <h1 style="font-size:32px;margin-bottom:8px;">Apply for <title></h1>
        <p style="color:var(--gray-500);margin:0;">Fields marked * are required.</p>
    </div>

    <div class="progress-bar-bg reveal"><div class="progress-bar-fill" id="formProgress"></div></div>
    <div style="text-align:right;margin:-24px 0 24px;font-size:13px;color:var(--gray-400);" id="formProgressLabel" class="reveal">0% complete</div>

    <div class="form-section reveal">
        <h2>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" style="vertical-align:-3px;margin-right:8px;"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Personal Information
        </h2>
        <div class="form-grid">
            <div><label>First Name <span style="color:var(--rose);">*</span></label><input-firstName req></div>
            <div><label>Last Name <span style="color:var(--rose);">*</span></label><input-lastName req></div>
            <div><label>Email <span style="color:var(--rose);">*</span></label><input-email req></div>
            <div><label>Confirm Email <span style="color:var(--rose);">*</span></label><input-emailconfirm req></div>
            <div><label>Phone <span style="color:var(--rose);">*</span></label><input-phone req></div>
            <div><label>City</label><input-city></div>
            <div><label>State</label><input-state></div>
            <div><label>Zip Code</label><input-zip></div>
            <div class="full"><label>Address</label><input-address></div>
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
            <div class="full"><label>How did you hear about us?</label><input-source></div>
        </div>
    </div>

    <div class="form-section reveal">
        <h2>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--emerald)" stroke-width="2" style="vertical-align:-3px;margin-right:8px;"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg>
            Resume &amp; Documents
        </h2>
        <div style="margin-bottom:24px;">
            <label>Upload Resume</label>
            <input-resumeUpload>
            <p style="font-size:13px;color:var(--gray-400);margin-top:8px;">PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</p>
        </div>
        <div>
            <label>Cover Letter / Notes</label>
            <input-extraNotes>
        </div>
    </div>

    <div class="reveal" style="text-align:center;padding:16px 0 48px;">
        <submit value="Submit Application" class="magnetic-btn" style="padding:16px 52px;min-width:300px;font-size:16px;border-radius:16px;">
    </div>
</div>
TPL;
$stmt->execute(['val' => $apply, 'setting' => 'Content - Apply for Position']);
echo "Apply updated\n";


// =====================================================
// THANKS
// =====================================================
$thanks = <<<'TPL'
<div class="main-content" style="text-align:center;padding:100px 24px;max-width:700px;">
    <div class="reveal-scale" style="margin-bottom:36px;">
        <div style="width:100px;height:100px;background:linear-gradient(135deg,#059669,#34d399);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;box-shadow:0 20px 60px rgba(5,150,105,0.35);animation:thanksPulse 2s ease-in-out infinite;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
        </div>
    </div>
    <style>@keyframes thanksPulse { 0%,100%{box-shadow:0 20px 60px rgba(5,150,105,0.35);} 50%{box-shadow:0 20px 80px rgba(5,150,105,0.5);} }</style>
    <h1 class="reveal" style="font-size:40px;margin-bottom:16px;">Application Submitted!</h1>
    <p class="reveal" style="font-size:19px;color:var(--gray-500);max-width:520px;margin:0 auto 20px;line-height:1.8;">Thank you for your interest. Our talent team will review your application and reach out within 3&ndash;5 business days.</p>
    <p class="reveal" style="font-size:15px;color:var(--gray-400);max-width:460px;margin:0 auto 44px;">A confirmation has been sent to your email.</p>
    <div class="reveal" style="display:flex;gap:14px;justify-content:center;flex-wrap:wrap;">
        <a href="index.php?m=careers&p=showAll" class="nav-btn magnetic-btn" style="padding:15px 36px;font-size:15px;border-radius:16px;">
            Browse More Jobs
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>
TPL;
$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Thanks updated\n";


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
            <a href="index.php?m=careers&p=showAll">Open Positions</a>
            <a href="index.php?m=careers&p=showAll">Departments</a>
        </div>
        <div class="footer-col">
            <h4>Company</h4>
            <a href="index.php?m=careers&p=showAll">About Us</a>
            <a href="index.php?m=careers&p=showAll">Culture</a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; 2026 Neutara. All rights reserved.</span>
        <span style="display:flex;gap:20px;">
            <a href="#" style="color:var(--gray-500);font-weight:600;">Privacy</a>
            <a href="#" style="color:var(--gray-500);font-weight:600;">Terms</a>
        </span>
    </div>
</div>
TPL;
$stmt->execute(['val' => $footer, 'setting' => 'Footer']);
echo "Footer updated\n";

echo "\n=== Premium V2 deployed! ===\n";
echo "Visit: http://localhost:8000/index.php?m=careers&p=showAll\n";
