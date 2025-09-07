<?php

ini_set('memory_limit', '512M');
set_time_limit(0);

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

$file = 'https://cdn.rebrickable.com/media/downloads/inventory_parts.csv.gz';

$tmpGzFile = tempnam(sys_get_temp_dir(), 'inventory_parts') . '.gz';
$tmpCsvFile = str_replace('.gz', '.csv', $tmpGzFile);

file_put_contents($tmpGzFile, file_get_contents($file));

$gz = gzopen($tmpGzFile, 'rb');
$out = fopen($tmpCsvFile, 'wb');

while (!gzeof($gz)) {
    fwrite($out, gzread($gz, 4096));
}

gzclose($gz);
fclose($out);
unlink($tmpGzFile);

$file = new SplFileObject($tmpCsvFile, 'r');
$file->seek(PHP_INT_MAX);
$lastLine = $file->key();

echo '<h2>Rows in File: ' . $lastLine . '</h2>';

if(isset($_GET['start'])) $start = $_GET['start'];
else $start = 0;

$records = 10000;

echo '<h2>Processing Records from ' . $start . ' to ' . ($start + $records) . '</h2>';

$query = 'INSERT INTO inventory_parts (
                    inventory_id,
                    part_num,
                    color_id,
                    quantity,
                    is_spare,
                    img_url
                ) VALUES ';
for ($i = $start; $i <= $start + $records; $i++) 
{

    $file->seek($i);
    $line = trim($file->current());

    if ($line === '') continue;

    $record = str_getcsv($line);
    $record = array_map('trim', $record);

    if($i >= $start && $i < $start + $records)
    {

        echo '<pre>';
        print_r($record);
        echo '</pre>';

        if (count($record) === 6 && is_numeric($record[0]))
        {
            $query .= '(
                    "' . addslashes($record[0]) . '",
                    "' . addslashes($record[1]) . '",
                    "' . addslashes($record[2]) . '",
                    "' . addslashes($record[3]) . '",
                    "' . addslashes($record[4]) . '",
                    "' . addslashes($record[5]) . '"
                ),';

            echo 'Inserting Record #' . $i . '<br>';

            // echo $query . '<br>';
            // echo 'Added Rows: ' . mysqli_affected_rows($connect) . '<br>';

        }
        else 
        {

            echo 'Invalid Record<br>';
            echo '<pre>';
            print_r($record);
            echo '</pre>';
        }

    }
    else
    {

        $query = rtrim($query, ',');
            
        mysqli_query($connect, $query);

        unlink($tmpCsvFile);

        echo '<h1>Redirecting...</h1>
            <h2>Added ' . ($start + $records) . ' records so far.</h2>
            <script>
            setTimeout(function() {
                window.location.href = "inventory_parts.php?start=' . ($start + $records) . '";
            }, 1000);
            </script>';
        exit;
    }

    echo '<hr>';
}
