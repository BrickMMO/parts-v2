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

    <h2 class="w3-indigo w3-padding">Featured Minifigs</h2>
    <p>TODO: 4 RANDOM MINIFIGS</p>
        <!-- <div style="display:flex; flex-direction:row;"> -->
        <?php 
            $query = "SELECT * FROM minifigs ORDER BY RAND() LIMIT 4";
            $result = mysqli_query($connect, $query);

            while($display = mysqli_fetch_assoc($result)) 
                {
                
                echo
                /* 
                '
                <div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;"> 
                    <div >
                        <img src="'.$display['img_url'].'">
                    </div>
                    <p>'.$display['fig_num'].'</p>
                    <p>Parts: '.$display['num_parts'].'</p>
                </div>';
                */

                '<div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">
                    <div style="width: calc(20% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                        <div class="w3-card-5 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                            <header class="w3-container w3-indigo">
                                <h6 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' .$display["fig_num"] . '</h6>
                            </header>
                            <div class="w3-container w3-center w3-padding" style="flex: 1 1 auto;">
                                <div style="position: relative; width: 100%; padding-top: 100%;">
                                    <a href="https://parts.brickmmo.com/minifig.php?id=fig-015714">

                                        <img src="'.$display['img_url'].'" alt="" style="position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                                        
                                    </a>
                                </div>  
                            </div>

                            <table class="w3-table w3-striped w3-bordered">
                                <thead>
                                    <tr class="w3-light-grey">
                                        <th>
                                            <a href="https://parts.brickmmo.com/minifig.php?id=fig-015714">fig-015714</a>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td> 
                                            Parts: '.$display['num_parts'].'                               
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        </div>
                    </div>    
                </div>
           '; 
           
        }
        ?>
        <!-- </div> -->
    <hr>

    <h2 class="w3-purple w3-padding">Featured Parts</h2>
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