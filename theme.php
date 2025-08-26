<?php
include('includes/connect.php');
include('includes/config.php');
include('includes/functions.php');

define('PAGE_TITLE', 'Theme');
include('includes/header.php');

/* Fetch all the data for the selected theme */
$query = 'SELECT * 
    FROM themes
    WHERE id = ' . $_GET['id'] . '
    LIMIT 1';
$result = mysqli_query($connect, $query);
$theme = mysqli_fetch_assoc($result);

// Define items per page
$itemsPerPage = 12;

// Calculate total number of sets
$query = 'SELECT COUNT(*) AS totalSets 
        FROM sets
        WHERE theme_id = ' . $theme['id'];
$result = mysqli_query($connect, $query);
$row = mysqli_fetch_assoc($result);
$totalSets = $row['totalSets'];

// Calculate total number of pages
$totalPages = ceil($totalSets / $itemsPerPage);

// Get current page number
$page = isset($_GET['page']) && $_GET['page'] > 0 && $_GET['page'] <= $totalPages ? $_GET['page'] : 1;

// Calculate offset for pagination
$offset = ($page - 1) * $itemsPerPage;
?>

<div class="container">
    <h2>Sets</h2>
    <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Theme</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $theme['name'] ?></li>
        </ol>
    </nav>
</div>

<?php
/* Fetch all sets linked to the selected theme */
$query = 'SELECT sets.*, inventories.id, inventories.version
        FROM sets
        LEFT JOIN inventories
        ON inventories.set_num = sets.set_num
        WHERE theme_id = ' . $theme['id'] . '
        ORDER BY RAND()
        LIMIT ' . $itemsPerPage . ' OFFSET ' . $offset;
$result = mysqli_query($connect, $query);
?>

<div class="container mt-4">
    <div class="row">
        <?php while ($set = mysqli_fetch_assoc($result)) : ?>
            <div class="col-3 mb-4">
                <div class="card justify-content-center parts-card">
                    <div class="parts-card-img-container p-2">
                        <img class="rounded mx-auto d-block" src=<?= $set['img_url']; ?> alt="<?= $set['name']; ?>">
                    </div>
                    <div class="card-body parts-card-body">
                        <h3 class="card-title parts-card-title"><?= $set['name']; ?></h3>
                        <h6 class="card-subtitle text-muted">Number of Parts: <?= $set['num_parts']; ?></h6>
                        <p class="card-text mb-0">Set No: <?= $set['set_num']; ?> | Year: <?= $set['year']; ?> </p>
                        <p class="card-text">Version: <?= $set['version']; ?></p>
                        <a href="set.php?id=<?= $set['id'] ?>">Set Details</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?id=<?= $_GET['id'] ?>&page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>
<?php include('includes/footer.php'); ?>