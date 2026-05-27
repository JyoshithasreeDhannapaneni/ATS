<?php
/**
 * Neutara Technologies — Full 7-Step ATS Career Portal Template Installer
 * Run: php install_neutara_template.php
 */

$pdo = new PDO('pgsql:host=localhost;port=5432;dbname=cats_dev', 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

$templateName = 'Neutara';

$pdo->prepare("DELETE FROM career_portal_template WHERE career_portal_name = :name")
    ->execute(['name' => $templateName]);

$ins = $pdo->prepare("INSERT INTO career_portal_template (career_portal_name, setting, value) VALUES (:name, :setting, :value)");

// ── CSS ──────────────────────────────────────────────────────────────────────
$css = <<<'CSS'
:root {
  --brand: #0d2488;
  --brand-dark: #091a6e;
  --brand-light: #e8ecfb;
  --brand-mid: #2a3fa3;
  --text-primary: #0f172a;
  --text-secondary: #475569;
  --text-muted: #94a3b8;
  --border: #e2e8f0;
  --bg: #f8fafc;
  --white: #ffffff;
  --success: #16a34a;
  --danger: #dc2626;
  --radius-sm: 6px;
  --radius-md: 10px;
  --radius-lg: 14px;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: 'Inter', system-ui, -apple-system, sans-serif; color: var(--text-primary); background: var(--bg); font-size: 14px; line-height: 1.6; }

/* ── TOPBAR ── */
.nt-topbar {
  background: var(--brand);
  padding: 0 32px;
  height: 58px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: sticky;
  top: 0;
  z-index: 100;
}
.nt-logo {
  display: flex;
  align-items: center;
  gap: 12px;
}
.nt-logo-mark {
  width: 38px;
  height: 38px;
  background: var(--white);
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
}
.nt-logo-mark span {
  font-size: 20px;
  font-weight: 900;
  color: var(--brand);
  font-family: Georgia, serif;
  line-height: 1;
}
.nt-logo-mark .sparkle {
  position: absolute;
  top: 4px;
  right: 4px;
  width: 8px;
  height: 8px;
}
.nt-logo-text { color: var(--white); }
.nt-logo-text strong { display: block; font-size: 14px; font-weight: 800; letter-spacing: -0.01em; }
.nt-logo-text small { font-size: 9px; font-weight: 400; letter-spacing: 0.12em; text-transform: uppercase; opacity: 0.65; }
.nt-topbar-center {
  display: flex;
  align-items: center;
  gap: 16px;
}
.nt-progress-wrap { display: flex; align-items: center; gap: 10px; }
.nt-progress-bar-bg {
  width: 180px;
  height: 5px;
  background: rgba(255,255,255,0.2);
  border-radius: 99px;
  overflow: hidden;
}
.nt-progress-bar-fill {
  height: 100%;
  background: #60a5fa;
  border-radius: 99px;
  transition: width 0.4s ease;
  width: 0%;
}
.nt-progress-text { color: rgba(255,255,255,0.8); font-size: 12px; font-weight: 600; white-space: nowrap; }
.nt-autosave { color: rgba(255,255,255,0.55); font-size: 12px; display: flex; align-items: center; gap: 5px; }
.nt-autosave svg { color: #4ade80; }

/* ── LAYOUT ── */
.nt-layout {
  display: flex;
  min-height: calc(100vh - 58px);
  max-width: 1140px;
  margin: 0 auto;
  padding: 28px 24px;
  gap: 24px;
}
.nt-sidebar {
  width: 280px;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.nt-main { flex: 1; min-width: 0; }

/* ── JOB CARD ── */
.nt-job-card {
  background: var(--white);
  border: 0.5px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 20px;
}
.nt-job-card-role { font-size: 15px; font-weight: 700; color: var(--text-primary); margin-bottom: 4px; }
.nt-job-card-company { font-size: 13px; color: var(--brand); font-weight: 600; margin-bottom: 12px; }
.nt-job-meta { list-style: none; display: flex; flex-direction: column; gap: 7px; }
.nt-job-meta li { display: flex; align-items: center; gap: 8px; font-size: 12px; color: var(--text-secondary); }
.nt-job-meta li svg { color: var(--text-muted); flex-shrink: 0; }
.nt-job-deadline { margin-top: 14px; padding-top: 14px; border-top: 0.5px solid var(--border); display: flex; justify-content: space-between; font-size: 12px; }
.nt-job-deadline span { color: var(--text-muted); }
.nt-job-deadline strong { color: var(--danger); font-weight: 600; }

/* ── STEP TRACKER ── */
.nt-step-tracker {
  background: var(--white);
  border: 0.5px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 16px 0;
}
.nt-step-tracker-title {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--text-muted);
  padding: 0 18px 12px;
  border-bottom: 0.5px solid var(--border);
  margin-bottom: 8px;
}
.nt-step-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 9px 18px;
  cursor: pointer;
  transition: background 0.15s;
  border-left: 3px solid transparent;
}
.nt-step-item:hover { background: var(--bg); }
.nt-step-item.active { background: var(--brand-light); border-left-color: var(--brand); }
.nt-step-item.done { opacity: 0.85; }
.nt-step-num {
  width: 26px;
  height: 26px;
  border-radius: 50%;
  border: 1.5px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  font-weight: 700;
  color: var(--text-muted);
  flex-shrink: 0;
  background: var(--white);
  transition: all 0.2s;
}
.nt-step-item.active .nt-step-num { background: var(--brand); border-color: var(--brand); color: var(--white); }
.nt-step-item.done .nt-step-num { background: #dcfce7; border-color: #86efac; color: var(--success); }
.nt-step-label { font-size: 13px; color: var(--text-secondary); font-weight: 500; }
.nt-step-item.active .nt-step-label { color: var(--brand); font-weight: 700; }

/* ── CARD ── */
.nt-card {
  background: var(--white);
  border: 0.5px solid var(--border);
  border-radius: var(--radius-lg);
  margin-bottom: 20px;
  overflow: hidden;
}
.nt-card-head {
  padding: 20px 24px 16px;
  border-bottom: 0.5px solid var(--border);
  display: flex;
  align-items: center;
  gap: 14px;
}
.nt-card-icon {
  width: 38px;
  height: 38px;
  border-radius: var(--radius-md);
  background: var(--brand-light);
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.nt-card-icon svg { color: var(--brand); }
.nt-card-title { font-size: 16px; font-weight: 700; color: var(--text-primary); }
.nt-card-subtitle { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
.nt-card-body { padding: 24px; }

/* ── STEPS (show/hide) ── */
.nt-step-panel { display: none; }
.nt-step-panel.nt-active { display: block; }

/* ── FORM ELEMENTS ── */
.nt-field { margin-bottom: 18px; }
.nt-field label { display: block; font-size: 12px; font-weight: 600; color: var(--text-secondary); margin-bottom: 6px; }
.nt-field label .req { color: var(--danger); margin-left: 2px; }
.nt-input {
  width: 100%;
  padding: 9px 13px;
  border: 0.5px solid var(--border);
  border-radius: var(--radius-md);
  font-size: 13px;
  color: var(--text-primary);
  background: var(--white);
  outline: none;
  transition: border-color 0.15s, box-shadow 0.15s;
  font-family: inherit;
}
.nt-input:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(13,36,136,0.08); }
.nt-select {
  width: 100%;
  padding: 9px 13px;
  border: 0.5px solid var(--border);
  border-radius: var(--radius-md);
  font-size: 13px;
  color: var(--text-primary);
  background: var(--white);
  outline: none;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='none' stroke='%2394a3b8' stroke-width='2' viewBox='0 0 24 24'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  padding-right: 34px;
  font-family: inherit;
}
.nt-select:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(13,36,136,0.08); }
.nt-textarea {
  width: 100%;
  padding: 10px 13px;
  border: 0.5px solid var(--border);
  border-radius: var(--radius-md);
  font-size: 13px;
  color: var(--text-primary);
  background: var(--white);
  outline: none;
  resize: vertical;
  min-height: 100px;
  font-family: inherit;
}
.nt-textarea:focus { border-color: var(--brand); box-shadow: 0 0 0 3px rgba(13,36,136,0.08); }
.nt-row { display: grid; gap: 16px; grid-template-columns: 1fr 1fr; }
.nt-row-3 { display: grid; gap: 16px; grid-template-columns: 1fr 1fr 1fr; }

/* ── UPLOAD ZONE ── */
.nt-upload-zone {
  border: 1.5px dashed var(--border);
  border-radius: var(--radius-md);
  padding: 28px 20px;
  text-align: center;
  cursor: pointer;
  transition: all 0.2s;
  background: var(--bg);
}
.nt-upload-zone:hover { border-color: var(--brand); background: var(--brand-light); }
.nt-upload-zone.attached { border-style: solid; border-color: #86efac; background: #f0fdf4; }
.nt-upload-zone svg { color: var(--text-muted); margin-bottom: 8px; }
.nt-upload-zone.attached svg { color: var(--success); }
.nt-upload-zone p { font-size: 13px; color: var(--text-muted); margin-bottom: 4px; }
.nt-upload-zone .attached-name { font-size: 13px; font-weight: 600; color: var(--success); }
.nt-upload-btn { font-size: 12px; font-weight: 600; color: var(--brand); margin-top: 6px; display: inline-block; cursor: pointer; }
.nt-upload-zone.attached .nt-upload-btn { color: var(--danger); }

/* ── SKILL DOTS ── */
.nt-skill-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-bottom: 0.5px solid var(--border); }
.nt-skill-row:last-child { border-bottom: none; }
.nt-skill-name { font-size: 13px; font-weight: 500; color: var(--text-primary); }
.nt-dots { display: flex; gap: 6px; }
.nt-dot {
  width: 22px;
  height: 22px;
  border-radius: 50%;
  border: 1.5px solid var(--border);
  cursor: pointer;
  transition: all 0.15s;
  background: var(--white);
}
.nt-dot.filled { background: var(--brand); border-color: var(--brand); }
.nt-dot:hover { border-color: var(--brand); }

/* ── CHECKBOX GRID ── */
.nt-check-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
.nt-check-item { display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-secondary); }
.nt-check-item input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--brand); cursor: pointer; }
.nt-radio-group { display: flex; flex-direction: column; gap: 10px; }
.nt-radio-item { display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--text-secondary); }
.nt-radio-item input[type=radio] { width: 15px; height: 15px; accent-color: var(--brand); cursor: pointer; }

