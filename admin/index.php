<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/header.php';
?>

<div class="d-flex" id="wrapper">
    <?php include 'includes/sidebar.php'; ?>

    <!-- Page Content -->
    <div class="container-fluid p-4" id="content">
        <?php include 'pages/dashboard.php'; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>