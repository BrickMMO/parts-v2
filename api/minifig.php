<?php

header('Content-Type: application/json; charset=utf-8');

include('../includes/connect.php');
include('../includes/config.php');
include('../includes/functions.php');

$query = 'SELECT *
    FROM minifigs 
    WHERE fig_num = "'.$_GET['id'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$minifig = mysqli_fetch_assoc($result);

$sets = array();

$query = 'SELECT 
        sets.set_num,
        sets.name
    FROM inventory_minifigs
    JOIN inventories ON inventory_minifigs.inventory_id = inventories.id
    JOIN sets ON inventories.set_num = sets.set_num
    WHERE inventory_minifigs.fig_num = "'.$_GET['id'].'"
    AND inventories.version = 1';
$result = mysqli_query($connect, $query);

while ($set = mysqli_fetch_assoc($result))
{
    $sets[] = $set;
}

$minifig['sets'] = $sets;

/*
$parts = array();

$query ='SELECT 
        p.part_num,
        p.name AS part_name,
        c.name AS color,
        ip.quantity
    FROM inventory_minifigs im
    JOIN inventories i ON im.inventory_id = i.id
    JOIN inventory_parts ip ON i.id = ip.inventory_id
    JOIN parts p ON ip.part_num = p.part_num
    JOIN colors c ON ip.color_id = c.id
    WHERE im.fig_num = "'.$_GET['id'].'"';
$result = mysqli_query($connect, $query);

while ($part = mysqli_fetch_assoc($result))
{
    $parts[] = $part;
}

$minifig['parts'] = $parts;
*/

echo json_encode($minifig);
