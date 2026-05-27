<?php
/**
 * One-time script: reset all PostgreSQL SERIAL sequences to be above
 * the current max ID in each table.  Run once after loading the schema:
 *   php fix_sequences.php
 */
require_once('config.php');

$dsn = "pgsql:host=" . DATABASE_HOST . ";port=" . DATABASE_PORT . ";dbname=" . DATABASE_NAME;
try {
    $pdo = new PDO($dsn, DATABASE_USER, DATABASE_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}

// Find every sequence and its owning table+column, then setval to max+1
$sql = "
    SELECT
        s.relname   AS seq_name,
        t.relname   AS table_name,
        a.attname   AS col_name
    FROM pg_class s
    JOIN pg_depend d ON d.objid = s.oid
    JOIN pg_class t  ON t.oid  = d.refobjid
    JOIN pg_attribute a ON a.attrelid = d.refobjid AND a.attnum = d.refobjsubid
    WHERE s.relkind = 'S'
    AND   t.relnamespace = (SELECT oid FROM pg_namespace WHERE nspname = 'public')
    ORDER BY t.relname
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
$fixed = 0;

foreach ($rows as $row) {
    $seq   = $row['seq_name'];
    $table = $row['table_name'];
    $col   = $row['col_name'];

    $maxRow = $pdo->query("SELECT COALESCE(MAX(\"$col\"), 0) AS m FROM \"$table\"")->fetch();
    $maxVal = (int)$maxRow['m'];
    $newVal = $maxVal + 1;

    $pdo->exec("SELECT setval('$seq', $newVal, false)");
    echo "Reset sequence '$seq' for $table.$col → next ID will be $newVal\n";
    $fixed++;
}

echo "\nDone. Reset $fixed sequences.\n";
?>
