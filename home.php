<?php
require_once 'config.php';
require_login();

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$sql = "SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC LIMIT 4";
$events_result = mysqli_query($conn, $sql);


$sql = "SELECT a.*, j.title, c.name as company_name 
        FROM applications a 
        JOIN jobs j ON a.job_id = j.id 
        JOIN companies c ON j.company_id = c.id 
        WHERE a.user_id = $user_id AND a.status NOT IN ('Rejected', 'Withdrawn', 'Offer Accepted', 'Offer Declined') 
        ORDER BY a.applied_date DESC 
        LIMIT 3";
$applications_result = mysqli_query($conn, $sql);

$sql = "SELECT j.*, c.name as company_name 
        FROM jobs j 
        JOIN companies c ON j.company_id = c.id 
        WHERE j.deadline >= CURDATE() 
        ORDER BY j.deadline ASC 
        LIMIT 4";
$drives_result = mysqli_query($conn, $sql);

$skills_query = "SELECT s.name FROM user_skills us JOIN skills s ON us.skill_id = s.id WHERE us.user_id = $user_id";
$skills_result = mysqli_query($conn, $skills_query);
$skills = [];
if ($skills_result && mysqli_num_rows($skills_result) > 0) {
    while ($skill = mysqli_fetch_assoc($skills_result)) {
        $skills[] = $skill['name'];
    }
}
$skills_str = !empty($skills) ? implode('|', array_map(function($skill) { 
    return preg_quote($skill, '/'); 
}, $skills)) : '';

$branch = $user['branch'];
$user_cgpa = floatval($user['cgpa']);

$sql = "SELECT j.*, c.name as company_name,
        (
            CASE 
                WHEN j.skills REGEXP '$skills_str' THEN 3
                WHEN j.title LIKE '%$branch%' OR j.description LIKE '%$branch%' THEN 2
                ELSE 1
            END
        ) as relevance_score,
        (
            CASE 
                WHEN j.min_cgpa IS NULL OR j.min_cgpa = 0 OR j.min_cgpa <= $user_cgpa THEN 1
                ELSE 0
            END
        ) as meets_cgpa
        FROM jobs j 
        JOIN companies c ON j.company_id = c.id
        WHERE j.deadline >= CURDATE()
        AND (j.min_cgpa IS NULL OR j.min_cgpa = 0 OR j.min_cgpa <= $user_cgpa)
        ORDER BY relevance_score DESC, j.deadline ASC
        LIMIT 5";
$recommended_result = mysqli_query($conn, $sql);

if (!$recommended_result) {
    error_log("Error in recommended jobs query: " . mysqli_error($conn));
    
    $sql = "SELECT j.*, c.name as company_name 
            FROM jobs j 
            JOIN companies c ON j.company_id = c.id
            WHERE j.deadline >= CURDATE()
            ORDER BY j.deadline ASC 
            LIMIT 5";
    $recommended_result = mysqli_query($conn, $sql);
}

if (!$recommended_result) {
    error_log("Error in fallback recommended jobs query: " . mysqli_error($conn));
}

$sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = $user_id AND is_read = 0";
$notif_result = mysqli_query($conn, $sql);
$notif_count = mysqli_fetch_assoc($notif_result)['count'];

