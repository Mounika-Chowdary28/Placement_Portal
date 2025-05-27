<?php
// Database connection
require_once 'config.php';

// Check database connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// First, let's check the structure of the notifications table
$table_query = "SHOW COLUMNS FROM notifications";
$table_result = mysqli_query($conn, $table_query);

echo "<h3>Notifications Table Structure:</h3>";
echo "<pre>";
while ($column = mysqli_fetch_assoc($table_result)) {
    print_r($column);
}
echo "</pre>";

// Get existing notifications for reference
$existing_query = "SELECT * FROM notifications WHERE user_id = ? LIMIT 1";
$stmt = mysqli_prepare($conn, $existing_query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$existing_result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($existing_result) > 0) {
    echo "<h3>Example Notification:</h3>";
    echo "<pre>";
    print_r(mysqli_fetch_assoc($existing_result));
    echo "</pre>";
}

// Now, let's add a new notification
$title = "New Feature Added";
$message = "The Resume Upload feature is now available. Upload your resume to improve your job prospects.";
$created_at = date('Y-m-d H:i:s');
$is_read = 0;

$insert_query = "INSERT INTO notifications (user_id, title, message, created_at, is_read) 
                VALUES (?, ?, ?, ?, ?)";
                
$stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($stmt, "isssi", $user_id, $title, $message, $created_at, $is_read);

if (mysqli_stmt_execute($stmt)) {
    echo "<h3>Success!</h3>";
    echo "<p>New notification added successfully.</p>";
    echo "<p>Title: $title</p>";
    echo "<p>Message: $message</p>";
} else {
    echo "<h3>Error</h3>";
    echo "<p>Failed to add notification: " . mysqli_error($conn) . "</p>";
}

// Close database connection
mysqli_close($conn);
?> 