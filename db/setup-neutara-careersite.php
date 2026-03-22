<?php
/**
 * Neutara Technologies Career Site Setup Script
 * Creates a beautiful, modern career portal template and enables it
 */

$rootDir = dirname(__DIR__);
chdir($rootDir);
include_once($rootDir . '/config.php');
include_once($rootDir . '/constants.php');
include_once($rootDir . '/lib/DatabaseConnection.php');

$db = DatabaseConnection::getInstance();
$siteID = 1;

echo "=== Setting up Neutara Technologies Career Site ===\n\n";

// 1. Enable career portal and set active board
echo "1. Enabling Career Portal...\n";
$settings = array(
    'enabled' => '1',
    'allowBrowse' => '1',
    'candidateRegistration' => '1',
    'showDepartment' => '1',
    'showCompany' => '0',
    'activeBoard' => 'Neutara Technologies',
    'allowXMLSubmit' => '1'
);

foreach ($settings as $key => $value) {
    // Delete existing
    $sql = sprintf(
        "DELETE FROM settings WHERE site_id = %d AND settings_type = %d AND setting = %s",
        $siteID, SETTINGS_CAREER_PORTAL, $db->makeQueryString($key)
    );
    $db->query($sql);

    // Insert new
    $sql = sprintf(
        "INSERT INTO settings (site_id, settings_type, setting, value) VALUES (%d, %d, %s, %s)",
        $siteID, SETTINGS_CAREER_PORTAL, $db->makeQueryString($key), $db->makeQueryString($value)
    );
    $db->query($sql);
}
echo "   Done!\n";

// 2. Clear old Neutara templates
echo "2. Clearing old Neutara templates...\n";
$db->query(sprintf(
    "DELETE FROM career_portal_template_site WHERE career_portal_name = %s AND site_id = %d",
    $db->makeQueryString('Neutara Technologies'), $siteID
));
$db->query(sprintf(
    "DELETE FROM career_portal_template_site WHERE career_portal_name = %s AND site_id = %d",
    $db->makeQueryString('Neutara'), $siteID
));
echo "   Done!\n";

// 3. Insert the Neutara Technologies template sections
echo "3. Creating Neutara Technologies template...\n";

$templateName = 'Neutara Technologies';

$templateParts = array();

// === CSS ===
$templateParts['CSS'] = <<<'CSS'
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: #f0f4f8;
    color: #1a202c;
    line-height: 1.6;
    -webkit-font-smoothing: antialiased;
}

