<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

// Clear table and disable keys for speed
mysqli_query($connect, 'DELETE FROM inventory_sets');
mysqli_query($connect, 'ALTER TABLE inventory_sets DISABLE KEYS');

$file = 'https://cdn.rebrickable.com/media/downloads/inventory_sets.csv.gz';
$tmpDir = __DIR__ . "/tmp";
if (!is_dir($tmpDir)) mkdir($tmpDir, 0777, true);

$tmpFile = tempnam($tmpDir, "inventory_sets_");
file_put_contents($tmpFile . ".gz", file_get_contents($file));

$tmpFile = $tmpFile . ".gz";
$tmpCsv  = str_replace(".gz", ".csv", $tmpFile);


// Decompress to CSV
$handleGz = gzopen($tmpFile, 'r');
$tmpCsv = str_replace('.gz', '.csv', $tmpFile);
$out = fopen($tmpCsv, 'wb');
while (!gzeof($handleGz)) {
    fwrite($out, gzread($handleGz, 8192));
}
gzclose($handleGz);
fclose($out);
unlink($tmpFile);

// Count total rows
$handle = fopen($tmpCsv, 'r');
fgetcsv($handle); // skip header
$totalRows = 0;
while (fgetcsv($handle) !== false) $totalRows++;
fclose($handle);
echo "Rows in File: $totalRows<hr>";

// Batch insert
$handle = fopen($tmpCsv, 'r');
fgetcsv($handle); // skip header
$batchSize = 1000;
$rows = [];
$counter = 0;

while (($record = fgetcsv($handle)) !== false) {
    $record = array_map('trim', $record);

    if (count($record) != 3) continue;

    $rows[] = sprintf(
        '(%d,"%s","%s","%s")',
        $counter,
        addslashes($record[0]),
        addslashes($record[1]),
        addslashes($record[2])
    );
    $counter++;

    if (count($rows) >= $batchSize) {
        $query = 'INSERT IGNORE INTO inventory_sets (`row`,inventory_id,set_num,quantity) VALUES ' . implode(',', $rows);
        mysqli_query($connect, $query);
        $rows = [];
    }
}

// Insert remaining rows
if (count($rows)) {
    $query = 'INSERT IGNORE INTO inventory_sets (`row`,inventory_id,set_num,quantity) VALUES ' . implode(',', $rows);
    mysqli_query($connect, $query);
}

fclose($handle);
unlink($tmpCsv);
mysqli_query($connect, 'ALTER TABLE inventory_sets ENABLE KEYS');

echo "✅ Imported $counter records successfully.";
