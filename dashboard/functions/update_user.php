<?php
session_start();

if(isset($_SESSION['userType'])){
    $userType = intval($_SESSION['userType']);
}

if (!isset($_SESSION['userID']) && $_SESSION['userType'] != 1) {
    header("Location: unauthorized.php");
    exit();
}

// Check if UserID is provided in the URL
if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    // Connect to your MySQL database
    include '../../config.php';

    // Fetch user details based on UserID
    $userQuery = "SELECT * FROM tbl_user WHERE UserID = '$userID'";
    $userResult = mysqli_query($conn, $userQuery);

    if (mysqli_num_rows($userResult) == 1) {
        $userRow = mysqli_fetch_assoc($userResult);

        // Process update form submission
        if (isset($_POST['updateUser'])) {
            // Retrieve updated form data
            $updatedFirstName = $_POST['firstName'];
            $updatedLastName = $_POST['lastName'];
            $updatedEmail = $_POST['email'];
            $updatedUserType = $_POST['userType'];
            $updatedAge = $_POST['age'];
            $updatedIsActive = $_POST['isActive'];
            $updatedIsDeleted = $_POST['isDeleted'];
            $updatedGender = $_POST['gender'];
            $updatedHomeCity = $_POST['homeCity'];
            $updatedUsername = $_POST['username'];
            $updatedPassword = $_POST['password'];

            // Update user details in the database
            $updateQuery = "UPDATE tbl_user SET FirstName = '$updatedFirstName', LastName = '$updatedLastName', Email = '$updatedEmail', UserType = '$updatedUserType', Age = '$updatedAge', IsActive = '$updatedIsActive', IsDeleted = '$updatedIsDeleted', Gender = '$updatedGender', HomeCity = '$updatedHomeCity', Username = '$updatedUsername', Password = '$updatedPassword' WHERE UserID = '$userID'";
            $updateResult = mysqli_query($conn, $updateQuery);

            if ($updateResult) {
                // Redirect to the user list page after successful update
                echo "<code>User Details updated successfully.</code>";
            } else {
                echo "Error updating user details: " . mysqli_error($conn);
            }
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="container">
        <h1 class="mt-5">Update User</h1>
        <div class="row mt-3">
            <div class="col-md-6">
                <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $userID; ?>" method="POST">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="firstName" class="form-control" value="<?php echo $userRow['FirstName']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="lastName" class="form-control" value="<?php echo $userRow['LastName']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?php echo $userRow['Email']; ?>">
                    </div>
                    <div class="form-group">
                        <label for="userType">User Type:</label>
                        <input type="text" id="userType" name="userType" class="form-control" value="<?php echo $userRow['UserType']; ?>">
                    </div>
                    <!-- Add more fields here -->
                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="text" id="age" name="age" class="form-control" value="<?php echo $userRow['Age']; ?>">
                    </div>
                    <!-- Add more fields here -->

                    <button type="submit" name="updateUser" class="btn btn-primary">Update User</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    } else {
        echo "User not found.";
    }

    mysqli_close($conn);
} else {
    echo "UserID not provided.";
}
?>
