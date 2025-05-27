<?php
// Include database configuration
require_once 'config.php';

// Check if jobs table exists
$tablesResult = mysqli_query($conn, "SHOW TABLES LIKE 'jobs'");
if (mysqli_num_rows($tablesResult) == 0) {
    echo "Jobs table does not exist!";
    exit;
}

// Check if jobs exist
$jobsResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM jobs");
$jobCount = mysqli_fetch_assoc($jobsResult)['count'];
echo "Total jobs in database: " . $jobCount . "<br>";

if ($jobCount > 0) {
    // Display all jobs
    $jobsData = mysqli_query($conn, "SELECT j.*, c.name as company_name FROM jobs j JOIN companies c ON j.company_id = c.id LIMIT 10");
    
    echo "<h3>Sample Jobs</h3>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Company</th><th>Title</th><th>Skills</th><th>Deadline</th></tr>";
    
    while ($job = mysqli_fetch_assoc($jobsData)) {
        echo "<tr>";
        echo "<td>" . $job['id'] . "</td>";
        echo "<td>" . $job['company_name'] . "</td>";
        echo "<td>" . $job['title'] . "</td>";
        echo "<td>" . $job['skills'] . "</td>";
        echo "<td>" . $job['deadline'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No jobs found in the database!";
}

// Check if there are any companies
$companiesResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM companies");
$companyCount = mysqli_fetch_assoc($companiesResult)['count'];
echo "<br>Total companies in database: " . $companyCount;

// List user skills
echo "<h3>User Skills</h3>";
$skillsResult = mysqli_query($conn, "SELECT user_id, COUNT(*) as skill_count FROM skills GROUP BY user_id");
echo "<table border='1'>";
echo "<tr><th>User ID</th><th>Skill Count</th></tr>";

while ($skill = mysqli_fetch_assoc($skillsResult)) {
    echo "<tr>";
    echo "<td>" . $skill['user_id'] . "</td>";
    echo "<td>" . $skill['skill_count'] . "</td>";
    echo "</tr>";
}

echo "</table>";
?> 