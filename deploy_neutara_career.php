<?php
/**
 * Deploy "Neutara Premium" Career Portal Template
 * Enterprise-grade career portal inspired by Dell, Microsoft, LinkedIn, Adobe
 * Run once: php deploy_neutara_career.php
 */

$templateName = 'Neutara Premium';

try {
    $pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage() . "\n");
}

// Remove old version
$pdo->prepare("DELETE FROM career_portal_template WHERE career_portal_name = ?")->execute([$templateName]);
echo "Cleared old template.\n";

// ════════════════════════════════════════════════════════════════
// CSS
// ════════════════════════════════════════════════════════════════
$css = <<<'EOCSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

:root {
    --nt-primary: #0057FF;
    --nt-primary-dark: #0046CC;
    --nt-primary-light: #3378FF;
    --nt-navy: #0B1F4D;
    --nt-navy-light: #1a3366;
    --nt-bg: #F5F9FF;
    --nt-white: #FFFFFF;
    --nt-gray-50: #f8fafc;
    --nt-gray-100: #f1f5f9;
    --nt-gray-200: #e2e8f0;
    --nt-gray-300: #cbd5e1;
    --nt-gray-400: #94a3b8;
    --nt-gray-500: #64748b;
    --nt-gray-600: #475569;
    --nt-gray-700: #334155;
    --nt-gray-800: #1e293b;
    --nt-gray-900: #0f172a;
    --nt-success: #10b981;
    --nt-warning: #f59e0b;
    --nt-danger: #ef4444;
    --nt-shadow-sm: 0 1px 3px rgba(0,0,0,0.06);
    --nt-shadow: 0 4px 16px rgba(0,0,0,0.08);
    --nt-shadow-lg: 0 12px 40px rgba(0,0,0,0.12);
    --nt-shadow-blue: 0 8px 32px rgba(0,87,255,0.2);
    --nt-radius: 12px;
    --nt-radius-lg: 20px;
    --nt-radius-xl: 28px;
    --nt-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: var(--nt-gray-800);
    background: var(--nt-bg);
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
    overflow-x: hidden;
}

a { text-decoration: none; color: inherit; transition: var(--nt-transition); }
img { max-width: 100%; height: auto; }

.nt-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

/* ══════════════ NAVBAR ══════════════ */
.nt-navbar {
    position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    border-bottom: 1px solid rgba(226,232,240,0.6);
    transition: var(--nt-transition);
}
.nt-navbar.scrolled {
    background: rgba(255,255,255,0.95);
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
}
.nt-navbar-inner {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    display: flex; align-items: center; height: 72px;
}

.nt-logo { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.nt-logo-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: linear-gradient(135deg, var(--nt-primary), #0046CC);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(0,87,255,0.25);
}
.nt-logo-text {
    font-weight: 800; font-size: 18px; color: var(--nt-navy);
    letter-spacing: -0.03em;
}
.nt-logo-text span { color: var(--nt-primary); }

.nt-nav { display: flex; align-items: center; gap: 4px; margin-left: 48px; }
.nt-nav a {
    font-weight: 500; font-size: 14px; color: var(--nt-gray-600);
    padding: 8px 16px; border-radius: 8px; transition: var(--nt-transition);
}
.nt-nav a:hover { color: var(--nt-primary); background: rgba(0,87,255,0.06); }
.nt-nav a.active { color: var(--nt-primary); font-weight: 600; }

.nt-nav-actions { display: flex; align-items: center; gap: 10px; margin-left: auto; }

.nt-btn {
    display: inline-flex; align-items: center; justify-content: center;
    gap: 8px; font-family: 'Inter', sans-serif;
    font-weight: 600; font-size: 14px;
    padding: 10px 22px; border-radius: 10px;
    border: none; cursor: pointer; transition: var(--nt-transition);
    white-space: nowrap; text-decoration: none;
}
.nt-btn-ghost {
    background: transparent; color: var(--nt-gray-600);
    border: 1.5px solid var(--nt-gray-200);
}
.nt-btn-ghost:hover { border-color: var(--nt-primary); color: var(--nt-primary); }
.nt-btn-primary {
    background: linear-gradient(135deg, var(--nt-primary), var(--nt-primary-dark));
    color: var(--nt-white);
    box-shadow: var(--nt-shadow-blue);
}
.nt-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 36px rgba(0,87,255,0.3);
}
.nt-btn-lg { padding: 14px 32px; font-size: 15px; border-radius: 12px; }
.nt-btn-white {
    background: var(--nt-white); color: var(--nt-navy);
    box-shadow: var(--nt-shadow);
}
.nt-btn-white:hover { transform: translateY(-2px); box-shadow: var(--nt-shadow-lg); }
.nt-btn-outline-white {
    background: transparent; color: var(--nt-white);
    border: 2px solid rgba(255,255,255,0.3);
}
.nt-btn-outline-white:hover { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.5); }

/* ══════════════ HERO ══════════════ */
.nt-hero {
    position: relative; padding: 160px 0 100px; overflow: hidden;
    background: linear-gradient(155deg, #0B1F4D 0%, #0a1a3e 30%, #091533 60%, #060f24 100%);
    min-height: 90vh; display: flex; align-items: center;
}
.nt-hero::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 70% 40%, rgba(0,87,255,0.15) 0%, transparent 60%),
        radial-gradient(ellipse 60% 50% at 20% 80%, rgba(0,87,255,0.08) 0%, transparent 50%),
        radial-gradient(circle at 90% 10%, rgba(99,102,241,0.1) 0%, transparent 30%);
}
.nt-hero::after {
    content: ''; position: absolute; bottom: -2px; left: 0; right: 0; height: 120px;
    background: linear-gradient(to top, var(--nt-bg), transparent);
    z-index: 2;
}

