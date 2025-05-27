<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Add New Notification</h3>
            </div>
            <div class="card-body">
                <?php
                // Process form submission
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    // Database connection
                    require_once 'config.php';

                    // Get form data
                    $user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
                    $title = isset($_POST['title']) ? trim($_POST['title']) : '';
                    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
                    $send_to_all = isset($_POST['send_to_all']) ? (bool)$_POST['send_to_all'] : false;
                    $created_at = date('Y-m-d H:i:s');
                    $is_read = 0;

                    // Validate input
                    $errors = [];
                    if (empty($title)) {
                        $errors[] = 'Title is required';
                    }
                    if (empty($message)) {
                        $errors[] = 'Message is required';
                    }
                    if (!$send_to_all && $user_id <= 0) {
                        $errors[] = 'User ID is required if not sending to all users';
                    }

                    // Display errors if any
                    if (!empty($errors)) {
                        echo '<div class="alert alert-danger">';
                        echo '<ul>';
                        foreach ($errors as $error) {
                            echo '<li>' . htmlspecialchars($error) . '</li>';
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        // No errors, proceed with adding notification(s)
                        if ($send_to_all) {
                            // Get all user IDs
                            $user_query = "SELECT id FROM users";
                            $user_result = mysqli_query($conn, $user_query);
                            
                            if (!$user_result) {
                                echo '<div class="alert alert-danger">Error fetching users: ' . mysqli_error($conn) . '</div>';
                            } else {
                                // Prepare statement
                                $insert_query = "INSERT INTO notifications (user_id, title, message, created_at, is_read) VALUES (?, ?, ?, ?, ?)";
                                $stmt = mysqli_prepare($conn, $insert_query);
                                
                                if (!$stmt) {
                                    echo '<div class="alert alert-danger">Error preparing statement: ' . mysqli_error($conn) . '</div>';
                                } else {
                                    mysqli_stmt_bind_param($stmt, "isssi", $user_id, $title, $message, $created_at, $is_read);
                                    
                                    $success_count = 0;
                                    while ($user = mysqli_fetch_assoc($user_result)) {
                                        $user_id = $user['id'];
                                        
                                        if (mysqli_stmt_execute($stmt)) {
                                            $success_count++;
                                        }
                                    }
                                    
                                    echo '<div class="alert alert-success">Successfully added notification for ' . $success_count . ' users.</div>';
                                    mysqli_stmt_close($stmt);
                                }
                            }
                        } else {
                            // Add notification for a specific user
                            $insert_query = "INSERT INTO notifications (user_id, title, message, created_at, is_read) VALUES (?, ?, ?, ?, ?)";
                            $stmt = mysqli_prepare($conn, $insert_query);
                            
                            if (!$stmt) {
                                echo '<div class="alert alert-danger">Error preparing statement: ' . mysqli_error($conn) . '</div>';
                            } else {
                                mysqli_stmt_bind_param($stmt, "isssi", $user_id, $title, $message, $created_at, $is_read);
                                
                                if (mysqli_stmt_execute($stmt)) {
                                    echo '<div class="alert alert-success">Notification added successfully for user ID ' . $user_id . '.</div>';
                                } else {
                                    echo '<div class="alert alert-danger">Error adding notification: ' . mysqli_error($conn) . '</div>';
                                }
                                
                                mysqli_stmt_close($stmt);
                            }
                        }
                    }
                }
                ?>
                
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Notification Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="message" class="form-label">Notification Message</label>
                        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="send_to_all" name="send_to_all" value="1">
                        <label class="form-check-label" for="send_to_all">Send to all users</label>
                    </div>
                    
                    <div class="mb-3" id="user_id_container">
                        <label for="user_id" class="form-label">User ID</label>
                        <input type="number" class="form-control" id="user_id" name="user_id" min="1">
                        <div class="form-text">Only needed if not sending to all users</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Add Notification</button>
                    <a href="home.php" class="btn btn-secondary">Back to Home</a>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Show/hide user ID field based on send to all checkbox
        document.getElementById('send_to_all').addEventListener('change', function() {
            document.getElementById('user_id_container').style.display = this.checked ? 'none' : 'block';
        });
    </script>
</body>
</html> 