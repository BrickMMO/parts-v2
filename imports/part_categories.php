<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

$file = 'https://cdn.rebrickable.com/media/downloads/part_categories.csv.gz';

$tmpFile = tempnam(sys_get_temp_dir(), 'part_categories') . '.gz';
file_put_contents($tmpFile, file_get_contents($file));

$handle = gzopen($tmpFile, 'r');

$contents = '';
while (!gzeof($handle)) 
{
    $contents .= gzread($handle, 4096);
}

gzclose($handle);
unlink($tmpFile);

$rows = explode("\n", $contents);

echo 'Rows in File: '.count($rows).'<hr>';

for ($i = count($rows) - 1; $i > 0; $i --)
{

    $record = $rows[$i];
    $record = str_getcsv($record);
    $record = array_map('trim', $record);

    /*
    echo '<pre>';
    print_r($record);
    echo '</pre>';
    */

    if(count($record) == 2)
    {

        $query = 'INSERT IGNORE INTO colors (
                id,
                name
            ) VALUES (
                "'.addslashes($record[0]).'",
                "'.addslashes($record[1]).'"
            )';
        mysqli_query($connect, $query);

        echo 'Inserting Record<br>';
        echo $query . '<br>';
        echo 'Added Rows: '.mysqli_affected_rows($connect).'<br>';

    }
    else
    {

        echo 'Invalid Record<br>';

        echo '<pre>';
        print_r($record);
        echo '</pre>';

    }

    echo '<hr>';

}

echo 'IMPORT COMPLETE';