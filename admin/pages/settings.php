<?php
session_start();

// Include the database connection file
include('../functions/config.php');

// Get user details (replace with actual user ID retrieval logic, e.g., from session)
$userId = $_SESSION['user_id']; // Assuming user ID is stored in the session

// Check if the user ID exists in the session
if (isset($userId)) {
    // Prepare the query to fetch user data from the database
    $query = "SELECT * FROM tbl_user WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the user data if found
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        $_SESSION['error_msg'] = "User not found!";
        header("Location: error_page.php"); // Redirect to an error page if user is not found
        exit();
    }
} else {
    $_SESSION['error_msg'] = "User not logged in!";
    header("Location: login.php"); // Redirect to login page if no user ID found in session
    exit();
}
?>

<?php
// Display success and error messages
if (isset($_SESSION['success_msg'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success_msg']; ?></div>
    <?php unset($_SESSION['success_msg']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_msg'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error_msg']; ?></div>
    <?php unset($_SESSION['error_msg']); ?>
<?php endif; ?>

<div class="container">
    <h3>Settings</h3>
    <p>Admin settings can be configured here.</p>

    <!-- Update User Form -->
    <form method="POST" action="functions/update_user.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" 
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" 
                   value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" 
                   placeholder="Enter new password" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
