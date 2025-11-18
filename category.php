<?php

if(!isset($_GET['page']))
{
    header('Location: '.$_SERVER['REQUEST_URI'].'&page=1');
    exit;
}

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

$query = 'SELECT *
    FROM part_categories
    WHERE id = "'.$_GET['id'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$category = mysqli_fetch_assoc($result);

define('PAGE_TITLE', $category['name'].' | Category');

include('includes/header.php');

?>

<h1><?=$category['name']?></h1> 

<nav>
    
    <a href="<?=SITE_URL?>/">Home</a> &gt; 
    <a href="<?=SITE_URL?>/categories.php">CategoRRRRRRries</a> &gt; 
    <?=$category['name']?>

</nav>

<hr>

<main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE * 5;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT parts.part_num, 
            parts.name, 
            colors.rgb,
            colors.id AS color_id,
            part_categories.id AS category_id,
            part_categories.name AS category_name
            FROM parts
            LEFT JOIN elements
            ON elements.part_num = parts.part_num
            LEFT JOIN colors
            ON elements.color_id = colors.id
            LEFT JOIN part_categories
            ON part_categories.id = parts.part_cat_id
            WHERE part_cat_id = "'.$category['id'].'"
            ORDER BY color_id, elements.part_num
            LIMIT '.$offset.', '.$results_per_page;
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($part = mysqli_fetch_assoc($result)): ?>

            <?php

            $query = 'SELECT img_url
                FROM inventory_parts
                WHERE inventory_parts.part_num = "'.$part['part_num'].'"
                AND img_url IS NOT NULL
                ORDER BY RAND()
                LIMIT 1';
            $result2 = mysqli_query($connect, $query);

            if(mysqli_num_rows($result2))
            {
                $image = mysqli_fetch_assoc($result2);
            }
            else
            {
                $image = false;
            }

            ?>

            <div style="width: calc(20% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                <div class="w3-card-5 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-purple">
                        <h6 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$part['name']?></h6>
                    </header>
                    <div class="w3-container w3-center w3-padding" style="flex: 1 1 auto;">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="<?=SITE_URL?>/element.php?id=<?=$part['part_num']?>&colour=<?=$part['color_id']?>">

                                <?php if($image && $image['img_url'] && url_exists($image['img_url'])): ?>
                                    <img src="<?=$image['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php else: ?>
                                    <img src="<?=SITE_URL?>/images/no-image.png" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                                <?php endif; ?>
                                
                            </a>
                        </div>  
                    </div>

                    <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>
                                    <a href="<?=SITE_URL?>/element.php?id=<?=$part['part_num']?>&colour=<?=$part['color_id']?>"><?=$part['part_num']?></a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a href="<?=SITE_URL?>/category.php?id=<?=$part['category_id']?>"><?=$part['category_name']?></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!--
                    <div class="w3-container w3-center w3-padding-16">
                        <a href="<?=SITE_URL?>/part.php?id=<?=$part['part_num']?>">Part Details</a>
                    </div>
                    -->
                </div>
            </div>
            
        <?php endwhile; ?>
        
    </main>

    <nav class="w3-text-center w3-section">

        <div class="w3-bar">            

            <?php
                
            $query = 'SELECT parts.part_num
                FROM parts
                WHERE part_cat_id = "'.$category['id'].'"';
            $result = mysqli_query($connect, $query);

            $count_row = mysqli_num_rows($result);
            $totalPages = ceil($count_row / $results_per_page);

            // Display pagination links
            for ($i = 1; $i <= $totalPages; $i++) 
            {
                echo '<a href="'.SITE_URL.'category.php?id='.$_GET['id'];
                if($i > 1) echo '&page='.$i;
                echo '" class="w3-button';
                if($i == $current_page) echo ' w3-border';
                echo '">'.$i.'</a>';
            }

            ?>

        </div>

    </nav>

<?php include('includes/footer.php'); ?>