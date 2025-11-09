<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

// Clear table and disable keys
mysqli_query($connect, 'DELETE FROM parts');
mysqli_query($connect, 'ALTER TABLE parts DISABLE KEYS');

$file = 'https://cdn.rebrickable.com/media/downloads/parts.csv.gz';
$tmpFile = tempnam(sys_get_temp_dir(), 'parts') . '.gz';
file_put_contents($tmpFile, file_get_contents($file));

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

// Stream CSV and batch insert
$handle = fopen($tmpCsv, 'r');
fgetcsv($handle); // skip header
$batchSize = 1000;
$rows = [];
$counter = 0;

while (($record = fgetcsv($handle)) !== false) {
    $record = array_map('trim', $record);

    if (count($record) != 4) continue;

    $rows[] = sprintf(
        '(%d,"%s","%s","%s","%s")',
        $counter,
        addslashes($record[0]),
        addslashes($record[1]),
        addslashes($record[2]),
        addslashes($record[3])
    );
    $counter++;

    if (count($rows) >= $batchSize) {
        $query = 'INSERT IGNORE INTO parts (`row`,part_num,name,part_cat_id,part_material) VALUES ' . implode(',', $rows);
        mysqli_query($connect, $query);
        $rows = [];
    }
}

// Insert remaining rows
if (count($rows)) {
    $query = 'INSERT IGNORE INTO parts (`row`,part_num,name,part_cat_id,part_material) VALUES ' . implode(',', $rows);
    mysqli_query($connect, $query);
}

fclose($handle);
unlink($tmpCsv);

// Re-enable keys
mysqli_query($connect, 'ALTER TABLE parts ENABLE KEYS');

echo "✅ Imported $counter records successfully.";
