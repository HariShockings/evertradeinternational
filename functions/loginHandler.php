<?php

session_start();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include database connection file
    include '../config.php'; // Update this with your actual database connection file

    // Check if the emailOrUsername and password POST variables are set
    if (isset($_POST['emailOrUsername'], $_POST['password'])) {
        // Get the form data
        $emailOrUsername = $_POST['emailOrUsername'];
        $password = $_POST['password'];

        // Query to fetch user data using email or username
        $query = "SELECT * FROM tbl_user WHERE (Email = '$emailOrUsername' OR Username = '$emailOrUsername') AND IsDeleted = 0 AND IsActive = 1";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) == 1) {
            $userData = mysqli_fetch_assoc($result);
            $hashed_password = $userData['Password'];

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Password is correct, redirect to success page
                $_SESSION['userID'] = $userData['UserID'];
                $_SESSION['firstName'] = $userData['FirstName'];
                $_SESSION['userType'] = $userData['UserType'];

                
                header("Location: ../");
                echo "<script>
                    window.location.href = '../';
                    alert('Login successful');
                </script>";
            } else {
                // Password is incorrect
                echo "<script>
                    window.location.href = '../';
                    alert('Invalid Credentials');
                </script>";
                exit();
            }
        } else {
            // User not found
            echo "<script>
                window.location.href = '../';
                alert('User not found');
            </script>";
            exit();
        }
    } else {
        // Redirect with an error message if POST variables are not set
        header("Location: ../"); // Redirect to sign-in page with an error parameter
        exit();
    }
} else {
    // Redirect to the sign-in page if accessed directly without form submission
    header("Location: signin.php"); // Replace 'signin.php' with your actual sign-in page URL
    exit();
}
?>
