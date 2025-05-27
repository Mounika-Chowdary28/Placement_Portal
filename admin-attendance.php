<?php
// Start session
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
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

// Process form submissions
$message = "";
$error = "";

// Add new event
if (isset($_POST['add_event'])) {
    $event_name = mysqli_real_escape_string($conn, $_POST['event_name']);
    $company_name = mysqli_real_escape_string($conn, $_POST['company_name']);
    $event_type = mysqli_real_escape_string($conn, $_POST['event_type']);
    $event_date = mysqli_real_escape_string($conn, $_POST['event_date']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    $query = "INSERT INTO events (event_name, company_name, event_type, event_date, location, description) 
              VALUES ('$event_name', '$company_name', '$event_type', '$event_date', '$location', '$description')";
    
    if (mysqli_query($conn, $query)) {
        $message = "Event added successfully!";
    } else {
        $error = "Error adding event: " . mysqli_error($conn);
    }
}

// Mark attendance
if (isset($_POST['mark_attendance'])) {
    $event_id = mysqli_real_escape_string($conn, $_POST['event_id']);
    $student_ids = isset($_POST['student_ids']) ? $_POST['student_ids'] : [];
    $statuses = isset($_POST['statuses']) ? $_POST['statuses'] : [];
    $reasons = isset($_POST['reasons']) ? $_POST['reasons'] : [];
    
    // First, delete existing attendance records for this event
    $delete_query = "DELETE FROM attendance WHERE event_id = $event_id";
    mysqli_query($conn, $delete_query);
    
    // Insert new attendance records
    $success = true;
    
    for ($i = 0; $i < count($student_ids); $i++) {
        $student_id = mysqli_real_escape_string($conn, $student_ids[$i]);
        $status = mysqli_real_escape_string($conn, $statuses[$i]);
        $reason = mysqli_real_escape_string($conn, $reasons[$i]);
        
        $insert_query = "INSERT INTO attendance (student_id, event_id, status, excuse_reason) 
                         VALUES ('$student_id', '$event_id', '$status', '$reason')";
        
        if (!mysqli_query($conn, $insert_query)) {
            $success = false;
            $error = "Error marking attendance: " . mysqli_error($conn);
            break;
        }
    }
    
    if ($success) {
        $message = "Attendance marked successfully!";
    }
}

// Get all events
$events_query = "SELECT * FROM events ORDER BY event_date DESC";
$events_result = mysqli_query($conn, $events_query);

// Get event details if event_id is provided
$event_details = null;
$students_list = null;

if (isset($_GET['event_id'])) {
    $event_id = mysqli_real_escape_string($conn, $_GET['event_id']);
    
    // Get event details
    $event_query = "SELECT * FROM events WHERE id = $event_id";
    $event_result = mysqli_query($conn, $event_query);
    $event_details = mysqli_fetch_assoc($event_result);
    
    // Get students and their attendance status for this event
    $students_query = "
        SELECT u.id, u.reg_number, u.full_name, u.branch, u.year_of_study, a.status, a.excuse_reason
        FROM users u
        LEFT JOIN attendance a ON u.id = a.student_id AND a.event_id = $event_id
        ORDER BY u.full_name
    ";
    $students_result = mysqli_query($conn, $students_query);
    $students_list = [];
    
    while ($student = mysqli_fetch_assoc($students_result)) {
        $students_list[] = $student;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management - Admin Panel</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .admin-header {
            background-color: #343a40;
            color: white;
        }
        .event-list {
            max-height: 600px;
            overflow-y: auto;
        }
        .event-item {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .event-item:hover {
            background-color: #f8f9fa;
        }
        .event-item.active {
            background-color: #e9ecef;
            border-left: 4px solid #0d6efd;
        }
        .attendance-form {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <header class="admin-header py-3">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-shield fs-3 me-3"></i>
                    <h1 class="h3 mb-0">Admin Panel - Attendance Management</h1>
                </div>
                <div>
                    <a href="admin-dashboard.php" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <div class="container-fluid py-4">
        <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Left Sidebar - Events List -->
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Events</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEventModal">
                            <i class="fas fa-plus me-1"></i> Add Event
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="event-list">
                            <div class="list-group list-group-flush">
                                <?php if (mysqli_num_rows($events_result) > 0): ?>
                                    <?php while ($event = mysqli_fetch_assoc($events_result)): ?>
                                        <a href="?event_id=<?php echo $event['id']; ?>" class="list-group-item list-group-item-action event-item <?php echo (isset($_GET['event_id']) && $_GET['event_id'] == $event['id']) ? 'active' : ''; ?>">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?php echo $event['event_name']; ?></h6>
                                                <small><?php echo date('M d, Y', strtotime($event['event_date'])); ?></small>
                                            </div>
                                            <p class="mb-1 small"><?php echo $event['company_name']; ?></p>
                                            <small class="text-muted">
                                                <span class="badge bg-info"><?php echo $event['event_type']; ?></span>
                                                <span class="ms-2"><?php echo $event['location']; ?></span>
                                            </small>
                                        </a>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="text-center py-4">
                                        <p class="text-muted mb-0">No events found</p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content - Attendance Form -->
            <div class="col-md-8">
                <?php if ($event_details): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Mark Attendance: <?php echo $event_details['event_name']; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Company:</strong> <?php echo $event_details['company_name']; ?></p>
                                    <p><strong>Type:</strong> <?php echo $event_details['event_type']; ?></p>
                                    <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event_details['event_date'])); ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Location:</strong> <?php echo $event_details['location']; ?></p>
                                    <p><strong>Description:</strong> <?php echo $event_details['description']; ?></p>
                                </div>
                            </div>
                        </div>

                        <form method="post" action="">
                            <input type="hidden" name="event_id" value="<?php echo $event_details['id']; ?>">
                            
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <button type="button" class="btn btn-outline-success btn-sm me-2" id="markAllPresent">
                                        <i class="fas fa-check-circle me-1"></i> Mark All Present
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm" id="markAllAbsent">
                                        <i class="fas fa-times-circle me-1"></i> Mark All Absent
                                    </button>
                                </div>
                                <div class="input-group" style="width: 250px;">
                                    <input type="text" class="form-control form-control-sm" id="searchStudent" placeholder="Search student...">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                            
                            <div class="attendance-form">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Reg. Number</th>
                                            <th>Name</th>
                                            <th>Branch</th>
                                            <th>Year</th>
                                            <th>Status</th>
                                            <th>Reason (if absent)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if ($students_list): ?>
                                            <?php foreach ($students_list as $index => $student): ?>
                                                <tr class="student-row">
                                                    <td><?php echo $student['reg_number']; ?></td>
                                                    <td><?php echo $student['full_name']; ?></td>
                                                    <td><?php echo $student['branch']; ?></td>
                                                    <td><?php echo $student['year_of_study']; ?></td>
                                                    <td>
                                                        <input type="hidden" name="student_ids[]" value="<?php echo $student['id']; ?>">
                                                        <select name="statuses[]" class="form-select form-select-sm status-select">
                                                            <option value="present" <?php echo ($student['status'] == 'present' || $student['status'] === null) ? 'selected' : ''; ?>>Present</option>
                                                            <option value="absent" <?php echo ($student['status'] == 'absent') ? 'selected' : ''; ?>>Absent</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="reasons[]" class="form-control form-control-sm reason-input" placeholder="Reason for absence" value="<?php echo $student['excuse_reason'] ?? ''; ?>" <?php echo ($student['status'] != 'absent') ? 'disabled' : ''; ?>>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center">No students found</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" name="mark_attendance" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Attendance
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-calendar-day text-muted mb-3" style="font-size: 3rem;"></i>
                        <h4>Select an Event</h4>
                        <p class="text-muted">Please select an event from the list to mark attendance</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="event_name" class="form-label">Event Name</label>
                            <input type="text" class="form-control" id="event_name" name="event_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="event_type" class="form-label">Event Type</label>
                            <select class="form-select" id="event_type" name="event_type" required>
                                <option value="">Select Type</option>
                                <option value="Recruitment">Recruitment</option>
                                <option value="Workshop">Workshop</option>
                                <option value="Info Session">Info Session</option>
                                <option value="Training">Training</option>
                                <option value="Seminar">Seminar</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="event_date" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="event_date" name="event_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="location" class="form-label">Location</label>
                            <input type="text" class="form-control" id="location" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_event" class="btn btn-primary">Add Event</button>
                    </div>
                </form>
            </div>
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
    
    <!-- Custom JS -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle status change to enable/disable reason input
        const statusSelects = document.querySelectorAll('.status-select');
        statusSelects.forEach(select => {
            select.addEventListener('change', function() {
                const reasonInput = this.closest('tr').querySelector('.reason-input');
                reasonInput.disabled = this.value !== 'absent';
                if (this.value !== 'absent') {
                    reasonInput.value = '';
                }
            });
        });
        
        // Mark all present
        document.getElementById('markAllPresent').addEventListener('click', function() {
            statusSelects.forEach(select => {
                select.value = 'present';
                const reasonInput = select.closest('tr').querySelector('.reason-input');
                reasonInput.disabled = true;
                reasonInput.value = '';
            });
        });
        
        // Mark all absent
        document.getElementById('markAllAbsent').addEventListener('click', function() {
            statusSelects.forEach(select => {
                select.value = 'absent';
                const reasonInput = select.closest('tr').querySelector('.reason-input');
                reasonInput.disabled = false;
            });
        });
        
        // Search functionality
        const searchInput = document.getElementById('searchStudent');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('.student-row');
                
                rows.forEach(row => {
                    const name = row.cells[1].textContent.toLowerCase();
                    const regNumber = row.cells[0].textContent.toLowerCase();
                    
                    if (name.includes(searchTerm) || regNumber.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    });
    </script>
</body>
</html>
