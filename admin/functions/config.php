<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ever_trade');

// Mailjet configuration
define('MAILJET_API_KEY', '2ba41827015b346bfae4dd4eaa8e7c10');
define('MAILJET_SECRET_KEY', 'bef474651dfb2fd8db877c265867e214');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    die("Database connection error");
}