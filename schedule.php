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

// Get attendance records for the student
$attendance_query = "
    SELECT a.*, e.event_name, e.company_name, e.event_type, e.event_date, e.location, e.description
    FROM attendance a
    JOIN events e ON a.event_id = e.id
    WHERE a.student_id = $user_id
    ORDER BY e.event_date DESC
";
$attendance_result = mysqli_query($conn, $attendance_query);

// Get upcoming events
$upcoming_query = "
    SELECT e.*
    FROM events e
    LEFT JOIN attendance a ON e.id = a.event_id AND a.student_id = $user_id
    WHERE e.event_date >= CURDATE() AND a.id IS NULL
    ORDER BY e.event_date ASC
    LIMIT 5
";
$upcoming_result = mysqli_query($conn, $upcoming_query);

// Get attendance statistics
$stats_query = "
    SELECT 
        COUNT(*) as total_events,
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as present_count,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as absent_count
    FROM attendance
    WHERE student_id = $user_id
";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

$attendance_rate = 0;
if ($stats['total_events'] > 0) {
    $attendance_rate = round(($stats['present_count'] / $stats['total_events']) * 100);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Details - Campus Placement Portal</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .attendance-badge {
            font-size: 0.8rem;
            padding: 0.35em 0.65em;
        }
        .event-card {
            transition: transform 0.2s;
        }
        .event-card:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .stats-icon {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }
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
                            <a class="nav-link" href="resume.php">
                                <i class="fas fa-file-pdf me-2"></i>
                                Resume Upload                            
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="schedule.php">
                                <i class="fas fa-calendar-days me-2"></i>
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

                <!-- Attendance Content -->
                <div class="container-fluid">
                    <h1 class="h2 mb-4">Attendance Details</h1>
                    
                    <!-- Attendance Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card stats-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-primary bg-opacity-10 text-primary me-3">
                                            <i class="fas fa-calendar-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-muted mb-0">Total Events</h6>
                                            <h2 class="mt-2 mb-0"><?php echo $stats['total_events']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card stats-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-success bg-opacity-10 text-success me-3">
                                            <i class="fas fa-user-check"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-muted mb-0">Present</h6>
                                            <h2 class="mt-2 mb-0"><?php echo $stats['present_count']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <div class="card stats-card h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="stats-icon bg-danger bg-opacity-10 text-danger me-3">
                                            <i class="fas fa-user-xmark"></i>
                                        </div>
                                        <div>
                                            <h6 class="card-title text-muted mb-0">Absent</h6>
                                            <h2 class="mt-2 mb-0"><?php echo $stats['absent_count']; ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Attendance Rate -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Attendance Rate</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <div class="position-relative d-inline-block">
                                        <svg width="120" height="120" viewBox="0 0 120 120">
                                            <circle cx="60" cy="60" r="54" fill="none" stroke="#e9ecef" stroke-width="12" />
                                            <circle cx="60" cy="60" r="54" fill="none" stroke="<?php echo $attendance_rate >= 75 ? '#198754' : ($attendance_rate >= 50 ? '#ffc107' : '#dc3545'); ?>" stroke-width="12"
                                                stroke-dasharray="339.292" stroke-dashoffset="<?php echo 339.292 * (1 - $attendance_rate / 100); ?>" />
                                        </svg>
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <h3 class="mb-0"><?php echo $attendance_rate; ?>%</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <h5>Your Attendance Summary</h5>
                                    <p>
                                        <?php if ($attendance_rate >= 75): ?>
                                            <span class="text-success">Excellent attendance!</span> You've attended <?php echo $attendance_rate; ?>% of all events. Keep up the good work!
                                        <?php elseif ($attendance_rate >= 50): ?>
                                            <span class="text-warning">Good attendance.</span> You've attended <?php echo $attendance_rate; ?>% of all events. Try to improve your attendance for better opportunities.
                                        <?php else: ?>
                                            <span class="text-danger">Low attendance.</span> You've only attended <?php echo $attendance_rate; ?>% of all events. Please make an effort to attend more events as it may affect your placement opportunities.
                                        <?php endif; ?>
                                    </p>
                                    <div class="alert alert-info mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Maintaining at least 75% attendance is recommended for optimal placement opportunities.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Upcoming Events -->
                    <?php if (mysqli_num_rows($upcoming_result) > 0): ?>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Upcoming Events</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Company</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($event = mysqli_fetch_assoc($upcoming_result)): ?>
                                        <tr>
                                            <td><?php echo $event['event_name']; ?></td>
                                            <td><?php echo $event['company_name']; ?></td>
                                            <td><span class="badge bg-info"><?php echo $event['event_type']; ?></span></td>
                                            <td><?php echo date('M d, Y', strtotime($event['event_date'])); ?></td>
                                            <td><?php echo $event['location']; ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Attendance Records -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Attendance History</h5>
                            <div>
                                <select class="form-select form-select-sm" id="filterStatus">
                                    <option value="all">All</option>
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (mysqli_num_rows($attendance_result) > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover" id="attendanceTable">
                                    <thead>
                                        <tr>
                                            <th>Event</th>
                                            <th>Company</th>
                                            <th>Type</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($attendance = mysqli_fetch_assoc($attendance_result)): ?>
                                        <tr data-status="<?php echo $attendance['status']; ?>">
                                            <td><?php echo $attendance['event_name']; ?></td>
                                            <td><?php echo $attendance['company_name']; ?></td>
                                            <td><span class="badge bg-info"><?php echo $attendance['event_type']; ?></span></td>
                                            <td><?php echo date('M d, Y', strtotime($attendance['event_date'])); ?></td>
                                            <td><?php echo $attendance['location']; ?></td>
                                            <td>
                                                <?php if ($attendance['status'] == 'present'): ?>
                                                <span class="badge bg-success attendance-badge">Present</span>
                                                <?php else: ?>
                                                <span class="badge bg-danger attendance-badge">Absent</span>
                                                <?php if (!empty($attendance['excuse_reason'])): ?>
                                                <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($attendance['excuse_reason']); ?>"></i>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-xmark text-muted mb-3" style="font-size: 3rem;"></i>
                                <h5>No attendance records found</h5>
                                <p class="text-muted">You don't have any attendance records yet.</p>
                            </div>
                            <?php endif; ?>
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
