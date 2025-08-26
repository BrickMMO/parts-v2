<?php

include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', 'Parts');
include('includes/header.php');
?>
<div class="container">
    <?php

    /*
Fetch the selected part
*/
    $query = 'SELECT parts.*
    FROM parts
    WHERE part_num = "' . $_GET['id'] . '"
    LIMIT 1';
    $result = mysqli_query($connect, $query);

    $part = mysqli_fetch_assoc($result);

    ?>



    <h1>Part: <?= $part['name'] ?></h1>

    <?php

    /*
    Fetch all the colors the selected part comes in
    */
    $query = 'SELECT colors.*
        FROM colors
        LEFT JOIN elements
        ON color_id = id
        WHERE part_num = "' . $part['part_num'] . '"
        GROUP BY colors.id
        ORDER BY name';
    $result = mysqli_query($connect, $query);

    ?>

    <h2>Colours</h2>
    <div class="table-responsive">
        <table class="table table-bordered parts-table">
            <thead>
                <tr>
                    <th>Color</th>
                    <th>RBG Color Demo</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($color = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $color['name'] ?></td>
                        <td style="background-color: #<?= $color['rgb'] ?>; width: 200px;"></td>

                    </tr>


                <?php endwhile; ?>

                <hr>



                <?php

                /*
Fetch all the sets the selected part comes with
*/
                $query = 'SELECT sets.*
    FROM sets
    LEFT JOIN inventories 
    ON inventories.set_num = sets.set_num
    LEFT JOIN inventory_parts
    ON inventory_parts.inventory_id = inventories.id
    WHERE part_num = "' . $part['part_num'] . '"
    GROUP BY sets.set_num
    ORDER BY name';
                $result = mysqli_query($connect, $query);

                ?>

            </tbody>
        </table>
    </div>
</div>

<div class="container">
    <hr>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <?php while ($set = mysqli_fetch_assoc($result)) : ?>
            <div class="col-3 mb-4">
                <div class="card">
                    <div class="parts-card-img-container p-2">
                        <img class="rounded mx-auto d-block" src=<?= $set['img_url']; ?> alt="<?= $set['name']; ?>">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title parts-card-title">Set: <?= $set['name'] ?></h5>
                        <a href="part.php?id=<?= $part['part_num'] ?>">Part Details</a>

                        <p class="card-text">Full Set Data:</p>
                        <ul>
                            <li>Set Number: <?= $set['set_num'] ?></li>
                            <li>Year: <?= $set['year'] ?></li>
                            <li>Theme: <?= $set['theme_id'] ?></li>
                            <li>Number of Parts: <?= $set['num_parts'] ?></li>


                        </ul>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include('includes/footer.php'); ?>