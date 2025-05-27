<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

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

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    die("User not found");
}

$user = mysqli_fetch_assoc($result);

// Fetch skills
$sql = "SELECT * FROM skills WHERE user_id = ? ORDER BY skill_type";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$skills_result = mysqli_stmt_get_result($stmt);

$skills = [];
while ($row = mysqli_fetch_assoc($skills_result)) {
    $skills[$row['skill_type']][] = $row;
}

// Fetch certifications
$sql = "SELECT * FROM certifications WHERE user_id = ? ORDER BY issue_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$certifications_result = mysqli_stmt_get_result($stmt);

// Fetch projects
$sql = "SELECT * FROM projects WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$projects_result = mysqli_stmt_get_result($stmt);

// Fetch applications
$sql = "SELECT a.*, j.title as job_title, c.name as company_name 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        JOIN companies c ON j.company_id = c.id 
        WHERE a.user_id = ? 
        ORDER BY a.applied_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$applications_result = mysqli_stmt_get_result($stmt);

// Fetch documents
$sql = "SELECT * FROM documents WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$documents_result = mysqli_stmt_get_result($stmt);

// Fetch notifications
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 5";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$notifications_result = mysqli_stmt_get_result($stmt);

// Count unread notifications
$sql = "SELECT COUNT(*) as unread_count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$unread_result = mysqli_stmt_get_result($stmt);
$unread_row = mysqli_fetch_assoc($unread_result);
$unread_count = $unread_row['unread_count'];

// Fetch upcoming events (interviews, assessments)
$sql = "SELECT e.* FROM events e 
        JOIN attendance a ON e.id = a.event_id 
        WHERE a.student_id = ? AND e.event_date >= CURDATE() 
        ORDER BY e.event_date ASC LIMIT 2";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$events_result = mysqli_stmt_get_result($stmt);

// Calculate profile completion percentage
$completion_percentage = calculateProfileCompletion($user);

// Function to calculate profile completion percentage
function calculateProfileCompletion($user) {
    $total_fields = 14; // Total number of important profile fields
    $filled_fields = 0;
    
    // Check each important field
    if (!empty($user['full_name'])) $filled_fields++;
    if (!empty($user['email'])) $filled_fields++;
    if (!empty($user['personal_email'])) $filled_fields++;
    if (!empty($user['phone'])) $filled_fields++;
    if (!empty($user['dob'])) $filled_fields++;
    if (!empty($user['branch'])) $filled_fields++;
    if (!empty($user['degree'])) $filled_fields++;
    if (!empty($user['year_of_study'])) $filled_fields++;
    if (!empty($user['cgpa'])) $filled_fields++;
    if (!empty($user['profile_pic']) && $user['profile_pic'] != 'default.jpg') $filled_fields++;
    if (!empty($user['linkedin'])) $filled_fields++;
    if (!empty($user['github'])) $filled_fields++;
    if (!empty($user['preferred_roles'])) $filled_fields++;
    if (!empty($user['preferred_companies'])) $filled_fields++;
    
    return round(($filled_fields / $total_fields) * 100);
}

// Format date function
function formatDate($date) {
    return date("d M Y", strtotime($date));
}

// Get first letter of each word for company icon
function getInitials($name) {
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        $initials .= strtoupper(substr($word, 0, 1));
    }
    return substr($initials, 0, 2); // Return first two initials
}

// Get badge class based on skill type
function getSkillBadgeClass($skillType) {
    switch ($skillType) {
        case 'Programming Languages':
            return 'badge-prog';
        case 'Frameworks':
            return 'badge-framework';
        case 'Databases':
            return 'badge-db';
        case 'Soft Skills':
            return 'badge-soft';
        default:
            return 'badge-other';
    }
}

// Get badge class based on application status
function getStatusBadgeClass($status) {
    switch ($status) {
        case 'Applied':
            return 'bg-secondary';
        case 'Application Under Review':
            return 'bg-info';
        case 'Technical Assessment':
            return 'bg-primary';
        case 'Interview Scheduled':
            return 'bg-warning text-dark';
        case 'Final Interview':
            return 'bg-warning';
        case 'Offer Received':
            return 'bg-success';
        case 'Rejected':
            return 'bg-danger';
        case 'Withdrawn':
            return 'bg-secondary';
        default:
            return 'bg-secondary';
    }
}

