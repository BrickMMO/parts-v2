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
    <p>TODO: 4 RANDOM SETS</p>

    <hr>

    <h2 class="w3-indigo w3-padding">Featued Minifigures</h2>
    <p>TODO: 4 RANDOM MINIFIGURES</p>

    <hr>

    <h2 class="w3-purple w3-padding">Featued Parts</h2>
    <p>TODO: 4 RANDOM PARTS</p>

    <hr>

 <h2 class="w3-deep-orange w3-padding">Featured Categories</h2>
<div class="w3-flex" style="flex-wrap: wrap; gap: 16px;">

<?php
$query = "SELECT id, name FROM part_categories ORDER BY RAND() LIMIT 4";
$result = mysqli_query($connect, $query);

while ($cat = mysqli_fetch_assoc($result)):

    $img_url = SITE_URL . '/images/no-image.png';

    $qPart = "SELECT part_num FROM parts WHERE part_cat_id = '".$cat['id']."' ORDER BY RAND() LIMIT 1";
    $rPart = mysqli_query($connect, $qPart);

    if (mysqli_num_rows($rPart)) {
        $part = mysqli_fetch_assoc($rPart);
        $part_num = $part['part_num'];

        $qImg = "SELECT img_url FROM inventory_parts WHERE part_num = '".$part_num."' AND img_url IS NOT NULL ORDER BY RAND() LIMIT 1";
        $rImg = mysqli_query($connect, $qImg);

        if (mysqli_num_rows($rImg)) {
            $imgData = mysqli_fetch_assoc($rImg);
            if ($imgData['img_url']) $img_url = $imgData['img_url'];
        }
    }
?>
    <div style="width: calc(25% - 16px); box-sizing: border-box;">
        <div class="w3-card-4" style="height: 100%; display: flex; flex-direction: column;">
            <header class="w3-container w3-deep-orange">
                <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: white;">
                    <?= $cat['name'] ?>
                </h4>
            </header>
            <div class="w3-container w3-center w3-padding">
                <div style="position: relative; width: 100%; padding-top: 100%;">
                    <img src="<?= $img_url ?>" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto; max-width: 80%; max-height: 80%; object-fit: contain;">
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
</div>

<button class="w3-button w3-large w3-deep-orange w3-text-white w3-margin-top"
        onclick="window.location='<?= SITE_URL ?>/categories.php';">
    View All Categories
</button>



    <hr>

    <h2 class="w3-dark-grey w3-padding">Featured Colours</h2>
    <p>TODO: 4 RANDOM COLOURS</p>
    <a href="<?=SITE_URL?>/colours.php">View All Colours</a>
    
</main>

<?php include('includes/footer.php'); ?>