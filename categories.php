<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

if(!isset($_GET['page']))
{
    redirect($_SERVER['REQUEST_URI'].'?page=1');
    exit;
}

define('PAGE_TITLE', 'Categories');

include('includes/header.php');

?>

<h1>LEGO&reg; Categories</h1>

<nav>
    
    <a href="<?=SITE_URL?>/">Home</a> &gt; 
    Categories

</nav>

<main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <?php

    $results_per_page = PER_PAGE * 4;
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
                        <a href="<?=SITE_URL?>/category.php?id=<?=$category['id']?>">
                            <img src="<?=$category['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                        </a>
                    </div>  
                </div>

                <!--
                <div class="w3-container w3-center w3-padding-16">
                    <a href="<?=SITE_URL?>/category.php?id=<?=$category['id']?>">Category Details</a>
                </div>
                -->
            </div>

        </div>
        
    <?php endwhile; ?>
    
</main>

<nav class="w3-text-center w3-section">

    <div class="w3-bar">            

        <?php

        // Count total categories
        $countQuery = "SELECT COUNT(*) AS total FROM part_categories";
        $countResult = mysqli_query($connect, $countQuery);
        $countRow = mysqli_fetch_assoc($countResult);
        $totalPages = ceil($countRow['total'] / $results_per_page);

        // Build pagination window
        $pagination = buildPagination($current_page, $totalPages);

        // Previous button
        if ($current_page > 1) {
            echo '<a href="?page='.($current_page - 1).'" class="w3-button">Previous</a>';
        } else {
            echo '<span class="w3-button w3-disabled">Previous</span>';
        }

        // Page numbers
        foreach ($pagination as $p) {
            if ($p === "...") {
                echo '<span class="w3-button w3-disabled">...</span>';
            } elseif ($p == $current_page) {
                echo '<span class="w3-button w3-border"><b>'.$p.'</b></span>';
            } else {
                echo '<a href="?page='.$p.'" class="w3-button">'.$p.'</a>';
            }
        }

        // Next button
        if ($current_page < $totalPages) {
            echo '<a href="?page='.($current_page + 1).'" class="w3-button">Next</a>';
        } else {
            echo '<span class="w3-button w3-disabled">Next</span>';
        }

        ?>


    </div>

</nav>

<?php include('includes/footer.php'); ?>