/* === HEADER / HERO === */
.nt-header {
    background: linear-gradient(135deg, #0a1628 0%, #1a365d 40%, #2b6cb0 100%);
    position: relative;
    overflow: hidden;
}
.nt-header::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -20%;
    width: 600px;
    height: 600px;
    background: radial-gradient(circle, rgba(66,153,225,0.15) 0%, transparent 70%);
    border-radius: 50%;
}
.nt-header::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -10%;
    width: 400px;
    height: 400px;
    background: radial-gradient(circle, rgba(99,179,237,0.1) 0%, transparent 70%);
    border-radius: 50%;
}
.nt-topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 18px 60px;
    position: relative;
    z-index: 2;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.nt-logo {
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
}
.nt-logo-icon {
    width: 44px;
    height: 44px;
    background: linear-gradient(135deg, #4299e1, #63b3ed);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -1px;
    box-shadow: 0 4px 15px rgba(66,153,225,0.4);
}
.nt-logo-text {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    letter-spacing: -0.5px;
}
.nt-logo-text span {
    color: #63b3ed;
}
.nt-nav { display: flex; gap: 32px; align-items: center; }
.nt-nav a {
    color: rgba(255,255,255,0.75);
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.2s;
}
.nt-nav a:hover, .nt-nav a:link:hover { color: #fff; }
.nt-nav a:visited { color: rgba(255,255,255,0.75); }

.nt-hero {
    text-align: center;
    padding: 70px 60px 80px;
    position: relative;
    z-index: 2;
}
.nt-hero h1 {
    font-size: 48px;
    font-weight: 800;
    color: #fff;
    margin-bottom: 16px;
    letter-spacing: -1.5px;
    line-height: 1.1;
}
.nt-hero h1 em {
    font-style: normal;
    background: linear-gradient(90deg, #63b3ed, #90cdf4);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.nt-hero p {
    font-size: 18px;
    color: rgba(255,255,255,0.7);
    max-width: 600px;
    margin: 0 auto 36px;
    font-weight: 400;
    line-height: 1.7;
}
.nt-hero-stats {
    display: flex;
    justify-content: center;
    gap: 50px;
    margin-top: 40px;
}
.nt-hero-stat {
    text-align: center;
}
.nt-hero-stat .number {
    font-size: 36px;
    font-weight: 800;
    color: #63b3ed;
    display: block;
    line-height: 1;
}
.nt-hero-stat .label {
    font-size: 13px;
    color: rgba(255,255,255,0.5);
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-top: 6px;
    font-weight: 500;
}
.nt-btn {
    display: inline-block;
    padding: 14px 36px;
    background: linear-gradient(135deg, #4299e1, #3182ce);
    color: #fff !important;
    text-decoration: none !important;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    transition: all 0.3s;
    box-shadow: 0 4px 20px rgba(66,153,225,0.4);
    border: none;
    cursor: pointer;
}
.nt-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(66,153,225,0.5); }
.nt-btn:link, .nt-btn:visited { color: #fff !important; }
.nt-btn-outline {
    display: inline-block;
    padding: 14px 36px;
    background: transparent;
    color: #fff !important;
    text-decoration: none !important;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    border: 2px solid rgba(255,255,255,0.25);
    transition: all 0.3s;
    cursor: pointer;
    margin-left: 16px;
}
.nt-btn-outline:hover { border-color: #fff; background: rgba(255,255,255,0.05); }
.nt-btn-outline:link, .nt-btn-outline:visited { color: #fff !important; }

/* === CONTENT AREA === */
.nt-content {
    max-width: 1100px;
    margin: -40px auto 40px;
    padding: 0 30px;
    position: relative;
    z-index: 3;
}
.nt-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 25px rgba(0,0,0,0.06);
    padding: 40px;
    margin-bottom: 30px;
}
.nt-section-title {
    font-size: 28px;
    font-weight: 700;
    color: #1a365d;
    margin-bottom: 8px;
    letter-spacing: -0.5px;
}
.nt-section-subtitle {
    font-size: 15px;
    color: #718096;
    margin-bottom: 30px;
}

/* === JOB LISTINGS TABLE === */
table.sortable {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}
table.sortable th {
    background: #f7fafc;
    padding: 14px 20px;
    text-align: left;
    font-weight: 600;
    color: #4a5568;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    border-bottom: 2px solid #e2e8f0;
}
table.sortable th:first-child { border-radius: 10px 0 0 0; }
table.sortable th:last-child { border-radius: 0 10px 0 0; }
table.sortable td {
    padding: 16px 20px;
    border-bottom: 1px solid #edf2f7;
    vertical-align: middle;
}
table.sortable tr.evenTableRow { background: #fff; }
table.sortable tr.oddTableRow { background: #f9fafb; }
table.sortable tr:hover td { background: #ebf4ff; }
table.sortable a {
    color: #2b6cb0;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.2s;
}
table.sortable a:hover { color: #1a365d; }
table.sortable a:visited { color: #2b6cb0; }
.rowHeading th { cursor: pointer; }
.rowHeading th:hover { background: #edf2f7; }

/* === JOB DETAILS === */
.nt-job-detail { padding: 0; }
.nt-job-detail h1 {
    font-size: 32px;
    font-weight: 700;
    color: #1a365d;
    margin-bottom: 20px;
}
#detailsTable {
    width: auto;
    margin-bottom: 20px;
}
#detailsTable td {
    padding: 8px 20px 8px 0;
    font-size: 14px;
}
#detailsTable td.detailsHeader {
    color: #718096;
    font-weight: 500;
    width: 140px;
}
#descriptive, #discriptive {
    margin-top: 24px;
}
#descriptive p, #discriptive p {
    font-size: 15px;
    line-height: 1.8;
    color: #4a5568;
}
#detailsTools {
    float: none !important;
    width: auto !important;
    height: auto !important;
    background: transparent !important;
    border: none !important;
    margin-top: 30px;
    padding: 0 !important;
}
#detailsTools h2 { display: none; }
#detailsTools ul { display: none; }
#detailsTools a, #detailsTools a:link {
    display: inline-block;
    padding: 14px 40px;
    background: linear-gradient(135deg, #4299e1, #3182ce);
    color: #fff !important;
    text-decoration: none !important;
    border-radius: 12px;
    font-weight: 600;
    font-size: 15px;
    box-shadow: 0 4px 20px rgba(66,153,225,0.4);
    transition: all 0.3s;
}
#detailsTools a:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(66,153,225,0.5); }
#detailsTools img { display: none; }
#detailsTools a::after { content: 'Apply for this Position'; }

/* === APPLICATION FORM === */
div.applyBoxLeft, div.applyBoxRight {
    float: none !important;
    width: 100% !important;
    height: auto !important;
    background: #fff !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 12px !important;
    margin: 0 0 24px 0 !important;
    padding: 0 !important;
}
div.applyBoxLeft div, div.applyBoxRight div {
    background: #f7fafc !important;
    border-top: none !important;
    border-bottom: 1px solid #e2e8f0 !important;
    border-radius: 12px 12px 0 0 !important;
    padding: 16px 24px !important;
    margin: 0 !important;
}
div.applyBoxLeft div h3, div.applyBoxRight div h3 {
    font-size: 16px;
    color: #2d3748;
    font-weight: 600;
}
div.applyBoxLeft table, div.applyBoxRight table {
    width: calc(100% - 48px) !important;
    margin: 20px 24px !important;
}
div.applyBoxLeft table td, div.applyBoxRight table td {
    padding: 8px 4px !important;
}
td.label {
    text-align: right !important;
    width: 160px !important;
    color: #4a5568;
}
td.label label {
    font-weight: 500 !important;
    font-size: 14px;
}
input.inputBoxName, input.inputBoxNormal, input.inputBoxFile, select.inputBoxNormal {
    width: 100% !important;
    padding: 10px 14px !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-family: 'Inter', sans-serif !important;
    transition: border-color 0.2s, box-shadow 0.2s !important;
    outline: none !important;
    background: #fff !important;
}
input.inputBoxName:focus, input.inputBoxNormal:focus, textarea.inputBoxArea:focus {
    border-color: #4299e1 !important;
    box-shadow: 0 0 0 3px rgba(66,153,225,0.15) !important;
}
textarea, textarea.inputBoxArea {
    width: 100% !important;
    padding: 10px 14px !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-family: 'Inter', sans-serif !important;
    resize: vertical;
}
input.submitButton, .submitButton, input[type="submit"] {
    display: inline-block !important;
    padding: 12px 32px !important;
    background: linear-gradient(135deg, #4299e1, #3182ce) !important;
    color: #fff !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 600 !important;
    font-size: 15px !important;
    cursor: pointer !important;
    box-shadow: 0 4px 15px rgba(66,153,225,0.3) !important;
    transition: all 0.3s !important;
    font-family: 'Inter', sans-serif !important;
    width: auto !important;
    height: auto !important;
}
input.submitButton:hover, .submitButton:hover, input[type="submit"]:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 20px rgba(66,153,225,0.4) !important;
}
p.instructions {
    padding: 0 24px;
    font-size: 13px;
    color: #a0aec0;
    font-style: italic;
}

/* === CANDIDATE REGISTRATION === */
#careerContent h1 {
    font-size: 26px;
    font-weight: 700;
    color: #1a365d;
    margin-bottom: 16px;
}

/* === QUESTIONNAIRE === */
.nt-questionnaire { max-width: 700px; margin: 0 auto; }

/* === BENEFITS GRID === */
.nt-benefits {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-top: 30px;
}
.nt-benefit {
    text-align: center;
    padding: 30px 20px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    transition: all 0.3s;
}
.nt-benefit:hover {
    border-color: #4299e1;
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(66,153,225,0.1);
}
.nt-benefit-icon {
    font-size: 36px;
    margin-bottom: 14px;
}
.nt-benefit h4 {
    font-size: 16px;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 8px;
}
.nt-benefit p {
    font-size: 13px;
    color: #718096;
    line-height: 1.6;
}

/* === FOOTER === */
.nt-footer {
    background: #0a1628;
    color: rgba(255,255,255,0.6);
    padding: 50px 60px 30px;
}
.nt-footer-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr;
    gap: 40px;
    max-width: 1100px;
    margin: 0 auto;
    padding-bottom: 30px;
    border-bottom: 1px solid rgba(255,255,255,0.08);
}
.nt-footer-brand h3 {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 12px;
}
.nt-footer-brand h3 span { color: #63b3ed; }
.nt-footer-brand p { font-size: 14px; line-height: 1.7; }
.nt-footer-col h4 {
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin-bottom: 16px;
}
.nt-footer-col a {
    display: block;
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    font-size: 14px;
    margin-bottom: 10px;
    transition: color 0.2s;
}
.nt-footer-col a:hover { color: #63b3ed; }
.nt-footer-col a:visited { color: rgba(255,255,255,0.5); }
.nt-footer-bottom {
    text-align: center;
    padding-top: 24px;
    font-size: 13px;
    max-width: 1100px;
    margin: 0 auto;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .nt-topbar { padding: 16px 20px; }
    .nt-nav { display: none; }
    .nt-hero { padding: 40px 20px 60px; }
    .nt-hero h1 { font-size: 32px; }
    .nt-hero-stats { flex-wrap: wrap; gap: 30px; }
    .nt-content { padding: 0 16px; }
    .nt-card { padding: 24px; }
    .nt-benefits { grid-template-columns: 1fr; }
    .nt-footer-grid { grid-template-columns: 1fr; }
    div.applyBoxLeft table, div.applyBoxRight table { width: 90% !important; }
    td.label { width: auto !important; text-align: left !important; display: block; }
}
CSS;

// === HEADER ===
$templateParts['Header'] = <<<'HTML'
<div class="nt-header">
    <div class="nt-topbar">
        <a class="nt-logo" href="index.php?m=careers" style="text-decoration:none;">
            <div class="nt-logo-icon">N</div>
            <div class="nt-logo-text">Neutara <span>Technologies</span></div>
        </a>
        <nav class="nt-nav">
            <a-LinkMain>Home</a>
            <a-ListAll>Open Positions</a>
            <a href="https://neutara.com" target="_blank">About Us</a>
            <a href="https://neutara.com/contact" target="_blank">Contact</a>
        </nav>
    </div>
HTML;

// === FOOTER ===
$templateParts['Footer'] = <<<'HTML'
<div class="nt-footer">
    <div class="nt-footer-grid">
        <div class="nt-footer-brand">
            <h3>Neutara <span>Technologies</span></h3>
            <p>We are a leading technology company building innovative solutions that transform industries. Join our team and be part of something extraordinary.</p>
        </div>
        <div class="nt-footer-col">
            <h4>Careers</h4>
            <a-ListAll>Open Positions</a>
            <a href="#">Life at Neutara</a>
            <a href="#">Benefits</a>
            <a href="#">Internships</a>
        </div>
        <div class="nt-footer-col">
            <h4>Company</h4>
            <a href="https://neutara.com" target="_blank">About Us</a>
            <a href="#">Our Team</a>
            <a href="#">Blog</a>
            <a href="#">Press</a>
        </div>
        <div class="nt-footer-col">
            <h4>Connect</h4>
            <a href="#">LinkedIn</a>
            <a href="#">Twitter</a>
            <a href="mailto:careers@neutara.com">careers@neutara.com</a>
            <a href="#">Glassdoor</a>
        </div>
    </div>
    <div class="nt-footer-bottom">
        &copy; 2026 Neutara Technologies Pvt. Ltd. All rights reserved.
    </div>
</div>
HTML;

// === CONTENT - MAIN (Landing Page) ===
$templateParts['Content - Main'] = <<<'HTML'
    <div class="nt-hero">
        <h1>Build the Future<br/>with <em>Neutara Technologies</em></h1>
        <p>Join a team of innovators, dreamers, and doers. We're on a mission to transform technology — and we want you to be a part of it.</p>
        <div>
            <a-ListAll class="nt-btn">View Open Positions (<numberOfOpenPositions>)</a>
        </div>
        <div class="nt-hero-stats">
            <div class="nt-hero-stat">
                <span class="number"><numberOfOpenPositions></span>
                <span class="label">Open Roles</span>
            </div>
            <div class="nt-hero-stat">
                <span class="number">500+</span>
                <span class="label">Team Members</span>
            </div>
            <div class="nt-hero-stat">
                <span class="number">12</span>
                <span class="label">Countries</span>
            </div>
            <div class="nt-hero-stat">
                <span class="number">4.8</span>
                <span class="label">Glassdoor Rating</span>
            </div>
        </div>
    </div>
</div>

<div class="nt-content">
    <div class="nt-card">
        <h2 class="nt-section-title">Why Neutara Technologies?</h2>
        <p class="nt-section-subtitle">We believe great people build great products. Here's what makes us different.</p>
        <div class="nt-benefits">
            <div class="nt-benefit">
                <div class="nt-benefit-icon">&#x1f680;</div>
                <h4>Innovation First</h4>
                <p>Work on cutting-edge projects using the latest technologies. We invest in R&D and encourage experimentation.</p>
            </div>
            <div class="nt-benefit">
                <div class="nt-benefit-icon">&#x1f4b0;</div>
                <h4>Competitive Package</h4>
                <p>Industry-leading salaries, equity options, annual bonuses, and comprehensive health benefits for you and your family.</p>
            </div>
            <div class="nt-benefit">
                <div class="nt-benefit-icon">&#x1f3e0;</div>
                <h4>Flexible Work</h4>
                <p>Hybrid and remote options available. Work from anywhere with flexible hours that suit your lifestyle.</p>
            </div>
            <div class="nt-benefit">
                <div class="nt-benefit-icon">&#x1f4da;</div>
                <h4>Learning & Growth</h4>
                <p>Dedicated learning budgets, conference sponsorships, mentorship programs, and internal career mobility.</p>
            </div>
            <div class="nt-benefit">
                <div class="nt-benefit-icon">&#x1f30d;</div>
                <h4>Global Impact</h4>
                <p>Our products serve millions worldwide. Your work directly impacts people across 50+ countries.</p>
            </div>
            <div class="nt-benefit">
                <div class="nt-benefit-icon">&#x1f91d;</div>
                <h4>Inclusive Culture</h4>
                <p>Diverse teams, employee resource groups, and a commitment to building a workplace where everyone belongs.</p>
            </div>
        </div>
    </div>

    <registeredCandidate>
    <registeredLoginTitle></registeredLoginTitle>
    <registeredLogin>
</div>
HTML;

// === CONTENT - SEARCH RESULTS (Job Listings) ===
$templateParts['Content - Search Results'] = <<<'HTML'
    <div class="nt-hero" style="padding: 40px 60px 60px;">
        <h1>Open Positions</h1>
        <p>We have <numberOfSearchResults> positions waiting for the right talent. Find your next career move.</p>
    </div>
</div>

<div class="nt-content">
    <registeredCandidate>
    <div class="nt-card">
        <h2 class="nt-section-title">Current Openings</h2>
        <p class="nt-section-subtitle">Click on any position to view details and apply.</p>
        <searchResultsTable>
    </div>
</div>
HTML;

// === CONTENT - JOB DETAILS ===
$templateParts['Content - Job Details'] = <<<'HTML'
    <div class="nt-hero" style="padding: 40px 60px 60px;">
        <h1><title></h1>
        <p>at Neutara Technologies &bull; <city>, <state></p>
    </div>
</div>

<div class="nt-content">
    <registeredCandidate>
    <div class="nt-card nt-job-detail">
        <div style="padding: 40px;">
            <div style="display:flex;gap:40px;flex-wrap:wrap;">
                <div style="flex:1;min-width:300px;">
                    <h2 style="font-size:20px;font-weight:700;color:#1a365d;margin-bottom:20px;">Position Details</h2>
                    <table id="detailsTable">
                        <tr>
                            <td class="detailsHeader"><strong>Location:</strong></td>
                            <td><city>, <state></td>
                        </tr>
                        <tr>
                            <td class="detailsHeader"><strong>Openings:</strong></td>
                            <td><openings></td>
                        </tr>
                        <tr>
                            <td class="detailsHeader"><strong>Salary Range:</strong></td>
                            <td><salary></td>
                        </tr>
                        <tr>
                            <td class="detailsHeader"><strong>Type:</strong></td>
                            <td><type></td>
                        </tr>
                        <tr>
                            <td class="detailsHeader"><strong>Posted:</strong></td>
                            <td><created></td>
                        </tr>
                        <tr>
                            <td class="detailsHeader"><strong>Recruiter:</strong></td>
                            <td><recruiter></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div style="margin-top:30px;padding-top:30px;border-top:1px solid #e2e8f0;">
                <h2 style="font-size:20px;font-weight:700;color:#1a365d;margin-bottom:16px;">Job Description</h2>
                <div style="font-size:15px;line-height:1.8;color:#4a5568;">
                    <description>
                </div>
            </div>

            <div id="detailsTools" style="margin-top:30px;padding-top:30px;border-top:1px solid #e2e8f0;">
                <a-applyToJob class="nt-btn" style="font-size:16px;padding:16px 48px;">Apply Now</a>
            </div>

            <div style="margin-top:24px;">
                <a-ListAll style="color:#4299e1;text-decoration:none;font-weight:500;font-size:14px;">&larr; Back to all positions</a>
            </div>
        </div>
    </div>
</div>
HTML;

// === CONTENT - APPLY FOR POSITION ===
$templateParts['Content - Apply for Position'] = <<<'HTML'
    <div class="nt-hero" style="padding: 40px 60px 60px;">
        <h1>Apply: <title></h1>
        <p>Complete the form below to submit your application.</p>
    </div>
</div>

<div class="nt-content">
    <div class="nt-card" style="padding:0;">
        <div style="padding:40px;">
            <div class="applyBoxLeft">
                <div><h3>1. Upload Your Resume</h3></div>
                <table>
                    <tr>
                        <td>
                            <input-resumeUploadPreview>
                        </td>
                    </tr>
                </table>
                <br />
                <div><h3>2. Personal Information</h3></div>
                <p class="instructions">Fields marked with * are required.</p>
                <table>
                    <tr>
                        <td class="label"><label id="firstNameLabel" for="firstName">*First Name:</label></td>
                        <td><input-firstName></td>
                    </tr>
                    <tr>
                        <td class="label"><label id="lastNameLabel" for="lastName">*Last Name:</label></td>
                        <td><input-lastName></td>
                    </tr>
                    <tr>
                        <td class="label"><label id="emailLabel" for="email">*Email:</label></td>
                        <td><input-email></td>
                    </tr>
                    <tr>
                        <td class="label"><label id="emailConfirmLabel" for="emailconfirm">*Confirm Email:</label></td>
                        <td><input-emailconfirm></td>
                    </tr>
                </table>
            </div>

            <div class="applyBoxRight">
                <div><h3>3. Contact Details</h3></div>
                <table>
                    <tr>
                        <td class="label"><label for="phoneHome">Home Phone:</label></td>
                        <td><input-phone-home></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="phoneCell">Mobile Phone:</label></td>
                        <td><input-phone-cell></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="phone">Work Phone:</label></td>
                        <td><input-phone></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="bestTimeToCall">*Best Time to Call:</label></td>
                        <td><input-best-time-to-call></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="address">Mailing Address:</label></td>
                        <td><input-address></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="city">*City:</label></td>
                        <td><input-city></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="state">*State:</label></td>
                        <td><input-state></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="zip">*Zip Code:</label></td>
                        <td><input-zip></td>
                    </tr>
                </table>
                <br />
                <div><h3>4. Additional Information</h3></div>
                <table>
                    <tr>
                        <td class="label"><label for="keySkills">*Key Skills:</label></td>
                        <td><input-keySkills></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="source">How did you hear?</label></td>
                        <td><input-source></td>
                    </tr>
                    <tr>
                        <td class="label"><label for="employer">Current Employer:</label></td>
                        <td><input-employer></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td style="padding-top:16px;">
                            <submit value="Submit Application" />
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
HTML;

// === CONTENT - QUESTIONNAIRE ===
$templateParts['Content - Questionnaire'] = <<<'HTML'
    <div class="nt-hero" style="padding: 40px 60px 60px;">
        <h1>Application Questionnaire</h1>
        <p>Please complete the following questionnaire as part of your application.</p>
    </div>
</div>

<div class="nt-content">
    <div class="nt-card">
        <div class="nt-questionnaire">
            <questionnaire>
            <br /><br />
            <div style="text-align: center;">
                <submit value="Submit and Continue" class="nt-btn" />
            </div>
        </div>
    </div>
</div>
HTML;

// === CONTENT - THANKS FOR SUBMISSION ===
$templateParts['Content - Thanks for your Submission'] = <<<'HTML'
    <div class="nt-hero" style="padding: 40px 60px 60px;">
        <h1>Application Submitted!</h1>
        <p>Thank you for applying to <title> at Neutara Technologies.</p>
    </div>
</div>

<div class="nt-content">
    <div class="nt-card" style="text-align:center;padding:60px 40px;">
        <div style="font-size:64px;margin-bottom:20px;">&#x2705;</div>
        <h2 style="font-size:28px;font-weight:700;color:#1a365d;margin-bottom:16px;">We've Received Your Application</h2>
        <p style="font-size:16px;color:#718096;max-width:500px;margin:0 auto 30px;line-height:1.7;">
            Our recruiting team will review your application carefully. If your qualifications match our requirements, we'll reach out to schedule an interview.
        </p>
        <p style="font-size:14px;color:#a0aec0;margin-bottom:30px;">
            You should receive a confirmation email shortly at the address you provided.
        </p>
        <a-ListAll class="nt-btn">View More Positions</a>
    </div>
</div>
HTML;

// === CONTENT - CANDIDATE REGISTRATION ===
$templateParts['Content - Candidate Registration'] = <<<'HTML'
<div style="max-width:500px;margin:30px auto;padding:30px;">
    <applyContent>
    <h2 style="font-size:22px;color:#1a365d;font-weight:700;margin-bottom:8px;">Applying to <title></h2>
    </applyContent>
    <p style="font-size:14px;color:#718096;margin-bottom:24px;">Please provide your details to continue.</p>

    <div style="margin-bottom:16px;">
        <input-new> <label for="isNewYes" style="font-weight:500;color:#2d3748;margin-right:20px;">I'm a new applicant</label>
        <input-registered> <label for="isNewNo" style="font-weight:500;color:#2d3748;">I've applied before</label>
    </div>

    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">First Name</label>
        <input-firstName>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Last Name</label>
        <input-lastName>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Email</label>
        <input-email1>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Phone</label>
        <input-phoneWork>
    </div>

    <div style="margin:16px 0;">
        <input-rememberMe> <label for="rememberMe" style="font-size:13px;color:#718096;">Remember me on this computer</label>
    </div>

    <div style="margin-top:24px;">
        <input-submit>
    </div>
</div>
HTML;

// === CONTENT - CANDIDATE PROFILE ===
$templateParts['Content - Candidate Profile'] = <<<'HTML'
<div style="max-width:600px;margin:30px auto;padding:30px;">
    <h2 style="font-size:24px;color:#1a365d;font-weight:700;margin-bottom:8px;">My Profile</h2>
    <p style="font-size:14px;color:#718096;margin-bottom:24px;">Update your details and resume below.</p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
        <div>
            <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">First Name</label>
            <input-firstName>
        </div>
        <div>
            <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Last Name</label>
            <input-lastName>
        </div>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Email</label>
        <input-email1>
    </div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
        <div>
            <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Home Phone</label>
            <input-phoneHome>
        </div>
        <div>
            <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Cell Phone</label>
            <input-phoneCell>
        </div>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Work Phone</label>
        <input-phoneWork>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Address</label>
        <input-address>
    </div>
    <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:12px;margin-bottom:12px;">
        <div>
            <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">City</label>
            <input-city>
        </div>
        <div>
            <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">State</label>
            <input-state>
        </div>
        <div>
            <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Zip</label>
            <input-zip>
        </div>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Key Skills</label>
        <input-keySkills>
    </div>
    <div style="margin-bottom:12px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Current Employer</label>
        <input-currentEmployer>
    </div>
    <div style="margin-bottom:20px;">
        <label style="display:block;font-size:13px;color:#4a5568;margin-bottom:4px;font-weight:500;">Resume</label>
        <input-resume>
    </div>
    <input-submit>
</div>
HTML;

// Left sidebar (unused in Blank.tpl but required)
$templateParts['Left'] = '';

// Insert all template parts
foreach ($templateParts as $setting => $value) {
    $sql = sprintf(
        "INSERT INTO career_portal_template_site (career_portal_name, setting, value, site_id) VALUES (%s, %s, %s, %d)",
        $db->makeQueryString($templateName),
        $db->makeQueryString($setting),
        $db->makeQueryString($value),
        $siteID
    );
    $db->query($sql);
    echo "   Inserted: $setting\n";
}
echo "   Done!\n";

// 4. Add sample job orders if none exist
echo "\n4. Checking for public job orders...\n";
$rs = $db->getAllAssoc("SELECT COUNT(*) as cnt FROM joborder WHERE public = 1 AND status = 'Active'");
$count = $rs[0]['cnt'];
echo "   Found $count active public job orders.\n";

if ($count == 0) {
    echo "   Adding sample job orders for Neutara Technologies...\n";

    // First check if company exists
    $companyRS = $db->getAllAssoc("SELECT company_id FROM company WHERE name LIKE '%Neutara%' LIMIT 1");
    if (empty($companyRS)) {
        $db->query(sprintf(
            "INSERT INTO company (name, address, city, state, zip, phone1, url, key_technologies, is_hot, site_id, entered_by, owner, date_created, date_modified)
             VALUES ('Neutara Technologies', '100 Innovation Drive', 'Hyderabad', 'Telangana', '500081', '+91-40-12345678', 'https://neutara.com', 'PHP, Python, React, Node.js, AWS, Machine Learning', 0, %d, 1, 1, NOW(), NOW())",
            $siteID
        ));
        $companyID = $db->getLastInsertID();
        echo "   Created company: Neutara Technologies (ID: $companyID)\n";
    } else {
        $companyID = $companyRS[0]['company_id'];
        echo "   Found existing company ID: $companyID\n";
    }

    // Add sample jobs
    $jobs = array(
        array(
            'title' => 'Senior Full Stack Developer',
            'desc' => '<p><strong>About the Role:</strong></p><p>We are looking for a Senior Full Stack Developer to join our engineering team. You will be responsible for building and maintaining web applications that serve millions of users worldwide.</p><br/><p><strong>Responsibilities:</strong></p><ul><li>Design, develop, and maintain scalable web applications</li><li>Collaborate with product managers and designers to deliver user-centric features</li><li>Write clean, efficient, and well-documented code</li><li>Mentor junior developers and conduct code reviews</li><li>Participate in architectural decisions and technical planning</li></ul><br/><p><strong>Requirements:</strong></p><ul><li>5+ years of experience in full-stack development</li><li>Proficiency in React, Node.js, and TypeScript</li><li>Experience with cloud services (AWS/GCP/Azure)</li><li>Strong understanding of databases (SQL and NoSQL)</li><li>Excellent problem-solving and communication skills</li></ul>',
            'city' => 'Hyderabad',
            'state' => 'Telangana',
            'salary' => '18-30 LPA',
            'type' => 'Full-Time',
            'openings' => 3
        ),
        array(
            'title' => 'DevOps Engineer',
            'desc' => '<p><strong>About the Role:</strong></p><p>Join our infrastructure team to build and maintain the systems that power Neutara. You will work on CI/CD pipelines, container orchestration, and cloud infrastructure.</p><br/><p><strong>Responsibilities:</strong></p><ul><li>Design and maintain CI/CD pipelines</li><li>Manage Kubernetes clusters and Docker environments</li><li>Implement monitoring and alerting solutions</li><li>Automate infrastructure provisioning with Terraform/Ansible</li><li>Ensure system reliability and uptime</li></ul><br/><p><strong>Requirements:</strong></p><ul><li>3+ years of DevOps/SRE experience</li><li>Proficiency in AWS, Docker, and Kubernetes</li><li>Experience with Terraform, Ansible, or similar IaC tools</li><li>Strong Linux administration skills</li><li>Knowledge of monitoring tools (Prometheus, Grafana, ELK)</li></ul>',
            'city' => 'Bangalore',
            'state' => 'Karnataka',
            'salary' => '15-25 LPA',
            'type' => 'Full-Time',
            'openings' => 2
        ),
        array(
            'title' => 'UI/UX Designer',
            'desc' => '<p><strong>About the Role:</strong></p><p>We need a creative UI/UX Designer to craft beautiful, intuitive experiences for our products. You will work closely with engineering and product teams to bring designs to life.</p><br/><p><strong>Responsibilities:</strong></p><ul><li>Create wireframes, prototypes, and high-fidelity designs</li><li>Conduct user research and usability testing</li><li>Build and maintain our design system</li><li>Collaborate with developers to ensure pixel-perfect implementation</li><li>Analyze user behavior data to inform design decisions</li></ul><br/><p><strong>Requirements:</strong></p><ul><li>3+ years of UI/UX design experience</li><li>Expert in Figma, Sketch, or Adobe XD</li><li>Strong portfolio showcasing web and mobile designs</li><li>Understanding of accessibility standards</li><li>Experience with design systems and component libraries</li></ul>',
            'city' => 'Hyderabad',
            'state' => 'Telangana',
            'salary' => '12-20 LPA',
            'type' => 'Full-Time',
            'openings' => 1
        ),
        array(
            'title' => 'Data Scientist - Machine Learning',
            'desc' => '<p><strong>About the Role:</strong></p><p>Join our AI/ML team to develop intelligent solutions that drive business value. You will work on recommendation engines, natural language processing, and predictive analytics.</p><br/><p><strong>Responsibilities:</strong></p><ul><li>Develop and deploy machine learning models</li><li>Process and analyze large datasets</li><li>Build data pipelines and ETL workflows</li><li>Collaborate with engineering to integrate ML into products</li><li>Stay current with AI/ML research and best practices</li></ul><br/><p><strong>Requirements:</strong></p><ul><li>MS/PhD in Computer Science, Statistics, or related field</li><li>3+ years of experience in ML/Data Science</li><li>Proficiency in Python, TensorFlow/PyTorch, and scikit-learn</li><li>Experience with NLP, computer vision, or recommendation systems</li><li>Strong statistical analysis and data visualization skills</li></ul>',
            'city' => 'Pune',
            'state' => 'Maharashtra',
            'salary' => '20-35 LPA',
            'type' => 'Full-Time',
            'openings' => 2
        ),
        array(
            'title' => 'Product Manager',
            'desc' => '<p><strong>About the Role:</strong></p><p>We are seeking a Product Manager to drive the vision and strategy for one of our core product lines. You will define the roadmap and work cross-functionally to deliver impact.</p><br/><p><strong>Responsibilities:</strong></p><ul><li>Define product vision, strategy, and roadmap</li><li>Gather and prioritize product requirements</li><li>Work with engineering, design, and marketing teams</li><li>Analyze market trends and competitive landscape</li><li>Track KPIs and measure product success</li></ul><br/><p><strong>Requirements:</strong></p><ul><li>4+ years of product management experience in tech</li><li>Strong analytical and problem-solving skills</li><li>Excellent communication and stakeholder management</li><li>Experience with agile methodologies</li><li>Technical background preferred (CS/Engineering degree)</li></ul>',
            'city' => 'Hyderabad',
            'state' => 'Telangana',
            'salary' => '22-35 LPA',
            'type' => 'Full-Time',
            'openings' => 1
        ),
        array(
            'title' => 'QA Automation Engineer',
            'desc' => '<p><strong>About the Role:</strong></p><p>Join our quality engineering team to ensure our products meet the highest standards. You will build and maintain automated testing frameworks across our product suite.</p><br/><p><strong>Responsibilities:</strong></p><ul><li>Design and implement automated test frameworks</li><li>Write and maintain test scripts (UI, API, performance)</li><li>Integrate tests into CI/CD pipelines</li><li>Identify, report, and track bugs effectively</li><li>Collaborate with developers to improve testability</li></ul><br/><p><strong>Requirements:</strong></p><ul><li>3+ years of QA automation experience</li><li>Proficiency in Selenium, Cypress, or Playwright</li><li>Experience with API testing (Postman, REST Assured)</li><li>Knowledge of JavaScript/Python for scripting</li><li>Familiarity with CI/CD tools (Jenkins, GitHub Actions)</li></ul>',
            'city' => 'Chennai',
            'state' => 'Tamil Nadu',
            'salary' => '10-18 LPA',
            'type' => 'Full-Time',
            'openings' => 2
        )
    );

    foreach ($jobs as $job) {
        $sql = sprintf(
            "INSERT INTO joborder (title, description, city, state, salary, type, openings, public, status, company_id, contact_id, recruiter, owner, entered_by, is_hot, duration, rate_max, start_date, site_id, date_created, date_modified)
             VALUES (%s, %s, %s, %s, %s, %s, %d, 1, 'Active', %d, 0, 1, 1, 1, 0, '', '', NOW(), %d, NOW(), NOW())",
            $db->makeQueryString($job['title']),
            $db->makeQueryString($job['desc']),
            $db->makeQueryString($job['city']),
            $db->makeQueryString($job['state']),
            $db->makeQueryString($job['salary']),
            $db->makeQueryString($job['type']),
            $job['openings'],
            $companyID,
            $siteID
        );
        $db->query($sql);
        $jobID = $db->getLastInsertID();
        echo "   Created job: {$job['title']} (ID: $jobID)\n";
    }
}

echo "\n=== Setup Complete! ===\n";
echo "\nCareer Site URL: http://localhost:8000/careers/\n";
echo "Admin Template Settings: Settings > Career Portal\n";
echo "Active Template: Neutara Technologies\n";
echo "\nTemplate features:\n";
echo "  - Modern BambooHR-inspired design\n";
echo "  - Gradient header with company branding\n";
echo "  - Benefits/perks showcase grid\n";
echo "  - Beautiful job listings table\n";
echo "  - Clean application form\n";
echo "  - Professional footer with links\n";
echo "  - Fully responsive (mobile-friendly)\n";
echo "  - Google Fonts (Inter) integration\n";
