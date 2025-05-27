<?php
require_once 'config.php';
require_login();

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

// Get application details if ID is provided
$application = null;
if (isset($_GET['id'])) {
    $application_id = (int)$_GET['id'];
    $sql = "SELECT a.*, j.title, j.description, j.location, j.job_type, j.salary_range, j.skills, 
                  c.name as company_name 
           FROM applications a 
           JOIN jobs j ON a.job_id = j.id 
           JOIN companies c ON j.company_id = c.id 
           WHERE a.id = $application_id AND a.user_id = $user_id";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) > 0) {
        $application = mysqli_fetch_assoc($result);
    }
}

// Get all active applications
$sql = "SELECT a.*, j.title, j.description, c.name as company_name 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        JOIN companies c ON j.company_id = c.id 
        WHERE a.user_id = $user_id AND a.status NOT IN ('Rejected', 'Withdrawn', 'Offer Accepted', 'Offer Declined') 
        ORDER BY a.applied_date DESC";
$active_applications = mysqli_query($conn, $sql);

// Get upcoming interviews
$sql = "SELECT a.*, j.title, c.name as company_name 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        JOIN companies c ON j.company_id = c.id 
        WHERE a.user_id = $user_id AND a.status = 'Interview Scheduled' 
        ORDER BY a.updated_at ASC";
$interviews = mysqli_query($conn, $sql);

// Get completed applications
$sql = "SELECT a.*, j.title, j.description, c.name as company_name 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        JOIN companies c ON j.company_id = c.id 
        WHERE a.user_id = $user_id AND a.status IN ('Rejected', 'Offer Received', 'Withdrawn', 'Offer Accepted', 'Offer Declined') 
        ORDER BY a.updated_at DESC";
$completed_applications = mysqli_query($conn, $sql);

// Get application statistics
$sql = "SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'Applied' OR status = 'Application Under Review' THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN status = 'Offer Received' OR status = 'Offer Accepted' THEN 1 ELSE 0 END) as offers,
            SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) as rejections
        FROM applications 
        WHERE user_id = $user_id";
$stats_result = mysqli_query($conn, $sql);
$stats = mysqli_fetch_assoc($stats_result);

// Get unread notifications count
$sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = $user_id AND is_read = 0";
$notif_result = mysqli_query($conn, $sql);
$notif_count = mysqli_fetch_assoc($notif_result)['count'];