/* Grid dots pattern */
.nt-hero-grid {
    position: absolute; inset: 0; opacity: 0.03;
    background-image: radial-gradient(circle, #fff 1px, transparent 1px);
    background-size: 40px 40px;
}

.nt-hero-inner {
    position: relative; z-index: 5;
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;
}

.nt-hero-content { max-width: 560px; }
.nt-hero-label {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(0,87,255,0.12); border: 1px solid rgba(0,87,255,0.2);
    border-radius: 50px; padding: 6px 16px 6px 8px;
    font-size: 13px; font-weight: 600; color: #60a5fa;
    margin-bottom: 24px; animation: nt-fadeUp 0.6s ease-out;
}
.nt-hero-label-dot {
    width: 8px; height: 8px; border-radius: 50%; background: #60a5fa;
    animation: nt-pulse 2s infinite;
}

.nt-hero h1 {
    font-size: 52px; font-weight: 900; line-height: 1.1;
    color: var(--nt-white); letter-spacing: -0.03em;
    margin-bottom: 20px; animation: nt-fadeUp 0.6s ease-out 0.1s both;
}
.nt-hero h1 .nt-gradient-text {
    background: linear-gradient(135deg, #60a5fa, #818cf8, #a78bfa);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text;
}
.nt-hero-desc {
    font-size: 17px; line-height: 1.7; color: rgba(255,255,255,0.55);
    margin-bottom: 36px; max-width: 480px;
    animation: nt-fadeUp 0.6s ease-out 0.2s both;
}
.nt-hero-buttons {
    display: flex; gap: 14px; flex-wrap: wrap;
    animation: nt-fadeUp 0.6s ease-out 0.3s both;
}

/* Hero illustration area */
.nt-hero-visual {
    position: relative; height: 480px;
    animation: nt-fadeUp 0.8s ease-out 0.3s both;
}

/* Floating cards */
.nt-float-card {
    position: absolute; background: rgba(255,255,255,0.08);
    backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 16px; padding: 16px 20px;
    display: flex; align-items: center; gap: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    animation: nt-float 6s ease-in-out infinite;
}
.nt-float-card-icon {
    width: 42px; height: 42px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.nt-float-card-title { font-size: 13px; font-weight: 700; color: var(--nt-white); }
.nt-float-card-sub { font-size: 11px; color: rgba(255,255,255,0.5); margin-top: 2px; }

.nt-fc-1 { top: 30px; left: 20px; animation-delay: 0s; }
.nt-fc-2 { top: 60px; right: 0; animation-delay: 1.5s; }
.nt-fc-3 { bottom: 120px; left: 0; animation-delay: 3s; }
.nt-fc-4 { bottom: 60px; right: 30px; animation-delay: 4.5s; }

/* Hero laptop illustration */
.nt-hero-laptop {
    position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
    width: 380px; perspective: 1000px;
}
.nt-laptop-screen {
    width: 100%; height: 240px; background: linear-gradient(135deg, #1a2744, #0d1a33);
    border-radius: 12px 12px 0 0; border: 2px solid rgba(255,255,255,0.1);
    overflow: hidden; position: relative;
    box-shadow: 0 -8px 40px rgba(0,87,255,0.15);
}
.nt-laptop-dashboard {
    position: absolute; inset: 8px; border-radius: 6px; overflow: hidden;
    background: linear-gradient(180deg, #0f1b33, #0a1428);
}
.nt-laptop-dash-bar {
    height: 24px; background: rgba(255,255,255,0.05);
    display: flex; align-items: center; gap: 4px; padding: 0 8px;
    border-bottom: 1px solid rgba(255,255,255,0.05);
}
.nt-laptop-dash-dot { width: 6px; height: 6px; border-radius: 50%; }
.nt-laptop-dash-content { padding: 10px; display: flex; gap: 6px; }
.nt-laptop-dash-card {
    flex: 1; height: 50px; border-radius: 6px;
    background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);
}
.nt-laptop-dash-chart {
    margin: 6px 10px; height: 80px; border-radius: 6px;
    background: linear-gradient(180deg, rgba(0,87,255,0.1), rgba(0,87,255,0.02));
    border: 1px solid rgba(0,87,255,0.15);
    position: relative; overflow: hidden;
}
.nt-laptop-dash-chart::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 60%;
    background: linear-gradient(90deg,
        transparent 0%, rgba(0,87,255,0.3) 15%, rgba(0,87,255,0.1) 30%,
        rgba(0,87,255,0.4) 45%, rgba(0,87,255,0.15) 60%,
        rgba(0,87,255,0.5) 75%, rgba(0,87,255,0.2) 100%);
    clip-path: polygon(0 80%, 10% 50%, 20% 65%, 30% 30%, 40% 45%, 50% 20%, 60% 35%, 70% 10%, 80% 25%, 90% 5%, 100% 15%, 100% 100%, 0 100%);
}
.nt-laptop-base {
    width: 110%; height: 14px; margin-left: -5%;
    background: linear-gradient(180deg, #2a3a5c, #1a2744);
    border-radius: 0 0 8px 8px;
    border: 2px solid rgba(255,255,255,0.08); border-top: none;
}
.nt-laptop-hinge {
    width: 60px; height: 4px; margin: 0 auto;
    background: rgba(255,255,255,0.1); border-radius: 0 0 4px 4px;
}

/* ══════════════ SEARCH SECTION ══════════════ */
.nt-search-section {
    position: relative; z-index: 10;
    margin-top: -60px; padding-bottom: 80px;
}
.nt-search-card {
    background: var(--nt-white);
    border-radius: var(--nt-radius-lg);
    padding: 32px 36px;
    box-shadow: 0 8px 40px rgba(0,0,0,0.08), 0 1px 3px rgba(0,0,0,0.04);
    border: 1px solid var(--nt-gray-100);
}
.nt-search-title {
    font-size: 18px; font-weight: 800; color: var(--nt-navy);
    margin-bottom: 20px; letter-spacing: -0.02em;
}
.nt-search-form {
    display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 12px;
    align-items: end;
}
.nt-search-group label {
    display: block; font-size: 12px; font-weight: 600; color: var(--nt-gray-500);
    text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 6px;
}
.nt-search-input {
    width: 100%; height: 48px; padding: 0 16px;
    border: 1.5px solid var(--nt-gray-200); border-radius: 10px;
    font-family: 'Inter', sans-serif; font-size: 14px; color: var(--nt-gray-800);
    background: var(--nt-gray-50); outline: none;
    transition: var(--nt-transition);
}
.nt-search-input:focus { border-color: var(--nt-primary); background: var(--nt-white); box-shadow: 0 0 0 3px rgba(0,87,255,0.1); }
.nt-search-input::placeholder { color: var(--nt-gray-400); }

.nt-search-tags {
    display: flex; gap: 8px; margin-top: 18px; flex-wrap: wrap; align-items: center;
}
.nt-search-tags-label { font-size: 13px; font-weight: 600; color: var(--nt-gray-500); }
.nt-search-tag {
    font-size: 12px; font-weight: 500; color: var(--nt-gray-600);
    background: var(--nt-gray-100); border: 1px solid var(--nt-gray-200);
    padding: 5px 14px; border-radius: 50px; cursor: pointer;
    transition: var(--nt-transition);
}
.nt-search-tag:hover { background: rgba(0,87,255,0.08); color: var(--nt-primary); border-color: rgba(0,87,255,0.2); }

/* ══════════════ FEATURED JOBS ══════════════ */
.nt-section { padding: 80px 0; }
.nt-section-header { text-align: center; margin-bottom: 48px; }
.nt-section-label {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 12px; font-weight: 700; color: var(--nt-primary);
    text-transform: uppercase; letter-spacing: 0.1em;
    margin-bottom: 12px;
}
.nt-section-label-line {
    width: 24px; height: 2px; border-radius: 99px;
    background: linear-gradient(90deg, var(--nt-primary), transparent);
}
.nt-section-title {
    font-size: 36px; font-weight: 900; color: var(--nt-navy);
    letter-spacing: -0.03em; margin-bottom: 14px; line-height: 1.2;
}
.nt-section-desc {
    font-size: 16px; color: var(--nt-gray-500); max-width: 560px;
    margin: 0 auto; line-height: 1.7;
}

.nt-jobs-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
}

.nt-job-card {
    background: var(--nt-white); border: 1px solid var(--nt-gray-200);
    border-radius: var(--nt-radius-lg); padding: 28px;
    transition: var(--nt-transition); position: relative; overflow: hidden;
}
.nt-job-card::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--nt-primary), #818cf8);
    transform: scaleX(0); transform-origin: left; transition: transform 0.4s ease;
}
.nt-job-card:hover {
    border-color: rgba(0,87,255,0.2);
    box-shadow: 0 12px 40px rgba(0,87,255,0.08);
    transform: translateY(-4px);
}
.nt-job-card:hover::before { transform: scaleX(1); }

.nt-job-card-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
.nt-job-card-badge {
    font-size: 11px; font-weight: 700; padding: 4px 12px;
    border-radius: 50px; letter-spacing: 0.02em;
}
.nt-badge-new { background: rgba(16,185,129,0.1); color: #059669; }
.nt-badge-hot { background: rgba(239,68,68,0.1); color: #dc2626; }
.nt-badge-remote { background: rgba(99,102,241,0.1); color: #6366f1; }

.nt-job-card h3 {
    font-size: 17px; font-weight: 700; color: var(--nt-navy);
    margin-bottom: 8px; letter-spacing: -0.01em;
}
.nt-job-card-company { font-size: 13px; color: var(--nt-gray-500); margin-bottom: 16px; }
.nt-job-card-meta {
    display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 18px;
}
.nt-job-meta-item {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 12px; color: var(--nt-gray-500); font-weight: 500;
}
.nt-job-meta-icon { color: var(--nt-gray-400); display: flex; }

.nt-job-card-skills { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 20px; }
.nt-job-skill {
    font-size: 11px; font-weight: 500; color: var(--nt-gray-600);
    background: var(--nt-gray-100); padding: 4px 10px; border-radius: 6px;
}

.nt-job-card-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 18px; border-top: 1px solid var(--nt-gray-100);
}
.nt-job-salary { font-size: 16px; font-weight: 800; color: var(--nt-navy); }
.nt-job-salary span { font-size: 12px; font-weight: 500; color: var(--nt-gray-400); }

.nt-btn-sm { padding: 8px 18px; font-size: 13px; border-radius: 8px; }

/* ══════════════ WHY JOIN ══════════════ */
.nt-why-section { background: var(--nt-white); }
.nt-why-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
}
.nt-why-card {
    text-align: center; padding: 36px 24px;
    border-radius: var(--nt-radius-lg); border: 1px solid var(--nt-gray-100);
    transition: var(--nt-transition); position: relative; overflow: hidden;
    background: var(--nt-white);
}
.nt-why-card::after {
    content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, var(--nt-primary), #818cf8);
    transform: scaleX(0); transition: transform 0.4s ease;
}
.nt-why-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--nt-shadow-lg);
    border-color: transparent;
}
.nt-why-card:hover::after { transform: scaleX(1); }

.nt-why-icon {
    width: 64px; height: 64px; border-radius: 18px;
    margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;
}
.nt-why-card h3 {
    font-size: 16px; font-weight: 700; color: var(--nt-navy);
    margin-bottom: 10px; letter-spacing: -0.01em;
}
.nt-why-card p {
    font-size: 13px; color: var(--nt-gray-500); line-height: 1.7;
}

/* ══════════════ TIMELINE ══════════════ */
.nt-timeline-section { background: var(--nt-bg); }
.nt-timeline {
    display: flex; justify-content: center; gap: 0; padding: 20px 0;
    position: relative;
}
.nt-timeline-step {
    flex: 1; max-width: 200px; text-align: center; position: relative;
    padding: 0 12px;
}
.nt-timeline-step::before {
    content: ''; position: absolute; top: 28px; left: 50%; right: -50%;
    height: 2px; background: var(--nt-gray-200); z-index: 1;
}
.nt-timeline-step:last-child::before { display: none; }

