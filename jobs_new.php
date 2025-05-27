<?php
// Include database configuration
require_once 'config.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

// Enable more detailed error logging for debugging
error_log("User authentication status: " . ($is_logged_in ? "Logged in" : "Not logged in"));
if ($is_logged_in) {
    error_log("User ID: $user_id, User name: " . $_SESSION['full_name']);
}

// Add min_cgpa column to jobs table if it doesn't exist
$checkCgpaColumnSql = "SHOW COLUMNS FROM jobs LIKE 'min_cgpa'";
$cgpaColumnResult = $conn->query($checkCgpaColumnSql);
if ($cgpaColumnResult->num_rows === 0) {
    $addCgpaColumnSql = "ALTER TABLE jobs ADD COLUMN min_cgpa DECIMAL(3,2) DEFAULT 0.00";
    if (!$conn->query($addCgpaColumnSql)) {
        error_log("Error adding min_cgpa column to jobs table: " . $conn->error);
    } else {
        error_log("Added min_cgpa column to jobs table");
    }
}

// Update any jobs with min_cgpa = 0 to have company-specific default CGPA requirements
// Different companies have different CGPA standards
function getCompanyDefaultCgpa($company_id) {
    // Map company IDs to their default CGPA requirements
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
    
    // Return company-specific CGPA or 7.0 as general default
    return isset($companyRequirements[$company_id]) ? $companyRequirements[$company_id] : 7.0;
}

// Update jobs with company-specific CGPA requirements
$updateCompanyCgpaSql = "SELECT id, company_id FROM jobs WHERE min_cgpa = 0 OR min_cgpa IS NULL";
$result = $conn->query($updateCompanyCgpaSql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $job_id = $row['id'];
        $company_id = $row['company_id'];
        $min_cgpa = getCompanyDefaultCgpa($company_id);
        
        $updateSql = "UPDATE jobs SET min_cgpa = $min_cgpa WHERE id = $job_id";
        if (!$conn->query($updateSql)) {
            error_log("Error updating CGPA for job $job_id: " . $conn->error);
        } else {
            error_log("Updated job $job_id (company $company_id) with min_cgpa of $min_cgpa");
        }
    }
}

// Create saved_jobs table if it doesn't exist
$createTableSql = "CREATE TABLE IF NOT EXISTS saved_jobs (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    job_id INT(11) NOT NULL,
    saved_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY user_job (user_id, job_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE
)";

if (!$conn->query($createTableSql)) {
    error_log("Error creating saved_jobs table: " . $conn->error);
}

