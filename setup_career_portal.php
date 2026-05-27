<?php
/**
 * Setup Career Portal with professional template
 * Uses direct PDO to avoid DatabaseConnection query translation issues
 */

$dsn = 'pgsql:host=localhost;port=5432;dbname=cats_dev';
$pdo = new PDO($dsn, 'postgres', 'Joshi@515', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
]);

// Step 1: Set active board to "Blank Page"
$pdo->exec("DELETE FROM settings WHERE setting = 'activeBoard'");
$pdo->exec("INSERT INTO settings (setting, value, site_id) VALUES ('activeBoard', 'Blank Page', 1)");
echo "activeBoard set to 'Blank Page'\n";

// Template sections
$sections = [];

$sections['CSS'] = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
:root {
    --primary: #2563eb; --primary-dark: #1d4ed8; --primary-light: #dbeafe;
    --primary-50: #eff6ff; --accent: #0ea5e9; --success: #059669;
    --gray-50: #f9fafb; --gray-100: #f3f4f6; --gray-200: #e5e7eb;
    --gray-300: #d1d5db; --gray-400: #9ca3af; --gray-500: #6b7280;
    --gray-600: #4b5563; --gray-700: #374151; --gray-800: #1f2937; --gray-900: #111827;
    --font: 'Inter','Segoe UI',system-ui,-apple-system,sans-serif;
    --radius: 8px; --radius-lg: 16px;
    --shadow: 0 1px 3px rgba(0,0,0,0.1); --shadow-md: 0 4px 6px rgba(0,0,0,0.07);
    --shadow-lg: 0 10px 25px rgba(0,0,0,0.08);
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
body,html{font-family:var(--font);color:var(--gray-800);background:#f8fafc;line-height:1.6;-webkit-font-smoothing:antialiased}
body{display:flex;flex-direction:column;min-height:100vh}
a{color:var(--primary);text-decoration:none;transition:color .2s}
a:hover{color:var(--primary-dark)} a:visited{color:var(--primary)}

.career-header{background:#fff;border-bottom:1px solid var(--gray-200);position:sticky;top:0;z-index:100;box-shadow:0 1px 3px rgba(0,0,0,0.05)}
.header-inner{max-width:1200px;margin:0 auto;padding:16px 24px;display:flex;align-items:center;justify-content:space-between}
.brand{display:flex;align-items:center;gap:12px;text-decoration:none}
.brand-icon{width:40px;height:40px;background:linear-gradient(135deg,var(--primary),var(--accent));border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:18px}
.brand-text{font-size:20px;font-weight:700;color:var(--gray-900);letter-spacing:-.02em}
.brand-text span{color:var(--primary)}
.header-nav{display:flex;align-items:center;gap:24px}
.header-nav a{font-size:14px;font-weight:500;color:var(--gray-600);padding:8px 0}
.header-nav a:hover{color:var(--primary)}
.nav-btn{display:inline-flex;align-items:center;gap:6px;padding:9px 20px;background:var(--primary);color:#fff !important;border-radius:var(--radius);font-weight:600;font-size:14px;transition:all .2s;box-shadow:0 1px 2px rgba(37,99,235,0.3)}
.nav-btn:hover{background:var(--primary-dark);transform:translateY(-1px);box-shadow:0 4px 12px rgba(37,99,235,0.3)}

.hero{background:linear-gradient(135deg,#1e3a5f 0%,#1e40af 50%,#2563eb 100%);color:#fff;padding:72px 24px;text-align:center;position:relative;overflow:hidden}
.hero::before{content:'';position:absolute;inset:0;background:url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")}
.hero-inner{max-width:700px;margin:0 auto;position:relative;z-index:1}
.hero h1{font-size:42px;font-weight:800;letter-spacing:-.03em;margin-bottom:16px;line-height:1.2;color:#fff;background:none;-webkit-text-fill-color:#fff}
.hero p{font-size:18px;opacity:.9;margin-bottom:32px;line-height:1.6;color:#fff}
.stats-bar{display:flex;gap:16px;justify-content:center;margin-top:32px}
.stat-item{background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.2);border-radius:var(--radius);padding:12px 24px;text-align:center}
.stat-num{font-size:24px;font-weight:700;display:block;color:#fff}
.stat-label{font-size:12px;opacity:.8;text-transform:uppercase;letter-spacing:.05em;color:#fff}

.main-content{max-width:1200px;margin:0 auto;padding:48px 24px;flex:1;width:100%}
.section-title{font-size:28px;font-weight:700;color:var(--gray-900);margin-bottom:8px;letter-spacing:-.02em}
.section-subtitle{font-size:16px;color:var(--gray-500);margin-bottom:32px}

table.sortable{width:100%;border-collapse:separate;border-spacing:0;background:#fff;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg)}
tr.rowHeading{background:linear-gradient(135deg,var(--primary),var(--primary-dark))}
tr.rowHeading th{padding:16px 20px;color:#fff;font-weight:600;font-size:13px;text-transform:uppercase;letter-spacing:.05em;border:none;text-align:left}
tr.evenTableRow,tr.oddTableRow{transition:all .15s}
tr.evenTableRow{background:#fff} tr.oddTableRow{background:var(--gray-50)}
tr.evenTableRow:hover,tr.oddTableRow:hover{background:var(--primary-50)}
tr.evenTableRow td,tr.oddTableRow td{padding:16px 20px;border-bottom:1px solid var(--gray-100);font-size:14px;color:var(--gray-700)}
tr.evenTableRow:last-child td,tr.oddTableRow:last-child td{border-bottom:none}
tr.evenTableRow td a,tr.oddTableRow td a{color:var(--primary);font-weight:600}
tr.evenTableRow td a:hover,tr.oddTableRow td a:hover{color:var(--primary-dark);text-decoration:underline}

#detailsTable{width:100%;border-collapse:collapse;background:#fff;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-md);margin:24px 0}
#detailsTable td.detailsHeader{width:180px;font-weight:600;color:var(--primary);padding:14px 20px;background:var(--primary-50);border:1px solid var(--gray-100);font-size:13px;text-transform:uppercase;letter-spacing:.03em}
#detailsTable td{padding:14px 20px;border:1px solid var(--gray-100);color:var(--gray-700);font-size:14px}
div#discriptive{float:left;width:calc(68% - 16px);margin-right:32px}
div#detailsTools{float:right;width:28%;padding:24px;background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius-lg);box-shadow:var(--shadow-md)}
div#detailsTools img{display:none}

div.applyBoxLeft,div.applyBoxRight{width:100%;max-width:580px;background:#fff;border:1px solid var(--gray-200);border-radius:var(--radius-lg);padding:32px;box-shadow:var(--shadow-md);margin-bottom:24px}
div.applyBoxLeft{float:left;margin-right:32px} div.applyBoxRight{float:right}
div.applyBoxLeft div,div.applyBoxRight div{margin:0 0 20px;padding:14px 20px;background:var(--primary-50);border:1px solid var(--primary-light);border-radius:var(--radius);font-weight:600;color:var(--primary);font-size:15px}
div.applyBoxLeft table td,div.applyBoxRight table td{padding:10px 8px;vertical-align:top;border-bottom:1px solid var(--gray-100)}
td.label{text-align:right;width:30%;font-weight:600;color:var(--gray-600);font-size:13px}
label{font-weight:600;color:var(--gray-700);display:block;margin-bottom:6px;font-size:14px}

input.inputbox,input.inputBoxName,input.inputBoxNormal,input.inputBoxArea,input.inputBoxFile,input#documentFile,input[type="text"],input[type="email"],input[type="tel"]{width:100%;padding:10px 14px;border:1.5px solid var(--gray-200);border-radius:var(--radius);font:14px var(--font);color:var(--gray-800);transition:all .2s;background:#fff}
input.inputbox:focus,input.inputBoxName:focus,input.inputBoxNormal:focus,input#documentFile:focus,input[type="text"]:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,0.1)}

textarea,textarea.inputBoxArea,textarea.inputboxlarge{width:100%;padding:10px 14px;min-height:100px;border:1.5px solid var(--gray-200);border-radius:var(--radius);font:14px var(--font);color:var(--gray-800);resize:vertical;transition:all .2s}
textarea:focus{outline:none;border-color:var(--primary);box-shadow:0 0 0 3px rgba(37,99,235,0.1)}

input.submitbutton,input.submitButton,input[type="submit"]{padding:12px 28px;width:auto;min-width:200px;background:var(--primary);color:#fff;font:600 15px var(--font);border:none;border-radius:var(--radius);cursor:pointer;transition:all .2s;box-shadow:0 2px 8px rgba(37,99,235,0.3);margin-top:16px}
input.submitbutton:hover,input.submitButton:hover,input[type="submit"]:hover{background:var(--primary-dark);transform:translateY(-1px);box-shadow:0 4px 16px rgba(37,99,235,0.35)}

.career-footer{background:var(--gray-900);color:var(--gray-400);padding:48px 24px 24px;margin-top:auto}
.footer-inner{max-width:1200px;margin:0 auto;display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:32px}
.footer-brand .brand-text{color:#fff;font-size:18px} .footer-brand .brand-text span{color:var(--accent)}
.footer-brand p{font-size:14px;margin-top:8px;color:var(--gray-400);max-width:300px}
.footer-links{display:flex;gap:48px}
.footer-links div h4{color:#fff;font-size:14px;font-weight:600;margin-bottom:12px;text-transform:uppercase;letter-spacing:.05em}
.footer-links div a{display:block;font-size:14px;color:var(--gray-400);padding:4px 0} .footer-links div a:hover{color:#fff}
.footer-bottom{max-width:1200px;margin:32px auto 0;padding-top:24px;border-top:1px solid rgba(255,255,255,0.1);text-align:center;font-size:13px}

@media(max-width:768px){
    .header-inner{flex-direction:column;gap:12px;text-align:center}
    .header-nav{flex-wrap:wrap;justify-content:center}
    .hero{padding:48px 16px} .hero h1{font-size:28px} .hero p{font-size:15px}
    .stats-bar{flex-direction:column;align-items:center}
    .main-content{padding:24px 16px}
    div#discriptive{float:none;width:100%;margin:0 0 24px}
    div#detailsTools{float:none;width:100%}
    div.applyBoxLeft,div.applyBoxRight{float:none;width:100%;max-width:100%;margin-right:0}
    .footer-inner{flex-direction:column;text-align:center}
    .footer-links{flex-direction:column;gap:24px}
}

h1{font:700 28px var(--font);color:var(--gray-900);letter-spacing:-.02em;margin:0 0 16px;background:none;-webkit-text-fill-color:var(--gray-900)}
h2{font:700 22px var(--font);color:var(--gray-800);margin:24px 0 12px;border:none;padding:0}
h3{font:600 18px var(--font);color:var(--gray-700);margin:16px 0 8px}
p{font:400 14px/1.7 var(--font);color:var(--gray-600)}
strong{font-weight:600;color:var(--gray-800)} img{border:none}
#careerContent{clear:both;padding:24px 0;flex:1} #poweredCATS{display:none}
.clearfix::after{content:'';display:table;clear:both}
CSS;

$sections['Header'] = <<<'HTML'
<div class="career-header">
    <div class="header-inner">
        <a href="index.php?m=careers" class="brand">
            <div class="brand-icon">N</div>
            <div class="brand-text">Neutara <span>Careers</span></div>
        </a>
        <nav class="header-nav">
            <a href="index.php?m=careers">Home</a>
            <a href="index.php?m=careers&p=showAll">All Positions</a>
            <a href="index.php?m=careers&p=showAll" class="nav-btn">Browse Jobs</a>
        </nav>
    </div>
</div>
HTML;

$sections['Content - Main'] = <<<'HTML'
<div class="hero">
    <div class="hero-inner">
        <h1>Build Your Career With Us</h1>
        <p>Join our team of talented professionals. We offer exciting opportunities, competitive benefits, and a culture that values innovation and growth.</p>
        <a href="index.php?m=careers&p=showAll" class="nav-btn" style="display:inline-flex;padding:14px 32px;font-size:16px;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,0.2);">View Open Positions &rarr;</a>
        <div class="stats-bar">
            <div class="stat-item"><span class="stat-num">100%</span><span class="stat-label">Remote Friendly</span></div>
            <div class="stat-item"><span class="stat-num">Global</span><span class="stat-label">Team</span></div>
        </div>
    </div>
</div>
<div class="main-content">
    <h2 class="section-title">Why Join Us?</h2>
    <p class="section-subtitle">We believe in empowering our team to do their best work.</p>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:24px;margin-bottom:48px;">
        <div style="background:#fff;padding:28px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);border:1px solid #f3f4f6;">
            <div style="width:48px;height:48px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:24px;">&#128640;</div>
            <h3 style="margin:0 0 8px;font-size:17px;">Growth & Learning</h3>
            <p style="margin:0;font-size:14px;">Continuous learning opportunities, mentorship programs, and career development paths.</p>
        </div>
        <div style="background:#fff;padding:28px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);border:1px solid #f3f4f6;">
            <div style="width:48px;height:48px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:24px;">&#129309;</div>
            <h3 style="margin:0 0 8px;font-size:17px;">Great Benefits</h3>
            <p style="margin:0;font-size:14px;">Competitive salary, health insurance, flexible work arrangements, and more.</p>
        </div>
        <div style="background:#fff;padding:28px;border-radius:16px;box-shadow:0 4px 6px rgba(0,0,0,0.07);border:1px solid #f3f4f6;">
            <div style="width:48px;height:48px;background:#eff6ff;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:16px;font-size:24px;">&#127758;</div>
            <h3 style="margin:0 0 8px;font-size:17px;">Inclusive Culture</h3>
            <p style="margin:0;font-size:14px;">A diverse, collaborative environment where every voice is heard and valued.</p>
        </div>
    </div>
    <div style="text-align:center;padding:32px 0;">
        <a href="index.php?m=careers&p=showAll" class="nav-btn" style="display:inline-flex;padding:14px 32px;font-size:16px;border-radius:10px;">Explore Open Positions &rarr;</a>
    </div>
</div>
HTML;

$sections['Content - Search Results'] = <<<'HTML'
<div class="main-content">
    <h2 class="section-title">Open Positions</h2>
    <p class="section-subtitle"><numberOfSearchResults> position(s) currently available</p>
    <searchResultsTableUnformatted>
</div>
HTML;

$sections['Content - Job Details'] = <<<'HTML'
<div class="main-content">
    <div style="margin-bottom:24px;">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:#6b7280;display:inline-flex;align-items:center;gap:4px;">&larr; Back to all positions</a>
    </div>
    <h1 style="font-size:32px;margin-bottom:4px;"><title></h1>
    <p style="font-size:16px;color:#6b7280;margin-bottom:32px;"><city>, <state> &bull; <type></p>
    <div class="clearfix">
        <div id="discriptive">
            <h2>Job Description</h2>
            <div style="color:#374151;line-height:1.8;font-size:15px;"><description></div>
            <table id="detailsTable" style="margin-top:32px;">
                <tr><td class="detailsHeader">Location</td><td><city>, <state></td></tr>
                <tr><td class="detailsHeader">Type</td><td><type></td></tr>
                <tr><td class="detailsHeader">Openings</td><td><openings></td></tr>
                <tr><td class="detailsHeader">Date Posted</td><td><created></td></tr>
                <tr><td class="detailsHeader">Recruiter</td><td><recruiter></td></tr>
            </table>
        </div>
        <div id="detailsTools">
            <h3 style="margin:0 0 16px;font-size:18px;color:#111827;">Interested?</h3>
            <p style="margin:0 0 20px;font-size:14px;color:#6b7280;">Submit your application to be considered for this role.</p>
            <a-applyToJob style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#2563eb;color:#fff;border-radius:8px;font-weight:600;font-size:15px;text-decoration:none;width:100%;justify-content:center;">Apply Now &rarr;</a>
        </div>
    </div>
</div>
HTML;

$sections['Content - Apply for Position'] = <<<'HTML'
<div class="main-content">
    <div style="margin-bottom:24px;">
        <a href="index.php?m=careers&p=showAll" style="font-size:14px;color:#6b7280;">&larr; Back to all positions</a>
    </div>
    <h1>Apply for: <title></h1>
    <p style="margin-bottom:32px;color:#6b7280;">Please fill out the form below to submit your application.</p>
    <div class="clearfix">
        <div class="applyBoxLeft">
            <div>Personal Information</div>
            <table>
                <tr><td class="label">First Name *</td><td><input-firstName req></td></tr>
                <tr><td class="label">Last Name *</td><td><input-lastName req></td></tr>
                <tr><td class="label">Email *</td><td><input-email req></td></tr>
                <tr><td class="label">Confirm Email *</td><td><input-emailconfirm req></td></tr>
                <tr><td class="label">Phone *</td><td><input-phone req></td></tr>
                <tr><td class="label">Address</td><td><input-address></td></tr>
                <tr><td class="label">City</td><td><input-city></td></tr>
                <tr><td class="label">State</td><td><input-state></td></tr>
                <tr><td class="label">Zip</td><td><input-zip></td></tr>
            </table>
        </div>
        <div class="applyBoxRight">
            <div>Professional Details</div>
            <table>
                <tr><td class="label">Key Skills</td><td><input-keySkills></td></tr>
                <tr><td class="label">Current Employer</td><td><input-employer></td></tr>
                <tr><td class="label">Source</td><td><input-source></td></tr>
                <tr><td class="label">Resume</td><td><input-resumeUpload></td></tr>
                <tr><td class="label">Resume Preview</td><td><input-resumeUploadPreview></td></tr>
                <tr><td class="label">Notes</td><td><input-extraNotes></td></tr>
                <tr><td class="label"></td><td><input-submit></td></tr>
            </table>
        </div>
    </div>
</div>
HTML;

$sections['Content - Thanks for your Submission'] = <<<'HTML'
<div class="main-content" style="text-align:center;padding:80px 24px;">
    <div style="width:80px;height:80px;background:#eff6ff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;margin-bottom:24px;font-size:40px;">&#10003;</div>
    <h1 style="font-size:32px;margin-bottom:12px;">Application Submitted!</h1>
    <p style="font-size:18px;color:#6b7280;max-width:500px;margin:0 auto 32px;">Thank you for your interest. We have received your application and will review it shortly.</p>
    <a href="index.php?m=careers&p=showAll" class="nav-btn" style="display:inline-flex;padding:12px 28px;">View More Positions</a>
</div>
HTML;

$sections['Footer'] = <<<'HTML'
<div class="career-footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <div class="brand-text">Neutara <span>Careers</span></div>
            <p>Connecting talented professionals with great opportunities.</p>
        </div>
        <div class="footer-links">
            <div>
                <h4>Quick Links</h4>
                <a href="index.php?m=careers">Home</a>
                <a href="index.php?m=careers&p=showAll">All Positions</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">&copy; 2026 Neutara ATS. All rights reserved.</div>
</div>
HTML;

// Update each section using prepared statements
$stmt = $pdo->prepare("UPDATE career_portal_template SET value = :val WHERE career_portal_name = 'Blank Page' AND setting = :setting");

foreach ($sections as $setting => $value) {
    $stmt->execute(['val' => $value, 'setting' => $setting]);
    $affected = $stmt->rowCount();
    echo "Updated: $setting (" . strlen($value) . " chars, $affected rows)\n";
}

echo "\nDone! Visit: http://localhost:8000/index.php?m=careers\n";
