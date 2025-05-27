<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "placement_portal";

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Create a notification for a single user (for testing)
$user_id = 1; // Adjust this to target a specific user
$title = "New Job Opening: Software Developer";
$message = "A new job opening for Software Developer position has been posted by TechCorp. Apply now to be considered for this exciting opportunity!";
$created_at = date('Y-m-d H:i:s');
$is_read = 0;

// Insert notification
$insert_query = "INSERT INTO notifications (user_id, title, message, created_at, is_read) VALUES (?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($conn, $insert_query);

if (!$stmt) {
    die("Error preparing statement: " . mysqli_error($conn));
}

mysqli_stmt_bind_param($stmt, "isssi", $user_id, $title, $message, $created_at, $is_read);

if (mysqli_stmt_execute($stmt)) {
    echo "Notification created successfully!<br>";
    echo "Title: $title<br>";
    echo "Message: $message<br>";
    echo "Created at: $created_at<br>";
} else {
    echo "Error: " . mysqli_error($conn);
}

// Close statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);

// Redirect back to home after 3 seconds
echo "<script>
    setTimeout(function() {
        window.location.href = 'home.php';
    }, 3000);
</script>";
?> 