$sql = "SELECT * FROM notifications WHERE user_id = $user_id ORDER BY created_at DESC LIMIT 3";
$notifications_result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Placement Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                            <a class="nav-link active" href="home.php">
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
                                <li><h6 class="dropdown-header">Notifications</h6></li>
                                <li><hr class="dropdown-divider"></li>
                                <?php while ($notification = mysqli_fetch_assoc($notifications_result)): ?>
                                <li>
                                    <a class="dropdown-item notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>" href="notifications.php?id=<?php echo $notification['id']; ?>">
                                        <div class="d-flex justify-content-between">
                                            <strong><?php echo htmlspecialchars($notification['title']); ?></strong>
                                            <small class="text-muted"><?php echo date('j M', strtotime($notification['created_at'])); ?></small>
                                        </div>
                                        <p class="mb-0 small"><?php echo htmlspecialchars($notification['message']); ?></p>
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <?php endwhile; ?>
                                
                            </ul>
                        </div>

                        <div class="dropdown">
                            <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="uploads/profile_pics/<?php echo htmlspecialchars($user['profile_pic'] ?: 'default.jpg'); ?>" class="rounded-circle" height="32" alt="Student" loading="lazy" />
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

                <div class="container-fluid">
                    <h1 class="h2 mb-4">Dashboard</h1>
                    <p class="text-muted mb-4">Welcome back! Here's an overview of your placement activities.</p>

                    <div class="row g-4 mb-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-briefcase text-primary"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="card-subtitle text-muted small">Active Applications</h6>
                                            <?php
                                            $sql = "SELECT COUNT(*) as count FROM applications WHERE user_id = $user_id AND status NOT IN ('Rejected', 'Withdrawn', 'Offer Accepted', 'Offer Declined')";
                                            $result = mysqli_query($conn, $sql);
                                            $active_apps = mysqli_fetch_assoc($result)['count'];
                                            ?>
                                            <h2 class="card-title h4 mb-0"><?php echo $active_apps; ?></h2>
                                            <p class="card-text small text-success mb-0">+2 from last week</p>
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
                                            <h6 class="card-subtitle text-muted small">Upcoming Interviews</h6>
                                            <?php
                                            $sql = "SELECT COUNT(*) as count FROM applications WHERE user_id = $user_id AND status = 'Interview Scheduled'";
                                            $result = mysqli_query($conn, $sql);
                                            $interviews = mysqli_fetch_assoc($result)['count'];
                                            ?>
                                            <h2 class="card-title h4 mb-0"><?php echo $interviews; ?></h2>
                                            <?php if ($interviews > 0): ?>
                                            <p class="card-text small mb-0">Next: TechCorp (Tomorrow)</p>
                                            <?php endif; ?>
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
                                            <i class="fas fa-file-alt text-success"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="card-subtitle text-muted small">Profile Completion</h6>
                                            <h2 class="card-title h4 mb-0">85%</h2>
                                            <div class="progress mt-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%;" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 p-3 rounded-circle">
                                            <i class="fas fa-check-circle text-warning"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h6 class="card-subtitle text-muted small">Eligible Jobs</h6>
                                            <?php
                                            $sql = "SELECT COUNT(*) as count FROM jobs WHERE deadline >= CURDATE()";
                                            $result = mysqli_query($conn, $sql);
                                            $eligible_jobs = mysqli_fetch_assoc($result)['count'];
                                            ?>
                                            <h2 class="card-title h4 mb-0"><?php echo $eligible_jobs; ?></h2>
                                            <p class="card-text small text-success mb-0">+3 new matches</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <ul class="nav nav-tabs mb-4" id="dashboardTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab" aria-controls="upcoming" aria-selected="true">Upcoming Events</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="placement-drives-tab" data-bs-toggle="tab" data-bs-target="#placement-drives" type="button" role="tab" aria-controls="placement-drives" aria-selected="false">Placement Drives</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="applications-tab" data-bs-toggle="tab" data-bs-target="#applications" type="button" role="tab" aria-controls="applications" aria-selected="false">My Applications</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="recommended-tab" data-bs-toggle="tab" data-bs-target="#recommended" type="button" role="tab" aria-controls="recommended" aria-selected="false">
                                Recommended Jobs
                            </button>
                        </li>
                    </ul>
                    
                    <div class="tab-content" id="dashboardTabsContent">
                        
                        <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Upcoming Interviews & Events</h5>
                                    <p class="card-text small text-muted mb-0">Your scheduled interviews and placement events</p>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush">
                                        <?php 
                                        
                                        $sql = "SELECT a.*, j.title, c.name as company_name 
                                                FROM applications a 
                                                JOIN jobs j ON a.job_id = j.id 
                                                JOIN companies c ON j.company_id = c.id 
                                                WHERE a.user_id = $user_id AND a.status = 'Interview Scheduled' 
                                                ORDER BY a.updated_at ASC";
                                        $interviews = mysqli_query($conn, $sql);
                                        
                                        if (mysqli_num_rows($interviews) > 0) {
                                            while ($interview = mysqli_fetch_assoc($interviews)) {
                                                echo '<div class="list-group-item border rounded p-3 mb-2">';
                                                echo '<div class="d-flex justify-content-between align-items-center">';
                                                echo '<div>';
                                                echo '<h6 class="mb-1">' . htmlspecialchars($interview['company_name']) . ' - ' . htmlspecialchars($interview['title']) . ' Interview</h6>';
                                                echo '<p class="small text-muted mb-0">Tomorrow, 10:00 AM - Online</p>';
                                                echo '</div>';
                                                echo '<a href="applications.php?id=' . $interview['id'] . '" class="btn btn-primary">Prepare</a>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        }
                                       
                                        if (mysqli_num_rows($events_result) > 0) {
                                            while ($event = mysqli_fetch_assoc($events_result)) {
                                                echo '<div class="list-group-item border rounded p-3 mb-2">';
                                                echo '<div class="d-flex justify-content-between align-items-center">';
                                                echo '<div>';
                                                echo '<h6 class="mb-1">' . htmlspecialchars($event['event_name']) . '</h6>';
                                                echo '<p class="small text-muted mb-0">' . date('M j, g:i A', strtotime($event['event_date'])) . ' - ' . htmlspecialchars($event['location']) . '</p>';
                                                echo '</div>';
                                                echo '<a href="events.php?id=' . $event['id'] . '" class="btn btn-primary">Details</a>';
                                                echo '</div>';
                                                echo '</div>';
                                            }
                                        }
                                        
                                        if (mysqli_num_rows($interviews) == 0 && mysqli_num_rows($events_result) == 0) {
                                            echo '<div class="text-center py-4">';
                                            echo '<i class="fas fa-calendar-alt text-muted fs-1 mb-3"></i>';
                                            echo '<p class="mb-0">No upcoming events or interviews</p>';
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="placement-drives" role="tabpanel" aria-labelledby="placement-drives-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Upcoming Placement Drives</h5>
                                    <p class="card-text small text-muted mb-0">Companies visiting campus soon</p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Company</th>
                                                <th>Role</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($drives_result) > 0) {
                                                while ($drive = mysqli_fetch_assoc($drives_result)) {
                                                    // Check if student is eligible based on CGPA
                                                    $eligible = true; // Default to eligible
                                                    
                                                    // Check if student has already applied
                                                    $sql = "SELECT id FROM applications WHERE user_id = $user_id AND job_id = " . $drive['id'];
                                                    $applied_result = mysqli_query($conn, $sql);
                                                    $already_applied = mysqli_num_rows($applied_result) > 0;
                                                    
                                                    echo '<tr>';
                                                    echo '<td>';
                                                    echo '<div class="d-flex align-items-center">';
                                                    echo '<div class="bg-light rounded-circle p-2 me-3">';
                                                    echo '<i class="fas fa-building text-secondary"></i>';
                                                    echo '</div>';
                                                    echo '<span>' . htmlspecialchars($drive['company_name']) . '</span>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                    echo '<td>' . htmlspecialchars($drive['title']) . '</td>';
                                                    echo '<td>' . date('M j, Y', strtotime($drive['deadline'])) . '</td>';
                                                    
                                                    if ($eligible) {
                                                        echo '<td><span class="badge bg-success">Eligible</span></td>';
                                                        if ($already_applied) {
                                                            echo '<td><button class="btn btn-sm btn-secondary" disabled>Applied</button></td>';
                                                        } else {
                                                            echo '<td><a href="apply.php?job_id=' . $drive['id'] . '" class="btn btn-sm btn-primary">Apply Now</a></td>';
                                                        }
                                                    } else {
                                                        echo '<td><span class="badge bg-danger">Not Eligible</span></td>';
                                                        echo '<td><button class="btn btn-sm btn-secondary" disabled>Not Eligible</button></td>';
                                                    }
                                                    
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center py-4">No upcoming placement drives</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-light">
                                    <a href="jobs.php" class="text-decoration-none small">View all placement drives</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Applications Tab -->
                        <div class="tab-pane fade" id="applications" role="tabpanel" aria-labelledby="applications-tab">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Recent Applications</h5>
                                    <p class="card-text small text-muted mb-0">Status of your job applications</p>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Company</th>
                                                <th>Role</th>
                                                <th>Applied Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if (mysqli_num_rows($applications_result) > 0) {
                                                while ($application = mysqli_fetch_assoc($applications_result)) {
                                                    echo '<tr>';
                                                    echo '<td>';
                                                    echo '<div class="d-flex align-items-center">';
                                                    echo '<div class="bg-light rounded-circle p-2 me-3">';
                                                    echo '<i class="fas fa-building text-secondary"></i>';
                                                    echo '</div>';
                                                    echo '<span>' . htmlspecialchars($application['company_name']) . '</span>';
                                                    echo '</div>';
                                                    echo '</td>';
                                                    echo '<td>' . htmlspecialchars($application['title']) . '</td>';
                                                    echo '<td>' . date('M j, Y', strtotime($application['applied_date'])) . '</td>';
                                                    
                                                    // Set badge color based on status
                                                    $badge_class = 'bg-primary';
                                                    if ($application['status'] == 'Rejected') {
                                                        $badge_class = 'bg-danger';
                                                    } elseif ($application['status'] == 'Offer Received') {
                                                        $badge_class = 'bg-success';
                                                    } elseif ($application['status'] == 'Application Under Review') {
                                                        $badge_class = 'bg-info text-dark';
                                                    } elseif ($application['status'] == 'Technical Assessment' || $application['status'] == 'Application Incomplete') {
                                                        $badge_class = 'bg-warning text-dark';
                                                    }
                                                    
                                                    echo '<td><span class="badge ' . $badge_class . '">' . htmlspecialchars($application['status']) . '</span></td>';
                                                    echo '<td><a href="applications.php?id=' . $application['id'] . '" class="btn btn-sm btn-outline-primary">View Details</a></td>';
                                                    echo '</tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="5" class="text-center py-4">No applications yet</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer bg-light">
                                    <a href="applications.php" class="text-decoration-none small">View all applications</a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recommended Jobs Tab -->
                        <div class="tab-pane fade" id="recommended" role="tabpanel" aria-labelledby="recommended-tab">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="card-title mb-0">Recommended Jobs</h5>
                                        <p class="card-text small text-muted mb-0">Jobs that match your profile and preferences</p>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <select class="form-select form-select-sm" id="cgpaFilter">
                                            <option value="all">All Jobs</option>
                                            <option value="eligible" selected>CGPA Eligible Only</option>
                                        </select>
                                        <select class="form-select form-select-sm" id="sortFilter">
                                            <option value="relevance" selected>Sort by Relevance</option>
                                            <option value="deadline">Sort by Deadline</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="list-group list-group-flush job-recommendations">
                                        <?php
                                        if (mysqli_num_rows($recommended_result) > 0) {
                                            while ($job = mysqli_fetch_assoc($recommended_result)) {
                                                // Check if already applied
                                                $sql = "SELECT id FROM applications WHERE user_id = $user_id AND job_id = " . $job['id'];
                                                $applied_result = mysqli_query($conn, $sql);
                                                $already_applied = mysqli_num_rows($applied_result) > 0;

                                                // Get matching skills
                                                $job_skills = !empty($job['skills']) ? explode(',', $job['skills']) : [];
                                                // Clean up job skills to remove any whitespace
                                                $job_skills = array_map('trim', $job_skills);

                                                // Ensure we're working with arrays for the intersection
                                                $matching_skills = !empty($skills) && !empty($job_skills) ? array_intersect($skills, $job_skills) : [];
                                                $matching_count = count($matching_skills);
                                                $matching_skills_str = $matching_count > 0 ? implode(', ', array_slice($matching_skills, 0, 2)) : '';

                                                // Calculate relevance score (matching skills, branch match, CGPA match)
                                                $skill_score = !empty($skills) ? ($matching_count / count($skills)) * 50 : 0;
                                                $branch_score = 0;
                                                if (!empty($branch) && (!empty($job['title']) || !empty($job['description']))) {
                                                    $branch_score = (strpos(strtolower($job['title'] ?? ''), strtolower($branch)) !== false || 
                                                                   strpos(strtolower($job['description'] ?? ''), strtolower($branch)) !== false) ? 30 : 0;
                                                }

                                                // CGPA score - higher if user's CGPA is well above the minimum requirement
                                                $cgpa_score = 0;
                                                if (!empty($user_cgpa) && !empty($job['min_cgpa']) && $job['min_cgpa'] > 0) {
                                                    $cgpa_diff = $user_cgpa - $job['min_cgpa'];
                                                    if ($cgpa_diff >= 0) {
                                                        // Give full points if exactly meeting or exceeding by 1 point
                                                        $cgpa_score = min(20, 10 + ($cgpa_diff * 10));
                                                    }
                                                }

                                                $relevance_score = $skill_score + $branch_score + $cgpa_score;

                                                echo '<div class="list-group-item border rounded p-3 mb-2 job-card" 
                                                     data-meets-cgpa="' . ($job['meets_cgpa'] ? '1' : '0') . '"
                                                      data-relevance="' . $relevance_score . '"
                                                      data-deadline="' . (isset($job['deadline']) ? $job['deadline'] : '') . '">';
                                                
                                                echo '<div class="d-flex justify-content-between align-items-start mb-2">';
                                                echo '<div>';
                                                echo '<h6 class="mb-1">' . htmlspecialchars($job['company_name']) . ' - ' . htmlspecialchars($job['title']) . '</h6>';
                                                
                                                // Match indicators - show badges based on actual scores
                                                $has_badges = false;
                                                if ($skill_score > 25) { // Show skills match if at least 50% of skills match (25 points)
                                                    echo '<span class="badge bg-info text-dark">Skills Match</span> ';
                                                    $has_badges = true;
                                                }
                                                if ($branch_score > 0) {
                                                    echo '<span class="badge bg-light text-dark">Branch Match</span> ';
                                                    $has_badges = true;
                                                }
                                                if ($cgpa_score > 15) { // High CGPA match (significantly above minimum)
                                                    echo '<span class="badge bg-warning text-dark">Strong CGPA</span> ';
                                                    $has_badges = true;
                                                }
                                                
                                                // If relevance is very high (75+), show perfect match badge
                                                if ($relevance_score > 75) {
                                                    echo '<span class="badge bg-success text-white">Perfect Match</span> ';
                                                    $has_badges = true;
                                                } else if (!$has_badges && $relevance_score > 0) {
                                                    // If no specific badges but some relevance, show partial match
                                                    echo '<span class="badge bg-secondary text-white">Partial Match</span> ';
                                                }
                                                
                                                echo '</div>'; // Close title div
                                                
                                                echo '<div class="d-flex gap-1">';
                                                echo '<span class="badge bg-primary">' . htmlspecialchars($job['job_type'] ?: 'Full-time') . '</span>';
                                                
                                                echo $job['meets_cgpa'] ? 
                                                    '<span class="badge bg-success">CGPA OK</span>' : 
                                                    '<span class="badge bg-danger">Min CGPA: ' . number_format($job['min_cgpa'] ?? 0, 2) . '</span>';
                                                
                                                echo '</div>'; // Close badges div
                                                echo '</div>'; // Close title row
                                                
                                                // Job details
                                                if ($matching_count > 0) {
                                                    echo '<p class="small text-muted mb-0">Matches: ' . htmlspecialchars($matching_skills_str) . ($matching_count > 2 ? ' and more' : '') . '</p>';
                                                }

                                                echo '<p class="small text-muted mb-0">Deadline: <strong>' . (isset($job['deadline']) ? date('M j, Y', strtotime($job['deadline'])) : 'Not specified') . '</strong></p>';
                                                
                                                // Action buttons
                                                echo '<div class="d-flex justify-content-end mt-2">';
                                                echo '<div class="d-flex gap-2">';
                                                echo '<a href="jobs.php?id=' . $job['id'] . '" class="btn btn-sm btn-outline-primary">View Details</a>';
                                                
                                                if ($already_applied) {
                                                    echo '<button class="btn btn-sm btn-secondary" disabled>Applied</button>';
                                                } else if ($job['meets_cgpa']) {
                                                    echo '<a href="apply.php?job_id=' . $job['id'] . '" class="btn btn-sm btn-primary">Apply</a>';
                                                } else {
                                                    echo '<button class="btn btn-sm btn-secondary" disabled>CGPA Too Low</button>';
                                                }
                                                
                                                echo '</div>'; // End of buttons div
                                                echo '</div>'; // End of justify-content-end div
                                                echo '</div>'; // End of list-group-item
                                            }
                                        } else {
                                            echo '<div class="text-center py-4">';
                                            echo '<i class="fas fa-search text-muted fs-1 mb-3"></i>';
                                            echo '<p class="mb-0">No recommended jobs found</p>';
                                            
                                            // Debug info for administrators
                                            if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
                                                echo '<div class="alert alert-info mt-3">';
                                                echo '<p class="mb-0">Debug info: SQL query returned no results</p>';
                                                echo '<p class="mb-0 small">User ID: ' . $user_id . '</p>';
                                                echo '<p class="mb-0 small">User CGPA: ' . $user_cgpa . '</p>';
                                                echo '<p class="mb-0 small">Skills: ' . $skills_str . '</p>';
                                                echo '<p class="mb-0 small">Branch: ' . $branch . '</p>';
                                                echo '</div>';
                                            }
                                            
                                            echo '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="card-footer bg-light d-flex justify-content-between align-items-center">
                                    <a href="jobs.php#recommendedJobs" class="text-decoration-none small">View all jobs</a>
                                    <small class="text-muted">Showing jobs matching your skills and branch</small>
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

    <!-- Bootstrap JS -->
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

            // Notification system - mark as read
            const notificationItems = document.querySelectorAll('.notification-item');
            notificationItems.forEach(item => {
                item.addEventListener('click', function() {
                    const notificationId = this.getAttribute('href').split('=')[1];
                    
                    // Send AJAX request to mark notification as read
                    fetch('mark_notification_read.php?id=' + notificationId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.classList.remove('unread');
                                
                                // Update notification count
                                const badge = document.querySelector('.badge.rounded-pill.bg-danger');
                                if (badge) {
                                    const count = parseInt(badge.textContent) - 1;
                                    if (count > 0) {
                                        badge.textContent = count;
                                    } else {
                                        badge.style.display = 'none';
                                    }
                                }
                            }
                        });
                });
            });
            
            // Add filter controls for recommended jobs
            const cgpaFilterSelect = document.getElementById('cgpaFilter');
            const sortFilterSelect = document.getElementById('sortFilter');
            
            if (cgpaFilterSelect && sortFilterSelect) {
                function filterJobs() {
                    const cgpaFilter = cgpaFilterSelect.value;
                    const sortFilter = sortFilterSelect.value;
                    const jobCards = document.querySelectorAll('.job-recommendations .job-card');
                    
                    jobCards.forEach(card => {
                        // Filter by CGPA eligibility
                        if (cgpaFilter === 'eligible' && card.getAttribute('data-meets-cgpa') === '0') {
                            card.style.display = 'none';
                        } else {
                            card.style.display = 'block';
                        }
                    });
                    
                    // Sort jobs
                    const jobsList = document.querySelector('.job-recommendations');
                    const jobsArray = Array.from(jobCards);
                    
                    jobsArray.sort((a, b) => {
                        if (sortFilter === 'relevance') {
                            return parseInt(b.getAttribute('data-relevance')) - parseInt(a.getAttribute('data-relevance'));
                        } else {
                            return new Date(a.getAttribute('data-deadline')) - new Date(b.getAttribute('data-deadline'));
                        }
                    });
                    
                    // Reappend sorted elements
                    jobsArray.forEach(job => {
                        jobsList.appendChild(job);
                    });
                }
                
                // Initial filter
                filterJobs();
                
                // Add event listeners
                cgpaFilterSelect.addEventListener('change', filterJobs);
                sortFilterSelect.addEventListener('change', filterJobs);
            }
        });
    </script>
</body>
</html>