.nt-timeline-dot {
    width: 56px; height: 56px; border-radius: 50%;
    background: var(--nt-white); border: 3px solid var(--nt-gray-200);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 16px; position: relative; z-index: 2;
    transition: var(--nt-transition);
    font-size: 18px; font-weight: 800; color: var(--nt-gray-400);
}
.nt-timeline-step.active .nt-timeline-dot {
    border-color: var(--nt-primary);
    background: linear-gradient(135deg, var(--nt-primary), var(--nt-primary-dark));
    color: var(--nt-white);
    box-shadow: var(--nt-shadow-blue);
}
.nt-timeline-step.completed .nt-timeline-dot {
    border-color: var(--nt-success); background: var(--nt-success); color: var(--nt-white);
}
.nt-timeline-step.completed::before { background: var(--nt-success); }

.nt-timeline-label {
    font-size: 14px; font-weight: 700; color: var(--nt-navy); margin-bottom: 4px;
}
.nt-timeline-desc {
    font-size: 12px; color: var(--nt-gray-500); line-height: 1.5;
}

/* ══════════════ TESTIMONIALS ══════════════ */
.nt-testimonials-section { background: var(--nt-white); overflow: hidden; }
.nt-testimonials-track {
    display: flex; gap: 24px; padding: 8px 0;
    animation: nt-scroll 40s linear infinite;
    width: max-content;
}
.nt-testimonials-track:hover { animation-play-state: paused; }

.nt-testimonial-card {
    width: 380px; flex-shrink: 0; padding: 28px;
    background: var(--nt-gray-50); border: 1px solid var(--nt-gray-100);
    border-radius: var(--nt-radius-lg); transition: var(--nt-transition);
}
.nt-testimonial-card:hover {
    background: var(--nt-white);
    box-shadow: var(--nt-shadow);
    transform: translateY(-4px);
}
.nt-testimonial-stars { display: flex; gap: 2px; margin-bottom: 14px; }
.nt-star { color: #f59e0b; font-size: 14px; }
.nt-testimonial-text {
    font-size: 14px; color: var(--nt-gray-600); line-height: 1.8;
    margin-bottom: 20px; font-style: italic;
}
.nt-testimonial-author {
    display: flex; align-items: center; gap: 12px;
}
.nt-testimonial-avatar {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800; color: var(--nt-white);
}
.nt-testimonial-name { font-size: 14px; font-weight: 700; color: var(--nt-navy); }
.nt-testimonial-role { font-size: 12px; color: var(--nt-gray-500); }

@keyframes nt-scroll {
    0% { transform: translateX(0); }
    100% { transform: translateX(-50%); }
}

/* ══════════════ STATS ══════════════ */
.nt-stats-section {
    background: linear-gradient(155deg, var(--nt-navy) 0%, #091533 100%);
    position: relative; overflow: hidden;
}
.nt-stats-section::before {
    content: ''; position: absolute; inset: 0;
    background:
        radial-gradient(circle at 20% 50%, rgba(0,87,255,0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 50%, rgba(99,102,241,0.08) 0%, transparent 50%);
}
.nt-stats-grid {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px;
    position: relative; z-index: 2;
}
.nt-stat-card { text-align: center; padding: 20px; }
.nt-stat-number {
    font-size: 48px; font-weight: 900; letter-spacing: -0.03em;
    background: linear-gradient(135deg, #60a5fa, #818cf8);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    background-clip: text; line-height: 1.1;
}
.nt-stat-label { font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.5); margin-top: 6px; }

/* ══════════════ NEWSLETTER ══════════════ */
.nt-newsletter {
    background: var(--nt-white);
    border: 1px solid var(--nt-gray-100);
    border-radius: var(--nt-radius-xl);
    padding: 48px 56px;
    display: flex; align-items: center; justify-content: space-between; gap: 40px;
    box-shadow: var(--nt-shadow);
    margin-bottom: 80px;
}
.nt-newsletter h3 {
    font-size: 24px; font-weight: 800; color: var(--nt-navy);
    margin-bottom: 6px; letter-spacing: -0.02em;
}
.nt-newsletter p { font-size: 14px; color: var(--nt-gray-500); }
.nt-newsletter-form { display: flex; gap: 10px; flex-shrink: 0; }
.nt-newsletter-input {
    width: 280px; height: 48px; padding: 0 18px;
    border: 1.5px solid var(--nt-gray-200); border-radius: 10px;
    font-family: 'Inter', sans-serif; font-size: 14px;
    outline: none; background: var(--nt-gray-50);
    transition: var(--nt-transition);
}
.nt-newsletter-input:focus { border-color: var(--nt-primary); background: var(--nt-white); }

/* ══════════════ FOOTER ══════════════ */
.nt-footer {
    background: linear-gradient(180deg, #0B1F4D, #070f29);
    padding: 64px 0 0; position: relative;
}
.nt-footer::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 1px;
    background: linear-gradient(90deg, transparent, rgba(0,87,255,0.3), transparent);
}
.nt-footer-grid {
    display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 48px;
    max-width: 1200px; margin: 0 auto; padding: 0 24px 48px;
}
.nt-footer-brand p {
    font-size: 13px; color: rgba(255,255,255,0.4); line-height: 1.7;
    margin: 14px 0 24px; max-width: 300px;
}
.nt-footer-social { display: flex; gap: 10px; }
.nt-footer-social a {
    width: 38px; height: 38px; border-radius: 10px;
    background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.08);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.5); transition: var(--nt-transition);
}
.nt-footer-social a:hover {
    background: rgba(0,87,255,0.2); color: var(--nt-white);
    border-color: rgba(0,87,255,0.3); transform: translateY(-2px);
}

.nt-footer-col h4 {
    font-size: 14px; font-weight: 700; color: var(--nt-white);
    margin-bottom: 18px; letter-spacing: -0.01em;
}
.nt-footer-col a {
    display: block; font-size: 13px; color: rgba(255,255,255,0.4);
    padding: 5px 0; transition: var(--nt-transition);
}
.nt-footer-col a:hover { color: var(--nt-white); padding-left: 4px; }

.nt-footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.06);
    padding: 20px 0; text-align: center;
}
.nt-footer-bottom p {
    max-width: 1200px; margin: 0 auto; padding: 0 24px;
    font-size: 12px; color: rgba(255,255,255,0.3);
}

/* ══════════════ SEARCH RESULTS PAGE ══════════════ */
.nt-results-page { padding: 110px 0 60px; }
.nt-results-header {
    background: linear-gradient(135deg, var(--nt-navy), #1a3366);
    border-radius: var(--nt-radius-xl); padding: 36px 40px;
    margin-bottom: 32px; position: relative; overflow: hidden;
}
.nt-results-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(circle at 90% 50%, rgba(0,87,255,0.15) 0%, transparent 60%);
}
.nt-results-header h2 {
    font-size: 24px; font-weight: 800; color: var(--nt-white);
    margin-bottom: 4px; position: relative; z-index: 2;
}
.nt-results-count {
    font-size: 14px; color: rgba(255,255,255,0.5);
    position: relative; z-index: 2;
}

.nt-results-list { display: flex; flex-direction: column; gap: 12px; }

/* Style the search results table */
table.sortable {
    width: 100%; border-collapse: separate; border-spacing: 0;
    background: var(--nt-white); border-radius: var(--nt-radius); overflow: hidden;
    box-shadow: var(--nt-shadow-sm); border: 1px solid var(--nt-gray-200);
}
table.sortable th {
    background: var(--nt-gray-50); font-size: 12px; font-weight: 700;
    color: var(--nt-gray-500); text-transform: uppercase; letter-spacing: 0.05em;
    padding: 14px 20px; text-align: left;
    border-bottom: 2px solid var(--nt-gray-200);
}
table.sortable td {
    padding: 16px 20px; font-size: 14px; color: var(--nt-gray-700);
    border-bottom: 1px solid var(--nt-gray-100);
    transition: var(--nt-transition);
}
table.sortable tr:last-child td { border-bottom: none; }
table.sortable tr:hover td { background: rgba(0,87,255,0.02); }
table.sortable td a {
    color: var(--nt-primary); font-weight: 600;
    transition: var(--nt-transition);
}
table.sortable td a:hover { color: var(--nt-primary-dark); }

