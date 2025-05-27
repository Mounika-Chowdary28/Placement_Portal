<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "placement_portal";

$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if profile_pic column exists
$result = mysqli_query($conn, "SHOW COLUMNS FROM users LIKE 'profile_pic'");
$exists = (mysqli_num_rows($result) > 0);

echo "<html><head><title>Database Check</title></head><body>";
echo "<h2>Database Structure Check</h2>";

if ($exists) {
    echo "<p style='color:green'>The profile_pic column exists in the users table.</p>";
    
    // Get default value
    $column_info = mysqli_fetch_assoc($result);
    echo "<p>Default value: " . ($column_info['Default'] ? $column_info['Default'] : 'NULL') . "</p>";
} else {
    echo "<p style='color:red'>The profile_pic column does NOT exist in the users table.</p>";
    echo "<p>You need to add the column using this SQL:</p>";
    echo "<pre>ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) DEFAULT 'default.jpg';</pre>";
    
    // Add the column button
    echo "<form method='post'>";
    echo "<input type='submit' name='add_column' value='Add Column Now'>";
    echo "</form>";
}

// Process add column request
if (isset($_POST['add_column'])) {
    $sql = "ALTER TABLE users ADD COLUMN profile_pic VARCHAR(255) DEFAULT 'default.jpg'";
    if (mysqli_query($conn, $sql)) {
        echo "<p style='color:green'>Successfully added profile_pic column to users table!</p>";
        echo "<p>Please refresh this page to verify.</p>";
    } else {
        echo "<p style='color:red'>Error adding column: " . mysqli_error($conn) . "</p>";
    }
}

// Show all user table columns
echo "<h3>Current Users Table Structure</h3>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";

$result = mysqli_query($conn, "DESCRIBE users");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>" . $row['Field'] . "</td>";
    echo "<td>" . $row['Type'] . "</td>";
    echo "<td>" . $row['Null'] . "</td>";
    echo "<td>" . ($row['Default'] ? $row['Default'] : 'NULL') . "</td>";
    echo "</tr>";
}

echo "</table>";
echo "</body></html>";

// Close connection
mysqli_close($conn);
?> 