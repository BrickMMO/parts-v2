<?php

ini_set('memory_limit', '512M');
set_time_limit(0);

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

mysqli_autocommit($connect, false);
mysqli_query($connect, 'DELETE FROM inventory_parts');
mysqli_query($connect, 'ALTER TABLE inventory_parts DISABLE KEYS');

$fileUrl = 'https://cdn.rebrickable.com/media/downloads/inventory_parts.csv.gz';

$tmpDir = __DIR__ . "/tmp";
if (!is_dir($tmpDir)) mkdir($tmpDir, 0777, true);

// Create temp file
$tmpBase = tempnam($tmpDir, "inventory_parts_");

// --------------------------------------
// FIX #1: DEFINE $tmpGz AND DOWNLOAD ONCE
// --------------------------------------
$tmpGz = $tmpBase . ".gz";
file_put_contents($tmpGz, file_get_contents($fileUrl));

$tmpCsv = str_replace(".gz", ".csv", $tmpGz);

// --------------------------------------
// FIX #2: USE SAME $tmpGz (not undefined)
// --------------------------------------
$gz = gzopen($tmpGz, 'rb');
$out = fopen($tmpCsv, 'wb');
while (!gzeof($gz)) fwrite($out, gzread($gz, 8192));
gzclose($gz);
fclose($out);

// Remove .gz now that it’s extracted
unlink($tmpGz);

// First pass: count rows
$handle = fopen($tmpCsv, 'r');
fgetcsv($handle); // skip header
$totalRows = 0;
while (fgetcsv($handle) !== false) {
    $totalRows++;
}
fclose($handle);

echo 'Rows in File: '.$totalRows.'<hr>';

// Second pass: process and batch insert
$handle = fopen($tmpCsv, 'r');
fgetcsv($handle); // skip header

$batchSize = 5000;
$rows = [];
$counter = 0;

while (($record = fgetcsv($handle)) !== false) 
{
    if (count($record) !== 6 || !is_numeric($record[0])) continue;

    $rows[] = sprintf(
        '(%d,"%s","%s","%s","%s","%s","%s")',
        $counter,
        addslashes($record[0]),
        addslashes($record[1]),
        addslashes($record[2]),
        addslashes($record[3]),
        addslashes($record[4]),
        addslashes($record[5])
    );
    $counter++;

    if (count($rows) >= $batchSize) 
    {
        $query = 'INSERT INTO inventory_parts (`row`,inventory_id,part_num,color_id,quantity,is_spare,img_url) VALUES ' . implode(',', $rows);
        mysqli_query($connect, $query);
        mysqli_commit($connect);
        $rows = [];
    }
}

// Insert remaining rows
if (count($rows)) 
{
    $query = 'INSERT INTO inventory_parts (`row`,inventory_id,part_num,color_id,quantity,is_spare,img_url) VALUES ' . implode(',', $rows);
    mysqli_query($connect, $query);
    mysqli_commit($connect);
}

fclose($handle);
mysqli_query($connect, 'ALTER TABLE inventory_parts ENABLE KEYS');

unlink($tmpCsv);

echo "✅ Imported $counter records successfully.";