// Handle API requests
if (isset($_GET['api']) && $is_logged_in) {
    header('Content-Type: application/json');
    $response = [];

    // Handle different request methods
    $method = $_SERVER['REQUEST_METHOD'];
    error_log("API request method: $method");

    switch ($method) {
        case 'GET':
            // Fetch jobs or details about a specific job
            if (isset($_GET['id'])) {
                // Fetch details for a specific job
                $job_id = (int)$_GET['id'];
                
                $sql = "SELECT j.*, c.name as company_name, c.logo as logo_url, c.description as company_description, c.location
                        FROM jobs j 
                        JOIN companies c ON j.company_id = c.id
                        WHERE j.id = ?";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $job_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $job = $result->fetch_assoc();
                    
                    // Ensure min_cgpa is properly formatted as a decimal
                    $job['min_cgpa'] = getDefaultCgpa($job['min_cgpa']);
                    
                    // Check if user has already applied for this job
                    if ($is_logged_in) {
                        $applied_stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
                        $applied_stmt->bind_param("ii", $user_id, $job_id);
                        $applied_stmt->execute();
                        $applied_result = $applied_stmt->get_result();
                        $job['has_applied'] = ($applied_result->num_rows > 0);
                        
                        // Check if job is saved
                        $saved_stmt = $conn->prepare("SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?");
                        $saved_stmt->bind_param("ii", $user_id, $job_id);
                        $saved_stmt->execute();
                        $saved_result = $saved_stmt->get_result();
                        $job['is_saved'] = ($saved_result->num_rows > 0);
                        
                        // Check if user meets CGPA requirement
                        $user_stmt = $conn->prepare("SELECT cgpa FROM users WHERE id = ?");
                        $user_stmt->bind_param("i", $user_id);
                        $user_stmt->execute();
                        $user_result = $user_stmt->get_result();
                        if ($user_result->num_rows > 0) {
                            $user_data = $user_result->fetch_assoc();
                            $job['meets_cgpa'] = ($user_data['cgpa'] >= $job['min_cgpa']);
                        } else {
                            $job['meets_cgpa'] = false;
                        }
                    } else {
                        $job['has_applied'] = false;
                        $job['is_saved'] = false;
                        $job['meets_cgpa'] = false;
                    }
                    
                    $response = $job;
                } else {
                    $response = ['error' => 'Job not found'];
                }
            } elseif (isset($_GET['saved']) && $_GET['saved'] === 'true') {
                // Get saved jobs for this user
                $sql = "SELECT j.*, c.name as company_name, c.logo as logo_url, c.description as company_description, c.location
                        FROM saved_jobs s
                        JOIN jobs j ON s.job_id = j.id
                        JOIN companies c ON j.company_id = c.id
                        WHERE s.user_id = ?
                        ORDER BY s.saved_date DESC";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                // Get user's CGPA
                $user_stmt = $conn->prepare("SELECT cgpa FROM users WHERE id = ?");
                $user_stmt->bind_param("i", $user_id);
                $user_stmt->execute();
                $user_result = $user_stmt->get_result();
                $user_cgpa = 0;
                if ($user_result->num_rows > 0) {
                    $user_data = $user_result->fetch_assoc();
                    $user_cgpa = $user_data['cgpa'];
                }
                
                $jobs = [];
                while ($row = $result->fetch_assoc()) {
                    // Check if user has already applied for this job
                    $applied_stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
                    $applied_stmt->bind_param("ii", $user_id, $row['id']);
                    $applied_stmt->execute();
                    $applied_result = $applied_stmt->get_result();
                    $row['has_applied'] = ($applied_result->num_rows > 0);
                    
                    // Mark as saved
                    $row['is_saved'] = true;
                    
                    // Ensure min_cgpa has a value
                    $row['min_cgpa'] = getDefaultCgpa($row['min_cgpa']);
                    $row['meets_cgpa'] = ($user_cgpa >= $row['min_cgpa']);
                    
                    // Convert database columns to match the expected format
                    $row['job_id'] = $row['id'];
                    $row['role'] = $row['title'];
                    $row['salary'] = (int)$row['salary_range'];
                    if (isset($row['skills'])) {
                        $row['skills'] = $row['skills'];
                    }
                    
                    $jobs[] = $row;
                }
                
                $response = $jobs;
            } elseif (isset($_GET['get_user_skills']) && $_GET['get_user_skills'] === 'true') {
                // Get skills for current user
                $sql = "SELECT id, name, skill_type FROM skills WHERE user_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                $skills = [];
                while ($row = $result->fetch_assoc()) {
                    $skills[] = $row;
                }
                
                $response = $skills;
            } elseif (isset($_GET['get_all_jobs']) && $_GET['get_all_jobs'] === 'true') {
                // Get all jobs with company details - remove deadline filter to show more jobs
                $sql = "SELECT j.*, c.name as company_name, c.logo as logo_url 
                        FROM jobs j 
                        JOIN companies c ON j.company_id = c.id";
                
                $result = $conn->query($sql);
                
                if (!$result) {
                    // Log database error
                    error_log("Database error in jobs.php get_all_jobs: " . $conn->error);
                    $response = ['error' => 'Database error: ' . $conn->error];
                    echo json_encode($response);
                    exit;
                }
                
                $jobs = [];
                $jobCount = $result->num_rows;
                error_log("Found $jobCount jobs in database");
                
                // Get user's CGPA if logged in
                $user_cgpa = 0;
                if ($is_logged_in) {
                    $user_stmt = $conn->prepare("SELECT cgpa FROM users WHERE id = ?");
                    $user_stmt->bind_param("i", $user_id);
                    $user_stmt->execute();
                    $user_result = $user_stmt->get_result();
                    if ($user_result->num_rows > 0) {
                        $user_data = $user_result->fetch_assoc();
                        $user_cgpa = $user_data['cgpa'];
                    }
                }
                
                // Ensure min_cgpa has a value and is never 0
                function getDefaultCgpa($cgpa, $company_id = null) {
                    $cgpa = isset($cgpa) ? (float)$cgpa : 0;
                    if ($cgpa > 0) {
                        return $cgpa; // Use the existing value if it's already set
                    } else if ($company_id) {
                        // Use company-specific CGPA requirement
                        return getCompanyDefaultCgpa($company_id);
                    } else {
                        // Fallback to general default
                        return 7.0;
                    }
                }
                
                // If no jobs found, add a test job for debugging
                if ($jobCount == 0) {
                    $testJob = [
                        'id' => 999,
                        'title' => 'Test Job',
                        'company_name' => 'Test Company',
                        'location' => 'Test Location',
                        'job_type' => 'Full-time',
                        'salary_range' => 'Test Salary',
                        'deadline' => '2023-12-31',
                        'description' => 'Test Description',
                        'skills' => 'Test Skill 1, Test Skill 2, Test Skill 3',
                        'min_cgpa' => 7.5,
                        'meets_cgpa' => $user_cgpa >= 7.5,
                        'has_applied' => false,
                        'is_saved' => false
                    ];
                    $jobs[] = $testJob;
                    error_log("Added test job since no real jobs found");
                }
                
                while ($row = $result->fetch_assoc()) {
                    // Check if user has already applied for this job
                    if ($is_logged_in) {
                        $applied_stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
                        $applied_stmt->bind_param("ii", $user_id, $row['id']);
                        $applied_stmt->execute();
                        $applied_result = $applied_stmt->get_result();
                        $has_applied = ($applied_result->num_rows > 0);
                        $row['has_applied'] = $has_applied;
                        
                        // Check if job is saved
                        $saved_stmt = $conn->prepare("SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?");
                        $saved_stmt->bind_param("ii", $user_id, $row['id']);
                        $saved_stmt->execute();
                        $saved_result = $saved_stmt->get_result();
                        $row['is_saved'] = ($saved_result->num_rows > 0);
                        
                        // Ensure min_cgpa has a value
                        $row['min_cgpa'] = getDefaultCgpa($row['min_cgpa']);
                        $row['meets_cgpa'] = ($user_cgpa >= $row['min_cgpa']);
                        
                        error_log("Job ID: " . $row['id'] . ", Title: " . $row['title'] . ", Has Applied: " . ($has_applied ? 'Yes' : 'No'));
                    } else {
                        $row['has_applied'] = false;
                        $row['is_saved'] = false;
                        $row['meets_cgpa'] = false;
                        $row['min_cgpa'] = getDefaultCgpa($row['min_cgpa']);
                    }
                    
                    $jobs[] = $row;
                }
                
                $response = $jobs;
            } else {
                // Get all jobs or filtered jobs
                $sql = "SELECT j.*, c.name as company_name, c.logo as logo_url 
                        FROM jobs j 
                        JOIN companies c ON j.company_id = c.id";
                
                // Add filters if needed
                if (isset($_GET['query'])) {
                    $search = mysqli_real_escape_string($conn, $_GET['query']);
                    $sql .= " WHERE j.title LIKE '%$search%' OR j.description LIKE '%$search%' OR c.name LIKE '%$search%'";
                }
                
                // Execute query
                $result = $conn->query($sql);
                
                // Get user's CGPA if logged in
                $user_cgpa = 0;
                if ($is_logged_in) {
                    $user_stmt = $conn->prepare("SELECT cgpa FROM users WHERE id = ?");
                    $user_stmt->bind_param("i", $user_id);
                    $user_stmt->execute();
                    $user_result = $user_stmt->get_result();
                    if ($user_result->num_rows > 0) {
                        $user_data = $user_result->fetch_assoc();
                        $user_cgpa = $user_data['cgpa'];
                    }
                }
                
                // Debug: Log CGPA values for all jobs
                error_log("*** DEBUG: Fetching CGPA values for all jobs ***");
                
                $jobs = [];
                while ($row = $result->fetch_assoc()) {
                    // Debug: Log min_cgpa for each job
                    error_log("Job ID: {$row['id']}, Title: {$row['title']}, Min CGPA: " . (isset($row['min_cgpa']) ? $row['min_cgpa'] : 'NULL'));
                    
                    // Check if user has already applied for this job
                    if ($is_logged_in) {
                        $applied_stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
                        $applied_stmt->bind_param("ii", $user_id, $row['id']);
                        $applied_stmt->execute();
                        $applied_result = $applied_stmt->get_result();
                        $row['has_applied'] = ($applied_result->num_rows > 0);
                        
                        // Check if job is saved
                        $saved_stmt = $conn->prepare("SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?");
                        $saved_stmt->bind_param("ii", $user_id, $row['id']);
                        $saved_stmt->execute();
                        $saved_result = $saved_stmt->get_result();
                        $row['is_saved'] = ($saved_result->num_rows > 0);
                        
                        // Ensure min_cgpa has a value
                        $row['min_cgpa'] = getDefaultCgpa($row['min_cgpa']);
                        $row['meets_cgpa'] = ($user_cgpa >= $row['min_cgpa']);
                    } else {
                        $row['has_applied'] = false;
                        $row['is_saved'] = false;
                        $row['meets_cgpa'] = false;
                        $row['min_cgpa'] = getDefaultCgpa($row['min_cgpa']);
                    }
                    
                    $jobs[] = $row;
                }
                
                $response = $jobs;
            }
            break;
            
        case 'POST':
            // Apply for a job or save/unsave a job
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (isset($data['save_job'])) {
                // Save or unsave a job
                $job_id = $data['job_id'];
                $action = $data['action']; // 'save' or 'unsave'
                
                if ($action === 'save') {
                    // Save job (insert)
                    $checkSql = "SELECT * FROM saved_jobs WHERE user_id = ? AND job_id = ?";
                    $checkStmt = $conn->prepare($checkSql);
                    $checkStmt->bind_param("ii", $user_id, $job_id);
                    $checkStmt->execute();
                    $checkResult = $checkStmt->get_result();
                    
                    if ($checkResult->num_rows === 0) {
                        // Not already saved, so insert
                        $stmt = $conn->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)");
                        $stmt->bind_param("ii", $user_id, $job_id);
                        
                        if ($stmt->execute()) {
                            $response = ['success' => true, 'message' => 'Job saved successfully', 'action' => 'saved'];
                        } else {
                            error_log("Error saving job: " . $conn->error . ", user_id: $user_id, job_id: $job_id");
                            $response = ['success' => false, 'message' => 'Failed to save job: ' . $conn->error, 'status' => 'error'];
                        }
                    } else {
                        // Already saved
                        $response = ['success' => true, 'message' => 'Job was already saved', 'action' => 'saved'];
                    }
                } else if ($action === 'unsave') {
                    // Unsave job (delete)
                    $stmt = $conn->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?");
                    $stmt->bind_param("ii", $user_id, $job_id);
                    
                    if ($stmt->execute()) {
                        $response = ['success' => true, 'message' => 'Job removed from saved jobs', 'action' => 'unsaved'];
                    } else {
                        error_log("Error unsaving job: " . $conn->error . ", user_id: $user_id, job_id: $job_id");
                        $response = ['success' => false, 'message' => 'Failed to remove saved job: ' . $conn->error, 'status' => 'error'];
                    }
                } else {
                    $response = ['success' => false, 'message' => 'Invalid action specified', 'status' => 'error'];
                }
            } elseif (isset($data['job_id'])) {
                // Apply for a job
                $job_id = $data['job_id'];
                
                // Check if this is an apply or a regular action
                if (isset($data['apply']) && $data['apply'] === true) {
                    // This is a job application
                    
                    // Check if already applied
                    $check_stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
                    $check_stmt->bind_param("ii", $user_id, $job_id);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    
                    if ($check_result->num_rows > 0) {
                        $response = ['success' => false, 'message' => 'You have already applied for this job', 'status' => 'already_applied'];
                    } else {
                        // Insert application
                        $stmt = $conn->prepare("INSERT INTO applications (user_id, job_id, applied_date, status) VALUES (?, ?, NOW(), 'Applied')");
                        $stmt->bind_param("ii", $user_id, $job_id);
                        
                        if ($stmt->execute()) {
                            $response = ['success' => true, 'message' => 'Application submitted successfully', 'status' => 'success'];
                        } else {
                            error_log("Error applying for job: " . $conn->error . ", user_id: $user_id, job_id: $job_id");
                            $response = ['success' => false, 'message' => 'Failed to submit application: ' . $conn->error, 'status' => 'error'];
                        }
                    }
                } elseif (isset($data['withdraw']) && $data['withdraw'] === true) {
                    // This is an application withdrawal
                    
                    // Check if application exists
                    $check_stmt = $conn->prepare("SELECT * FROM applications WHERE user_id = ? AND job_id = ?");
                    $check_stmt->bind_param("ii", $user_id, $job_id);
                    $check_stmt->execute();
                    $check_result = $check_stmt->get_result();
                    
                    if ($check_result->num_rows === 0) {
                        $response = ['success' => false, 'message' => 'You have not applied for this job', 'status' => 'not_applied'];
                    } else {
                        // Delete application
                        $stmt = $conn->prepare("DELETE FROM applications WHERE user_id = ? AND job_id = ?");
                        $stmt->bind_param("ii", $user_id, $job_id);
                        
                        if ($stmt->execute()) {
                            $response = ['success' => true, 'message' => 'Application withdrawn successfully', 'status' => 'success'];
                        } else {
                            error_log("Error withdrawing application: " . $conn->error . ", user_id: $user_id, job_id: $job_id");
                            $response = ['success' => false, 'message' => 'Failed to withdraw application: ' . $conn->error, 'status' => 'error'];
                        }
                    }
                } else {
                    // This is a regular action - likely a direct API call without the save_job parameter
                    $response = ['success' => false, 'message' => 'Invalid request parameters', 'status' => 'error'];
                }
            }
            break;
            
        default:
            $response = ['error' => 'Invalid request method'];
            break;
    }

    // Return JSON response
    echo json_encode($response);
    exit;
}

