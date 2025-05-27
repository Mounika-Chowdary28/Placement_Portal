<?php
// Script to add user_id column to students table
require_once 'config.php';

// Add user_id column to students table
$add_column_query = "ALTER TABLE students ADD COLUMN user_id INT NOT NULL AFTER id";

if ($conn->query($add_column_query)) {
    echo "<p>Successfully added user_id column to students table.</p>";
    
    // Try to populate user_id column by matching email addresses
    $update_query = "UPDATE students s
                    JOIN users u ON s.email = u.email
                    SET s.user_id = u.id";
    
    if ($conn->query($update_query)) {
        echo "<p>Successfully populated user_id values from matching users.</p>";
    } else {
        echo "<p>Failed to populate user_id values: " . $conn->error . "</p>";
        echo "<p>You may need to manually set user_id values if email fields don't match between tables.</p>";
    }
} else {
    // If there was an error, check if it's because the column already exists
    if (strpos($conn->error, "Duplicate column name") !== false) {
        echo "<p>The user_id column already exists in the students table.</p>";
    } else {
        echo "<p>Error adding column: " . $conn->error . "</p>";
    }
}

echo "<p>You can now try accessing your <a href='profile.php'>profile page</a>.</p>";
?> 