/* ══════════════ JOB DETAILS PAGE ══════════════ */
.nt-job-page { padding: 110px 0 60px; }
.nt-job-detail-card {
    background: var(--nt-white); border-radius: var(--nt-radius-xl);
    border: 1px solid var(--nt-gray-200); overflow: hidden;
    box-shadow: var(--nt-shadow-sm);
}
.nt-job-detail-hero {
    background: linear-gradient(135deg, var(--nt-navy), #1a3366);
    padding: 40px 44px; position: relative; overflow: hidden;
}
.nt-job-detail-hero::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(circle at 90% 30%, rgba(0,87,255,0.12) 0%, transparent 60%);
}
.nt-job-detail-hero h1 {
    font-size: 30px; font-weight: 900; color: var(--nt-white);
    margin-bottom: 12px; position: relative; z-index: 2;
    letter-spacing: -0.02em;
}
.nt-job-detail-meta {
    display: flex; flex-wrap: wrap; gap: 20px;
    position: relative; z-index: 2;
}
.nt-job-detail-meta-item {
    display: flex; align-items: center; gap: 6px;
    font-size: 14px; color: rgba(255,255,255,0.6); font-weight: 500;
}
.nt-job-detail-meta-item svg { color: rgba(255,255,255,0.4); }

.nt-job-detail-body { padding: 40px 44px; }
.nt-job-detail-body h2 {
    font-size: 18px; font-weight: 800; color: var(--nt-navy);
    margin: 32px 0 14px; letter-spacing: -0.01em;
}
.nt-job-detail-body h2:first-child { margin-top: 0; }
.nt-job-detail-body p,
.nt-job-detail-body li {
    font-size: 15px; color: var(--nt-gray-600); line-height: 1.8;
}
.nt-job-detail-body ul { padding-left: 20px; margin: 8px 0; }
.nt-job-detail-body li { margin-bottom: 6px; }

.nt-job-detail-apply {
    display: flex; align-items: center; gap: 16px;
    padding: 28px 44px; border-top: 1px solid var(--nt-gray-100);
    background: var(--nt-gray-50);
}
.nt-job-detail-apply p {
    font-size: 13px; color: var(--nt-gray-500); flex: 1;
}

/* ══════════════ APPLICATION FORM ══════════════ */
.nt-apply-page { padding: 110px 0 60px; }
.nt-apply-card {
    max-width: 720px; margin: 0 auto;
    background: var(--nt-white); border-radius: var(--nt-radius-xl);
    border: 1px solid var(--nt-gray-200);
    box-shadow: var(--nt-shadow-sm); overflow: hidden;
}
.nt-apply-header {
    background: linear-gradient(135deg, var(--nt-navy), #1a3366);
    padding: 32px 40px; position: relative; overflow: hidden;
}
.nt-apply-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(circle at 80% 50%, rgba(0,87,255,0.12) 0%, transparent 60%);
}
.nt-apply-header h2 {
    font-size: 22px; font-weight: 800; color: var(--nt-white);
    margin-bottom: 4px; position: relative; z-index: 2;
}
.nt-apply-header p {
    font-size: 14px; color: rgba(255,255,255,0.5);
    position: relative; z-index: 2;
}

.nt-apply-body { padding: 36px 40px; }
.nt-form-section-title {
    font-size: 14px; font-weight: 700; color: var(--nt-navy);
    text-transform: uppercase; letter-spacing: 0.05em;
    margin-bottom: 18px; padding-bottom: 10px;
    border-bottom: 2px solid var(--nt-gray-100);
    display: flex; align-items: center; gap: 8px;
}
.nt-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.nt-form-row.full { grid-template-columns: 1fr; }

.nt-form-group { margin-bottom: 0; }
.nt-form-group label {
    display: block; font-size: 13px; font-weight: 600; color: var(--nt-gray-700);
    margin-bottom: 6px;
}
.nt-form-group label .nt-required { color: var(--nt-danger); }

/* Style CATS form elements */
.inputBoxName, .inputBoxArea, .inputBoxNormal,
input[type="text"], input[type="email"], input[type="tel"],
textarea, select {
    width: 100% !important; height: 46px; padding: 0 14px;
    border: 1.5px solid var(--nt-gray-200) !important; border-radius: 10px !important;
    font-family: 'Inter', sans-serif !important; font-size: 14px !important;
    color: var(--nt-gray-800) !important; background: var(--nt-gray-50) !important;
    outline: none !important; transition: var(--nt-transition);
    box-sizing: border-box;
}
.inputBoxName:focus, .inputBoxArea:focus, .inputBoxNormal:focus,
input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus,
textarea:focus, select:focus {
    border-color: var(--nt-primary) !important;
    background: var(--nt-white) !important;
    box-shadow: 0 0 0 3px rgba(0,87,255,0.08) !important;
}
textarea, .inputBoxArea {
    height: 120px !important; padding: 12px 14px !important;
    resize: vertical;
}
input[type="file"] {
    padding: 10px 14px !important; height: auto;
    border: 2px dashed var(--nt-gray-200) !important;
    background: var(--nt-gray-50) !important;
    border-radius: 10px !important; cursor: pointer;
    font-family: 'Inter', sans-serif !important; font-size: 13px !important;
}

.nt-form-submit {
    margin-top: 28px; padding-top: 24px; border-top: 1px solid var(--nt-gray-100);
    text-align: right;
}

/* Style the submit button from CATS */
input[type="submit"], input[type="button"], button[type="submit"] {
    display: inline-flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, var(--nt-primary), var(--nt-primary-dark)) !important;
    color: var(--nt-white) !important; font-family: 'Inter', sans-serif !important;
    font-weight: 700 !important; font-size: 15px !important;
    padding: 14px 36px !important; border-radius: 12px !important;
    border: none !important; cursor: pointer !important;
    box-shadow: var(--nt-shadow-blue) !important;
    transition: var(--nt-transition);
}
input[type="submit"]:hover, input[type="button"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 36px rgba(0,87,255,0.3) !important;
}

/* ══════════════ THANK YOU PAGE ══════════════ */
.nt-thanks-page {
    padding: 140px 0 80px;
    display: flex; align-items: center; justify-content: center; min-height: 70vh;
}
.nt-thanks-card {
    max-width: 520px; text-align: center;
    background: var(--nt-white); border-radius: var(--nt-radius-xl);
    padding: 56px 48px; border: 1px solid var(--nt-gray-200);
    box-shadow: var(--nt-shadow);
}
.nt-thanks-icon {
    width: 80px; height: 80px; margin: 0 auto 24px;
    background: linear-gradient(135deg, rgba(16,185,129,0.1), rgba(16,185,129,0.05));
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    animation: nt-scaleIn 0.5s ease-out;
}
.nt-thanks-card h2 {
    font-size: 26px; font-weight: 800; color: var(--nt-navy);
    margin-bottom: 12px; letter-spacing: -0.02em;
}
.nt-thanks-card p {
    font-size: 15px; color: var(--nt-gray-500); line-height: 1.7;
    margin-bottom: 28px;
}

/* ══════════════ REGISTRATION / PROFILE ══════════════ */
.nt-reg-page { padding: 110px 0 60px; }

/* ══════════════ QUESTIONNAIRE ══════════════ */
.nt-questionnaire-page { padding: 110px 0 60px; }
.nt-questionnaire-card {
    max-width: 720px; margin: 0 auto;
    background: var(--nt-white); border-radius: var(--nt-radius-xl);
    border: 1px solid var(--nt-gray-200);
    box-shadow: var(--nt-shadow-sm); overflow: hidden;
    padding: 40px;
}
.nt-questionnaire-card h2 {
    font-size: 22px; font-weight: 800; color: var(--nt-navy);
    margin-bottom: 24px;
}

/* ══════════════ ANIMATIONS ══════════════ */
@keyframes nt-fadeUp {
    from { opacity: 0; transform: translateY(24px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes nt-float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-12px); }
}
@keyframes nt-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(1.2); }
}
@keyframes nt-scaleIn {
    from { opacity: 0; transform: scale(0.8); }
    to { opacity: 1; transform: scale(1); }
}
@keyframes nt-countUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Scroll reveal */
.nt-reveal {
    opacity: 0; transform: translateY(30px);
    transition: opacity 0.7s ease, transform 0.7s ease;
}
.nt-reveal.visible {
    opacity: 1; transform: translateY(0);
}

