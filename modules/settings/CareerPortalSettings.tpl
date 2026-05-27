<?php /* Career Portal Settings — Premium Professional UI */ ?>
<?php TemplateUtility::printHeader('Settings - Career Portal', array('modules/settings/validator.js', 'modules/settings/Settings.js', 'js/careerportal.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>
<?php $careerPortalEnabledId = 0; ?>

<?php
// Template metadata for rich cards
$tplMeta = [
    'Blank Page' => ['desc'=>'Empty canvas for full custom design','color'=>'#94a3b8','gradient'=>'linear-gradient(135deg,#f1f5f9,#e2e8f0)','icon'=>'blank'],
    'CATS 2.0' => ['desc'=>'Classic legacy layout with sidebar','color'=>'#6b7280','gradient'=>'linear-gradient(135deg,#f3f4f6,#e5e7eb)','icon'=>'classic'],
    'Modern Blue' => ['desc'=>'Blue header, hero wave, stats & feature cards','color'=>'#2563eb','gradient'=>'linear-gradient(135deg,#dbeafe,#bfdbfe)','icon'=>'modern'],
    'Premium' => ['desc'=>'Full-featured with illustrations & testimonials','color'=>'#1e40af','gradient'=>'linear-gradient(135deg,#c7d2fe,#a5b4fc)','icon'=>'premium'],
    'Starter' => ['desc'=>'Clean minimal design, easy to customize','color'=>'#3b82f6','gradient'=>'linear-gradient(135deg,#eff6ff,#dbeafe)','icon'=>'starter'],
    'Corporate' => ['desc'=>'Dark professional theme with emerald accents','color'=>'#059669','gradient'=>'linear-gradient(135deg,#d1fae5,#a7f3d0)','icon'=>'corporate'],
    'Neutara'   => ['desc'=>'7-step ATS portal with Neutara Technologies branding','color'=>'#0d2488','gradient'=>'linear-gradient(135deg,#e8ecfb,#c7d2fe)','icon'=>'neutara'],
];
function getTplMeta($name, $field, $default, $meta) {
    return isset($meta[$name][$field]) ? $meta[$name][$field] : $default;
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

/* ===== RESET & BASE ===== */
.cps * { box-sizing: border-box; }
.cps {
    max-width: 1140px; margin: 0 auto; padding: 32px 40px 80px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    color: #1e293b;
}

/* ===== PAGE HEADER ===== */
.cps-hero {
    display: flex; align-items: center; gap: 20px;
    padding: 28px 32px; margin-bottom: 28px;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 20px; position: relative; overflow: hidden;
}
.cps-hero::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(circle at 90% 20%, rgba(37,99,235,0.15) 0%, transparent 50%),
                radial-gradient(circle at 10% 80%, rgba(5,150,105,0.1) 0%, transparent 50%);
}
.cps-hero > * { position: relative; z-index: 2; }
.cps-hero-icon {
    width: 52px; height: 52px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border-radius: 14px; display: flex; align-items: center; justify-content: center;
    box-shadow: 0 8px 24px rgba(37,99,235,0.35); flex-shrink: 0;
}
.cps-hero h1 { font: 800 22px/1.2 'Inter', sans-serif; color: #fff; letter-spacing: -0.03em; margin: 0 0 4px; }
.cps-hero p { font: 400 13px/1.5 'Inter', sans-serif; color: rgba(255,255,255,0.5); margin: 0; }
.cps-hero-right { margin-left: auto; display: flex; align-items: center; gap: 12px; }

/* Status badge */
.cps-badge { display: inline-flex; align-items: center; gap: 6px; font: 700 12px 'Inter', sans-serif; padding: 6px 14px; border-radius: 50px; letter-spacing: 0.02em; }
.cps-badge-active { background: rgba(22,163,74,0.15); color: #34d399; }
.cps-badge-inactive { background: rgba(239,68,68,0.15); color: #f87171; }
.cps-badge-dot { width: 7px; height: 7px; border-radius: 50%; }
.cps-badge-active .cps-badge-dot { background: #34d399; box-shadow: 0 0 8px rgba(52,211,153,0.5); animation: cps-pulse 2s infinite; }
.cps-badge-inactive .cps-badge-dot { background: #f87171; }
@keyframes cps-pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }

/* ===== SECTION CARDS ===== */
.cps-section {
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 16px; margin-bottom: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 12px rgba(0,0,0,0.02);
    overflow: hidden; transition: box-shadow 0.3s;
}
.cps-section:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
.cps-section-head {
    padding: 20px 28px; display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid #f1f5f9; background: #fafbfc;
}
.cps-section-head-left { display: flex; align-items: center; gap: 14px; }
.cps-section-icon {
    width: 40px; height: 40px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.cps-section-title { font: 700 15px 'Inter', sans-serif; color: #0f172a; margin: 0 0 2px; }
.cps-section-subtitle { font: 400 12px 'Inter', sans-serif; color: #94a3b8; margin: 0; }
.cps-section-body { padding: 24px 28px; }

/* ===== TOGGLE ROWS ===== */
.cps-toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; border-bottom: 1px solid #f1f5f9; }
.cps-toggle-row:last-child { border-bottom: none; }
.cps-toggle-info h4 { font: 600 14px 'Inter', sans-serif; color: #1e293b; margin: 0 0 3px; }
.cps-toggle-info p { font: 400 12px 'Inter', sans-serif; color: #94a3b8; margin: 0; }
.cps-switch { position: relative; width: 48px; height: 26px; flex-shrink: 0; }
.cps-switch input { opacity: 0; width: 0; height: 0; }
.cps-switch-track {
    position: absolute; inset: 0; background: #cbd5e1; border-radius: 99px;
    cursor: pointer; transition: background 0.25s;
}
.cps-switch-track::before {
    content: ''; position: absolute; width: 20px; height: 20px; background: #fff;
    border-radius: 50%; top: 3px; left: 3px; transition: transform 0.25s cubic-bezier(0.34,1.56,0.64,1);
    box-shadow: 0 1px 4px rgba(0,0,0,0.15);
}
.cps-switch input:checked + .cps-switch-track { background: #2563eb; }
.cps-switch input:checked + .cps-switch-track::before { transform: translateX(22px); }

/* ===== URL BOX ===== */
.cps-url-wrap { margin-top: 20px; }
.cps-url-label { font: 600 13px 'Inter', sans-serif; color: #475569; margin-bottom: 8px; }
.cps-url-box {
    display: flex; align-items: center; gap: 10px;
    background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px;
    padding: 10px 16px;
}
.cps-url-box code { font: 500 13px 'JetBrains Mono', 'Fira Code', monospace; color: #334155; flex: 1; word-break: break-all; }
.cps-url-copy {
    flex-shrink: 0; background: #fff; border: 1.5px solid #e2e8f0;
    color: #475569; font: 600 12px 'Inter', sans-serif; padding: 7px 16px;
    border-radius: 8px; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 6px;
}
.cps-url-copy:hover { background: #f1f5f9; border-color: #cbd5e1; }
.cps-url-visit {
    flex-shrink: 0; background: #2563eb; color: #fff; font: 700 12px 'Inter', sans-serif;
    padding: 7px 16px; border-radius: 8px; text-decoration: none; transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 6px;
}
.cps-url-visit:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.3); }

/* ===== BUTTONS ===== */
.cps-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font: 600 13px 'Inter', sans-serif; padding: 9px 20px; border-radius: 10px;
    border: none; cursor: pointer; transition: all 0.2s; text-decoration: none;
}
.cps-btn-primary { background: #2563eb; color: #fff; }
.cps-btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37,99,235,0.25); }
.cps-btn-secondary { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
.cps-btn-secondary:hover { background: #e2e8f0; }
.cps-btn-danger { background: #fff; color: #dc2626; border: 1.5px solid #fca5a5; }
.cps-btn-danger:hover { background: #fef2f2; }
.cps-btn-save {
    background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff;
    font: 700 14px 'Inter', sans-serif; padding: 12px 32px; border-radius: 12px;
    border: none; cursor: pointer; transition: all 0.25s;
    box-shadow: 0 4px 16px rgba(37,99,235,0.3);
}
.cps-btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,99,235,0.35); }

/* ===== NEUTARA HERO LOGO ===== */
.cps-hero-logo-n {
    font: 900 26px/1 Georgia,serif;
    color: #fff;
}
.cps-hero-divider {
    width: 1px; height: 36px;
    background: rgba(255,255,255,0.15);
    margin: 0 4px;
}

/* ===== TEMPLATE SECTION ===== */
.cps-tpl-header {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;
}
.cps-tpl-tabs { display: flex; gap: 4px; background: #f1f5f9; border-radius: 10px; padding: 3px; }
.cps-tpl-tab {
    font: 600 13px 'Inter', sans-serif; padding: 8px 18px; border-radius: 8px;
    color: #64748b; cursor: pointer; transition: all 0.2s; border: none; background: transparent;
}
.cps-tpl-tab.active { background: #fff; color: #0f172a; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
.cps-tpl-tab:hover:not(.active) { color: #334155; }

.cps-tpl-count { font: 600 12px 'Inter', sans-serif; color: #94a3b8; margin-left: 6px; background: #f1f5f9; padding: 2px 8px; border-radius: 99px; }

/* Template grid */
.cps-tpl-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 16px; margin-bottom: 16px;
}

/* Template card */
.cps-tpl-card {
    position: relative; background: #fff; border: 2px solid #e2e8f0;
    border-radius: 14px; overflow: hidden; cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.cps-tpl-card:hover { border-color: #93c5fd; box-shadow: 0 8px 24px rgba(37,99,235,0.1); transform: translateY(-3px); }
.cps-tpl-card.cps-active { border-color: #22c55e; }
.cps-tpl-card.cps-active::after {
    content: ''; position: absolute; top: 10px; right: 10px; width: 24px; height: 24px;
    background: #22c55e; border-radius: 50%; z-index: 10;
    display: flex; align-items: center; justify-content: center;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='3' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='20 6 9 17 4 12'/%3E%3C/svg%3E");
    background-repeat: no-repeat; background-position: center;
}

/* Card thumbnail */
.cps-tpl-thumb {
    height: 140px; display: flex; align-items: center; justify-content: center;
    position: relative; overflow: hidden;
}
.cps-tpl-thumb-wireframe {
    width: 80%; height: 85%; border-radius: 6px; overflow: hidden;
    background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.08); position: relative;
}
.cps-wire-header { height: 16px; border-radius: 3px 3px 0 0; }
.cps-wire-hero { height: 28px; margin: 4px; border-radius: 3px; opacity: 0.3; }
.cps-wire-content { display: flex; gap: 3px; margin: 4px; }
.cps-wire-block { flex: 1; height: 16px; border-radius: 3px; background: #e2e8f0; }
.cps-wire-table { margin: 4px; height: 24px; border-radius: 3px; background: #f1f5f9; }
.cps-wire-footer { position: absolute; bottom: 0; left: 0; right: 0; height: 10px; }

/* Hover overlay */
.cps-tpl-overlay {
    position: absolute; inset: 0; background: rgba(15,23,42,0.85);
    backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);
    display: none; flex-direction: column; align-items: center; justify-content: center; gap: 8px;
    padding: 16px; z-index: 5;
}
.cps-tpl-card:hover .cps-tpl-overlay { display: flex; }
.cps-tpl-overlay-btn {
    display: inline-flex; align-items: center; gap: 8px;
    font: 600 12px 'Inter', sans-serif; padding: 8px 20px; border-radius: 8px;
    border: none; cursor: pointer; transition: all 0.15s; min-width: 160px; justify-content: center;
}
.cps-tpl-overlay-btn.white { background: #fff; color: #1e293b; }
.cps-tpl-overlay-btn.white:hover { background: #f1f5f9; }
.cps-tpl-overlay-btn.green { background: #22c55e; color: #fff; }
.cps-tpl-overlay-btn.green:hover { background: #16a34a; }
.cps-tpl-overlay-btn.blue { background: #2563eb; color: #fff; }
.cps-tpl-overlay-btn.blue:hover { background: #1d4ed8; }
.cps-tpl-overlay-btn.red { background: #ef4444; color: #fff; }
.cps-tpl-overlay-btn.red:hover { background: #dc2626; }

/* Card body */
.cps-tpl-body { padding: 14px 16px; border-top: 1px solid #f1f5f9; }
.cps-tpl-name { font: 700 14px 'Inter', sans-serif; color: #0f172a; margin: 0 0 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.cps-tpl-desc { font: 400 12px/1.4 'Inter', sans-serif; color: #94a3b8; margin: 0 0 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
.cps-tpl-badges { display: flex; gap: 6px; flex-wrap: wrap; }
.cps-tpl-badge {
    font: 700 10px 'Inter', sans-serif; padding: 3px 8px; border-radius: 99px;
    letter-spacing: 0.03em; text-transform: uppercase;
}
.cps-tpl-badge-active { background: #dcfce7; color: #16a34a; }
.cps-tpl-badge-custom { background: #eff6ff; color: #2563eb; }
.cps-tpl-badge-builtin { background: #f1f5f9; color: #64748b; }

/* New template inline form */
.cps-new-form {
    background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px;
    padding: 20px; margin-bottom: 16px; display: none;
}
.cps-new-form label { font: 600 12px 'Inter', sans-serif; color: #64748b; display: block; margin-bottom: 6px; }
.cps-new-form input[type=text] {
    width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px;
    font: 400 14px 'Inter', sans-serif; outline: none; margin-bottom: 12px; transition: all 0.2s;
}
.cps-new-form input[type=text]:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
.cps-new-form-actions { display: flex; gap: 8px; }

/* Empty state */
.cps-empty {
    text-align: center; padding: 40px; background: #fafbfc;
    border: 2px dashed #e2e8f0; border-radius: 14px; color: #94a3b8;
}
.cps-empty p { font: 400 13px 'Inter', sans-serif; margin: 12px 0; }

/* ===== QUESTIONNAIRE TABLE ===== */
.cps-q-table { width: 100%; border-collapse: collapse; }
.cps-q-table th {
    font: 700 11px 'Inter', sans-serif; letter-spacing: 0.06em; text-transform: uppercase;
    color: #94a3b8; padding: 12px 16px; text-align: left; border-bottom: 2px solid #f1f5f9;
}
.cps-q-table td { font: 500 13px 'Inter', sans-serif; color: #475569; padding: 14px 16px; border-bottom: 1px solid #f8fafc; vertical-align: middle; }
.cps-q-table tr:last-child td { border-bottom: none; }
.cps-q-table tr:hover td { background: #fafbfc; }
.cps-q-table td a { color: #2563eb; font-weight: 700; text-decoration: none; }
.cps-q-table td a:hover { text-decoration: underline; }

/* ===== MODALS ===== */
.cps-modal-backdrop {
    display: none; position: fixed; inset: 0; background: rgba(15,23,42,0.6);
    backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
    z-index: 99999; align-items: center; justify-content: center;
}
.cps-modal {
    background: #fff; border-radius: 20px; padding: 32px; width: 440px;
    box-shadow: 0 24px 64px rgba(0,0,0,0.2); animation: cps-modal-in 0.2s ease-out;
}
@keyframes cps-modal-in { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
.cps-modal h2 { font: 800 18px 'Inter', sans-serif; color: #0f172a; margin: 0 0 6px; }
.cps-modal .modal-desc { font: 400 13px 'Inter', sans-serif; color: #94a3b8; margin: 0 0 20px; }
.cps-modal label { font: 600 12px 'Inter', sans-serif; color: #64748b; display: block; margin-bottom: 6px; }
.cps-modal input[type=text] {
    width: 100%; padding: 10px 14px; border: 1.5px solid #e2e8f0; border-radius: 10px;
    font: 400 14px 'Inter', sans-serif; outline: none; margin-bottom: 20px; transition: all 0.2s;
}
.cps-modal input[type=text]:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
.cps-modal-actions { display: flex; gap: 8px; justify-content: flex-end; }

/* ===== PREVIEW PANEL ===== */
.cps-preview-panel {
    display: none; margin-top: 20px; background: #f8fafc;
    border: 1.5px solid #e2e8f0; border-radius: 14px; overflow: hidden;
}
.cps-preview-bar {
    display: flex; align-items: center; justify-content: space-between;
    padding: 12px 20px; background: #fff; border-bottom: 1px solid #e2e8f0;
}
.cps-preview-bar span { font: 700 13px 'Inter', sans-serif; color: #0f172a; }
.cps-preview-frame { width: 100%; height: 480px; border: none; background: #fff; }

/* ===== RESPONSIVE ===== */
@media (max-width: 768px) {
    .cps { padding: 20px 16px 60px; }
    .cps-hero { flex-direction: column; align-items: flex-start; gap: 14px; padding: 24px; }
    .cps-hero-right { margin-left: 0; }
    .cps-tpl-grid { grid-template-columns: 1fr 1fr; }
    .cps-section-body { padding: 20px; }
    .cps-section-head { padding: 16px 20px; flex-wrap: wrap; gap: 12px; }
}
@media (max-width: 480px) {
    .cps-tpl-grid { grid-template-columns: 1fr; }
}
</style>

<div id="main">
  <?php TemplateUtility::printQuickSearch(); ?>
  <div id="contents">
    <div class="cps">

      <!-- ====== PAGE HERO ====== -->
      <div class="cps-hero">

        <!-- Neutara Logo -->
        <div style="display:flex;align-items:center;gap:14px;flex-shrink:0;">
          <!-- N lettermark with sparkle -->
          <div style="position:relative;width:54px;height:54px;flex-shrink:0;">
            <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
              <!-- Rounded square bg -->
              <rect width="54" height="54" rx="14" fill="url(#nHeroBg)"/>
              <!-- Bold N letterform -->
              <path d="M13 40V14h4.8l14.4 17.6V14H37v26h-4.8L17.8 22.4V40H13z" fill="#ffffff"/>
              <!-- Sparkle top-right -->
              <g transform="translate(36,8)">
                <path d="M4 0 L4.8 3.2 L8 4 L4.8 4.8 L4 8 L3.2 4.8 L0 4 L3.2 3.2 Z" fill="#60a5fa"/>
              </g>
              <defs>
                <linearGradient id="nHeroBg" x1="0" y1="0" x2="54" y2="54" gradientUnits="userSpaceOnUse">
                  <stop offset="0%" stop-color="#1e3a8a"/>
                  <stop offset="100%" stop-color="#1d4ed8"/>
                </linearGradient>
              </defs>
            </svg>
          </div>
          <!-- Wordmark -->
          <div>
            <div style="font:800 20px/1 'Inter',sans-serif;color:#fff;letter-spacing:-0.01em;">
              neutara
              <span style="font-weight:300;opacity:0.6;font-size:13px;letter-spacing:0.12em;text-transform:uppercase;display:block;margin-top:2px;">TECHNOLOGIES</span>
            </div>
          </div>
          <!-- Divider -->
          <div style="width:1px;height:36px;background:rgba(255,255,255,0.15);margin:0 4px;"></div>
        </div>

        <!-- Title block -->
        <div>
          <h1>Career Portal</h1>
          <p>Configure your public career website, manage templates, and set up questionnaires</p>
        </div>

        <div class="cps-hero-right">
          <?php if ($this->careerPortalSettingsRS['enabled'] == '1'): ?>
          <span class="cps-badge cps-badge-active"><span class="cps-badge-dot"></span> Portal Active</span>
          <?php else: ?>
          <span class="cps-badge cps-badge-inactive"><span class="cps-badge-dot"></span> Portal Disabled</span>
          <?php endif; ?>
        </div>
      </div>

      <!-- ====== GENERAL SETTINGS ====== -->
      <div class="cps-section">
        <div class="cps-section-head">
          <div class="cps-section-head-left">
            <div class="cps-section-icon" style="background:#eff6ff;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06A1.65 1.65 0 004.68 15a1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06A1.65 1.65 0 009 4.68a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06A1.65 1.65 0 0019.4 9a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z"/></svg>
            </div>
            <div>
              <div class="cps-section-title">General Settings</div>
              <div class="cps-section-subtitle">Control portal visibility and features</div>
            </div>
          </div>
        </div>
        <div class="cps-section-body">
          <form name="careerPortalSettingsForm" id="careerPortalSettingsForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=careerPortalSettings" method="post">
            <input type="hidden" name="postback" value="postback" />
            <input type="hidden" name="configured" value="1" />

            <div class="cps-toggle-row">
              <div class="cps-toggle-info">
                <h4>Enable Public Career Portal</h4>
                <p>Make your career site visible to the public so candidates can browse and apply</p>
              </div>
              <label class="cps-switch">
                <input type="checkbox" name="enabled" id="enabledToggle" <?php if ($this->careerPortalSettingsRS['enabled'] == '1'): ?>checked<?php endif; ?> onchange="this.form.submit()">
                <span class="cps-switch-track"></span>
              </label>
            </div>

            <div id="careerPortalEnabled<?php echo ++$careerPortalEnabledId; ?>">
              <div class="cps-toggle-row">
                <div class="cps-toggle-info">
                  <h4>Allow Browsing of All Job Orders</h4>
                  <p>Visitors can see and browse all publicly listed job openings</p>
                </div>
                <label class="cps-switch">
                  <input type="checkbox" name="allowBrowse" <?php if ($this->careerPortalSettingsRS['allowBrowse'] == '1'): ?>checked<?php endif; ?>>
                  <span class="cps-switch-track"></span>
                </label>
              </div>

              <div class="cps-toggle-row">
                <div class="cps-toggle-info">
                  <h4>Candidate Registration</h4>
                  <p>Allow candidates to register and update their contact information</p>
                </div>
                <label class="cps-switch">
                  <input type="checkbox" name="candidateRegistration" <?php if ($this->careerPortalSettingsRS['candidateRegistration'] == '1'): ?>checked<?php endif; ?>>
                  <span class="cps-switch-track"></span>
                </label>
              </div>

              <div class="cps-toggle-row">
                <div class="cps-toggle-info">
                  <h4>Show Company Column</h4>
                  <p>Display the company name column in the job listings table</p>
                </div>
                <label class="cps-switch">
                  <input type="checkbox" name="showCompany" <?php if ($this->careerPortalSettingsRS['showCompany'] == '1'): ?>checked<?php endif; ?>>
                  <span class="cps-switch-track"></span>
                </label>
              </div>

              <div class="cps-toggle-row">
                <div class="cps-toggle-info">
                  <h4>Show Department Column</h4>
                  <p>Display the department column in the job listings table</p>
                </div>
                <label class="cps-switch">
                  <input type="checkbox" name="showDepartment" <?php if ($this->careerPortalSettingsRS['showDepartment'] == '1'): ?>checked<?php endif; ?>>
                  <span class="cps-switch-track"></span>
                </label>
              </div>

              <?php eval(Hooks::get('CAREER_PORTAL_SUBMIT_XML_FEEDS')); ?>

              <!-- Portal URL -->
              <div class="cps-url-wrap">
                <div class="cps-url-label">Career Portal URL</div>
                <div class="cps-url-box">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                  <code id="portalUrlText"><?php $this->_($this->careerPortalURL); ?></code>
                  <button type="button" class="cps-url-copy" onclick="copyPortalUrl()">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                    Copy
                  </button>
                  <a class="cps-url-visit" href="<?php $this->_($this->careerPortalURL); ?>" target="_blank">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    Visit Site
                  </a>
                </div>
              </div>

              <div style="margin-top:24px;">
                <button type="submit" class="cps-btn-save">Save Settings</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- ====== TEMPLATES ====== -->
      <div class="cps-section" id="careerPortalEnabled<?php echo ++$careerPortalEnabledId; ?>">
        <div class="cps-section-head">
          <div class="cps-section-head-left">
            <div class="cps-section-icon" style="background:#f0fdf4;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            </div>
            <div>
              <div class="cps-section-title">Career Site Templates</div>
              <div class="cps-section-subtitle">Choose and customize the design of your career portal</div>
            </div>
          </div>
          <button type="button" class="cps-btn cps-btn-primary" onclick="toggleNewForm()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            New Template
          </button>
        </div>
        <div class="cps-section-body">

          <!-- New template form -->
          <div class="cps-new-form" id="newTemplateForm">
            <form name="careerPortalNewForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onCareerPortalTweak&amp;p=new" method="post" onsubmit="if(detectInputIsValid(document.getElementById('templateName').value)){alert('This template name is already in use.');return false;}">
              <label>New Template Name</label>
              <input name="newName" id="templateName" type="text" placeholder="e.g. My Custom Template" />
              <div class="cps-new-form-actions">
                <button type="submit" class="cps-btn cps-btn-primary">Create Template</button>
                <button type="button" class="cps-btn cps-btn-secondary" onclick="toggleNewForm()">Cancel</button>
              </div>
            </form>
          </div>

          <!-- Hidden forms -->
          <form name="setAsActiveForm" id="setAsActiveForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onCareerPortalTweak&amp;p=setAsActive" method="post" style="display:none;">
            <input name="activeName" id="activeName" type="hidden" value="" />
          </form>

          <!-- Tab navigation -->
          <div class="cps-tpl-header">
            <div class="cps-tpl-tabs">
              <button type="button" class="cps-tpl-tab active" onclick="switchTab('builtin',this)">
                Built-in<span class="cps-tpl-count"><?php echo count($this->careerPortalTemplateNames); ?></span>
              </button>
              <button type="button" class="cps-tpl-tab" onclick="switchTab('custom',this)">
                Custom<span class="cps-tpl-count"><?php echo count($this->careerPortalTemplateCustomNames); ?></span>
              </button>
            </div>
          </div>

          <!-- Built-in Templates Grid -->
          <div id="tab-builtin">
            <div class="cps-tpl-grid">
              <?php foreach ($this->careerPortalTemplateNames as $name => $data): ?>
              <?php
                $tplName = $data['careerPortalName'];
                $isActive = ($tplName == $this->careerPortalSettingsRS['activeBoard']);
                $color = getTplMeta($tplName, 'color', '#94a3b8', $tplMeta);
                $gradient = getTplMeta($tplName, 'gradient', 'linear-gradient(135deg,#f1f5f9,#e2e8f0)', $tplMeta);
                $desc = getTplMeta($tplName, 'desc', 'Default template', $tplMeta);
              ?>
              <div class="cps-tpl-card<?php if($isActive): ?> cps-active<?php endif; ?>">
                <div class="cps-tpl-thumb" style="background:<?php echo $gradient; ?>;">
                  <!-- Mini wireframe preview -->
                  <div class="cps-tpl-thumb-wireframe">
                    <div class="cps-wire-header" style="background:<?php echo $color; ?>;">
                      <?php if ($tplName === 'Neutara'): ?>
                      <span style="color:#fff;font-size:5px;font-weight:900;letter-spacing:0.02em;opacity:0.9;padding:0 3px;">N</span>
                      <?php endif; ?>
                    </div>
                    <div class="cps-wire-hero" style="background:<?php echo $tplName==='Neutara'?'linear-gradient(135deg,#dbeafe,#eff6ff)':$color; ?>;<?php if($tplName==='Neutara'): ?>opacity:0.8;<?php endif; ?>"></div>
                    <div class="cps-wire-content">
                      <div class="cps-wire-block" style="<?php if($tplName==='Neutara'):?>background:#0d2488;opacity:0.15;<?php endif;?>"></div>
                      <div class="cps-wire-block"></div><div class="cps-wire-block"></div>
                    </div>
                    <div class="cps-wire-table"></div>
                    <div class="cps-wire-footer" style="background:<?php echo $tplName==='Neutara'?'#0f172a':$color; ?>; opacity:0.85;"></div>
                  </div>
                  <!-- Overlay -->
                  <div class="cps-tpl-overlay">
                    <button type="button" class="cps-tpl-overlay-btn white" onclick="previewTpl('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      Preview
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn blue" onclick="editTpl('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      Edit Template
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn green" onclick="doSetActive('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                      Set as Active
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn white" onclick="openDuplicateModal('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                      Duplicate
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn red" onclick="openDeleteModal('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                      Delete
                    </button>
                  </div>
                </div>
                <div class="cps-tpl-body">
                  <div class="cps-tpl-name"><?php $this->_($tplName); ?></div>
                  <div class="cps-tpl-desc"><?php echo htmlspecialchars($desc); ?></div>
                  <div class="cps-tpl-badges">
                    <?php if($isActive): ?><span class="cps-tpl-badge cps-tpl-badge-active">Active</span><?php endif; ?>
                    <span class="cps-tpl-badge cps-tpl-badge-builtin">Built-in</span>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Custom Templates Grid -->
          <div id="tab-custom" style="display:none;">
            <?php if (empty($this->careerPortalTemplateCustomNames)): ?>
            <div class="cps-empty">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
              <p>No custom templates yet. Create one from scratch or duplicate a built-in template.</p>
              <button type="button" class="cps-btn cps-btn-primary" onclick="toggleNewForm()">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                Create Your First Template
              </button>
            </div>
            <?php else: ?>
            <div class="cps-tpl-grid">
              <?php foreach ($this->careerPortalTemplateCustomNames as $name => $data): ?>
              <?php
                $tplName = $data['careerPortalName'];
                $isActive = ($tplName == $this->careerPortalSettingsRS['activeBoard']);
                $color = getTplMeta($tplName, 'color', '#6366f1', $tplMeta);
                $gradient = getTplMeta($tplName, 'gradient', 'linear-gradient(135deg,#eef2ff,#e0e7ff)', $tplMeta);
                $desc = getTplMeta($tplName, 'desc', 'Custom template', $tplMeta);
              ?>
              <div class="cps-tpl-card<?php if($isActive): ?> cps-active<?php endif; ?>">
                <div class="cps-tpl-thumb" style="background:<?php echo $gradient; ?>;">
                  <div class="cps-tpl-thumb-wireframe">
                    <div class="cps-wire-header" style="background:<?php echo $color; ?>;"></div>
                    <div class="cps-wire-hero" style="background:<?php echo $color; ?>;"></div>
                    <div class="cps-wire-content">
                      <div class="cps-wire-block"></div><div class="cps-wire-block"></div><div class="cps-wire-block"></div>
                    </div>
                    <div class="cps-wire-table"></div>
                    <div class="cps-wire-footer" style="background:<?php echo $color; ?>; opacity:0.7;"></div>
                  </div>
                  <div class="cps-tpl-overlay">
                    <button type="button" class="cps-tpl-overlay-btn white" onclick="previewTpl('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                      Preview
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn blue" onclick="editTpl('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                      Edit Template
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn green" onclick="doSetActive('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                      Set as Active
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn white" onclick="openDuplicateModal('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                      Duplicate
                    </button>
                    <button type="button" class="cps-tpl-overlay-btn red" onclick="openDeleteModal('<?php echo htmlspecialchars($tplName, ENT_QUOTES); ?>')">
                      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                      Delete
                    </button>
                  </div>
                </div>
                <div class="cps-tpl-body">
                  <div class="cps-tpl-name"><?php $this->_($tplName); ?></div>
                  <div class="cps-tpl-desc"><?php echo htmlspecialchars($desc); ?></div>
                  <div class="cps-tpl-badges">
                    <?php if($isActive): ?><span class="cps-tpl-badge cps-tpl-badge-active">Active</span><?php endif; ?>
                    <span class="cps-tpl-badge cps-tpl-badge-custom">Custom</span>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>

          <!-- Inline preview panel -->
          <div class="cps-preview-panel" id="previewPanel">
            <div class="cps-preview-bar">
              <span id="previewTitle">Preview</span>
              <div style="display:flex;gap:8px;">
                <button type="button" class="cps-btn cps-btn-secondary" onclick="openPreviewNewTab()" style="padding:6px 14px;font-size:12px;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                  Open in New Tab
                </button>
                <button type="button" class="cps-btn cps-btn-secondary" onclick="closePreview()" style="padding:6px 14px;font-size:12px;">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                  Close
                </button>
              </div>
            </div>
            <iframe id="previewFrame" class="cps-preview-frame"></iframe>
          </div>

          <!-- Duplicate Modal -->
          <div class="cps-modal-backdrop" id="duplicateModal">
            <div class="cps-modal">
              <h2>Duplicate Template</h2>
              <p class="modal-desc">Create a copy of <strong id="dupSourceName"></strong></p>
              <form name="duplicateForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onCareerPortalTweak&amp;p=duplicate" method="post" onsubmit="if(detectInputIsValid(document.getElementById('duplicateName').value)){alert('This template name is already in use.');return false;}">
                <input name="origName" id="origName" type="hidden" value="" />
                <label>New Template Name</label>
                <input name="duplicateName" id="duplicateName" type="text" placeholder="e.g. My Copy" />
                <div class="cps-modal-actions">
                  <button type="button" class="cps-btn cps-btn-secondary" onclick="closeDuplicateModal()">Cancel</button>
                  <button type="submit" class="cps-btn cps-btn-primary">Duplicate</button>
                </div>
              </form>
            </div>
          </div>

          <!-- Delete Modal -->
          <div class="cps-modal-backdrop" id="deleteModal">
            <div class="cps-modal">
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
                <div style="width:40px;height:40px;background:#fef2f2;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </div>
                <h2 style="color:#dc2626;">Delete Template</h2>
              </div>
              <p class="modal-desc">Are you sure you want to permanently delete <strong id="delTargetName"></strong>? This cannot be undone.</p>
              <form name="deleteForm" action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=onCareerPortalTweak&amp;p=delete" method="post">
                <input name="delName" id="delName" type="hidden" value="" />
                <div class="cps-modal-actions">
                  <button type="button" class="cps-btn cps-btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                  <button type="submit" class="cps-btn cps-btn-danger">Delete Template</button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>

      <!-- ====== QUESTIONNAIRES ====== -->
      <div class="cps-section" id="careerPortalEnabled<?php echo ++$careerPortalEnabledId; ?>">
        <div class="cps-section-head">
          <div class="cps-section-head-left">
            <div class="cps-section-icon" style="background:#fef9c3;">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ca8a04" stroke-width="2" stroke-linecap="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
            </div>
            <div>
              <div class="cps-section-title">Questionnaires</div>
              <div class="cps-section-subtitle">Screen candidates with custom questions before they apply</div>
            </div>
          </div>
          <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalQuestionnaire" class="cps-btn cps-btn-primary" style="text-decoration:none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Questionnaire
          </a>
        </div>
        <div class="cps-section-body" style="padding:0;">
          <form method="post" action="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalQuestionnaireUpdate" name="questionnaireUpdateForm">
            <?php if (isset($this->questionnaires) && !empty($this->questionnaires)): ?>
            <table class="cps-q-table">
              <thead>
                <tr><th>Title</th><th>Description</th><th>Status</th><th style="text-align:center;">Remove</th></tr>
              </thead>
              <tbody>
              <?php for ($i = 0; $i < count($this->questionnaires); $i++): ?>
              <?php $q = $this->questionnaires[$i]; ?>
                <tr>
                  <td><a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalQuestionnaire&questionnaireID=<?php echo $q['questionnaireID']; ?>"><?php echo htmlspecialchars($q['title']); ?></a></td>
                  <td><?php echo htmlspecialchars($q['description']); ?></td>
                  <td>
                    <?php if ($q['isActive']): ?>
                    <span class="cps-badge cps-badge-active" style="font-size:11px;padding:3px 10px;"><span class="cps-badge-dot"></span>Active</span>
                    <?php else: ?>
                    <span class="cps-badge cps-badge-inactive" style="font-size:11px;padding:3px 10px;"><span class="cps-badge-dot"></span>Inactive</span>
                    <?php endif; ?>
                  </td>
                  <td style="text-align:center;"><input type="checkbox" name="removeQuestionnaire<?php echo $i; ?>" value="yes" /></td>
                </tr>
              <?php endfor; ?>
              </tbody>
            </table>
            <div style="padding:16px 24px;display:flex;justify-content:flex-end;border-top:1px solid #f1f5f9;">
              <button type="submit" class="cps-btn cps-btn-primary">Update Questionnaires</button>
            </div>
            <?php else: ?>
            <div class="cps-empty" style="margin:24px;border:none;background:transparent;">
              <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
              <p>No questionnaires yet. Click <strong>Add Questionnaire</strong> to create one.</p>
            </div>
            <?php endif; ?>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
var indexURL = '<?php echo(CATSUtility::getIndexName()); ?>';
var currentPreviewName = '';

function detectInputIsValid(name) {
  <?php foreach ($this->careerPortalTemplateNames as $name => $data): ?>
  if (name.toLowerCase() == '<?php echo addslashes($data['careerPortalName']); ?>'.toLowerCase()) return true;
  <?php endforeach; ?>
  <?php foreach ($this->careerPortalTemplateCustomNames as $name => $data): ?>
  if (name.toLowerCase() == '<?php echo addslashes($data['careerPortalName']); ?>'.toLowerCase()) return true;
  <?php endforeach; ?>
  return false;
}

// Tab switching
function switchTab(tab, btn) {
  document.getElementById('tab-builtin').style.display = tab === 'builtin' ? '' : 'none';
  document.getElementById('tab-custom').style.display = tab === 'custom' ? '' : 'none';
  document.querySelectorAll('.cps-tpl-tab').forEach(function(t) { t.classList.remove('active'); });
  btn.classList.add('active');
}

// Preview
function previewTpl(name) {
  currentPreviewName = name;
  var panel = document.getElementById('previewPanel');
  var frame = document.getElementById('previewFrame');
  var title = document.getElementById('previewTitle');
  title.textContent = 'Preview: ' + name;
  frame.src = indexURL + '?m=careers&templateName=' + encodeURIComponent(name);
  panel.style.display = 'block';
  panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

function openPreviewNewTab() {
  if (currentPreviewName) {
    window.open(indexURL + '?m=careers&templateName=' + encodeURIComponent(currentPreviewName), '_blank');
  }
}

function closePreview() {
  document.getElementById('previewPanel').style.display = 'none';
  document.getElementById('previewFrame').src = '';
}

function editTpl(name) {
  window.location.href = indexURL + '?m=settings&a=careerPortalTemplateEdit&templateName=' + encodeURIComponent(name);
}

function doSetActive(name) {
  document.getElementById('activeName').value = name;
  document.getElementById('setAsActiveForm').submit();
}

function openDuplicateModal(name) {
  document.getElementById('origName').value = name;
  document.getElementById('dupSourceName').textContent = name;
  document.getElementById('duplicateName').value = name + ' Copy';
  document.getElementById('duplicateModal').style.display = 'flex';
  setTimeout(function() { document.getElementById('duplicateName').focus(); }, 100);
}

function closeDuplicateModal() {
  document.getElementById('duplicateModal').style.display = 'none';
}

function openDeleteModal(name) {
  document.getElementById('delName').value = name;
  document.getElementById('delTargetName').textContent = '"' + name + '"';
  document.getElementById('deleteModal').style.display = 'flex';
}

function closeDeleteModal() {
  document.getElementById('deleteModal').style.display = 'none';
}

function toggleNewForm() {
  var f = document.getElementById('newTemplateForm');
  f.style.display = f.style.display === 'block' ? 'none' : 'block';
  if (f.style.display === 'block') { f.querySelector('input[type=text]').focus(); }
}

function copyPortalUrl() {
  var url = document.getElementById('portalUrlText').textContent;
  navigator.clipboard.writeText(url).then(function() {
    var btn = document.querySelector('.cps-url-copy');
    var orig = btn.innerHTML;
    btn.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg> Copied!';
    btn.style.background = '#f0fdf4';
    btn.style.borderColor = '#bbf7d0';
    btn.style.color = '#16a34a';
    setTimeout(function(){ btn.innerHTML = orig; btn.style.background = ''; btn.style.borderColor = ''; btn.style.color = ''; }, 2000);
  });
}

function setVisibility(vis) {
  for (var i = 1; i < 50; i++) {
    var el = document.getElementById('careerPortalEnabled' + i);
    if (el) el.style.display = vis; else break;
  }
}

setVisibility('<?php echo $this->careerPortalSettingsRS['enabled'] == '1' ? '' : 'none'; ?>');

// Close modals on backdrop click
document.getElementById('duplicateModal').addEventListener('click', function(e){ if(e.target===this) closeDuplicateModal(); });
document.getElementById('deleteModal').addEventListener('click', function(e){ if(e.target===this) closeDeleteModal(); });

// Keyboard: Escape to close modals
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') { closeDuplicateModal(); closeDeleteModal(); }
});
</script>

<?php TemplateUtility::printFooter(); ?>
