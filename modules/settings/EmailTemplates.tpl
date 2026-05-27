<?php /* $Id: EmailTemplates.tpl 1929 2007-02-22 06:18:30Z will $ */ ?>
<?php TemplateUtility::printHeader('Settings', array()); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>

<style>
/* ============================================================
   Email Templates – Modern SaaS UI
   ============================================================ */
#main { background: #f4f6fb; min-height: 100vh; }

.et-page-wrapper {
    padding: 28px 32px;
    max-width: 1380px;
    margin: 0 auto;
}

/* ── Page header ── */
.et-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.et-page-header-left { display: flex; align-items: center; gap: 16px; }
.et-page-icon {
    width: 48px; height: 48px; border-radius: 12px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 4px 12px rgba(79,70,229,.3);
}
.et-page-icon svg { width: 24px; height: 24px; fill: none; stroke: #fff; stroke-width: 2; }
.et-page-title h1 { font-size: 22px; font-weight: 700; color: #111827; margin: 0 0 2px; }
.et-page-title p  { font-size: 13px; color: #6b7280; margin: 0; }
.et-breadcrumb { font-size: 13px; color: #9ca3af; }
.et-breadcrumb a { color: #6b7280; text-decoration: none; }
.et-breadcrumb a:hover { color: #4f46e5; }
.et-breadcrumb span { color: #4f46e5; }

/* ── Two-column layout ── */
.et-layout { display: flex; gap: 24px; align-items: flex-start; }

/* ── Left panel ── */
.et-left-panel {
    width: 300px; flex-shrink: 0;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    overflow: hidden;
}
.et-left-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f3f4f6;
    display: flex; align-items: center; justify-content: space-between;
}
.et-left-header h3 { font-size: 14px; font-weight: 600; color: #111827; margin: 0; }
.et-add-btn {
    display: inline-flex; align-items: center; gap: 6px;
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; font-size: 12px; font-weight: 600;
    padding: 7px 14px; border-radius: 8px;
    border: none; cursor: pointer; text-decoration: none;
    transition: opacity .15s;
    white-space: nowrap;
}
.et-add-btn:hover { opacity: .88; color: #fff; text-decoration: none; }

.et-search-wrap { padding: 12px 16px; border-bottom: 1px solid #f3f4f6; }
.et-search-wrap input {
    width: 100%; padding: 8px 12px 8px 34px;
    border: 1px solid #e5e7eb; border-radius: 8px;
    font-size: 13px; background: #f9fafb url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' fill='none' stroke='%239ca3af' stroke-width='2' viewBox='0 0 24 24'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center;
    color: #374151; outline: none; box-sizing: border-box;
    transition: border-color .15s;
}
.et-search-wrap input:focus { border-color: #4f46e5; background-color: #fff; }

.et-template-list { padding: 8px 0; }

.et-template-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px; cursor: pointer;
    border-left: 3px solid transparent;
    transition: background .15s, border-color .15s;
    text-decoration: none;
}
.et-template-item:hover { background: #f9fafb; }
.et-template-item.active {
    background: #eef2ff;
    border-left-color: #4f46e5;
}
.et-template-icon {
    width: 36px; height: 36px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; font-size: 16px;
}
.et-template-icon.green  { background: #d1fae5; }
.et-template-icon.orange { background: #ffedd5; }
.et-template-icon.red    { background: #fee2e2; }
.et-template-icon.blue   { background: #dbeafe; }
.et-template-icon.yellow { background: #fef9c3; }
.et-template-icon.purple { background: #ede9fe; }

.et-template-info { flex: 1; min-width: 0; }
.et-template-name { font-size: 13px; font-weight: 600; color: #111827; margin: 0 0 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.et-template-desc { font-size: 11px; color: #9ca3af; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

.et-badge-active   { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #d1fae5; color: #065f46; white-space: nowrap; }
.et-badge-disabled { font-size: 10px; font-weight: 600; padding: 2px 8px; border-radius: 20px; background: #f3f4f6; color: #6b7280; white-space: nowrap; }

.et-left-footer {
    padding: 10px 16px;
    border-top: 1px solid #f3f4f6;
    font-size: 11px; color: #9ca3af;
}

/* ── Right panel ── */
.et-right-panel { flex: 1; min-width: 0; }

.et-editor-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    overflow: hidden;
    display: none; /* hidden by default; JS shows active */
}
.et-editor-card.active { display: block; }

.et-editor-header {
    padding: 20px 24px 0;
    border-bottom: 1px solid #f3f4f6;
}
.et-editor-header-top {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 16px;
}
.et-editor-header-top h2 { font-size: 17px; font-weight: 700; color: #111827; margin: 0; }

/* Toggle switch */
.et-toggle-wrap { display: flex; align-items: center; gap: 10px; }
.et-toggle-label { font-size: 13px; color: #374151; font-weight: 500; }
.et-toggle { position: relative; display: inline-block; width: 42px; height: 24px; }
.et-toggle input { opacity: 0; width: 0; height: 0; }
.et-toggle-slider {
    position: absolute; cursor: pointer; inset: 0;
    background: #d1d5db; border-radius: 24px;
    transition: background .2s;
}
.et-toggle-slider:before {
    content: ''; position: absolute;
    width: 18px; height: 18px; border-radius: 50%;
    background: #fff; left: 3px; bottom: 3px;
    transition: transform .2s;
    box-shadow: 0 1px 3px rgba(0,0,0,.2);
}
.et-toggle input:checked + .et-toggle-slider { background: #4f46e5; }
.et-toggle input:checked + .et-toggle-slider:before { transform: translateX(18px); }

/* Tabs */
.et-tabs { display: flex; gap: 0; }
.et-tab {
    padding: 10px 20px; font-size: 13px; font-weight: 500;
    color: #6b7280; cursor: pointer; border-bottom: 2px solid transparent;
    transition: color .15s, border-color .15s; white-space: nowrap;
    user-select: none;
}
.et-tab:hover { color: #4f46e5; }
.et-tab.active { color: #4f46e5; border-bottom-color: #4f46e5; }

/* Tab content */
.et-tab-content { display: none; }
.et-tab-content.active { display: block; }

/* Form body */
.et-form-body { padding: 24px; }

.et-form-row { display: flex; gap: 16px; margin-bottom: 16px; }
.et-form-group { flex: 1; }
.et-form-group label {
    display: block; font-size: 12px; font-weight: 600;
    color: #374151; margin-bottom: 6px;
}
.et-form-group label .req { color: #ef4444; margin-left: 2px; }
.et-form-group input[type=text] {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    font-size: 13px; color: #111827; background: #fff;
    outline: none; box-sizing: border-box;
    transition: border-color .15s, box-shadow .15s;
}
.et-form-group input[type=text]:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
}

/* Rich text toolbar */
.et-rte-wrap {
    border: 1.5px solid #e5e7eb; border-radius: 10px;
    overflow: hidden;
    transition: border-color .15s, box-shadow .15s;
}
.et-rte-wrap:focus-within {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79,70,229,.12);
}
.et-rte-toolbar {
    padding: 8px 12px; background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    display: flex; align-items: center; gap: 4px; flex-wrap: wrap;
}
.et-rte-btn {
    width: 28px; height: 28px; border-radius: 6px;
    border: none; background: transparent; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    color: #374151; font-size: 13px; font-weight: 700;
    transition: background .12s, color .12s;
}
.et-rte-btn:hover { background: #e5e7eb; }
.et-rte-btn.active { background: #eef2ff; color: #4f46e5; }
.et-rte-sep { width: 1px; height: 20px; background: #e5e7eb; margin: 0 4px; }

.et-rte-body {
    width: 100%; min-height: 220px;
    padding: 14px 16px;
    font-size: 13px; line-height: 1.7; color: #374151;
    border: none; resize: vertical; outline: none;
    background: #fff; box-sizing: border-box;
    font-family: inherit;
}

/* Word/char count */
.et-rte-footer {
    padding: 6px 14px; background: #fafafa;
    border-top: 1px solid #f0f0f0;
    font-size: 11px; color: #9ca3af;
    text-align: right;
}

/* Template settings */
.et-settings-section { margin-top: 4px; }
.et-settings-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 10px; }
.et-checkbox-row { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; }
.et-checkbox-row input[type=checkbox] { width: 16px; height: 16px; accent-color: #4f46e5; cursor: pointer; }
.et-checkbox-row label { font-size: 13px; color: #374151; cursor: pointer; }

/* Variables panel */
.et-vars-panel {
    background: #fafbff; border-top: 1px solid #f0f1f8;
    padding: 20px 24px;
}
.et-vars-title { font-size: 13px; font-weight: 600; color: #374151; margin-bottom: 4px; }
.et-vars-subtitle { font-size: 12px; color: #9ca3af; margin-bottom: 14px; }
.et-vars-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.et-var-tag {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 8px;
    background: #fff; border: 1.5px solid #e5e7eb;
    font-size: 12px; color: #4f46e5; font-weight: 500;
    cursor: pointer; transition: all .15s;
    white-space: nowrap;
}
.et-var-tag:hover { background: #eef2ff; border-color: #c7d2fe; }
.et-var-tag .et-var-icon { font-size: 14px; }
.et-var-tag code { font-size: 11px; color: #6366f1; background: #eef2ff; padding: 1px 5px; border-radius: 4px; }

/* Editor actions */
.et-editor-actions {
    padding: 16px 24px;
    border-top: 1px solid #f3f4f6;
    display: flex; align-items: center; justify-content: space-between;
    background: #fff;
}
.et-actions-right { display: flex; gap: 10px; }
.et-btn {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 18px; border-radius: 10px;
    font-size: 13px; font-weight: 600; cursor: pointer;
    border: 1.5px solid transparent; transition: all .15s;
}
.et-btn-primary {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    color: #fff; border-color: transparent;
    box-shadow: 0 2px 8px rgba(79,70,229,.3);
}
.et-btn-primary:hover { opacity: .88; }
.et-btn-secondary {
    background: #fff; color: #374151;
    border-color: #e5e7eb;
}
.et-btn-secondary:hover { border-color: #d1d5db; background: #f9fafb; }
.et-btn-danger {
    background: #fff; color: #ef4444;
    border-color: #fecaca;
}
.et-btn-danger:hover { background: #fef2f2; border-color: #fca5a5; }

/* Preview tab */
.et-preview-wrap {
    padding: 24px;
    background: #fff;
}
.et-preview-email {
    max-width: 600px; margin: 0 auto;
    border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;
}
.et-preview-header-bar {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    padding: 20px 24px; color: #fff;
}
.et-preview-header-bar h4 { margin: 0 0 4px; font-size: 15px; }
.et-preview-header-bar p  { margin: 0; font-size: 12px; opacity: .75; }
.et-preview-body { padding: 24px; color: #374151; font-size: 14px; line-height: 1.7; }

/* Helper cards row */
.et-helper-row { display: flex; gap: 16px; margin-top: 24px; }
.et-helper-card {
    flex: 1; background: #fff; border-radius: 14px;
    padding: 16px 20px; display: flex; align-items: flex-start; gap: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,.05);
}
.et-helper-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px; flex-shrink: 0;
}
.et-helper-icon.yellow { background: #fef9c3; }
.et-helper-icon.blue   { background: #dbeafe; }
.et-helper-icon.green  { background: #d1fae5; }
.et-helper-title { font-size: 13px; font-weight: 700; color: #111827; margin: 0 0 4px; }
.et-helper-text  { font-size: 12px; color: #6b7280; margin: 0; line-height: 1.5; }

/* History / Usage tabs */
.et-empty-state {
    padding: 48px 24px; text-align: center; color: #9ca3af;
}
.et-empty-state .et-es-icon { font-size: 36px; margin-bottom: 12px; }
.et-empty-state p { font-size: 13px; }

/* Responsive tweaks */
@media (max-width: 900px) {
    .et-layout { flex-direction: column; }
    .et-left-panel { width: 100%; }
    .et-helper-row { flex-direction: column; }
}
</style>

<div id="main">
    <?php TemplateUtility::printQuickSearch(); ?>

    <div class="et-page-wrapper">

        <!-- Page Header -->
        <div class="et-page-header">
            <div class="et-page-header-left">
                <div class="et-page-icon">
                    <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="3"/><path d="m2 7 10 7 10-7"/></svg>
                </div>
                <div class="et-page-title">
                    <h1>Email Templates</h1>
                    <p>Create, customize and manage email templates used for communication with candidates.</p>
                </div>
            </div>
            <div class="et-breadcrumb">
                <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=administration">Settings</a>
                &rsaquo; <span>E-Mail Templates</span>
            </div>
        </div>

        <!-- JS: show/hide editor panels + filter list -->
        <script>
        (function() {
            function showEditor(id) {
                document.querySelectorAll('.et-editor-card').forEach(function(c){ c.classList.remove('active'); });
                document.querySelectorAll('.et-template-item').forEach(function(i){ i.classList.remove('active'); });
                var card = document.getElementById('etCard' + id);
                var item = document.getElementById('etItem' + id);
                if (card) card.classList.add('active');
                if (item) item.classList.add('active');
                window._etActiveId = id;
            }
            function switchTab(cardId, tabName) {
                var card = document.getElementById('etCard' + cardId);
                if (!card) return;
                card.querySelectorAll('.et-tab').forEach(function(t){ t.classList.remove('active'); });
                card.querySelectorAll('.et-tab-content').forEach(function(c){ c.classList.remove('active'); });
                var activeTab = card.querySelector('[data-tab="' + tabName + '"]');
                var activeContent = card.querySelector('[data-tab-content="' + tabName + '"]');
                if (activeTab) activeTab.classList.add('active');
                if (activeContent) activeContent.classList.add('active');
            }
            function filterTemplates(q) {
                q = q.toLowerCase();
                document.querySelectorAll('.et-template-item').forEach(function(item) {
                    var name = item.querySelector('.et-template-name').textContent.toLowerCase();
                    item.style.display = name.indexOf(q) >= 0 ? '' : 'none';
                });
            }
            function insertAtCursor(fieldId, value) {
                var field = document.getElementById(fieldId);
                if (!field) return;
                field.focus();
                if (field.selectionStart !== undefined) {
                    var s = field.selectionStart, e = field.selectionEnd;
                    field.value = field.value.substring(0, s) + value + field.value.substring(e);
                    field.selectionStart = field.selectionEnd = s + value.length;
                } else {
                    field.value += value;
                }
                updateCount(fieldId);
            }
            function updateCount(fieldId) {
                var field = document.getElementById(fieldId);
                if (!field) return;
                var countEl = document.getElementById('count' + fieldId);
                if (!countEl) return;
                var words = field.value.trim() === '' ? 0 : field.value.trim().split(/\s+/).length;
                countEl.textContent = 'Words: ' + words + '   Characters: ' + field.value.length;
            }
            function applyFormat(fieldId, open, close) {
                var field = document.getElementById(fieldId);
                if (!field) return;
                var s = field.selectionStart, e = field.selectionEnd;
                var selected = field.value.substring(s, e) || '';
                var replacement = open + selected + close;
                field.value = field.value.substring(0, s) + replacement + field.value.substring(e);
                field.selectionStart = s + open.length;
                field.selectionEnd   = s + open.length + selected.length;
                field.focus();
                updateCount(fieldId);
            }
            function toggleEnabled(templateId) {
                var chk = document.getElementById('enabledChk' + templateId);
                var ta  = document.getElementById('messageText' + templateId);
                if (chk && ta) ta.disabled = !chk.checked;
                var badge = document.getElementById('badge' + templateId);
                if (badge) {
                    badge.textContent  = chk.checked ? 'Active' : 'Inactive';
                    badge.className    = chk.checked ? 'et-badge-active' : 'et-badge-disabled';
                }
            }
            // Expose globals
            window.etShowEditor    = showEditor;
            window.etSwitchTab     = switchTab;
            window.etFilterTemplates = filterTemplates;
            window.etInsertAtCursor = insertAtCursor;
            window.etUpdateCount    = updateCount;
            window.etApplyFormat    = applyFormat;
            window.etToggleEnabled  = toggleEnabled;
            window.refreshPreview   = refreshPreview;
            window.buildPreviewHTML = buildPreviewHTML;

            document.addEventListener('DOMContentLoaded', function() {
                // Auto-open: newly added, just saved, or first template
                var params = new URLSearchParams(window.location.search);
                var openId = params.get('newTemplate') || params.get('saved') || null;
                if (openId) {
                    var target = document.getElementById('etItem' + openId);
                    if (target) { showEditor(openId); }
                    else {
                        var first = document.querySelector('.et-template-item');
                        if (first) showEditor(first.dataset.id);
                    }
                } else {
                    var first = document.querySelector('.et-template-item');
                    if (first) showEditor(first.dataset.id);
                }

                // Toast notifications
                if (params.get('saved')) showToast('Template saved successfully!', 'success');
                if (params.get('deleted')) showToast('Template deleted.', 'info');
                if (params.get('newTemplate')) showToast('New template created. Edit and save it below.', 'success');
            });

            function showToast(msg, type) {
                var colors = { success: '#16a34a', info: '#2563eb', error: '#dc2626' };
                var t = document.createElement('div');
                t.style.cssText = 'position:fixed;bottom:28px;right:28px;z-index:9999;background:' + (colors[type]||'#374151')
                    + ';color:#fff;padding:14px 22px;border-radius:10px;font-size:14px;font-weight:600;'
                    + 'box-shadow:0 4px 20px rgba(0,0,0,0.18);opacity:0;transition:opacity .3s;font-family:Inter,system-ui,sans-serif;';
                t.textContent = msg;
                document.body.appendChild(t);
                requestAnimationFrame(function(){ t.style.opacity = '1'; });
                setTimeout(function(){ t.style.opacity = '0'; setTimeout(function(){ t.remove(); }, 400); }, 3500);
            }

            function buildPreviewHTML(bodyText, recipientName) {
                var lines = bodyText.split('\n');
                var bodyHTML = '';
                for (var i = 0; i < lines.length; i++) {
                    var line = lines[i].replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').trim();
                    if (line === '') { bodyHTML += '<br>'; }
                    else { bodyHTML += '<p style="margin:0 0 12px 0;color:#374151;font-size:15px;line-height:1.7;">' + line + '</p>'; }
                }
                return '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Preview</title></head>'
                    + '<body style="margin:0;padding:0;background:#f1f5f9;font-family:Segoe UI,Arial,sans-serif;">'
                    + '<table width="100%" cellpadding="0" cellspacing="0" style="background:#f1f5f9;padding:24px 0;"><tr><td align="center">'
                    + '<table width="600" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.08);max-width:600px;width:100%;">'
                    + '<tr><td style="background:#fff;padding:20px 36px 16px;border-bottom:1px solid #e5e7eb;">'
                    + '<table width="100%" cellpadding="0" cellspacing="0"><tr>'
                    + '<td><table cellpadding="0" cellspacing="0"><tr>'
                    + '<td style="background:#2563eb;border-radius:10px;width:40px;height:40px;text-align:center;vertical-align:middle;">'
                    + '<span style="color:#fff;font-size:22px;font-weight:900;font-family:Georgia,serif;line-height:40px;display:block;">N</span></td>'
                    + '<td style="padding-left:12px;"><div style="font-size:17px;font-weight:800;color:#1e293b;">Neutara ATS</div>'
                    + '<div style="font-size:11px;color:#64748b;">Applicant Tracking System</div></td></tr></table></td>'
                    + '<td align="right"><div style="font-size:11px;color:#64748b;">Empowering Careers, Driving Growth</div></td></tr></table></td></tr>'
                    + '<tr><td style="padding:30px 36px 24px;">' + bodyHTML
                    + '<table width="100%" cellpadding="0" cellspacing="0" style="margin:20px 0;"><tr>'
                    + '<td style="background:#eff6ff;border-radius:10px;padding:14px 18px;">'
                    + '<table cellpadding="0" cellspacing="0"><tr>'
                    + '<td style="vertical-align:middle;padding-right:10px;"><span style="display:inline-block;background:#2563eb;color:#fff;border-radius:50%;width:22px;height:22px;text-align:center;line-height:22px;font-size:12px;font-weight:700;">i</span></td>'
                    + '<td style="color:#1e40af;font-size:13px;">If you have any questions, please feel free to reply to this email.</td>'
                    + '</tr></table></td></tr></table>'
                    + '<p style="margin:0;color:#374151;font-size:14px;">We look forward to working with you.</p>'
                    + '</td></tr>'
                    + '<tr><td style="padding:0 36px 28px;"><p style="margin:0;color:#374151;font-size:13px;">Best regards,</p>'
                    + '<p style="margin:4px 0 0;font-weight:700;color:#1e293b;font-size:13px;">Neutara ATS Recruitment Team</p></td></tr>'
                    + '<tr><td style="background:#f8fafc;border-top:1px solid #e5e7eb;padding:16px 36px;">'
                    + '<table width="100%" cellpadding="0" cellspacing="0"><tr>'
                    + '<td style="font-size:11px;color:#64748b;">&#9993; careers@neutaraats.com</td>'
                    + '<td align="center" style="font-size:11px;color:#64748b;">&#127760; www.neutaraats.com</td>'
                    + '<td align="right" style="font-size:11px;color:#94a3b8;">in &nbsp; &#120139; &nbsp; f</td>'
                    + '</tr></table></td></tr>'
                    + '</table></td></tr></table></body></html>';
            }

            function refreshPreview(cardId) {
                var ta = document.getElementById('messageText' + cardId);
                var frame = document.getElementById('previewFrame' + cardId);
                if (!ta || !frame) return;
                var text = ta.value
                    .replace(/%CANDFIRSTNAME%/g, 'Jane')
                    .replace(/%CANDFULLNAME%/g, 'Jane Smith')
                    .replace(/%JBODTITLE%/g, 'Software Engineer')
                    .replace(/%CLNTNAME%/g, 'Acme Corp')
                    .replace(/%USERFULLNAME%/g, 'Recruiter')
                    .replace(/%DATETIME%/g, new Date().toLocaleString())
                    .replace(/%SITENAME%/g, 'Neutara ATS')
                    .replace(/%CANDOWNER%/g, 'Recruiter')
                    .replace(/%CANDSTATUS%/g, 'Interview Scheduled')
                    .replace(/%CANDPREVSTATUS%/g, 'Applied');
                frame.srcdoc = buildPreviewHTML(text, 'Jane Smith');
            }
        })();

        <?php
        /* Keep legacy helper for anything outside this file that calls insertAtCursor() */
        function generateInsertAtCursorLink($data, $description, $value)
        {
            $id = $data['emailTemplateID'];
            echo '<button type="button" class="et-var-tag" onclick="etInsertAtCursor(\'messageText'.$id.'\', \''.addslashes($value).'\')">';
            echo '<code>'.htmlspecialchars($value).'</code> '.htmlspecialchars($description);
            echo '</button>'."\n";
        }
        function generateInsertAtCursorLinkConditional($data, $description, $value)
        {
            if (strrpos($data['possibleVariables'], $value) !== false) {
                generateInsertAtCursorLink($data, $description, $value);
            }
        }
        ?>
        </script>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(15,23,42,0.55);align-items:center;justify-content:center;">
            <div style="background:#fff;border-radius:16px;width:420px;max-width:92vw;padding:28px 32px;box-shadow:0 20px 60px rgba(0,0,0,0.22);font-family:Inter,system-ui,sans-serif;">
                <div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">
                    <span style="display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;background:#fef2f2;border-radius:12px;font-size:22px;flex-shrink:0;">🗑️</span>
                    <div>
                        <div style="font-size:16px;font-weight:700;color:#111827;">Delete Template</div>
                        <div id="deleteModalName" style="font-size:13px;color:#6b7280;margin-top:2px;"></div>
                    </div>
                </div>
                <p style="font-size:13px;color:#374151;margin:0 0 24px;line-height:1.6;">Are you sure you want to delete this template? This action cannot be undone.</p>
                <div style="display:flex;gap:10px;justify-content:flex-end;">
                    <button onclick="document.getElementById('deleteModal').style.display='none';" style="padding:9px 20px;background:#f1f5f9;color:#374151;border:1px solid #d1d5db;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;">Cancel</button>
                    <a id="deleteModalLink" href="#" style="padding:9px 20px;background:#dc2626;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;">Delete</a>
                </div>
            </div>
        </div>
        <script>
        function confirmDeleteTemplate(id, name) {
            document.getElementById('deleteModalName').textContent = name;
            document.getElementById('deleteModalLink').href = '<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=deleteEmailTemplate&id=' + id;
            var m = document.getElementById('deleteModal');
            m.style.display = 'flex';
        }
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') document.getElementById('deleteModal').style.display='none'; });
        </script>

        <div class="et-layout">

            <!-- ════════════════════════════════════
                 LEFT PANEL – template list
                 ════════════════════════════════════ -->
            <div class="et-left-panel">
                <div class="et-left-header">
                    <h3>Templates</h3>
                    <a href="<?php echo CATSUtility::getIndexName(); ?>?m=settings&a=addEmailTemplate" class="et-add-btn">
                        &#43; Add Template
                    </a>
                </div>

                <div class="et-search-wrap">
                    <input type="text" placeholder="Search templates…" oninput="etFilterTemplates(this.value)">
                </div>

                <div class="et-template-list">
                    <?php
                    $icons = ['📅','🔔','✉️','📄','📨','⭐','🗂️','💬'];
                    $iconClasses = ['green','orange','red','blue','yellow','purple','green','blue'];
                    foreach ($this->emailTemplatesRS as $idx => $data):
                        $tid    = $data['emailTemplateID'];
                        $title  = htmlspecialchars($data['emailTemplateTitle']);
                        $isActive = ($data['disabled'] == 0);
                        $ic     = $icons[$idx % count($icons)];
                        $icCls  = $iconClasses[$idx % count($iconClasses)];
                        $descMap = [
                            'Invite candidate for an interview.',
                            'Reminder for scheduled interview.',
                            'Send rejection email to candidate.',
                            'Send offer letter to selected candidate.',
                            'Acknowledge candidate application.',
                            'Request feedback after interview.',
                        ];
                        $desc = isset($descMap[$idx]) ? $descMap[$idx] : 'Email template.';
                    ?>
                    <div class="et-template-item" id="etItem<?php echo $tid; ?>" data-id="<?php echo $tid; ?>"
                         onclick="etShowEditor(<?php echo $tid; ?>)">
                        <div class="et-template-icon <?php echo $icCls; ?>"><?php echo $ic; ?></div>
                        <div class="et-template-info">
                            <p class="et-template-name"><?php echo $title; ?></p>
                            <p class="et-template-desc"><?php echo htmlspecialchars($desc); ?></p>
                        </div>
                        <span id="badge<?php echo $tid; ?>" class="<?php echo $isActive ? 'et-badge-active' : 'et-badge-disabled'; ?>">
                            <?php echo $isActive ? 'Active' : 'Inactive'; ?>
                        </span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="et-left-footer">
                    Showing <?php echo count($this->emailTemplatesRS); ?> of <?php echo count($this->emailTemplatesRS); ?> templates
                </div>
            </div><!-- /left panel -->


            <!-- ════════════════════════════════════
                 RIGHT PANEL – editors
                 ════════════════════════════════════ -->
            <div class="et-right-panel">
                <?php foreach ($this->emailTemplatesRS as $idx => $data):
                    $tid      = $data['emailTemplateID'];
                    $title    = htmlspecialchars($data['emailTemplateTitle']);
                    $isActive = ($data['disabled'] == 0);
                    $isCustom = (strpos($data['emailTemplateTag'], 'CUSTOM') === 0);
                ?>
                <div class="et-editor-card" id="etCard<?php echo $tid; ?>">

                    <!-- Card header -->
                    <div class="et-editor-header">
                        <div class="et-editor-header-top">
                            <h2>Edit Template: <?php echo $title; ?></h2>
                            <div class="et-toggle-wrap">
                                <span class="et-toggle-label">Active</span>
                                <label class="et-toggle">
                                    <input type="checkbox" id="enabledChk<?php echo $tid; ?>"
                                           <?php if ($isActive) echo 'checked'; ?>
                                           onchange="etToggleEnabled(<?php echo $tid; ?>)">
                                    <span class="et-toggle-slider"></span>
                                </label>
                            </div>
                        </div>
                        <!-- Tabs -->
                        <div class="et-tabs">
                            <div class="et-tab active" data-tab="details"  onclick="etSwitchTab(<?php echo $tid; ?>, 'details')">Template Details</div>
                            <div class="et-tab"        data-tab="preview"  onclick="etSwitchTab(<?php echo $tid; ?>, 'preview'); refreshPreview(<?php echo $tid; ?>);">Preview</div>
                            <div class="et-tab"        data-tab="history"  onclick="etSwitchTab(<?php echo $tid; ?>, 'history')">History</div>
                            <div class="et-tab"        data-tab="usage"    onclick="etSwitchTab(<?php echo $tid; ?>, 'usage')">Usage</div>
                        </div>
                    </div><!-- /editor-header -->

                    <!-- ── TAB: Template Details ── -->
                    <div class="et-tab-content active" data-tab-content="details">
                        <form action="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=emailTemplates" method="post">
                            <input type="hidden" name="postback"   value="postback">
                            <input type="hidden" name="templateID" value="<?php echo $tid; ?>">

                            <div class="et-form-body">

                                <div class="et-form-row">
                                    <!-- Template Name -->
                                    <div class="et-form-group">
                                        <label>Template Name <span class="req">*</span></label>
                                        <input type="text" name="emailTemplateTitle" value="<?php echo $title; ?>">
                                    </div>
                                    <!-- Subject (display only – not stored separately in DB, shown for UX) -->
                                    <div class="et-form-group">
                                        <label>Subject <span class="req">*</span></label>
                                        <input type="text" name="emailSubject" placeholder="e.g. Update on Your Application – %JBODTITLE%"
                                               value="<?php echo htmlspecialchars($data['subject'] ?? ''); ?>">
                                    </div>
                                </div>

                                <!-- Email Body -->
                                <div class="et-form-group" style="margin-bottom:16px;">
                                    <label>Email Body <span class="req">*</span></label>
                                    <div class="et-rte-wrap">
                                        <div class="et-rte-toolbar">
                                            <button type="button" class="et-rte-btn" title="Bold"
                                                    onclick="etApplyFormat('messageText<?php echo $tid; ?>', '<B>', '</B>')"><b>B</b></button>
                                            <button type="button" class="et-rte-btn" title="Italic"
                                                    onclick="etApplyFormat('messageText<?php echo $tid; ?>', '<I>', '</I>')"><i>I</i></button>
                                            <button type="button" class="et-rte-btn" title="Underline"
                                                    onclick="etApplyFormat('messageText<?php echo $tid; ?>', '<U>', '</U>')"><u>U</u></button>
                                            <div class="et-rte-sep"></div>
                                            <button type="button" class="et-rte-btn" title="Unordered list"
                                                    onclick="etApplyFormat('messageText<?php echo $tid; ?>', '<UL><LI>', '</LI></UL>')">&#8226;&#8212;</button>
                                            <button type="button" class="et-rte-btn" title="Ordered list"
                                                    onclick="etApplyFormat('messageText<?php echo $tid; ?>', '<OL><LI>', '</LI></OL>')">1&#8212;</button>
                                            <div class="et-rte-sep"></div>
                                            <button type="button" class="et-rte-btn" title="Insert link"
                                                    onclick="etApplyFormat('messageText<?php echo $tid; ?>', '<A HREF=\'\'>', '</A>')">&#128279;</button>
                                        </div>
                                        <textarea
                                            class="et-rte-body"
                                            name="messageText"
                                            id="messageText<?php echo $tid; ?>"
                                            <?php if (!$isActive) echo 'disabled'; ?>
                                            oninput="etUpdateCount('messageText<?php echo $tid; ?>')"
                                        ><?php echo $this->_($data['text']); ?></textarea>
                                        <input type="hidden" name="messageTextOrigional"
                                               id="messageTextOrigional<?php echo $tid; ?>"
                                               value="<?php echo $this->_($data['text']); ?>">
                                        <div class="et-rte-footer">
                                            <span id="countmessageText<?php echo $tid; ?>">
                                                <?php
                                                    $txt   = strip_tags($data['text'] ?? '');
                                                    $words = $txt === '' ? 0 : count(preg_split('/\s+/', trim($txt)));
                                                    echo 'Words: ' . $words . '   Characters: ' . strlen($txt);
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Template settings checkboxes -->
                                <div class="et-settings-section">
                                    <div class="et-settings-title">Template Settings</div>
                                    <div class="et-checkbox-row">
                                        <input type="checkbox" id="useThisTemplate<?php echo $tid; ?>"
                                               name="useThisTemplate"
                                               <?php if ($isActive) echo 'checked'; ?>
                                               onchange="
                                                   document.getElementById('messageText<?php echo $tid; ?>').disabled = !this.checked;
                                                   document.getElementById('enabledChk<?php echo $tid; ?>').checked = this.checked;
                                                   etToggleEnabled(<?php echo $tid; ?>);
                                               ">
                                        <label for="useThisTemplate<?php echo $tid; ?>">Use this Template / Feature (Enable)</label>
                                    </div>
                                    <div class="et-checkbox-row">
                                        <input type="checkbox" id="allowSubject<?php echo $tid; ?>" checked>
                                        <label for="allowSubject<?php echo $tid; ?>">Allow users to edit subject</label>
                                    </div>
                                    <div class="et-checkbox-row">
                                        <input type="checkbox" id="allowBody<?php echo $tid; ?>" checked>
                                        <label for="allowBody<?php echo $tid; ?>">Allow users to edit body</label>
                                    </div>
                                </div>

                            </div><!-- /form-body -->

                            <!-- Variables -->
                            <div class="et-vars-panel">
                                <div class="et-vars-title">Insert Variables</div>
                                <div class="et-vars-subtitle">Click on a variable to insert it.</div>
                                <div class="et-vars-grid" id="varsGrid<?php echo $tid; ?>">
                                    <?php if (!isset($this->noGlobalTemplates)): ?>
                                    <button type="button" class="et-var-tag"
                                            onclick="etInsertAtCursor('messageText<?php echo $tid; ?>', '%DATETIME%')">
                                        <span class="et-var-icon">📅</span>
                                        <code>%DATETIME%</code> Current Date/Time
                                    </button>
                                    <button type="button" class="et-var-tag"
                                            onclick="etInsertAtCursor('messageText<?php echo $tid; ?>', '%SITENAME%')">
                                        <span class="et-var-icon">🏢</span>
                                        <code>%SITENAME%</code> Site Name
                                    </button>
                                    <button type="button" class="et-var-tag"
                                            onclick="etInsertAtCursor('messageText<?php echo $tid; ?>', '%USERFULLNAME%')">
                                        <span class="et-var-icon">👤</span>
                                        <code>%USERFULLNAME%</code> Sender Name
                                    </button>
                                    <button type="button" class="et-var-tag"
                                            onclick="etInsertAtCursor('messageText<?php echo $tid; ?>', '%USERMAIL%')">
                                        <span class="et-var-icon">✉️</span>
                                        <code>%USERMAIL%</code> Sender Email
                                    </button>
                                    <?php endif; ?>

                                    <?php generateInsertAtCursorLinkConditional($data, 'Candidate First Name',    '%CANDFIRSTNAME%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Candidate Full Name',     '%CANDFULLNAME%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Candidate Status',        '%CANDSTATUS%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Prev Candidate Status',   '%CANDPREVSTATUS%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Candidate Owner',         '%CANDOWNER%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Candidate URL',           '%CANDCATSURL%'); ?>

                                    <?php generateInsertAtCursorLinkConditional($data, 'Company Name',            '%CLNTNAME%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Company Owner',           '%CLNTOWNER%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Company URL',             '%CLNTCATSURL%'); ?>

                                    <?php generateInsertAtCursorLinkConditional($data, 'Contact First Name',      '%CONTFIRSTNAME%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Contact Full Name',       '%CONTFULLNAME%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Contact Company',         '%CONTCLIENTNAME%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Contact Owner',           '%CONTOWNER%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Contact URL',             '%CONTCATSURL%'); ?>

                                    <?php generateInsertAtCursorLinkConditional($data, 'Job Order Title',         '%JBODTITLE%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Job Order ID',            '%JBODID%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Job Order Company',       '%JBODCLIENT%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Job Order Owner',         '%JBODOWNER%'); ?>
                                    <?php generateInsertAtCursorLinkConditional($data, 'Job Order URL',           '%JBODCATSURL%'); ?>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="et-editor-actions">
                                <div>
                                    <button type="button" class="et-btn et-btn-danger"
                                            onclick="confirmDeleteTemplate(<?php echo $tid; ?>, '<?php echo addslashes($title); ?>')">
                                        🗑 Delete Template
                                    </button>
                                </div>
                                <div class="et-actions-right">
                                    <button type="reset" class="et-btn et-btn-secondary">Cancel</button>
                                    <button type="submit" class="et-btn et-btn-primary">&#128190; Save Template</button>
                                </div>
                            </div>
                        </form>
                    </div><!-- /tab: details -->

                    <!-- ── TAB: Preview ── -->
                    <div class="et-tab-content" data-tab-content="preview">
                        <div style="padding:20px 24px;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                                <p style="font-size:13px;color:#6b7280;margin:0;">Preview with sample values. Variables are replaced for display only.</p>
                                <button type="button" onclick="refreshPreview(<?php echo $tid; ?>);" style="padding:7px 16px;background:#f0f4ff;color:#2563eb;border:1px solid #bfdbfe;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;font-family:Inter,system-ui,sans-serif;">
                                    ↻ Refresh Preview
                                </button>
                            </div>
                            <div style="border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#f1f5f9;">
                                <iframe id="previewFrame<?php echo $tid; ?>" style="width:100%;min-height:580px;border:none;display:block;" srcdoc="<p style='text-align:center;padding:60px;color:#9ca3af;font-family:sans-serif;'>Click &quot;Preview&quot; tab again or press Refresh Preview to render.</p>"></iframe>
                            </div>
                        </div>
                    </div>

                    <!-- ── TAB: History ── -->
                    <div class="et-tab-content" data-tab-content="history">
                        <div class="et-empty-state">
                            <div class="et-es-icon">📋</div>
                            <p>No history available for this template yet.</p>
                        </div>
                    </div>

                    <!-- ── TAB: Usage ── -->
                    <div class="et-tab-content" data-tab-content="usage">
                        <div class="et-empty-state">
                            <div class="et-es-icon">📊</div>
                            <p>Usage statistics will appear here once the template has been sent.</p>
                        </div>
                    </div>

                </div><!-- /et-editor-card -->
                <?php endforeach; ?>
            </div><!-- /right panel -->

        </div><!-- /et-layout -->

        <!-- Helper cards -->
        <div class="et-helper-row">
            <div class="et-helper-card">
                <div class="et-helper-icon yellow">💡</div>
                <div>
                    <p class="et-helper-title">Pro Tip</p>
                    <p class="et-helper-text">Use variables to personalize your emails and make your communication more effective.</p>
                </div>
            </div>
            <div class="et-helper-card">
                <div class="et-helper-icon blue">👁️</div>
                <div>
                    <p class="et-helper-title">Preview your email</p>
                    <p class="et-helper-text">Switch to the Preview tab to see how this email will look for the candidate.</p>
                </div>
            </div>
            <div class="et-helper-card">
                <div class="et-helper-icon green">❓</div>
                <div>
                    <p class="et-helper-title">Need Help?</p>
                    <p class="et-helper-text">Learn more about email templates and best practices in our help center.</p>
                </div>
            </div>
        </div>

    </div><!-- /et-page-wrapper -->
</div><!-- /main -->

<?php TemplateUtility::printFooter(); ?>