/* ══════════════ RESPONSIVE ══════════════ */
@media (max-width: 1024px) {
    .nt-hero-inner { grid-template-columns: 1fr; text-align: center; }
    .nt-hero-content { max-width: 100%; margin: 0 auto; }
    .nt-hero-desc { margin: 0 auto 36px; }
    .nt-hero-buttons { justify-content: center; }
    .nt-hero-visual { display: none; }
    .nt-hero h1 { font-size: 40px; }
    .nt-search-form { grid-template-columns: 1fr 1fr; }
    .nt-why-grid { grid-template-columns: 1fr 1fr; }
    .nt-stats-grid { grid-template-columns: 1fr 1fr; }
    .nt-footer-grid { grid-template-columns: 1fr 1fr; }
    .nt-newsletter { flex-direction: column; text-align: center; padding: 36px; }
    .nt-newsletter-form { width: 100%; }
    .nt-newsletter-input { flex: 1; }
}
@media (max-width: 768px) {
    .nt-hero { padding: 130px 0 80px; min-height: auto; }
    .nt-hero h1 { font-size: 32px; }
    .nt-navbar-inner { height: 60px; }
    .nt-nav { display: none; }
    .nt-search-form { grid-template-columns: 1fr; }
    .nt-jobs-grid { grid-template-columns: 1fr; }
    .nt-why-grid { grid-template-columns: 1fr; }
    .nt-timeline { flex-direction: column; align-items: center; gap: 24px; }
    .nt-timeline-step::before { display: none; }
    .nt-stats-grid { grid-template-columns: 1fr 1fr; gap: 16px; }
    .nt-stat-number { font-size: 36px; }
    .nt-footer-grid { grid-template-columns: 1fr; gap: 32px; }
    .nt-form-row { grid-template-columns: 1fr; }
    .nt-job-detail-hero, .nt-job-detail-body, .nt-job-detail-apply { padding: 24px; }
    .nt-apply-header, .nt-apply-body { padding: 24px; }
}
@media (max-width: 480px) {
    .nt-hero h1 { font-size: 26px; }
    .nt-section-title { font-size: 26px; }
    .nt-hero-buttons { flex-direction: column; }
    .nt-btn-lg { width: 100%; justify-content: center; }
}
EOCSS;

// ════════════════════════════════════════════════════════════════
// HEADER (Navbar)
// ════════════════════════════════════════════════════════════════
$header = <<<'EOHTML'
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<nav class="nt-navbar" id="ntNavbar">
  <div class="nt-navbar-inner">
    <a href="<a-LinkMain>/" class="nt-logo">
      <div class="nt-logo-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
          <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"/>
          <line x1="12" y1="22" x2="12" y2="15.5"/>
          <polyline points="22 8.5 12 15.5 2 8.5"/>
        </svg>
      </div>
      <span class="nt-logo-text">Neutara<span>Tech</span></span>
    </a>

    <div class="nt-nav">
      <a href="<a-LinkMain>/" class="active">Home</a>
      <a href="<a-ListAll>/">Careers</a>
      <a href="<a-ListAll>/">Jobs</a>
    </div>

    <div class="nt-nav-actions">
      <a href="<a-ListAll>/" class="nt-btn nt-btn-ghost nt-btn-sm">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
        Search Jobs
      </a>
      <a href="<a-ListAll>/" class="nt-btn nt-btn-primary nt-btn-sm">Apply Now</a>
    </div>
  </div>
</nav>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - MAIN (Landing page)
// ════════════════════════════════════════════════════════════════
$contentMain = <<<'EOHTML'
<!-- ══════════ HERO ══════════ -->
<section class="nt-hero">
  <div class="nt-hero-grid"></div>
  <div class="nt-hero-inner">
    <div class="nt-hero-content">
      <div class="nt-hero-label">
        <span class="nt-hero-label-dot"></span>
        We're Hiring — <numberOfOpenPositions> Open Positions
      </div>
      <h1>Build Your Future<br>With <span class="nt-gradient-text">Neutara Technologies</span></h1>
      <p class="nt-hero-desc">
        Join a team of innovators shaping the future of enterprise technology.
        Discover roles that challenge, inspire, and accelerate your career growth.
      </p>
      <div class="nt-hero-buttons">
        <a href="<a-ListAll>/" class="nt-btn nt-btn-primary nt-btn-lg">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          Explore Careers
        </a>
        <a href="#nt-about" class="nt-btn nt-btn-outline-white nt-btn-lg">
          Learn More
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>

    <div class="nt-hero-visual">
      <!-- Laptop Illustration -->
      <div class="nt-hero-laptop">
        <div class="nt-laptop-screen">
          <div class="nt-laptop-dashboard">
            <div class="nt-laptop-dash-bar">
              <span class="nt-laptop-dash-dot" style="background:#ef4444;"></span>
              <span class="nt-laptop-dash-dot" style="background:#f59e0b;"></span>
              <span class="nt-laptop-dash-dot" style="background:#22c55e;"></span>
            </div>
            <div class="nt-laptop-dash-content">
              <div class="nt-laptop-dash-card"></div>
              <div class="nt-laptop-dash-card"></div>
              <div class="nt-laptop-dash-card"></div>
            </div>
            <div class="nt-laptop-dash-chart"></div>
          </div>
        </div>
        <div class="nt-laptop-base"></div>
        <div class="nt-laptop-hinge"></div>
      </div>

      <!-- Floating Cards -->
      <div class="nt-float-card nt-fc-1">
        <div class="nt-float-card-icon" style="background:rgba(16,185,129,0.15);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg>
        </div>
        <div>
          <div class="nt-float-card-title">150+ Jobs</div>
          <div class="nt-float-card-sub">Open positions</div>
        </div>
      </div>

      <div class="nt-float-card nt-fc-2">
        <div class="nt-float-card-icon" style="background:rgba(99,102,241,0.15);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#818cf8" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div>
          <div class="nt-float-card-title">Resume Review</div>
          <div class="nt-float-card-sub">AI-powered screening</div>
        </div>
      </div>

      <div class="nt-float-card nt-fc-3">
        <div class="nt-float-card-icon" style="background:rgba(245,158,11,0.15);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div>
          <div class="nt-float-card-title">Interviews</div>
          <div class="nt-float-card-sub">Seamless scheduling</div>
        </div>
      </div>

      <div class="nt-float-card nt-fc-4">
        <div class="nt-float-card-icon" style="background:rgba(0,87,255,0.15);">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#60a5fa" stroke-width="2" stroke-linecap="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        </div>
        <div>
          <div class="nt-float-card-title">Analytics</div>
          <div class="nt-float-card-sub">Real-time insights</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ SEARCH ══════════ -->
<section class="nt-search-section">
  <div class="nt-container">
    <div class="nt-search-card nt-reveal">
      <div class="nt-search-title">Find Your Perfect Role</div>
      <form class="nt-search-form" action="<a-ListAll>/" method="get">
        <div class="nt-search-group">
          <label>Job Title</label>
          <input type="text" class="nt-search-input" placeholder="e.g. Software Engineer" name="keyword">
        </div>
        <div class="nt-search-group">
          <label>Location</label>
          <input type="text" class="nt-search-input" placeholder="e.g. San Francisco" name="location">
        </div>
        <div class="nt-search-group">
          <label>Experience</label>
          <select class="nt-search-input" name="experience">
            <option value="">Any Level</option>
            <option>Entry Level</option>
            <option>Mid Level</option>
            <option>Senior</option>
            <option>Lead / Manager</option>
          </select>
        </div>
        <div class="nt-search-group">
          <label>Department</label>
          <select class="nt-search-input" name="department">
            <option value="">All Departments</option>
            <option>Engineering</option>
            <option>Design</option>
            <option>Marketing</option>
            <option>Sales</option>
            <option>Operations</option>
          </select>
        </div>
        <button type="submit" class="nt-btn nt-btn-primary" style="height:48px;margin-top:22px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
          Search
        </button>
      </form>
      <div class="nt-search-tags">
        <span class="nt-search-tags-label">Popular:</span>
        <span class="nt-search-tag">Software Engineer</span>
        <span class="nt-search-tag">Product Manager</span>
        <span class="nt-search-tag">Data Scientist</span>
        <span class="nt-search-tag">UX Designer</span>
        <span class="nt-search-tag">DevOps</span>
        <span class="nt-search-tag">Remote</span>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ FEATURED JOBS ══════════ -->
