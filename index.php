<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', '');

include('includes/header.php');
$query = "SELECT set_num, name, img_url FROM sets ORDER BY RAND() LIMIT 4";
$result_sets = mysqli_query($connect, $query);
?>

<h1>LEGO&reg; Parts Directory</h1>

<main style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <h2 class="w3-green w3-padding">Featured Themes</h2>
    <p>TODO: 4 RANDOM THEMES</p>
    <a href="<?=SITE_URL?>/themes.php">View All Themes</a>

    <hr>

    <h2 class="w3-blue w3-padding">Featured Sets</h2>

    <div class="w3-row-padding">

        <?php while ($set = mysqli_fetch_assoc($result_sets)): ?>

            <div class="w3-quarter w3-center w3-margin-top">
            <a href="<?=SITE_URL?>/set.php?id=<?=$set['set_num']?>">
            
            <?php if($set['img_url'] && url_exists($set['img_url'])): ?>
                <img src="<?=$set['img_url']?>" alt="<?=$set['name']?>" style="width:100%; max-height:150px; object-fit:contain;">
            <?php else: ?>
                <img src="<?=SITE_URL?>/images/no-image.png" alt="<?=$set['name']?>" style="width:100%; max-height:150px; object-fit:contain;">
            <?php endif; ?>

        </a>

        <p><strong><?=$set['name']?></strong></p>
    </div>

<?php endwhile; ?>

</div>

    
    <hr>

    <h2 class="w3-indigo w3-padding">Featued Minifigures</h2>
    <p>TODO: 4 RANDOM MINIFIGURES</p>

    <hr>

    <h2 class="w3-purple w3-padding">Featued Parts</h2>
    <p>TODO: 4 RANDOM PARTS</p>

    <hr>

    <h2 class="w3-deep-orange w3-padding">Featured Categories</h2>
    <p>TODO: 4 RANDOM PART CATEGORIES</p>
    <a href="<?=SITE_URL?>/categories.php">View All Categories</a>

    <hr>

    <h2 class="w3-dark-grey w3-padding">Featured Colours</h2>
    <p>TODO: 4 RANDOM COLOURS</p>
    <a href="<?=SITE_URL?>/colours.php">View All Colours</a>
    
</main>

<?php include('includes/footer.php'); ?>