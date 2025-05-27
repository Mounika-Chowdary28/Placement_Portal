<?php
require_once 'config.php';
require_login();

// Get user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Process AJAX requests
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';

// Mark notification as read if specific ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $notification_id = (int)$_GET['id'];
    
    // Use prepared statement to prevent SQL injection
    $markReadSql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $markReadSql);
    mysqli_stmt_bind_param($stmt, "ii", $notification_id, $user_id);
    $success = mysqli_stmt_execute($stmt);
    
    if ($is_ajax) {
        echo json_encode(['success' => $success]);
        exit;
    }
    
    // Redirect to referring page or notifications page
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// Mark all notifications as read
if (isset($_GET['mark_all_read']) && $_GET['mark_all_read'] == 1) {
    // Use prepared statement to prevent SQL injection
    $markAllReadSql = "UPDATE notifications SET is_read = 1 WHERE user_id = ?";
    $stmt = mysqli_prepare($conn, $markAllReadSql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    $success = mysqli_stmt_execute($stmt);
    
    if ($is_ajax) {
        echo json_encode(['success' => $success]);
        exit;
    }
    
    // Redirect to referring page or notifications page
    if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}

// Get all notifications for the user
$sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$notifications_result = mysqli_stmt_get_result($stmt);
$notifications = [];
while ($row = mysqli_fetch_assoc($notifications_result)) {
    $notifications[] = $row;
}

// Get unread notifications count
$sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = ? AND is_read = 0";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$notif_result = mysqli_stmt_get_result($stmt);
$notif_count = mysqli_fetch_assoc($notif_result)['count'];

// Reset notification_result for use in the template
mysqli_data_seek($notifications_result, 0);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Campus Placement Portal</title>
    
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
            transition: background-color 0.2s;
        }
        .notification-item.unread {
            background-color: #f0f8ff;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .mark-all-read-btn {
            font-size: 0.9rem;
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
                                <i class="fa-solid fa-magnifying-glass"></i>
                                Resume Upload                            
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
                                    <a class="dropdown-item notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>" href="notifications.php?id=<?php echo $notification['id']; ?>">
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

                <!-- Notifications Content -->
                <div class="container-fluid">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="h2 mb-0">Notifications</h1>
                            <p class="text-muted">View and manage your notifications</p>
                        </div>
                        <?php if ($notif_count > 0): ?>
                        <a href="notifications.php?mark_all_read=1" class="btn btn-sm btn-outline-primary mark-all-read-btn">
                            <i class="fas fa-check-double me-1"></i>Mark all as read
                        </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php if (count($notifications) > 0): ?>
                                    <?php foreach ($notifications as $notification): ?>
                                        <a href="javascript:void(0);" class="list-group-item notification-item <?php echo $notification['is_read'] ? '' : 'unread'; ?>" data-id="<?php echo $notification['id']; ?>">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0"><?php echo htmlspecialchars($notification['title']); ?></h6>
                                                <span class="notification-time"><?php echo date('M j, Y g:i A', strtotime($notification['created_at'])); ?></span>
                                            </div>
                                            <p class="mb-0"><?php echo htmlspecialchars($notification['message']); ?></p>
                                        </a>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-bell-slash text-muted fs-1 mb-3"></i>
                                        <p class="mb-0">No notifications to display</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

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
                                updateNotificationBadge();
                            }
                        })
                        .catch(error => {
                            console.error('Error marking notification as read:', error);
                        });
                    }
                });
            });
            
            // Handle mark all as read
            const markAllReadBtn = document.querySelector('.mark-all-read-btn');
            if (markAllReadBtn) {
                markAllReadBtn.addEventListener('click', function(e) {
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
                        }
                    })
                    .catch(error => {
                        console.error('Error marking all notifications as read:', error);
                    });
                });
            }
            
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
            
            // Update notification badge count
            function updateNotificationBadge() {
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
        });
    </script>
</body>
</html> 