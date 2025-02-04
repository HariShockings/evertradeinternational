<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../config.php'; // Include your database connection file

    // Get form data
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $homecity = $_POST['homecity'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate form data (you can add more validation checks)
    if ($password != $confirm_password) {
        echo "<script>
                    window.location.href = '../';
                    alert('Passwords do not match. Please try again.');
                </script>";
        exit();
    }

    // Generate a unique UserID
    $userID = generateUniqueUserID($conn);

    // Assign UserType based on selected option
    $usertype = $_POST['usertype'] === 'normal' ? 3 : ($_POST['usertype'] === 'advertiser' ? 2 : null);

    // Check if UserType is valid
    if ($usertype === null) {
        echo "<script>
                    window.location.href = '../';
                    alert('Invalid UserType selected.');
                </script>";
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data into the database
    $query = "INSERT INTO tbl_user (UserID, FirstName, LastName, Gender, Age, HomeCity, Email, Username, Password, UserType) VALUES ($userID, '$firstname', '$lastname', '$gender', $age, '$homecity', '$email', '$username', '$hashed_password', $usertype)";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>
                    window.location.href = '../';
                    alert('User registered successfully!');
                </script>";
        // Redirect to a success page or login page
        header("Location: ../"); 
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }

    mysqli_close($conn);
} else {
    header("Location: ../");
    exit();
}

// Function to generate a unique UserID
function generateUniqueUserID($conn) {
    $userID = null;
    do {
        $userID = rand(100, 99999999);
        $checkQuery = "SELECT UserID FROM tbl_user WHERE UserID = $userID";
        $checkResult = mysqli_query($conn, $checkQuery);
        if (mysqli_num_rows($checkResult) > 0) {
            $userID = null; // Reset $userID if already exists
        }
    } while ($userID === null);
    return $userID;
}
?>
