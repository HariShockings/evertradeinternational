<style>
    .navbar {
        background-color: var(--color-background);
        padding: 10px;
        margin: 0 10px;
        z-index: 9999;
    }

.navbar-brand { 
    font-weight: bold;
    background: var(--gradient-text);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    display: inline-block;
    transition: all 0.3s ease;
}

.navbar-brand:hover {
    background: var(--color-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: var(--color-primary);
}

.navbar-toggler {
    color: var(--color-primary);
}

.navbar-nav .nav-link {
    color: var(--color-primary);
    font-weight: 600;
    margin: 0 10px;
    position: relative;
    transition: color 0.3s ease;
    padding-bottom: 5px; /* Space for the underline */
}

.navbar-nav .nav-link::after {
    content: "";
    position: absolute;
    left: 50%;
    bottom: 0;
    width: 0;
    height: 3px;
    background: var(--gradient-underline);
    transition: width 0.3s ease, left 0.3s ease;
    transform: translateX(-50%);
}

.navbar-nav .nav-link:hover::after {
    width: 100%; 
}

.navbar-nav > .nav-item:last-child > .nav-link {
    background-color: var(--color-primary);
    color: var(--color-background);
    border-radius: 40px;
    padding: 10px 20px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.navbar-nav > .nav-item:last-child > .nav-link:hover {
    background-color: var(--color-third);
    color: #fff;
}

.navbar-nav > .nav-item:last-child > .nav-link::after {
    height: 0;
}

/* Toggle icon color */
.navbar-toggler-icon {
    color: var(--color-primary);
    margin-top: 5px;
    font-weight: bold;
}
</style>

<?php
include('config.php');

// Fetch navigation items
$navQuery = "SELECT * FROM tbl_navbar ORDER BY display_order ASC";
$navResult = $conn->query($navQuery);

$menu_items = [];
if ($navResult->num_rows > 0) {
    while ($row = $navResult->fetch_assoc()) {
        $menu_items[] = $row;
    }
}

// Fetch logo and company name from tbl_owner
$ownerQuery = "SELECT logo, name FROM tbl_owner LIMIT 1";
$ownerResult = $conn->query($ownerQuery);
if ($ownerResult->num_rows > 0) {
    $ownerData = $ownerResult->fetch_assoc();
    $logo = $ownerData['logo'] ?? 'assets/img/placeholder.jpg';
    $companyName = $ownerData['name'] ?? 'Company Name';
} else {
    $logo = 'assets/img/placeholder.jpg';
    $companyName = 'Company Name';
}

$conn->close();
?>

<nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">
        <img src="<?= htmlspecialchars($logo); ?>" alt="Company Logo" height="50px">
        <?= htmlspecialchars($companyName); ?>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">☰</span>
    </button>
    <div class="collapse navbar-collapse d-none d-md-block" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <?php foreach ($menu_items as $item) : ?>
                <li class="nav-item">
                    <a class="nav-link badge-pill" href="#" data-page="<?= $item['page_slug']; ?>">
                        <?= htmlspecialchars($item['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</nav>



<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-content">
        <span class="sidebar-close"><i class="fas fa-times"></i></span>
        <ul>
            <?php foreach ($menu_items as $item) : ?>
                <li>
                    <a href="#" data-page="<?= $item['page_slug']; ?>">
                        <?= htmlspecialchars($item['title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
