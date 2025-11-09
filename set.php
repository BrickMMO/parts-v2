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

define('PAGE_TITLE', $set['name'].' | Set');

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

<nav>
    
    <a href="<?=SITE_URL?>">Home</a> &gt; 
    <a href="<?=SITE_URL?>/themes.php">Themes</a> &gt; 

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
        
        echo '<a href="'.SITE_URL.'theme.php?id='.$parent['id'].'">'.$parent['name'].'</a> &gt; ';
        
        $parent_id = $parent['parent_id'];
    }

    ?>

    <?=$set['name']?>

</nav>

<hr>

<nav>
    <button class="w3-button w3-large w3-<?=!isset($_GET['tab']) ? 'green' : 'light-grey'?>" onclick="window.location='set.php?id=<?=$_GET['id']?>';">Details</button>
    <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'parts') ? 'green' : 'light-grey'?>" onclick="window.location='set.php?id=<?=$_GET['id']?>&tab=parts';">Parts</button>
    <?php if($inventory['minifigs'] > 0): ?>
        <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'minifigs') ? 'green' : 'light-grey'?>" onclick="window.location='set.php?id=<?=$_GET['id']?>&tab=minifigs';">Minifigs</button>
    <?php endif; ?>
</nav>

<?php if(!isset($_GET['tab'])): ?>

    <main class="w3-row w3-margin-top" style="align-items: start;">

        <div class="w3-col s8">

            <?php if($set['img_url'] && url_exists($set['img_url'])): ?>
                <img src="<?= $set['img_url']; ?>" alt="<?= $set['name']; ?>" class="w3-image" style="max-width: 90%; height: auto;">
            <?php else: ?>
                <img src="<?=SITE_URL?>images/no-image.png" alt="" class="w3-image" style="max-width: 90%; height: auto;">
            <?php endif; ?>

        </div>
        <div class="w3-col s4">
            <table class="w3-table w3-striped w3-bordered">
                <thead>
                    <tr class="w3-blue">
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
                            <a href="<?=SITE_URL?>set.php?id=<?=$set['set_num']?>&tab=parts"><?=$set['num_parts']?></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Number of Minfigs</td>
                        <td>
                            <?php if($inventory['minifigs'] > 0): ?>
                                <a href="<?=SITE_URL?>set.php?id=<?=$set['set_num']?>&tab=minifigs"><?=$inventory['minifigs']?></a>
                            <?php else: ?>
                                0
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Theme</td>
                        <td>
                            <a href="<?=SITE_URL?>theme.php?id=<?=$theme['id']?>"><?=$theme['name']?></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </main>

<?php elseif($_GET['tab'] == 'parts'): ?>

    <main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE * 10;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT parts.part_num, 
            parts.name, 
            inventory_parts.is_spare,
            inventory_parts.img_url,
            inventory_parts.quantity,
            inventory_parts.is_spare,
            colors.rgb,
            colors.id AS color_id,
            colors.name AS color_name,
            part_categories.name AS category_name,
            part_categories.id AS category_id 
            FROM inventory_parts
            LEFT JOIN parts 
            ON inventory_parts.part_num = parts.part_num
            LEFT JOIN colors
            ON colors.id = inventory_parts.color_id
            LEFT JOIN part_categories
            ON parts.part_cat_id = part_categories.id
            WHERE inventory_id = "'.$inventory['id'].'"
            ORDER BY color_id, inventory_parts.part_num
            LIMIT '.$offset.', '.$results_per_page;
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($part = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(20% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                <div class="w3-card-5 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-purple">
                        <h6 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$part['name']?></h6>
                    </header>
                    <div class="w3-container w3-center w3-padding" style="flex: 1 1 auto;">
                        <div style="position: relative; width: 100%; padding-top: 100%;">

                            <a href="<?=SITE_URL?>element.php?id=<?=$part['part_num']?>&colour=<?=$part['color_id']?>">

                                <?php if($part['img_url'] && url_exists($part['img_url'])): ?>
                                    <img src="<?=$part['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;" loading="lazy">
                                <?php else: ?>
                                    <img src="<?=SITE_URL?>images/no-image.png" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;" loading="lazy">
                                <?php endif; ?>
                                
                            </a>
                            
                        </div>  
                    </div>

                    <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>
                                    <a href="<?=SITE_URL?>element.php?id=<?=$part['part_num']?>&colour=<?=$part['color_id']?>"><?=$part['part_num']?></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Qty: 
                                    <?=$part['quantity']?>
                                    <?php if($part['is_spare'] == 'True'): ?>
                                        &#9873;
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div style="display: inline-block; vertical-align: middle; width: 16px; height: 16px; background-color:#<?=$part['rgb']?>;"></div>
                                    <a href="<?=SITE_URL?>colour.php?id=<?=$part['color_id']?>">#<?=$part['rgb']?></a>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="<?=SITE_URL?>category.php?id=<?=$part['category_id']?>"><?=$part['category_name']?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!--
                    <div class="w3-container w3-center w3-padding-16">
                        <a href="<?=SITE_URL?>part.php?id=<?=$part['part_num']?>">Part Details</a>
                    </div>
                    -->
                </div>
            </div>
            
        <?php endwhile; ?>
        
    </main>

    <nav class="w3-text-center w3-section">

        <div class="w3-bar">            

            <?php
                
            $query = 'SELECT *
                FROM inventory_parts
                WHERE inventory_id = "'.$inventory['id'].'"';
            $result = mysqli_query($connect, $query);

            $count_row = mysqli_num_rows($result);
            $totalPages = ceil($count_row / $results_per_page);

            // Display pagination links
            for ($i = 1; $i <= $totalPages; $i++) 
            {
                echo '<a href="'.SITE_URL.'set.php?id='.$_GET['id'].'&tab=parts';
                if($i > 1) echo '&page='.$i;
                echo '" class="w3-button';
                if($i == $current_page) echo ' w3-border';
                echo '">'.$i.'</a>';
            }

            ?>

        </div>

    </nav>

<?php elseif($_GET['tab'] == 'minifigs'): ?>

<main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE * 5;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT *
            FROM inventory_minifigs
            LEFT JOIN minifigs 
            ON inventory_minifigs.fig_num = minifigs.fig_num
            WHERE inventory_id = "'.$inventory['id'].'"
            ORDER BY inventory_minifigs.fig_num
            -- LIMIT '.$offset.', '.$results_per_page;
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($minifig = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(20% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                <div class="w3-card-5 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-indigo">
                        <h6 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$minifig['name']?></h6>
                    </header>
                    <div class="w3-container w3-center w3-padding" style="flex: 1 1 auto;">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="<?=SITE_URL?>minifig.php?id=<?=$minifig['fig_num']?>">

                                <?php if($minifig['img_url'] && url_exists($minifig['img_url'])): ?>
                                    <img src="<?=$minifig['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php else: ?>
                                    <img src="<?=SITE_URL?>images/no-image.png" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php endif; ?>

                            </a>
                        </div>  
                    </div>

                    <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>
                                    <a href="<?=SITE_URL?>minifig.php?id=<?=$minifig['fig_num']?>"><?=$minifig['fig_num']?></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Parts: 
                                    <?=$minifig['num_parts']?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                </div>
            </div>
            
        <?php endwhile; ?>
        
    </main>


<?php endif; ?>


<?php include('includes/footer.php'); ?>