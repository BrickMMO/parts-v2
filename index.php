<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', '');

include('includes/header.php');

?>

<?php

$resultsPerPage = 40;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $resultsPerPage;

$query = "SELECT *,(
        SELECT MAX(year) 
        FROM sets 
        WHERE sets.theme_id = themes.id
    ) AS year
    FROM themes 
    WHERE parent_id = 0
    HAVING year IS NOT NULL 
    ORDER BY year DESC, name
    LIMIT $offset, $resultsPerPage";

$result = mysqli_query($connect, $query);

?>

<h1>LEGO&reg; Themes</h1>

<main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">
    
    <?php while ($theme = mysqli_fetch_assoc($result)): ?>

        <div style="width: calc(25% - 16px); box-sizing: border-box;">

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
            
            <div class="w3-card-4 w3-margin-top w3-margin-bottom" style="max-width:100%;">
                <header class="w3-container w3-red">
                    <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $theme['name']; ?></h4>
                </header>
                <div class="w3-container w3-center w3-padding">
                    <div style="position: relative; width: 100%; padding-top: 100%;">
                        <a href="theme.php?id=<?= $theme['id'] ?>">
                            <img src="<?= $set['img_url']; ?>" alt="" style="max-width:80%; position: absolute; top: 0; bottom: 0; left: 0; right: 0; margin: auto;  max-width: 100%; max-height: 100%; object-fit: contain;">
                        </a>
                    </div>  
                </div>
                <div class="w3-container w3-center w3-padding-16">
                    <a href="theme.php?id=<?= $theme['id'] ?>">Theme Details</a>
                </div>
            </div>

        </div>
        
    <?php endwhile; ?>
    
</main>

<nav class="w3-text-center w3-section">

    <div class="w3-bar">            

        <?php
            
        $query = "SELECT *,(
                SELECT MAX(year) 
                FROM sets 
                WHERE sets.theme_id = themes.id
            ) AS year
            FROM themes 
            WHERE parent_id = 0
            HAVING year IS NOT NULL 
            ORDER BY year DESC, name";

        $result = mysqli_query($connect, $query);

        $count_row = mysqli_num_rows($result);
        $totalPages = ceil($count_row / $resultsPerPage);

        // Display pagination links
        for ($i = 1; $i <= $totalPages; $i++) 
        {
            echo '<a href="/';
            if($i > 1) echo '?page='.$i;
            echo '" class="w3-button';
            if($i == $current_page) echo ' w3-border';
            echo '">'.$i.'</a>';
        }
        ?>

    </div>

</nav>

<?php include('includes/footer.php'); ?>