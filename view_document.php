<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if file parameter is provided
if (!isset($_GET['file']) || empty($_GET['file'])) {
    die("No file specified");
}

$file_path = '../' . $_GET['file'];

// Check if file exists
if (!file_exists($file_path)) {
    die("File not found");
}

// Get file information
$file_info = pathinfo($file_path);
$extension = strtolower($file_info['extension']);

// Set appropriate content type based on file extension
switch ($extension) {
    case 'pdf':
        $content_type = 'application/pdf';
        break;
    case 'doc':
    case 'docx':
        $content_type = 'application/msword';
        break;
    default:
        $content_type = 'application/octet-stream';
}

// Set headers to display the file in the browser
header('Content-Type: ' . $content_type);
header('Content-Disposition: inline; filename="' . basename($file_path) . '"');
header('Cache-Control: public, max-age=0');
header('Content-Length: ' . filesize($file_path));

// Output the file
readfile($file_path);
exit;
?> 