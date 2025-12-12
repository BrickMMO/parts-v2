<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

// Get the element based on part_num and color_id
$query = 'SELECT 
        elements.element_id,
        elements.part_num,
        elements.color_id,
        elements.design_id,
        parts.name AS part_name,
        parts.part_cat_id,
        parts.part_material,
        colors.name AS color_name,
        colors.rgb,
        colors.is_trans,
        part_categories.name AS category_name
    FROM elements
    LEFT JOIN parts ON elements.part_num = parts.part_num
    LEFT JOIN colors ON elements.color_id = colors.id
    LEFT JOIN part_categories ON parts.part_cat_id = part_categories.id
    WHERE elements.part_num = "'.mysqli_real_escape_string($connect, $_GET['id']).'"
    AND elements.color_id = "'.mysqli_real_escape_string($connect, $_GET['colour']).'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(!mysqli_num_rows($result))
{
    die('Element not found');
}

$element = mysqli_fetch_assoc($result);

define('PAGE_TITLE', $element['part_name'].' ('.$element['color_name'].') | Element');

include('includes/header.php');

// Get an image for this part/color combination
$query = 'SELECT img_url
    FROM inventory_parts
    WHERE part_num = "'.$element['part_num'].'"
    AND color_id = "'.$element['color_id'].'"
    AND img_url IS NOT NULL
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(mysqli_num_rows($result))
{
    $image = mysqli_fetch_assoc($result);
}
else
{
    $image = false;
}

?>

<h1><?=$element['part_name']?> (<?=$element['color_name']?>)</h1>

<nav>
    <a href="<?=SITE_URL?>/">Home</a> &gt; 
    <a href="<?=SITE_URL?>/categories.php">Categories</a> &gt; 
    <a href="<?=SITE_URL?>/category.php?id=<?=$element['part_cat_id']?>"><?=$element['category_name']?></a> &gt; 
    <?=$element['part_name']?> (<?=$element['color_name']?>)
</nav>

<hr>

<nav>
    <button class="w3-button w3-large w3-<?=!isset($_GET['tab']) ? 'purple' : 'light-grey'?>" onclick="window.location='element.php?id=<?=$_GET['id']?>&colour=<?=$_GET['colour']?>';">Details</button>
    <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'colours') ? 'purple' : 'light-grey'?>" onclick="window.location='element.php?id=<?=$_GET['id']?>&colour=<?=$_GET['colour']?>&tab=colours';">Colours</button>
    <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'sets') ? 'purple' : 'light-grey'?>" onclick="window.location='element.php?id=<?=$_GET['id']?>&colour=<?=$_GET['colour']?>&tab=sets';">Sets</button>
</nav>

<?php if(!isset($_GET['tab'])): ?>

    <main class="w3-row w3-margin-top" style="align-items: start;">

        <div class="w3-col s8">
            <?php if($image && $image['img_url'] && url_exists($image['img_url'])): ?>
                <img src="<?=$image['img_url']?>" alt="<?=$element['part_name']?>" class="w3-image" style="max-width: 90%; height: auto;">
            <?php else: ?>
                <img src="<?=SITE_URL?>/images/no-image.png" alt="" class="w3-image" style="max-width: 90%; height: auto;">
            <?php endif; ?>
        </div>

        <div class="w3-col s4">
            <table class="w3-table w3-striped w3-bordered">
                <thead>
                    <tr class="w3-purple">
                        <th>Part Number</th>
                        <th><?=$element['part_num']?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Name</td>
                        <td><?=$element['part_name']?></td>
                    </tr>
                    <tr>
                        <td>Element ID</td>
                        <td><?=$element['element_id']?></td>
                    </tr>
                    <tr>
                        <td>Design ID</td>
                        <td><?=$element['design_id']?></td>
                    </tr>
                    <tr>
                        <td>Color</td>
                        <td>
                            <div style="display: inline-block; vertical-align: middle; width: 16px; height: 16px; background-color:#<?=$element['rgb']?>;"></div>
                            <a href="<?=SITE_URL?>/colour.php?id=<?=$element['color_id']?>"><?=$element['color_name']?></a>
                        </td>
                    </tr>
                    <tr>
                        <td>Category</td>
                        <td>
                            <a href="<?=SITE_URL?>/category.php?id=<?=$element['part_cat_id']?>"><?=$element['category_name']?></a>
                        </td>
                    </tr>
                    <?php if($element['part_material']): ?>
                    <tr>
                        <td>Material</td>
                        <td><?=$element['part_material']?></td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </main>

