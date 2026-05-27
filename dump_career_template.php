<?php
define('LEGACY_ROOT', __DIR__);
include_once(LEGACY_ROOT . '/constants.php');
include_once(LEGACY_ROOT . '/config.php');
include_once(LEGACY_ROOT . '/lib/DatabaseConnection.php');

$db = DatabaseConnection::getInstance();

// Get active template name
$rs = $db->getAllAssoc("SELECT setting, value FROM settings WHERE setting = 'activeBoard' LIMIT 1");
echo "=== Active Board ===\n";
print_r($rs);

// Get all template names
$rs2 = $db->getAllAssoc("SELECT DISTINCT career_portal_name FROM career_portal_template ORDER BY career_portal_name");
echo "\n=== Available Templates ===\n";
print_r($rs2);

// Get the default template settings
if (!empty($rs2)) {
    $name = $rs2[0]['career_portal_name'];
    echo "\n=== Template: $name ===\n";
    $rs3 = $db->getAllAssoc("SELECT setting, LENGTH(value) as val_len FROM career_portal_template WHERE career_portal_name = " . $db->makeQueryString($name));
    foreach ($rs3 as $row) {
        echo $row['setting'] . ' (' . $row['val_len'] . " chars)\n";
    }

    // Get Header
    $rs4 = $db->getAssoc("SELECT value FROM career_portal_template WHERE career_portal_name = " . $db->makeQueryString($name) . " AND setting = 'Header'");
    echo "\n=== Header ===\n";
    echo substr($rs4['value'] ?? 'NOT SET', 0, 500) . "\n";

    // Get Content - Main
    $rs5 = $db->getAssoc("SELECT value FROM career_portal_template WHERE career_portal_name = " . $db->makeQueryString($name) . " AND setting = 'Content - Main'");
    echo "\n=== Content - Main ===\n";
    echo substr($rs5['value'] ?? 'NOT SET', 0, 500) . "\n";

    // Get Content - Search Results
    $rs6 = $db->getAssoc("SELECT value FROM career_portal_template WHERE career_portal_name = " . $db->makeQueryString($name) . " AND setting = 'Content - Search Results'");
    echo "\n=== Content - Search Results ===\n";
    echo substr($rs6['value'] ?? 'NOT SET', 0, 800) . "\n";

    // Get Footer
    $rs7 = $db->getAssoc("SELECT value FROM career_portal_template WHERE career_portal_name = " . $db->makeQueryString($name) . " AND setting = 'Footer'");
    echo "\n=== Footer ===\n";
    echo substr($rs7['value'] ?? 'NOT SET', 0, 500) . "\n";

    // Get CSS
    $rs8 = $db->getAssoc("SELECT value FROM career_portal_template WHERE career_portal_name = " . $db->makeQueryString($name) . " AND setting = 'CSS'");
    echo "\n=== CSS (first 300 chars) ===\n";
    echo substr($rs8['value'] ?? 'NOT SET', 0, 300) . "\n";
}

// Check site-specific overrides
$rs9 = $db->getAllAssoc("SELECT career_portal_name, setting, LENGTH(value) as val_len FROM career_portal_template_site ORDER BY career_portal_name, setting");
echo "\n=== Site-specific overrides ===\n";
foreach ($rs9 as $row) {
    echo $row['career_portal_name'] . ' -> ' . $row['setting'] . ' (' . $row['val_len'] . " chars)\n";
}