/* ── EEO NOTICE ── */
.nt-eeo-notice {
  background: #eff6ff;
  border: 0.5px solid #bfdbfe;
  border-radius: var(--radius-md);
  padding: 14px 16px;
  font-size: 12px;
  color: #1e40af;
  margin-bottom: 20px;
  line-height: 1.6;
}

/* ── REVIEW CARDS ── */
.nt-review-section { margin-bottom: 20px; }
.nt-review-section-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between; }
.nt-review-section-title button { font-size: 11px; font-weight: 600; color: var(--brand); background: none; border: none; cursor: pointer; }
.nt-review-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
.nt-review-item label { font-size: 11px; color: var(--text-muted); display: block; margin-bottom: 2px; }
.nt-review-item p { font-size: 13px; font-weight: 600; color: var(--text-primary); }
.nt-agreement-row { display: flex; align-items: flex-start; gap: 10px; margin-bottom: 14px; font-size: 13px; color: var(--text-secondary); }
.nt-agreement-row input[type=checkbox] { width: 15px; height: 15px; accent-color: var(--brand); cursor: pointer; margin-top: 2px; flex-shrink: 0; }

/* ── BUTTONS ── */
.nt-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 10px 22px;
  border-radius: var(--radius-md);
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  border: 0.5px solid transparent;
  transition: all 0.15s;
  font-family: inherit;
}
.nt-btn-primary { background: var(--brand); color: var(--white); }
.nt-btn-primary:hover { background: var(--brand-dark); }
.nt-btn-secondary { background: var(--white); color: var(--text-secondary); border-color: var(--border); }
.nt-btn-secondary:hover { background: var(--bg); }
.nt-btn-outline { background: transparent; color: var(--brand); border-color: var(--brand); }
.nt-btn-outline:hover { background: var(--brand-light); }
.nt-nav-row { display: flex; justify-content: space-between; align-items: center; margin-top: 24px; padding-top: 20px; border-top: 0.5px solid var(--border); }

