<?php $current_page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
<style>
.property {
            margin-bottom: 30px;
        }

        .property-inner {
            box-shadow: 0 0 10px rgba(0, 0, 0, .2);
            padding: 10px;
        }

        .property img {
            margin-bottom: 10px;
            max-width: 100%;
            height: auto;
        }
</style>

<section id="hero">
<?php include('../components/carousel.php'); ?>
</section>

<section id="aboutus" class="bg-light py-2">
<?php
// another-page.php

include('../components/about.php');

// Display only specific sections
displayAboutUsComponent([
    'abt-data-1' => false,
    'abt-data-2' => true,
    'abt-data-3' => true,
    'abt-data-4' => false,
    'abt-data-5' => true,
    'abt-data-6' => false
]);
?>
</section>

<section id="contactus" class="mt-2">
<?php
// contact-page.php

include('../components/contact.php');

// Display only specific sections
displayContactComponent([
    'contact-data-1' => false,
    'contact-data-2' => true,
    'contact-data-3' => true,
    'contact-data-4' => false,
    'contact-data-5' => false
]);
?>
</section>