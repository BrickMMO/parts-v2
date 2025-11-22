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

// Download the compressed file content into memory
echo "Downloading inventory parts file...<br>";
$compressedData = file_get_contents($fileUrl);
if ($compressedData === false) {
    die('Error: Failed to download the inventory parts file.');
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

// Count rows
$totalRows = count($lines);
echo 'Rows in File: '.$totalRows.'<hr>';

// Process and batch insert
$batchSize = 5000;
$rows = [];
$counter = 0;

foreach ($lines as $line) {
    $record = str_getcsv($line);
    
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

mysqli_query($connect, 'ALTER TABLE inventory_parts ENABLE KEYS');

echo "✅ Imported $counter records successfully.";
