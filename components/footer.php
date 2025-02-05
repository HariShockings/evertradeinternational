<?php

include('config.php');
// Fetch owner details
$query = "SELECT name, logo, contact, email, location, facebook, twitter, instagram, linkedin, whatsapp FROM tbl_owner LIMIT 1";
$result = mysqli_query($conn, $query);
$owner = mysqli_fetch_assoc($result);
?>

<div class="footer-inner">
    <div class="footer-column">
        <div class="footer-logo">
            <img src="<?php echo $owner['logo']; ?>" alt="<?php echo $owner['name']; ?>" width="50px">
        </div>
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
            <p>Email: <?php echo $owner['email']; ?></p>
            <p>Phone: <?php echo $owner['contact']; ?></p>
            <p>Address: <?php echo $owner['location']; ?></p>
        </div>
    </div>
    <div class="footer-column">
        <div class="footer-social">
            <h3>Follow Us</h3>
            <?php if ($owner['facebook']) echo "<a href='{$owner['facebook']}' target='_blank'><i class='fab fa-facebook-f'></i></a>"; ?>
            <?php if ($owner['twitter']) echo "<a href='{$owner['twitter']}' target='_blank'><i class='fab fa-twitter'></i></a>"; ?>
            <?php if ($owner['instagram']) echo "<a href='{$owner['instagram']}' target='_blank'><i class='fab fa-instagram'></i></a>"; ?>
            <?php if ($owner['linkedin']) echo "<a href='{$owner['linkedin']}' target='_blank'><i class='fab fa-linkedin'></i></a>"; ?>
            <?php if ($owner['whatsapp']) echo "<a href='https://wa.me/{$owner['whatsapp']}' target='_blank'><i class='fab fa-whatsapp'></i></a>"; ?>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-text">
            &copy; 2024 <?php echo $owner['name']; ?>. All rights reserved. | Designed by <a href="#" target="_blank">Your Name</a>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
