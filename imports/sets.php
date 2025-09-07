<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

$file = 'https://cdn.rebrickable.com/media/downloads/sets.csv.gz';

$tmpFile = tempnam(sys_get_temp_dir(), 'sets') . '.gz';
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

    if(count($record) == 6)
    {

        $query = 'INSERT IGNORE INTO sets (
                set_num,
                name,
                year,
                theme_id,
                num_parts,
                img_url
            ) VALUES (
                "'.addslashes($record[0]).'",
                "'.addslashes($record[1]).'",
                "'.addslashes($record[2]).'",
                "'.addslashes($record[3]).'",
                "'.addslashes($record[4]).'",
                "'.addslashes($record[5]).'"
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