<?php /* Email Settings - Modern UI */ ?>
<?php TemplateUtility::printHeader('Settings - Email', array('modules/settings/validator.js', 'modules/settings/Settings.js')); ?>
<?php TemplateUtility::printHeaderBlock(); ?>
<?php TemplateUtility::printTabs($this->active, $this->subActive); ?>

<style>
.em-wrap { max-width: 860px; margin: 0 auto; padding: 28px 32px 60px; }

/* Page header */
.em-page-header { display: flex; align-items: center; gap: 14px; margin-bottom: 28px; }
.em-page-icon { width: 44px; height: 44px; background: linear-gradient(135deg,#2563eb,#1d4ed8); border-radius: 11px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 4px 12px rgba(37,99,235,.3); }
.em-page-header h1 { font-size: 20px; font-weight: 800; color: #111827; letter-spacing: -.02em; }
.em-page-header p { font-size: 13px; color: #6b7280; margin-top: 2px; }

/* Cards */
.em-card { background: #fff; border: 1.5px solid #e5e7eb; border-radius: 14px; overflow: hidden; margin-bottom: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.06); }
.em-card-header { padding: 16px 22px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: 10px; background: #fafafa; }
.em-card-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.em-card-title { font-size: 14px; font-weight: 700; color: #111827; }
.em-card-subtitle { font-size: 12px; color: #9ca3af; margin-top: 1px; }
.em-card-body { padding: 22px; }

/* Status badge */
.em-badge { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 99px; }
.em-badge-ok  { background: #dcfce7; color: #16a34a; }
.em-badge-off { background: #fee2e2; color: #dc2626; }
.em-badge-dot { width: 6px; height: 6px; border-radius: 50%; }
.em-badge-ok  .em-badge-dot { background: #22c55e; }
.em-badge-off .em-badge-dot { background: #ef4444; }

/* Form grid */
.em-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.em-grid-full { grid-column: 1 / -1; }
.em-field { display: flex; flex-direction: column; gap: 6px; }
.em-field label { font-size: 12px; font-weight: 700; color: #374151; }
.em-field input[type=text],
.em-field input[type=email],
.em-field input[type=number],
.em-field input[type=password],
.em-field select {
  padding: 9px 12px;
  border: 1.5px solid #e5e7eb;
  border-radius: 9px;
  font-size: 13px;
  font-family: inherit;
  color: #111827;
  background: #fff;
  outline: none;
  transition: border-color .15s, box-shadow .15s;
  width: 100%;
  box-sizing: border-box;
}
.em-field input:focus,
.em-field select:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.1); }
.em-field .em-hint { font-size: 11px; color: #9ca3af; }

/* Password field with show/hide */
.em-pass-wrap { position: relative; }
.em-pass-wrap input { padding-right: 40px; }
.em-pass-toggle { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #9ca3af; padding: 0; display: flex; }
.em-pass-toggle:hover { color: #374151; }

/* Toggle switch */
.em-toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 13px 0; border-bottom: 1px solid #f9fafb; }
.em-toggle-row:last-child { border-bottom: none; }
.em-toggle-info h4 { font-size: 13px; font-weight: 600; color: #111827; }
.em-toggle-info p { font-size: 12px; color: #6b7280; margin-top: 2px; }
.em-toggle { position: relative; width: 42px; height: 23px; flex-shrink: 0; }
.em-toggle input { opacity: 0; width: 0; height: 0; }
.em-slider { position: absolute; inset: 0; background: #d1d5db; border-radius: 99px; cursor: pointer; transition: background .2s; }
.em-slider::before { content: ''; position: absolute; width: 17px; height: 17px; background: #fff; border-radius: 50%; top: 3px; left: 3px; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.2); }
.em-toggle input:checked + .em-slider { background: #2563eb; }
.em-toggle input:checked + .em-slider::before { transform: translateX(19px); }

/* Test email section */
.em-test-row { display: flex; align-items: flex-end; gap: 10px; }
.em-test-row .em-field { flex: 1; }
.em-test-result { margin-top: 12px; padding: 12px 16px; border-radius: 9px; font-size: 13px; display: none; }
.em-test-success { background: #f0fdf4; border: 1.5px solid #86efac; color: #16a34a; }
.em-test-error   { background: #fef2f2; border: 1.5px solid #fca5a5; color: #dc2626; }

/* Current SMTP info box */
.em-smtp-info { background: #eff6ff; border: 1.5px solid #bfdbfe; border-radius: 10px; padding: 14px 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; }
.em-smtp-info-text { font-size: 12px; color: #1d4ed8; }
.em-smtp-info-text strong { display: block; font-size: 13px; margin-bottom: 2px; }

/* Action buttons */
.em-save-btn { background: #2563eb; color: #fff; border: none; font-size: 14px; font-weight: 700; padding: 11px 28px; border-radius: 10px; cursor: pointer; transition: all .2s; box-shadow: 0 2px 8px rgba(37,99,235,.3); font-family: inherit; }
.em-save-btn:hover { background: #1d4ed8; transform: translateY(-1px); }
.em-secondary-btn { background: #f3f4f6; color: #374151; border: 1.5px solid #e5e7eb; font-size: 13px; font-weight: 600; padding: 10px 22px; border-radius: 10px; cursor: pointer; font-family: inherit; transition: all .15s; }
.em-secondary-btn:hover { background: #e5e7eb; }
.em-btn-row { display: flex; gap: 10px; align-items: center; margin-top: 22px; }

/* Mailer mode selector cards */
.em-mode-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 10px; margin-bottom: 20px; }
.em-mode-card { border: 2px solid #e5e7eb; border-radius: 10px; padding: 14px 12px; cursor: pointer; transition: all .15s; text-align: center; background: #fff; }
.em-mode-card:hover { border-color: #93c5fd; background: #eff6ff; }
.em-mode-card.selected { border-color: #2563eb; background: #eff6ff; }
.em-mode-card input[type=radio] { display: none; }
.em-mode-card-icon { font-size: 22px; margin-bottom: 6px; }
.em-mode-card-name { font-size: 13px; font-weight: 700; color: #111827; }
.em-mode-card-desc { font-size: 11px; color: #6b7280; margin-top: 3px; }

/* SMTP section visibility */
.em-smtp-section { transition: opacity .2s; }
</style>

<div id="main">
  <?php TemplateUtility::printQuickSearch(); ?>
  <div id="contents">
    <div class="em-wrap">

      <!-- Page Header -->
      <div class="em-page-header">
        <div class="em-page-icon">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <div>
          <h1>Email Configuration</h1>
          <p>SMTP server credentials for sending outgoing email from Neutara ATS</p>
        </div>
        <div style="margin-left:auto;">
          <?php $mailerMode = defined('MAIL_MAILER') ? MAIL_MAILER : 0; ?>
          <?php if ($mailerMode == 3): ?>
          <span class="em-badge em-badge-ok"><span class="em-badge-dot"></span> Email Active</span>
          <?php else: ?>
          <span class="em-badge em-badge-off"><span class="em-badge-dot"></span> Email Disabled</span>
          <?php endif; ?>
        </div>
      </div>

      <form name="emailSettingsForm" id="emailSettingsForm"
            action="<?php echo CATSUtility::getIndexName(); ?>?m=settings&amp;a=emailSettings"
            method="post">
        <input type="hidden" name="postback"   value="postback" />
        <input type="hidden" name="configured" value="1" />
        <!-- SMTP is the only supported sending method here; saving these
             settings turns email sending on via SMTP. -->
        <input type="hidden" name="mailMailer" value="3" />

        <!-- ── SMTP SETTINGS ── -->
        <div class="em-card em-smtp-section" id="smtpCard">
          <div class="em-card-header">
            <div class="em-card-icon" style="background:#fef3c7;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <div>
              <div class="em-card-title">SMTP Server</div>
              <div class="em-card-subtitle">Credentials for your outgoing mail server</div>
            </div>
          </div>
          <div class="em-card-body">
            <div class="em-smtp-info">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <span class="em-smtp-info-text">
                <strong>Currently active: <?php echo htmlspecialchars(defined('MAIL_SMTP_HOST') ? MAIL_SMTP_HOST : ''); ?>:<?php echo htmlspecialchars(defined('MAIL_SMTP_PORT') ? MAIL_SMTP_PORT : ''); ?></strong>
                Changes here update <code>config.php</code> and take effect immediately.
              </span>
            </div>
            <div class="em-grid">
              <div class="em-field em-grid-full" style="grid-column:1/2;">
                <label>SMTP Host</label>
                <input type="text" name="smtpHost" placeholder="smtp.gmail.com" autocomplete="off"
                       value="<?php echo htmlspecialchars(defined('MAIL_SMTP_HOST') ? MAIL_SMTP_HOST : ''); ?>" />
                <span class="em-hint">e.g. smtp.gmail.com · smtp.office365.com · mail.yourdomain.com</span>
              </div>
              <div class="em-field">
                <label>SMTP Port</label>
                <input type="number" name="smtpPort" placeholder="587"
                       value="<?php echo htmlspecialchars(defined('MAIL_SMTP_PORT') ? MAIL_SMTP_PORT : '587'); ?>" />
                <span class="em-hint">587 (TLS) · 465 (SSL) · 25 (none)</span>
              </div>
              <div class="em-field">
                <label>Encryption</label>
                <select name="smtpSecure">
                  <option value="tls"  <?php if(!defined('MAIL_SMTP_SECURE') || MAIL_SMTP_SECURE==='tls')  echo 'selected'; ?>>TLS (recommended)</option>
                  <option value="ssl"  <?php if( defined('MAIL_SMTP_SECURE') && MAIL_SMTP_SECURE==='ssl')  echo 'selected'; ?>>SSL</option>
                  <option value=""     <?php if( defined('MAIL_SMTP_SECURE') && MAIL_SMTP_SECURE==='')     echo 'selected'; ?>>None</option>
                </select>
              </div>
              <div class="em-field">
                <label>SMTP Username</label>
                <!-- type="text", not "email": many SMTP providers (self-hosted
                     mail servers in particular) use plain usernames, not email
                     addresses. type="email" silently blocks form submission via
                     native browser validation - with no visible error - the
                     instant the value isn't shaped like an address, which reads
                     to the user as "my save just did nothing." -->
                <input type="text" name="smtpUser" placeholder="you@gmail.com" autocomplete="off"
                       value="<?php echo htmlspecialchars(defined('MAIL_SMTP_USER') ? MAIL_SMTP_USER : ''); ?>" />
              </div>
              <div class="em-field">
                <label>SMTP Password / App Password</label>
                <div class="em-pass-wrap">
                  <input type="password" name="smtpPass" id="smtpPassField" placeholder="••••••••••••"
                         value="<?php echo htmlspecialchars(defined('MAIL_SMTP_PASS') ? MAIL_SMTP_PASS : ''); ?>" />
                  <button type="button" class="em-pass-toggle" onclick="togglePass()">
                    <svg id="eyeIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                  </button>
                </div>
                <span class="em-hint">For Gmail, use an <a href="https://myaccount.google.com/apppasswords" target="_blank" style="color:#2563eb;">App Password</a> (16 chars, no spaces)</span>
              </div>
            </div>
          </div>
        </div>

        <!-- ── FROM ADDRESS + TEST ── -->
        <div class="em-card">
          <div class="em-card-header">
            <div class="em-card-icon" style="background:#f5f3ff;">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div>
              <div class="em-card-title">Sender Identity</div>
              <div class="em-card-subtitle">The "From" address candidates and users see</div>
            </div>
          </div>
          <div class="em-card-body">
            <div class="em-grid">
              <div class="em-field">
                <label>From Email Address</label>
                <input type="email" name="fromAddress" id="fromAddress" placeholder="noreply@yourdomain.com"
                       value="<?php $this->_($this->mailerSettingsRS['fromAddress']); ?>" />
                <span class="em-hint">Shown as the sender on all outgoing emails</span>
              </div>
              <div class="em-field">
                <label>Send Test Email To</label>
                <div class="em-test-row">
                  <div class="em-field" style="margin:0;flex:1;">
                    <input type="text" id="testEmailAddress" name="testEmailAddress"
                           placeholder="test@example.com" />
                  </div>
                  <button type="button" id="testBtn" onclick="runEmailTest()"
                          style="flex-shrink:0;background:#2563eb;color:#fff;border:none;font-size:13px;font-weight:700;padding:9px 18px;border-radius:9px;cursor:pointer;font-family:inherit;white-space:nowrap;display:flex;align-items:center;gap:6px;transition:background .15s;"
                          onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    Send Test
                  </button>
                </div>
              </div>
            </div>
            <div id="testResult" class="em-test-result"></div>
            <div id="testButtonSpanActive" style="display:none; margin-top:10px; font-size:13px; color:#6b7280;">
              <svg style="animation:spin 1s linear infinite;display:inline-block;" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
              Sending…
            </div>
            <div id="testOutput" style="display:none;"></div>
          </div>
        </div>

        <!-- Save row -->
        <div class="em-btn-row">
          <button type="submit" class="em-save-btn">Save Email Settings</button>
          <button type="reset"  class="em-secondary-btn">Reset</button>
        </div>

      </form>
    </div><!-- em-wrap -->
  </div><!-- contents -->
</div><!-- main -->

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<script>
var sessionCookie = '<?php echo $this->sessionCookie; ?>';

/* Password visibility toggle */
function togglePass() {
  var f = document.getElementById('smtpPassField');
  f.type = (f.type === 'password') ? 'text' : 'password';
}

/* Test email — reuse existing Settings.js infrastructure */
function runEmailTest() {
  var addr = document.getElementById('testEmailAddress').value.trim();
  if (!addr) { showTestResult('error', 'Please enter a test email address.'); return; }
  document.getElementById('testBtn').disabled = true;
  document.getElementById('testButtonSpanActive').style.display = 'block';
  document.getElementById('testResult').style.display = 'none';
  // Use the existing testEmailSettings function from Settings.js
  testEmailSettings(sessionCookie);
  // Poll for testOutput content
  var poll = setInterval(function() {
    var out = document.getElementById('testOutput').innerHTML;
    if (out.length > 0) {
      clearInterval(poll);
      document.getElementById('testButtonSpanActive').style.display = 'none';
      document.getElementById('testBtn').disabled = false;
      var success = out.toLowerCase().indexOf('success') > -1 || out.toLowerCase().indexOf('sent') > -1;
      showTestResult(success ? 'success' : 'error', out.replace(/<[^>]+>/g,'').trim());
    }
  }, 500);
  setTimeout(function(){ clearInterval(poll); document.getElementById('testButtonSpanActive').style.display='none'; document.getElementById('testBtn').disabled=false; }, 15000);
}

function showTestResult(type, msg) {
  var el = document.getElementById('testResult');
  el.className = 'em-test-result em-test-' + type;
  el.innerHTML = (type === 'success'
    ? '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline;vertical-align:middle;margin-right:6px;"><polyline points="20 6 9 17 4 12"/></svg>'
    : '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline;vertical-align:middle;margin-right:6px;"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>')
    + msg;
  el.style.display = 'block';
}
</script>

<?php TemplateUtility::printFooter(); ?>
