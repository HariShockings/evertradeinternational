<?php
// contact-page.php

include('../components/contact.php');

// Display only specific sections
displayContactComponent([
    'contact-data-1' => true,
    'contact-data-2' => true,
    'contact-data-3' => true,
    'contact-data-4' => true,
    'contact-data-5' => true
]);
?>