// Process profile picture upload
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $filename = $_FILES['profile_pic']['name'];
    $filesize = $_FILES['profile_pic']['size'];
    $filetype = $_FILES['profile_pic']['type'];
    
    // Validate file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if (!in_array(strtolower($ext), $allowed)) {
        $error_msg = "Error: Please select a valid file format (JPG, JPEG, PNG, GIF).";
    } else if ($filesize > 5242880) { // 5MB max
        $error_msg = "Error: File size must be less than 5MB.";
    } else {
        // Generate unique filename
        $new_filename = uniqid('profile_') . '.' . $ext;
        $upload_dir = 'uploads/profile_pics/';
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Move the uploaded file
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $upload_dir . $new_filename)) {
            // Update user profile in database
            $sql = "UPDATE users SET profile_pic = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $new_filename, $user_id);
            
            if (mysqli_stmt_execute($stmt)) {
                $success_msg = "Profile picture updated successfully!";
                
                // Delete old profile picture if it exists and is not default
                $query = "SELECT profile_pic FROM users WHERE id = ? AND profile_pic != 'default.jpg'";
                $stmt = mysqli_prepare($conn, $query);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($row = mysqli_fetch_assoc($result)) {
                    $old_pic = $row['profile_pic'];
                    if ($old_pic != $new_filename && file_exists($upload_dir . $old_pic)) {
                        unlink($upload_dir . $old_pic);
                    }
                }
            } else {
                $error_msg = "Error updating profile: " . mysqli_error($conn);
            }
        } else {
            $error_msg = "Error uploading file.";
        }
    }
}