/* ── CONFIRM SCREEN ── */
.nt-confirm { display: none; max-width: 580px; margin: 60px auto; text-align: center; }
.nt-confirm-icon { width: 72px; height: 72px; background: #dcfce7; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; }
.nt-confirm h1 { font-size: 24px; font-weight: 800; color: var(--text-primary); margin-bottom: 8px; }
.nt-confirm p { color: var(--text-secondary); font-size: 14px; margin-bottom: 6px; }
.nt-ref-id { background: var(--brand-light); color: var(--brand); font: 700 16px monospace; padding: 10px 24px; border-radius: var(--radius-md); display: inline-block; margin: 16px 0 24px; letter-spacing: 0.05em; }
.nt-confirm-actions { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; }

@media (max-width: 900px) {
  .nt-layout { flex-direction: column; }
  .nt-sidebar { width: 100%; }
  .nt-row, .nt-row-3 { grid-template-columns: 1fr; }
  .nt-check-grid { grid-template-columns: 1fr 1fr; }
}
CSS;

// ── HEADER ───────────────────────────────────────────────────────────────────
$header = <<<'HDR'
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Careers — Neutara Technologies</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<style><css></style>
</head>
<body>
<!-- TOPBAR -->
<div class="nt-topbar">
  <div class="nt-logo">
    <div class="nt-logo-mark">
      <span>N</span>
      <svg class="sparkle" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M5 0 L5.8 3.2 L9 5 L5.8 6.8 L5 10 L4.2 6.8 L1 5 L4.2 3.2 Z" fill="#0d2488" opacity="0.7"/>
      </svg>
    </div>
    <div class="nt-logo-text">
      <strong>neutara</strong>
      <small>technologies</small>
    </div>
  </div>
  <div style="color:rgba(255,255,255,0.7);font-size:13px;font-weight:500;">Careers</div>
  <div style="width:120px;"></div>
</div>
HDR;

// ── CONTENT - MAIN (job listings) ────────────────────────────────────────────
$contentMain = <<<'MAIN'
<div style="max-width:1100px;margin:0 auto;padding:36px 24px;">

  <!-- Hero -->
  <div style="text-align:center;margin-bottom:40px;">
    <div style="display:inline-flex;align-items:center;gap:8px;background:#e8ecfb;color:#0d2488;font-size:12px;font-weight:700;letter-spacing:0.06em;text-transform:uppercase;padding:6px 16px;border-radius:99px;margin-bottom:16px;">
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
      We're Hiring
    </div>
    <h1 style="font-size:36px;font-weight:900;color:#0f172a;letter-spacing:-0.03em;margin-bottom:12px;">Join Neutara Technologies</h1>
    <p style="font-size:15px;color:#475569;max-width:520px;margin:0 auto;">Build the future of intelligent hiring. We're looking for exceptional people who love solving hard problems.</p>
  </div>

  <!-- Search bar -->
  <div style="display:flex;gap:12px;margin-bottom:32px;max-width:700px;margin-left:auto;margin-right:auto;">
    <div style="flex:1;position:relative;">
      <svg style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#94a3b8;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input style="width:100%;padding:10px 13px 10px 38px;border:0.5px solid #e2e8f0;border-radius:10px;font-size:13px;font-family:inherit;outline:none;" placeholder="Search jobs, titles, skills…" oninput="ntSearch(this.value)">
    </div>
    <a href="?m=careers&p=showAll" style="display:inline-flex;align-items:center;gap:6px;padding:10px 20px;background:#0d2488;color:#fff;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">View All</a>
  </div>

  <!-- Open positions count -->
  <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;margin-bottom:16px;">
    Open Positions &middot; <searchResultsCount>
  </div>

  <!-- Job table -->
  <searchResultsTable showTitle=true showCompany=false showDepartment=true>

</div>
<script>
function ntSearch(q) {
  q = q.toLowerCase();
  document.querySelectorAll('table tr[data-title]').forEach(function(r){
    r.style.display = r.dataset.title.toLowerCase().indexOf(q) > -1 ? '' : 'none';
  });
}
</script>
MAIN;

// ── CONTENT - SEARCH RESULTS ─────────────────────────────────────────────────
$contentSearch = <<<'SEARCH'
<div style="max-width:1100px;margin:0 auto;padding:36px 24px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
      <h1 style="font-size:24px;font-weight:800;color:#0f172a;margin-bottom:4px;">Open Positions</h1>
      <p style="font-size:13px;color:#475569;"><numberOfSearchResults> roles available</p>
    </div>
    <registeredCandidate>
  </div>
  <searchResultsTableUnformatted>
</div>
SEARCH;

// ── CONTENT - JOB DETAILS ────────────────────────────────────────────────────
$contentJobDetail = <<<'JOB'
<div style="max-width:900px;margin:0 auto;padding:36px 24px;">
  <a href="javascript:history.back()" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:#475569;text-decoration:none;margin-bottom:24px;">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
    Back to Jobs
  </a>
  <div style="background:#fff;border:0.5px solid #e2e8f0;border-radius:14px;overflow:hidden;">
    <div style="background:#0d2488;padding:28px 32px;">
      <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:rgba(255,255,255,0.55);margin-bottom:8px;">Neutara Technologies</div>
      <h1 style="font-size:26px;font-weight:900;color:#fff;margin-bottom:12px;letter-spacing:-0.02em;"><title></h1>
      <div style="display:flex;flex-wrap:wrap;gap:12px;">
        <span style="display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,0.75);font-size:13px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <location>
        </span>
        <span style="display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,0.75);font-size:13px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
          <type>
        </span>
        <span style="display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,0.75);font-size:13px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <dateCreated>
        </span>
      </div>
    </div>
    <div style="padding:32px;">
      <div style="display:grid;grid-template-columns:1fr 200px;gap:32px;">
        <div>
          <h2 style="font-size:15px;font-weight:700;color:#0f172a;margin-bottom:12px;">About this Role</h2>
          <div style="font-size:14px;color:#475569;line-height:1.75;"><description></div>
        </div>
        <div>
          <a href="<applyURL>" style="display:block;text-align:center;background:#0d2488;color:#fff;font-size:14px;font-weight:700;padding:13px 20px;border-radius:10px;text-decoration:none;margin-bottom:12px;">Apply Now</a>
          <div style="font-size:12px;color:#94a3b8;text-align:center;">Takes about 10 min</div>
        </div>
      </div>
    </div>
  </div>
</div>
JOB;

// ── CONTENT - CANDIDATE REGISTRATION ────────────────────────────────────────
$contentCandReg = <<<'REG'
<div style="background:#fff;border:0.5px solid #e2e8f0;border-radius:14px;padding:24px;margin-bottom:16px;">
  <h3 style="font-size:14px;font-weight:700;color:#0f172a;margin-bottom:14px;">Save Your Progress</h3>
  <p style="font-size:12px;color:#64748b;margin-bottom:12px;">Enter your email to save and return to your application.</p>
  <input name="email1" id="email1" type="email" class="nt-input" placeholder="your@email.com" style="margin-bottom:10px;">
  <button type="submit" class="nt-btn nt-btn-primary" style="width:100%;">Save Progress</button>
</div>
REG;

// ── CONTENT - CANDIDATE PROFILE ──────────────────────────────────────────────
$contentCandProfile = <<<'PROF'
<div style="max-width:700px;margin:0 auto;padding:36px 24px;">
  <div style="background:#fff;border:0.5px solid #e2e8f0;border-radius:14px;padding:28px;">
    <h2 style="font-size:18px;font-weight:800;margin-bottom:20px;">Your Profile</h2>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
      <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">First Name</label><input-firstName></div>
      <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">Last Name</label><input-lastName></div>
      <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">Email</label><input-email1></div>
      <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">Phone</label><input-phoneWork></div>
    </div>
    <div style="margin-bottom:16px;"><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">Address</label><input-address></div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
      <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">City</label><input-city></div>
      <div><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">State</label><input-state></div>
    </div>
    <div style="margin-bottom:20px;"><label style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:#94a3b8;display:block;margin-bottom:4px;">Resume</label><input-resume></div>
    <button type="submit" class="nt-btn nt-btn-primary">Update Profile</button>
  </div>
</div>
PROF;

// ── CONTENT - APPLY FOR POSITION (7-step form) ───────────────────────────────
$contentApply = <<<'APPLY'
<div class="nt-layout" id="ntApp">

  <!-- SIDEBAR -->
  <aside class="nt-sidebar">

    <!-- Job card -->
    <div class="nt-job-card">
      <div class="nt-job-card-role"><title></div>
      <div class="nt-job-card-company">Neutara Technologies</div>
      <ul class="nt-job-meta">
        <li>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          <location>
        </li>
        <li>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
          <type>
        </li>
        <li>
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
          Applicants applying
        </li>
      </ul>
      <div class="nt-job-deadline">
        <span>Closes</span>
        <strong><dateCreated></strong>
      </div>
    </div>

    <!-- Step tracker -->
    <div class="nt-step-tracker">
      <div class="nt-step-tracker-title">Application Steps</div>
      <div class="nt-step-item active" onclick="ntGoStep(1)" id="ntStepNav1">
        <div class="nt-step-num" id="ntStepNum1">1</div>
        <span class="nt-step-label">Personal Info</span>
      </div>
      <div class="nt-step-item" onclick="ntGoStep(2)" id="ntStepNav2">
        <div class="nt-step-num" id="ntStepNum2">2</div>
        <span class="nt-step-label">Resume &amp; Docs</span>
      </div>
      <div class="nt-step-item" onclick="ntGoStep(3)" id="ntStepNav3">
        <div class="nt-step-num" id="ntStepNum3">3</div>
        <span class="nt-step-label">Work Experience</span>
      </div>
      <div class="nt-step-item" onclick="ntGoStep(4)" id="ntStepNav4">
        <div class="nt-step-num" id="ntStepNum4">4</div>
        <span class="nt-step-label">Skills</span>
      </div>
      <div class="nt-step-item" onclick="ntGoStep(5)" id="ntStepNav5">
        <div class="nt-step-num" id="ntStepNum5">5</div>
        <span class="nt-step-label">Screening Questions</span>
      </div>
      <div class="nt-step-item" onclick="ntGoStep(6)" id="ntStepNav6">
        <div class="nt-step-num" id="ntStepNum6">6</div>
        <span class="nt-step-label">Equal Opportunity</span>
      </div>
      <div class="nt-step-item" onclick="ntGoStep(7)" id="ntStepNav7">
        <div class="nt-step-num" id="ntStepNum7">7</div>
        <span class="nt-step-label">Review &amp; Submit</span>
      </div>
    </div>

  </aside>

  <!-- MAIN FORM -->
  <div class="nt-main">

    <!-- Topbar progress -->
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
      <div style="font-size:13px;color:#475569;">
        <a href="javascript:history.back()" style="color:#0d2488;text-decoration:none;display:inline-flex;align-items:center;gap:5px;">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
          Back to listing
        </a>
      </div>
      <div style="display:flex;align-items:center;gap:14px;">
        <span id="ntAutoSave" style="font-size:12px;color:#94a3b8;display:flex;align-items:center;gap:5px;">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          Auto-saved
        </span>
        <div class="nt-progress-wrap">
          <div class="nt-progress-bar-bg" style="width:140px;height:5px;background:#e2e8f0;border-radius:99px;overflow:hidden;">
            <div id="ntProgFill" style="height:100%;background:#0d2488;border-radius:99px;transition:width 0.4s;width:14%;"></div>
          </div>
          <span id="ntProgText" style="font-size:12px;font-weight:600;color:#475569;">Step 1 of 7</span>
        </div>
      </div>
    </div>

    <form name="submitApplicationForm" id="submitApplicationForm" method="post" enctype="multipart/form-data">

      <!-- ── STEP 1: Personal Information ── -->
      <div class="nt-step-panel nt-active" id="ntStep1">
        <div class="nt-card">
          <div class="nt-card-head">
            <div class="nt-card-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
              <div class="nt-card-title">Personal Information</div>
              <div class="nt-card-subtitle">Tell us about yourself</div>
            </div>
          </div>
          <div class="nt-card-body">
            <div class="nt-row">
              <div class="nt-field">
                <label>First Name <span class="req">*</span></label>
                <input type="text" name="firstName" id="firstName" class="nt-input" placeholder="Jane" required>
              </div>
              <div class="nt-field">
                <label>Last Name <span class="req">*</span></label>
                <input type="text" name="lastName" id="lastName" class="nt-input" placeholder="Smith" required>
              </div>
            </div>
            <div class="nt-row">
              <div class="nt-field">
                <label>Email Address <span class="req">*</span></label>
                <input type="email" name="email1" id="email1" class="nt-input" placeholder="jane@example.com" required>
              </div>
              <div class="nt-field">
                <label>Phone Number <span class="req">*</span></label>
                <input type="tel" name="phoneWork" id="phoneWork" class="nt-input" placeholder="+1 (555) 000-0000" required>
              </div>
            </div>
            <div class="nt-row">
              <div class="nt-field">
                <label>City</label>
                <input type="text" name="city" id="city" class="nt-input" placeholder="San Francisco">
              </div>
              <div class="nt-field">
                <label>State / Province</label>
                <input type="text" name="state" id="state" class="nt-input" placeholder="CA">
              </div>
            </div>
            <div class="nt-field">
              <label>LinkedIn Profile URL</label>
              <input type="url" name="website" id="website" class="nt-input" placeholder="https://linkedin.com/in/yourprofile">
            </div>
            <div class="nt-row">
              <div class="nt-field">
                <label>Work Authorization <span class="req">*</span></label>
                <select name="workAuthorization" class="nt-select" required>
                  <option value="">Select…</option>
                  <option>US Citizen</option>
                  <option>Permanent Resident</option>
                  <option>H-1B Visa</option>
                  <option>OPT/CPT</option>
                  <option>Other Work Visa</option>
                  <option>Require Sponsorship</option>
                </select>
              </div>
              <div class="nt-field">
                <label>Earliest Start Date</label>
                <input type="date" name="startDate" class="nt-input">
              </div>
            </div>
          </div>
        </div>
        <div class="nt-nav-row">
          <span></span>
          <button type="button" class="nt-btn nt-btn-primary" onclick="ntNext(1)">
            Next: Resume &amp; Docs
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <!-- ── STEP 2: Resume & Documents ── -->
      <div class="nt-step-panel" id="ntStep2">
        <div class="nt-card">
          <div class="nt-card-head">
            <div class="nt-card-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div>
              <div class="nt-card-title">Resume &amp; Documents</div>
              <div class="nt-card-subtitle">Upload your application materials</div>
            </div>
          </div>
          <div class="nt-card-body">
            <div class="nt-row" style="margin-bottom:20px;">
              <!-- Resume upload -->
              <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:8px;">Resume <span class="req">*</span></label>
                <div class="nt-upload-zone" id="resumeZone" onclick="ntToggleUpload('resume')">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                  <p id="resumeZoneTxt">Drag &amp; drop or click to upload</p>
                  <span class="nt-upload-btn" id="resumeBtn">Attach Resume</span>
                  <input type="file" name="resume" id="resumeFile" style="display:none;" accept=".pdf,.doc,.docx" onchange="ntFileSelected('resume',this)">
                </div>
                <p style="font-size:11px;color:#94a3b8;margin-top:6px;">PDF, DOC, DOCX · Max 10 MB</p>
              </div>
              <!-- Cover letter upload -->
              <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#475569;margin-bottom:8px;">Cover Letter</label>
                <div class="nt-upload-zone" id="coverZone" onclick="ntToggleUpload('cover')">
                  <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 14.66V20a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2h5.34"/><polygon points="18 2 22 6 12 16 8 16 8 12 18 2"/></svg>
                  <p id="coverZoneTxt">Drag &amp; drop or click to upload</p>
                  <span class="nt-upload-btn" id="coverBtn">Attach Cover Letter</span>
                  <input type="file" name="coverLetter" id="coverFile" style="display:none;" accept=".pdf,.doc,.docx" onchange="ntFileSelected('cover',this)">
                </div>
                <p style="font-size:11px;color:#94a3b8;margin-top:6px;">PDF, DOC, DOCX · Max 10 MB</p>
              </div>
            </div>
            <div class="nt-field">
              <label>Or type your cover letter here</label>
              <textarea name="coverLetterText" class="nt-textarea" rows="6" placeholder="Dear Hiring Team,&#10;&#10;I'm excited to apply for…"></textarea>
            </div>
            <div class="nt-field">
              <label>Portfolio / Work Samples URL</label>
              <input type="url" name="portfolio" class="nt-input" placeholder="https://yourportfolio.com">
            </div>
          </div>
        </div>
        <div class="nt-nav-row">
          <button type="button" class="nt-btn nt-btn-secondary" onclick="ntGoStep(1)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back
          </button>
          <button type="button" class="nt-btn nt-btn-primary" onclick="ntNext(2)">
            Next: Work Experience
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <!-- ── STEP 3: Work Experience ── -->
      <div class="nt-step-panel" id="ntStep3">
        <div class="nt-card">
          <div class="nt-card-head">
            <div class="nt-card-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
            </div>
            <div>
              <div class="nt-card-title">Work Experience</div>
              <div class="nt-card-subtitle">Your most recent positions</div>
            </div>
          </div>
          <div class="nt-card-body">
            <!-- Role 1 -->
            <div style="background:#f8fafc;border:0.5px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:16px;">
              <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;margin-bottom:14px;">Most Recent Role</div>
              <div class="nt-row">
                <div class="nt-field"><label>Job Title</label><input type="text" name="exp1_title" class="nt-input" placeholder="Senior Designer"></div>
                <div class="nt-field"><label>Company</label><input type="text" name="exp1_company" class="nt-input" placeholder="Acme Corp"></div>
              </div>
              <div class="nt-row">
                <div class="nt-field"><label>Start Date</label><input type="month" name="exp1_start" class="nt-input"></div>
                <div class="nt-field"><label>End Date</label><input type="month" name="exp1_end" class="nt-input" placeholder="Present"></div>
              </div>
              <div class="nt-field"><label>Key Responsibilities</label><textarea name="exp1_desc" class="nt-textarea" rows="3" placeholder="Led a team of 4 designers…"></textarea></div>
            </div>
            <!-- Role 2 -->
            <div style="background:#f8fafc;border:0.5px solid #e2e8f0;border-radius:10px;padding:18px;margin-bottom:20px;">
              <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;margin-bottom:14px;">Previous Role</div>
              <div class="nt-row">
                <div class="nt-field"><label>Job Title</label><input type="text" name="exp2_title" class="nt-input" placeholder="Product Designer"></div>
                <div class="nt-field"><label>Company</label><input type="text" name="exp2_company" class="nt-input" placeholder="Startup Inc"></div>
              </div>
              <div class="nt-row">
                <div class="nt-field"><label>Start Date</label><input type="month" name="exp2_start" class="nt-input"></div>
                <div class="nt-field"><label>End Date</label><input type="month" name="exp2_end" class="nt-input"></div>
              </div>
              <div class="nt-field"><label>Key Responsibilities</label><textarea name="exp2_desc" class="nt-textarea" rows="3" placeholder="Redesigned the onboarding flow…"></textarea></div>
            </div>
            <!-- Education -->
            <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:14px;">Education</div>
            <div class="nt-row">
              <div class="nt-field"><label>Degree / Certificate</label><input type="text" name="edu_degree" class="nt-input" placeholder="B.S. Computer Science"></div>
              <div class="nt-field"><label>Institution</label><input type="text" name="edu_school" class="nt-input" placeholder="University of California"></div>
            </div>
            <div class="nt-row">
              <div class="nt-field"><label>Graduation Year</label><input type="number" name="edu_year" class="nt-input" placeholder="2019" min="1970" max="2030"></div>
              <div class="nt-field"><label>GPA (optional)</label><input type="text" name="edu_gpa" class="nt-input" placeholder="3.8 / 4.0"></div>
            </div>
          </div>
        </div>
        <div class="nt-nav-row">
          <button type="button" class="nt-btn nt-btn-secondary" onclick="ntGoStep(2)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back
          </button>
          <button type="button" class="nt-btn nt-btn-primary" onclick="ntNext(3)">
            Next: Skills
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <!-- ── STEP 4: Skills & Competencies ── -->
      <div class="nt-step-panel" id="ntStep4">
        <div class="nt-card">
          <div class="nt-card-head">
            <div class="nt-card-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            </div>
            <div>
              <div class="nt-card-title">Skills &amp; Competencies</div>
              <div class="nt-card-subtitle">Rate your proficiency and select tools</div>
            </div>
          </div>
          <div class="nt-card-body">
            <div style="font-size:12px;font-weight:600;color:#475569;margin-bottom:12px;">Rate each skill (1 = beginner, 5 = expert)</div>
            <div id="ntSkillsWrap">
              <!-- skills rendered by JS -->
            </div>
            <div style="margin-top:22px;margin-bottom:16px;font-size:13px;font-weight:700;color:#0f172a;">Tools &amp; Software</div>
            <div class="nt-check-grid">
              <label class="nt-check-item"><input type="checkbox" name="tool_figma"> Figma</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_sketch"> Sketch</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_miro"> Miro</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_jira"> Jira</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_notion"> Notion</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_slack"> Slack</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_github"> GitHub</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_vscode"> VS Code</label>
              <label class="nt-check-item"><input type="checkbox" name="tool_other"> Other</label>
            </div>
            <div class="nt-row" style="margin-top:20px;">
              <div class="nt-field">
                <label>Years of Relevant Experience</label>
                <select name="yearsExp" class="nt-select">
                  <option value="">Select…</option>
                  <option>Less than 1 year</option>
                  <option>1–2 years</option>
                  <option>3–5 years</option>
                  <option>6–9 years</option>
                  <option>10+ years</option>
                </select>
              </div>
              <div class="nt-field">
                <label>Seniority Level</label>
                <select name="seniority" class="nt-select">
                  <option value="">Select…</option>
                  <option>Intern / Entry Level</option>
                  <option>Junior</option>
                  <option>Mid-Level</option>
                  <option>Senior</option>
                  <option>Lead / Staff</option>
                  <option>Principal / Director</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="nt-nav-row">
          <button type="button" class="nt-btn nt-btn-secondary" onclick="ntGoStep(3)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back
          </button>
          <button type="button" class="nt-btn nt-btn-primary" onclick="ntNext(4)">
            Next: Screening Questions
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <!-- ── STEP 5: Screening Questions ── -->
      <div class="nt-step-panel" id="ntStep5">
        <div class="nt-card">
          <div class="nt-card-head">
            <div class="nt-card-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div>
              <div class="nt-card-title">Screening Questions</div>
              <div class="nt-card-subtitle">Help us understand your fit</div>
            </div>
          </div>
          <div class="nt-card-body">
            <div class="nt-field">
              <label>Expected Salary Range</label>
              <select name="salaryExpectation" class="nt-select">
                <option value="">Select a range…</option>
                <option>Under $50,000</option>
                <option>$50,000 – $75,000</option>
                <option>$75,000 – $100,000</option>
                <option>$100,000 – $130,000</option>
                <option>$130,000 – $160,000</option>
                <option>$160,000 – $200,000</option>
                <option>Over $200,000</option>
                <option>Open to discussion</option>
              </select>
            </div>
            <div class="nt-field">
              <label>Work Location Preference</label>
              <div class="nt-radio-group" style="margin-top:8px;">
                <label class="nt-radio-item"><input type="radio" name="workLocation" value="remote"> Fully Remote</label>
                <label class="nt-radio-item"><input type="radio" name="workLocation" value="hybrid"> Hybrid (2–3 days on-site)</label>
                <label class="nt-radio-item"><input type="radio" name="workLocation" value="onsite"> Fully On-site</label>
                <label class="nt-radio-item"><input type="radio" name="workLocation" value="flexible"> Flexible / No preference</label>
              </div>
            </div>
            <div class="nt-field">
              <label>Tell us about a challenge you solved with a creative solution.</label>
              <textarea name="behavioral1" class="nt-textarea" rows="4" placeholder="Describe the situation, your approach, and the outcome…"></textarea>
            </div>
            <div class="nt-field">
              <label>Why do you want to work at Neutara Technologies?</label>
              <textarea name="behavioral2" class="nt-textarea" rows="4" placeholder="What excites you about this role and our mission…"></textarea>
            </div>
            <div class="nt-field">
              <label>How did you hear about this position?</label>
              <select name="source" class="nt-select">
                <option value="">Select…</option>
                <option>LinkedIn</option>
                <option>Indeed</option>
                <option>Company Website</option>
                <option>Referral</option>
                <option>Job Fair</option>
                <option>Twitter / X</option>
                <option>Other</option>
              </select>
            </div>
          </div>
        </div>
        <div class="nt-nav-row">
          <button type="button" class="nt-btn nt-btn-secondary" onclick="ntGoStep(4)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back
          </button>
          <button type="button" class="nt-btn nt-btn-primary" onclick="ntNext(5)">
            Next: Equal Opportunity
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <!-- ── STEP 6: EEO ── -->
      <div class="nt-step-panel" id="ntStep6">
        <div class="nt-card">
          <div class="nt-card-head">
            <div class="nt-card-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
            </div>
            <div>
              <div class="nt-card-title">Equal Opportunity</div>
              <div class="nt-card-subtitle">Voluntary — will not affect your application</div>
            </div>
          </div>
          <div class="nt-card-body">
            <div class="nt-eeo-notice">
              <strong>Voluntary Self-Identification</strong> — Neutara Technologies is an equal opportunity employer. Providing this information is completely voluntary and will not affect hiring decisions. It is used only for government reporting and compliance purposes, and will be kept confidential.
            </div>
            <div class="nt-row">
              <div class="nt-field">
                <label>Gender Identity</label>
                <select name="eeoGender" class="nt-select">
                  <option value="">Prefer not to say</option>
                  <option>Male</option>
                  <option>Female</option>
                  <option>Non-binary / Gender non-conforming</option>
                  <option>Self-describe</option>
                </select>
              </div>
              <div class="nt-field">
                <label>Race / Ethnicity</label>
                <select name="eeoRace" class="nt-select">
                  <option value="">Prefer not to say</option>
                  <option>Hispanic or Latino</option>
                  <option>White (non-Hispanic)</option>
                  <option>Black or African American</option>
                  <option>Asian</option>
                  <option>American Indian / Alaska Native</option>
                  <option>Native Hawaiian / Pacific Islander</option>
                  <option>Two or more races</option>
                </select>
              </div>
            </div>
            <div class="nt-row">
              <div class="nt-field">
                <label>Veteran Status</label>
                <select name="eeoVeteran" class="nt-select">
                  <option value="">Prefer not to say</option>
                  <option>Protected Veteran</option>
                  <option>Not a Veteran</option>
                </select>
              </div>
              <div class="nt-field">
                <label>Disability Status</label>
                <select name="eeoDisability" class="nt-select">
                  <option value="">Prefer not to say</option>
                  <option>Yes, I have a disability</option>
                  <option>No, I do not have a disability</option>
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="nt-nav-row">
          <button type="button" class="nt-btn nt-btn-secondary" onclick="ntGoStep(5)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back
          </button>
          <button type="button" class="nt-btn nt-btn-primary" onclick="ntNext(6)">
            Next: Review &amp; Submit
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
          </button>
        </div>
      </div>

      <!-- ── STEP 7: Review & Submit ── -->
      <div class="nt-step-panel" id="ntStep7">
        <div class="nt-card">
          <div class="nt-card-head">
            <div class="nt-card-icon">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            </div>
            <div>
              <div class="nt-card-title">Review &amp; Submit</div>
              <div class="nt-card-subtitle">Double-check everything before you send</div>
            </div>
          </div>
          <div class="nt-card-body">

            <!-- Review summary cards -->
            <div id="ntReviewSummary"></div>

            <!-- Agreements -->
            <div style="background:#f8fafc;border:0.5px solid #e2e8f0;border-radius:10px;padding:18px;margin-top:4px;">
              <div style="font-size:13px;font-weight:700;color:#0f172a;margin-bottom:14px;">Agreements</div>
              <div class="nt-agreement-row">
                <input type="checkbox" id="agreeTerms" name="agreeTerms" required>
                <label for="agreeTerms">I certify that the information provided in this application is true and complete. Misrepresentation may result in disqualification or termination of employment.</label>
              </div>
              <div class="nt-agreement-row">
                <input type="checkbox" id="agreePrivacy" name="agreePrivacy" required>
                <label for="agreePrivacy">I agree to Neutara Technologies' <a href="#" style="color:#0d2488;">Privacy Policy</a> and consent to the processing of my application data.</label>
              </div>
            </div>

          </div>
        </div>
        <div class="nt-nav-row">
          <button type="button" class="nt-btn nt-btn-secondary" onclick="ntGoStep(6)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
            Back
          </button>
          <button type="submit" class="nt-btn nt-btn-primary" id="ntSubmitBtn" style="padding:12px 32px;font-size:14px;">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 2L11 13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
            Submit Application
          </button>
        </div>
      </div>

    </form>

    <!-- CONFIRMATION SCREEN -->
    <div class="nt-confirm" id="ntConfirm">
      <div class="nt-confirm-icon">
        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h1>Application Submitted!</h1>
      <p>Thank you for applying to Neutara Technologies.</p>
      <p>We'll review your application and be in touch within 5–7 business days.</p>
      <div class="nt-ref-id" id="ntRefId">NTA-000000</div>
      <div class="nt-confirm-actions">
        <button onclick="sendPrompt('Write a professional follow-up email to send 5 days after submitting my application to Neutara Technologies. Keep it concise and enthusiastic.')" class="nt-btn nt-btn-outline">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0 1.1.9 2 2 2z"/><polyline points="22,6 12,13 2,6"/></svg>
          Write Follow-Up Email
        </button>
        <button onclick="sendPrompt('Create a 5-day interview preparation plan for a role at Neutara Technologies. Include research tips, common ATS interview questions, and STAR story practice.')" class="nt-btn nt-btn-primary">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
          Prep for Interview
        </button>
      </div>
    </div>

  </div>
</div>

<script>
var ntCurrent = 1;
var ntDone = {};

var ntSkills = ['UX Research','Visual Design','Prototyping','Interaction Design','User Testing','Design Systems'];

(function initSkills() {
  var wrap = document.getElementById('ntSkillsWrap');
  if (!wrap) return;
  ntSkills.forEach(function(skill, si) {
    var row = document.createElement('div');
    row.className = 'nt-skill-row';
    var name = document.createElement('span');
    name.className = 'nt-skill-name';
    name.textContent = skill;
    var dots = document.createElement('div');
    dots.className = 'nt-dots';
    for (var d = 1; d <= 5; d++) {
      (function(dot, s) {
        var el = document.createElement('div');
        el.className = 'nt-dot';
        el.dataset.skill = s;
        el.dataset.val = dot;
        el.title = dot + ' / 5';
        el.onclick = function() {
          var all = document.querySelectorAll('[data-skill="' + s + '"]');
          var v = parseInt(this.dataset.val);
          all.forEach(function(d2, i) { d2.classList.toggle('filled', i < v); });
          document.getElementById('ntSkillVal_' + s).value = v;
        };
        dots.appendChild(el);
      })(d, 'skill_' + si);
    }
    var hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'skill_' + si;
    hidden.id = 'ntSkillVal_skill_' + si;
    hidden.value = '0';
    row.appendChild(name);
    row.appendChild(dots);
    row.appendChild(hidden);
    wrap.appendChild(row);
  });
})();

function ntGoStep(n) {
  document.querySelectorAll('.nt-step-panel').forEach(function(p){ p.classList.remove('nt-active'); });
  document.getElementById('ntStep' + n).classList.add('nt-active');
  document.querySelectorAll('[id^=ntStepNav]').forEach(function(el){ el.classList.remove('active'); });
  document.getElementById('ntStepNav' + n).classList.add('active');
  ntCurrent = n;
  var pct = Math.round((n / 7) * 100);
  document.getElementById('ntProgFill').style.width = pct + '%';
  document.getElementById('ntProgText').textContent = 'Step ' + n + ' of 7';
  if (n === 7) ntBuildReview();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function ntNext(from) {
  ntDone[from] = true;
  var num = document.getElementById('ntStepNum' + from);
  if (num) {
    num.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>';
    document.getElementById('ntStepNav' + from).classList.add('done');
  }
  ntGoStep(from + 1);
}

function ntGetVal(name) {
  var el = document.querySelector('[name="' + name + '"]');
  if (!el) return '—';
  if (el.type === 'radio') {
    var checked = document.querySelector('[name="' + name + '"]:checked');
    return checked ? checked.value : '—';
  }
  return el.value || '—';
}

function ntBuildReview() {
  var s = document.getElementById('ntReviewSummary');
  if (!s) return;
  var sections = [
    { title: 'Personal Information', fields: [
      { label: 'Full Name', value: ntGetVal('firstName') + ' ' + ntGetVal('lastName') },
      { label: 'Email', value: ntGetVal('email1') },
      { label: 'Phone', value: ntGetVal('phoneWork') },
      { label: 'Location', value: ntGetVal('city') + (ntGetVal('state') !== '—' ? ', ' + ntGetVal('state') : '') },
      { label: 'Work Auth', value: ntGetVal('workAuthorization') },
      { label: 'Start Date', value: ntGetVal('startDate') }
    ]},
    { title: 'Most Recent Role', fields: [
      { label: 'Title', value: ntGetVal('exp1_title') },
      { label: 'Company', value: ntGetVal('exp1_company') }
    ]},
    { title: 'Screening', fields: [
      { label: 'Salary Range', value: ntGetVal('salaryExpectation') },
      { label: 'Work Location', value: ntGetVal('workLocation') },
      { label: 'Source', value: ntGetVal('source') }
    ]}
  ];
  var html = '';
  sections.forEach(function(sec) {
    html += '<div class="nt-review-section"><div class="nt-review-section-title">' + sec.title + '</div><div class="nt-review-grid">';
    sec.fields.forEach(function(f) {
      html += '<div class="nt-review-item"><label>' + f.label + '</label><p>' + (f.value || '—') + '</p></div>';
    });
    html += '</div></div>';
  });
  s.innerHTML = html;
}

function ntToggleUpload(type) {
  document.getElementById(type === 'resume' ? 'resumeFile' : 'coverFile').click();
}

function ntFileSelected(type, input) {
  var zone = document.getElementById(type === 'resume' ? 'resumeZone' : 'coverZone');
  var txt  = document.getElementById(type === 'resume' ? 'resumeZoneTxt' : 'coverZoneTxt');
  var btn  = document.getElementById(type === 'resume' ? 'resumeBtn' : 'coverBtn');
  if (input.files.length > 0) {
    zone.classList.add('attached');
    txt.innerHTML = '<span class="attached-name">' + input.files[0].name + '</span>';
    btn.textContent = 'Remove File';
  } else {
    zone.classList.remove('attached');
    txt.textContent = 'Drag & drop or click to upload';
    btn.textContent = type === 'resume' ? 'Attach Resume' : 'Attach Cover Letter';
  }
}

// Auto-save pulse
setInterval(function() {
  var el = document.getElementById('ntAutoSave');
  if (!el) return;
  el.style.opacity = '1';
  setTimeout(function(){ el.style.opacity = '0.4'; }, 1000);
}, 8000);

// Handle form submit
document.getElementById('submitApplicationForm') && document.getElementById('submitApplicationForm').addEventListener('submit', function(e) {
  // Let normal form submit happen — confirmation shown by thanks page
});
</script>
APPLY;

// ── CONTENT - QUESTIONNAIRE ──────────────────────────────────────────────────
$contentQ = <<<'QUEST'
<div style="max-width:720px;margin:0 auto;padding:36px 24px;">
  <div style="background:#fff;border:0.5px solid #e2e8f0;border-radius:14px;overflow:hidden;">
    <div style="background:#0d2488;padding:24px 28px;">
      <h2 style="font-size:18px;font-weight:800;color:#fff;margin-bottom:4px;">Screening Questions</h2>
      <p style="font-size:13px;color:rgba(255,255,255,0.65);">Please answer all questions honestly — this helps us match you with the right team.</p>
    </div>
    <div style="padding:28px;">
      <questionnaire>
      <div style="margin-top:24px;">
        <button type="submit" class="nt-btn nt-btn-primary">Submit Answers</button>
      </div>
    </div>
  </div>
</div>
QUEST;

// ── CONTENT - THANKS FOR YOUR SUBMISSION ─────────────────────────────────────
$contentThanks = <<<'THANKS'
<div style="max-width:580px;margin:60px auto;padding:0 24px;text-align:center;">
  <div style="width:72px;height:72px;background:#dcfce7;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
  </div>
  <h1 style="font-size:26px;font-weight:900;color:#0f172a;margin-bottom:8px;">Application Received!</h1>
  <p style="font-size:15px;color:#475569;margin-bottom:6px;">Thank you for applying to Neutara Technologies.</p>
  <p style="font-size:14px;color:#64748b;margin-bottom:24px;">We'll review your application and get back to you within 5–7 business days.</p>
  <div style="background:#e8ecfb;color:#0d2488;font:700 16px monospace;padding:12px 28px;border-radius:10px;display:inline-block;margin-bottom:28px;letter-spacing:0.06em;">
    <?php echo 'NTA-' . strtoupper(substr(md5(uniqid()), 0, 6)); ?>
  </div>
  <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
    <a href="?m=careers&p=showAll" style="display:inline-flex;align-items:center;gap:7px;padding:11px 22px;background:#0d2488;color:#fff;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      Browse More Jobs
    </a>
    <a href="?m=careers" style="display:inline-flex;align-items:center;gap:7px;padding:11px 22px;background:#fff;color:#0d2488;border:0.5px solid #0d2488;border-radius:10px;font-size:13px;font-weight:600;text-decoration:none;">
      Back to Careers
    </a>
  </div>
</div>
THANKS;

// ── FOOTER ────────────────────────────────────────────────────────────────────
$footer = <<<'FTR'
<footer style="background:#0f172a;padding:28px 32px;margin-top:48px;">
  <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
    <div style="display:flex;align-items:center;gap:10px;">
      <div style="width:30px;height:30px;background:#0d2488;border-radius:6px;display:flex;align-items:center;justify-content:center;">
        <span style="font:900 16px Georgia,serif;color:#fff;">N</span>
      </div>
      <div>
        <div style="font-size:12px;font-weight:700;color:#fff;letter-spacing:-0.01em;">neutara</div>
        <div style="font-size:9px;color:rgba(255,255,255,0.4);letter-spacing:0.1em;text-transform:uppercase;">technologies</div>
      </div>
    </div>
    <div style="font-size:12px;color:rgba(255,255,255,0.35);">
      &copy; <?php echo date('Y'); ?> Neutara Technologies. All rights reserved. &nbsp;&middot;&nbsp;
      <a href="#" style="color:rgba(255,255,255,0.5);text-decoration:none;">Privacy Policy</a> &nbsp;&middot;&nbsp;
      <a href="#" style="color:rgba(255,255,255,0.5);text-decoration:none;">Equal Opportunity Employer</a>
    </div>
  </div>
</footer>
</body>
</html>
FTR;

$settings = [
    'CSS'                                => $css,
    'Header'                             => $header,
    'Content - Main'                     => $contentMain,
    'Content - Search Results'           => $contentSearch,
    'Content - Job Details'              => $contentJobDetail,
    'Content - Candidate Registration'   => $contentCandReg,
    'Content - Candidate Profile'        => $contentCandProfile,
    'Content - Apply for Position'       => $contentApply,
    'Content - Questionnaire'            => $contentQ,
    'Content - Thanks for your Submission' => $contentThanks,
    'Footer'                             => $footer,
];

foreach ($settings as $setting => $value) {
    $ins->execute(['name' => $templateName, 'setting' => $setting, 'value' => $value]);
}
echo "✓ Neutara template installed (" . count($settings) . " sections)\n";
echo "  → Go to Settings > Career Portal > Templates to set it as active.\n";
