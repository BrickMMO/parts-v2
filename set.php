<?php
include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', 'Set');
include('includes/header.php');

/* Fetch the selected set */
$query = 'SELECT sets.*,inventories.id,inventories.version, themes.name
    FROM sets
    LEFT JOIN inventories
    ON inventories.set_num = sets.set_num
    LEFT JOIN themes
    ON themes.id = sets.theme_id
    WHERE inventories.id = "' . $_GET['id'] . '"
    LIMIT 1';

$result = mysqli_query($connect, $query);
$set = mysqli_fetch_assoc($result);
?>

<div class="container">
    <h2>Set Details</h2>
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);"
        aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Theme</a></li>
            <li class="breadcrumb-item"><a href="theme.php?id=<?= $set['theme_id'] ?>"><?= $set['name'] ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $set['name'] . " Details" ?></li>
        </ol>
    </nav>
</div>

<div class="container mt-4">
    <h3><?= $set['name'] ?></h3>
    <hr>
    <div class="row align-items-center justify-content-between m-2">
        <div class="col-md-6">
            <div class="img-container-fluid">
                <img class="rounded mx-auto d-block parts-set-image" src=<?= $set['img_url']; ?>
                    alt="<?= $set['name']; ?>">
            </div>
        </div>
        <div class="col-md-3 text-center">
            <div class="card">
                <div class="card-header justify-content-center parts-theme-header">
                    Product Details
                </div>
                <div class="card-body parts-theme-body">
                    <h5 class="card-title">ID: <?= $set['set_num']; ?></h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <h6 class="m-0">Theme</h6>
                        <span> <?= $set['name']  ?></span>
                    </li>
                    <li class="list-group-item">
                        <h6 class="m-0">Inventory</h6>
                        <span> <?= $set['num_parts']; ?> </span>
                    </li>
                    <li class="list-group-item">
                        <h6 class="m-0">Year</h6>
                        < <span> <?= $set['year']; ?> </span>
                    </li>


                </ul>
            </div>
        </div>
    </div>
</div>

<?php
/* Select all the parts that are connected to the selected set */
$query = 'SELECT inventory_parts.*,
        parts.name AS part_name,
        part_categories.name AS category_name
        FROM inventory_parts
        LEFT JOIN parts 
        ON inventory_parts.part_num = parts.part_num
        LEFT JOIN part_categories
        ON parts.part_cat_id = part_categories.id
        WHERE inventory_id = ' . $set['id'] . '
        ORDER BY parts.name';
$result = mysqli_query($connect, $query);
?>

<div class="container mt-3">
    <h3>Inventory</h3>
    <hr>
    <div class="row">
        <?php while ($part = mysqli_fetch_assoc($result)) : ?>
        <?php
            /* Fetch the colour used in this set */
            $query = 'SELECT *
            FROM colors
            WHERE id = ' . $part['color_id'] . '
            LIMIT 1';
            $result2 = mysqli_query($connect, $query);
            $color = mysqli_fetch_assoc($result2);
            ?>
        <div class="col-3 mb-4">
            <div class="card justify-content-center parts-theme-card">
                <div class="parts-card-img-container p-2">
                    <img class="rounded mx-auto d-block" src=<?= $part['img_url']; ?> alt="<?= $part['part_name']; ?>">
                </div>
                <div class="card-body parts-card-body">
                    <h4 class="card-title parts-theme-card-title"><?= $part['part_name']; ?></h4>
                    <h6 class="card-subtitle text-muted">Category: <?= $part['category_name']; ?></h6>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Part No: <?= $part['part_num']; ?></li>
                    <li class="list-group-item">Color: <?= $color['name']; ?></li>
                    <li class="list-group-item">Quantity: <?= $part['quantity']; ?></li>
                    <li class="list-group-item">
                        <a href="part.php?id=<?= $part['part_num'] ?>">Part Details</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- <br>
            <div class="col">
                <a href="part.php?id=<?= $part['part_num'] ?>">Part Details</a>
                <br>
                Full Part Data:
                <pre><?php print_r($part); ?></pre>
                Full Color Data:
                <pre><?php print_r($color); ?></pre>
            </div> -->
        <?php endwhile; ?>
    </div>
</div>

<div class="container mt-3">
    <h3>Minifigs</h3>
    <?php
    /* Here you can add code to fetch all the minifigs that belong to the selected set, list them, and link to a minifig.php page */
    ?>
    <hr>
</div>
<?php include('includes/footer.php'); ?>