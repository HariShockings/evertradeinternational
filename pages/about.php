<?php
// another-page.php

include('../components/about.php');

// Display only specific sections
displayAboutUsComponent([
    'abt-data-1' => true,
    'abt-data-2' => true,
    'abt-data-3' => true,
    'abt-data-4' => true,
    'abt-data-5' => true,
    'abt-data-6' => true
]);
?>