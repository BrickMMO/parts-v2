<?php
include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

$query = 'SELECT * FROM themes';
$result = mysqli_query($connect, $query);

$themes = array();

while ($theme = mysqli_fetch_assoc($result)) {
    $themes[] = $theme;
}

echo json_encode($themes);

?>