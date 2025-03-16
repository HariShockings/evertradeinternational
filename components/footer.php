<?php

include('config.php');
// Fetch owner details
$query = "SELECT name, logo, contact, email, location, facebook, twitter, instagram, linkedin, whatsapp FROM tbl_owner LIMIT 1";
$result = mysqli_query($conn, $query);
$owner = mysqli_fetch_assoc($result);
?>

<div class="footer-inner">
    <div class="footer-column">
        <div class="footer-links">
            <?php foreach ($menu_items as $item) : ?>
                    <a href="#" data-page="<?= $item['page_slug']; ?>">
                        <?= htmlspecialchars($item['title']); ?>
                    </a>
            <?php endforeach; ?>
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
        <div class="footer-logo">
            <img src="<?php echo 'uploads/' . $owner['logo']; ?>" alt="<?php echo $owner['name']; ?>" width="50px">
        </div>
        <h3>Follow Us</h3>
        <div class="footer-social">
            <?php if ($owner['facebook']) echo "<a class='footer-social-a' href='{$owner['facebook']}' target='_blank'><i class='fab fa-facebook-f'></i></a>"; ?>
            <?php if ($owner['twitter']) echo "<a class='footer-social-a' href='{$owner['twitter']}' target='_blank'><i class='fab fa-twitter'></i></a>"; ?>
            <?php if ($owner['instagram']) echo "<a class='footer-social-a' href='{$owner['instagram']}' target='_blank'><i class='fab fa-instagram'></i></a>"; ?>
            <?php if ($owner['linkedin']) echo "<a class='footer-social-a' href='{$owner['linkedin']}' target='_blank'><i class='fab fa-linkedin'></i></a>"; ?>
            <?php if ($owner['whatsapp']) echo "<a class='footer-social-a' href='https://wa.me/{$owner['whatsapp']}' target='_blank'><i class='fab fa-whatsapp'></i></a>"; ?>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="footer-text">
            &copy; 2024 <?php echo $owner['name']; ?>. All rights reserved. | Designed by <a href="#" target="_blank">HariShockings</a>
        </div>
    </div>
</div>

<?php mysqli_close($conn); ?>