<section class="nt-section">
  <div class="nt-container">
    <div class="nt-section-header nt-reveal">
      <div class="nt-section-label">
        <span class="nt-section-label-line"></span>
        FEATURED POSITIONS
        <span class="nt-section-label-line" style="transform:rotate(180deg);"></span>
      </div>
      <h2 class="nt-section-title">Opportunities Waiting For You</h2>
      <p class="nt-section-desc">Explore our latest openings across engineering, design, product, and more. Find the role that matches your ambition.</p>
    </div>

    <div class="nt-jobs-grid nt-reveal">
      <div class="nt-job-card">
        <div class="nt-job-card-top">
          <span class="nt-job-card-badge nt-badge-new">New</span>
        </div>
        <h3>Senior Software Engineer</h3>
        <div class="nt-job-card-company">Neutara Technologies &middot; Engineering</div>
        <div class="nt-job-card-meta">
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span> San Francisco, CA</span>
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Full-time</span>
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg></span> 5+ years</span>
        </div>
        <div class="nt-job-card-skills">
          <span class="nt-job-skill">React</span>
          <span class="nt-job-skill">Node.js</span>
          <span class="nt-job-skill">AWS</span>
          <span class="nt-job-skill">TypeScript</span>
        </div>
        <div class="nt-job-card-footer">
          <div class="nt-job-salary">$180K <span>/ year</span></div>
          <a href="<a-ListAll>/" class="nt-btn nt-btn-primary nt-btn-sm">Apply Now</a>
        </div>
      </div>

      <div class="nt-job-card">
        <div class="nt-job-card-top">
          <span class="nt-job-card-badge nt-badge-hot">Hot</span>
        </div>
        <h3>Product Manager</h3>
        <div class="nt-job-card-company">Neutara Technologies &middot; Product</div>
        <div class="nt-job-card-meta">
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span> New York, NY</span>
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Full-time</span>
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg></span> 4+ years</span>
        </div>
        <div class="nt-job-card-skills">
          <span class="nt-job-skill">Strategy</span>
          <span class="nt-job-skill">Analytics</span>
          <span class="nt-job-skill">Agile</span>
          <span class="nt-job-skill">SQL</span>
        </div>
        <div class="nt-job-card-footer">
          <div class="nt-job-salary">$160K <span>/ year</span></div>
          <a href="<a-ListAll>/" class="nt-btn nt-btn-primary nt-btn-sm">Apply Now</a>
        </div>
      </div>

      <div class="nt-job-card">
        <div class="nt-job-card-top">
          <span class="nt-job-card-badge nt-badge-remote">Remote</span>
        </div>
        <h3>UX/UI Designer</h3>
        <div class="nt-job-card-company">Neutara Technologies &middot; Design</div>
        <div class="nt-job-card-meta">
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span> Remote</span>
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></span> Full-time</span>
          <span class="nt-job-meta-item"><span class="nt-job-meta-icon"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg></span> 3+ years</span>
        </div>
        <div class="nt-job-card-skills">
          <span class="nt-job-skill">Figma</span>
          <span class="nt-job-skill">Design Systems</span>
          <span class="nt-job-skill">Prototyping</span>
        </div>
        <div class="nt-job-card-footer">
          <div class="nt-job-salary">$140K <span>/ year</span></div>
          <a href="<a-ListAll>/" class="nt-btn nt-btn-primary nt-btn-sm">Apply Now</a>
        </div>
      </div>
    </div>

    <div style="text-align:center;margin-top:36px;" class="nt-reveal">
      <a href="<a-ListAll>/" class="nt-btn nt-btn-ghost" style="color:var(--nt-primary);border-color:rgba(0,87,255,0.2);">
        View All Positions
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M5 12h14"/><polyline points="12 5 19 12 12 19"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- ══════════ WHY JOIN ══════════ -->
<section class="nt-section nt-why-section" id="nt-about">
  <div class="nt-container">
    <div class="nt-section-header nt-reveal">
      <div class="nt-section-label">
        <span class="nt-section-label-line"></span>
        WHY NEUTARA
        <span class="nt-section-label-line" style="transform:rotate(180deg);"></span>
      </div>
      <h2 class="nt-section-title">Why Build Your Career Here</h2>
      <p class="nt-section-desc">We invest in our people with world-class benefits, meaningful work, and a culture that celebrates innovation.</p>
    </div>

    <div class="nt-why-grid nt-reveal">
      <div class="nt-why-card">
        <div class="nt-why-icon" style="background:linear-gradient(135deg,rgba(0,87,255,0.1),rgba(0,87,255,0.05));">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#0057FF" stroke-width="1.5" stroke-linecap="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
          </svg>
        </div>
        <h3>Career Growth</h3>
        <p>Structured mentorship programs, learning budgets, and clear promotion paths to accelerate your trajectory.</p>
      </div>

      <div class="nt-why-card">
        <div class="nt-why-icon" style="background:linear-gradient(135deg,rgba(99,102,241,0.1),rgba(99,102,241,0.05));">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#6366f1" stroke-width="1.5" stroke-linecap="round">
            <circle cx="12" cy="12" r="10"/>
            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
          </svg>
        </div>
        <h3>Innovation First</h3>
        <p>Work on cutting-edge technology solving real-world problems. Hackathons, R&D time, and patent programs.</p>
      </div>

      <div class="nt-why-card">
        <div class="nt-why-icon" style="background:linear-gradient(135deg,rgba(16,185,129,0.1),rgba(16,185,129,0.05));">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="1.5" stroke-linecap="round">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
          </svg>
        </div>
        <h3>Work-Life Balance</h3>
        <p>Flexible hours, remote work options, unlimited PTO, and comprehensive wellness programs for your well-being.</p>
      </div>

      <div class="nt-why-card">
        <div class="nt-why-icon" style="background:linear-gradient(135deg,rgba(245,158,11,0.1),rgba(245,158,11,0.05));">
          <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.5" stroke-linecap="round">
            <circle cx="12" cy="12" r="10"/>
            <line x1="2" y1="12" x2="22" y2="12"/>
            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
          </svg>
        </div>
        <h3>Global Impact</h3>
        <p>Offices across 12 countries. Work with diverse teams and make an impact that reaches millions worldwide.</p>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ RECRUITMENT TIMELINE ══════════ -->
<section class="nt-section nt-timeline-section">
  <div class="nt-container">
    <div class="nt-section-header nt-reveal">
      <div class="nt-section-label">
        <span class="nt-section-label-line"></span>
        HOW IT WORKS
        <span class="nt-section-label-line" style="transform:rotate(180deg);"></span>
      </div>
      <h2 class="nt-section-title">Our Hiring Process</h2>
      <p class="nt-section-desc">A transparent, efficient process designed to be respectful of your time and showcase your strengths.</p>
    </div>

    <div class="nt-timeline nt-reveal">
      <div class="nt-timeline-step completed">
        <div class="nt-timeline-dot">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
        </div>
        <div class="nt-timeline-label">Apply Online</div>
        <div class="nt-timeline-desc">Submit your application and resume through our portal</div>
      </div>

      <div class="nt-timeline-step completed">
        <div class="nt-timeline-dot">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="nt-timeline-label">Resume Review</div>
        <div class="nt-timeline-desc">Our team reviews your qualifications within 48 hours</div>
      </div>

      <div class="nt-timeline-step active">
        <div class="nt-timeline-dot">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
        </div>
        <div class="nt-timeline-label">Technical Round</div>
        <div class="nt-timeline-desc">Showcase your skills in a collaborative coding session</div>
      </div>

      <div class="nt-timeline-step">
        <div class="nt-timeline-dot">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="nt-timeline-label">HR Discussion</div>
        <div class="nt-timeline-desc">Culture fit conversation and benefits discussion</div>
      </div>

      <div class="nt-timeline-step">
        <div class="nt-timeline-dot">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="nt-timeline-label">Offer Letter</div>
        <div class="nt-timeline-desc">Receive your offer and join the Neutara family</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ TESTIMONIALS ══════════ -->
<section class="nt-section nt-testimonials-section">
  <div class="nt-container">
    <div class="nt-section-header nt-reveal">
      <div class="nt-section-label">
        <span class="nt-section-label-line"></span>
        EMPLOYEE STORIES
        <span class="nt-section-label-line" style="transform:rotate(180deg);"></span>
      </div>
      <h2 class="nt-section-title">What Our Team Says</h2>
      <p class="nt-section-desc">Hear directly from the people who make Neutara Technologies a great place to work.</p>
    </div>
  </div>
  <div class="nt-reveal" style="overflow:hidden;">
    <div class="nt-testimonials-track">
      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"The engineering culture here is incredible. I've grown more in 2 years at Neutara than in 5 years elsewhere. The mentorship and learning opportunities are unmatched."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#0057FF,#0046CC);">SK</div>
          <div>
            <div class="nt-testimonial-name">Sarah Kim</div>
            <div class="nt-testimonial-role">Senior Engineer &middot; 2 years</div>
          </div>
        </div>
      </div>

      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"The work-life balance is real, not just a poster on the wall. Remote flexibility, mental health days, and a team that genuinely cares about your well-being."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">RP</div>
          <div>
            <div class="nt-testimonial-name">Raj Patel</div>
            <div class="nt-testimonial-role">Product Manager &middot; 3 years</div>
          </div>
        </div>
      </div>

      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"From intern to team lead in 3 years. Neutara invests in your growth with clear paths, regular feedback, and opportunities to lead meaningful projects."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">ML</div>
          <div>
            <div class="nt-testimonial-name">Maria Lopez</div>
            <div class="nt-testimonial-role">Team Lead &middot; 3 years</div>
          </div>
        </div>
      </div>

      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"The diversity of projects is amazing. One quarter I'm working on AI, the next on cloud infrastructure. There's never a dull moment and always something new to learn."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);">JC</div>
          <div>
            <div class="nt-testimonial-name">James Chen</div>
            <div class="nt-testimonial-role">DevOps Engineer &middot; 1 year</div>
          </div>
        </div>
      </div>

      <!-- Duplicate set for seamless scroll -->
      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"The engineering culture here is incredible. I've grown more in 2 years at Neutara than in 5 years elsewhere. The mentorship and learning opportunities are unmatched."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#0057FF,#0046CC);">SK</div>
          <div>
            <div class="nt-testimonial-name">Sarah Kim</div>
            <div class="nt-testimonial-role">Senior Engineer &middot; 2 years</div>
          </div>
        </div>
      </div>

      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"The work-life balance is real, not just a poster on the wall. Remote flexibility, mental health days, and a team that genuinely cares about your well-being."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#6366f1,#4f46e5);">RP</div>
          <div>
            <div class="nt-testimonial-name">Raj Patel</div>
            <div class="nt-testimonial-role">Product Manager &middot; 3 years</div>
          </div>
        </div>
      </div>

      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"From intern to team lead in 3 years. Neutara invests in your growth with clear paths, regular feedback, and opportunities to lead meaningful projects."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#10b981,#059669);">ML</div>
          <div>
            <div class="nt-testimonial-name">Maria Lopez</div>
            <div class="nt-testimonial-role">Team Lead &middot; 3 years</div>
          </div>
        </div>
      </div>

      <div class="nt-testimonial-card">
        <div class="nt-testimonial-stars">
          <span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span><span class="nt-star">&#9733;</span>
        </div>
        <div class="nt-testimonial-text">"The diversity of projects is amazing. One quarter I'm working on AI, the next on cloud infrastructure. There's never a dull moment and always something new to learn."</div>
        <div class="nt-testimonial-author">
          <div class="nt-testimonial-avatar" style="background:linear-gradient(135deg,#f59e0b,#d97706);">JC</div>
          <div>
            <div class="nt-testimonial-name">James Chen</div>
            <div class="nt-testimonial-role">DevOps Engineer &middot; 1 year</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ STATISTICS ══════════ -->
