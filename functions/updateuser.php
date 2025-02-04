<?php 
session_start();
include('../config.php');

$userID = $_SESSION['userID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $homeCity = $_POST['homeCity'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $userType = $_POST['userType'];

    // Update the user data in the database
    $updateQuery = "UPDATE tbl_user SET FirstName = '$firstName', LastName = '$lastName', Gender = '$gender', Age = '$age', HomeCity = '$homeCity', Username = '$username', Email = '$email', UserType = '$userType' WHERE UserID = '$userID'";
    $updateResult = mysqli_query($conn, $updateQuery);

    if ($updateResult) {
        // Reload the page to display updated data
        header("Location: logout.php");
        exit();
    } else {
        echo "Error updating profile: " . mysqli_error($conn);
    }
}
?>