// Get recent notifications
$sql = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3";
$notifications_result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications - Campus Placement Portal</title>
    
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
                        <a href="index.php" class="text-decoration-none d-flex align-items-center">
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
                        <li class="nav-item" class="item">
                            <a class="nav-link" href="jobs.php">
                                <i class="fas fa-briefcase me-2"></i>
                                Jobs
                            </a>
                        </li>
                        <li class="nav-item" class="item">
                            <a class="nav-link active" href="applications.php">
                                <i class="fas fa-file-alt me-2"></i>
                                Applications
                            </a>
                        </li>
                        <li class="nav-item" class="item">
                            <a class="nav-link" href="profile.php">
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
                                <img src="uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic']); ?>" class="rounded-circle" height="32" alt="Student" loading="lazy" />
                                <span class="ms-2 d-none d-md-inline" style="font-size:20px; text-decoration: none;"><?php echo htmlspecialchars($user['full_name']); ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </header>

                <!-- Applications Content -->
                <div class="container-fluid">
                    <h1 class="h2 mb-4">My Applications</h1>
                    <p class="text-muted mb-4">Track and manage your job applications</p>
                    
                    <!-- Applications Tabs -->
                    <ul class="nav nav-tabs mb-4" id="applicationTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active" type="button" role="tab" aria-controls="active" aria-selected="true">Active Applications (<?php echo mysqli_num_rows($active_applications); ?>)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="interviews-tab" data-bs-toggle="tab" data-bs-target="#interviews" type="button" role="tab" aria-controls="interviews" aria-selected="false">Upcoming Interviews (<?php echo mysqli_num_rows($interviews); ?>)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed (<?php echo mysqli_num_rows($completed_applications); ?>)</button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="applicationTabsContent">
                        <!-- Active Applications Tab -->
                        <div class="tab-pane fade show active" id="active" role="tabpanel" aria-labelledby="active-tab">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">Active Applications</h5>
                                        <p class="card-text small text-muted mb-0">Applications you've submitted that are still in progress</p>
                                    </div>
                                    <div class="form-group has-search">
                                        <span class="fa fa-search form-control-feedback"></span>
                                            <input type="text" class="form-control" placeholder="Search applications...">
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Company</th>
                                                <th>Position</th>
                                                <th>Applied Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($active_applications) > 0) {
                                                mysqli_data_seek($active_applications, 0);
                                                while ($app = mysqli_fetch_assoc($active_applications)) {
                                                    echo '<tr>';
                                                    echo '<td>';
                                                    echo '<div class="d-flex align-items-center">';
                                                    echo '<div class="bg-light rounded-circle p-2 me-3">';
                                                    echo '<i class="fas fa-building text-primary"></i>';
                                                    echo '</div>';
                                                    echo '<span>' . htmlspecialchars($app['company_name']) . '</span>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                    echo '<td>' . htmlspecialchars($app['title']) . '</td>';
                                                    echo '<td>' . date('M j, Y', strtotime($app['applied_date'])) . '</td>';
                                                    
                                                    // Set badge color based on status
                                                    $badge_class = 'bg-primary';
                                                    if ($app['status'] == 'Technical Assessment') {
                                                        $badge_class = 'bg-warning text-dark';
                                                    } elseif ($app['status'] == 'Interview Scheduled') {
                                                        $badge_class = 'bg-info text-dark';
                                                    }
                                                    
                                                    echo '<td><span class="badge ' . $badge_class . '">' . htmlspecialchars($app['status']) . '</span></td>';
                                                    echo '<td>';
                                                    echo '<button class="btn btn-sm btn-outline-primary view-details" data-bs-toggle="modal" data-bs-target="#jobDetailsModal" data-id="' . $app['id'] . '" data-description="' . htmlspecialchars($app['description']) . '">View Details</button>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center py-4">No active applications</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Upcoming Interviews Tab -->
                        <div class="tab-pane fade" id="interviews" role="tabpanel" aria-labelledby="interviews-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Upcoming Interviews</h5>
                                    <p class="card-text small text-muted mb-0">Your scheduled interviews and assessments</p>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <?php
                                        if (mysqli_num_rows($interviews) > 0) {
                                            mysqli_data_seek($interviews, 0);
                                            while ($interview = mysqli_fetch_assoc($interviews)) {
                                                echo '<div class="list-group-item border rounded p-3 mb-2">';
                                                echo '<div class="d-flex justify-content-between align-items-center">';
                                                echo '<div>';
                                                echo '<h6 class="mb-1">' . htmlspecialchars($interview['company_name']) . ' - First Round Interview</h6>';
                                                echo '<p class="small text-muted mb-0">Tomorrow, 10:00 AM - Online (Google Meet)</p>';
                                                echo '<div class="mt-2">';
                                                echo '<span class="badge bg-primary-subtle text-primary">Technical</span>';
                                                echo '<span class="badge bg-primary-subtle text-primary">45 minutes</span>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '<div class="d-flex gap-2">';
                                                echo '<a href="prepare_interview.php?id=' . $interview['id'] . '" class="btn btn-outline-primary">Prepare</a>';
                                                echo '<a href="join_interview.php?id=' . $interview['id'] . '" class="btn btn-primary">Join Meeting</a>';
                                                echo '</div>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        } else {
                                            echo '<div class="text-center py-4">';
                                            echo '<i class="fas fa-calendar-check text-muted fs-1 mb-3"></i>';
                                            echo '<p class="mb-0">No upcoming interviews scheduled</p>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Completed Applications Tab -->
                        <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Completed Applications</h5>
                                    <p class="card-text small text-muted mb-0">Your past applications and their outcomes</p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Company</th>
                                                <th>Position</th>
                                                <th>Applied Date</th>
                                                <th>Result</th>
                                                <th>Feedback</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($completed_applications) > 0) {
                                                mysqli_data_seek($completed_applications, 0);
                                                while ($app = mysqli_fetch_assoc($completed_applications)) {
                                                    echo '<tr>';
                                                    echo '<td>';
                                                    echo '<div class="d-flex align-items-center">';
                                                    echo '<div class="bg-light rounded-circle p-2 me-3">';
                                                    echo '<i class="fas fa-building text-secondary"></i>';
                                                    echo '</div>';
                                                    echo '<span>' . htmlspecialchars($app['company_name']) . '</span>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                    echo '<td>' . htmlspecialchars($app['title']) . '</td>';
                                                    echo '<td>' . date('M j, Y', strtotime($app['applied_date'])) . '</td>';
                                                    
                                                    // Set badge color based on status
                                                    $badge_class = 'bg-secondary';
                                                    if ($app['status'] == 'Offer Received' || $app['status'] == 'Offer Accepted') {
                                                        $badge_class = 'bg-success';
                                                    } elseif ($app['status'] == 'Rejected') {
                                                        $badge_class = 'bg-danger';
                                                    } elseif ($app['status'] == 'Withdrawn') {
                                                        $badge_class = 'bg-secondary';
                                                    }
                                                    
                                                    echo '<td><span class="badge ' . $badge_class . '">' . htmlspecialchars($app['status']) . '</span></td>';
                                                    
                                                    // Sample feedback based on status
                                                    $feedback = '';
                                                    if ($app['status'] == 'Offer Received' || $app['status'] == 'Offer Accepted') {
                                                        $feedback = 'Excellent technical skills and cultural fit';
                                                    } elseif ($app['status'] == 'Rejected') {
                                                        $feedback = 'Need more experience with cloud technologies';
                                                    } elseif ($app['status'] == 'Withdrawn') {
                                                        $feedback = 'Withdrew to pursue other opportunities';
                                                    }
                                                    
                                                    echo '<td>' . $feedback . '</td>';
                                                    echo '<td>';
                                                    echo '<button class="btn btn-sm btn-outline-primary view-details" data-bs-toggle="modal" data-bs-target="#jobDetailsModal" data-id="' . $app['id'] . '" data-description="' . htmlspecialchars($app['description']) . '">View Details</button>';
                                                    echo '</td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="6" class="text-center py-4">No completed applications</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Application Statistics -->
                    <div class="row g-4 mt-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-file-alt text-primary"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="card-subtitle text-muted small">Total Applications</h6>
                                            <h2 class="card-title h4 mb-0"><?php echo $stats['total']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-clock text-info"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="card-subtitle text-muted small">Pending Response</h6>
                                            <h2 class="card-title h4 mb-0"><?php echo $stats['pending']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-check-circle text-success"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="card-subtitle text-muted small">Offers Received</h6>
                                            <h2 class="card-title h4 mb-0"><?php echo $stats['offers']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-danger bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-times-circle text-danger"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="card-subtitle text-muted small">Rejections</h6>
                                            <h2 class="card-title h4 mb-0"><?php echo $stats['rejections']; ?></h2>
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

    <!-- Job Details Modal -->
    <div class="modal fade" id="jobDetailsModal" tabindex="-1" aria-labelledby="jobDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="jobDetailsModalLabel">Job Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="jobDetailsContent">
                        <!-- Job details will be populated here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Details Modal -->
    <div class="modal fade" id="applicationDetailsModal" tabindex="-1" aria-labelledby="applicationDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="applicationDetailsModalLabel">Application Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if ($application): ?>
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-building fs-3 text-secondary"></i>
                        </div>
                        <div>
                            <h4 class="job-title mb-1"><?php echo htmlspecialchars($application['title']); ?></h4>
                            <p class="company-name text-secondary mb-0"><?php echo htmlspecialchars($application['company_name']); ?></p>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-calendar me-2 text-primary"></i>
                                <span>Applied on: <?php echo date('M j, Y', strtotime($application['applied_date'])); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-location-dot me-2 text-primary"></i>
                                <span><?php echo htmlspecialchars($application['location']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-briefcase me-2 text-primary"></i>
                                <span><?php echo htmlspecialchars($application['job_type']); ?></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fa-solid fa-indian-rupee-sign me-2 text-primary"></i>
                                <span><?php echo htmlspecialchars($application['salary_range']); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Application Status</h5>
                        <div class="d-flex align-items-center">
                            <?php
                            $badge_class = 'bg-primary';
                            if ($application['status'] == 'Rejected') {
                                $badge_class = 'bg-danger';
                            } elseif ($application['status'] == 'Offer Received' || $application['status'] == 'Offer Accepted') {
                                $badge_class = 'bg-success';
                            } elseif ($application['status'] == 'Application Under Review') {
                                $badge_class = 'bg-info text-dark';
                            } elseif ($application['status'] == 'Technical Assessment' || $application['status'] == 'Application Incomplete') {
                                $badge_class = 'bg-warning text-dark';
                            } elseif ($application['status'] == 'Withdrawn') {
                                $badge_class = 'bg-secondary';
                            }
                            ?>
                            <span class="badge <?php echo $badge_class; ?> me-2"><?php echo htmlspecialchars($application['status']); ?></span>
                            <span>
                                <?php
                                if ($application['status'] == 'Interview Scheduled') {
                                    echo 'First Round Interview (Tomorrow, 10:00 AM)';
                                } elseif ($application['status'] == 'Technical Assessment') {
                                    echo 'Complete Online Assessment (Due: May 15)';
                                }
                                ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Application Timeline</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">Application Submitted</h6>
                                        <p class="small text-muted mb-0">You submitted your application with resume v2.0</p>
                                    </div>
                                    <span class="text-muted"><?php echo date('M j, Y', strtotime($application['applied_date'])); ?></span>
                                </div>
                            </li>
                            <?php if ($application['status'] != 'Applied'): ?>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">Application Reviewed</h6>
                                        <p class="small text-muted mb-0">Your application was reviewed by the hiring team</p>
                                    </div>
                                    <span class="text-muted"><?php echo date('M j, Y', strtotime($application['updated_at'])); ?></span>
                                </div>
                            </li>
                            <?php endif; ?>
                            <?php if ($application['status'] == 'Interview Scheduled'): ?>
                            <li class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">Interview Scheduled</h6>
                                        <p class="small text-muted mb-0">First round technical interview scheduled</p>
                                    </div>
                                    <span class="text-muted"><?php echo date('M j, Y', strtotime($application['updated_at'])); ?></span>
                                </div>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    
                    <div class="mb-4">
                        <h5>Documents Submitted</h5>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center p-2 border rounded">
                                <i class="fas fa-file-pdf text-danger me-3"></i>
                                <span><?php echo htmlspecialchars($application['resume']); ?></span>
                                <a href="uploads/resumes/<?php echo htmlspecialchars($application['resume']); ?>" class="btn btn-sm btn-link ms-auto">View</a>
                            </div>
                            <div class="d-flex align-items-center p-2 border rounded">
                                <i class="fas fa-file-alt text-primary me-3"></i>
                                <span>Cover Letter</span>
                                <a href="#" class="btn btn-sm btn-link ms-auto">View</a>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($application['status'] == 'Interview Scheduled'): ?>
                    <div class="mb-4">
                        <h5>Interview Details</h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6>First Round Technical Interview</h6>
                                <p class="mb-2"><strong>Date & Time:</strong> Tomorrow, 10:00 AM</p>
                                <p class="mb-2"><strong>Format:</strong> Online (Google Meet)</p>
                                <p class="mb-2"><strong>Duration:</strong> 45 minutes</p>
                                <p class="mb-2"><strong>Interviewer:</strong> Rahul Sharma (Technical Lead)</p>
                                <p class="mb-0"><strong>Meeting Link:</strong> <a href="#">https://meet.google.com/abc-defg-hij</a></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-file-alt text-muted fs-1 mb-3"></i>
                        <p class="mb-0">Select an application to view details</p>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <?php if ($application && $application['status'] == 'Interview Scheduled'): ?>
                    <button type="button" class="btn btn-primary">Prepare for Interview</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <footer class="bg-light py-3 mt-4 border-top">
        <div class="container text-center">
            <p class="mb-0 text-muted">© 2023 Campus Placement Portal. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const sidebarToggle = document.querySelector('[data-bs-toggle="offcanvas"][data-bs-target="#sidebar"]');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    
                    // Create backdrop for mobile
                    if (sidebar.classList.contains('show')) {
                        const backdrop = document.createElement('div');
                        backdrop.classList.add('offcanvas-backdrop', 'show');
                        document.body.appendChild(backdrop);
                        
                        backdrop.addEventListener('click', function() {
                            sidebar.classList.remove('show');
                            this.remove();
                        });
                    } else {
                        const backdrop = document.querySelector('.offcanvas-backdrop');
                        if (backdrop) backdrop.remove();
                    }
                });
            }

            // Search functionality for applications
            const searchInput = document.querySelector('input[placeholder="Search applications..."]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const activeTabContent = document.querySelector('.tab-pane.active');
                    const tableRows = activeTabContent.querySelectorAll('tbody tr');
                    
                    tableRows.forEach(row => {
                        const companyName = row.querySelector('td:first-child').textContent.toLowerCase();
                        const position = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                        const status = row.querySelector('.badge').textContent.toLowerCase();
                        
                        const isMatch = companyName.includes(searchTerm) || 
                                        position.includes(searchTerm) || 
                                        status.includes(searchTerm);
                        
                        row.style.display = isMatch ? '' : 'none';
                    });
                });
            }

            // View job details functionality
            const viewDetailsButtons = document.querySelectorAll('.view-details');
            viewDetailsButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const appId = this.getAttribute('data-id');
                    const description = this.getAttribute('data-description');
                    
                    // Fetch application details
                    fetch(`applications.php?id=${appId}`)
                        .then(response => response.text())
                        .then(html => {
                            const parser = new DOMParser();
                            const doc = parser.parseFromString(html, 'text/html');
                            
                            // Get application details from the fetched page
                            const jobTitle = doc.querySelector('.job-title')?.textContent || '';
                            const companyName = doc.querySelector('.company-name')?.textContent || '';
                            const location = doc.querySelector('.fa-location-dot')?.nextElementSibling?.textContent || '';
                            const jobType = doc.querySelector('.fa-briefcase')?.nextElementSibling?.textContent || '';
                            const salary = doc.querySelector('.fa-indian-rupee-sign')?.nextElementSibling?.textContent || '';
                            
                            // Build job details HTML
                            let detailsHtml = `
                                <div class="d-flex align-items-center mb-4">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 60px; height: 60px;">
                                        <i class="fa-solid fa-building fs-3 text-secondary"></i>
                                    </div>
                                    <div>
                                        <h4 class="job-title mb-1">${jobTitle}</h4>
                                        <p class="company-name text-secondary mb-0">${companyName}</p>
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-location-dot me-2 text-primary"></i>
                                            <span>${location}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-briefcase me-2 text-primary"></i>
                                            <span>${jobType}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fa-solid fa-indian-rupee-sign me-2 text-primary"></i>
                                            <span>${salary}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-4">
                                    <h5>Job Description</h5>
                                    <div class="bg-light p-3 rounded">
                                        ${description || 'No description available'}
                                    </div>
                                </div>
                            `;
                            
                            // Update modal content
                            document.getElementById('jobDetailsContent').innerHTML = detailsHtml;
                        })
                        .catch(error => {
                            console.error('Error fetching application details:', error);
                            document.getElementById('jobDetailsContent').innerHTML = '<p class="text-danger">Error loading job details. Please try again.</p>';
                        });
                });
            });
        });
    </script>
</body>
</html>