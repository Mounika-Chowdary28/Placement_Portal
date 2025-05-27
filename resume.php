<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "placement_portal";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get user information
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Get notifications
$notifications_query = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 5";
$notifications_result = mysqli_query($conn, $notifications_query);

// Get unread notifications count
$unread_query = "SELECT COUNT(*) as count FROM notifications WHERE user_id = $user_id AND is_read = 0";
$unread_result = mysqli_query($conn, $unread_query);
$notif_count = mysqli_fetch_assoc($unread_result)['count'];

// Check if student record exists
$student_query = "SELECT * FROM students WHERE user_id = $user_id";
$student_result = mysqli_query($conn, $student_query);
$student_exists = mysqli_num_rows($student_result) > 0;
$student = $student_exists ? mysqli_fetch_assoc($student_result) : null;

// Handle resume upload
$upload_message = "";
$upload_error = "";
$resume_url = "";

if (isset($_POST['upload_resume'])) {
    // Check if file was uploaded without errors
    if (isset($_FILES['resume_file']) && $_FILES['resume_file']['error'] == 0) {
        $allowed = array('pdf', 'doc', 'docx');
        $filename = $_FILES['resume_file']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Verify file extension
        if (in_array(strtolower($filetype), $allowed)) {
            // Check filesize (5MB max)
            if ($_FILES['resume_file']['size'] < 5000000) {
                // Create uploads directory if it doesn't exist
                if (!file_exists('../uploads/resumes')) {
                    mkdir('../uploads/resumes', 0777, true);
                }
                
                // Create unique filename
                $new_filename = $user['reg_number'] . '_resume_' . time() . '.' . $filetype;
                $upload_path = '../uploads/resumes/' . $new_filename;
                
                // Move the file
                if (move_uploaded_file($_FILES['resume_file']['tmp_name'], $upload_path)) {
                    $resume_url = 'uploads/resumes/' . $new_filename;
                    
                    // Update students table if it exists
                    if ($student_exists) {
                        $update_student = "UPDATE students SET resume_url = '$resume_url' WHERE user_id = $user_id";
                        mysqli_query($conn, $update_student);
                    }
                    
                    $upload_message = "Resume uploaded successfully!";
                    
                    // Refresh user data
                    $result = mysqli_query($conn, $query);
                    $user = mysqli_fetch_assoc($result);
                    
                    if ($student_exists) {
                        $student_result = mysqli_query($conn, $student_query);
                        $student = mysqli_fetch_assoc($student_result);
                    }
                } else {
                    $upload_error = "Error uploading file. Please try again.";
                }
            } else {
                $upload_error = "File is too large. Maximum size is 5MB.";
            }
        } else {
            $upload_error = "Invalid file type. Only PDF, DOC, and DOCX files are allowed.";
        }
    } else {
        $upload_error = "Please select a file to upload.";
    }
}

