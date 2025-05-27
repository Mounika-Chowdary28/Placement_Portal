<?php
require_once 'config.php';
require_once 'functions.php';

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Log the logout activity if user was logged in
if (is_logged_in()) {
    log_activity("User logged out: " . $_SESSION['reg_number'], "info");
}

// Destroy the session
session_unset();
session_destroy();

// Redirect to login page
redirect('index.php');
?>