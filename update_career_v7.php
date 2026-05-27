<?php
/**
 * Career Portal V7 — Premium Design
 * Full redesign matching provided screenshot:
 * - White header with nav + CTA buttons
 * - Hero with "Build What Matters Most" + illustration
 * - Feature cards, process steps, testimonials
 * - Job search section with blue gradient
 * - Dark navy footer with social icons
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
    --emerald: #059669;
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
    --shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
    --shadow-lg: 0 12px 32px rgba(0,0,0,0.1);
    --shadow-xl: 0 20px 48px rgba(0,0,0,0.12);
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
body { display: flex; flex-direction: column; min-height: 100vh; opacity:0; transition: opacity 0.5s; }
body.page-loaded { opacity: 1; }
a { color: var(--primary); text-decoration: none; transition: all 0.25s; }
a:hover { color: var(--primary-dark); }
a:visited { color: var(--primary); }
img { border: none; }

/* Reveals */
.reveal { opacity:0; transform:translateY(30px); transition: opacity 0.8s cubic-bezier(0.22,1,0.36,1), transform 0.8s cubic-bezier(0.22,1,0.36,1); }
.reveal.revealed { opacity:1; transform:translateY(0); }
.stagger-children > * { opacity:0; transform:translateY(20px); transition: opacity 0.6s ease, transform 0.6s ease; }
.stagger-children > *.stagger-visible { opacity:1; transform:translateY(0); }

/* ===== HEADER ===== */
.career-header {
    background: #fff;
    border-bottom: 1px solid var(--gray-100);
    position: sticky; top: 0; z-index: 1000;
    transition: box-shadow 0.3s;
}
.career-header.scrolled { box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
.header-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 32px;
    display: flex; align-items: center; justify-content: space-between; height: 64px;
}
.brand { display: flex; align-items: center; gap: 10px; text-decoration: none !important; }
.brand-icon {
    width: 36px; height: 36px; background: var(--primary); border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 800; font-size: 16px;
}
.brand-text { font: 700 20px var(--font); color: var(--gray-900); letter-spacing: -0.02em; }
.header-nav { display: flex; align-items: center; gap: 32px; }
.header-nav a {
    font: 500 14px var(--font); color: var(--gray-600) !important; transition: color 0.2s;
}
.header-nav a:hover { color: var(--gray-900) !important; }
.header-actions { display: flex; align-items: center; gap: 16px; }
.header-actions .login-link { font: 500 14px var(--font); color: var(--primary) !important; }
.btn-primary-sm {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 22px; background: var(--primary); color: #fff !important;
    font: 600 14px var(--font); border-radius: var(--radius); transition: all 0.25s;
}
.btn-primary-sm:hover { background: var(--primary-dark); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }

/* ===== HERO ===== */
.hero-section {
    max-width: 1200px; margin: 0 auto; padding: 60px 32px 40px;
    display: grid; grid-template-columns: 1fr 1fr; gap: 48px; align-items: center;
}
.hero-badge {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 6px 16px; border-radius: 50px;
    background: var(--primary-50); border: 1px solid var(--primary-light);
    font: 600 13px var(--font); color: var(--primary); margin-bottom: 20px;
}
.hero-badge .dot { width: 8px; height: 8px; border-radius: 50%; background: #34d399; box-shadow: 0 0 8px #34d399; }
.hero-section h1 {
    font: 900 48px var(--font) !important; color: var(--gray-900) !important;
    letter-spacing: -0.04em; line-height: 1.1; margin-bottom: 20px;
    -webkit-text-fill-color: var(--gray-900) !important; background: none !important;
}
.hero-section h1 .highlight { color: var(--primary); -webkit-text-fill-color: var(--primary); }
.hero-section > div > p { font: 400 16px/1.7 var(--font); color: var(--gray-500); margin-bottom: 32px; max-width: 440px; }
.hero-buttons { display: flex; gap: 14px; flex-wrap: wrap; margin-bottom: 40px; }
.btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 32px; background: var(--primary); color: #fff !important;
    font: 700 15px var(--font); border-radius: var(--radius-lg); transition: all 0.25s;
    box-shadow: 0 4px 16px rgba(37,99,235,0.25);
}
.btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,99,235,0.3); }
.btn-outline {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 32px; background: #fff; color: var(--gray-700) !important;
    font: 700 15px var(--font); border-radius: var(--radius-lg);
    border: 2px solid var(--gray-200); transition: all 0.25s;
}
.btn-outline:hover { border-color: var(--gray-300); background: var(--gray-50); transform: translateY(-2px); }

/* Hero Stats */
.hero-stats {
    display: flex; gap: 0; border-top: 1px solid var(--gray-200); padding-top: 24px;
}
.hero-stat {
    flex: 1; text-align: center; padding: 0 16px;
    border-right: 1px solid var(--gray-200);
}
.hero-stat:last-child { border-right: none; }
.hero-stat:first-child { padding-left: 0; text-align: left; }
.hero-stat .stat-value { font: 800 24px var(--font); color: var(--gray-900); display: block; letter-spacing: -0.02em; }
.hero-stat .stat-label { font: 400 12px var(--font); color: var(--gray-400); margin-top: 2px; }

.hero-illustration { display: flex; align-items: center; justify-content: center; }
.hero-illustration svg { width: 100%; max-width: 520px; height: auto; }

/* ===== FEATURES SECTION ===== */
.features-section {
    max-width: 1200px; margin: 0 auto; padding: 64px 32px;
}
.section-header { text-align: center; margin-bottom: 48px; }
.section-header h2 {
    font: 800 32px var(--font) !important; color: var(--gray-900) !important;
    letter-spacing: -0.03em; margin-bottom: 10px;
    -webkit-text-fill-color: var(--gray-900) !important; background: none !important;
    border: none !important; padding: 0 !important;
}
.section-header h2 .highlight { color: var(--primary); -webkit-text-fill-color: var(--primary); }
.section-header p { font: 400 15px var(--font); color: var(--gray-500); }