// We're not including header.php anymore to fix the error
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | Campus Placement Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <style>
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        
        .profile-completion {
            height: 8px;
            border-radius: 4px;
        }
        
        .nav-pills .nav-link.active {
            background-color: var(--bs-primary);
        }
        
        .badge-skill {
            font-weight: normal;
            padding: 0.5em 0.75em;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .badge-prog {
            background-color: #e6f2ff;
            color: #0d6efd;
        }
        
        .badge-framework {
            background-color: #e6fff2;
            color: #198754;
        }
        
        .badge-db {
            background-color: #f2e6ff;
            color: #6f42c1;
        }
        
        .badge-soft {
            background-color: #fff8e6;
            color: #fd7e14;
        }
        
        .badge-other {
            background-color: #f2f2f2;
            color: #6c757d;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 2px;
            background-color: #dee2e6;
        }
        
        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -2.25rem;
            top: 0;
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            background-color: var(--bs-primary);
        }
        
        .notification-item {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .notification-item.unread {
            background-color: #e6f2ff;
        }
        
        .table-applications th {
            font-size: 0.875rem;
            text-transform: uppercase;
            color: #6c757d;
        }
        
        .company-icon {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.75rem;
            margin-right: 0.75rem;
        }
        
        .document-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar - Using the exact HTML from profile.html -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                        <a href="index.html" class="text-decoration-none d-flex align-items-center">
                            <i class="fas fa-graduation-cap text-primary me-2"></i>
                            <span class="fs-5 fw-semibold text-dark">SRM University</span>
                        </a>
                        <button type="button" class="btn-close d-md-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <ul class="nav flex-column mt-3">
                        <li class="nav-item" class="item">
                            <a class="nav-link" href="home.php">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item"class="item">
                            <a class="nav-link" href="jobs.php">
                                <i class="fas fa-briefcase me-2"></i>
                                Jobs
                            </a>
                        </li>
                        <li class="nav-item" class="item">
                            <a class="nav-link" href="applications.php">
                                <i class="fas fa-file-alt me-2"></i>
                                Applications
                            </a>
                        </li>
                        <li class="nav-item" class="item">
                            <a class="nav-link active" href="profile.php">
                                <i class="fas fa-user me-2"></i>
                                Profile
                            </a>
                        </li>
                        <li class="nav-item" class="item">
                            <a class="nav-link" href="resume.php">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Resume Upload                            
                            </a>
                        </li>
                        <li class="nav-item" class="item">
                            <a class="nav-link" href="schedule.php">
                                <i class="fa-solid fa-calendar-days"></i>
                                Attendance Details
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="my-3">
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="index.html">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <header class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
                    <button class="btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <a class="btn btn-light position-relative" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <?php if ($unread_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $unread_count; ?>
                                </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                
                                <?php while ($notification = mysqli_fetch_assoc($notifications_result)): ?>
                                <li>
                                    <a class="dropdown-item notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>" 
                                       href="notification.php?id=<?php echo $notification['id']; ?>">
                                        <div class="d-flex justify-content-between">
                                            <strong><?php echo htmlspecialchars($notification['title']); ?></strong>
                                            <small class="text-muted">
                                                <?php 
                                                $date = new DateTime($notification['created_at']);
                                                $now = new DateTime();
                                                $interval = $date->diff($now);
                                                
                                                if ($interval->d == 0) {
                                                    echo "Today";
                                                } elseif ($interval->d == 1) {
                                                    echo "Yesterday";
                                                } else {
                                                    echo $interval->d . " days ago";
                                                }
                                                ?>
                                            </small>
                                        </div>
                                        <p class="mb-0 small"><?php echo htmlspecialchars($notification['message']); ?></p>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php endwhile; ?>
                                
                                
                            </ul>
                        </div>
                        
                        <!-- User Profile -->
                        <div class="dropdown">
                            <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic'] ?: 'default.jpg'); ?>" class="rounded-circle" height="32" alt="Student" loading="lazy" />
                                <span class="ms-2 d-none d-md-inline" style="font-size:20px; text-decoration: none;">
                                    <?php echo htmlspecialchars($user['full_name']); ?>
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </header>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <img src="uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic'] ?: 'default.jpg'); ?>" 
                                             class="rounded-circle img-fluid" style="width: 150px; height: 150px; object-fit: cover;" 
                                             alt="Profile Picture">
                                    </div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($user['full_name']); ?></h5>
                                    <p class="text-muted"><?php echo htmlspecialchars($user['degree']); ?> in <?php echo htmlspecialchars($user['branch']); ?></p>
                                    
                                    <form method="post" enctype="multipart/form-data" class="mt-3">
                                        <div class="mb-3">
                                            <label for="profile_pic" class="form-label">Update Profile Picture</label>
                                            <input class="form-control" type="file" id="profile_pic" name="profile_pic">
                                            <div class="form-text">Supported formats: JPG, JPEG, PNG, GIF (Max size: 5MB)</div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Upload Picture</button>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Quick Links</h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="list-group list-group-flush">
                                        <?php 
                                        // Get the resume URL from either user table or students table
                                        $resume_url = "";
                                        
                                        // Check user table for resume field
                                        if (!empty($user['resume'])) {
                                            $resume_url = $user['resume'];
                                        }
                                        
                                        // Check if there's a student record with resume_url
                                        $student_query = "SELECT resume_url FROM students WHERE user_id = ?";
                                        $stmt = mysqli_prepare($conn, $student_query);
                                        mysqli_stmt_bind_param($stmt, "i", $user_id);
                                        mysqli_stmt_execute($stmt);
                                        $student_result = mysqli_stmt_get_result($stmt);
                                        
                                        if ($student = mysqli_fetch_assoc($student_result)) {
                                            if (!empty($student['resume_url'])) {
                                                $resume_url = $student['resume_url'];
                                            }
                                        }
                                        
                                        if (!empty($resume_url)) {
                                        ?>
                                        <a href="view_document.php?file=<?php echo urlencode($resume_url); ?>" class="list-group-item list-group-item-action" target="_blank">
                                            <i class="fas fa-file-alt me-2 text-primary"></i> View Resume
                                        </a>
                                        <?php } else { ?>
                                        <a href="resume.php" class="list-group-item list-group-item-action">
                                            <i class="fas fa-file-alt me-2 text-primary"></i> Upload Resume
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <ul class="nav nav-pills mb-4" id="profileTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab" aria-controls="basic" aria-selected="true">Basic Info</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="academic-tab" data-bs-toggle="tab" data-bs-target="#academic" type="button" role="tab" aria-controls="academic" aria-selected="false">Academic</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="false">Skills</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="placement-tab" data-bs-toggle="tab" data-bs-target="#placement" type="button" role="tab" aria-controls="placement" aria-selected="false">Placement</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="profileTabsContent">
                                <div class="tab-pane fade show active" id="basic" role="tabpanel" aria-labelledby="basic-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Basic Information</h5>
                                            <p class="card-subtitle text-muted small">Your personal and contact details</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Full Name</label>
                                                    <p><?php echo htmlspecialchars($user['full_name']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Student ID / Registration Number</label>
                                                    <p><?php echo htmlspecialchars($user['reg_number']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">College Email</label>
                                                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Personal Email</label>
                                                    <p><?php echo htmlspecialchars($user['personal_email']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Phone Number</label>
                                                    <p>+91 <?php echo htmlspecialchars($user['phone']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Date of Birth</label>
                                                    <p><?php echo date('d M Y', strtotime($user['dob'])); ?></p>
                                                </div>
                                            </div>

                                            <div class="pt-4 border-top mt-4">
                                                <h6 class="text-muted small mb-3">Social Profiles</h6>
                                                <div class="row g-4">
                                                    <?php if (!empty($user['linkedin'])): ?>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fab fa-linkedin text-primary me-3 fs-5"></i>
                                                            <div>
                                                                <label class="form-label small fw-medium mb-0">LinkedIn</label>
                                                                <p class="small text-primary mb-0"><?php echo htmlspecialchars($user['linkedin']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($user['portfolio'])): ?>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fas fa-globe me-3 fs-5"></i>
                                                            <div>
                                                                <label class="form-label small fw-medium mb-0">Portfolio</label>
                                                                <p class="small text-primary mb-0"><?php echo htmlspecialchars($user['portfolio']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($user['github'])): ?>
                                                    <div class="col-md-6">
                                                        <div class="d-flex align-items-center">
                                                            <i class="fab fa-github me-3 fs-5"></i>
                                                            <div>
                                                                <label class="form-label small fw-medium mb-0">GitHub</label>
                                                                <p class="small text-primary mb-0"><?php echo htmlspecialchars($user['github']); ?></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="academic" role="tabpanel" aria-labelledby="academic-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Academic Details</h5>
                                            <p class="card-subtitle text-muted small">Your educational background and achievements</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Degree & Branch</label>
                                                    <p><?php echo htmlspecialchars($user['degree']); ?> in <?php echo htmlspecialchars($user['branch']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Year of Study</label>
                                                    <p><?php echo htmlspecialchars($user['year_of_study']); ?>rd Year</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">CGPA</label>
                                                    <p><?php echo htmlspecialchars($user['cgpa']); ?> / 10</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Backlogs</label>
                                                    <p><?php echo $user['backlogs'] == 0 ? 'None' : $user['backlogs']; ?></p>
                                                </div>
                                            </div>

                                            <div class="pt-4 border-top mt-4">
                                                <h6 class="text-muted small mb-3">Certifications</h6>
                                                <?php if (mysqli_num_rows($certifications_result) > 0): ?>
                                                    <?php while ($certification = mysqli_fetch_assoc($certifications_result)): ?>
                                                    <div class="mb-3">
                                                        <div class="bg-light p-3 rounded">
                                                            <div class="d-flex justify-content-between">
                                                                <h6 class="fw-medium"><?php echo htmlspecialchars($certification['name']); ?></h6>
                                                                <span class="badge bg-success">Verified</span>
                                                            </div>
                                                            <p class="small text-muted mt-1">
                                                                <?php echo htmlspecialchars($certification['issuer']); ?> • 
                                                                Issued <?php echo date('M Y', strtotime($certification['issue_date'])); ?>
                                                                <?php if (!empty($certification['expiry_date'])): ?>
                                                                • Expires <?php echo date('M Y', strtotime($certification['expiry_date'])); ?>
                                                                <?php endif; ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <p class="text-muted">No certifications added yet.</p>
                                                <?php endif; ?>
                                            </div>

                                            <div class="pt-4 border-top mt-4">
                                                <h6 class="text-muted small mb-3">Projects & Research Work</h6>
                                                <?php if (mysqli_num_rows($projects_result) > 0): ?>
                                                    <?php while ($project = mysqli_fetch_assoc($projects_result)): ?>
                                                    <div class="mb-3">
                                                        <div class="bg-light p-3 rounded">
                                                            <h6 class="fw-medium"><?php echo htmlspecialchars($project['title']); ?></h6>
                                                            <p class="small text-muted mt-1">
                                                                <?php echo htmlspecialchars($project['description']); ?>
                                                            </p>
                                                            <?php if (!empty($project['technologies'])): ?>
                                                            <div class="mt-2">
                                                                <?php 
                                                                $technologies = explode(',', $project['technologies']);
                                                                foreach ($technologies as $tech): 
                                                                ?>
                                                                <span class="badge bg-secondary me-1"><?php echo htmlspecialchars(trim($tech)); ?></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endwhile; ?>
                                                <?php else: ?>
                                                    <p class="text-muted">No projects added yet.</p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Skills & Competencies</h5>
                                            <p class="card-subtitle text-muted small">Your technical and soft skills</p>
                                        </div>
                                        <div class="card-body">
                                            <?php if (!empty($skills)): ?>
                                                <?php foreach ($skills as $skillType => $skillList): ?>
                                                <div class="mb-4">
                                                    <h6 class="text-muted small mb-3"><?php echo htmlspecialchars($skillType); ?></h6>
                                                    <div>
                                                        <?php foreach ($skillList as $skill): ?>
                                                        <span class="badge badge-skill <?php echo getSkillBadgeClass($skillType); ?>">
                                                            <?php echo htmlspecialchars($skill['name']); ?>
                                                        </span>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <p class="text-muted">No skills added yet.</p>
                                                <a href="add_skill.php" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-plus me-1"></i> Add Skills
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Placement Tab -->
                                <div class="tab-pane fade" id="placement" role="tabpanel" aria-labelledby="placement-tab">
                                    <!-- Placement & Job Preferences -->
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Placement & Job Preferences</h5>
                                            <p class="card-subtitle text-muted small">Your career preferences and expectations</p>
                                        </div>
                                        <div class="card-body">
                                            <div class="row g-4">
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Preferred Job Roles</label>
                                                    <div class="mt-2">
                                                        <?php 
                                                        if (!empty($user['preferred_roles'])) {
                                                            $roles = explode(',', $user['preferred_roles']);
                                                            foreach ($roles as $role) {
                                                                echo '<span class="badge bg-primary me-1">' . htmlspecialchars(trim($role)) . '</span>';
                                                            }
                                                        } else {
                                                            echo '<p class="text-muted">Not specified</p>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Preferred Companies</label>
                                                    <div class="mt-2">
                                                        <?php 
                                                        if (!empty($user['preferred_companies'])) {
                                                            $companies = explode(',', $user['preferred_companies']);
                                                            foreach ($companies as $company) {
                                                                echo '<span class="badge bg-light text-dark me-1">' . htmlspecialchars(trim($company)) . '</span>';
                                                            }
                                                        } else {
                                                            echo '<p class="text-muted">Not specified</p>';
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Location Preferences</label>
                                                    <div class="d-flex align-items-center mt-2">
                                                        <i class="fas fa-map-marker-alt me-2 text-muted"></i>
                                                        <span>
                                                            <?php echo !empty($user['location_preference']) ? 
                                                                htmlspecialchars($user['location_preference']) : 'Not specified'; ?>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label text-muted small">Expected Salary Range</label>
                                                    <p>
                                                        <?php echo !empty($user['expected_salary']) ? 
                                                            htmlspecialchars($user['expected_salary']) : 'Not specified'; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Application & Status Tracking -->
                                    <div class="card mt-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Application & Status Tracking</h5>
                                            <p class="card-subtitle text-muted small">Track your job applications and interview status</p>
                                        </div>
                                        <div class="card-body">
                                            <?php if (mysqli_num_rows($applications_result) > 0): ?>
                                            <div class="table-responsive">
                                                <table class="table table-applications">
                                                    <thead>
                                                        <tr>
                                                            <th>Company</th>
                                                            <th>Role</th>
                                                            <th>Applied On</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php while ($application = mysqli_fetch_assoc($applications_result)): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="company-icon">
                                                                        <?php echo getInitials($application['company_name']); ?>
                                                                    </div>
                                                                    <div><?php echo htmlspecialchars($application['company_name']); ?></div>
                                                                </div>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($application['job_title']); ?></td>
                                                            <td><?php echo date('M j, Y', strtotime($application['applied_date'])); ?></td>
                                                            <td>
                                                                <span class="badge <?php echo getStatusBadgeClass($application['status']); ?>">
                                                                    <?php echo htmlspecialchars($application['status']); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <?php endwhile; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="text-end mt-3">
                                                <a href="applications.php" class="btn btn-primary">View All Applications</a>
                                            </div>
                                            <?php else: ?>
                                                <p class="text-muted">You haven't applied to any jobs yet.</p>
                                                <a href="jobs.php" class="btn btn-primary">Browse Jobs</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-3 mt-4 border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">© <?php echo date('Y'); ?> Campus Placement Portal. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="js/profile.js"></script>
</body>
</html>

<?php
// Close database connection
mysqli_close($conn);
?>
