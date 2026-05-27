<?php /* Career Portal Template Editor — Professional UI */ ?>
<?php TemplateUtility::printHeader('Settings - Template Editor', array('modules/settings/validator.js', 'modules/settings/Settings.js', 'js/careerportal.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>

<?php
$sectionMeta = [
    'Header' => [
        'icon' => '<path d="M3 12h18M3 6h18M3 18h18"/>',
        'desc' => 'Top of every page — logo, navigation, branding.',
        'color' => '#6366f1',
        'bg'    => '#eef2ff',
    ],
    'Footer' => [
        'icon' => '<rect x="3" y="15" width="18" height="6" rx="2"/><path d="M3 9h18"/>',
        'desc' => 'Bottom of every page — copyright, social links.',
        'color' => '#8b5cf6',
        'bg'    => '#f5f3ff',
    ],
    'CSS' => [
        'icon' => '<path d="M4 6l2 14h12l2-14"/><path d="M8 10l2 4 2-4 2 4 2-4"/>',
        'desc' => 'Stylesheet applied across the entire career portal.',
        'color' => '#ec4899',
        'bg'    => '#fdf2f8',
    ],
    'Content - Main' => [
        'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/>',
        'desc' => 'Homepage shown when candidates first visit.',
        'color' => '#2563eb',
        'bg'    => '#eff6ff',
    ],
    'Content - Search Results' => [
        'icon' => '<circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>',
        'desc' => 'Search results page — insert the results table.',
        'color' => '#0891b2',
        'bg'    => '#ecfeff',
    ],
    'Content - Job Details' => [
        'icon' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6M9 13h4"/>',
        'desc' => 'Full job description page with apply link.',
        'color' => '#0d9488',
        'bg'    => '#f0fdfa',
    ],
    'Content - Apply for Position' => [
        'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>',
        'desc' => 'Application form — add form fields via insert.',
        'color' => '#059669',
        'bg'    => '#ecfdf5',
    ],
    'Content - Questionnaire' => [
        'icon' => '<path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>',
        'desc' => 'Shown when a questionnaire is attached to a job.',
        'color' => '#d97706',
        'bg'    => '#fffbeb',
    ],
    'Content - Thanks for your Submission' => [
        'icon' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'desc' => 'Confirmation page after candidate submits.',
        'color' => '#16a34a',
        'bg'    => '#f0fdf4',
    ],
    'Content - Candidate Registration' => [
        'icon' => '<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>',
        'desc' => 'Self-registration page for new candidates.',
        'color' => '#7c3aed',
        'bg'    => '#f5f3ff',
    ],
    'Content - Candidate Profile' => [
        'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'desc' => 'Candidate profile view page.',
        'color' => '#4f46e5',
        'bg'    => '#eef2ff',
    ],
];
$defaultMeta = [
    'icon' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 9h6"/>',
    'desc' => 'Edit the HTML/CSS content for this section.',
    'color' => '#64748b',
    'bg'    => '#f8fafc',
];
function getSMeta($setting, $field, $meta, $default) {
    return isset($meta[$setting][$field]) ? $meta[$setting][$field] : $default[$field];
}
?>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
@import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&display=swap');

/* ── Reset ── */
.tpe * { box-sizing: border-box; margin: 0; padding: 0; }
#main { overflow: hidden; }
#contents { padding: 0 !important; overflow: hidden; }

/* ── Wrapper ── */
.tpe {
    display: flex; flex-direction: column;
    height: calc(100vh - 118px); overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    background: #f1f5f9; color: #1e293b;
}

/* ══════════════ HERO HEADER ══════════════ */
.tpe-hero {
    display: flex; align-items: center; gap: 16px;
    padding: 18px 28px; flex-shrink: 0;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    position: relative; overflow: hidden;
}
.tpe-hero::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(circle at 85% 30%, rgba(37,99,235,0.12) 0%, transparent 50%),
                radial-gradient(circle at 15% 80%, rgba(5,150,105,0.08) 0%, transparent 50%);
}
.tpe-hero > * { position: relative; z-index: 2; }

.tpe-hero-back {
    width: 36px; height: 36px; border-radius: 10px;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.1);
    display: flex; align-items: center; justify-content: center;
    color: rgba(255,255,255,0.6); cursor: pointer; transition: all 0.2s;
    text-decoration: none; flex-shrink: 0;
}
.tpe-hero-back:hover { background: rgba(255,255,255,0.15); color: #fff; }

.tpe-hero-icon {
    width: 40px; height: 40px; border-radius: 11px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 16px rgba(37,99,235,0.35); flex-shrink: 0;
}

.tpe-hero-text h1 {
    font: 800 17px/1.2 'Inter', sans-serif; color: #fff;
    letter-spacing: -0.03em;
}
.tpe-hero-text p {
    font: 400 12px/1.4 'Inter', sans-serif; color: rgba(255,255,255,0.45);
    margin-top: 2px;
}

.tpe-hero-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font: 600 11px 'Inter', sans-serif; padding: 5px 12px; border-radius: 50px;
    letter-spacing: 0.02em; margin-left: 4px;
}
.tpe-hero-badge-builtin {
    background: rgba(99,102,241,0.15); color: #a5b4fc;
    border: 1px solid rgba(99,102,241,0.25);
}
.tpe-hero-badge-custom {
    background: rgba(34,197,94,0.15); color: #86efac;
    border: 1px solid rgba(34,197,94,0.25);
}

