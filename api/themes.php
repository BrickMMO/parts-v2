<?php

header('Content-Type: application/json');

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

$themes = array();

$query = 'SELECT
    id, name, parent_id
    FROM themes
    ORDER BY name';

$result = mysqli_query($connect, $query);
if (!$result) {
    http_response_code(500);
    echo json_encode(['error' => 'Database query failed']);
    exit;
}

echo json_encode($themes);
