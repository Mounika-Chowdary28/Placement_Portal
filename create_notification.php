<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Notification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <?php
        // Database connection
        require_once 'config.php';

        // Check connection
        if (!$conn) {
            die('<div class="alert alert-danger">Connection failed: ' . mysqli_connect_error() . '</div>');
        }

        // Get all user IDs
        $user_query = "SELECT id FROM users";
        $user_result = mysqli_query($conn, $user_query);

        if (!$user_result) {
            die('<div class="alert alert-danger">Error fetching users: ' . mysqli_error($conn) . '</div>');
        }

        // Notification content
        $title = "Resume Upload Feature Available";
        $message = "You can now upload your resume through the Resume Upload section. A well-formatted resume increases your chances of getting selected for interviews.";
        $created_at = date('Y-m-d H:i:s');
        $is_read = 0;

        // Counter for successful insertions
        $success_count = 0;

        // Prepare the insert statement
        $insert_query = "INSERT INTO notifications (user_id, title, message, created_at, is_read) VALUES (?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insert_query);

        if (!$stmt) {
            die('<div class="alert alert-danger">Error preparing statement: ' . mysqli_error($conn) . '</div>');
        }

        mysqli_stmt_bind_param($stmt, "isssi", $user_id, $title, $message, $created_at, $is_read);

        // Add notification for each user
        while ($user = mysqli_fetch_assoc($user_result)) {
            $user_id = $user['id'];
            
            if (mysqli_stmt_execute($stmt)) {
                $success_count++;
            } else {
                echo '<div class="alert alert-warning">Error adding notification for user ID ' . $user_id . ': ' . mysqli_error($conn) . '</div>';
            }
        }
        ?>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="my-2">Notification Creation Complete</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-success">
                    Successfully added notification for <?php echo $success_count; ?> users.
                </div>
                
                <h5>Notification Details:</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Title</th>
                        <td><?php echo $title; ?></td>
                    </tr>
                    <tr>
                        <th>Message</th>
                        <td><?php echo $message; ?></td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td><?php echo $created_at; ?></td>
                    </tr>
                </table>
                
                <a href="home.php" class="btn btn-primary">Return to Home</a>
            </div>
        </div>
    </div>

    <?php
    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    ?>
</body>
</html> 