<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "placement_portal";

// Create database connection
$conn = mysqli_connect($host, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include functions file
require_once 'functions.php';
?>