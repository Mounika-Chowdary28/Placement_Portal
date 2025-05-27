<?php
require_once 'config.php';

// Clear any existing session data if coming to the login page directly
if (!isset($_POST['username']) && !isset($_POST['password'])) {
    session_unset();
    session_destroy();
    session_start();
}

// Check if user is already logged in
if (is_logged_in()) {
    redirect('home.php');
}

$error = '';

// Process login form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reg_number = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    // Validate registration number format
    if (!preg_match('/^AP\d{11}$/', $reg_number)) {
        $error = "Invalid Registration Number. It must start with 'AP' followed by 11 digits.";
    } else {
        // Check if user exists
        $reg_number = sanitize_db_input($reg_number, $conn);
        $sql = "SELECT id, reg_number, password, full_name, profile_pic FROM users WHERE reg_number = '$reg_number'";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            
            // Verify password (DOB in dd-mm-yyyy format)
            if (password_verify($password, $user['password'])) {
                // Password is correct, start a new session
                session_start();
                
                // Store data in session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['reg_number'] = $user['reg_number'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['profile_pic'] = $user['profile_pic'];
                
                // Log successful login
                log_activity("User logged in: " . $user['reg_number'], "info");
                
                // Redirect to home page
                redirect('home.php');
            } else {
                $error = "Invalid password. Please try again.";
                log_activity("Failed login attempt for user: " . $reg_number . " (Invalid password)", "warning");
            }
        } else {
            $error = "User not found. Please check your registration number.";
            log_activity("Failed login attempt with non-existent registration number: " . $reg_number, "warning");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Placement Portal</title>
    <style>
         body {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
    background-color: #f4f4f4;
    position: relative;
    overflow: hidden;
    background-image: url("image.png");
}

body::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url("image.png");
    background-size: cover;
    background-position: center;
    filter: blur(1.5px); /* Adjust blur level as needed */
    z-index: -1;
}
        .login-card {
            background-color: #f8f8f8;
            border-radius: 10px;
            box-sizing: border-box;
            padding: 20px;
            max-width: 600px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
            height:400px;
            width:400px;
        }
        .card-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .card-header h1 {
            font-size: 2em;
            color: #333;
            text-shadow: 1px 1px #ddd;
        }
        .form-group {
            margin-bottom: 10px;
        }
        .form-group label {
            display: block;
            font-size: 1.2em;
            color: #555;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="password"] {
            box-sizing: border-box;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background-color: #f0f0f0;
            font-size: 1.2em;
            color: #555;
            box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
            width: 100%;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            box-shadow: inset 0px 2px 5px rgba(0, 0, 0, 0.3);
            outline: none;
        }
        .login-button {
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 1.2em;
            padding: 10px;
            width: 100%;
            transition: background-color 0.3s ease;
            margin-top: 35px;
        }
        .login-button:hover {
            background-color: #0069d9;
            box-shadow: 0px 0px 30px 0px rgba(0,105,217,1);
        }
        .error-message {
            color: #dc3545;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="card-header">
          <h1>Placement Portal</h1>
        </div>
        <div class="card-body">
          <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
              <label for="username">Registration Number</label>
              <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <input type="password" id="password" name="password" required>
            </div>
            <?php if ($error): ?>
                <div class="error-message"><?php echo escape_output($error); ?></div>
            <?php endif; ?>
            <div class="form-group" div="log">
              <button type="submit" class="login-button">Login</button>
            </div>
          </form>
        </div>
    </div>
    <script>
        // Client-side validation
        document.querySelector('form').addEventListener('submit', function(event) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            
            // Validate registration number
            const regPattern = /^AP\d{11}$/;
            if (!regPattern.test(username)) {
                alert("Invalid Registration Number. It must start with 'AP' followed by 11 digits.");
                event.preventDefault();
                return;
            }
            
            // Validate password format (dd-mm-yyyy)
            const passPattern = /^(0[1-9]|[12][0-9]|3[01])-(0[1-9]|1[0-2])-(\d{4})$/;
            if (!passPattern.test(password)) {
                alert("Invalid Password. It must be in 'dd-mm-yyyy' format.");
                event.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>