<?php

include('../config.php');

// Get the UserID from the session
$userID = $_SESSION['userID'];

// Fetch user data from the database
$query = "SELECT * FROM tbl_user WHERE UserID = '$userID'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) == 1) {
    $userData = mysqli_fetch_assoc($result);
} else {
    // User not found or database error
    echo "Error: User data not found.";
    exit();
}

?>

<div class="container my-5">
        <center><h1 class="mb-4">User Profile</h1></center>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="functions/updateuser.php" method="post">
                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName"
                            value="<?php echo $userData['FirstName']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" name="lastName"
                            value="<?php echo $userData['LastName']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gender">Gender:</label>
                        <input type="text" class="form-control" id="gender" name="gender"
                            value="<?php echo $userData['Gender']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="number" class="form-control" id="age" name="age"
                            value="<?php echo $userData['Age']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="homeCity">Home City:</label>
                        <input type="text" class="form-control" id="homeCity" name="homeCity"
                            value="<?php echo $userData['HomeCity']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username"
                            value="<?php echo $userData['Username']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo $userData['Email']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="userType">User Type:</label>
                        <select class="form-control" id="userType" name="userType" required>
                            <option value="2" <?php if ($userData['UserType'] == 2) echo "selected"; ?>>Advertiser</option>
                            <option value="3" <?php if ($userData['UserType'] == 3) echo "selected"; ?>>Normal User</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
