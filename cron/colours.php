<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

// Clear table and disable keys for speed
mysqli_query($connect, 'DELETE FROM colors');
mysqli_query($connect, 'ALTER TABLE colors DISABLE KEYS');

$file = 'https://cdn.rebrickable.com/media/downloads/colors.csv.gz';
$tmpFile = tempnam(sys_get_temp_dir(), 'colors') . '.gz';
file_put_contents($tmpFile, file_get_contents($file));

$tmpCsv = str_replace('.gz', '.csv', $tmpFile);

// Decompress while writing to CSV file
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

    if (count($record) == 8) {
        $rows[] = sprintf(
            '(%d,"%s","%s","%s","%s","%s","%s","%s","%s")',
            $counter,
            addslashes($record[0]),
            addslashes($record[1]),
            addslashes($record[2]),
            addslashes($record[3]),
            addslashes($record[4]),
            addslashes($record[5]),
            addslashes($record[6]),
            addslashes($record[7])
        );
        $counter++;
    }

    // Insert batch
    if (count($rows) >= $batchSize) {
        $query = 'INSERT IGNORE INTO colors (`row`,id,name,rgb,is_trans,num_parts,num_sets,y1,y2) VALUES ' . implode(',', $rows);
        mysqli_query($connect, $query);
        $rows = [];
    }
}

// Insert any remaining rows
if (count($rows)) {
    $query = 'INSERT IGNORE INTO colors (`row`,id,name,rgb,is_trans,num_parts,num_sets,y1,y2) VALUES ' . implode(',', $rows);
    mysqli_query($connect, $query);
}

fclose($handle);
unlink($tmpCsv);

// Re-enable keys
mysqli_query($connect, 'ALTER TABLE colors ENABLE KEYS');

echo "✅ Imported $counter records successfully.";
?>