<?php elseif($_GET['tab'] == 'colours'): ?>

    <main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        // Get all colors this part comes in
        $query = 'SELECT 
                elements.color_id,
                colors.name AS color_name,
                colors.rgb,
                COUNT(DISTINCT inventory_parts.inventory_id) AS set_count
            FROM elements
            LEFT JOIN colors ON elements.color_id = colors.id
            LEFT JOIN inventory_parts ON inventory_parts.part_num = elements.part_num 
                AND inventory_parts.color_id = elements.color_id
            WHERE elements.part_num = "'.$element['part_num'].'"
            GROUP BY elements.color_id
            ORDER BY colors.name';
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($color = mysqli_fetch_assoc($result)): ?>

            <?php
            // Get an image for this color
            $query2 = 'SELECT img_url
                FROM inventory_parts
                WHERE part_num = "'.$element['part_num'].'"
                AND color_id = "'.$color['color_id'].'"
                AND img_url IS NOT NULL
                LIMIT 1';
            $result2 = mysqli_query($connect, $query2);
            
            if(mysqli_num_rows($result2))
            {
                $color_image = mysqli_fetch_assoc($result2);
            }
            else
            {
                $color_image = false;
            }
            ?>

            <div style="width: calc(20% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                <div class="w3-card-5 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-purple">
                        <h6 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$color['color_name']?></h6>
                    </header>
                    <div class="w3-container w3-center w3-padding" style="flex: 1 1 auto;">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="<?=SITE_URL?>/element.php?id=<?=$element['part_num']?>&colour=<?=$color['color_id']?>">
                                <?php if($color_image && $color_image['img_url'] && url_exists($color_image['img_url'])): ?>
                                    <img src="<?=$color_image['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto; max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php else: ?>
                                    <img src="<?=SITE_URL?>/images/no-image.png" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto; max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>

                    <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>
                                    <div style="display: inline-block; vertical-align: middle; width: 16px; height: 16px; background-color:#<?=$color['rgb']?>;"></div>
                                    #<?=$color['rgb']?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <?=$color['set_count']?> sets
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php endwhile; ?>
        
    </main>

<?php elseif($_GET['tab'] == 'sets'): ?>

    <main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE * 5;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        // Get all sets that contain this part in this color
        $query = 'SELECT DISTINCT
                sets.set_num,
                sets.name,
                sets.year,
                sets.img_url,
                inventory_parts.quantity,
                inventory_parts.is_spare
            FROM inventory_parts
            LEFT JOIN inventories ON inventory_parts.inventory_id = inventories.id
            LEFT JOIN sets ON inventories.set_num = sets.set_num
            WHERE inventory_parts.part_num = "'.$element['part_num'].'"
            AND inventory_parts.color_id = "'.$element['color_id'].'"
            ORDER BY sets.year DESC, sets.name
            LIMIT '.$offset.', '.$results_per_page;
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($set = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(20% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                <div class="w3-card-5 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-blue">
                        <h6 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$set['name']?></h6>
                    </header>
                    <div class="w3-container w3-center w3-padding" style="flex: 1 1 auto;">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="<?=SITE_URL?>/set.php?id=<?=$set['set_num']?>">
                                <?php if($set['img_url'] && url_exists($set['img_url'])): ?>
                                    <img src="<?=$set['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto; max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php else: ?>
                                    <img src="<?=SITE_URL?>/images/no-image.png" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto; max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php endif; ?>
                            </a>
                        </div>
                    </div>

                    <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>
                                    <a href="<?=SITE_URL?>/set.php?id=<?=$set['set_num']?>"><?=$set['set_num']?></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Year: <?=$set['year']?>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Qty: <?=$set['quantity']?>
                                    <?php if($set['is_spare'] == 'True'): ?>
                                        &#9873;
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
        <?php endwhile; ?>
        
    </main>

    <nav class="w3-text-center w3-section">

        <div class="w3-bar">            

            <?php
                
            $query = 'SELECT COUNT(DISTINCT sets.set_num) AS total
                FROM inventory_parts
                LEFT JOIN inventories ON inventory_parts.inventory_id = inventories.id
                LEFT JOIN sets ON inventories.set_num = sets.set_num
                WHERE inventory_parts.part_num = "'.$element['part_num'].'"
                AND inventory_parts.color_id = "'.$element['color_id'].'"';
            $result = mysqli_query($connect, $query);

            $count_row = mysqli_fetch_assoc($result)['total'];
            $totalPages = ceil($count_row / $results_per_page);

            // Display pagination links
            for ($i = 1; $i <= $totalPages; $i++) 
            {
                echo '<a href="'.SITE_URL.'element.php?id='.$_GET['id'].'&colour='.$_GET['colour'].'&tab=sets';
                if($i > 1) echo '&page='.$i;
                echo '" class="w3-button';
                if($i == $current_page) echo ' w3-border';
                echo '">'.$i.'</a>';
            }

            ?>

        </div>

    </nav>

<?php endif; ?>

<?php include('includes/footer.php'); ?>