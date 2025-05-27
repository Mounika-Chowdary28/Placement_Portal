<?php
/**
 * Common utility functions for Practice Pro
 */

// Make sure this file is included only once
if (!defined('FUNCTIONS_INCLUDED')) {
    define('FUNCTIONS_INCLUDED', true);

    /**
     * Sanitize user input to prevent XSS attacks
     * 
     * @param string $data Input data to sanitize
     * @return string Sanitized data
     */
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }

    /**
     * Sanitize user input for database operations
     * Combines XSS protection with SQL injection protection
     * 
     * @param string $data Input data to sanitize
     * @param mysqli $conn Database connection object
     * @return string Fully sanitized data
     */
    function sanitize_db_input($data, $conn) {
        $data = sanitize_input($data);
        $data = mysqli_real_escape_string($conn, $data);
        return $data;
    }

    /**
     * Escape output data to prevent XSS in HTML output
     * 
     * @param string $data The data to be escaped
     * @return string Escaped data safe for HTML output
     */
    function escape_output($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Redirect to a specific page
     * 
     * @param string $location URL to redirect to
     * @return void
     */
    function redirect($location) {
        header("Location: $location");
        exit;
    }

    /**
     * Check if user is logged in
     * 
     * @return bool True if user is logged in, false otherwise
     */
    function is_logged_in() {
        return isset($_SESSION['user_id']);
    }

    /**
     * Check if user has admin role
     * 
     * @return bool True if user is admin, false otherwise
     */
    function is_admin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }

    /**
     * Get current user ID
     * 
     * @return int|null User ID if logged in, null otherwise
     */
    function get_user_id() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }

    /**
     * Generate a random string
     * 
     * @param int $length Length of the random string
     * @return string Random string
     */
    function generate_random_string($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $random_string = '';
        for ($i = 0; $i < $length; $i++) {
            $random_string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $random_string;
    }

    /**
     * Format date for display
     * 
     * @param string $date Date in MySQL format (YYYY-MM-DD)
     * @return string Formatted date (e.g., January 1, 2023)
     */
    function format_date($date) {
        return date('F j, Y', strtotime($date));
    }

    /**
     * Check if a value exists in database
     * 
     * @param mysqli $conn Database connection
     * @param string $table Table name
     * @param string $column Column name
     * @param mixed $value Value to check
     * @return bool True if value exists, false otherwise
     */
    function value_exists($conn, $table, $column, $value) {
        $sql = "SELECT COUNT(*) as count FROM $table WHERE $column = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $value);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        
        return $row['count'] > 0;
    }

    /**
     * Log system activities
     * 
     * @param string $message Message to log
     * @param string $level Log level (info, warning, error)
     * @return void
     */
    function log_activity($message, $level = 'info') {
        $log_dir = 'logs';
        if (!file_exists($log_dir)) {
            mkdir($log_dir, 0755, true);
        }
        
        $log_file = $log_dir . '/activity_' . date('Y-m-d') . '.log';
        $timestamp = date('Y-m-d H:i:s');
        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'guest';
        
        $log_entry = "[$timestamp][$level][User:$user_id] $message" . PHP_EOL;
        file_put_contents($log_file, $log_entry, FILE_APPEND);
    }

    /**
     * Function to check if user is logged in and redirect if not
     * 
     * @param string $redirect_url URL to redirect to if not logged in
     * @return void
     */
    function require_login($redirect_url = 'login.php') {
        if (!is_logged_in()) {
            redirect($redirect_url);
        }
    }

    /**
     * Generate CSRF token and store in session
     * 
     * @return string Generated CSRF token
     */
    function generate_csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     * 
     * @param string $token Token to verify
     * @return bool True if token is valid, false otherwise
     */
    function verify_csrf_token($token) {
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            return false;
        }
        return true;
    }

    /**
     * Parse profile data from JSON
     * 
     * @param string $jsonData The JSON string containing profile data
     * @return array Parsed profile data array with all sections
     */
    function parse_profile_data($jsonData) {
        // Initialize the return array with empty sub-arrays
        $profileData = [
            'user_id' => null,
            'name' => null,
            'email' => null,
            'phone' => null,
            'branch' => null,
            'semester' => null,
            'cgpa' => null,
            'bio' => null,
            'address' => null,
            'profile_image' => 'default.jpg',
            'skills' => [],
            'education' => [],
            'experience' => [],
            'projects' => [],
            'certifications' => [],
            'applications' => [],
            'notifications' => [],
            'recommendedSkills' => []
        ];
        
        // Decode JSON
        $data = json_decode($jsonData, true);
        
        // Return empty array if JSON is invalid
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('JSON parsing error: ' . json_last_error_msg());
            return $profileData;
        }
        
        // Map basic profile fields
        $basicFields = [
            'user_id', 'name', 'email', 'phone', 'branch', 
            'semester', 'cgpa', 'bio', 'address', 'profile_image'
        ];
        
        foreach ($basicFields as $field) {
            if (isset($data[$field])) {
                $profileData[$field] = $data[$field];
            }
        }
        
        // Map array fields
        $arrayFields = [
            'skills', 'education', 'experience', 'projects', 
            'certifications', 'applications', 'notifications', 'recommendedSkills'
        ];
        
        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && is_array($data[$field])) {
                $profileData[$field] = $data[$field];
            }
        }
        
        // Calculate additional metrics
        $profileData['profile_completion'] = calculate_profile_completion($profileData);
        $profileData['unread_notifications'] = count_unread_notifications($profileData['notifications']);
        
        return $profileData;
    }

    /**
     * Calculate profile completion percentage
     * 
     * @param array $profileData The profile data array
     * @return int Profile completion percentage
     */
    function calculate_profile_completion($profileData) {
        $totalFields = 8; // Basic fields that should be filled
        $filledFields = 0;
        
        // Check basic fields
        $checkFields = ['name', 'email', 'phone', 'branch', 'semester', 'cgpa', 'bio', 'profile_image'];
        foreach ($checkFields as $field) {
            if (!empty($profileData[$field]) && $profileData[$field] !== 'default.jpg') {
                $filledFields++;
            }
        }
        
        // Check arrays - give partial credit for having at least one entry
        $arrayFields = ['skills', 'education', 'experience', 'projects', 'certifications'];
        $arrayWeight = 0.5; // Each array counts as half a field
        
        foreach ($arrayFields as $field) {
            if (!empty($profileData[$field])) {
                $filledFields += $arrayWeight;
                $totalFields += $arrayWeight;
            }
        }
        
        return ($filledFields / $totalFields) * 100;
    }

    /**
     * Count unread notifications
     * 
     * @param array $notifications Array of notification objects
     * @return int Number of unread notifications
     */
    function count_unread_notifications($notifications) {
        $count = 0;
        
        foreach ($notifications as $notification) {
            if (isset($notification['status']) && $notification['status'] === 'unread') {
                $count++;
            }
        }
        
        return $count;
    }

    /**
     * Get the user's profile image URL
     * 
     * @param string|null $profileImage The profile image path if set
     * @return string The complete URL to the profile image
     */
    function get_profile_image($profileImage = null) {
        if (!empty($profileImage) && file_exists($profileImage)) {
            return $profileImage;
        }
        
        // Return default profile image
        return "assets/images/default-profile.jpg";
    }

    /**
     * Calculate student profile completion percentage
     * 
     * @param array $student Student data array
     * @return int Completion percentage (0-100)
     */
    function calculate_student_completion($student) {
        $total_fields = 10; // Basic fields we're counting
        $filled_fields = 0;
        
        // Basic profile fields
        $check_fields = ['full_name', 'email', 'phone', 'department', 'degree', 'year', 'cgpa', 'profile_image', 'bio', 'address'];
        
        foreach ($check_fields as $field) {
            if (isset($student[$field]) && !empty($student[$field]) && $student[$field] != 'default.jpg') {
                $filled_fields++;
            }
        }
        
        // Calculate percentage
        $completion = round(($filled_fields / $total_fields) * 100);
        return $completion > 100 ? 100 : $completion;
    }
}
?> 