.features-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
}
.feature-card {
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-2xl); padding: 32px 24px; text-align: center;
    transition: all 0.35s;
}
.feature-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-lg); border-color: transparent; }
.feature-icon {
    width: 80px; height: 80px; margin: 0 auto 20px;
    display: flex; align-items: center; justify-content: center;
    border-radius: 20px;
}
.feature-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); }
.feature-icon.violet { background: linear-gradient(135deg, #ede9fe, #ddd6fe); }
.feature-icon.emerald { background: linear-gradient(135deg, #d1fae5, #a7f3d0); }
.feature-icon.sky { background: linear-gradient(135deg, #e0f2fe, #bae6fd); }
.feature-card h3 { font: 700 16px var(--font); color: var(--gray-900); margin: 0 0 8px; }
.feature-card p { font: 400 13px/1.6 var(--font); color: var(--gray-500); margin: 0; }

/* ===== PROCESS SECTION ===== */
.process-section {
    background: linear-gradient(180deg, #f0f4ff 0%, #e8eeff 100%);
    padding: 64px 0;
    position: relative;
    overflow: hidden;
}
.process-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 32px;
    display: grid; grid-template-columns: 1.2fr 1fr; gap: 48px; align-items: center;
}
.process-inner h2 {
    font: 800 32px var(--font) !important; color: var(--gray-900) !important;
    letter-spacing: -0.03em; margin-bottom: 8px;
    -webkit-text-fill-color: var(--gray-900) !important; background: none !important;
    border: none !important; padding: 0 !important;
}
.process-inner > div > p.process-sub { font: 400 15px var(--font); color: var(--gray-500); margin-bottom: 36px; }

.process-steps { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
.process-step { text-align: center; }
.process-num {
    width: 44px; height: 44px; border-radius: 50%;
    display: inline-flex; align-items: center; justify-content: center;
    background: var(--primary); color: #fff;
    font: 800 16px var(--font); margin-bottom: 12px;
    box-shadow: 0 4px 16px rgba(37,99,235,0.3);
}
.process-step h4 { font: 700 13px var(--font); color: var(--gray-900); margin: 0 0 4px; }
.process-step p { font: 400 12px/1.5 var(--font); color: var(--gray-500); margin: 0; }

.process-illustration { display: flex; align-items: center; justify-content: center; }
.process-illustration svg { width: 100%; max-width: 400px; height: auto; }

/* ===== TESTIMONIALS ===== */
.testimonials-section {
    max-width: 1200px; margin: 0 auto; padding: 64px 32px;
    display: grid; grid-template-columns: 1fr auto; gap: 48px; align-items: center;
}
.testimonials-section > div > h2 {
    font: 800 32px var(--font) !important; color: var(--gray-900) !important;
    letter-spacing: -0.03em; margin-bottom: 6px;
    -webkit-text-fill-color: var(--gray-900) !important; background: none !important;
    border: none !important; padding: 0 !important;
}
.testimonials-section > div > p { font: 400 15px var(--font); color: var(--gray-500); margin-bottom: 28px; }

.testimonials-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
.testimonial-card {
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl); padding: 24px; transition: all 0.3s;
}
.testimonial-card:hover { box-shadow: var(--shadow-md); transform: translateY(-4px); }
.testimonial-stars { color: #fbbf24; font-size: 14px; margin-bottom: 12px; letter-spacing: 2px; }
.testimonial-card .quote { font: 500 14px/1.6 var(--font); color: var(--gray-700); margin-bottom: 16px; }
.testimonial-author { display: flex; align-items: center; gap: 10px; }
.testimonial-avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-light), #c7d2fe);
    display: flex; align-items: center; justify-content: center;
    font: 700 14px var(--font); color: var(--primary);
}
.testimonial-author .name { font: 600 13px var(--font); color: var(--gray-800); }
.testimonial-author .role { font: 400 12px var(--font); color: var(--gray-400); }

.testimonials-illustration { display: flex; align-items: center; justify-content: flex-end; }
.testimonials-illustration svg { max-width: 280px; height: auto; }

/* ===== JOBS / SEARCH SECTION ===== */
.jobs-section {
    margin: 32px auto; max-width: 1200px; padding: 0 32px;
}
.jobs-banner {
    background: linear-gradient(135deg, #1e3a5f 0%, #1e40af 50%, #2563eb 100%);
    border-radius: var(--radius-2xl); padding: 48px;
    display: grid; grid-template-columns: auto 1fr; gap: 40px; align-items: center;
    position: relative; overflow: hidden;
}
.jobs-banner::before {
    content:''; position:absolute; inset:0;
    background: radial-gradient(circle at 80% 20%, rgba(59,130,246,0.3) 0%, transparent 50%),
                radial-gradient(circle at 20% 80%, rgba(37,99,235,0.2) 0%, transparent 50%);
}
.jobs-banner * { position: relative; z-index: 2; }
.jobs-banner-illustration svg { width: 180px; height: auto; }

.jobs-banner h2 {
    font: 800 28px var(--font) !important; color: #fff !important;
    -webkit-text-fill-color: #fff !important; margin-bottom: 4px;
    background: none !important; border: none !important; padding: 0 !important;
}
.jobs-badge {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 4px 12px; border-radius: 50px; background: rgba(52,211,153,0.15);
    font: 600 12px var(--font); color: #34d399; margin-left: 12px;
}
.jobs-banner > div > p { font: 400 15px var(--font); color: rgba(255,255,255,0.7); margin-bottom: 20px; }

.search-row {
    display: flex; gap: 12px; margin-bottom: 16px;
}
.search-input-wrap {
    flex: 1; display: flex; align-items: center; gap: 10px;
    padding: 0 16px; background: #fff; border-radius: var(--radius);
    height: 48px;
}
.search-input-wrap svg { color: var(--gray-400); flex-shrink: 0; }
.search-input-wrap input {
    border: none; outline: none; flex: 1; font: 400 14px var(--font);
    color: var(--gray-800); background: transparent; width: 100%;
}
.search-input-wrap input::placeholder { color: var(--gray-400); }
.search-btn {
    padding: 0 28px; height: 48px; background: var(--primary); color: #fff;
    font: 700 14px var(--font); border: none; border-radius: var(--radius);
    cursor: pointer; transition: all 0.25s; white-space: nowrap;
}
.search-btn:hover { background: #3b82f6; }

.popular-searches { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.popular-searches span { font: 600 12px var(--font); color: rgba(255,255,255,0.6); }
.popular-tag {
    padding: 5px 14px; border-radius: 50px;
    background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.15);
    font: 500 12px var(--font); color: rgba(255,255,255,0.8);
    transition: all 0.2s; cursor: pointer;
}
.popular-tag:hover { background: rgba(255,255,255,0.2); }

/* Job table (actual listings) */
.job-listings { margin-top: 32px; }
.job-listings h3 {
    font: 700 20px var(--font); color: var(--gray-900); margin-bottom: 16px;
}
table.sortable {
    width: 100%; border-collapse: separate; border-spacing: 0;
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-xl); overflow: hidden;
}
tr.rowHeading { background: var(--gray-50) !important; }
tr.rowHeading th {
    padding: 14px 24px; color: var(--gray-500); font: 700 11px var(--font);
    text-transform: uppercase; letter-spacing: 0.08em;
    border: none; border-bottom: 1px solid var(--gray-200); text-align: left;
    background: transparent;
}
tr.evenTableRow, tr.oddTableRow {
    background: #fff !important; transition: all 0.2s; cursor: pointer;
}
tr.evenTableRow:hover, tr.oddTableRow:hover { background: var(--primary-50) !important; }
tr.evenTableRow td, tr.oddTableRow td {
    padding: 18px 24px; border: none; border-bottom: 1px solid var(--gray-100);
    font: 500 14px var(--font); color: var(--gray-600); vertical-align: middle;
    background: transparent;
}
tr.evenTableRow:last-child td, tr.oddTableRow:last-child td { border-bottom: none; }
tr.evenTableRow td a, tr.oddTableRow td a {
    color: var(--primary); font-weight: 700; -webkit-text-fill-color: var(--primary); background: none;
}

/* ===== CTA CARDS ===== */
.cta-cards {
    max-width: 1200px; margin: 32px auto; padding: 0 32px;
    display: grid; grid-template-columns: 1fr 1fr; gap: 24px;
}
.cta-card {
    background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius-2xl);
    padding: 32px; display: flex; align-items: center; gap: 20px;
    transition: all 0.3s;
}
.cta-card:hover { box-shadow: var(--shadow-md); transform: translateY(-4px); }
.cta-card-icon {
    width: 56px; height: 56px; border-radius: 16px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.cta-card-icon.blue { background: var(--primary-50); color: var(--primary); }
.cta-card-icon.violet { background: #ede9fe; color: #7c3aed; }
.cta-card h3 { font: 700 16px var(--font); color: var(--gray-900); margin: 0 0 6px; }
.cta-card p { font: 400 13px/1.5 var(--font); color: var(--gray-500); margin: 0 0 14px; }
.cta-card .cta-link {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 20px; border-radius: var(--radius);
    font: 600 13px var(--font); transition: all 0.2s;
}
.cta-card .cta-link.blue { background: var(--primary); color: #fff !important; }
.cta-card .cta-link.blue:hover { background: var(--primary-dark); }
.cta-card .cta-link.violet { background: #7c3aed; color: #fff !important; }
.cta-card .cta-link.violet:hover { background: #6d28d9; }

/* ===== FOOTER ===== */
.career-footer {
    background: var(--gray-900); margin-top: auto; color: var(--gray-400);
}
.footer-main {
    max-width: 1200px; margin: 0 auto; padding: 48px 32px;
    display: grid; grid-template-columns: 1.5fr 2fr 1fr; gap: 48px; align-items: start;
}
.footer-brand .brand-icon { background: var(--primary); }
.footer-brand .brand-text { color: #fff; }
.footer-brand p { font: 400 13px/1.7 var(--font); color: var(--gray-400); margin-top: 12px; max-width: 240px; }
.footer-links { display: flex; gap: 40px; justify-content: center; }
.footer-links a {
    font: 500 14px var(--font); color: var(--gray-400) !important; transition: color 0.2s;
}
.footer-links a:hover { color: #fff !important; }
.footer-social { display: flex; gap: 12px; justify-content: flex-end; }
.footer-social a {
    width: 38px; height: 38px; border-radius: 10px;
    background: rgba(255,255,255,0.06);
    display: flex; align-items: center; justify-content: center;
    color: var(--gray-400); transition: all 0.25s;
}
.footer-social a:hover { background: rgba(255,255,255,0.12); color: #fff; transform: translateY(-2px); }

.footer-bottom {
    max-width: 1200px; margin: 0 auto; padding: 20px 32px;
    border-top: 1px solid rgba(255,255,255,0.06);
    display: flex; justify-content: space-between; align-items: center;
    font: 400 13px var(--font); color: var(--gray-500);
}
.footer-legal { display: flex; gap: 24px; }
.footer-legal a { font: 500 13px var(--font); color: var(--gray-500) !important; }
.footer-legal a:hover { color: #fff !important; }

/* ===== JOB DETAILS ===== */
.job-detail-card {
    background: #fff; border: 1px solid var(--gray-200);
    border-radius: var(--radius-2xl); padding: 48px; box-shadow: var(--shadow-md);
}
.job-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px; border-radius: 50px; font: 600 13px var(--font); transition: all 0.25s;
}
.job-tag:hover { transform: translateY(-2px); }
.job-tag.location { background: #eff6ff; color: #2563eb; }
.job-tag.type { background: #f0fdf4; color: #059669; }
.job-tag.time { background: #fefce8; color: #ca8a04; }
.job-tag.openings { background: #faf5ff; color: #7c3aed; }
#detailsTable { display: none; }
div#discriptive { float: none; width: 100%; margin: 0; }
div#detailsTools { display: none; }

/* ===== FORM ===== */
.form-section {
    background: #fff; border: 1px solid var(--gray-200); border-radius: var(--radius-xl);
    padding: 32px; margin-bottom: 24px; transition: box-shadow 0.25s;
}
.form-section:hover { box-shadow: var(--shadow-md); }
.form-section::before { display: none; }
.form-section h2 { font: 700 18px var(--font); color: var(--gray-900); margin: 0 0 24px; border:none; padding:0; }
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.form-grid .full { grid-column: 1 / -1; }
label { font: 600 13px var(--font); color: var(--gray-700); display: block; margin-bottom: 6px; }
label .req { color: var(--danger); margin-left: 2px; }

input.inputbox, input.inputBoxName, input.inputBoxNormal, input.inputBoxArea,
input.inputBoxFile, input#documentFile, input[type="text"], input[type="email"], input[type="tel"] {
    width: 100%; padding: 11px 16px; border: 1px solid var(--gray-300); border-radius: var(--radius);
    font: 400 14px var(--font); color: var(--gray-800); transition: all 0.25s; background: #fff; outline: none;
}
input.inputbox:focus, input.inputBoxName:focus, input.inputBoxNormal:focus,
input[type="text"]:focus, input[type="email"]:focus {
    border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
}
textarea, textarea.inputBoxArea, textarea.inputboxlarge {
    width: 100%; padding: 11px 16px; min-height: 100px; border: 1px solid var(--gray-300);
    border-radius: var(--radius); font: 400 14px var(--font); color: var(--gray-800);
    resize: vertical; transition: all 0.25s; background: #fff; outline: none;
}
textarea:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
select, select.inputBoxNormal {
    width: 100%; padding: 11px 16px; border: 1px solid var(--gray-300); border-radius: var(--radius);
    font: 400 14px var(--font); color: var(--gray-800); background: #fff; outline: none; cursor: pointer;
    appearance: none; -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M2 4l4 4 4-4'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; transition: all 0.25s;
}
select:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
input.submitbutton, input.submitButton, input[type="submit"] {
    padding: 14px 40px; width: auto; min-width: 240px; background: var(--primary); color: #fff;
    font: 700 15px var(--font); border: none; border-radius: var(--radius); cursor: pointer;
    transition: all 0.25s; box-shadow: 0 4px 16px rgba(37,99,235,0.25);
}
input.submitbutton:hover, input[type="submit"]:hover { background: var(--primary-dark); transform: translateY(-2px); }

div.applyBoxLeft, div.applyBoxRight { float:none; width:100%; max-width:100%; border:none; box-shadow:none; padding:0; background:transparent; margin:0; }
div.applyBoxLeft div, div.applyBoxRight div { display:none; }
div.applyBoxLeft table, div.applyBoxRight table { display:none; }
td.label { display:none; }
.progress-bar-bg { width:100%; height:4px; background:var(--gray-200); border-radius:2px; overflow:hidden; margin-bottom:24px; }
.progress-bar-fill { height:100%; width:0%; background:var(--primary); border-radius:2px; transition:width 0.5s; }

/* Apply page V4 classes */
.step-indicator { display:flex; align-items:center; gap:0; padding:24px 0; border-bottom:1px solid var(--gray-200); margin-bottom:32px; overflow-x:auto; }
.step-item { display:flex; align-items:center; gap:10px; white-space:nowrap; flex:1; }
.step-item:not(:last-child)::after { content:''; flex:1; height:1px; background:var(--gray-200); margin:0 16px; min-width:24px; }
.step-number { width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; font:600 13px var(--font); flex-shrink:0; }
.step-number.active { background:var(--primary); color:#fff; }
.step-number.inactive { background:var(--gray-100); color:var(--gray-500); border:1.5px solid var(--gray-300); }
.step-number.completed { background:var(--success); color:#fff; }
.step-label { font:500 14px var(--font); color:var(--gray-700); }
.step-label.inactive { color:var(--gray-400); }
.apply-layout { display:grid; grid-template-columns:1fr 340px; gap:32px; align-items:start; }
.progress-section { display:flex; align-items:center; gap:12px; margin-bottom:8px; }
.progress-label { font:500 13px var(--font); color:var(--gray-500); white-space:nowrap; }
.form-section-header { display:flex; align-items:flex-start; gap:12px; margin-bottom:24px; }
.form-section-icon { width:40px; height:40px; background:var(--primary-50); border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
.form-section-icon svg { color:var(--primary); }
.form-section-title h2 { font:600 16px var(--font); color:var(--gray-900); margin-bottom:2px; }
.form-section-title p { font:400 13px var(--font); color:var(--gray-500); margin:0; }
.file-upload-area { border:2px dashed var(--gray-300); border-radius:var(--radius-lg); padding:32px 24px; text-align:center; transition:all 0.2s; cursor:pointer; background:var(--gray-50); }
.file-upload-area:hover { border-color:var(--primary); background:var(--primary-50); }
.file-upload-area .upload-icon { margin-bottom:8px; color:var(--gray-400); }
.file-upload-area p { font:400 14px var(--font); color:var(--gray-500); margin:0 0 4px; }
.file-upload-area .or-text { font:400 13px var(--font); color:var(--gray-400); margin:4px 0 12px; }
.choose-file-btn { display:inline-block; padding:8px 20px; background:var(--primary); color:#fff; font:600 13px var(--font); border-radius:6px; cursor:pointer; transition:all 0.2s; border:none; }
.choose-file-btn:hover { background:var(--primary-dark); }
.file-types { font:400 12px var(--font); color:var(--gray-400); margin-top:12px; }
input.inputBoxFile, input[type="file"] { display:none; }
.form-actions { display:flex; align-items:center; justify-content:space-between; padding:24px 0 48px; }
.btn-draft { display:inline-flex; align-items:center; gap:8px; padding:12px 24px; border:1px solid var(--gray-300); border-radius:var(--radius); background:#fff; color:var(--gray-700); font:600 14px var(--font); cursor:pointer; transition:all 0.2s; }
.btn-draft:hover { background:var(--gray-50); border-color:var(--gray-400); }
.btn-submit { display:inline-flex; align-items:center; gap:8px; padding:12px 32px; background:var(--primary); color:#fff; font:600 14px var(--font); border:none; border-radius:var(--radius); cursor:pointer; transition:all 0.2s; }
.btn-submit:hover { background:var(--primary-dark); }
.apply-sidebar { position:sticky; top:80px; }
.summary-card { background:#fff; border:1px solid var(--gray-200); border-radius:var(--radius-xl); padding:24px; }
.summary-card h3 { font:600 16px var(--font); color:var(--gray-900); margin-bottom:4px; }
.summary-card .summary-subtitle { font:400 13px var(--font); color:var(--gray-500); margin-bottom:20px; }
.summary-item { display:flex; align-items:flex-start; gap:12px; padding:12px 0; }
.summary-item:not(:last-child) { border-bottom:1px solid var(--gray-100); }
.summary-dot { width:20px; height:20px; border-radius:50%; border:2px solid var(--gray-300); flex-shrink:0; margin-top:2px; }
.summary-dot.completed { border-color:var(--success); background:var(--success); }
.summary-dot.active { border-color:var(--primary); background:var(--primary); }
.summary-item-text h4 { font:500 14px var(--font); color:var(--gray-700); margin:0 0 2px; }
.summary-item-text p { font:400 12px var(--font); color:var(--gray-400); margin:0; }
.tip-box { margin-top:20px; padding:16px; background:var(--primary-50); border-radius:var(--radius-lg); border:1px solid var(--primary-light); }
.tip-box .tip-header { display:flex; align-items:center; gap:8px; margin-bottom:8px; }
.tip-box .tip-header svg { color:var(--primary); }
.tip-box .tip-header span { font:600 13px var(--font); color:var(--primary-dark); }
.tip-box p { font:400 13px/1.5 var(--font); color:var(--primary-dark); margin:0; }

/* ===== GENERIC ===== */
h1 { font:800 32px var(--font); color:var(--gray-900); letter-spacing:-0.03em; margin:0 0 12px; background:none; -webkit-text-fill-color:var(--gray-900); }
h2 { font:700 24px var(--font); color:var(--gray-800); margin:0 0 12px; border:none; padding:0; }
h3 { font:600 16px var(--font); color:var(--gray-700); margin:0 0 6px; }
p { font:400 14px/1.7 var(--font); color:var(--gray-500); }
strong { font-weight:700; color:var(--gray-800); }
#careerContent { clear:both; padding:0; flex:1; }
#poweredCATS { display:none; }

/* ===== RESPONSIVE ===== */
@media (max-width:1024px) {
    .hero-section { grid-template-columns:1fr; gap:32px; padding:40px 24px; }
    .hero-illustration { display:none; }
    .features-grid { grid-template-columns:repeat(2,1fr); }
    .process-inner { grid-template-columns:1fr; }
    .process-illustration { display:none; }
    .testimonials-section { grid-template-columns:1fr; }
    .testimonials-illustration { display:none; }
    .process-steps { grid-template-columns:repeat(2,1fr); }
}
@media (max-width:768px) {
    .header-nav { display:none; }
    .hero-section h1 { font-size:36px !important; }
    .features-grid { grid-template-columns:1fr; }
    .testimonials-grid { grid-template-columns:1fr; }
    .cta-cards { grid-template-columns:1fr; }
    .jobs-banner { grid-template-columns:1fr; padding:32px; }
    .jobs-banner-illustration { display:none; }
    .search-row { flex-direction:column; }
    .footer-main { grid-template-columns:1fr; gap:32px; text-align:center; }
    .footer-links { justify-content:center; flex-wrap:wrap; gap:20px; }
    .footer-social { justify-content:center; }
    .footer-bottom { flex-direction:column; gap:12px; text-align:center; }
    .footer-legal { justify-content:center; }
    .apply-layout { grid-template-columns:1fr; }
    .apply-sidebar { position:static; }
    .form-grid { grid-template-columns:1fr; }
    .step-label { display:none; }
}
@media (max-width:480px) {
    .hero-section h1 { font-size:28px !important; }
    .hero-buttons { flex-direction:column; }
    .hero-stats { flex-wrap:wrap; }
    .hero-stat { border-right:none; flex:none; width:50%; padding:8px 0; text-align:center !important; }
    .process-steps { grid-template-columns:1fr 1fr; }
    .section-header h2, .process-inner h2, .testimonials-section > div > h2, .jobs-banner h2 { font-size:24px !important; }
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
            <div class="brand-text">Neutara</div>
        </a>
        <nav class="header-nav">
            <a href="index.php?m=careers&p=showAll">Home</a>
            <a href="index.php?m=careers&p=showAll#positions">Jobs</a>
            <a href="index.php?m=careers&p=showAll#features">Companies</a>
            <a href="index.php?m=careers&p=showAll#process">Resources</a>
            <a href="index.php?m=careers&p=showAll#about">About Us</a>
        </nav>
        <div class="header-actions">
            <a href="index.php?m=careers&p=showAll#positions" class="login-link">Login</a>
            <a href="index.php?m=careers&p=showAll#positions" class="btn-primary-sm">Post Your Resume</a>
        </div>
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
// CONTENT - SEARCH RESULTS (Main Landing Page)
// =====================================================
$search = <<<'TPL'
<!-- HERO -->
<div class="hero-section">
    <div class="reveal">
        <div class="hero-badge"><span class="dot"></span> Your Career, Elevated</div>
        <h1>Build What<br>Matters <span class="highlight">Most</span></h1>
        <p>Join a platform where top talents find the best career opportunities and companies build winning teams.</p>
        <div class="hero-buttons">
            <a href="#positions" class="btn-primary">Find Opportunity</a>
            <a href="#positions" class="btn-outline">Post a Job</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat"><span class="stat-value counter" data-target="1" data-suffix="M+">0</span><span class="stat-label">Active Talents</span></div>
            <div class="hero-stat"><span class="stat-value counter" data-target="5" data-suffix="K+">0</span><span class="stat-label">Top Companies</span></div>
            <div class="hero-stat"><span class="stat-value">4.8&#9733;</span><span class="stat-label">User Ratings</span></div>
            <div class="hero-stat"><span class="stat-value counter" data-target="98" data-suffix="%">0</span><span class="stat-label">Success Rate</span></div>
        </div>
    </div>
    <div class="hero-illustration reveal">
        <svg viewBox="0 0 520 440" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Floating blocks background -->
            <rect x="260" y="30" width="140" height="140" rx="28" fill="#DBEAFE" transform="rotate(8 330 100)"/>
            <rect x="330" y="180" width="120" height="120" rx="24" fill="#EFF6FF" transform="rotate(-5 390 240)"/>
            <!-- Main block with N -->
            <rect x="280" y="100" width="130" height="130" rx="28" fill="#2563EB" transform="rotate(6 345 165)"/>
            <text x="335" y="178" fill="white" font-family="Inter" font-weight="900" font-size="52" text-anchor="middle">N</text>
            <!-- Person -->
            <circle cx="200" cy="160" r="36" fill="#FCD34D"/>
            <circle cx="190" cy="154" r="4" fill="#1E293B"/>
            <circle cx="212" cy="154" r="4" fill="#1E293B"/>
            <path d="M195 170Q203 177 212 170" stroke="#1E293B" stroke-width="2.5" stroke-linecap="round"/>
            <path d="M164 155Q168 120 200 124Q232 120 236 155" fill="#1E293B" opacity="0.85"/>
            <!-- Body -->
            <rect x="172" y="195" width="56" height="60" rx="14" fill="#2563EB"/>
            <rect x="155" y="255" width="90" height="65" rx="18" fill="#93C5FD"/>
            <!-- Laptop -->
            <rect x="220" y="240" width="80" height="50" rx="6" fill="#1E3A5F"/>
            <rect x="225" y="245" width="70" height="38" rx="4" fill="#60A5FA"/>
            <rect x="210" y="290" width="100" height="5" rx="2.5" fill="#94A3B8"/>
            <!-- Arms -->
            <rect x="215" y="222" width="35" height="10" rx="5" fill="#FCD34D"/>
            <rect x="168" y="222" width="35" height="10" rx="5" fill="#FCD34D"/>
            <!-- Legs -->
            <rect x="175" y="318" width="15" height="40" rx="6" fill="#1E293B"/>
            <rect x="210" y="318" width="15" height="40" rx="6" fill="#1E293B"/>
            <rect x="168" y="354" width="28" height="10" rx="5" fill="#334155"/>
            <rect x="204" y="354" width="28" height="10" rx="5" fill="#334155"/>
            <!-- Magnifying glass -->
            <circle cx="440" cy="80" r="32" stroke="#60A5FA" stroke-width="6" fill="#EFF6FF"/>
            <line x1="462" y1="104" x2="480" y2="122" stroke="#60A5FA" stroke-width="6" stroke-linecap="round"/>
            <!-- Plant -->
            <rect x="430" y="340" width="40" height="36" rx="8" fill="#93C5FD"/>
            <ellipse cx="450" cy="326" rx="18" ry="22" fill="#34D399"/>
            <ellipse cx="436" cy="318" rx="12" ry="16" fill="#6EE7B7"/>
            <!-- Small decorations -->
            <circle cx="80" cy="80" r="10" fill="#DBEAFE"/>
            <circle cx="120" cy="380" r="8" fill="#C7D2FE"/>
            <rect x="60" y="200" width="20" height="20" rx="6" fill="#EFF6FF" transform="rotate(-15 70 210)"/>
        </svg>
    </div>
</div>

<!-- FEATURES -->
<div class="features-section" id="features">
    <div class="section-header reveal">
        <h2>Where Careers Reach Their <span class="highlight">Peak</span></h2>
        <p>We connect great people with great opportunities every day.</p>
    </div>
    <div class="features-grid stagger-children">
        <div class="feature-card">
            <div class="feature-icon blue">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            </div>
            <h3>Effortless Discovery</h3>
            <p>Find roles that match your skills and ambitions.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon violet">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.5"><path d="M12 2L15 8.5L22 9.5L17 14.5L18 21.5L12 18L6 21.5L7 14.5L2 9.5L9 8.5L12 2Z"/></svg>
            </div>
            <h3>Top Companies</h3>
            <p>Connect with innovative companies hiring now.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon emerald">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#059669" stroke-width="1.5"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>
            </div>
            <h3>Career Growth</h3>
            <p>Upskill and grow with resources that matter.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon sky">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#0284c7" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/></svg>
            </div>
            <h3>Trusted Platform</h3>
            <p>Secure, transparent, and built for your success.</p>
        </div>
    </div>
</div>

<!-- PROCESS -->
<div class="process-section" id="process">
    <div class="process-inner">
        <div class="reveal">
            <h2>Simple. Transparent. Fast.</h2>
            <p class="process-sub">A better way to find or hire the right talent.</p>
            <div class="process-steps stagger-children">
                <div class="process-step">
                    <div class="process-num">1</div>
                    <h4>Create Profile</h4>
                    <p>Build your profile in minutes.</p>
                </div>
                <div class="process-step">
                    <div class="process-num">2</div>
                    <h4>Discover Opportunities</h4>
                    <p>Find jobs or candidates that fit.</p>
                </div>
                <div class="process-step">
                    <div class="process-num">3</div>
                    <h4>Connect &amp; Interview</h4>
                    <p>Easily connect and communicate.</p>
                </div>
                <div class="process-step">
                    <div class="process-num">4</div>
                    <h4>Get Hired</h4>
                    <p>Start your new journey with confidence.</p>
                </div>
            </div>
        </div>
        <div class="process-illustration reveal">
            <svg viewBox="0 0 400 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="60" y="40" width="280" height="200" rx="20" fill="#E0E7FF"/>
                <rect x="80" y="60" width="240" height="160" rx="12" fill="#fff" stroke="#DBEAFE" stroke-width="2"/>
                <rect x="100" y="84" width="140" height="12" rx="4" fill="#DBEAFE"/>
                <rect x="100" y="106" width="100" height="8" rx="3" fill="#E2E8F0"/>
                <rect x="100" y="122" width="120" height="8" rx="3" fill="#E2E8F0"/>
                <rect x="100" y="146" width="80" height="32" rx="8" fill="#2563EB"/>
                <text x="140" y="167" fill="white" font-family="Inter" font-weight="700" font-size="12" text-anchor="middle">Apply Now</text>
                <circle cx="300" cy="100" r="30" fill="#2563EB"/>
                <polyline points="288,100 296,108 314,90" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                <rect x="110" y="240" width="180" height="8" rx="4" fill="#CBD5E1"/>
                <ellipse cx="200" cy="280" rx="100" ry="10" fill="#E2E8F0"/>
            </svg>
        </div>
    </div>
</div>

<!-- TESTIMONIALS -->
<div class="testimonials-section" id="about">
    <div>
        <div class="reveal">
            <h2>People Love Working Here</h2>
            <p>See what our community has to say.</p>
        </div>
        <div class="testimonials-grid stagger-children">
            <div class="testimonial-card">
                <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9734;</div>
                <p class="quote">&ldquo;Neutara made finding my dream job simple and stress-free.&rdquo;</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">A</div>
                    <div><div class="name">Alex R.</div><div class="role">Product Designer</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p class="quote">&ldquo;We hired top talent faster than ever before.&rdquo;</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">S</div>
                    <div><div class="name">Sarah K.</div><div class="role">HR Manager</div></div>
                </div>
            </div>
            <div class="testimonial-card">
                <div class="testimonial-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                <p class="quote">&ldquo;The platform is intuitive, transparent, and effective.&rdquo;</p>
                <div class="testimonial-author">
                    <div class="testimonial-avatar">J</div>
                    <div><div class="name">John D.</div><div class="role">Software Engineer</div></div>
                </div>
            </div>
        </div>
    </div>
    <div class="testimonials-illustration reveal">
        <svg viewBox="0 0 280 320" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Person 1 -->
            <circle cx="120" cy="120" r="32" fill="#FCD34D"/>
            <circle cx="110" cy="114" r="3.5" fill="#1E293B"/>
            <circle cx="130" cy="114" r="3.5" fill="#1E293B"/>
            <path d="M113 128Q120 134 128 128" stroke="#1E293B" stroke-width="2" stroke-linecap="round"/>
            <path d="M88 112Q92 82 120 86Q148 82 152 112" fill="#1E293B" opacity="0.8"/>
            <rect x="100" y="152" width="40" height="50" rx="12" fill="#2563EB"/>
            <rect x="90" y="200" width="60" height="40" rx="10" fill="#1E293B"/>
            <!-- Person 2 -->
            <circle cx="200" cy="140" r="28" fill="#FCD34D"/>
            <circle cx="192" cy="135" r="3" fill="#1E293B"/>
            <circle cx="210" cy="135" r="3" fill="#1E293B"/>
            <path d="M195 148Q201 153 208 148" stroke="#1E293B" stroke-width="2" stroke-linecap="round"/>
            <path d="M172 132Q176 108 200 112Q224 108 228 132" fill="#1E293B" opacity="0.8"/>
            <rect x="182" y="168" width="36" height="44" rx="10" fill="#60A5FA"/>
            <rect x="175" y="210" width="50" height="36" rx="8" fill="#1E293B"/>
            <!-- Decorations -->
            <circle cx="60" cy="200" r="8" fill="#DBEAFE"/>
            <circle cx="250" cy="80" r="6" fill="#C7D2FE"/>
        </svg>
    </div>
</div>

<!-- JOBS SEARCH + LISTINGS -->
<div class="jobs-section" id="positions">
    <div class="jobs-banner reveal">
        <div class="jobs-banner-illustration">
            <svg viewBox="0 0 180 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="20" y="20" width="140" height="170" rx="16" fill="rgba(255,255,255,0.1)" stroke="rgba(255,255,255,0.2)" stroke-width="1.5"/>
                <rect x="40" y="50" width="100" height="12" rx="4" fill="rgba(255,255,255,0.2)"/>
                <rect x="40" y="72" width="70" height="8" rx="3" fill="rgba(255,255,255,0.12)"/>
                <rect x="40" y="88" width="85" height="8" rx="3" fill="rgba(255,255,255,0.12)"/>
                <rect x="40" y="112" width="100" height="40" rx="8" fill="rgba(255,255,255,0.08)"/>
                <circle cx="140" cy="40" r="24" fill="#34d399"/>
                <polyline points="130,40 138,48 152,34" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <div>
            <h2>Find Your Perfect Role <span class="jobs-badge">&#10003; Job updates daily</span></h2>
            <p>Discover opportunities that match your skills and passion.</p>
            <div class="search-row">
                <div class="search-input-wrap">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                    <input type="text" placeholder="Job title, keyword, or company" disabled>
                </div>
                <div class="search-input-wrap" style="max-width:220px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <input type="text" placeholder="Location" disabled>
                </div>
                <a href="#job-table" class="search-btn">Find Jobs</a>
            </div>
            <div class="popular-searches">
                <span>Popular Searches:</span>
                <span class="popular-tag">Product Designer</span>
                <span class="popular-tag">Software Engineer</span>
                <span class="popular-tag">Marketing Manager</span>
                <span class="popular-tag">Data Analyst</span>
            </div>
        </div>
    </div>

    <div class="job-listings reveal" id="job-table">
        <h3><numberOfSearchResults> Open Position(s)</h3>
        <searchResultsTableUnformatted>
    </div>
</div>

<!-- CTA CARDS -->
<div class="cta-cards">
    <div class="cta-card reveal">
        <div class="cta-card-icon blue">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
        </div>
        <div>
            <h3>Ready to Hire Top Talent?</h3>
            <p>Post your job and connect with thousands of qualified candidates.</p>
            <a href="index.php?m=careers&p=showAll" class="cta-link blue">Post a Job</a>
        </div>
    </div>
    <div class="cta-card reveal">
        <div class="cta-card-icon violet">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
        </div>
        <div>
            <h3>Explore Resources</h3>
            <p>Career tips, insights, and tools to grow your future.</p>
            <a href="index.php?m=careers&p=showAll" class="cta-link violet">Visit Resources</a>
        </div>
    </div>
</div>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        document.body.classList.add('page-loaded');

        // Scroll reveals
        if ('IntersectionObserver' in window) {
            var obs = new IntersectionObserver(function(entries) {
                entries.forEach(function(e) { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } });
            }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
            document.querySelectorAll('.reveal').forEach(function(el) { obs.observe(el); });

            document.querySelectorAll('.stagger-children').forEach(function(c) {
                var sobs = new IntersectionObserver(function(entries) {
                    if (entries[0].isIntersecting) {
                        for (var i = 0; i < c.children.length; i++) {
                            (function(idx) { setTimeout(function() { c.children[idx].classList.add('stagger-visible'); }, idx * 120); })(i);
                        }
                        sobs.unobserve(c);
                    }
                }, { threshold: 0.1 });
                sobs.observe(c);
            });
        } else {
            document.querySelectorAll('.reveal').forEach(function(el) { el.classList.add('revealed'); });
        }

        // Counter animation
        document.querySelectorAll('.counter').forEach(function(el) {
            var target = parseInt(el.getAttribute('data-target')) || 0;
            var suffix = el.getAttribute('data-suffix') || '';
            if (!target) return;
            el.textContent = '0' + suffix;
            var co = new IntersectionObserver(function(entries) {
                if (entries[0].isIntersecting) {
                    var start = null, dur = 1200;
                    (function anim(ts) {
                        if (!start) start = ts;
                        var p = Math.min((ts - start) / dur, 1);
                        el.textContent = Math.floor((1 - Math.pow(1 - p, 3)) * target) + suffix;
                        if (p < 1) requestAnimationFrame(anim);
                    })(performance.now());
                    co.unobserve(el);
                }
            }, { threshold: 0.5 });
            co.observe(el);
        });

        // Header scroll
        var h = document.querySelector('.career-header');
        window.addEventListener('scroll', function() { h.classList.toggle('scrolled', window.scrollY > 20); });
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
            <span class="job-tag location"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg> <city>, <state></span>
            <span class="job-tag type"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg> <type></span>
            <span class="job-tag time"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg> Posted <daysOld> days ago</span>
            <span class="job-tag openings"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg> <openings> opening(s)</span>
        </div>
        <div style="margin-bottom:40px;">
            <a-applyToJob class="btn-primary" style="text-decoration:none;">Apply Now <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
        </div>
        <div style="border-top:1px solid var(--gray-200);padding-top:32px;">
            <h2 style="font-size:22px;margin:0 0 20px;">About This Role</h2>
            <div style="color:var(--gray-600);line-height:1.9;font-size:15px;"><description></div>
        </div>
        <div style="margin-top:40px;display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:12px;">
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;"><div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Location</div><div style="font:700 14px var(--font);color:var(--gray-800);"><city>, <state></div></div>
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;"><div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Type</div><div style="font:700 14px var(--font);color:var(--gray-800);"><type></div></div>
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;"><div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Recruiter</div><div style="font:700 14px var(--font);color:var(--gray-800);"><recruiter></div></div>
            <div style="background:var(--gray-50);border-radius:var(--radius-lg);padding:20px;"><div style="font:700 11px var(--font);color:var(--gray-400);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:6px;">Posted</div><div style="font:700 14px var(--font);color:var(--gray-800);"><created></div></div>
        </div>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded',function(){document.body.classList.add('page-loaded');document.querySelectorAll('.reveal').forEach(function(el){el.classList.add('revealed');});});</script>
TPL;
$stmt->execute(['val' => $jobDetails, 'setting' => 'Content - Job Details']);
echo "Job Details updated\n";


// =====================================================
// APPLY PAGE
// =====================================================
$apply = <<<'TPL'
<div style="max-width:1100px;margin:0 auto;padding:0 32px;">
    <div style="padding-top:24px;margin-bottom:16px;" class="reveal">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:var(--gray-500);display:inline-flex;align-items:center;gap:6px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Back to Jobs
        </a>
    </div>
    <div class="reveal" style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
        <div><h1 style="font-size:26px;margin-bottom:4px;">Apply for <title></h1><p style="color:var(--gray-500);margin:0;font-size:14px;">Fields marked <span style="color:var(--danger);">*</span> are required.</p></div>
        <div style="text-align:right;min-width:180px;"><div class="progress-section"><span class="progress-label" id="formProgressLabel">0% complete</span></div><div class="progress-bar-bg" style="margin-top:6px;"><div class="progress-bar-fill" id="formProgress"></div></div></div>
    </div>
    <div class="step-indicator reveal">
        <div class="step-item"><div class="step-number active">1</div><span class="step-label">Personal Info</span></div>
        <div class="step-item"><div class="step-number inactive">2</div><span class="step-label inactive">Background</span></div>
        <div class="step-item"><div class="step-number inactive">3</div><span class="step-label inactive">Documents</span></div>
        <div class="step-item"><div class="step-number inactive">4</div><span class="step-label inactive">Review</span></div>
    </div>
    <div class="apply-layout">
        <div>
            <div class="form-section reveal">
                <div class="form-section-header"><div class="form-section-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><div class="form-section-title"><h2>Personal Information</h2><p>Tell us who you are.</p></div></div>
                <div class="form-grid">
                    <div><label>First Name <span class="req">*</span></label><input-firstName req></div>
                    <div><label>Last Name <span class="req">*</span></label><input-lastName req></div>
                    <div><label>Email <span class="req">*</span></label><input-email req></div>
                    <div><label>Confirm Email <span class="req">*</span></label><input-emailconfirm req></div>
                    <div><label>Phone <span class="req">*</span></label><input-phone req></div>
                    <div><label>City</label><input-city></div>
                    <div><label>State</label><input-state></div>
                    <div><label>Zip Code</label><input-zip></div>
                    <div class="full"><label>Address</label><input-address></div>
                </div>
            </div>
            <div class="form-section reveal">
                <div class="form-section-header"><div class="form-section-icon" style="background:#f0fdf4;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg></div><div class="form-section-title"><h2>Professional Background</h2><p>Your experience and skills.</p></div></div>
                <div class="form-grid">
                    <div><label>Key Skills <span class="req">*</span></label><input-keySkills req></div>
                    <div><label>Current Employer</label><input-employer></div>
                    <div class="full"><label>How did you hear about us?</label><input-source></div>
                    <div class="full"><label>Additional Info</label><input-extraNotes></div>
                </div>
            </div>
            <div class="form-section reveal">
                <div class="form-section-header"><div class="form-section-icon" style="background:#eff6ff;"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div><div class="form-section-title"><h2>Resume & Documents</h2><p>Upload your resume.</p></div></div>
                <div><label>Upload Resume <span class="req">*</span></label>
                    <div class="file-upload-area" onclick="document.getElementById('resume')?document.getElementById('resume').click():(document.getElementById('resumeFile')?document.getElementById('resumeFile').click():null)"><div class="upload-icon"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg></div><p>Drag and drop your file here</p><div class="or-text">or</div><span class="choose-file-btn">Choose File</span></div>
                    <input-resumeUpload><div class="file-types">PDF, DOC, DOCX, TXT, RTF &bull; Max 10MB</div>
                </div>
                <div style="margin-top:20px;"><label>Cover Letter</label><input-extraNotes></div>
            </div>
            <div class="form-actions reveal">
                <button type="button" class="btn-draft" onclick="alert('Draft saved!');"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg> Save Draft</button>
                <submit value="Save & Continue →" class="btn-submit">
            </div>
        </div>
        <div class="apply-sidebar">
            <div class="summary-card reveal">
                <h3>Application Summary</h3><p class="summary-subtitle">Review your progress</p>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Personal Information</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Professional Background</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Resume & Documents</h4><p>Not started</p></div></div>
                <div class="summary-item"><div class="summary-dot"></div><div class="summary-item-text"><h4>Review & Submit</h4><p>Not started</p></div></div>
                <div class="tip-box"><div class="tip-header"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg><span>Tip</span></div><p>Complete all sections to increase your chances.</p></div>
            </div>
        </div>
    </div>
</div>
<script>
(function(){function u(){var inp=document.querySelectorAll('#applyToJobForm input[type="text"],#applyToJobForm input[type="email"],#applyToJobForm input[type="tel"],#applyToJobForm textarea,#applyToJobForm select');var f=0,t=0;inp.forEach(function(i){if(i.type!=='hidden'&&i.offsetParent!==null){t++;if(i.value&&i.value.trim()!=='')f++;}});var p=t>0?Math.round((f/t)*100):0;var b=document.getElementById('formProgress'),l=document.getElementById('formProgressLabel');if(b)b.style.width=p+'%';if(l)l.textContent=p+'% complete';var d=document.querySelectorAll('.summary-dot'),s=document.querySelectorAll('.summary-item-text p');var s1=['firstName','lastName','email','phone'].filter(function(id){var e=document.getElementById(id);return e&&e.value.trim();}).length;if(s1>=4){d[0].style.borderColor='#16a34a';d[0].style.background='#16a34a';s[0].textContent='Completed';}else if(s1>0){d[0].style.borderColor='#2563eb';d[0].style.background='#2563eb';s[0].textContent='In progress';}var s2=['keySkills','source'].filter(function(id){var e=document.getElementById(id);return e&&e.value.trim();}).length;if(s2>=2){d[1].style.borderColor='#16a34a';d[1].style.background='#16a34a';s[1].textContent='Completed';}else if(s2>0){d[1].style.borderColor='#2563eb';d[1].style.background='#2563eb';s[1].textContent='In progress';}var fe=document.getElementById('resume')||document.getElementById('resumeFile');if(fe&&fe.value){d[2].style.borderColor='#16a34a';d[2].style.background='#16a34a';s[2].textContent='Completed';}var st=document.querySelectorAll('.step-number');if(s1>=4&&st[0]){st[0].className='step-number completed';st[0].innerHTML='<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';}if(s2>=2&&st[1]){st[1].className='step-number completed';st[1].innerHTML='<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';}}document.addEventListener('DOMContentLoaded',function(){document.body.classList.add('page-loaded');setInterval(u,500);var els=document.querySelectorAll('.reveal');if('IntersectionObserver' in window){var o=new IntersectionObserver(function(en){en.forEach(function(e){if(e.isIntersecting){e.target.classList.add('revealed');o.unobserve(e.target);}});},{threshold:0.1});els.forEach(function(el){o.observe(el);});}else{els.forEach(function(el){el.classList.add('revealed');});}var ph={'firstName':'First name','lastName':'Last name','email':'Email address','emailconfirm':'Confirm email','phone':'Phone','city':'City','zip':'Zip code','address':'Address','keySkills':'Skills (comma separated)','employer':'Current employer','extraNotes':'Additional info'};Object.keys(ph).forEach(function(id){var el=document.getElementById(id);if(el)el.placeholder=ph[id];});});})();
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
        <p style="font-size:15px;color:var(--gray-500);max-width:380px;margin:0 auto 24px;">Thank you for applying. We&rsquo;ll review your application and get back to you shortly.</p>
        <a href="index.php?m=careers&p=showAll" class="btn-primary" style="display:inline-flex;"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg> Back to Positions</a>
    </div>
</div>
<script>document.addEventListener('DOMContentLoaded',function(){document.body.classList.add('page-loaded');document.querySelectorAll('.reveal').forEach(function(el){el.classList.add('revealed');});});</script>
TPL;
$stmt->execute(['val' => $thanks, 'setting' => 'Content - Thanks for your Submission']);
echo "Thanks updated\n";


// =====================================================
// FOOTER
// =====================================================
$footer = <<<'TPL'
<div class="career-footer">
    <div class="footer-main">
        <div class="footer-brand">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                <div class="brand-icon">N</div>
                <div class="brand-text">Neutara</div>
            </div>
            <p>Connecting Talent.<br>Building Futures.</p>
        </div>
        <div class="footer-links">
            <a href="index.php?m=careers&p=showAll#positions">Jobs</a>
            <a href="index.php?m=careers&p=showAll#features">Companies</a>
            <a href="index.php?m=careers&p=showAll#process">Resources</a>
            <a href="index.php?m=careers&p=showAll#about">About Us</a>
            <a href="index.php?m=careers&p=showAll">Contact</a>
        </div>
        <div class="footer-social">
            <a href="#" title="LinkedIn"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>
            <a href="#" title="Twitter"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></a>
            <a href="#" title="Facebook"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>
            <a href="#" title="Instagram"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678a6.162 6.162 0 100 12.324 6.162 6.162 0 100-12.324zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405a1.441 1.441 0 11-2.88 0 1.441 1.441 0 012.88 0z"/></svg></a>
        </div>
    </div>
    <div class="footer-bottom">
        <span>&copy; 2024 Neutara. All rights reserved.</span>
        <div class="footer-legal">
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
    </div>
</div>
TPL;
$stmt->execute(['val' => $footer, 'setting' => 'Footer']);
echo "Footer updated\n";


echo "\n=== Career Portal V7 Premium Deployed! ===\n";
echo "Visit: http://localhost:8000/careers/index.php?m=careers&p=showAll\n";
