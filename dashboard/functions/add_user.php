<?php
session_start();

if (!isset($_SESSION['userID']) && $_SESSION['userType'] != 1) {
    header("Location: unauthorized.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to your MySQL database
    include '../../config.php';

    // Function to generate a random UserID
    function generateRandomUserID($length = 8) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomUserID = '';
        for ($i = 0; $i < $length; $i++) {
            $randomUserID .= $characters[mt_rand(0, $charactersLength - 1)];
        }
        return $randomUserID;
    }

    // Generate a random UserID
    $randomUserID = generateRandomUserID();

    // Check if the generated UserID already exists in the table
    $checkQuery = "SELECT UserID FROM tbl_user WHERE UserID = '$randomUserID'";
    $checkResult = mysqli_query($conn, $checkQuery);

    // If the UserID already exists, generate another random UserID
    while (mysqli_num_rows($checkResult) > 0) {
        $randomUserID = generateRandomUserID();
        $checkQuery = "SELECT UserID FROM tbl_user WHERE UserID = '$randomUserID'";
        $checkResult = mysqli_query($conn, $checkQuery);
    }

    // Retrieve form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $userType = $_POST['userType'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $homeCity = $_POST['homeCity'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Default values
    $isActive = 1;
    $isDeleted = 0;

    // Insert the new user with the generated random UserID
    $insertQuery = "INSERT INTO tbl_user (UserID, FirstName, LastName, Email, UserType, Age, IsActive, IsDeleted, Gender, HomeCity, Username, Password) 
                    VALUES ('$randomUserID', '$firstName', '$lastName', '$email', '$userType', '$age', '$isActive', '$isDeleted', '$gender', '$homeCity', '$username', '$password')";

    if (mysqli_query($conn, $insertQuery)) {
        echo "<code>New user added successfully with UserID: $randomUserID.</code>";
    } else {
        echo "Error adding user: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Add User</h1>
        <div class="row mt-3">
            <div class="col-md-6">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="userType">User Type:</label>
                        <input type="number" id="userType" name="userType" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" id="age" name="age" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <input type="text" id="gender" name="gender" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="homeCity">Home City:</label>
                        <input type="text" id="homeCity" name="homeCity" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