<section class="nt-section nt-stats-section">
  <div class="nt-container">
    <div class="nt-stats-grid nt-reveal" id="ntStats">
      <div class="nt-stat-card">
        <div class="nt-stat-number" data-target="10000">0</div>
        <div class="nt-stat-label">Applications Received</div>
      </div>
      <div class="nt-stat-card">
        <div class="nt-stat-number" data-target="2000">0</div>
        <div class="nt-stat-label">Team Members Worldwide</div>
      </div>
      <div class="nt-stat-card">
        <div class="nt-stat-number" data-target="98">0</div>
        <div class="nt-stat-label">Employee Satisfaction %</div>
      </div>
      <div class="nt-stat-card">
        <div class="nt-stat-number" data-target="150">0</div>
        <div class="nt-stat-label">Open Positions</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════ NEWSLETTER ══════════ -->
<section class="nt-section" style="padding-bottom:0;">
  <div class="nt-container">
    <div class="nt-newsletter nt-reveal">
      <div>
        <h3>Get Job Alerts</h3>
        <p>Stay updated with the latest openings matching your profile. No spam, unsubscribe anytime.</p>
      </div>
      <form class="nt-newsletter-form" onsubmit="event.preventDefault();this.querySelector('button').textContent='Subscribed!';this.querySelector('button').style.background='#10b981';">
        <input type="email" class="nt-newsletter-input" placeholder="Enter your email address" required>
        <button type="submit" class="nt-btn nt-btn-primary">Subscribe</button>
      </form>
    </div>
  </div>
</section>

<!-- ══════════ SCRIPTS ══════════ -->
<script>
(function() {
  // Navbar scroll effect
  var navbar = document.getElementById('ntNavbar');
  if (navbar) {
    window.addEventListener('scroll', function() {
      navbar.classList.toggle('scrolled', window.scrollY > 20);
    });
  }

  // Scroll reveal
  var reveals = document.querySelectorAll('.nt-reveal');
  if (reveals.length > 0 && 'IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(e) {
        if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); }
      });
    }, { threshold: 0.1 });
    reveals.forEach(function(el) { obs.observe(el); });
  } else {
    reveals.forEach(function(el) { el.classList.add('visible'); });
  }

  // Animated counters
  var statsEl = document.getElementById('ntStats');
  if (statsEl && 'IntersectionObserver' in window) {
    var counted = false;
    var statsObs = new IntersectionObserver(function(entries) {
      if (entries[0].isIntersecting && !counted) {
        counted = true;
        var nums = statsEl.querySelectorAll('.nt-stat-number[data-target]');
        nums.forEach(function(el) {
          var target = parseInt(el.getAttribute('data-target'));
          var suffix = target >= 10000 ? 'K+' : (target >= 100 ? '+' : '%');
          var displayTarget = target >= 10000 ? target / 1000 : target;
          var duration = 2000;
          var start = 0;
          var startTime = null;
          function animate(ts) {
            if (!startTime) startTime = ts;
            var progress = Math.min((ts - startTime) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = Math.floor(eased * displayTarget);
            el.textContent = current.toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(animate);
          }
          requestAnimationFrame(animate);
        });
      }
    }, { threshold: 0.3 });
    statsObs.observe(statsEl);
  }

  // Popular search tags click
  var tags = document.querySelectorAll('.nt-search-tag');
  tags.forEach(function(tag) {
    tag.addEventListener('click', function() {
      var input = document.querySelector('.nt-search-input[name="keyword"]');
      if (input) { input.value = this.textContent; input.focus(); }
    });
  });
})();
</script>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - SEARCH RESULTS
// ════════════════════════════════════════════════════════════════
$contentSearchResults = <<<'EOHTML'
<div class="nt-results-page">
  <div class="nt-container">
    <div class="nt-results-header">
      <h2>Search Results</h2>
      <div class="nt-results-count"><numberOfSearchResults> positions found</div>
    </div>
    <searchResultsTable>
    <div style="text-align:center;margin-top:32px;">
      <a href="<a-LinkMain>/" class="nt-btn nt-btn-ghost" style="color:var(--nt-primary);border-color:rgba(0,87,255,0.2);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Home
      </a>
    </div>
  </div>
</div>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - JOB DETAILS
// ════════════════════════════════════════════════════════════════
$contentJobDetails = <<<'EOHTML'
<div class="nt-job-page">
  <div class="nt-container">
    <div style="margin-bottom:20px;">
      <a href="<a-ListAll>/" class="nt-btn nt-btn-ghost nt-btn-sm" style="color:var(--nt-gray-600);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        All Positions
      </a>
    </div>

    <div class="nt-job-detail-card">
      <div class="nt-job-detail-hero">
        <h1><title></h1>
        <div class="nt-job-detail-meta">
          <span class="nt-job-detail-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            <city>, <state>
          </span>
          <span class="nt-job-detail-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <type>
          </span>
          <span class="nt-job-detail-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            Posted <daysOld> days ago
          </span>
          <span class="nt-job-detail-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <openings> Opening(s)
          </span>
          <span class="nt-job-detail-meta-item">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Recruiter: <recruiter>
          </span>
        </div>
      </div>

      <div class="nt-job-detail-body">
        <h2>Job Description</h2>
        <description>
      </div>

      <div class="nt-job-detail-apply">
        <p>Interested in this position? We'd love to hear from you.</p>
        <a-applyToJob><span class="nt-btn nt-btn-primary nt-btn-lg">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          Apply Now
        </span></a>
      </div>
    </div>
  </div>
</div>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - APPLY FOR POSITION
// ════════════════════════════════════════════════════════════════
$contentApply = <<<'EOHTML'
<div class="nt-apply-page">
  <div class="nt-container">
    <div style="margin-bottom:20px;">
      <a href="javascript:history.back()" class="nt-btn nt-btn-ghost nt-btn-sm" style="color:var(--nt-gray-600);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Job
      </a>
    </div>

    <div class="nt-apply-card">
      <div class="nt-apply-header">
        <h2>Apply for Position</h2>
        <p>Applying for: <title></p>
      </div>

      <div class="nt-apply-body">
        <div class="nt-form-section-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--nt-primary)" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Personal Information
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>First Name <span class="nt-required">*</span></label>
            <input-firstName>
          </div>
          <div class="nt-form-group">
            <label>Last Name <span class="nt-required">*</span></label>
            <input-lastName>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>Email Address <span class="nt-required">*</span></label>
            <input-email>
          </div>
          <div class="nt-form-group">
            <label>Phone Number</label>
            <input-phone>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>Address</label>
            <input-address>
          </div>
          <div class="nt-form-group">
            <label>City</label>
            <input-city>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>State</label>
            <input-state>
          </div>
          <div class="nt-form-group">
            <label>Zip Code</label>
            <input-zip>
          </div>
        </div>

        <div class="nt-form-section-title" style="margin-top:32px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--nt-primary)" stroke-width="2" stroke-linecap="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a4 4 0 0 0-8 0v2"/></svg>
          Professional Details
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Current Employer</label>
            <input-employer>
          </div>
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Key Skills</label>
            <input-keySkills>
          </div>
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>How did you hear about us?</label>
            <input-source>
          </div>
        </div>

        <div class="nt-form-section-title" style="margin-top:32px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--nt-primary)" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Resume & Additional Info
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Upload Resume <span class="nt-required">*</span></label>
            <input-resumeUpload>
          </div>
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Additional Notes</label>
            <input-extraNotes>
          </div>
        </div>

        <div class="nt-form-submit">
          <submit value="Submit Application">
        </div>
      </div>
    </div>
  </div>
