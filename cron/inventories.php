<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

// Clear table and disable keys
mysqli_query($connect, 'DELETE FROM inventories');
mysqli_query($connect, 'ALTER TABLE inventories DISABLE KEYS');

$file = 'https://cdn.rebrickable.com/media/downloads/inventories.csv.gz';
$tmpDir = __DIR__ . "/tmp";
if (!is_dir($tmpDir)) mkdir($tmpDir, 0777, true);

$tmpFile = tempnam($tmpDir, "inventories_");
file_put_contents($tmpFile . ".gz", file_get_contents($file));

$tmpFile = $tmpFile . ".gz";
$tmpCsv  = str_replace(".gz", ".csv", $tmpFile);


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

    if (count($record) == 3) {
        $rows[] = sprintf(
            '(%d,"%s","%s","%s")',
            $counter,
            addslashes($record[0]),
            addslashes($record[1]),
            addslashes($record[2])
        );
        $counter++;
    }

    // Insert batch
    if (count($rows) >= $batchSize) {
        $query = 'INSERT IGNORE INTO inventories (`row`,id,version,set_num) VALUES ' . implode(',', $rows);
        mysqli_query($connect, $query);
        $rows = [];
    }
}

// Insert remaining rows
if (count($rows)) {
    $query = 'INSERT IGNORE INTO inventories (`row`,id,version,set_num) VALUES ' . implode(',', $rows);
    mysqli_query($connect, $query);
}

fclose($handle);
unlink($tmpCsv);

// Re-enable keys
mysqli_query($connect, 'ALTER TABLE inventories ENABLE KEYS');

echo "✅ Imported $counter records successfully.";
?>
