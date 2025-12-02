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

    <h2 class="w3-indigo w3-padding">Featued Minifigures</h2>
    <p>TODO: 4 RANDOM MINIFIGURES</p>

    <hr>

    <h2 class="w3-purple w3-padding">Featured Parts</h2>
    <p>TODO: 4 RANDOM PARTS</p>
            <?php
            $query = 'SELECT *
            FROM inventory_parts
            LEFT JOIN parts 
            ON inventory_parts.part_num = parts.part_num
            ORDER BY RAND() 
            LIMIT 4';
;
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
                            <a href="<?=SITE_URL?>/element.php?id=<?=$part['part_num']?>&colour=<?=$part['color_id']?>">

                                <?php if($part['img_url'] && url_exists($part['img_url'])): ?>
                                    <img src="<?=$part['img_url']?>" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
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
                                    <a href="<?=SITE_URL?>/colour.php?id=<?=$part['color_id']?>">#<?=$part['rgb']?></a>
                                </td>
                            </tr>
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