<?php 
    session_start();
    // if (!isset($_SESSION['userID'])) {
    //     // Redirect to the index.php page
    //     header("Location: index.php");
    //     exit(); // Stop further execution
    // }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FindBodima</title>

    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Owl Carousel CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/css/main.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Owl Carousel JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://www.payhere.lk/lib/payhere.js"></script>

</head>

<body>
    <?php include('components/navbar.php'); ?>

    <div id="content">
        <!-- Dynamic content will be loaded here -->
    </div>

    <script src="assets/js/switchPage.js"></script>
    <script>
        $(document).ready(function() {
            // Toggle sidebar when sidebar toggle button is clicked
            $('.navbar-toggler').on('click', function() {
                $('.sidebar').toggleClass('show');
            });
            // Close sidebar when sidebar close button is clicked
            $('.sidebar-close').on('click', function() {
                $('.sidebar').removeClass('show');
            });
        });
    </script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

    <footer class="footer">
        <div class="footer-inner">
            <div class="footer-column">
                <div class="footer-logo">Your Logo</div>
                <div class="footer-links">
                    <a href="#">Home</a>
                    <a href="#">About Us</a>
                    <a href="#">Services</a>
                    <a href="#">Portfolio</a>
                    <a href="#">Contact Us</a>
                </div>
            </div>
            <div class="footer-column">
                <div class="footer-contact">
                    <h3>Contact Us</h3>
                    <p>Email: info@example.com</p>
                    <p>Phone: +1 234 567 890</p>
                    <p>Address: 123 Street, City, Country</p>
                </div>
            </div>
            <div class="footer-column">
                <div class="footer-social">
                    <h3>Follow Us</h3>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                </div>
            </div>
            <div class="footer-column">
                <div class="footer-text">
                    &copy; 2024 Your Company. All rights reserved. | Designed by <a href="#" target="_blank">Your
                        Name</a>
                </div>
            </div>
        </div>
    </footer>

    <script>

    </script>
</body>

</html>