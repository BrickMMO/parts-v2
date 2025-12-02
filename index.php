<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', '');

include('includes/header.php');

?>

<h1>LEGO&reg; Parts Directory</h1>

<main style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <h2 class="w3-green w3-padding">Featured Themes</h2>
    <p>TODO: 4 RANDOM THEMES</p>
    <a href="<?=SITE_URL?>/themes.php">View All Themes</a>

    <hr>

    <h2 class="w3-blue w3-padding">Featured Sets</h2>
    <div style="display:flex; justify-content: space-around;">
        <?php 

            $query= 'SELECT * FROM sets ORDER BY RAND() LIMIT 4';
            $result = mysqli_query($connect, $query);

        ?>

        <?php while($randomSet = mysqli_fetch_assoc($result)): ?>
            <div style="width: calc(25% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                        <div class="w3-card-4 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                            <header class="w3-container w3-blue">
                                <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $randomSet["name"] ?></h4>
                            </header>
                            <div class="w3-container w3-center w3-padding">
                                <div style="position: relative; width: 100%; padding-top: 100%;">
                                    <a href="<?=SITE_URL?>/set.php?id=<?=$randomSet["set_num"]?>">
                                        
                                        <img src="<?=$randomSet["img_url"]?>" alt="" style="max-width:80%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                                         
                                        <p class="w3-blue"><?=$randomSet["year"]?></p>
                                    </a>
                                </div>  
                            </div>
                        </div>
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