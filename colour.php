<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

$query = 'SELECT *
    FROM colors
    WHERE id = "'.$_GET['id'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$colour = mysqli_fetch_assoc($result);

define('PAGE_TITLE', $colour['name'].' | Colour');

include('includes/header.php');

?>

<h1><?=$colour['name']?></h1>

<nav>
    
    <a href="<?=SITE_URL?>">Home</a> &gt;
    <a href="<?=SITE_URL?>/colours.php">Colours</a> &gt; 
    <?=$colour['name']?>

</nav>

<hr>

<nav>
    <button class="w3-button w3-large w3-<?=!isset($_GET['tab']) ? 'green' : 'light-grey'?>" onclick="window.location='colour.php?id=<?=$_GET['id']?>';">Details</button>
    <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'parts') ? 'green' : 'light-grey'?>" onclick="window.location='colour.php?id=<?=$_GET['id']?>&tab=parts';">Parts</button>
</nav>

<?php if(!isset($_GET['tab'])): ?>

    <main class="w3-row w3-margin-top" style="align-items: start;">

        <div class="w3-col s8">
            COLOR BOX
        </div>
        <div class="w3-col s4">
            <table class="w3-table w3-striped w3-bordered">
                <thead>
                    <tr class="w3-dark-grey">
                        <th>Name</th>
                        <th><?=$colour['name']?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>RGB</td>
                        <td>RGB VALURE - LINKABLE</td>
                    </tr>
                    <tr>
                        <td>Transparent</td>
                        <td>TRU OR FALSE</td>
                    </tr>
                    <tr>
                        <td>Number of Parts</td>
                        <td>
                            <a href="<?=SITE_URL?>colour.php?id=<?=$set['set_num']?>&tab=minifigs">PARTS LINKABLE</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Number of Sets</td>
                        <td>
                            <a href="<?=SITE_URL?>colour.php?id=<?=$set['set_num']?>&tab=sets">SETS LINKABLE</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Active</td>
                        <td>
                            FROM YEAR - TO YEAR
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

        $query = 'SELECT DISTINCT parts.part_num, 
            parts.name, 
            inventory_parts.is_spare,
            inventory_parts.img_url,
            colors.rgb,
            colors.id AS color_id,
            part_categories.id AS category_id,
            part_categories.name AS category_name
            FROM parts
            LEFT JOIN inventory_parts
            ON inventory_parts.part_num = parts.part_num
            LEFT JOIN colors
            ON colors.id = inventory_parts.color_id
            LEFT JOIN part_categories
            ON part_categories.id = parts.part_cat_id
            WHERE colors.id = "'.$colour['id'].'"
            ORDER BY parts.name
            -- LIMIT '.$offset.', '.$results_per_page;
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
                                    <img src="<?=$part['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
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
                                    <a href="<?=SITE_URL?>element.php?id=<?=$part['part_num']?>&colour=<?=$part['color_id']?>"><?=$part['part_num']?></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <?php if($part['category_id']): ?>
                                        <a href="<?=SITE_URL?>category.php?id=<?=$part['category_id']?>"><?=$part['category_name']?></a>
                                    <?php else: ?>
                                        <span class="w3-text-grey">No Category</span>
                                    <?php endif; ?>
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

    <?php /*
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
                echo '<a href="'.SITE_URL.'theme.php?id='.$_GET['id'];
                if($i > 1) echo '&page='.$i;
                echo '" class="w3-button';
                if($i == $current_page) echo ' w3-border';
                echo '">'.$i.'</a>';
            }

            ?>

        </div>

    </nav>
    */ ?>

<?php endif; ?>


<?php include('includes/footer.php'); ?>