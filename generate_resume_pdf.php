<?php
include_once('./config.php');
include_once('./constants.php');
include_once('./lib/DatabaseConnection.php');
include_once('./lib/Session.php');

spl_autoload_register(function ($class) {
    $prefix = 'Dompdf\\';
    $base_dir = __DIR__ . '/lib/dompdf/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

require_once('./lib/dompdf/lib/Cpdf.php');

use Dompdf\Dompdf;
use Dompdf\Options;

$candidateID = isset($_GET['candidateID']) ? intval($_GET['candidateID']) : 0;
$format = isset($_GET['format']) ? $_GET['format'] : 'html';

$db = DatabaseConnection::getInstance();
$sql = sprintf("SELECT * FROM candidate WHERE candidate_id = %d AND site_id = %d",
    $candidateID, $_SESSION['CATS']->getSiteID());
$rs = $db->getAssoc($sql);

if (empty($rs)) { die('Candidate not found.'); }

$name = htmlspecialchars($rs['first_name'] . ' ' . $rs['last_name']);
$html = '<html><head><meta charset="UTF-8"><style>
body{font-family:DejaVu Sans,Arial,sans-serif;margin:30px;color:#333;font-size:13px;}
h1{color:#2c3e50;border-bottom:2px solid #2c3e50;padding-bottom:8px;font-size:22px;}
h2{color:#34495e;font-size:15px;margin-top:15px;border-bottom:1px solid #ccc;padding-bottom:4px;}
.info{margin:4px 0;} .label{font-weight:bold;color:#555;}
</style></head><body>';
$html .= '<h1>' . $name . '</h1>';
$html .= '<h2>Contact Information</h2>';
if (!empty($rs['email1'])) $html .= '<div class="info"><span class="label">Email:</span> ' . htmlspecialchars($rs['email1']) . '</div>';
if (!empty($rs['phone_cell'])) $html .= '<div class="info"><span class="label">Mobile:</span> ' . htmlspecialchars($rs['phone_cell']) . '</div>';
if (!empty($rs['phone_home'])) $html .= '<div class="info"><span class="label">Home:</span> ' . htmlspecialchars($rs['phone_home']) . '</div>';
if (!empty($rs['address'])) $html .= '<div class="info"><span class="label">Address:</span> ' . htmlspecialchars($rs['address']) . '</div>';
if (!empty($rs['city'])) $html .= '<div class="info"><span class="label">City:</span> ' . htmlspecialchars($rs['city']) . '</div>';
if (!empty($rs['web_site'])) $html .= '<div class="info"><span class="label">Website:</span> ' . htmlspecialchars($rs['web_site']) . '</div>';
if (!empty($rs['current_employer'])) $html .= '<h2>Current Employer</h2><div class="info">' . htmlspecialchars($rs['current_employer']) . '</div>';
if (!empty($rs['current_pay'])) $html .= '<h2>Compensation</h2><div class="info"><span class="label">Current Pay:</span> ' . htmlspecialchars($rs['current_pay']) . '</div>';
if (!empty($rs['desired_pay'])) $html .= '<div class="info"><span class="label">Desired Pay:</span> ' . htmlspecialchars($rs['desired_pay']) . '</div>';
if (!empty($rs['key_skills'])) $html .= '<h2>Key Skills</h2><div class="info">' . nl2br(htmlspecialchars($rs['key_skills'])) . '</div>';
if (!empty($rs['notes'])) $html .= '<h2>Notes</h2><div class="info">' . nl2br(htmlspecialchars($rs['notes'])) . '</div>';
$html .= '</body></html>';

if ($format === 'pdf') {
    $options = new Options();
    $options->set('defaultFont', 'DejaVu Sans');
    $options->setIsRemoteEnabled(false);
    $options->setTempDir('./temp');
    $options->setFontDir('./lib/dompdf/lib/fonts');
    $options->setFontCache('./lib/dompdf/lib/fonts');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $filename = $rs['first_name'] . '_' . $rs['last_name'] . '_Resume.pdf';
    $dompdf->stream($filename, ['Attachment' => 1]);
} else {
    $buttons = '<div style="position:fixed;top:10px;right:10px;z-index:999;">
        <a href="?candidateID=' . $candidateID . '&format=pdf" style="background:#2c3e50;color:white;padding:8px 15px;text-decoration:none;border-radius:4px;margin-right:5px;">Download PDF</a>
        <button onclick="window.print()" style="background:#27ae60;color:white;padding:8px 15px;border:none;border-radius:4px;cursor:pointer;">Print</button>
    </div>';
    echo str_replace('<body>', '<body>' . $buttons, $html);
}
