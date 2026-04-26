<?php

header('Content-Type: application/json; charset=utf-8');

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

$themes = array();

$query = 'SELECT 
    id, name, parent_id
FROM themes
ORDER BY name';

$result = mysqli_query($connect, $query);
while ($theme = mysqli_fetch_assoc($result)) {
    $themes[] = $theme;
}

echo json_encode($themes);
