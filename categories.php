<?php

if(!isset($_GET['page']))
{
    header('Location: '.$_SERVER['REQUEST_URI'].'?page=1');
    exit;
}

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', 'Categories');

include('includes/header.php');

?>

<h1>LEGO&reg; Categories</h1>

<nav>
    
    <a href="<?=SITE_URL?>">Home</a> &gt; 
    Categories

</nav>

<main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <?php

    $results_per_page = PER_PAGE;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $results_per_page;

    $query = 'SELECT *,(
            SELECT img_url 
            FROM inventory_parts
            INNER JOIN parts
            ON inventory_parts.part_num = parts.part_num
            WHERE img_url IS NOT NULL
            AND part_cat_id = part_categories.id
            LIMIT 1
        ) AS img_url
        FROM part_categories
        ORDER BY part_categories.name
        LIMIT '.$offset.', '.$results_per_page;
    $result = mysqli_query($connect, $query);

    ?>
    
    <?php while ($category = mysqli_fetch_assoc($result)): ?>

        <div style="width: calc(25% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
            
            <div class="w3-card-4 w3-margin-top" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                <header class="w3-container w3-green">
                    <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$category['name']?></h4>
                </header>
                <div class="w3-container w3-center w3-padding">
                    <div style="position: relative; width: 100%; padding-top: 100%;">
                        <a href="<?=SITE_URL?>category.php?id=<?=$category['id']?>">
                            <img src="<?=$category['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                        </a>
                    </div>  
                </div>

                <!--
                <div class="w3-container w3-center w3-padding-16">
                    <a href="<?=SITE_URL?>category.php?id=<?=$category['id']?>">Theme Details</a>
                </div>
                -->
            </div>

        </div>
        
    <?php endwhile; ?>
    
</main>

<nav class="w3-text-center w3-section">

    <div class="w3-bar">            

        <?php
            
        $query = 'SELECT *,(
                SELECT MAX(year) 
                FROM sets 
                WHERE sets.theme_id = themes.id
            ) AS year
            FROM themes 
            WHERE parent_id = 0
            HAVING year IS NOT NULL';
        $result = mysqli_query($connect, $query);

        $count_row = mysqli_num_rows($result);
        $totalPages = ceil($count_row / $results_per_page);

        // Display pagination links
        for ($i = 1; $i <= $totalPages; $i++) 
        {
            echo '<a href="'.SITE_URL.'themes.php';
            if($i > 1) echo '?page='.$i;
            echo '" class="w3-button';
            if($i == $current_page) echo ' w3-border';
            echo '">'.$i.'</a>';
        }

        ?>

    </div>

</nav>

<?php include('includes/footer.php'); ?>