// Get saved jobs for current user
$savedJobs = [];
if ($is_logged_in) {
    $savedJobsSql = "SELECT job_id FROM saved_jobs WHERE user_id = ?";
    $savedJobsStmt = $conn->prepare($savedJobsSql);
    $savedJobsStmt->bind_param("i", $user_id);
    $savedJobsStmt->execute();
    $savedJobsResult = $savedJobsStmt->get_result();
    
    while ($row = $savedJobsResult->fetch_assoc()) {
        $savedJobs[] = (string)$row['job_id'];
    }
}

// Debug output - comment out in production
// echo "<!-- Saved Jobs: " . json_encode($savedJobs) . " -->";

// If not an API request, continue to render the HTML page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - Campus Placement Portal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php if (!$is_logged_in): ?>
    <div class="container mt-5">
        <div class="alert alert-warning">
            <h4 class="alert-heading">Login Required</h4>
            <p>You need to be logged in to view job listings. Please <a href="login.php" class="alert-link">login</a> to continue.</p>
        </div>
    </div>
    <?php else: ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                        <a href="index.php" class="text-decoration-none d-flex align-items-center">
                            <i class="fas fa-graduation-cap text-primary me-2"></i>
                            <span class="fs-5 fw-semibold text-dark">SRM University</span>
                        </a>
                        <button type="button" class="btn-close d-md-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="jobs.php">
                                <i class="fas fa-briefcase me-2"></i>
                                Jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="applications.php">
                                <i class="fas fa-file-alt me-2"></i>
                                Applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php">
                                <i class="fas fa-user me-2"></i>
                                Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="resume.php">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Resume Testing                            
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="schedule.php">
                                <i class="fa-solid fa-calendar-days"></i>
                                Attendance Details
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="my-3">
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="settings.php">
                                <i class="fas fa-cog me-2"></i>
                                Settings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <div class="col-md-9 col-lg-10 bg-body-tertiary">
                <div class="d-md-none p-3">
                    <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-expanded="false" aria-controls="sidebarMenu">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                </div>
                
                <main class="p-4">
                    <div class="mb-4">
                        <h1 class="h2 fw-bold mb-2">Jobs</h1>
                        <p class="text-secondary">Browse and apply for available job opportunities</p>
                    </div>

                    <!-- Search and filters -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="fa-solid fa-search text-secondary"></i>
                                </span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search for jobs, companies, or keywords">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex gap-2 flex-wrap">
                                <select class="form-select w-auto" id="jobTypeFilter">
                                    <option value="all">All Job Types</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Internship">Internship</option>
                                    <option value="Contract">Contract</option>
                                </select>
                                
                                <select class="form-select w-auto" id="industryFilter">
                                    <option value="all">All Industries</option>
                                    <option value="Tech">Technology</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Education">Education</option>
                                </select>
                                
                                <button class="btn btn-outline-secondary d-flex align-items-center gap-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#filtersOffcanvas">
                                    <i class="fa-solid fa-filter"></i>
                                    <span>More Filters</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Job listings tabs -->
                    <ul class="nav nav-tabs mb-4" id="jobTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="recommendedTab" data-bs-toggle="tab" data-bs-target="#recommendedJobs" type="button" role="tab" aria-controls="recommendedJobs" aria-selected="true">Recommended</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="eligibleTab" data-bs-toggle="tab" data-bs-target="#eligibleJobs" type="button" role="tab" aria-controls="eligibleJobs" aria-selected="false">Eligible</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="appliedTab" data-bs-toggle="tab" data-bs-target="#appliedJobs" type="button" role="tab" aria-controls="appliedJobs" aria-selected="false">Applied</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="savedJobsTab" data-bs-toggle="tab" data-bs-target="#savedJobs" type="button" role="tab" aria-controls="savedJobs" aria-selected="false">Saved</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="jobTabsContent">
                        <div class="tab-pane fade show active" id="recommendedJobs" role="tabpanel" aria-labelledby="recommendedTab">
                            <!-- Job cards will be dynamically populated here -->
                            <div class="job-cards">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-secondary">Loading recommended jobs...</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="eligibleJobs" role="tabpanel" aria-labelledby="eligibleTab">
                            <!-- Job cards will be dynamically populated here -->
                            <div class="job-cards">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-secondary">Loading eligible jobs...</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="appliedJobs" role="tabpanel" aria-labelledby="appliedTab">
                            <!-- Job cards will be dynamically populated here -->
                            <div class="job-cards">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-secondary">Loading applied jobs...</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="savedJobs" role="tabpanel" aria-labelledby="savedJobsTab">
                            <!-- Job cards will be dynamically populated here -->
                            <div class="job-cards">
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 text-secondary">Loading saved jobs...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
                
                <footer class="text-center p-3 text-secondary border-top small">
                    © 2023 Campus Placement Portal. All rights reserved.
                    <?php if ($is_logged_in && isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                    <div class="mt-2">
                        <a href="update_cgpa.php" class="btn btn-sm btn-outline-primary">Update CGPA Requirements</a>
                    </div>
                    <?php endif; ?>
                </footer>
            </div>
        </div>
    </div>

    <!-- Job Details Modal -->
    <div class="modal fade" id="jobDetailsModal" tabindex="-1" aria-labelledby="jobDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobDetailsModalLabel">Job Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="jobDetailsModalContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Job Application Success Modal -->
    <div class="modal fade" id="applicationSuccessModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Application Submitted</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <p class="text-center fs-5">Your application has been submitted successfully!</p>
                    <p class="text-center">You can track your application status in the <a href="applications.php">Applications</a> section.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="applications.php" class="btn btn-primary">View Applications</a>
                </div>
            </div>
        </div>
    </div>

    <!-- More Filters Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="filtersOffcanvas" aria-labelledby="filtersOffcanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filtersOffcanvasLabel">Advanced Filters</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="advancedFiltersForm">
                <div class="mb-3">
                    <label class="form-label">Salary Range</label>
                    <div class="row g-2">
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" id="minSalary" placeholder="Min" min="0">
                            </div>
                        </div>
                        <div class="col">
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" id="maxSalary" placeholder="Max" min="0">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Experience Level</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="entry" id="entryLevel">
                        <label class="form-check-label" for="entryLevel">Entry Level (0-2 years)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="mid" id="midLevel">
                        <label class="form-check-label" for="midLevel">Mid Level (3-5 years)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="senior" id="seniorLevel">
                        <label class="form-check-label" for="seniorLevel">Senior Level (6+ years)</label>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Location</label>
                    <select class="form-select" id="locationFilter" multiple size="4">
                        <option value="bangalore">Bangalore</option>
                        <option value="chennai">Chennai</option>
                        <option value="hyderabad">Hyderabad</option>
                        <option value="mumbai">Mumbai</option>
                        <option value="pune">Pune</option>
                        <option value="delhi">Delhi NCR</option>
                        <option value="remote">Remote</option>
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Skills</label>
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" placeholder="Add a skill" id="skillInput">
                        <button class="btn btn-outline-secondary" type="button" id="addSkillBtn">Add</button>
                    </div>
                    <div id="selectedSkills" class="d-flex flex-wrap gap-2 mt-2">
                        <!-- Skills will be added here -->
                    </div>
                </div>
                
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" id="applyFiltersBtn">Apply Filters</button>
                    <button type="button" class="btn btn-outline-secondary" id="resetFiltersBtn">Reset Filters</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Initialize tabs
        document.addEventListener('DOMContentLoaded', function() {
            // Remove the custom styles we added previously
            // Get user skills
            fetchUserSkills().then(skills => {
                // Fetch jobs from the database
                fetchJobs().then(jobs => {
                    // Store the jobs in the global variable
                    allJobs = jobs;
                    
                    // Display jobs only once after fetching data
                    displaySampleJobs('recommendedJobs');
                    
                    // Add event listeners to tab buttons
                    document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
                        tab.addEventListener('click', function() {
                            const containerId = this.getAttribute('data-bs-target').substring(1);
                            displaySampleJobs(containerId);
                        });
                    });
                    
                    // Add event listeners for the modal buttons
                    document.addEventListener('click', function(e) {
                        // For apply buttons in the modal
                        if (e.target.classList.contains('apply-job-modal') || 
                            (e.target.parentElement && e.target.parentElement.classList.contains('apply-job-modal'))) {
                            const button = e.target.classList.contains('apply-job-modal') ? e.target : e.target.parentElement;
                            const jobId = button.getAttribute('data-job-id');
                            applyForJob(jobId);
                        }
                        
                        // For save buttons in the modal
                        if (e.target.classList.contains('save-job-modal') || 
                            (e.target.parentElement && e.target.parentElement.classList.contains('save-job-modal'))) {
                            const button = e.target.classList.contains('save-job-modal') ? e.target : e.target.parentElement;
                            const jobId = button.getAttribute('data-job-id');
                            saveJob(jobId);
                        }
                        
                        // For unsave buttons in the modal
                        if (e.target.classList.contains('unsave-job-modal') || 
                            (e.target.parentElement && e.target.parentElement.classList.contains('unsave-job-modal'))) {
                            const button = e.target.classList.contains('unsave-job-modal') ? e.target : e.target.parentElement;
                            const jobId = button.getAttribute('data-job-id');
                            unsaveJob(jobId);
                        }
                        
                        // For withdraw buttons in the modal
                        if (e.target.classList.contains('withdraw-job-modal') || 
                            (e.target.parentElement && e.target.parentElement.classList.contains('withdraw-job-modal'))) {
                            const button = e.target.classList.contains('withdraw-job-modal') ? e.target : e.target.parentElement;
                            const jobId = button.getAttribute('data-job-id');
                            withdrawApplication(jobId);
                        }
                    });
                });
            });
        });
        
        // Function to display job details in modal
        function displayJobDetails(job) {
            console.log("Displaying details for job:", job);
            
            // Make sure the job object is valid
            if (!job) {
                console.error("Invalid job object:", job);
                showToast('error', 'Could not display job details. Please try again.');
                return;
            }
            
            const modalContent = document.getElementById('jobDetailsModalContent');
            if (!modalContent) {
                console.error("Modal content element not found!");
                return;
            }
            
            // Ensure Bootstrap is available
            if (typeof bootstrap === 'undefined') {
                console.error("Bootstrap is not defined. Make sure you've included Bootstrap JS.");
                showToast('error', 'Could not load job details. Please refresh the page.');
                return;
            }
            
            modalContent.innerHTML = `
                <div class="d-flex flex-column">
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0">${job.role || job.title || 'Job Position'}</h5>
                            <span class="badge bg-primary">${job.job_type || 'Job'}</span>
                        </div>
                        <p class="text-muted mb-0">${job.company_name || 'Company'}</p>
                        <p class="small text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>${job.location || 'Location not specified'}
                        </p>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Salary: ${job.salary || 'Not specified'}</p>
                                <p class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Deadline: ${job.deadline || 'Not specified'}</p>
                                <p class="mb-0"><i class="fas fa-graduation-cap me-2"></i>Min CGPA: ${job.min_cgpa.toFixed(2)}</p>
                                ${!job.meets_cgpa ? 
                                    `<p class="mb-0 text-danger mt-2"><i class="fas fa-exclamation-circle me-2"></i>Your CGPA doesn't meet the minimum requirement</p>` : 
                                    `<p class="mb-0 text-success mt-2"><i class="fas fa-check-circle me-2"></i>Your CGPA meets the requirement</p>`
                                }
                            </div>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-muted">Skills Required</h6>
                        <div class="d-flex flex-wrap gap-1">
                            ${job.skills && Array.isArray(job.skills) ? job.skills.map(skill => `<span class="badge bg-secondary">${skill}</span>`).join('') : 'No specific skills listed'}
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="text-muted">Job Description</h6>
                        <p>${job.description || 'No description available'}</p>
                    </div>
                    <div class="mt-4 d-flex justify-content-between">
                        ${!job.has_applied ? 
                            `<button class="btn btn-primary apply-job-modal ${!job.meets_cgpa ? 'disabled' : ''}" data-job-id="${job.job_id}" ${!job.meets_cgpa ? 'disabled' : ''}>
                                <i class="fas fa-paper-plane me-2"></i>Apply Now
                                ${!job.meets_cgpa ? '<small>(CGPA requirement not met)</small>' : ''}
                            </button>` : 
                            `<div>
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check me-2"></i>Applied
                                </button>
                                <button class="btn btn-outline-danger withdraw-job-modal" data-job-id="${job.job_id}">
                                    <i class="fas fa-times me-2"></i>Withdraw
                                </button>
                            </div>`
                        }
                        <button class="btn ${job.is_saved ? 'btn-danger unsave-job-modal' : 'btn-outline-danger save-job-modal'}" data-job-id="${job.job_id}">
                            <i class="fas fa-heart me-2"></i>${job.is_saved ? 'Unsave' : 'Save'}
                        </button>
                    </div>
                </div>
            `;
            
            // Add event listeners for the buttons in the modal
            const applyBtn = modalContent.querySelector('.apply-job-modal');
            if (applyBtn) {
                applyBtn.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    applyForJob(jobId);
                });
            }
            
            const saveBtn = modalContent.querySelector('.save-job-modal');
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    saveJob(jobId);
                });
            }
            
            const unsaveBtn = modalContent.querySelector('.unsave-job-modal');
            if (unsaveBtn) {
                unsaveBtn.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    unsaveJob(jobId);
                });
            }
            
            const withdrawBtn = modalContent.querySelector('.withdraw-job-modal');
            if (withdrawBtn) {
                withdrawBtn.addEventListener('click', function() {
                    const jobId = this.getAttribute('data-job-id');
                    withdrawApplication(jobId);
                });
            }
            
            try {
                // Get the modal element
                const modalElement = document.getElementById('jobDetailsModal');
                if (!modalElement) {
                    console.error("Modal element not found!");
                    return;
                }
                
                // Show the modal
                const jobDetailsModal = new bootstrap.Modal(modalElement);
                jobDetailsModal.show();
            } catch (error) {
                console.error("Error showing modal:", error);
                // Try alternative method if bootstrap modal fails
                const modalElement = document.getElementById('jobDetailsModal');
                if (modalElement) {
                    modalElement.classList.add('show');
                    modalElement.style.display = 'block';
                    document.body.classList.add('modal-open');
                    
                    // Create backdrop if it doesn't exist
                    let backdrop = document.querySelector('.modal-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.classList.add('modal-backdrop', 'fade', 'show');
                        document.body.appendChild(backdrop);
                    }
                }
            }
        }

        // Get user skills from database
        async function fetchUserSkills() {
            try {
                const response = await fetch('jobs.php?api=true&get_user_skills=true');
                const data = await response.json();
                
                // Update the global userSkills array
                userSkills = data.map(skill => skill.name);
                
                // Debug: display user skills in detail
                console.log("User skills details:");
                data.forEach(skill => {
                    console.log(`- ${skill.id}: ${skill.name} (${skill.skill_type})`);
                });
                console.log("User skills array:", userSkills);
                
                return userSkills;
            } catch (error) {
                console.error("Error fetching user skills:", error);
                return [];
            }
        }

        // Fetch jobs from database
        async function fetchJobs() {
            try {
                console.log("Fetching jobs from API...");
                const response = await fetch('jobs.php?api=true&get_all_jobs=true');
                
                if (!response.ok) {
                    console.error("API response not OK:", response.status, response.statusText);
                    return [];
                }
                
                const data = await response.json();
                console.log(`Raw jobs data from API (${data.length} items)`);
                
                if (data.length === 0) {
                    console.error("API returned 0 jobs!");
                    return [];
                }
                
                if (data.error) {
                    console.error("API returned error:", data.error);
                    return [];
                }
                
                // Format job data
                const formattedJobs = data.map(job => {
                    // Create skills array from string
                    let skillsArray = [];
                    if (job.skills) {
                        if (typeof job.skills === 'string') {
                            skillsArray = job.skills.split(',').map(s => s.trim());
                        } else if (Array.isArray(job.skills)) {
                            skillsArray = job.skills;
                        }
                    }
                    
                    // Ensure min_cgpa is always a number, default to 6.00 if not specified or is zero
                    const min_cgpa = job.min_cgpa !== null && job.min_cgpa !== undefined && parseFloat(job.min_cgpa) > 0 
                        ? parseFloat(job.min_cgpa) 
                        : 6.00; // Use 6.00 as default minimum CGPA
                    
                    return {
                        job_id: job.id,
                        role: job.title,
                        company_name: job.company_name,
                        location: job.location || "Not specified",
                        job_type: job.job_type || "Full-time",
                        salary: job.salary_range || "Not specified",
                        deadline: job.deadline || "Not specified",
                        description: job.description || "No description available",
                        skills: skillsArray,
                        min_cgpa: min_cgpa,
                        meets_cgpa: job.meets_cgpa || false,
                        has_applied: !!job.has_applied,
                        is_saved: !!job.is_saved
                    };
                });
                
                // Enhanced deduplication logic using multiple criteria
                // First by ID, then by combination of title+company+location
                const uniqueJobs = {};
                const titleCompanyMap = new Map(); // Track combinations of title+company+location
                
                formattedJobs.forEach(job => {
                    const jobId = parseInt(job.job_id);
                    
                    // Create a unique key based on job details to catch duplicate listings
                    // even with different IDs
                    const jobSignature = `${job.role.toLowerCase()}|${job.company_name.toLowerCase()}|${job.location.toLowerCase()}`;
                    
                    // Check if we've already seen this job signature
                    if (titleCompanyMap.has(jobSignature)) {
                        console.log(`Skipping duplicate job with signature: ${jobSignature}, ID: ${jobId}`);
                        // If this job has a newer deadline, replace the existing one
                        const existingId = titleCompanyMap.get(jobSignature);
                        const existingJob = uniqueJobs[existingId];
                        
                        if (existingJob && new Date(job.deadline) > new Date(existingJob.deadline)) {
                            console.log(`Replacing older job (ID: ${existingId}) with newer job (ID: ${jobId}) based on deadline`);
                            uniqueJobs[existingId] = job;
                        }
                        return;
                    }
                    
                    // This is a new unique job
                    uniqueJobs[jobId] = job;
                    titleCompanyMap.set(jobSignature, jobId);
                });
                
                const dedupedJobs = Object.values(uniqueJobs);
                console.log(`Processed ${formattedJobs.length} jobs, returning ${dedupedJobs.length} unique jobs after enhanced deduplication`);
                return dedupedJobs;
            } catch (error) {
                console.error("Error fetching jobs:", error);
                return [];
            }
        }

        // Initialize saved jobs from PHP data
        let savedJobIds = <?php echo json_encode($savedJobs); ?>;
        
        // Initialize user skills and jobs arrays (will be filled by API calls)
        let userSkills = [];
        let allJobs = [];
        
        // Find a job by ID
        function findJobById(id, jobsArray) {
            const jobId = parseInt(id);
            console.log(`Looking for job with ID ${jobId} in array of ${jobsArray.length} jobs`);
            
            const job = jobsArray.find(job => parseInt(job.job_id) === jobId);
            
            if (job) {
                console.log("Found job:", job.role || job.title);
            } else {
                console.error("Job not found with ID:", jobId);
                // For debugging, log the first few job IDs in the array
                if (jobsArray.length > 0) {
                    console.log("Available job IDs:", jobsArray.slice(0, 5).map(j => j.job_id));
                }
            }
            
            return job;
        }

        // Add a utility function to check if skills actually match
        function doSkillsMatch(jobSkill, userSkill) {
            // Normalize both skills for comparison
            const normalizedJobSkill = jobSkill.toLowerCase().trim();
            const normalizedUserSkill = userSkill.toLowerCase().trim();
            
            // Check exact match
            const isExactMatch = normalizedJobSkill === normalizedUserSkill;
            
            // Also check if one contains the other
            const jobContainsUser = normalizedJobSkill.includes(normalizedUserSkill);
            const userContainsJob = normalizedUserSkill.includes(normalizedJobSkill);
            
            return isExactMatch || (jobContainsUser && normalizedUserSkill.length > 3) || (userContainsJob && normalizedJobSkill.length > 3);
        }
        
        // Display jobs in a given container
        function displaySampleJobs(containerId) {
            // Get the container element
            const container = document.getElementById(containerId);
            if (!container) {
                console.error(`Container with ID ${containerId} not found`);
                return;
            }
            
            // Clear existing content
            container.innerHTML = '';
            
            // If no jobs are loaded, display a message
            if (!allJobs || allJobs.length === 0) {
                container.innerHTML = '<div class="alert alert-info">No jobs available at the moment.</div>';
                return;
            }
            
            console.log(`Displaying ${containerId} with ${allJobs.length} total jobs`);
            
            // Create a set to track jobs that have already been displayed
            // This prevents the same job from being displayed multiple times
            const displayedJobIds = new Set();
            
            let filteredJobs = [];
            
            if (containerId === 'recommendedJobs') {
                // For recommended jobs, filter based on user skills and CGPA
                filteredJobs = allJobs.filter(job => {
                    // Check if the job has already been displayed
                    if (displayedJobIds.has(job.job_id)) {
                        console.log(`Skipping duplicate job: ${job.role || job.title} (ID: ${job.job_id})`);
                        return false;
                    }
                    
                    // Check if the job has any matching skills
                    const hasMatchingSkills = userSkills.length > 0 && job.skills && job.skills.some(skill => 
                        userSkills.includes(skill.toLowerCase()) || userSkills.includes(skill.toUpperCase()) || userSkills.includes(skill)
                    );
                    
                    // Both skill match and CGPA requirements must be met
                    if (hasMatchingSkills && job.meets_cgpa) {
                        displayedJobIds.add(job.job_id);
                        return true;
                    }
                    
                    return false;
                });
                
                // Log the number of recommended jobs
                console.log(`Found ${filteredJobs.length} recommended jobs based on user skills and CGPA`);
                
            } else if (containerId === 'eligibleJobs') {
                // For eligible jobs, only consider CGPA requirement
                filteredJobs = allJobs.filter(job => {
                    if (displayedJobIds.has(job.job_id)) {
                        return false;
                    }
                    
                    if (job.meets_cgpa) {
                        displayedJobIds.add(job.job_id);
                        return true;
                    }
                    
                    return false;
                });
                
                console.log(`Found ${filteredJobs.length} eligible jobs based on CGPA`);
                
            } else if (containerId === 'appliedJobs') {
                // For applied jobs, only show jobs the user has applied to
                filteredJobs = allJobs.filter(job => {
                    if (displayedJobIds.has(job.job_id)) {
                        return false;
                    }
                    
                    if (job.has_applied) {
                        displayedJobIds.add(job.job_id);
                        return true;
                    }
                    
                    return false;
                });
                
                console.log(`Found ${filteredJobs.length} applied jobs`);
                
            } else if (containerId === 'savedJobs') {
                // For saved jobs, only show jobs the user has saved
                filteredJobs = allJobs.filter(job => {
                    if (displayedJobIds.has(job.job_id)) {
                        return false;
                    }
                    
                    if (job.is_saved) {
                        displayedJobIds.add(job.job_id);
                        return true;
                    }
                    
                    return false;
                });
                
                console.log(`Found ${filteredJobs.length} saved jobs`);
                
            } else {
                // For any other container, show all jobs
                filteredJobs = allJobs.filter(job => {
                    if (displayedJobIds.has(job.job_id)) {
                        return false;
                    }
                    
                    displayedJobIds.add(job.job_id);
                    return true;
                });
            }
            
            // Check if we have any jobs to display
            if (filteredJobs.length === 0) {
                container.innerHTML = getEmptyStateMessage(containerId);
                return;
            }
            
            // Create rows for Bootstrap grid (3 cards per row)
            let row;
            let cardsRendered = 0;
            
            filteredJobs.forEach((job, index) => {
                // Calculate number of matching skills for display
                let matchingSkills = [];
                if (userSkills && job.skills && Array.isArray(job.skills)) {
                    // Use more flexible matching logic
                    job.skills.forEach(jobSkill => {
                        if (jobSkill) { // Make sure jobSkill is not null or undefined
                            userSkills.forEach(userSkill => {
                                if (userSkill && doSkillsMatch(jobSkill, userSkill)) {
                                    if (!matchingSkills.includes(jobSkill)) {
                                        matchingSkills.push(jobSkill);
                                    }
                                }
                            });
                        }
                    });
                }
                
                if (index % 3 === 0) {
                    row = document.createElement('div');
                    row.className = 'row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-4';
                    container.appendChild(row);
                }
                
                // Create a column for this job card
                const jobColumn = document.createElement('div');
                jobColumn.className = 'col';
                
                // Ensure job.skills is an array before using map
                const skillsArray = Array.isArray(job.skills) ? job.skills : [];
                
                // Job card HTML
                jobColumn.innerHTML = `
                    <div class="card h-100 job-card" data-job-id="${job.job_id}">
                        <div class="card-header bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-primary">${job.job_type || 'Job'}</span>
                                    ${matchingSkills.length > 0 ? `<span class="badge bg-success ms-1">${matchingSkills.length}/${skillsArray.length} skills match</span>` : ''}
                                    ${job.meets_cgpa ? `<span class="badge bg-success ms-1">CGPA Ok</span>` : `<span class="badge bg-danger ms-1">Min CGPA: ${job.min_cgpa.toFixed(2)}</span>`}
                                </div>
                                <small class="text-muted">Deadline: ${job.deadline}</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title mb-1">${job.role || job.title || 'Job Position'}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">${job.company_name || 'Company'}</h6>
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>${job.location || 'Location not specified'} |
                                    <i class="fas fa-money-bill-wave me-1"></i>${job.salary || 'Salary not specified'} |
                                    <i class="fas fa-graduation-cap me-1"></i>Min CGPA: ${job.min_cgpa.toFixed(2)}
                                </small>
                            </div>
                            <div class="mb-3">
                                <p class="card-text small text-secondary">Required skills:</p>
                                <div class="d-flex flex-wrap gap-1 mb-2">
                                    ${skillsArray.map(jobSkill => {
                                        if (!jobSkill) return ''; // Skip empty skills
                                        const isMatch = userSkills.some(userSkill => userSkill && doSkillsMatch(jobSkill, userSkill));
                                        return `<span class="badge ${isMatch ? 'bg-success' : 'bg-secondary'}">${jobSkill}</span>`;
                                    }).join('')}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-sm btn-outline-primary view-details" data-job-id="${job.job_id}">
                                    <i class="fas fa-eye"></i> Details
                                </button>
                                ${!job.has_applied ? `
                                <button class="btn btn-sm btn-outline-success apply-job ${!job.meets_cgpa ? 'disabled' : ''}" data-job-id="${job.job_id}" ${!job.meets_cgpa ? 'disabled' : ''}>
                                    <i class="fas fa-paper-plane"></i> Apply
                                </button>
                                ` : `
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-success" disabled>
                                        <i class="fas fa-check"></i> Applied
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger withdraw-job" data-job-id="${job.job_id}">
                                        <i class="fas fa-times"></i> Withdraw
                                    </button>
                                </div>
                                `}
                                <button class="btn btn-sm btn-outline-danger ${job.is_saved ? 'unsave-job' : 'save-job'}" data-job-id="${job.job_id}">
                                    <i class="fas fa-heart"></i> ${job.is_saved ? 'Unsave' : 'Save'}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                row.appendChild(jobColumn);
                cardsRendered++;
            });
            
            // Add event listeners for job actions
            setTimeout(() => {
                // View details buttons
                container.querySelectorAll('.view-details').forEach(button => {
                    button.addEventListener('click', function() {
                        const jobId = this.getAttribute('data-job-id');
                        const job = findJobById(jobId, allJobs);
                        if (job) displayJobDetails(job);
                    });
                });
                
                // Apply buttons
                container.querySelectorAll('.apply-job').forEach(button => {
                    button.addEventListener('click', function() {
                        const jobId = this.getAttribute('data-job-id');
                        applyForJob(jobId);
                    });
                });
                
                // Save buttons
                container.querySelectorAll('.save-job').forEach(button => {
                    button.addEventListener('click', function() {
                        const jobId = this.getAttribute('data-job-id');
                        saveJob(jobId);
                    });
                });
                
                // Unsave buttons
                container.querySelectorAll('.unsave-job').forEach(button => {
                    button.addEventListener('click', function() {
                        const jobId = this.getAttribute('data-job-id');
                        unsaveJob(jobId);
                    });
                });
                
                // Withdraw buttons
                container.querySelectorAll('.withdraw-job').forEach(button => {
                    button.addEventListener('click', function() {
                        const jobId = this.getAttribute('data-job-id');
                        withdrawApplication(jobId);
                    });
                });
            }, 500);
        }
        
        // Function to get action buttons for job cards
        function getActionButtons(job, containerId) {
            // Different buttons depending on job state and container
            let buttons = '';
            
            // View details button - always shown
            buttons += `<button class="btn btn-sm btn-outline-primary view-details-btn" data-job-id="${job.job_id}">
                <i class="fas fa-eye"></i> Details
            </button>`;
            
            // Apply button - not shown if already applied
            if (!job.has_applied) {
                buttons += `<button class="btn btn-sm btn-outline-success apply-btn ${!job.meets_cgpa ? 'disabled' : ''}" 
                    data-job-id="${job.job_id}" ${!job.meets_cgpa ? 'disabled' : ''}>
                    <i class="fas fa-paper-plane"></i> Apply
                </button>`;
                } else {
                buttons += `<button class="btn btn-sm btn-success" disabled>
                    <i class="fas fa-check"></i> Applied
                </button>`;
            }
            
            // Save/Unsave button
            if (job.is_saved) {
                buttons += `<button class="btn btn-sm btn-outline-danger unsave-btn" data-job-id="${job.job_id}">
                    <i class="fas fa-heart"></i> Unsave
                </button>`;
                    } else {
                buttons += `<button class="btn btn-sm btn-outline-danger save-btn" data-job-id="${job.job_id}">
                    <i class="fas fa-heart"></i> Save
                </button>`;
            }
            
            return buttons;
        }
        
        // Function to show job details in modal
        function showJobDetails(job) {
            displayJobDetails(job);
        }

        // Function to apply for a job
        function applyForJob(jobId) {
            console.log("Applying for job with ID:", jobId);
            
            // Find the job in allJobs array
            const job = findJobById(jobId, allJobs);
            if (!job) {
                console.error("Job not found with ID:", jobId);
                return;
            }
            
            // Check if user meets CGPA requirement
            if (!job.meets_cgpa) {
                showToast('error', 'You do not meet the minimum CGPA requirement for this job.');
                return;
            }
            
            // Check if already applied
            if (job.has_applied) {
                showToast('warning', 'You have already applied for this job.');
                return;
            }
            
            // Send application to the server
            fetch('jobs.php?api=true', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    job_id: jobId,
                    apply: true
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Application response:", data);
                
                if (data.success) {
                    // Show success modal
                    const modal = new bootstrap.Modal(document.getElementById('applicationSuccessModal'));
                    modal.show();
                    
                    // Update job status in our data
                    job.has_applied = true;
                    
                    // Update UI
                    updateJobUI(jobId);
                    
                    showToast('success', data.message || 'Application submitted successfully!');
                } else {
                    showToast('error', data.message || 'Failed to submit application. Please try again.');
                }
            })
            .catch(error => {
                console.error("Error applying for job:", error);
                showToast('error', 'An error occurred. Please try again later.');
            });
        }
        
        // Function to save a job
        function saveJob(jobId) {
            console.log("Saving job with ID:", jobId);
            
            // Find the job in allJobs array
            const job = findJobById(jobId, allJobs);
            if (!job) {
                console.error("Job not found with ID:", jobId);
                return;
            }
            
            // Check if already saved
            if (job.is_saved) {
                showToast('info', 'This job is already saved.');
                return;
            }
            
            // Send save request to the server
            fetch('jobs.php?api=true', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    save_job: true,
                    job_id: jobId,
                    action: 'save'
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Save job response:", data);
                
                if (data.success) {
                    // Update job status in our data
                    job.is_saved = true;
                    savedJobIds.push(jobId.toString());
                    
                    // Update UI
                    updateJobUI(jobId);
                    
                    showToast('success', data.message || 'Job saved successfully!');
                } else {
                    showToast('error', data.message || 'Failed to save job. Please try again.');
                }
            })
            .catch(error => {
                console.error("Error saving job:", error);
                showToast('error', 'An error occurred. Please try again later.');
            });
        }
        
        // Function to unsave a job
        function unsaveJob(jobId) {
            console.log("Unsaving job with ID:", jobId);
            
            // Find the job in allJobs array
            const job = findJobById(jobId, allJobs);
            if (!job) {
                console.error("Job not found with ID:", jobId);
                return;
            }
            
            // Check if not saved
            if (!job.is_saved) {
                showToast('info', 'This job is not saved.');
                return;
            }
            
            // Send unsave request to the server
            fetch('jobs.php?api=true', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    save_job: true,
                    job_id: jobId,
                    action: 'unsave'
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Unsave job response:", data);
                
                if (data.success) {
                    // Update job status in our data
                    job.is_saved = false;
                    savedJobIds = savedJobIds.filter(id => id !== jobId.toString());
                    
                    // Update UI
                    updateJobUI(jobId);
                    
                    // If we're in the saved jobs tab, we might need to refresh
                    const activeTab = document.querySelector('.nav-link.active');
                    if (activeTab && activeTab.id === 'savedJobsTab') {
                        displaySampleJobs('savedJobs');
                    }
                    
                    showToast('success', data.message || 'Job removed from saved jobs.');
                } else {
                    showToast('error', data.message || 'Failed to remove job from saved jobs. Please try again.');
                }
            })
            .catch(error => {
                console.error("Error unsaving job:", error);
                showToast('error', 'An error occurred. Please try again later.');
            });
        }
        
        // Function to update the UI after job actions
        function updateJobUI(jobId) {
            // Find all instances of this job in the UI and update them
            document.querySelectorAll(`.job-card[data-job-id="${jobId}"]`).forEach(card => {
                const job = findJobById(jobId, allJobs);
                if (!job) return;
                
                // Update save/unsave buttons
                const saveBtn = card.querySelector('.save-job');
                const unsaveBtn = card.querySelector('.unsave-job');
                
                if (saveBtn && job.is_saved) {
                    saveBtn.classList.remove('save-job');
                    saveBtn.classList.add('unsave-job');
                    saveBtn.innerHTML = '<i class="fas fa-heart"></i> Unsave';
                } else if (unsaveBtn && !job.is_saved) {
                    unsaveBtn.classList.remove('unsave-job');
                    unsaveBtn.classList.add('save-job');
                    unsaveBtn.innerHTML = '<i class="fas fa-heart"></i> Save';
                }
                
                // Update apply button
                const applyBtn = card.querySelector('.apply-job');
                if (applyBtn && job.has_applied) {
                    const btnParent = applyBtn.parentNode;
                    btnParent.removeChild(applyBtn);
                    
                    const appliedBtnGroup = document.createElement('div');
                    appliedBtnGroup.className = 'btn-group';
                    appliedBtnGroup.setAttribute('role', 'group');
                    
                    const appliedBtn = document.createElement('button');
                    appliedBtn.className = 'btn btn-sm btn-success';
                    appliedBtn.disabled = true;
                    appliedBtn.innerHTML = '<i class="fas fa-check"></i> Applied';
                    
                    const withdrawBtn = document.createElement('button');
                    withdrawBtn.className = 'btn btn-sm btn-outline-danger withdraw-job';
                    withdrawBtn.setAttribute('data-job-id', jobId);
                    withdrawBtn.innerHTML = '<i class="fas fa-times"></i> Withdraw';
                    withdrawBtn.addEventListener('click', function() {
                        withdrawApplication(jobId);
                    });
                    
                    appliedBtnGroup.appendChild(appliedBtn);
                    appliedBtnGroup.appendChild(withdrawBtn);
                    btnParent.appendChild(appliedBtnGroup);
                } else if (!applyBtn && !job.has_applied) {
                    // If the user withdrew their application, we need to add the apply button back
                    const appliedBtnGroup = card.querySelector('.btn-group');
                    if (appliedBtnGroup) {
                        const btnParent = appliedBtnGroup.parentNode;
                        btnParent.removeChild(appliedBtnGroup);
                        
                        const newApplyBtn = document.createElement('button');
                        newApplyBtn.className = 'btn btn-sm btn-outline-success apply-job';
                        if (!job.meets_cgpa) {
                            newApplyBtn.classList.add('disabled');
                            newApplyBtn.setAttribute('disabled', 'disabled');
                        }
                        newApplyBtn.setAttribute('data-job-id', jobId);
                        newApplyBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Apply';
                        newApplyBtn.addEventListener('click', function() {
                            applyForJob(jobId);
                        });
                        
                        btnParent.appendChild(newApplyBtn);
                    }
                }
            });
            
            // Update buttons in the job details modal
            const modalContent = document.getElementById('jobDetailsModalContent');
            if (modalContent) {
                const job = findJobById(jobId, allJobs);
                if (!job) return;
                
                // Update modal save/unsave button
                const modalSaveBtn = modalContent.querySelector('.save-job-modal');
                const modalUnsaveBtn = modalContent.querySelector('.unsave-job-modal');
                
                if (modalSaveBtn && job.is_saved) {
                    modalSaveBtn.classList.remove('save-job-modal');
                    modalSaveBtn.classList.add('unsave-job-modal');
                    modalSaveBtn.innerHTML = '<i class="fas fa-heart me-2"></i>Unsave';
                } else if (modalUnsaveBtn && !job.is_saved) {
                    modalUnsaveBtn.classList.remove('unsave-job-modal');
                    modalUnsaveBtn.classList.add('save-job-modal');
                    modalUnsaveBtn.innerHTML = '<i class="fas fa-heart me-2"></i>Save';
                }
                
                // Update modal apply button
                const modalApplyBtn = modalContent.querySelector('.apply-job-modal');
                if (modalApplyBtn && job.has_applied) {
                    const btnParent = modalApplyBtn.parentNode;
                    btnParent.removeChild(modalApplyBtn);
                    
                    const appliedBtnDiv = document.createElement('div');
                    
                    const appliedBtn = document.createElement('button');
                    appliedBtn.className = 'btn btn-success';
                    appliedBtn.disabled = true;
                    appliedBtn.innerHTML = '<i class="fas fa-check me-2"></i>Applied';
                    
                    const withdrawBtn = document.createElement('button');
                    withdrawBtn.className = 'btn btn-outline-danger withdraw-job-modal ms-2';
                    withdrawBtn.setAttribute('data-job-id', jobId);
                    withdrawBtn.innerHTML = '<i class="fas fa-times me-2"></i>Withdraw';
                    withdrawBtn.addEventListener('click', function() {
                        withdrawApplication(jobId);
                    });
                    
                    appliedBtnDiv.appendChild(appliedBtn);
                    appliedBtnDiv.appendChild(withdrawBtn);
                    btnParent.appendChild(appliedBtnDiv);
                } else if (!modalApplyBtn && !job.has_applied) {
                    // If the user withdrew their application, add back the apply button
                    const appliedBtnDiv = modalContent.querySelector('.btn-success')?.parentNode;
                    if (appliedBtnDiv && appliedBtnDiv.contains(modalContent.querySelector('.withdraw-job-modal'))) {
                        const btnParent = appliedBtnDiv.parentNode;
                        btnParent.removeChild(appliedBtnDiv);
                        
                        const newApplyBtn = document.createElement('button');
                        newApplyBtn.className = 'btn btn-primary apply-job-modal';
                        if (!job.meets_cgpa) {
                            newApplyBtn.classList.add('disabled');
                            newApplyBtn.setAttribute('disabled', 'disabled');
                            newApplyBtn.innerHTML = `<i class="fas fa-paper-plane me-2"></i>Apply Now <small>(CGPA requirement not met)</small>`;
                        } else {
                            newApplyBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Apply Now';
                        }
                        newApplyBtn.setAttribute('data-job-id', jobId);
                        newApplyBtn.addEventListener('click', function() {
                            applyForJob(jobId);
                        });
                        
                        btnParent.appendChild(newApplyBtn);
                    }
                }
            }
            
            // Refresh tabs data
            const activeTab = document.querySelector('.nav-link.active');
            if (activeTab) {
                const tabId = activeTab.getAttribute('aria-controls');
                if (tabId) {
                    setTimeout(() => {
                        displaySampleJobs(tabId);
                    }, 1000);
                }
            }
        }
        
        // Function to show toast messages
        function showToast(type, message) {
            // Create toast container if it doesn't exist
            let toastContainer = document.querySelector('.toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
                document.body.appendChild(toastContainer);
            }
            
            // Create a new toast
            const toastId = 'toast-' + Date.now();
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'info'}`;
            toast.setAttribute('id', toastId);
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Show the toast
            const bsToast = new bootstrap.Toast(toast, {
                autohide: true,
                delay: 3000
            });
            bsToast.show();
            
            // Remove toast after it's hidden
            toast.addEventListener('hidden.bs.toast', function() {
                if (toastContainer.contains(toast)) {
                    toastContainer.removeChild(toast);
                }
            });
        }

        // Function to get empty state message for different containers
        function getEmptyStateMessage(containerId) {
            if (containerId === 'recommendedJobs') {
                return `
                    <div class="text-center py-5">
                        <i class="fas fa-lightbulb text-secondary mb-3" style="font-size: 3rem;"></i>
                        <h4 class="fw-medium mb-2">No recommended jobs found</h4>
                        <p class="text-secondary mb-4">Add more skills to your profile to get job recommendations that match your qualifications</p>
                    </div>
                `;
            } else if (containerId === 'eligibleJobs') {
                return `
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase text-secondary mb-3" style="font-size: 3rem;"></i>
                        <h4 class="fw-medium mb-2">No eligible jobs found</h4>
                        <p class="text-secondary mb-4">You may need to meet minimum CGPA requirements for available jobs</p>
                    </div>
                `;
            } else if (containerId === 'appliedJobs') {
                return `
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt text-secondary mb-3" style="font-size: 3rem;"></i>
                        <h4 class="fw-medium mb-2">No applied jobs found</h4>
                        <p class="text-secondary mb-4">Jobs you've applied to will appear here</p>
                    </div>
                `;
            } else if (containerId === 'savedJobs') {
                return `
                    <div class="text-center py-5">
                        <i class="fas fa-heart text-secondary mb-3" style="font-size: 3rem;"></i>
                        <h4 class="fw-medium mb-2">No saved jobs found</h4>
                        <p class="text-secondary mb-4">Save jobs you're interested in to view them later</p>
                    </div>
                `;
            } else {
                return `
                    <div class="text-center py-5">
                        <i class="fas fa-search text-secondary mb-3" style="font-size: 3rem;"></i>
                        <h4 class="fw-medium mb-2">No jobs found</h4>
                        <p class="text-secondary mb-4">Try adjusting your filters or check back later</p>
                    </div>
                `;
            }
        }

        // Function to withdraw job application
        function withdrawApplication(jobId) {
            console.log("Withdrawing application for job with ID:", jobId);
            
            // Find the job in allJobs array
            const job = findJobById(jobId, allJobs);
            if (!job) {
                console.error("Job not found with ID:", jobId);
                return;
            }
            
            // Check if application exists
            if (!job.has_applied) {
                showToast('warning', 'You have not applied for this job.');
                return;
            }
            
            // Confirm withdrawal
            if (!confirm('Are you sure you want to withdraw your application? This action cannot be undone.')) {
                return;
            }
            
            // Send withdrawal request to the server
            fetch('jobs.php?api=true', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    job_id: jobId,
                    withdraw: true
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log("Withdrawal response:", data);
                
                if (data.success) {
                    // Update job status in our data
                    job.has_applied = false;
                    
                    // Update UI
                    updateJobUI(jobId);
                    
                    // If we're in the applied jobs tab, we might need to refresh
                    const activeTab = document.querySelector('.nav-link.active');
                    if (activeTab && activeTab.id === 'appliedTab') {
                        displaySampleJobs('appliedJobs');
                    }
                    
                    showToast('success', data.message || 'Application withdrawn successfully.');
                } else {
                    showToast('error', data.message || 'Failed to withdraw application. Please try again.');
                }
            })
            .catch(error => {
                console.error("Error withdrawing application:", error);
                showToast('error', 'An error occurred. Please try again later.');
            });
        }
    </script>
    <?php endif; ?>
</body>
</html>
