<?php
// Include database configuration
require_once 'config.php';

// Query to get all jobs and their min_cgpa values
$sql = "SELECT j.id, j.title, j.company_id, j.min_cgpa, c.name as company_name 
        FROM jobs j JOIN companies c ON j.company_id = c.id 
        ORDER BY j.company_id, j.id";

$result = $conn->query($sql);

if (!$result) {
    die("Error querying database: " . $conn->error);
}

// HTML output with simple styling
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
echo "<meta charset='UTF-8'>";
echo "<title>CGPA Values Check</title>";
echo "<style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; }
    th, td { padding: 8px; text-align: left; border: 1px solid #ddd; }
    th { background-color: #f2f2f2; }
    tr:hover { background-color: #f5f5f5; }
    h1 { color: #333; }
    .highlight { background-color: #ffffcc; }
</style>";
echo "</head>";
echo "<body>";
echo "<h1>Database Check: CGPA Requirements for Jobs</h1>";

// Display results in table
echo "<table>";
echo "<tr><th>ID</th><th>Job Title</th><th>Company</th><th>Company ID</th><th>Min CGPA</th></tr>";

while ($row = $result->fetch_assoc()) {
    $highlight = ($row['min_cgpa'] == 0 || $row['min_cgpa'] === NULL) ? ' class="highlight"' : '';
    echo "<tr$highlight>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['title']}</td>";
    echo "<td>{$row['company_name']}</td>";
    echo "<td>{$row['company_id']}</td>";
    echo "<td>{$row['min_cgpa']}</td>";
    echo "</tr>";
}

echo "</table>";

// Link to update script
echo "<p style='margin-top: 20px;'>";
echo "<a href='update_cgpa.php?force_update=true' style='padding: 10px 15px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 4px;'>Fix All CGPA Values</a>";
echo " <a href='jobs.php' style='padding: 10px 15px; background-color: #2196F3; color: white; text-decoration: none; border-radius: 4px;'>Go to Jobs Page</a>";
echo "</p>";

echo "</body>";
echo "</html>";
?> 