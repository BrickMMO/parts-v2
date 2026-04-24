<?php

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

header('Content-Type: application/json; charset=utf-8');

$query = 'SELECT *
        FROM minifigs
        LIMIT 6';

$result = mysqli_query($connect, $query);

$minifig = mysqli_fetch_assoc($result);

$sets = array();

while ($row = $result->fetch_row()) 
{
        $sets[] = $row;
}

$minifig['sets'] = $sets;

echo json_encode($minifig);

?>