</div>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - CANDIDATE REGISTRATION
// ════════════════════════════════════════════════════════════════
$contentRegistration = <<<'EOHTML'
<div class="nt-reg-page">
  <div class="nt-container">
    <div class="nt-apply-card">
      <div class="nt-apply-header">
        <h2>Create Your Profile</h2>
        <p>Register to apply for positions and track your applications</p>
      </div>

      <div class="nt-apply-body">
        <div class="nt-form-section-title">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--nt-primary)" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          Personal Information
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>First Name <span class="nt-required">*</span></label>
            <input-firstName>
          </div>
          <div class="nt-form-group">
            <label>Last Name <span class="nt-required">*</span></label>
            <input-lastName>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>Email <span class="nt-required">*</span></label>
            <input-email>
          </div>
          <div class="nt-form-group">
            <label>Phone</label>
            <input-phone>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>Address</label>
            <input-address>
          </div>
          <div class="nt-form-group">
            <label>City</label>
            <input-city>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>State</label>
            <input-state>
          </div>
          <div class="nt-form-group">
            <label>Zip Code</label>
            <input-zip>
          </div>
        </div>

        <div class="nt-form-section-title" style="margin-top:32px;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--nt-primary)" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          Resume
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Upload Resume</label>
            <input-resumeUpload>
          </div>
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Key Skills</label>
            <input-keySkills>
          </div>
        </div>

        <div class="nt-form-submit">
          <submit value="Create Profile">
        </div>
      </div>
    </div>
  </div>
</div>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - CANDIDATE PROFILE
// ════════════════════════════════════════════════════════════════
$contentProfile = <<<'EOHTML'
<div class="nt-reg-page">
  <div class="nt-container">
    <div class="nt-apply-card">
      <div class="nt-apply-header">
        <h2>Your Profile</h2>
        <p>Review and update your candidate information</p>
      </div>

      <div class="nt-apply-body">
        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>First Name</label>
            <input-firstName>
          </div>
          <div class="nt-form-group">
            <label>Last Name</label>
            <input-lastName>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>Email</label>
            <input-email>
          </div>
          <div class="nt-form-group">
            <label>Phone</label>
            <input-phone>
          </div>
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Address</label>
            <input-address>
          </div>
        </div>

        <div class="nt-form-row">
          <div class="nt-form-group">
            <label>City</label>
            <input-city>
          </div>
          <div class="nt-form-group">
            <label>State</label>
            <input-state>
          </div>
        </div>

        <div class="nt-form-row full">
          <div class="nt-form-group">
            <label>Key Skills</label>
            <input-keySkills>
          </div>
        </div>

        <div class="nt-form-submit">
          <submit value="Update Profile">
        </div>
      </div>
    </div>
  </div>
</div>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - QUESTIONNAIRE
// ════════════════════════════════════════════════════════════════
$contentQuestionnaire = <<<'EOHTML'
<div class="nt-questionnaire-page">
  <div class="nt-container">
    <div style="margin-bottom:20px;">
      <a href="javascript:history.back()" class="nt-btn nt-btn-ghost nt-btn-sm" style="color:var(--nt-gray-600);">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
        Back
      </a>
    </div>

    <div class="nt-questionnaire-card">
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;">
        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,rgba(0,87,255,0.1),rgba(0,87,255,0.05));display:flex;align-items:center;justify-content:center;">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#0057FF" stroke-width="2" stroke-linecap="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        </div>
        <div>
          <h2 style="font-size:22px;font-weight:800;color:var(--nt-navy);margin:0;">Questionnaire</h2>
          <p style="font-size:13px;color:var(--nt-gray-500);margin:2px 0 0;">Please answer the following questions to complete your application.</p>
        </div>
      </div>
    </div>
  </div>
</div>
EOHTML;

// ════════════════════════════════════════════════════════════════
// CONTENT - THANKS
// ════════════════════════════════════════════════════════════════
$contentThanks = <<<'EOHTML'
<div class="nt-thanks-page">
  <div class="nt-container">
    <div class="nt-thanks-card">
      <div class="nt-thanks-icon">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
      </div>
      <h2>Application Submitted!</h2>
      <p>Thank you for applying to Neutara Technologies. Our recruitment team will review your application and get back to you within 3-5 business days.</p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
        <a-jobDetails><span class="nt-btn nt-btn-ghost" style="color:var(--nt-primary);border-color:rgba(0,87,255,0.2);">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="15 18 9 12 15 6"/></svg>
          View Job Details
        </span></a>
        <a href="<a-LinkMain>/" class="nt-btn nt-btn-primary">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
          Back to Home
        </a>
      </div>
    </div>
  </div>
</div>
EOHTML;

// ════════════════════════════════════════════════════════════════
// FOOTER
// ════════════════════════════════════════════════════════════════
$footer = <<<'EOHTML'
<footer class="nt-footer">
  <div class="nt-footer-grid">
    <div class="nt-footer-brand">
      <div class="nt-logo" style="margin-bottom:4px;">
        <div class="nt-logo-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"/>
            <line x1="12" y1="22" x2="12" y2="15.5"/>
            <polyline points="22 8.5 12 15.5 2 8.5"/>
          </svg>
        </div>
        <span class="nt-logo-text" style="color:#fff;">Neutara<span style="color:#60a5fa;">Tech</span></span>
      </div>
      <p>Empowering talent, driving innovation. Building the technology solutions that shape tomorrow's enterprise landscape.</p>
      <div class="nt-footer-social">
        <a href="#" title="LinkedIn">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
        <a href="#" title="Twitter">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
        </a>
        <a href="#" title="GitHub">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
        </a>
        <a href="#" title="YouTube">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
        </a>
      </div>
    </div>

    <div class="nt-footer-col">
      <h4>Careers</h4>
      <a href="<a-ListAll>/">All Positions</a>
      <a href="#">Engineering</a>
      <a href="#">Product</a>
      <a href="#">Design</a>
      <a href="#">Marketing</a>
      <a href="#">Internships</a>
    </div>

    <div class="nt-footer-col">
      <h4>Company</h4>
      <a href="#">About Us</a>
      <a href="#">Leadership</a>
      <a href="#">News & Blog</a>
      <a href="#">Press Room</a>
      <a href="#">Investors</a>
    </div>

    <div class="nt-footer-col">
      <h4>Support</h4>
      <a href="#">Help Center</a>
      <a href="#">Contact Us</a>
      <a href="#">Privacy Policy</a>
      <a href="#">Terms of Service</a>
      <a href="#">Accessibility</a>
    </div>
  </div>

  <div class="nt-footer-bottom">
    <p>&copy; 2026 Neutara Technologies, Inc. All rights reserved. &nbsp;&middot;&nbsp; Building the future of enterprise technology.</p>
  </div>
</footer>
EOHTML;

// ════════════════════════════════════════════════════════════════
// INSERT ALL SECTIONS
// ════════════════════════════════════════════════════════════════
$sections = [
    'Header'                                => $header,
    'Footer'                                => $footer,
    'CSS'                                   => $css,
    'Content - Main'                        => $contentMain,
    'Content - Search Results'              => $contentSearchResults,
    'Content - Job Details'                 => $contentJobDetails,
    'Content - Apply for Position'          => $contentApply,
    'Content - Candidate Registration'      => $contentRegistration,
    'Content - Candidate Profile'           => $contentProfile,
    'Content - Questionnaire'               => $contentQuestionnaire,
    'Content - Thanks for your Submission'  => $contentThanks,
];

$insert = $pdo->prepare(
    "INSERT INTO career_portal_template (setting, value, career_portal_name) VALUES (?, ?, ?)"
);

$count = 0;
foreach ($sections as $setting => $value) {
    $insert->execute([$setting, $value, $templateName]);
    $count++;
    echo "  Inserted: $setting\n";
}

// Set as active template
$pdo->prepare("DELETE FROM settings WHERE setting = 'activeBoard' AND settings_type = 1 AND site_id = 1")->execute();
$pdo->prepare("INSERT INTO settings (settings_type, setting, value, site_id) VALUES (1, 'activeBoard', ?, 1)")->execute([$templateName]);

echo "\nDone! Deployed '$templateName' with $count sections and set as active.\n";
echo "Visit your career portal to see the new design.\n";