// Get current resume URL
$current_resume = $user['resume'] ?? ($student['resume_url'] ?? "");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Upload - Campus Placement Portal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .notification-dropdown {
            width: 320px;
            padding: 0;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        .notification-item.unread {
            background-color: #f0f8ff;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Home
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="jobs.php">
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
                            <a class="nav-link active" href="resume.php">
                                <i class="fas fa-file-pdf me-2"></i>
                                Resume Upload                            
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="schedule.php">
                                <i class="fa-solid fa-calendar-days me-2"></i>
                                Attendance Details
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="my-3">
                    
                    <ul class="nav flex-column">
                        
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
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <header class="d-flex justify-content-between align-items-center py-3 mb-4 border-bottom">
                    <button class="btn d-md-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <div class="d-flex align-items-center gap-3">
                        <div class="dropdown">
                            <a class="btn btn-light position-relative" href="#" role="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <?php if ($notif_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?php echo $notif_count; ?>
                                </span>
                                <?php endif; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                                <li>
                                    <div class="d-flex justify-content-between align-items-center px-3 py-2">
                                        <h6 class="dropdown-header p-0 m-0">Notifications</h6>
                                        <?php if ($notif_count > 0): ?>
                                        <a href="notifications.php?mark_all_read=1" class="text-decoration-none small mark-all-read">Mark all read</a>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php 
                                mysqli_data_seek($notifications_result, 0);
                                $has_notifications = false;
                                $counter = 0;
                                while ($notification = mysqli_fetch_assoc($notifications_result)):
                                    if ($counter < 3): // Show only 3 notifications in dropdown
                                        $has_notifications = true;
                                ?>
                                <li>
                                    <a class="dropdown-item notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>" href="javascript:void(0);" data-id="<?php echo $notification['id']; ?>">
                                        <div class="d-flex justify-content-between">
                                            <strong><?php echo htmlspecialchars($notification['title']); ?></strong>
                                            <small class="text-muted"><?php echo date('j M', strtotime($notification['created_at'])); ?></small>
                                        </div>
                                        <p class="mb-0 small"><?php echo htmlspecialchars($notification['message']); ?></p>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php 
                                    endif;
                                    $counter++;
                                endwhile; 
                                ?>
                                <?php if (!$has_notifications): ?>
                                <li>
                                    <div class="text-center py-3">
                                        <i class="fas fa-bell-slash text-muted fs-5 mb-2"></i>
                                        <p class="mb-0 small">No notifications</p>
                                    </div>
                                </li>
                                <?php endif; ?>
                                
                            </ul>
                        </div>
                        
                        <!-- User Profile -->
                        <div class="dropdown">
                            <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic'] ?: 'default.jpg'); ?>" class="rounded-circle" height="32" alt="Student" loading="lazy" />
                                <span class="ms-2 d-none d-md-inline" style="font-size:20px; text-decoration: none;"><?php echo $user['full_name']; ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </header>

                <!-- Resume Upload Content -->
                <div class="container-fluid">
                    <h1 class="h2 mb-4">Resume Upload</h1>
                    <p class="text-muted mb-4">Upload your resume to apply for job opportunities</p>
                    
                    <?php if (!empty($upload_message)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo $upload_message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($upload_error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo $upload_error; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Resume Upload Section -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Upload Your Resume</h5>
                                </div>
                                <div class="card-body">
                                    <form id="resumeUploadForm" method="post" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="resumeFile" class="form-label">Select your resume file (PDF, DOCX)</label>
                                            <input class="form-control" type="file" id="resumeFile" name="resume_file" accept=".pdf,.docx,.doc">
                                            <div class="form-text">Maximum file size: 5MB</div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" name="upload_resume" class="btn btn-primary">
                                                <i class="fas fa-upload me-2"></i>Upload Resume
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <!-- Current Resume Section -->
                            <?php if (!empty($current_resume)): ?>
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Current Resume</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-pdf fs-1 text-danger me-3"></i>
                                        <div>
                                            <h6 class="mb-1">Your Resume</h6>
                                            <p class="text-muted mb-2 small">Uploaded on: <?php echo date("F j, Y", filemtime("../" . $current_resume)); ?></p>
                                            <div class="btn-group">
                                                <a href="view_document.php?file=<?php echo urlencode($current_resume); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-eye me-1"></i> View
                            </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="col-lg-4">
                            <!-- Tips and Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Resume Tips</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Keep your resume concise and relevant (1-2 pages)
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Include both technical and soft skills
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Use industry-standard terminology for skills
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Highlight relevant projects and experience
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Quantify your achievements where possible
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Proofread carefully for errors
                                        </li>
                                        <li class="list-group-item">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            Save your resume as a PDF to preserve formatting
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Resume Resources -->
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Resume Resources</h5>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">Resume Templates</h6>
                                                    <p class="mb-0 small text-muted">Professional templates for various roles</p>
                                                </div>
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">Resume Workshop Recording</h6>
                                                    <p class="mb-0 small text-muted">Watch our recent resume building workshop</p>
                                                </div>
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-1">Book a Resume Review</h6>
                                                    <p class="mb-0 small text-muted">Get personalized feedback from our career advisors</p>
                                                </div>
                                                <i class="fas fa-chevron-right"></i>
                                            </div>
                                        </a>
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
            <p class="mb-0 text-muted">© 2023 Campus Placement Portal. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle notification clicks - mark as read
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    
                    if (notificationId) {
                        // Send AJAX request to mark notification as read
                        fetch(`notifications.php?id=${notificationId}`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove unread style
                                this.classList.remove('unread');
                                
                                // Update badge count
                                let badge = document.querySelector('.badge.rounded-pill.bg-danger');
                                if (badge) {
                                    let count = parseInt(badge.textContent);
                                    count -= 1;
                                    
                                    if (count <= 0) {
                                        badge.remove();
                                    } else {
                                        badge.textContent = count;
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error marking notification as read:', error);
                        });
                    }
                });
            });
            
            // Handle mark all as read in dropdown
            const markAllReadLink = document.querySelector('.mark-all-read');
            if (markAllReadLink) {
                markAllReadLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Send AJAX request to mark all notifications as read
                    fetch('notifications.php?mark_all_read=1', {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove unread style from all notifications
                            document.querySelectorAll('.notification-item.unread').forEach(item => {
                                item.classList.remove('unread');
                            });
                            
                            // Remove notification badge
                            const badge = document.querySelector('.badge.rounded-pill.bg-danger');
                            if (badge) {
                                badge.remove();
                            }
                            
                            // Close dropdown
                            const dropdown = document.getElementById('notificationDropdown');
                            const bsDropdown = bootstrap.Dropdown.getInstance(dropdown);
                            if (bsDropdown) {
                                bsDropdown.hide();
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error marking all notifications as read:', error);
                    });
                });
            }
        });
    </script>
</body>
</html>
