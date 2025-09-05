<?php

if(!isset($_GET['page']))
{
    header('Location: '.$_SERVER['REQUEST_URI'].'&page=1');
    exit;
}

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

$query = 'SELECT *,(
        SELECT COUNT(*) 
        FROM themes AS subthemmes
        WHERE subthemmes.parent_id = themes.id
    ) AS subthemmes
    FROM themes
    WHERE id = "'.$_GET['id'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

$theme = mysqli_fetch_assoc($result);

define('PAGE_TITLE', $theme['name'].' | Sets');

include('includes/header.php');

?>

<h1><?=$theme['name']?></h1>

<a href="<?=SITE_URL?>">Themes</a> &gt; 

<?php

$parent_id = $theme['parent_id'];

while($parent_id)
{
    $query = 'SELECT *
        FROM themes
        WHERE id = "'.$parent_id.'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);
    $parent = mysqli_fetch_assoc($result);
    
    echo '<a href="'.SITE_URL.'theme.php?id='.$parent['id'].'">'.$parent['name'].'</a> &gt; ';
    
    $parent_id = $parent['parent_id'];
}

?>

<?=$theme['name']?>

<hr>

<?php if($theme['subthemmes'] > 0): ?>

    <nav>
        <button class="w3-button w3-large w3-<?=!isset($_GET['tab']) ? 'green' : 'light-grey'?>" onclick="window.location='theme.php?id=<?=$_GET['id']?>';">Sets</button>
        <button class="w3-button w3-large w3-<?=(isset($_GET['tab']) && $_GET['tab'] == 'themes') ? 'green' : 'light-grey'?>" onclick="window.location='theme.php?id=<?=$_GET['id']?>&tab=themes';">Themes</button>
    </nav>

<?php endif; ?>

<?php if(!isset($_GET['tab'])): ?>

    <main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT *
            FROM sets 
            WHERE theme_id = "'.$_GET['id'].'"
            ORDER BY year DESC, name
            LIMIT '.$offset.', '.$results_per_page;
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($set = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(25% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
                <div class="w3-card-4 w3-margin-top w3-margin-bottom" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-blue">
                        <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$set['name']?></h4>
                    </header>
                    <div class="w3-container w3-center w3-padding">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="<?=SITE_URL?>set.php?id=<?=$set['set_num']?>">
                                <img src="<?=$set['img_url']?>" alt="" style="max-width:80%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                            </a>
                        </div>  
                    </div>

                    <!--
                    <div class="w3-container w3-center w3-padding-16">
                        <a href="<?=SITE_URL?>set.php?id=<?=$set['set_num']?>">Set Details</a>
                    </div>
                    -->
                </div>
            </div>
            
        <?php endwhile; ?>
        
    </main>

    <nav class="w3-text-center w3-section">

        <div class="w3-bar">            

            <?php
                
            $query = 'SELECT *
                FROM sets
                WHERE theme_id = "'.$_GET['id'].'"';
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

<?php elseif($_GET['tab'] == 'themes'): ?>

    <main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

        <?php

        $results_per_page = PER_PAGE;
        $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($current_page - 1) * $results_per_page;

        $query = 'SELECT *,(
                SELECT MAX(year) 
                FROM sets 
                WHERE sets.theme_id = themes.id
            ) AS year
            FROM themes 
            WHERE parent_id = "'.$_GET['id'].'"
            HAVING year IS NOT NULL 
            ORDER BY year DESC, name
            LIMIT '.$offset.', '.$results_per_page;
        $result = mysqli_query($connect, $query);

        ?>
        
        <?php while ($theme = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(25% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">

                <?php

                $query = 'SELECT * 
                    FROM sets 
                    WHERE theme_id = '.$theme['id'].' 
                    ORDER BY year DESC, name';
                $result2 = mysqli_query($connect, $query);
                
                for($i = 0; $i < mysqli_num_rows($result2); $i++) 
                {
                    $set = mysqli_fetch_assoc($result2);
                    if(url_exists($set['img_url'])) break;
                }
                
                ?>
                
                <div class="w3-card-4 w3-margin-top" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-green">
                        <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?=$theme['name']?></h4>
                    </header>
                    <div class="w3-container w3-center w3-padding">
                        <div style="position: relative; width: 100%; padding-top: 100%;">
                            <a href="<?=SITE_URL?>theme.php?id=<?=$theme['id']?>">
                                <img src="<?=$set['img_url']?>" alt="" style="max-width:80%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 80%; max-height: 80%; object-fit: contain;">
                            </a>
                        </div>  
                    </div>
                    
                    <!--
                    <div class="w3-container w3-center w3-padding-16">
                        <a href="<?=SITE_URL?>theme.php?id=<?=$theme['id']?>">Theme Details</a>
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
                WHERE parent_id = "'.$_GET['id'].'"
                HAVING year IS NOT NULL ';
            $result = mysqli_query($connect, $query);

            $count_row = mysqli_num_rows($result);
            $totalPages = ceil($count_row / $results_per_page);

            // Display pagination links
            for ($i = 1; $i <= $totalPages; $i++) 
            {
                echo '<a href="'.SITE_URL.'theme.php?id='.$_GET['id'].'&tab=themes';
                if($i > 1) echo '&page='.$i;
                echo '" class="w3-button';
                if($i == $current_page) echo ' w3-border';
                echo '">'.$i.'</a>';
            }

            ?>

        </div>

    </nav>


<?php endif; ?>

<?php include('includes/footer.php'); ?>