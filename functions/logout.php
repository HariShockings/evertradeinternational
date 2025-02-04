<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to the login page
header("Location: ../"); // Replace 'signin.php' with your actual sign-in page URL
exit();
?>
