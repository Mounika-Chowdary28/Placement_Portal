<?php
require_once 'config.php';

// Check if profile_pic column exists in users table
$result = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'profile_pic'");
$exists = (mysqli_num_rows($result)) ? TRUE : FALSE;

if (!$exists) {
    // Add profile_pic column if it doesn't exist
    $sql = "ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) DEFAULT 'default.jpg'";
    if (mysqli_query($conn, $sql)) {
        echo "Profile picture column added successfully.\n";
    } else {
        echo "Error adding profile picture column: " . mysqli_error($conn) . "\n";
    }
} else {
    echo "Profile picture column already exists.\n";
}

// Show all columns in users table
echo "\nUsers table columns:\n";
$result = mysqli_query($conn, "DESCRIBE users");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . ' - ' . $row['Type'] . ' - Default: ' . $row['Default'] . "\n";
}
?> 