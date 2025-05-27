<?php
// Include database configuration
require_once 'config.php';

// Check if jobs table exists
$tablesResult = mysqli_query($conn, "SHOW TABLES LIKE 'jobs'");
if (mysqli_num_rows($tablesResult) == 0) {
    echo "Jobs table does not exist!";
    exit;
}

// Check if companies table exists
$tablesResult = mysqli_query($conn, "SHOW TABLES LIKE 'companies'");
if (mysqli_num_rows($tablesResult) == 0) {
    echo "Companies table does not exist!";
    exit;
}

// Check if there are any companies
$companiesResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM companies");
$companyCount = mysqli_fetch_assoc($companiesResult)['count'];

// If no companies, add some sample companies
if ($companyCount == 0) {
    $companies = [
        ['TechCorp', 'Tech company specializing in software development'],
        ['DataSystems Inc', 'Data analytics and systems integration company'],
        ['WebTech Solutions', 'Web development and design company'],
        ['CloudSys Technologies', 'Cloud infrastructure and DevOps services'],
        ['InnovateX', 'Innovation and technology consulting firm']
    ];
    
    foreach ($companies as $company) {
        $name = mysqli_real_escape_string($conn, $company[0]);
        $description = mysqli_real_escape_string($conn, $company[1]);
        
        $sql = "INSERT INTO companies (name, description) 
                VALUES ('$name', '$description')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Company '$name' added successfully<br>";
        } else {
            echo "Error adding company '$name': " . mysqli_error($conn) . "<br>";
        }
    }
    
    echo "Added sample companies<br>";
}

// Check if there are any jobs
$jobsResult = mysqli_query($conn, "SELECT COUNT(*) as count FROM jobs");
$jobCount = mysqli_fetch_assoc($jobsResult)['count'];

// If no jobs, add some sample jobs
if ($jobCount == 0) {
    $jobs = [
        [1, 'Software Development Engineer', 'Bangalore, India', 'Full-time', '₹12-18 LPA', '2023-12-31', 'Java, Spring Boot, React, AWS', 'Bachelor\'s degree in Computer Science or related field, 3+ years of experience in software development'],
        [2, 'Data Scientist', 'Hyderabad, India', 'Full-time', '₹14-20 LPA', '2023-12-31', 'Python, Machine Learning, SQL, TensorFlow', 'Master\'s degree in Data Science, Statistics or related field'],
        [3, 'Frontend Developer', 'Chennai, India', 'Full-time', '₹8-12 LPA', '2023-12-31', 'JavaScript, React, HTML/CSS, TypeScript', 'Experience with modern JavaScript frameworks'],
        [4, 'DevOps Engineer', 'Remote', 'Full-time', '₹15-22 LPA', '2023-12-31', 'Docker, Kubernetes, CI/CD, AWS', 'Experience with containerization and cloud platforms'],
        [5, 'Full Stack Developer', 'Pune, India', 'Full-time', '₹10-15 LPA', '2023-12-31', 'JavaScript, Node.js, React, MongoDB', 'Experience with full stack development']
    ];
    
    foreach ($jobs as $job) {
        $company_id = $job[0];
        $title = mysqli_real_escape_string($conn, $job[1]);
        $location = mysqli_real_escape_string($conn, $job[2]);
        $job_type = mysqli_real_escape_string($conn, $job[3]);
        $salary_range = mysqli_real_escape_string($conn, $job[4]);
        $deadline = $job[5];
        $skills = mysqli_real_escape_string($conn, $job[6]);
        $requirements = mysqli_real_escape_string($conn, $job[7]);
        
        $sql = "INSERT INTO jobs (company_id, title, location, job_type, salary_range, deadline, skills, requirements) 
                VALUES ($company_id, '$title', '$location', '$job_type', '$salary_range', '$deadline', '$skills', '$requirements')";
        
        if (mysqli_query($conn, $sql)) {
            echo "Job '$title' added successfully<br>";
        } else {
            echo "Error adding job '$title': " . mysqli_error($conn) . "<br>";
        }
    }
    
    echo "Added sample jobs<br>";
}

echo "<p>Database now has:</p>";
echo "<ul>";
echo "<li>" . $companyCount . " companies</li>";
echo "<li>" . $jobCount . " jobs</li>";
echo "</ul>";

echo "<p><a href='jobs.php'>Go back to jobs page</a></p>";
?> 