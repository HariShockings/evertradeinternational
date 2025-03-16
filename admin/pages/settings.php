<?php
include('../functions/config.php'); // Include your database connection file

// Fetch user details (assuming the user ID is 1 for this example)
$userId = 1; // Replace with dynamic user ID in a real application
$userQuery = "SELECT * FROM tbl_user WHERE id = $userId";
$userResult = $conn->query($userQuery);
$user = $userResult->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Hash the password using MD5 (for demonstration purposes only)
    $hashedPassword = md5($password);

    // Update user details in the database
    $updateQuery = "UPDATE tbl_user SET 
                    username = '$username', 
                    email = '$email', 
                    password = '$hashedPassword' 
                    WHERE id = $userId";

    if ($conn->query($updateQuery)) {
        echo "<div class='alert alert-success'>User details updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Error updating user details: " . $conn->error . "</div>";
    }
}
?>

<div class="container">
    <h3>Settings</h3>
    <p>Admin settings can be configured here.</p>

    <!-- Update User Form -->
    <form method="POST" action="">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>