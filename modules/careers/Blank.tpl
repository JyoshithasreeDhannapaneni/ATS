<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($this->siteName); ?> — Careers</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script src="../js/careerPortalApply.js"></script>
  <?php global $careerPage; if (isset($careerPage) && $careerPage == true): ?>
  <script src="../js/lib.js"></script><script src="../js/sorttable.js"></script><script src="../js/calendarDateInput.js"></script>
  <?php else: ?>
  <script src="js/lib.js"></script><script src="js/sorttable.js"></script><script src="js/calendarDateInput.js"></script><script src="js/careersPage.js"></script>
  <?php endif; ?>
  <style>
  /* ── RESET ── */
  *,*::before,*::after{box-sizing:border-box;margin:0;padding:0;}
  html{scroll-behavior:smooth;}
  body{font-family:'Inter',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;background:#f4f6fb;color:#111827;-webkit-font-smoothing:antialiased;font-size:15px;line-height:1.6;}

  /* ── VARIABLES ── */
  :root{
    --blue:#2563eb;--blue-d:#1d4ed8;--blue-bg:#eff6ff;--blue-border:#bfdbfe;
    --dark:#0d1b3e;--dark2:#1a2f5e;
    --text:#111827;--text2:#4b5563;--text3:#9ca3af;
    --border:#e5e7eb;--bg:#f4f6fb;--white:#ffffff;
    --shadow:0 2px 8px rgba(0,0,0,.07),0 1px 2px rgba(0,0,0,.05);
    --shadow-md:0 4px 16px rgba(0,0,0,.1),0 2px 4px rgba(0,0,0,.06);
    --radius:14px;
  }

  /* ── NAV ── */
  .nav{
    position:sticky;top:0;z-index:999;
    background:rgba(255,255,255,.97);
    border-bottom:1px solid var(--border);
    height:62px;padding:0 48px;
    display:flex;align-items:center;justify-content:space-between;
    box-shadow:0 1px 0 var(--border);
  }
  .nav-brand{display:flex;align-items:center;gap:10px;text-decoration:none;}
  .nav-logo{width:36px;height:36px;background:var(--blue);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 2px 8px rgba(37,99,235,.4);}
  .nav-logo span{color:#fff;font-size:17px;font-weight:900;line-height:1;}
  .nav-title{font-size:15px;font-weight:800;color:var(--text);}
  .nav-sep{color:var(--border);font-size:18px;margin:0 2px;}
  .nav-sub{font-size:14px;font-weight:600;color:var(--blue);}
  .nav-actions{display:flex;align-items:center;gap:10px;}
  .nav-link{font-size:13px;font-weight:600;color:var(--text2);text-decoration:none;padding:7px 14px;border-radius:8px;transition:all .15s;}
  .nav-link:hover{color:var(--blue);background:var(--blue-bg);}
  .nav-cta{background:var(--blue);color:#fff;font-size:13px;font-weight:700;text-decoration:none;padding:9px 20px;border-radius:9px;box-shadow:0 2px 8px rgba(37,99,235,.3);transition:all .2s;}
  .nav-cta:hover{background:var(--blue-d);box-shadow:0 4px 14px rgba(37,99,235,.4);transform:translateY(-1px);}

  /* ── HERO ── */
  .hero{background:linear-gradient(150deg,#e8f0fe 0%,#eef4ff 50%,#e4edfc 100%);padding:80px 48px 72px;overflow:hidden;position:relative;}
  .hero-wrap{max-width:1140px;margin:0 auto;display:flex;align-items:center;gap:56px;}
  .hero-left{flex:1;min-width:0;}
  .hero-right{flex-shrink:0;width:420px;}
  .hero-chip{
    display:inline-flex;align-items:center;gap:8px;
    background:rgba(37,99,235,.1);border:1px solid rgba(37,99,235,.22);
    color:var(--blue);font-size:11px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;
    padding:5px 13px;border-radius:99px;margin-bottom:24px;
  }
  .hero-dot{width:7px;height:7px;background:#22c55e;border-radius:50%;animation:blink 2s infinite;}
  @keyframes blink{0%,100%{opacity:1}50%{opacity:.3}}
  .hero h1{font-size:clamp(32px,4.5vw,52px);font-weight:900;color:var(--text);line-height:1.1;letter-spacing:-.03em;margin-bottom:18px;}
  .hero h1 strong{color:var(--blue);}
  .hero-desc{font-size:16px;color:var(--text2);line-height:1.7;max-width:440px;margin-bottom:32px;}
  .hero-btns{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:44px;}
  .btn-primary{display:inline-block;background:var(--blue);color:#fff;font-size:14px;font-weight:700;text-decoration:none;padding:12px 26px;border-radius:10px;transition:all .2s;box-shadow:0 3px 12px rgba(37,99,235,.35);}
  .btn-primary:hover{background:var(--blue-d);transform:translateY(-1px);}
  .btn-outline{display:inline-block;background:var(--white);color:var(--text);font-size:14px;font-weight:600;text-decoration:none;padding:12px 22px;border-radius:10px;border:1.5px solid var(--border);transition:all .2s;}
  .btn-outline:hover{border-color:var(--blue);color:var(--blue);}
  .hero-stats{display:flex;gap:0;padding-top:32px;border-top:1px solid rgba(37,99,235,.15);}
  .hero-stat{padding:0 24px;text-align:center;border-right:1px solid rgba(37,99,235,.12);}
  .hero-stat:first-child{padding-left:0;}
  .hero-stat:last-child{border-right:none;}
  .stat-num{font-size:24px;font-weight:900;color:var(--text);letter-spacing:-.03em;line-height:1.2;}
  .stat-lbl{font-size:11px;color:var(--text3);font-weight:600;text-transform:uppercase;letter-spacing:.04em;margin-top:2px;}

  /* Hero illustration box */
  .hero-illus-box{
    background:linear-gradient(135deg,#dbeafe 0%,#eff6ff 60%,#e0e7ff 100%);
    border-radius:24px;padding:32px 24px;
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 8px 32px rgba(37,99,235,.12);
    min-height:260px;
  }

  /* ── SECTION SHELL ── */
  .sec{padding:80px 48px;}
  .sec-white{background:var(--white);}
  .sec-dark{background:linear-gradient(150deg,var(--dark) 0%,var(--dark2) 100%);}
  .sec-gray{background:var(--bg);}
  .wrap{max-width:1140px;margin:0 auto;}
  .sec-head{text-align:center;margin-bottom:48px;}
  .eyebrow{display:inline-flex;align-items:center;gap:6px;font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--blue);margin-bottom:10px;}
  .eyebrow-light{color:#93c5fd;}
  .sec-title{font-size:clamp(22px,3vw,34px);font-weight:900;color:var(--text);letter-spacing:-.03em;line-height:1.15;margin-bottom:12px;}
  .sec-title-light{color:#fff;}
  .sec-sub{font-size:15px;color:var(--text2);max-width:520px;margin:0 auto;line-height:1.7;}
  .sec-sub-light{color:rgba(255,255,255,.5);}

  /* ── PERKS GRID ── */
  .perks-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:20px;}
  .perk-card{background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);padding:28px 22px;text-align:center;transition:all .25s;cursor:default;}
  .perk-card:hover{border-color:var(--blue-border);box-shadow:0 6px 24px rgba(37,99,235,.1);transform:translateY(-3px);}
  .perk-icon{width:54px;height:54px;background:var(--blue-bg);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;}
  .perk-card h3{font-size:14px;font-weight:800;color:var(--text);margin-bottom:8px;}
  .perk-card p{font-size:13px;color:var(--text2);line-height:1.65;}

  /* ── PROCESS ── */
  .proc-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px;}
  .proc-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:var(--radius);padding:28px 20px;text-align:center;transition:background .2s;}
  .proc-card:hover{background:rgba(255,255,255,.1);}
  .proc-num{width:44px;height:44px;background:linear-gradient(135deg,var(--blue),#3b82f6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:900;color:#fff;margin:0 auto 16px;box-shadow:0 4px 14px rgba(37,99,235,.4);}
  .proc-ico{margin:0 auto 14px;color:rgba(255,255,255,.5);}
  .proc-card h4{font-size:14px;font-weight:800;color:#fff;margin-bottom:8px;letter-spacing:-.01em;}
  .proc-card p{font-size:12.5px;color:rgba(255,255,255,.45);line-height:1.65;}

  /* ── TESTIMONIALS ── */
  .testi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
  .testi-card{background:var(--white);border:1.5px solid var(--border);border-radius:var(--radius);padding:26px;}
  .stars{display:flex;gap:2px;margin-bottom:14px;}
  .stars span{color:#f59e0b;font-size:15px;}
  .testi-card blockquote{font-size:13.5px;color:var(--text2);line-height:1.7;margin-bottom:20px;font-style:italic;}
  .testi-author{display:flex;align-items:center;gap:12px;}
  .testi-avatar{width:38px;height:38px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:800;color:#fff;flex-shrink:0;}
  .testi-name{font-size:13px;font-weight:700;color:var(--text);}
  .testi-role{font-size:11px;color:var(--text3);}

  /* ── JOBS ── */
  .jobs-header{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;margin-bottom:24px;flex-wrap:wrap;}
  .jobs-header-left .eyebrow{display:block;margin-bottom:6px;}
  .jobs-header-left h2{font-size:28px;font-weight:900;color:var(--text);letter-spacing:-.03em;}
  .jobs-header-right{display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
  .badge-green{display:inline-flex;align-items:center;gap:6px;background:#dcfce7;color:#16a34a;font-size:12px;font-weight:700;padding:6px 14px;border-radius:99px;white-space:nowrap;}
  .badge-green-dot{width:6px;height:6px;background:#22c55e;border-radius:50%;animation:blink 2s infinite;}
  .btn-jobs{background:var(--blue);color:#fff;font-size:13px;font-weight:700;text-decoration:none;padding:9px 18px;border-radius:9px;white-space:nowrap;}
  .jobs-table-wrap{background:var(--white);border-radius:var(--radius);overflow:hidden;border:1.5px solid var(--border);box-shadow:var(--shadow);}
  .jobs-empty{background:var(--white);border:2px dashed var(--border);border-radius:var(--radius);padding:60px 40px;text-align:center;}
  .jobs-empty h3{font-size:16px;font-weight:700;color:var(--text2);margin-bottom:6px;}
  .jobs-empty p{font-size:13px;color:var(--text3);}

  /* Table overrides */
  table.sortable{width:100%;border-collapse:collapse;background:var(--white);}
  tr.rowHeading th{
    background:#f8fafc !important;color:var(--blue) !important;
    font-size:11px;font-weight:800;letter-spacing:.08em;text-transform:uppercase;
    padding:14px 20px !important;border:none !important;
    border-bottom:2px solid var(--border) !important;text-align:left;
  }
  tr.oddTableRow td,tr.evenTableRow td{
    padding:16px 20px !important;font-size:14px;
    color:var(--text);border:none !important;border-bottom:1px solid #f1f5f9 !important;
    background:var(--white) !important;
  }
  tr.oddTableRow:last-child td,tr.evenTableRow:last-child td{border-bottom:none !important;}
  tr.oddTableRow:hover td,tr.evenTableRow:hover td{background:#f0f6ff !important;}
  tr.oddTableRow td a,tr.evenTableRow td a{color:var(--blue);font-weight:700;text-decoration:none;}
  tr.oddTableRow td a:hover{text-decoration:underline;}

  /* ── CTA TWO CARDS ── */
  .cta-row{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
  .cta-card{background:var(--blue-bg);border:1.5px solid var(--blue-border);border-radius:20px;padding:36px 32px 36px 36px;display:flex;align-items:flex-start;gap:18px;position:relative;overflow:hidden;}
  .cta-icon{width:52px;height:52px;background:var(--blue);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .cta-body h3{font-size:17px;font-weight:800;color:var(--text);letter-spacing:-.02em;margin-bottom:8px;}
  .cta-body p{font-size:13.5px;color:var(--text2);line-height:1.65;margin-bottom:18px;max-width:260px;}
  .cta-btn{display:inline-flex;align-items:center;gap:6px;background:var(--blue);color:#fff;font-size:13px;font-weight:700;text-decoration:none;padding:10px 20px;border-radius:9px;transition:all .2s;box-shadow:0 2px 8px rgba(37,99,235,.25);}
  .cta-btn:hover{background:var(--blue-d);transform:translateY(-1px);}
  .cta-deco{position:absolute;right:20px;bottom:12px;opacity:.2;pointer-events:none;}

  /* ── FOOTER ── */
  .footer{background:var(--dark);padding:22px 48px;display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap;}
  .footer-brand{display:flex;align-items:center;gap:10px;}
  .footer-logo{width:30px;height:30px;background:var(--blue);border-radius:7px;display:flex;align-items:center;justify-content:center;}
  .footer-logo span{color:#fff;font-size:13px;font-weight:900;}
  .footer-site{font-size:13px;font-weight:700;color:rgba(255,255,255,.7);}
  .footer-tag{font-size:13px;color:rgba(255,255,255,.35);}
  .footer-copy{font-size:12px;color:rgba(255,255,255,.3);}
  .footer-link{font-size:13px;font-weight:600;color:rgba(255,255,255,.5);text-decoration:none;transition:color .15s;}
  .footer-link:hover{color:#fff;}

  /* ── INNER PAGES ── */
  .inner-page{max-width:1100px;margin:0 auto;padding:40px 24px 72px;}
  #careerContent h1{font-size:26px;font-weight:900;color:var(--text);letter-spacing:-.03em;margin-bottom:24px;}
  #detailsTable{width:100%;border-collapse:collapse;margin-bottom:20px;}
  #detailsTable td{padding:10px 14px;font-size:14px;color:var(--text2);border-bottom:1px solid var(--border);}
  #detailsTable td.detailsHeader{font-weight:700;color:var(--text);width:160px;}
  #descriptive{background:var(--white);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);margin:20px 0;}
  #descriptive p{line-height:1.8;color:var(--text2);font-size:14px;}
  #detailsTools{background:var(--white);border-radius:var(--radius);padding:24px;box-shadow:var(--shadow);margin:16px 0;}
  #detailsTools h2{font-size:15px;font-weight:700;color:var(--text);margin-bottom:12px;}
  #detailsTools a,#detailsTools ul li a{display:inline-flex;align-items:center;gap:7px;background:var(--blue);color:#fff;text-decoration:none;padding:11px 24px;border-radius:9px;font-size:14px;font-weight:700;transition:all .2s;}
  #detailsTools a img{display:none;}
  #detailsTools a:hover{background:var(--blue-d);}
  div.applyBoxLeft,div.applyBoxRight{background:var(--white);border-radius:var(--radius);padding:28px;box-shadow:var(--shadow);margin-bottom:20px;float:none!important;width:100%!important;height:auto!important;border:none!important;}
  div.applyBoxLeft div,div.applyBoxRight div{background:var(--bg);border-radius:8px;padding:10px 14px;border:none!important;margin-bottom:14px;}
  div.applyBoxLeft table,div.applyBoxRight table{width:100%;}
  div.applyBoxLeft td,div.applyBoxRight td{padding:7px 5px;font-size:13px;vertical-align:middle;}
  td.label{text-align:left!important;width:auto!important;color:var(--text2);font-weight:600;white-space:nowrap;padding-right:12px!important;}
  input.inputBoxName,input.inputBoxNormal{width:100%!important;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;outline:none;}
  input.inputBoxName:focus,input.inputBoxNormal:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(37,99,235,.1);}
  input.submitButton,input[type=submit]{background:var(--blue)!important;color:#fff;border:none;padding:11px 28px;border-radius:9px;font-size:14px;font-weight:700;cursor:pointer;width:auto!important;height:auto!important;font-family:inherit;}
  input.submitButton:hover{background:var(--blue-d)!important;}
  textarea{width:100%!important;border:1.5px solid var(--border);border-radius:8px;padding:9px 12px;font-size:13px;font-family:inherit;}
  select.inputBoxNormal{width:100%!important;padding:9px 12px;border:1.5px solid var(--border);border-radius:8px;font-size:13px;font-family:inherit;}

  /* ── RESPONSIVE ── */
  @media(max-width:1060px){
    .perks-grid{grid-template-columns:repeat(2,1fr);}
    .proc-grid{grid-template-columns:repeat(2,1fr);}
    .hero-right{width:340px;}
  }
  @media(max-width:820px){
    .hero-wrap{flex-direction:column;}
    .hero-right{width:100%;max-width:440px;margin:0 auto;}
    .testi-grid{grid-template-columns:repeat(2,1fr);}
    .cta-row{grid-template-columns:1fr;}
    .sec,.hero{padding-left:24px;padding-right:24px;}
    .nav,.footer{padding-left:24px;padding-right:24px;}
  }
  @media(max-width:560px){
    .perks-grid{grid-template-columns:1fr 1fr;}
    .proc-grid{grid-template-columns:1fr 1fr;}
    .testi-grid{grid-template-columns:1fr;}
    .hero-stats{flex-wrap:wrap;gap:16px;}
    .hero-stat{border-right:none;width:calc(50% - 8px);}
    .jobs-header{flex-direction:column;align-items:flex-start;}
  }
  </style>
<?php if (!empty($this->template['CSS'])): ?>
<style><?php echo $this->template['CSS']; ?></style>
<?php endif; ?>
</head>
<body>

<!-- ═══════ NAV ═══════ -->
<nav class="nav">
  <a class="nav-brand" href="index.php?m=careers">
    <div class="nav-logo">
      <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M4 18V4h2.8l8.4 10.2V4H18v14h-2.8L6.8 7.8V18H4z" fill="#fff"/>
        <g transform="translate(13.5,2.5)">
          <path d="M2.5 0 L3 2 L5 2.5 L3 3 L2.5 5 L2 3 L0 2.5 L2 2 Z" fill="rgba(255,255,255,0.7)" style="transform-origin:2.5px 2.5px"/>
        </g>
      </svg>
    </div>
    <div style="display:flex;flex-direction:column;line-height:1.1;">
      <span class="nav-title" style="font-size:13px;font-weight:900;letter-spacing:-0.02em;"><?php echo htmlspecialchars($this->siteName); ?></span>
      <span class="nav-sub" style="font-size:10px;font-weight:600;letter-spacing:0.05em;text-transform:uppercase;color:var(--blue);">Careers</span>
    </div>
  </a>
  <div class="nav-actions">
    <a class="nav-link" href="index.php?m=careers&p=showAll">All Jobs</a>
    <a class="nav-cta" href="#open-roles">View Open Roles</a>
  </div>
</nav>

<?php if ($this->template['_page'] === 'showAll' || $this->template['_page'] === ''): ?>

<!-- ═══════ HERO ═══════ -->
<section class="hero">
  <div class="hero-wrap">
    <div class="hero-left">
      <div class="hero-chip">
        <span class="hero-dot"></span>
        We&rsquo;re Hiring &mdash; Join Our Team
      </div>
      <h1>Build What<br>Matters <strong>Most</strong></h1>
      <p class="hero-desc">Join a world-class team pushing the boundaries of what&rsquo;s possible. Your talent, our mission &mdash; extraordinary outcomes, together.</p>
      <div class="hero-btns">
        <a class="btn-primary" href="#open-roles">Explore Opportunities</a>
        <a class="btn-outline" href="#why-us">Why <?php echo htmlspecialchars($this->siteName); ?> &rarr;</a>
      </div>
      <div class="hero-stats">
        <div class="hero-stat">
          <div class="stat-num"><?php echo max(1,(int)$this->template['_jobCount']); ?>+</div>
          <div class="stat-lbl">Open Roles</div>
        </div>
        <div class="hero-stat">
          <div class="stat-num">50+</div>
          <div class="stat-lbl">Team Members</div>
        </div>
        <div class="hero-stat">
          <div class="stat-num">4.8★</div>
          <div class="stat-lbl">Glassdoor</div>
        </div>
        <div class="hero-stat">
          <div class="stat-num">98%</div>
          <div class="stat-lbl">Satisfaction</div>
        </div>
      </div>
    </div>
    <div class="hero-right">
      <div class="hero-illus-box">
        <svg viewBox="0 0 400 280" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:380px;">
          <!-- Background shapes -->
          <circle cx="340" cy="60" r="90" fill="#bfdbfe" opacity=".45"/>
          <circle cx="70" cy="220" r="55" fill="#c7d2fe" opacity=".35"/>
          <!-- Ground shadow -->
          <ellipse cx="200" cy="262" rx="160" ry="12" fill="#93c5fd" opacity=".25"/>
          <!-- Left laptop desk -->
          <rect x="30" y="158" width="110" height="72" rx="8" fill="#dbeafe"/>
          <rect x="36" y="163" width="98" height="52" rx="5" fill="#93c5fd" opacity=".6"/>
          <rect x="42" y="168" width="86" height="40" rx="3" fill="#eff6ff"/>
          <rect x="48" y="173" width="68" height="5" rx="2.5" fill="#2563eb" opacity=".4"/>
          <rect x="48" y="183" width="50" height="4" rx="2" fill="#bfdbfe"/>
          <rect x="48" y="192" width="60" height="4" rx="2" fill="#bfdbfe"/>
          <!-- Left person -->
          <ellipse cx="85" cy="148" rx="18" ry="18" fill="#fde68a"/>
          <path d="M67 148 Q67 136 85 133 Q103 136 103 148" fill="#1d4ed8"/>
          <path d="M72 168 Q72 184 85 186 Q98 184 98 168" fill="#1d4ed8"/>
          <circle cx="80" cy="143" r="2.5" fill="#374151" opacity=".8"/>
          <circle cx="90" cy="143" r="2.5" fill="#374151" opacity=".8"/>
          <path d="M82 150 Q85 153 88 150" stroke="#d97706" stroke-width="1.5" fill="none" stroke-linecap="round"/>
          <!-- Center person standing -->
          <ellipse cx="200" cy="88" rx="20" ry="20" fill="#fed7aa"/>
          <path d="M180 86 Q180 74 200 71 Q220 74 220 86" fill="#2563eb"/>
          <path d="M186 110 Q186 136 200 138 Q214 136 214 110" fill="#2563eb"/>
          <!-- Arm pointing right to board -->
          <path d="M217 96 Q242 82 258 87" stroke="#2563eb" stroke-width="7" stroke-linecap="round"/>
          <!-- Arm left -->
          <path d="M183 98 Q176 111 180 120" stroke="#2563eb" stroke-width="7" stroke-linecap="round"/>
          <circle cx="195" cy="83" r="3" fill="#374151" opacity=".8"/>
          <circle cx="205" cy="83" r="3" fill="#374151" opacity=".8"/>
          <path d="M197 90 Q200 93 203 90" stroke="#c2410c" stroke-width="1.5" fill="none" stroke-linecap="round"/>
          <!-- Whiteboard -->
          <rect x="260" y="56" width="112" height="80" rx="7" fill="#fff" stroke="#bfdbfe" stroke-width="1.5"/>
          <rect x="267" y="63" width="98" height="64" rx="4" fill="#f0f6ff"/>
          <!-- Chart on whiteboard -->
          <polyline points="278,114 295,94 312,104 330,78 345,90" stroke="#2563eb" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="278" cy="114" r="3" fill="#2563eb"/>
          <circle cx="345" cy="90" r="3" fill="#2563eb"/>
          <rect x="278" y="66" width="86" height="4" rx="2" fill="#bfdbfe" opacity=".6"/>
          <!-- Check badge on board -->
          <circle cx="363" cy="60" r="14" fill="#22c55e"/>
          <path d="M356 60 L361 65 L371 53" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          <!-- Right laptop desk -->
          <rect x="264" y="172" width="106" height="68" rx="8" fill="#dbeafe"/>
          <rect x="270" y="177" width="94" height="48" rx="5" fill="#93c5fd" opacity=".6"/>
          <rect x="275" y="182" width="84" height="36" rx="3" fill="#eff6ff"/>
          <rect x="281" y="187" width="65" height="5" rx="2.5" fill="#2563eb" opacity=".4"/>
          <rect x="281" y="196" width="45" height="4" rx="2" fill="#bfdbfe"/>
          <!-- Right person -->
          <ellipse cx="317" cy="162" rx="18" ry="18" fill="#fdba74"/>
          <path d="M299 162 Q299 150 317 147 Q335 150 335 162" fill="#7c3aed"/>
          <path d="M304 180 Q304 196 317 198 Q330 196 330 180" fill="#7c3aed"/>
          <circle cx="312" cy="157" r="2.5" fill="#374151" opacity=".8"/>
          <circle cx="322" cy="157" r="2.5" fill="#374151" opacity=".8"/>
          <path d="M314 164 Q317 167 320 164" stroke="#c2410c" stroke-width="1.5" fill="none" stroke-linecap="round"/>
          <!-- Speech bubble -->
          <rect x="118" y="60" width="76" height="40" rx="9" fill="#fff" stroke="#bfdbfe" stroke-width="1.5"/>
          <path d="M140 100 L136 110 L150 100" fill="#fff" stroke="#bfdbfe" stroke-width="1.5" stroke-linejoin="round"/>
          <circle cx="135" cy="80" r="5" fill="#bfdbfe"/>
          <circle cx="149" cy="80" r="5" fill="#bfdbfe"/>
          <circle cx="163" cy="80" r="5" fill="#bfdbfe"/>
          <!-- Decorative plants -->
          <rect x="14" y="238" width="12" height="20" rx="3" fill="#a7f3d0"/>
          <ellipse cx="20" cy="233" rx="9" ry="11" fill="#34d399"/>
          <ellipse cx="14" cy="238" rx="6" ry="8" fill="#10b981"/>
          <rect x="374" y="240" width="12" height="18" rx="3" fill="#a7f3d0"/>
          <ellipse cx="380" cy="235" rx="9" ry="10" fill="#34d399"/>
        </svg>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ WHY JOIN US ═══════ -->
<section class="sec sec-white" id="why-us">
  <div class="wrap">
    <div class="sec-head">
      <div class="eyebrow">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
        Why Join Us
      </div>
      <h2 class="sec-title">Where Careers Reach Their Peak</h2>
      <p class="sec-sub">We invest in people first. Here&rsquo;s what makes <?php echo htmlspecialchars($this->siteName); ?> the place where great careers are built.</p>
    </div>
    <div class="perks-grid">
      <div class="perk-card">
        <div class="perk-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg></div>
        <h3>Continuous Learning</h3>
        <p>$5K annual learning budget, conference sponsorships, and access to premium education platforms for every team member.</p>
      </div>
      <div class="perk-card">
        <div class="perk-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
        <h3>Rapid Career Growth</h3>
        <p>Structured advancement paths, quarterly reviews, and dedicated 1-on-1 mentorship from senior leaders.</p>
      </div>
      <div class="perk-card">
        <div class="perk-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div>
        <h3>Remote First Culture</h3>
        <p>Work from anywhere. We believe great work happens when you have the freedom to choose your environment.</p>
      </div>
      <div class="perk-card">
        <div class="perk-icon"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
        <h3>Premium Benefits</h3>
        <p>Top-tier health coverage, equity packages, unlimited PTO, parental leave, and comprehensive family-friendly policies.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ HIRING PROCESS ═══════ -->
<section class="sec sec-dark">
  <div class="wrap">
    <div class="sec-head">
      <div class="eyebrow eyebrow-light">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        Our Hiring Process
      </div>
      <h2 class="sec-title sec-title-light">Simple. Transparent. Fast.</h2>
      <p class="sec-sub sec-sub-light">We respect your time. Our streamlined process takes just 2&ndash;3 weeks.</p>
    </div>
    <div class="proc-grid">
      <div class="proc-card">
        <div class="proc-num">1</div>
        <div class="proc-ico"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></div>
        <h4>Submit Application</h4>
        <p>Apply online in under 5 minutes. Upload your resume and tell us why you&rsquo;re excited about the role.</p>
      </div>
      <div class="proc-card">
        <div class="proc-num">2</div>
        <div class="proc-ico"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><rect x="2" y="3" width="20" height="14" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg></div>
        <h4>Skills Assessment</h4>
        <p>A take-home or live assessment designed to showcase your skills and thinking.</p>
      </div>
      <div class="proc-card">
        <div class="proc-num">3</div>
        <div class="proc-ico"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div>
        <h4>Team Interview</h4>
        <p>Meet the team you&rsquo;d work with. We focus on collaboration, communication, and culture fit.</p>
      </div>
      <div class="proc-card">
        <div class="proc-num">4</div>
        <div class="proc-ico"><svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
        <h4>Welcome Aboard</h4>
        <p>Receive your offer and start your journey. We&rsquo;ll make onboarding smooth and exceptional.</p>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ TESTIMONIALS ═══════ -->
<section class="sec sec-white">
  <div class="wrap">
    <div class="sec-head">
      <div class="eyebrow">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Our Team
      </div>
      <h2 class="sec-title">People Love Working Here</h2>
      <p class="sec-sub">Don&rsquo;t just take our word for it &mdash; hear from the team.</p>
    </div>
    <div class="testi-grid">
      <div class="testi-card">
        <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
        <blockquote>&ldquo;The growth opportunities here are unmatched. In 18 months I went from junior to leading a small squad.&rdquo;</blockquote>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#2563eb,#3b82f6)">A</div>
          <div><div class="testi-name">Aarav K.</div><div class="testi-role">Senior Engineer, 2 years</div></div>
        </div>
      </div>
      <div class="testi-card">
        <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
        <blockquote>&ldquo;I value the trust I get to do my job &mdash; the autonomy mixed with the culture. I&rsquo;ve never worked somewhere I felt so invested in personally.&rdquo;</blockquote>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#7c3aed,#6366f1)">M</div>
          <div><div class="testi-name">Maria S.</div><div class="testi-role">Product Designer, 3 years</div></div>
        </div>
      </div>
      <div class="testi-card">
        <div class="stars"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>
        <blockquote>&ldquo;The benefits package is genuinely exceptional. From health coverage to the work-life balance &mdash; it&rsquo;s a company that puts people first.&rdquo;</blockquote>
        <div class="testi-author">
          <div class="testi-avatar" style="background:linear-gradient(135deg,#0ea5e9,#2563eb)">J</div>
          <div><div class="testi-name">James R.</div><div class="testi-role">Marketing Lead, 1.5 years</div></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ═══════ OPEN ROLES ═══════ -->
<section class="sec sec-gray" id="open-roles">
  <div class="wrap">
    <div class="jobs-header">
      <div class="jobs-header-left">
        <div class="eyebrow">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
          Open Roles
        </div>
        <h2>Find Your Perfect Role</h2>
      </div>
      <div class="jobs-header-right">
        <div class="badge-green"><span class="badge-green-dot"></span>+ <?php echo (int)$this->template['_jobCount']; ?> position<?php echo (int)$this->template['_jobCount'] !== 1 ? 's' : ''; ?> available</div>
        <a class="btn-jobs" href="#open-roles">View Open Roles</a>
      </div>
    </div>
    <?php if ((int)$this->template['_jobCount'] === 0): ?>
    <div class="jobs-empty">
      <h3>No open positions right now</h3>
      <p>We&rsquo;re always growing &mdash; check back soon or send us your resume.</p>
    </div>
    <?php else: ?>
    <div class="jobs-table-wrap"><?php echo($this->template['Content']); ?></div>
    <?php endif; ?>
  </div>
</section>

<!-- ═══════ CTA TWO CARDS ═══════ -->
<section class="sec sec-white" style="padding-top:0;">
  <div class="wrap">
    <div class="cta-row">
      <div class="cta-card">
        <div class="cta-icon">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
        </div>
        <div class="cta-body">
          <h3>Don&rsquo;t See Your Dream Role?</h3>
          <p>We&rsquo;re always looking for exceptional talent. Send us your resume and we&rsquo;ll reach out when the right opportunity opens.</p>
          <a class="cta-btn" href="mailto:careers@<?php echo htmlspecialchars(strtolower(str_replace(' ', '', $this->siteName))); ?>.com">Stay Connected &rarr;</a>
        </div>
        <div class="cta-deco">
          <svg width="90" height="80" viewBox="0 0 90 80" fill="none"><rect x="8" y="22" width="58" height="42" rx="6" fill="#bfdbfe" stroke="#2563eb" stroke-width="1.5"/><path d="M8 28 L37 50 L66 28" stroke="#2563eb" stroke-width="1.5"/><line x1="50" y1="38" x2="78" y2="12" stroke="#2563eb" stroke-width="1.5" stroke-dasharray="4 3"/><circle cx="78" cy="10" r="10" fill="#2563eb"/><path d="M73 10 L77 14 L84 6" stroke="#fff" stroke-width="1.5" stroke-linecap="round"/></svg>
        </div>
      </div>
      <div class="cta-card" style="background:#f5f3ff;border-color:#ddd6fe;">
        <div class="cta-icon" style="background:#7c3aed;">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="cta-body">
          <h3>Have Questions?</h3>
          <p>Our team is here to help you understand our culture, roles, and hiring process.</p>
          <a class="cta-btn" style="background:#7c3aed;" href="mailto:hr@<?php echo htmlspecialchars(strtolower(str_replace(' ', '', $this->siteName))); ?>.com">Get in Touch &rarr;</a>
        </div>
        <div class="cta-deco">
          <svg width="90" height="80" viewBox="0 0 90 80" fill="none"><circle cx="45" cy="36" r="28" fill="#ede9fe" stroke="#7c3aed" stroke-width="1.5"/><path d="M33 32 Q33 22 45 22 Q57 22 57 32 Q57 42 49 45 L49 52" stroke="#7c3aed" stroke-width="2" stroke-linecap="round" fill="none"/><circle cx="49" cy="57" r="2.5" fill="#7c3aed"/></svg>
        </div>
      </div>
    </div>
  </div>
</section>

<?php else: ?>
<!-- ═══════ INNER PAGE ═══════ -->
<div class="inner-page">
  <?php echo($this->template['Content']); ?>
</div>
<?php endif; ?>

<!-- ═══════ FOOTER ═══════ -->
<footer class="footer">
  <div class="footer-brand">
    <div class="footer-logo"><span>N</span></div>
    <span class="footer-site"><?php echo htmlspecialchars($this->siteName); ?></span>
    <span class="footer-tag">Careers</span>
  </div>
  <span class="footer-copy">&copy; <?php echo date('Y'); ?> <?php echo htmlspecialchars($this->siteName); ?> &mdash; All Rights Reserved</span>
  <a class="footer-link" href="index.php?m=careers&p=showAll">All Jobs</a>
</footer>

<script>
// Scroll-in animation
(function(){
  if(!('IntersectionObserver' in window)) return;
  var targets = document.querySelectorAll('.perk-card,.proc-card,.testi-card,.cta-card');
  targets.forEach(function(el,i){
    el.style.cssText += 'opacity:0;transform:translateY(20px);transition:opacity .5s ease '+(i*0.07)+'s,transform .5s ease '+(i*0.07)+'s;';
  });
  var obs = new IntersectionObserver(function(entries){
    entries.forEach(function(e){
      if(e.isIntersecting){ e.target.style.opacity='1'; e.target.style.transform='translateY(0)'; obs.unobserve(e.target); }
    });
  },{threshold:0.1});
  targets.forEach(function(el){ obs.observe(el); });
})();
</script>
<script>st_init();</script>
</body>
</html>
