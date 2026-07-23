<?php
global $careerPage;
$jsBase = (isset($careerPage) && $careerPage == true) ? '../js/' : 'js/';
$imgBase = (isset($careerPage) && $careerPage == true) ? '../images/' : 'images/';
$rootBase = (isset($careerPage) && $careerPage == true) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo htmlspecialchars($this->siteName ?? 'Neutara'); ?> Careers – Apply</title>
<link href="<?php echo($rootBase); ?>inter.css" rel="stylesheet">
<style>
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
body{font-family:'Inter',system-ui,sans-serif;background:#f8fafc;color:#1e293b;min-height:100vh;}

/* ── Loader ── */
#pageLoader{position:fixed;inset:0;z-index:99999;background:#fff;display:flex;flex-direction:column;align-items:center;justify-content:center;transition:opacity .4s ease;}
#pageLoader.hide{opacity:0;pointer-events:none;}
.loader-logo{display:flex;align-items:center;gap:12px;margin-bottom:24px;}
.loader-logo-box{width:48px;height:48px;background:#2563eb;border-radius:12px;display:flex;align-items:center;justify-content:center;}
.loader-logo-box span{color:#fff;font-size:26px;font-weight:900;font-family:Georgia,serif;}
.loader-brand{font-size:22px;font-weight:800;color:#1e293b;}
.loader-brand small{display:block;font-size:12px;font-weight:400;color:#64748b;}
.loader-bar{width:200px;height:3px;background:#e5e7eb;border-radius:99px;overflow:hidden;}
.loader-bar-fill{height:100%;background:#2563eb;border-radius:99px;animation:loaderFill 1.2s ease-in-out infinite;}
@keyframes loaderFill{0%{width:0;margin-left:0;}60%{width:80%;}100%{width:0;margin-left:100%;}}

/* ── Nav ── */
.cp-nav{background:#fff;border-bottom:1px solid #e5e7eb;padding:0 40px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;box-shadow:0 1px 4px rgba(0,0,0,.05);}
.cp-nav-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
.cp-nav-logo{width:36px;height:36px;background:#2563eb;border-radius:9px;display:flex;align-items:center;justify-content:center;}
.cp-nav-logo span{color:#fff;font-size:18px;font-weight:900;font-family:Georgia,serif;}
.cp-nav-name{font-size:16px;font-weight:800;color:#1e293b;}
.cp-nav-name small{display:block;font-size:10px;font-weight:500;color:#64748b;line-height:1;}
.cp-nav-links{display:flex;align-items:center;gap:16px;}
.cp-nav-links a{font-size:13px;font-weight:500;color:#475569;text-decoration:none;padding:6px 14px;border-radius:8px;transition:background .15s;}
.cp-nav-links a:hover{background:#f1f5f9;color:#1e293b;}
.cp-nav-links .btn-explore{background:#2563eb;color:#fff;padding:7px 18px;border-radius:8px;font-weight:600;font-size:13px;}
.cp-nav-links .btn-explore:hover{background:#1d4ed8;color:#fff;}

/* ── Page wrapper ── */
.cp-page{max-width:1100px;margin:0 auto;padding:36px 24px 60px;}
.cp-back{display:inline-flex;align-items:center;gap:6px;color:#2563eb;font-size:13px;font-weight:500;text-decoration:none;margin-bottom:20px;}
.cp-back:hover{text-decoration:underline;}
.cp-page-title{font-size:28px;font-weight:800;color:#0f172a;margin-bottom:4px;}
.cp-page-subtitle{font-size:13px;color:#64748b;margin-bottom:24px;}

/* ── Progress ── */
.cp-progress-wrap{margin-bottom:28px;}
.cp-progress-label{font-size:12px;color:#64748b;margin-bottom:6px;}
.cp-progress-bar{height:6px;background:#e5e7eb;border-radius:99px;overflow:hidden;}
.cp-progress-fill{height:100%;background:#2563eb;border-radius:99px;transition:width .4s ease;}

/* ── Steps ── */
.cp-steps{display:flex;gap:0;margin-bottom:28px;align-items:center;}
.cp-step{display:flex;align-items:center;gap:8px;flex:1;}
.cp-step-circle{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;flex-shrink:0;border:2px solid #e5e7eb;background:#fff;color:#94a3b8;transition:all .3s;}
.cp-step.active .cp-step-circle{background:#2563eb;border-color:#2563eb;color:#fff;}
.cp-step.done .cp-step-circle{background:#2563eb;border-color:#2563eb;color:#fff;}
.cp-step-label{font-size:12px;font-weight:600;color:#94a3b8;}
.cp-step.active .cp-step-label{color:#2563eb;}
.cp-step.done .cp-step-label{color:#2563eb;}
.cp-step-line{flex:1;height:2px;background:#e5e7eb;margin:0 6px;}
.cp-step.done .cp-step-line{background:#2563eb;}

/* ── Two col layout ── */
.cp-layout{display:flex;gap:24px;align-items:flex-start;}
.cp-main{flex:1;min-width:0;display:flex;flex-direction:column;gap:20px;}
.cp-sidebar{width:280px;flex-shrink:0;position:sticky;top:80px;}

/* ── Section cards ── */
.cp-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;}
.cp-card-header{padding:20px 24px 16px;display:flex;align-items:center;gap:12px;border-bottom:1px solid #f1f5f9;}
.cp-card-icon{width:38px;height:38px;border-radius:10px;background:#eff6ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.cp-card-icon svg{width:18px;height:18px;stroke:#2563eb;fill:none;stroke-width:2;}
.cp-card-title{font-size:15px;font-weight:700;color:#0f172a;}
.cp-card-sub{font-size:12px;color:#64748b;margin-top:2px;}
.cp-card-body{padding:20px 24px;}

/* ── Form grid ── */
.cp-form-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.cp-form-grid.full{grid-template-columns:1fr;}
.cp-form-group{display:flex;flex-direction:column;gap:5px;}
.cp-form-group.span2{grid-column:1/-1;}
.cp-label{font-size:12px;font-weight:600;color:#374151;}
.cp-label .req{color:#ef4444;margin-left:2px;}
.cp-input,.cp-select,.cp-textarea{width:100%;padding:10px 14px;border:1.5px solid #e5e7eb;border-radius:9px;font-size:13px;color:#1e293b;background:#fff;font-family:inherit;outline:none;transition:border-color .15s,box-shadow .15s;}
.cp-input:focus,.cp-select:focus,.cp-textarea:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.1);}
.cp-textarea{resize:vertical;min-height:80px;line-height:1.5;}
.cp-select{cursor:pointer;appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 12px center;}

/* ── Upload zone ── */
.cp-upload-zone{border:2px dashed #cbd5e1;border-radius:12px;padding:32px 20px;text-align:center;cursor:pointer;transition:all .2s;background:#f8fafc;position:relative;}
.cp-upload-zone:hover,.cp-upload-zone.drag-over{border-color:#2563eb;background:#eff6ff;}
.cp-upload-zone input[type=file]{position:absolute;inset:0;opacity:0;cursor:pointer;width:100%;height:100%;}
.cp-upload-icon{width:40px;height:40px;background:#eff6ff;border-radius:10px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;}
.cp-upload-icon svg{width:20px;height:20px;stroke:#2563eb;fill:none;stroke-width:2;}
.cp-upload-text{font-size:13px;color:#475569;margin-bottom:8px;}
.cp-upload-text strong{color:#2563eb;}
.cp-upload-btn{display:inline-block;padding:8px 20px;background:#2563eb;color:#fff;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;}
.cp-upload-hint{font-size:11px;color:#94a3b8;margin-top:8px;}
.cp-file-selected{display:flex;align-items:center;gap:10px;margin-top:10px;background:#eff6ff;border-radius:8px;padding:10px 14px;display:none;}
.cp-file-selected svg{width:16px;height:16px;stroke:#2563eb;fill:none;stroke-width:2;flex-shrink:0;}
.cp-file-selected span{font-size:13px;color:#1e40af;font-weight:500;}

/* ── Sidebar ── */
.cp-summary-card{background:#fff;border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;}
.cp-summary-header{padding:16px 20px;border-bottom:1px solid #f1f5f9;}
.cp-summary-header h3{font-size:14px;font-weight:700;color:#0f172a;}
.cp-summary-header p{font-size:12px;color:#64748b;margin-top:2px;}
.cp-summary-list{padding:16px 20px;display:flex;flex-direction:column;gap:12px;}
.cp-summary-item{display:flex;align-items:center;gap:10px;}
.cp-summary-dot{width:18px;height:18px;border-radius:50%;border:2px solid #d1d5db;flex-shrink:0;transition:all .3s;}
.cp-summary-item.done .cp-summary-dot{background:#2563eb;border-color:#2563eb;}
.cp-summary-item.active .cp-summary-dot{border-color:#2563eb;}
.cp-summary-info .cp-summary-name{font-size:13px;font-weight:600;color:#1e293b;}
.cp-summary-info .cp-summary-status{font-size:11px;color:#94a3b8;}
.cp-summary-item.done .cp-summary-info .cp-summary-status{color:#059669;}
.cp-tip{margin:12px 20px;background:#eff6ff;border-radius:10px;padding:14px 16px;display:flex;gap:10px;align-items:flex-start;}
.cp-tip svg{width:16px;height:16px;stroke:#2563eb;fill:none;stroke-width:2;flex-shrink:0;margin-top:1px;}
.cp-tip p{font-size:12px;color:#1e40af;line-height:1.5;}
.cp-tip p strong{display:block;font-weight:700;margin-bottom:2px;}

/* ── Buttons ── */
.cp-actions{display:flex;justify-content:space-between;align-items:center;padding:20px 24px;border-top:1px solid #f1f5f9;gap:12px;}
.cp-btn{padding:11px 22px;border-radius:9px;font-size:14px;font-weight:600;cursor:pointer;border:none;display:inline-flex;align-items:center;gap:8px;transition:all .2s;font-family:inherit;text-decoration:none;}
.cp-btn-ghost{background:transparent;color:#64748b;border:1.5px solid #e5e7eb;}
.cp-btn-ghost:hover{background:#f8fafc;color:#1e293b;}
.cp-btn-primary{background:#2563eb;color:#fff;}
.cp-btn-primary:hover{background:#1d4ed8;}
.cp-btn-primary:disabled{background:#93c5fd;cursor:not-allowed;}

/* ── Success page ── */
.cp-success{text-align:center;padding:60px 24px;}
.cp-success-icon{width:72px;height:72px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;}
.cp-success-icon svg{width:36px;height:36px;stroke:#059669;fill:none;stroke-width:2.5;}
.cp-success h2{font-size:24px;font-weight:800;color:#0f172a;margin-bottom:8px;}
.cp-success p{font-size:14px;color:#64748b;max-width:400px;margin:0 auto 24px;}
.cp-success .cp-btn-primary{margin:0 auto;}

/* ── Footer ── */
.cp-footer{background:#fff;border-top:1px solid #e5e7eb;padding:32px 40px;margin-top:48px;}
.cp-footer-inner{max-width:1100px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr;gap:24px;}
.cp-footer-brand-name{font-size:16px;font-weight:800;color:#1e293b;margin-bottom:6px;display:flex;align-items:center;gap:8px;}
.cp-footer-brand-name .dot{width:28px;height:28px;background:#2563eb;border-radius:7px;display:flex;align-items:center;justify-content:center;}
.cp-footer-brand-name .dot span{color:#fff;font-size:14px;font-weight:900;font-family:Georgia,serif;}
.cp-footer-desc{font-size:12px;color:#64748b;line-height:1.6;margin-bottom:12px;}
.cp-footer-social{display:flex;gap:8px;}
.cp-footer-social a{width:28px;height:28px;border:1px solid #e5e7eb;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#64748b;font-size:11px;font-weight:700;text-decoration:none;transition:all .15s;}
.cp-footer-social a:hover{background:#2563eb;color:#fff;border-color:#2563eb;}
.cp-footer-col h4{font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin-bottom:10px;}
.cp-footer-col a{display:block;font-size:13px;color:#475569;text-decoration:none;margin-bottom:6px;}
.cp-footer-col a:hover{color:#2563eb;}
.cp-footer-bottom{max-width:1100px;margin:20px auto 0;padding-top:16px;border-top:1px solid #f1f5f9;display:flex;justify-content:space-between;font-size:11px;color:#94a3b8;}
.cp-footer-bottom a{color:#94a3b8;text-decoration:none;}
.cp-footer-bottom a:hover{color:#2563eb;}

@media(max-width:768px){
  .cp-form-grid{grid-template-columns:1fr;}
  .cp-layout{flex-direction:column;}
  .cp-sidebar{width:100%;position:static;}
  .cp-steps .cp-step-label{display:none;}
  .cp-nav{padding:0 16px;}
  .cp-page{padding:20px 16px 40px;}
}
</style>
</head>
<body>

<!-- ── Loader ── -->
<div id="pageLoader">
  <div class="loader-logo">
    <div class="loader-logo-box"><img src="<?php echo $imgBase; ?>Neutaralogo.jpg" alt="Neutara ATS" /></div>
    <div>
      <div class="loader-brand">Neutara ATS</div>
      <div class="loader-brand"><small>Applicant Tracking System</small></div>
    </div>
  </div>
  <div class="loader-bar"><div class="loader-bar-fill"></div></div>
</div>

<!-- ── Navbar ── -->
<nav class="cp-nav">
  <a href="<?php global $careerPage; echo (isset($careerPage)&&$careerPage)?'../index.php?m=careers&p=showAll':'index.php?m=careers&p=showAll'; ?>" class="cp-nav-brand">
    <div class="cp-nav-logo"><img src="<?php echo $imgBase; ?>Neutaralogo.jpg" alt="Neutara ATS" /></div>
    <div class="cp-nav-name">Neutara Careers<small>Applicant Tracking System</small></div>
  </a>
  <div class="cp-nav-links">
    <a href="<?php echo (isset($careerPage)&&$careerPage)?'../index.php?m=careers&p=showAll':'index.php?m=careers&p=showAll'; ?>">Positions</a>
    <a href="<?php echo (isset($careerPage)&&$careerPage)?'../index.php?m=careers&p=showAll':'index.php?m=careers&p=showAll'; ?>" class="btn-explore">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:inline;vertical-align:middle;margin-right:4px;"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      Explore Jobs
    </a>
  </div>
</nav>

<!-- ── Page content ── -->
<div class="cp-page">
  <?php echo($this->template['Content']); ?>
</div>

<!-- ── Footer ── -->
<footer class="cp-footer">
  <div class="cp-footer-inner">
    <div>
      <div class="cp-footer-brand-name">
        <div class="dot"><span>N</span></div>
        Neutara Careers
      </div>
      <p class="cp-footer-desc">Building the future with exceptional talent. We are committed to creating an inclusive workplace where innovation thrives.</p>
      <div class="cp-footer-social">
        <a href="#">in</a><a href="#">𝕏</a><a href="#">ig</a><a href="#">f</a>
      </div>
    </div>
    <div class="cp-footer-col">
      <h4>Careers</h4>
      <a href="<?php echo (isset($careerPage)&&$careerPage)?'../index.php?m=careers&p=showAll':'index.php?m=careers&p=showAll'; ?>">Open Positions</a>
      <a href="<?php echo (isset($careerPage)&&$careerPage)?'../index.php?m=careers&p=showAll':'index.php?m=careers&p=showAll'; ?>">Departments</a>
    </div>
    <div class="cp-footer-col">
      <h4>Company</h4>
      <a href="#">About Us</a>
      <a href="#">Culture</a>
    </div>
  </div>
  <div class="cp-footer-bottom">
    <span>&copy; <?php echo date('Y'); ?> Neutara. All rights reserved.</span>
    <span><a href="#">Privacy</a> &nbsp; <a href="#">Terms</a></span>
  </div>
</footer>

<script>
// Hide loader when page is ready
window.addEventListener('load', function() {
  var loader = document.getElementById('pageLoader');
  if (loader) { loader.classList.add('hide'); setTimeout(function(){ loader.style.display='none'; }, 450); }
});

// Drag-and-drop on upload zones
document.querySelectorAll('.cp-upload-zone').forEach(function(zone) {
  zone.addEventListener('dragover', function(e){ e.preventDefault(); zone.classList.add('drag-over'); });
  zone.addEventListener('dragleave', function(){ zone.classList.remove('drag-over'); });
  zone.addEventListener('drop', function(e){
    e.preventDefault(); zone.classList.remove('drag-over');
    var input = zone.querySelector('input[type=file]');
    if (input && e.dataTransfer.files.length) {
      input.files = e.dataTransfer.files;
      input.dispatchEvent(new Event('change'));
    }
  });
});

// Show selected file name
function handleFileSelect(input, displayId) {
  var display = document.getElementById(displayId);
  if (!display) return;
  if (input.files && input.files[0]) {
    display.style.display = 'flex';
    display.querySelector('span').textContent = input.files[0].name;
  }
}

// Progress tracking
function updateProgress() {
  var fields = document.querySelectorAll('.cp-input[required], .cp-select[required], .cp-textarea[required]');
  var filled = 0;
  fields.forEach(function(f){ if(f.value.trim()) filled++; });
  var pct = fields.length ? Math.round((filled/fields.length)*100) : 0;
  var bar = document.getElementById('progressFill');
  var label = document.getElementById('progressLabel');
  if (bar) bar.style.width = pct + '%';
  if (label) label.textContent = pct + '% complete';

  // Update summary dots
  updateSummaryDots();
}

function updateSummaryDots() {
  var sections = ['personalInfo','professionalBg','resumeDocs'];
  var items = document.querySelectorAll('.cp-summary-item');
  sections.forEach(function(id, i) {
    var sec = document.getElementById(id);
    if (!sec || !items[i]) return;
    var required = sec.querySelectorAll('[required]');
    var allFilled = Array.from(required).every(function(f){ return f.value.trim() !== ''; });
    items[i].className = 'cp-summary-item ' + (allFilled ? 'done' : '');
  });
}

document.addEventListener('input', updateProgress);
document.addEventListener('change', updateProgress);
</script>
<?php echo($this->template['CSS_SCRIPTS'] ?? ''); ?>
</body>
</html>
