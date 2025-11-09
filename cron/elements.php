<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

// Clear table and disable keys for speed
mysqli_query($connect, 'DELETE FROM elements');
mysqli_query($connect, 'ALTER TABLE elements DISABLE KEYS');

$file = 'https://cdn.rebrickable.com/media/downloads/elements.csv.gz';
$tmpFile = tempnam(sys_get_temp_dir(), 'elements') . '.gz';
file_put_contents($tmpFile, file_get_contents($file));

$tmpCsv = str_replace('.gz', '.csv', $tmpFile);

// Decompress while writing to CSV
$gz = gzopen($tmpFile, 'rb');
$out = fopen($tmpCsv, 'wb');
while (!gzeof($gz)) {
    fwrite($out, gzread($gz, 8192));
}
gzclose($gz);
fclose($out);
unlink($tmpFile);

// First pass: count rows
$handle = fopen($tmpCsv, 'r');
fgetcsv($handle); // skip header
$totalRows = 0;
while (fgetcsv($handle) !== false) {
    $totalRows++;
}
fclose($handle);

echo 'Rows in File: ' . $totalRows . '<hr>';

// Second pass: insert data in batches
$handle = fopen($tmpCsv, 'r');
fgetcsv($handle); // skip header

$batchSize = 1000;
$rows = [];
$counter = 0;

while (($record = fgetcsv($handle)) !== false) {
    $record = array_map('trim', $record);

    if (count($record) == 4) {
        $rows[] = sprintf(
            '(%d,"%s","%s","%s","%s")',
            $counter,
            addslashes($record[0]),
            addslashes($record[1]),
            addslashes($record[2]),
            addslashes($record[3])
        );
        $counter++;
    }

    // Insert batch
    if (count($rows) >= $batchSize) {
        $query = 'INSERT IGNORE INTO elements (`row`,element_id,part_num,color_id,design_id) VALUES ' . implode(',', $rows);
        mysqli_query($connect, $query);
        $rows = [];
    }
}

// Insert any remaining rows
if (count($rows)) {
    $query = 'INSERT IGNORE INTO elements (`row`,element_id,part_num,color_id,design_id) VALUES ' . implode(',', $rows);
    mysqli_query($connect, $query);
}

fclose($handle);
unlink($tmpCsv);

// Re-enable keys
mysqli_query($connect, 'ALTER TABLE elements ENABLE KEYS');

echo "✅ Imported $counter records successfully.";
?>
