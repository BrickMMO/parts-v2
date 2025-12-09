<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', '');

include('includes/header.php');
$query = "SELECT set_num, name, img_url FROM sets ORDER BY RAND() LIMIT 4";
$result_sets = mysqli_query($connect, $query);
?>

<main style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <h2 class="w3-green w3-padding">Featured Themes</h2>
    <div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">
        <?php

        $results_per_page = PER_PAGE * 4;
        $current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT *,(
                SELECT MAX(year) 
                FROM sets 
                WHERE sets.theme_id = themes.id
            ) AS year
            FROM themes 
            WHERE parent_id = 0
            HAVING year IS NOT NULL 

            ORDER BY RAND()
            LIMIT 4';
        $result = mysqli_query($connect, $query);

        ?>

        <?php while ($theme = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(25% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">

                <?php

                $query = 'SELECT * 
                FROM sets 
                WHERE theme_id = ' . $theme['id'] . ' 
                ORDER BY year DESC, name';
                $result2 = mysqli_query($connect, $query);

                for ($i = 0; $i < mysqli_num_rows($result2); $i++) {
                    $set = mysqli_fetch_assoc($result2);
                    if (url_exists($set['img_url']))
                        break;
                }

                ?>

                <div class="w3-card-4 w3-margin-top"
                    style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-green">
                        <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $theme['name'] ?></h4>
                    </header>
                    <div class="w3-container w3-center w3-padding">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="<?= SITE_URL ?>/theme.php?id=<?= $theme['id'] ?>">
                                <img src="<?= $set['img_url'] ?>" alt=""
                                    style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                            </a>
                        </div>
                    </div>

                    <!--
                <div class="w3-container w3-center w3-padding-16">
                    <a href="<?= SITE_URL ?>/theme.php?id=<?= $theme['id'] ?>">Theme Details</a>
                </div>
                -->
                </div>

            </div>

        <?php endwhile; ?>
    </div>

    <div class="w3-margin-top">

        <button class="w3-button w3-large w3-light-grey w3-red w3-text-white w3-margin-top" 
            onclick="window.location='<?= SITE_URL ?>/themes.php';">View all Themes</button>

    </div>

    <hr>

    <h2 class="w3-blue w3-padding">Featured Sets</h2>

    <div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php 

        $query= 'SELECT * 
            FROM sets 
            ORDER BY RAND() 
            LIMIT 4';
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
                                    
                            </a>
                        </div>  
                    </div>
                    <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>
                                    <a href="<?=SITE_URL?>/set.php?id=<?=$randomSet["set_num"]?>">
                                        <?=$randomSet["set_num"]?>
                                    </a>
                                </th>
                            </tr>
                        </thead>    
                        <tbody>
                            <tr>
                                <td>
                                    Year: <?=$randomSet["year"]?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endwhile; ?>
    
    </div>

    <hr>

    <h2 class="w3-indigo w3-padding">Featured Minifigs</h2>
    
    <div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE * 5;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT *
            FROM inventory_minifigs
            LEFT JOIN minifigs 
            ON inventory_minifigs.fig_num = minifigs.fig_num
            ORDER BY RAND()
            LIMIT 5';
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
                            <a href="<?=SITE_URL?>/minifig.php?id=<?=$minifig['fig_num']?>">

                                <?php if($minifig['img_url'] && url_exists($minifig['img_url'])): ?>
                                    <img src="<?=$minifig['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
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
                                    <a href="<?=SITE_URL?>/minifig.php?id=<?=$minifig['fig_num']?>"><?=$minifig['fig_num']?></a>
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
        
    </div>

    <hr>

    <h2 class="w3-purple w3-padding">Featured Parts</h2>
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

    <div class="w3-margin-top">

        <button class="w3-button w3-large w3-light-grey w3-red w3-text-white w3-margin-top" 
            onclick="window.location='<?= SITE_URL ?>/colours.php';">View all Colours</button>

    </div>

</main>

<?php include('includes/footer.php'); ?>
