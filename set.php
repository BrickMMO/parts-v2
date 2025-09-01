<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

$query = 'SELECT *
    FROM sets
    WHERE set_num = "'.$_GET['id'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$set = mysqli_fetch_assoc($result);

define('PAGE_TITLE', $set['name'].' | Sets');

include('includes/header.php');

$query = 'SELECT *
    FROM themes
    WHERE id = "'.$set['theme_id'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$theme = mysqli_fetch_assoc($result);

$query = 'SELECT *,(
        SELECT COUNT(*) 
        FROM inventory_parts
        WHERE inventory_id = inventories.id
    ) AS parts,(
        SELECT COUNT(*) 
        FROM inventory_minifigs
        WHERE inventory_id = inventories.id
    ) AS minifigs
    FROM inventories
    WHERE set_num = "'.$set['set_num'].'"
    ORDER BY version DESC
    LIMIT 1';
$result = mysqli_query($connect, $query);

$inventory = mysqli_fetch_assoc($result);

?>

<h1><?=$set['name']?></h1>

<a href="/">Home</a> &gt; 

<?php

$parent_id = $set['theme_id'];

while($parent_id)
{
    $query = 'SELECT *
        FROM themes
        WHERE id = "'.$parent_id.'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);
    $parent = mysqli_fetch_assoc($result);
    
    echo '<a href="theme.php?id='.$parent['id'].'">'.$parent['name'].'</a> &gt; ';
    
    $parent_id = $parent['parent_id'];
}

?>

<?=$set['name']?>

<hr>

<nav>
    <button class="w3-button w3-large w3-<?=!isset($_GET['tab']) ? 'green' : 'light-grey'?>" onclick="window.location='set.php?id=<?=$_GET['id']?>';">Details</button>
    <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'parts') ? 'green' : 'light-grey'?>" onclick="window.location='set.php?id=<?=$_GET['id']?>&tab=parts';">Parts</button>
    <?php if($inventory['minifigs'] > 0): ?>
        <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'minifigs') ? 'green' : 'light-grey'?>" onclick="window.location='set.php?id=<?=$_GET['id']?>&tab=minifigs';">Minifigs</button>
    <?php endif; ?>
</nav>

<?php if(!isset($_GET['tab'])): ?>

    <main class="w3-row">

        <div class="w3-col s8">
            <img src="<?= $set['img_url']; ?>" alt="<?= $set['name']; ?>" class="w3-image">
        </div>
        <div class="w3-col s4">
            <table class="w3-table w3-striped w3-bordered">
                <thead>
                    <tr class="w3-green">
                        <th>Number</th>
                        <th><?=$set['set_num']?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td><?=$set['name']?></td>
                    </tr>
                    <tr>
                        <td>Year</td>
                        <td><?=$set['year']?></td>
                    </tr>
                    <tr>
                        <td>Number of Parts</td>
                        <td>
                            <a href="set.php?id=<?=$set['set_num']?>&tab=parts"><?=$set['num_parts']?></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Number of Minfigs</td>
                        <td>
                            <?php if($inventory['minifigs'] > 0): ?>
                                <a href="set.php?id=<?=$set['set_num']?>&tab=minifigs"><?=$inventory['minifigs']?></a>
                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Theme</td>
                        <td>
                            <a href="theme.php?id=<?=$theme['id']?>"><?=$theme['name']?></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

<?php elseif($_GET['tab'] == 'parts'): ?>

    <main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT *
            FROM sets 
            WHERE theme_id = "'.$_GET['id'].'"
            ORDER BY year DESC, name
            LIMIT '.$offset.', '.$results_per_page;
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($set = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(25% - 16px); box-sizing: border-box;">
                
                <div class="w3-card-4 w3-margin-top w3-margin-bottom" style="max-width:100%;">
                    <header class="w3-container w3-red">
                        <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$set['name']?></h4>
                    </header>
                    <div class="w3-container w3-center w3-padding">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="set.php?id=<?=$set['set_num']?>">
                                <img src="<?=$set['img_url']?>" alt="" style="max-width:80%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 100%; max-height: 100%; object-fit: contain;">
                            </a>
                        </div>  
                    </div>
                    <div class="w3-container w3-center w3-padding-16">
                        <a href="set.php?id=<?=$set['set_num']?>">Set Details</a>
                    </div>
                </div>

            </div>
            
        <?php endwhile; ?>
        
    </main>

    <nav class="w3-text-center w3-section">

        <div class="w3-bar">            

            <?php
                
            $query = 'SELECT *
                FROM sets
                WHERE theme_id = "'.$_GET['id'].'"
                ORDER BY year DESC, name';

            $result = mysqli_query($connect, $query);

            $count_row = mysqli_num_rows($result);
            $totalPages = ceil($count_row / $results_per_page);

            // Display pagination links
            for ($i = 1; $i <= $totalPages; $i++) 
            {
                echo '<a href="/theme.php?id='.$_GET['id'];
                if($i > 1) echo '&page='.$i;
                echo '" class="w3-button';
                if($i == $current_page) echo ' w3-border';
                echo '">'.$i.'</a>';
            }
            ?>

        </div>

    </nav>

<?php elseif($_GET['tab'] == 'minifigs'): ?>



<?php endif; ?>



<?php
/* Select all the parts that are connected to the selected set 
$query = 'SELECT inventory_parts.*,
        parts.name AS part_name,
        part_categories.name AS category_name
        FROM inventory_parts
        LEFT JOIN parts 
        ON inventory_parts.part_num = parts.part_num
        LEFT JOIN part_categories
        ON parts.part_cat_id = part_categories.id
        WHERE inventory_id = ' . $set['id'] . '
        ORDER BY parts.name';
$result = mysqli_query($connect, $query);
*/
?>

<div class="container mt-3">
    <h3>Inventory</h3>
    <hr>
    <div class="row">
        <?php while ($part = mysqli_fetch_assoc($result)) : ?>
        <?php
            /* Fetch the colour used in this set
            $query = 'SELECT *
            FROM colors
            WHERE id = ' . $part['color_id'] . '
            LIMIT 1';
            $result2 = mysqli_query($connect, $query);
            $color = mysqli_fetch_assoc($result2);
            */
            ?>
        <div class="col-3 mb-4">
            <div class="card justify-content-center parts-theme-card">
                <div class="parts-card-img-container p-2">
                    <img class="rounded mx-auto d-block" src=<?= $part['img_url']; ?> alt="<?= $part['part_name']; ?>">
                </div>
                <div class="card-body parts-card-body">
                    <h4 class="card-title parts-theme-card-title"><?= $part['part_name']; ?></h4>
                    <h6 class="card-subtitle text-muted">Category: <?= $part['category_name']; ?></h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Part No: <?= $part['part_num']; ?></li>
                    <li class="list-group-item">Color: <?= $color['name']; ?></li>
                    <li class="list-group-item">Quantity: <?= $part['quantity']; ?></li>
                    <li class="list-group-item">
                        <a href="part.php?id=<?= $part['part_num'] ?>">Part Details</a>
                    </li>
                </ul>
            </div>
        </div>
    
        <?php endwhile; ?>
    </div>
</div>



<?php include('includes/footer.php'); ?>