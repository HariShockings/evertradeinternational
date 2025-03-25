<style>
        .contact-container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .contact-container:hover {
            transform: scale(1.02);
        }
        .contact-header {
            font-size: 28px;
            font-weight: bold;
            background: var(--gradient-text);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .contact-info a {
            text-decoration: none;
            font-weight: bold;
            color: var(--color-primary);
            transition: color 0.3s;
        }
        .contact-info a:hover {
            color: var(--color-third);
        }
        .btn-contact {
            background: var(--color-primary);
            color: white;
            transition: background 0.3s;
        }
        .btn-contact:hover {
            background: var(--color-third);
        }
    </style>
<?php
// contact-component.php

function displayContactComponent($show = ['contact-data-1' => true, 'contact-data-2' => true, 'contact-data-3' => true, 'contact-data-4' => true, 'contact-data-5' => true]) {
    include('../config.php');

    // Fetch Contact Us Text
    $contactUsQuery = "SELECT * FROM tbl_pages WHERE pages = 'contact_us'";
    $contactUsResult = $conn->query($contactUsQuery);
    $contactUs = $contactUsResult->fetch_assoc();

    // Fetch Owner Details
    $ownerQuery = "SELECT * FROM tbl_owner LIMIT 1";
    $ownerResult = $conn->query($ownerQuery);
    $owner = $ownerResult->fetch_assoc();

    $conn->close();
    ?>

    <?php if ($show['contact-data-1']) : ?>
    <div class="container-fluid p-0 m-0 contact-data-1">
        <img src="<?php echo 'uploads/' . $contactUs['img_url']; ?>" alt="<?php echo $contactUs['alt_text']; ?>" class="full-image" id="scrollImage">
    </div>
    <?php endif; ?>

    <?php if ($show['contact-data-2']) : ?>
    <div class="container my-5">
        <div class="row contact-data-2">
            <div class="col-12 text-center">
                <h2 class="fw-bold heading"><?php echo $contactUs['heading']; ?></h2>
                <p><?php echo $contactUs['description']; ?></p>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show['contact-data-3']) : ?>
        <div class="row text-center mt-4 contact-data-3">
            <div class="col-md-4">
                <div class="about-box bg-light p-4">
                    <i class="fas fa-map-marked fa-3x text-warning"></i>
                    <h4 class="mt-3">Address</h4>
                    <p><?php echo $owner['location']; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-box bg-light p-4">
                    <i class="fas fa-envelope fa-3x text-warning"></i>
                    <h4 class="mt-3">E-mail</h4>
                    <p><?php echo $owner['email']; ?></p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="about-box bg-light p-4">
                    <i class="fas fa-clock fa-3x text-warning"></i>
                    <h4 class="mt-3">Office Time</h4>
                    <p><?php echo $owner['office_time']; ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($show['contact-data-4']) : ?>
        <div class="row mt-5 contact-data-4">
            <div class="col-md-8">
                <div class="contact-page-form bg-light p-5 rounded shadow-sm">
                    <h3 class="text-center mb-4">Get in Touch</h3>
                    <form action="functions/contact-mail.php" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" class="form-control" name="email" placeholder="E-mail" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <textarea class="form-control" name="message" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-md-12 text-center">
                                <button type="submit" class="btn btn-contact px-4 py-2">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <div class="contact-page-map">
                    <iframe src="<?php echo $owner['google_map_link']; ?>" width="100%" height="350" style="border:0;" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php if ($show['contact-data-5']) : ?>
        <div class="row mt-5 text-center contact-data-5">
            <div class="col">
                <h4>Follow Us</h4>
                <div class="d-flex align-items-center justify-content-center">
                <a href="<?php echo $owner['facebook']; ?>" class="footer-social-a btn btn-primary mx-2"><i class="fab fa-facebook-f"></i></a>
                <a href="<?php echo $owner['twitter']; ?>" class="footer-social-a btn btn-info mx-2"><i class="fab fa-twitter"></i></a>
                <a href="<?php echo $owner['instagram']; ?>" class="footer-social-a btn btn-danger mx-2"><i class="fab fa-instagram"></i></a>
                <a href="<?php echo $owner['linkedin']; ?>" class="footer-social-a btn btn-dark mx-2"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://wa.me/<?php echo $owner['whatsapp']; ?>" class="footer-social-a btn btn-success mx-2"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div> <!-- Close container -->
<?php
}
?>

<script>
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            var name = $('input[name="name"]').val().trim();
            var email = $('input[name="email"]').val().trim();
            var message = $('textarea[name="message"]').val().trim();

            var nameRegex = /^[A-Za-z\s]{2,50}$/; // Only letters and spaces (2-50 characters)
            var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // Standard email format
            var messageRegex = /^[A-Za-z0-9\s.,!?]{5,300}$/; // Allows letters, numbers, punctuation (5-300 characters)

            if (!nameRegex.test(name)) {
                alert("Please enter a valid name (only letters, max 50 characters).");
                e.preventDefault();
                return false;
            }

            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address.");
                e.preventDefault();
                return false;
            }

            if (!messageRegex.test(message)) {
                alert("Please enter a valid message (at least 5 characters, no excessive special symbols).");
                e.preventDefault();
                return false;
            }
        });
    });

</script>