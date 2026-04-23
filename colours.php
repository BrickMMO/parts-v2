<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

if(!isset($_GET['page']))
{
    redirect($_SERVER['REQUEST_URI'].'?page=1');
    exit;
}

define('PAGE_TITLE', 'Colours');

include('includes/header.php');

?>

<h1>LEGO&reg; Colours</h1>

<nav>
    
    <a href="<?=SITE_URL?>/">Home</a> &gt; 
    Colours

</nav>

<main class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <?php

    $results_per_page = PER_PAGE * 4;
    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $offset = ($current_page - 1) * $results_per_page;

    $query = 'SELECT *
        FROM colors
        ORDER BY y2 DESC, name
        LIMIT '.$offset.', '.$results_per_page;
    $result = mysqli_query($connect, $query);

    ?>
    
    <?php while ($colour = mysqli_fetch_assoc($result)): ?>

            <div style="width: calc(25% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">

                <div class="w3-card-4 w3-margin-top"
                    style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-dark-grey">
                        <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><?= $colour['name'] ?>
                        </h4>
                    </header>

                    <div style="background-color: #<?=$colour['rgb']?>;">
                    <div class="w3-container w3-text-center w3-padding-16 w3-text-white" style="weight:294px;height:150px;">
                    </div>
                </div>
                <table class="w3-table w3-striped w3-bordered">
                        <thead>
                            <tr class="w3-light-grey">
                                <th>
                                    #<?=$colour['rgb']?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a href="<?= SITE_URL ?>/colour.php?id=<?= $colour['id'] ?>">Colour Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

        <?php endwhile; ?>
    
</main>

<nav class="w3-text-center w3-section">

    <div class="w3-bar">            

        <?php

        // Count total colours
        $countQuery = "SELECT COUNT(*) AS total FROM colors";
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