.tpe-hero-actions { display: flex; gap: 8px; margin-left: auto; align-items: center; }

.tpe-btn {
    display: inline-flex; align-items: center; gap: 6px;
    font: 600 12px 'Inter', sans-serif; padding: 8px 16px; border-radius: 9px;
    border: none; cursor: pointer; transition: all 0.2s;
    text-decoration: none; white-space: nowrap;
}
.tpe-btn-ghost {
    background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.7);
    border: 1px solid rgba(255,255,255,0.1);
}
.tpe-btn-ghost:hover { background: rgba(255,255,255,0.15); color: #fff; }
.tpe-btn-primary {
    background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff;
    box-shadow: 0 2px 8px rgba(37,99,235,0.3);
}
.tpe-btn-primary:hover { box-shadow: 0 4px 14px rgba(37,99,235,0.4); transform: translateY(-1px); }
.tpe-btn-success {
    background: linear-gradient(135deg, #16a34a, #15803d); color: #fff;
    box-shadow: 0 2px 8px rgba(22,163,74,0.3);
}
.tpe-btn-success:hover { box-shadow: 0 4px 14px rgba(22,163,74,0.4); transform: translateY(-1px); }

/* ══════════════ BODY LAYOUT ══════════════ */
.tpe-body {
    display: flex; flex: 1; overflow: hidden; min-height: 0;
}

/* ══════════════ SIDEBAR ══════════════ */
.tpe-sidebar {
    width: 230px; flex-shrink: 0; background: #fff;
    border-right: 1px solid #e2e8f0;
    display: flex; flex-direction: column; overflow: hidden;
}

.tpe-sidebar-head {
    padding: 16px 18px 12px; flex-shrink: 0;
    border-bottom: 1px solid #f1f5f9;
}
.tpe-sidebar-title {
    font: 700 11px 'Inter', sans-serif; text-transform: uppercase;
    letter-spacing: 0.08em; color: #94a3b8;
}
.tpe-sidebar-count {
    font: 600 11px 'Inter', sans-serif; color: #94a3b8;
    background: #f1f5f9; padding: 1px 7px; border-radius: 99px;
    margin-left: 6px;
}

.tpe-sidebar-list {
    flex: 1; overflow-y: auto; padding: 6px 8px 12px;
}

.tpe-nav-btn {
    display: flex; align-items: center; gap: 10px;
    width: 100%; text-align: left;
    padding: 9px 12px; margin-bottom: 2px;
    background: none; border: none; border-radius: 9px;
    font: 500 12.5px/1.3 'Inter', sans-serif; color: #475569;
    cursor: pointer; transition: all 0.15s;
}
.tpe-nav-btn:hover { background: #f8fafc; color: #1e293b; }
.tpe-nav-btn.active {
    background: #eff6ff; color: #2563eb; font-weight: 700;
}

.tpe-nav-icon {
    width: 28px; height: 28px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; transition: all 0.15s;
}
.tpe-nav-btn.active .tpe-nav-icon { box-shadow: 0 2px 6px rgba(0,0,0,0.08); }

.tpe-nav-dot {
    width: 6px; height: 6px; border-radius: 50%;
    margin-left: auto; flex-shrink: 0;
}
.tpe-nav-dot-on  { background: #22c55e; box-shadow: 0 0 6px rgba(34,197,94,0.4); }
.tpe-nav-dot-off { background: #e2e8f0; }

/* ══════════════ EDITOR COLUMN ══════════════ */
.tpe-editor {
    flex: 1; display: flex; flex-direction: column;
    overflow: hidden; min-width: 0; position: relative;
    background: #f8fafc;
}

/* Section panels — only active visible */
.tpe-panel { display: none; flex-direction: column; flex: 1; overflow: hidden; height: 100%; }
.tpe-panel.visible { display: flex; }

/* Section header inside editor */
.tpe-panel-head {
    padding: 14px 22px 12px; flex-shrink: 0;
    border-bottom: 1px solid #e2e8f0; background: #fff;
    display: flex; align-items: center; gap: 12px;
}
.tpe-panel-icon {
    width: 34px; height: 34px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.tpe-panel-title { font: 700 14px 'Inter', sans-serif; color: #0f172a; }
.tpe-panel-desc { font: 400 12px 'Inter', sans-serif; color: #94a3b8; margin-top: 1px; }

/* Insert toolbar */
.tpe-toolbar {
    display: flex; align-items: center; gap: 6px;
    padding: 8px 22px; background: #fff;
    border-bottom: 1px solid #f1f5f9;
    flex-shrink: 0; flex-wrap: wrap; min-height: 40px;
}
.tpe-toolbar-lbl {
    font: 600 10px 'Inter', sans-serif; text-transform: uppercase;
    letter-spacing: 0.06em; color: #94a3b8; margin-right: 4px;
}
.tpe-ibtn {
    font: 500 11px 'JetBrains Mono', monospace; padding: 4px 10px;
    border-radius: 6px; background: #f1f5f9; border: 1px solid #e2e8f0;
    color: #475569; cursor: pointer; transition: all 0.15s;
}
.tpe-ibtn:hover { background: #e2e8f0; color: #1e293b; border-color: #cbd5e1; }
.tpe-isel {
    font: 500 11px 'Inter', sans-serif; padding: 4px 8px;
    border: 1px solid #e2e8f0; border-radius: 6px;
    color: #475569; background: #fff; outline: none; cursor: pointer;
}
.tpe-char-badge {
    margin-left: auto; font: 500 11px 'JetBrains Mono', monospace;
    color: #94a3b8; background: #f1f5f9; padding: 3px 10px;
    border-radius: 6px;
}

/* Code textarea */
textarea.tpe-code {
    flex: 1; width: 100%; min-height: 0; resize: none;
    border: none; outline: none;
    font: 400 13px/1.8 'JetBrains Mono', 'Fira Code', 'Cascadia Code', monospace;
    color: #1e293b; background: #fafbfc;
    padding: 16px 22px; box-sizing: border-box;
    tab-size: 2;
}
textarea.tpe-code:focus { background: #fff; }

/* Line number gutter effect */
textarea.tpe-code::selection { background: rgba(37,99,235,0.15); }

/* ══════════════ PREVIEW PANE ══════════════ */
.tpe-preview {
    width: 420px; flex-shrink: 0; background: #fff;
    border-left: 1px solid #e2e8f0;
    display: flex; flex-direction: column; overflow: hidden;
}

.tpe-preview-bar {
    display: flex; align-items: center; gap: 10px;
    padding: 12px 16px; flex-shrink: 0;
    border-bottom: 1px solid #f1f5f9; background: #fafbfc;
}
.tpe-preview-dots { display: flex; gap: 5px; }
.tpe-preview-dot { width: 10px; height: 10px; border-radius: 50%; }
.tpe-preview-title { font: 700 12px 'Inter', sans-serif; color: #475569; }
.tpe-preview-actions { margin-left: auto; display: flex; gap: 6px; align-items: center; }

.tpe-preview-btn {
    background: none; border: 1px solid #e2e8f0; border-radius: 6px;
    padding: 4px 8px; cursor: pointer; color: #94a3b8;
    display: flex; align-items: center; gap: 4px;
    font: 500 10px 'Inter', sans-serif; transition: all 0.15s;
}
.tpe-preview-btn:hover { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }

.tpe-preview-frame { flex: 1; width: 100%; border: none; min-height: 0; background: #fff; }

/* ══════════════ KEYBOARD HINT ══════════════ */
.tpe-hint {
    position: absolute; bottom: 12px; right: 14px;
    display: flex; align-items: center; gap: 6px;
    font: 500 11px 'Inter', sans-serif; color: #94a3b8;
    background: rgba(255,255,255,0.92); backdrop-filter: blur(8px);
    padding: 5px 12px; border-radius: 8px;
    border: 1px solid #e2e8f0; pointer-events: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.tpe-kbd {
    font: 600 10px 'Inter', sans-serif; background: #f1f5f9;
    padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0;
    color: #64748b;
}

/* ══════════════ SCROLLBAR ══════════════ */
.tpe-sidebar-list::-webkit-scrollbar,
textarea.tpe-code::-webkit-scrollbar { width: 5px; }
.tpe-sidebar-list::-webkit-scrollbar-track,
textarea.tpe-code::-webkit-scrollbar-track { background: transparent; }
.tpe-sidebar-list::-webkit-scrollbar-thumb,
textarea.tpe-code::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }
.tpe-sidebar-list::-webkit-scrollbar-thumb:hover,
textarea.tpe-code::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

/* ══════════════ RESPONSIVE ══════════════ */
@media (max-width: 1100px) {
    .tpe-preview { width: 320px; }
    .tpe-sidebar { width: 200px; }
}
@media (max-width: 860px) {
    .tpe-preview { display: none; }
}
</style>

<div id="main">
  <?php TemplateUtility::printQuickSearch(); ?>
  <div id="contents">

    <form id="editorForm"
          action="<?php echo(CATSUtility::getIndexName()); ?>?m=settings&amp;a=careerPortalTemplateEdit"
          method="post">
      <input type="hidden" name="postback"    value="postback" />
      <input type="hidden" name="templateName" value="<?php $this->_($this->templateName); ?>" />
      <input type="hidden" name="continueEdit" id="continueEdit" value="0" />

      <div class="tpe">

        <!-- ══════════ HERO HEADER ══════════ -->
        <div class="tpe-hero">
          <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=careerPortalSettings"
             class="tpe-hero-back" title="Back to Settings">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <polyline points="15 18 9 12 15 6"/>
            </svg>
          </a>

          <div class="tpe-hero-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                 stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
          </div>

          <div class="tpe-hero-text">
            <h1>Template Editor</h1>
            <p>Customize HTML, CSS, and content sections</p>
          </div>

          <span class="tpe-hero-badge <?php echo (!empty($this->isDefaultTemplate) ? 'tpe-hero-badge-builtin' : 'tpe-hero-badge-custom'); ?>">
            <svg width="10" height="10" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
              <?php if (!empty($this->isDefaultTemplate)): ?>
              <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
              <?php else: ?>
              <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/>
              <?php endif; ?>
            </svg>
            <?php $this->_($this->templateName); ?>
          </span>

          <div class="tpe-hero-actions">
            <button type="button" class="tpe-btn tpe-btn-ghost" onclick="openFullPreview()">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                <polyline points="15 3 21 3 21 9"/>
                <line x1="10" y1="14" x2="21" y2="3"/>
              </svg>
              Preview
            </button>
            <button type="submit" class="tpe-btn tpe-btn-primary"
                    onclick="document.getElementById('continueEdit').value='1';">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                <polyline points="17 21 17 13 7 13 7 21"/>
              </svg>
              Save &amp; Continue
            </button>
            <button type="submit" class="tpe-btn tpe-btn-success"
                    onclick="document.getElementById('continueEdit').value='0';">
              <svg width="13" height="13" viewBox="0 0 24 24" fill="none"
                   stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                <polyline points="20 6 9 17 4 12"/>
              </svg>
              Save &amp; Exit
            </button>
          </div>
        </div>

        <!-- ══════════ BODY ══════════ -->
        <div class="tpe-body">

          <!-- ── SIDEBAR ── -->
          <div class="tpe-sidebar">
            <div class="tpe-sidebar-head">
              <span class="tpe-sidebar-title">Sections</span>
              <span class="tpe-sidebar-count"><?php echo count($this->template); ?></span>
            </div>
            <div class="tpe-sidebar-list">
              <?php $idx = 0; foreach ($this->template as $setting => $value):
                  $color = getSMeta($setting, 'color', $sectionMeta, $defaultMeta);
                  $bg    = getSMeta($setting, 'bg', $sectionMeta, $defaultMeta);
                  $icon  = getSMeta($setting, 'icon', $sectionMeta, $defaultMeta);
                  $hasContent = strlen(trim($value)) > 0;
              ?>
              <button type="button"
                      class="tpe-nav-btn<?php echo ($idx === 0 ? ' active' : ''); ?>"
                      id="navBtn<?php echo $idx; ?>"
                      onclick="switchSection(<?php echo $idx; ?>)">
                <span class="tpe-nav-icon" style="background:<?php echo $bg; ?>;">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                       stroke="<?php echo $color; ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <?php echo $icon; ?>
                  </svg>
                </span>
                <?php
                  $label = $setting;
                  $label = str_replace('Content - ', '', $label);
                  echo htmlspecialchars($label);
                ?>
                <span class="tpe-nav-dot <?php echo ($hasContent ? 'tpe-nav-dot-on' : 'tpe-nav-dot-off'); ?>"
                      id="dot<?php echo $idx; ?>"></span>
              </button>
              <?php $idx++; endforeach; ?>
            </div>
          </div>

          <!-- ── EDITOR COLUMN ── -->
          <div class="tpe-editor">
            <?php
            $idx = 0;
            foreach ($this->template as $setting => $value):
                $color = getSMeta($setting, 'color', $sectionMeta, $defaultMeta);
                $bg    = getSMeta($setting, 'bg', $sectionMeta, $defaultMeta);
                $icon  = getSMeta($setting, 'icon', $sectionMeta, $defaultMeta);
                $desc  = getSMeta($setting, 'desc', $sectionMeta, $defaultMeta);
            ?>
            <div class="tpe-panel<?php echo ($idx === 0 ? ' visible' : ''); ?>"
                 id="section<?php echo $idx; ?>">

              <!-- Section header -->
              <div class="tpe-panel-head">
                <span class="tpe-panel-icon" style="background:<?php echo $bg; ?>;">
                  <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                       stroke="<?php echo $color; ?>" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <?php echo $icon; ?>
                  </svg>
                </span>
                <div>
                  <div class="tpe-panel-title"><?php $this->_($setting); ?></div>
                  <div class="tpe-panel-desc"><?php echo htmlspecialchars($desc); ?></div>
                </div>
              </div>

              <!-- Insert toolbar -->
              <div class="tpe-toolbar">
                <span class="tpe-toolbar-lbl">Insert</span>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;siteName&gt;')">Site Name</button>

                <?php if ($setting === 'Header'): ?>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;a-LinkMain&gt;Main Page&lt;/a&gt;\n&lt;a-ListAll&gt;List All Jobs&lt;/a&gt;')">Menu Links</button>
                <?php endif; ?>

                <?php if ($setting === 'Content - Main'): ?>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;numberOfOpenPositions&gt;')"># Open Positions</button>
                <?php endif; ?>

                <?php if ($setting === 'Content - Apply for Position' || $setting === 'Content - Candidate Profile'): ?>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;title&gt;')">Job Title</button>
                <select class="tpe-isel" onchange="if(this.value){insertAt(<?php echo $idx; ?>,this.value);this.value='';}">
                  <option value="">+ Form Field</option>
                  <option value="&lt;input-firstName&gt;">First Name *</option>
                  <option value="&lt;input-lastName&gt;">Last Name *</option>
                  <option value="&lt;input-email&gt;">Email *</option>
                  <option value="&lt;input-address&gt;">Address</option>
                  <option value="&lt;input-city&gt;">City</option>
                  <option value="&lt;input-state&gt;">State</option>
                  <option value="&lt;input-zip&gt;">Zip Code</option>
                  <option value="&lt;input-phone&gt;">Phone (Work)</option>
                  <option value="&lt;input-phone-home&gt;">Phone (Home)</option>
                  <option value="&lt;input-email2&gt;">Email 2</option>
                  <option value="&lt;input-source&gt;">Source</option>
                  <option value="&lt;input-employer&gt;">Current Employer</option>
                  <option value="&lt;input-resumeUpload&gt;">Resume Upload</option>
                  <option value="&lt;input-keySkills&gt;">Key Skills</option>
                  <option value="&lt;input-extraNotes&gt;">Extra Notes</option>
                  <?php if ($this->eeoEnabled == 1): ?>
                    <?php if ($this->EEOSettingsRS['genderTracking']   == 1): ?><option value="&lt;input-eeo-gender&gt;">EEO: Gender</option><?php endif; ?>
                    <?php if ($this->EEOSettingsRS['ethnicTracking']   == 1): ?><option value="&lt;input-eeo-race&gt;">EEO: Ethnicity</option><?php endif; ?>
                    <?php if ($this->EEOSettingsRS['veteranTracking']  == 1): ?><option value="&lt;input-eeo-veteran&gt;">EEO: Veteran</option><?php endif; ?>
                    <?php if ($this->EEOSettingsRS['disabilityTracking']== 1): ?><option value="&lt;input-eeo-disability&gt;">EEO: Disability</option><?php endif; ?>
                  <?php endif; ?>
                  <?php foreach ($this->extraFieldsForCandidates as $ef): ?>
                  <option value="&lt;input-extraField-<?php echo urlencode($ef['fieldName']); ?>&gt;"><?php $this->_($ef['fieldName']); ?></option>
                  <?php endforeach; ?>
                  <option value="&lt;submit value=&quot;Apply for Position&quot;&gt;">Submit Button</option>
                </select>
                <?php endif; ?>

                <?php if ($setting === 'Content - Job Details'): ?>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;a-applyToJob&gt;Apply to Job&lt;/a&gt;')">Apply Link</button>
                <select class="tpe-isel" onchange="if(this.value){insertAt(<?php echo $idx; ?>,this.value);this.value='';}">
                  <option value="">+ Job Field</option>
                  <option value="&lt;title&gt;">Job Title</option>
                  <option value="&lt;type&gt;">Type</option>
                  <option value="&lt;city&gt;">City</option>
                  <option value="&lt;state&gt;">State</option>
                  <option value="&lt;openings&gt;">Openings</option>
                  <option value="&lt;salary&gt;">Salary</option>
                  <option value="&lt;description&gt;">Description</option>
                  <option value="&lt;companyName&gt;">Company Name</option>
                  <option value="&lt;recruiter&gt;">Recruiter</option>
                  <option value="&lt;created&gt;">Date Created</option>
                  <?php foreach ($this->extraFieldsForJobOrders as $ef): ?>
                  <option value="&lt;extraField-<?php echo urlencode($ef['fieldName']); ?>&gt;"><?php $this->_($ef['fieldName']); ?></option>
                  <?php endforeach; ?>
                </select>
                <?php endif; ?>

                <?php if ($setting === 'Content - Search Results' || $setting === 'Body - Search Results'): ?>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;numberOfSearchResults&gt;')"># Results</button>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;searchResultsTable&gt;')">Results Table</button>
                <?php endif; ?>

                <?php if ($setting === 'Content - Thanks for your Submission'): ?>
                <button type="button" class="tpe-ibtn"
                        onclick="insertAt(<?php echo $idx; ?>,'&lt;a-jobDetails&gt;Job Details&lt;/a&gt;')">Job Details Link</button>
                <?php endif; ?>

                <?php if ($setting === 'CSS'): ?>
                <select class="tpe-isel" onchange="if(this.value){insertAt(<?php echo $idx; ?>,this.value);this.value='';}">
                  <option value="">+ CSS Snippet</option>
                  <option value="body {\n\n}">body</option>
                  <option value=".inputBoxName {\n\n}">.inputBoxName</option>
                  <option value=".inputBoxArea {\n\n}">.inputBoxArea</option>
                  <option value=".inputBoxNormal {\n\n}">.inputBoxNormal</option>
                  <option value="table.sortable {\n  border-collapse: collapse;\n}">Results Table</option>
                </select>
                <?php endif; ?>

                <span class="tpe-char-badge" id="cnt<?php echo $idx; ?>">0</span>
              </div>

              <!-- Code textarea -->
              <textarea class="tpe-code"
                        name="<?php $this->_(md5($setting)); ?>"
                        id="ta<?php echo $idx; ?>"
                        spellcheck="false"
                        oninput="onInput(<?php echo $idx; ?>)"><?php $this->_($value); ?></textarea>
            </div>
            <?php $idx++; endforeach; ?>

            <span class="tpe-hint">
              <span class="tpe-kbd">Ctrl</span> + <span class="tpe-kbd">S</span>
              <span style="margin-left:2px;">Save &amp; Continue</span>
            </span>
          </div>

          <!-- ── PREVIEW PANE ── -->
          <div class="tpe-preview">
            <div class="tpe-preview-bar">
              <div class="tpe-preview-dots">
                <div class="tpe-preview-dot" style="background:#ef4444;"></div>
                <div class="tpe-preview-dot" style="background:#f59e0b;"></div>
                <div class="tpe-preview-dot" style="background:#22c55e;"></div>
              </div>
              <span class="tpe-preview-title">Live Preview</span>
              <div class="tpe-preview-actions">
                <button type="button" class="tpe-preview-btn" onclick="refreshPreview()" title="Refresh">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <polyline points="23 4 23 10 17 10"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                  </svg>
                  Refresh
                </button>
                <button type="button" class="tpe-preview-btn" onclick="openFullPreview()" title="Open in new tab">
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                       stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                    <polyline points="15 3 21 3 21 9"/>
                    <line x1="10" y1="14" x2="21" y2="3"/>
                  </svg>
                  Open
                </button>
              </div>
            </div>
            <iframe id="livePreview" class="tpe-preview-frame"
                    src="<?php echo CATSUtility::getIndexName(); ?>?m=careers&templateName=<?php echo urlencode($this->templateName); ?>">
            </iframe>
          </div>

        </div><!-- /.tpe-body -->
      </div><!-- /.tpe -->
    </form>

  </div><!-- #contents -->
</div><!-- #main -->

<script>
var totalSections = <?php echo count($this->template); ?>;
var tplName       = '<?php echo addslashes($this->templateName); ?>';
var indexURL      = '<?php echo CATSUtility::getIndexName(); ?>';

function switchSection(idx) {
    for (var i = 0; i < totalSections; i++) {
        var panel = document.getElementById('section' + i);
        var btn   = document.getElementById('navBtn' + i);
        if (panel) panel.classList.remove('visible');
        if (btn)   btn.classList.remove('active');
    }
    var p = document.getElementById('section' + idx);
    var b = document.getElementById('navBtn' + idx);
    if (p) p.classList.add('visible');
    if (b) b.classList.add('active');
    updateCount(idx);
}

function insertAt(idx, raw) {
    var ta = document.getElementById('ta' + idx);
    if (!ta) return;
    var val = raw.replace(/\\n/g, '\n');
    var s = ta.selectionStart, e = ta.selectionEnd;
    ta.value = ta.value.slice(0, s) + val + ta.value.slice(e);
    ta.selectionStart = ta.selectionEnd = s + val.length;
    ta.focus();
    onInput(idx);
}

function onInput(idx) {
    updateCount(idx);
    var ta  = document.getElementById('ta'  + idx);
    var dot = document.getElementById('dot' + idx);
    if (ta && dot) {
        dot.className = 'tpe-nav-dot ' + (ta.value.trim().length > 0 ? 'tpe-nav-dot-on' : 'tpe-nav-dot-off');
    }
}

function updateCount(idx) {
    var ta  = document.getElementById('ta'  + idx);
    var cnt = document.getElementById('cnt' + idx);
    if (ta && cnt) cnt.textContent = ta.value.length.toLocaleString() + ' chars';
}

function refreshPreview() {
    var f = document.getElementById('livePreview');
    f.src = f.src;
}

function openFullPreview() {
    window.open(indexURL + '?m=careers&templateName=' + encodeURIComponent(tplName), '_blank');
}

document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('continueEdit').value = '1';
        document.getElementById('editorForm').submit();
    }
});

for (var i = 0; i < totalSections; i++) updateCount(i);
</script>

<?php TemplateUtility::printFooter(); ?>
