<?php 
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ever Trade</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
        rel="stylesheet">
    <!-- Alertify CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/alertify.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/alertifyjs/build/css/themes/default.min.css">

<!-- Alertify JS -->
<script src="https://cdn.jsdelivr.net/npm/alertifyjs/build/alertify.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <link rel="stylesheet" href="assets/css/main.css">

</head>


<body>
    <?php include('components/navbar.php'); ?>

    <div id="content">
        <?php
if (isset($_SESSION['success_msg'])) {
    echo "<script> 
        alertify.success('" . $_SESSION['success_msg'] . "'); 
    </script>";
    unset($_SESSION['success_msg']); // Clear session message after displaying
}

if (isset($_SESSION['error_msg'])) {
    echo "<script> 
        alertify.error('" . $_SESSION['error_msg'] . "'); 
    </script>";
    unset($_SESSION['error_msg']); // Clear session message after displaying
}
?>

        <!-- Dynamic content will be loaded here -->
    </div>

    <footer class="footer">
        <?php include('components/footer.php'); ?>
    </footer>


    <script> window.chtlConfig = { chatbotId: "3138336768" } </script>
    <script async data-id="3138336768" id="chatling-embed-script" type="text/javascript" src="https://chatling.ai/js/embed.js"></script>
    <script src="assets/js/switchPage.js"></script>
    <script src="assets/js/script.js"></script>

</body>

</html>
