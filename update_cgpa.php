<?php
// Include database configuration
require_once 'config.php';

// Check if force update is requested
if (isset($_GET['force_update']) && $_GET['force_update'] == 'true') {
    $force_update = true;
} else {
    $force_update = false;
}

// Map of company IDs to their default CGPA requirements
$companyRequirements = [
    1 => 7.5,  // TechCorp
    2 => 8.0,  // DataSystems Inc
    3 => 7.0,  // WebTech Solutions
    4 => 8.5,  // CloudSys Technologies
    5 => 7.5,  // InnovateX
    6 => 8.0,  // GlobalTech
    7 => 7.2,  // Tech Solutions Inc.
    8 => 7.8,  // Digital Dynamics
    9 => 8.2,  // Cyber Systems
    10 => 8.5  // AI Innovations
];

// Force update button
echo "<div style='margin-bottom: 20px;'>";
echo "<a href='update_cgpa.php?force_update=true' class='btn btn-warning' style='background-color: #ffc107; color: #000; padding: 10px 15px; text-decoration: none; border-radius: 4px;'>Force Update All Jobs with Company CGPA</a>";
echo "</div>";

// First, check all jobs and their company IDs
$checkJobsSql = "SELECT j.id, j.title, j.company_id, j.min_cgpa, c.name as company_name FROM jobs j JOIN companies c ON j.company_id = c.id";
$result = $conn->query($checkJobsSql);

if (!$result) {
    die("Error fetching jobs: " . $conn->error);
}

echo "<h2>Current Jobs and Their CGPA Requirements</h2>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background-color: #f2f2f2;'><th>ID</th><th>Job Title</th><th>Company</th><th>Current Min CGPA</th><th>Company Default CGPA</th><th>Status</th></tr>";

$updatedCount = 0;

while ($job = $result->fetch_assoc()) {
    $jobId = $job['id'];
    $companyId = $job['company_id'];
    $currentCgpa = $job['min_cgpa'];
    $defaultCgpa = isset($companyRequirements[$companyId]) ? $companyRequirements[$companyId] : 7.0;
    
    echo "<tr>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$job['id']}</td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$job['title']}</td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>{$job['company_name']} (ID: $companyId)</td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>$currentCgpa</td>";
    echo "<td style='border: 1px solid #ddd; padding: 8px;'>$defaultCgpa</td>";
    
    // If current CGPA is 0 or NULL or force update is requested, update it with the company's default
    if ($currentCgpa == 0 || $currentCgpa === NULL || $force_update) {
        $updateSql = "UPDATE jobs SET min_cgpa = $defaultCgpa WHERE id = $jobId";
        if ($conn->query($updateSql)) {
            echo "<td style='border: 1px solid #ddd; padding: 8px; color:green'>Updated to $defaultCgpa</td>";
            $updatedCount++;
        } else {
            echo "<td style='border: 1px solid #ddd; padding: 8px; color:red'>Update failed: " . $conn->error . "</td>";
        }
    } else {
        echo "<td style='border: 1px solid #ddd; padding: 8px;'>No update needed</td>";
    }
    
    echo "</tr>";
}

echo "</table>";
echo "<p>Total jobs updated: $updatedCount</p>";

// Option to manually set CGPA requirements
if (isset($_POST['update'])) {
    $jobId = (int)$_POST['job_id'];
    $newCgpa = (float)$_POST['new_cgpa'];
    
    if ($jobId > 0 && $newCgpa > 0) {
        $updateManualSql = "UPDATE jobs SET min_cgpa = $newCgpa WHERE id = $jobId";
        if ($conn->query($updateManualSql)) {
            echo "<p style='color:green'>Successfully updated job ID $jobId with min_cgpa = $newCgpa</p>";
            // Redirect to refresh the page with updated data
            echo "<script>window.location.href = 'update_cgpa.php';</script>";
        } else {
            echo "<p style='color:red'>Error updating job: " . $conn->error . "</p>";
        }
    }
}

// Form for manually updating CGPA
echo "<div style='margin-top: 20px; padding: 15px; background-color: #f8f9fa; border-radius: 5px;'>";
echo "<h2>Manually Set CGPA Requirement</h2>";
echo "<form method='post'>";
echo "<div style='margin-bottom: 10px;'>";
echo "<label for='job_id'>Job ID:</label> ";
echo "<input type='number' id='job_id' name='job_id' required style='padding: 5px; margin-right: 10px;'>";
echo "</div>";
echo "<div style='margin-bottom: 10px;'>";
echo "<label for='new_cgpa'>New CGPA Requirement:</label> ";
echo "<input type='number' id='new_cgpa' name='new_cgpa' step='0.01' min='6.0' max='10.0' required style='padding: 5px;'>";
echo "</div>";
echo "<div>";
echo "<input type='submit' name='update' value='Update CGPA' style='background-color: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer;'>";
echo "</div>";
echo "</form>";
echo "</div>";

// Link back to main page
echo "<p style='margin-top: 20px;'><a href='jobs.php' style='color: #007bff; text-decoration: none;'>Back to Jobs Page</a></p>";
?> 