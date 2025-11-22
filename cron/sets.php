<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

// Clear table and disable keys
mysqli_query($connect, 'DELETE FROM sets');
mysqli_query($connect, 'ALTER TABLE sets DISABLE KEYS');

$file = 'https://cdn.rebrickable.com/media/downloads/sets.csv.gz';

// Download the compressed file content into memory
echo "Downloading sets file...<br>";
$compressedData = file_get_contents($file);
if ($compressedData === false) {
    die('Error: Failed to download the sets file.');
}

// Decompress the data in memory
echo "Decompressing data...<br>";
$csvData = gzdecode($compressedData);
if ($csvData === false) {
    die('Error: Failed to decompress the file.');
}

// Convert to array of lines for processing
$lines = explode("\n", $csvData);
$header = array_shift($lines); // Remove header
$lines = array_filter($lines); // Remove empty lines

// Count total rows
$totalRows = count($lines);

echo "Rows in File: $totalRows<hr>";

// Process data in batches
$batchSize = 1000;
$rows = [];
$counter = 0;

foreach ($lines as $line) {
    $record = str_getcsv($line);
    $record = array_map('trim', $record);

    if (count($record) != 6) continue;

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

    if (count($rows) >= $batchSize) {
        $query = 'INSERT IGNORE INTO sets (`row`,set_num,name,year,theme_id,num_parts,img_url) VALUES ' . implode(',', $rows);
        mysqli_query($connect, $query);
        $rows = [];
    }
}

// Insert remaining rows
if (count($rows)) {
    $query = 'INSERT IGNORE INTO sets (`row`,set_num,name,year,theme_id,num_parts,img_url) VALUES ' . implode(',', $rows);
    mysqli_query($connect, $query);
}

// Re-enable keys
mysqli_query($connect, 'ALTER TABLE sets ENABLE KEYS');

echo "✅ Imported $counter records successfully.";
