<?php
session_start();

if(isset($_SESSION['userID'])) {
    // Load profile.php if $_SESSION['userID'] is set
    include 'profile.php';
} else {
    // Redirect to login page or display an error message
    include 'login.php';
    exit();
}
?>
