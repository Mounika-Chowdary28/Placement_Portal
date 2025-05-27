<?php
// Test upload directory
$upload_dir = 'uploads/profile_pics/';

// Test results
$results = [
    'Directory exists' => file_exists($upload_dir) ? 'Yes' : 'No',
    'Directory is writable' => is_writable($upload_dir) ? 'Yes' : 'No',
    'Default image exists' => file_exists($upload_dir . 'default.jpg') ? 'Yes' : 'No',
    'Default image size' => file_exists($upload_dir . 'default.jpg') ? filesize($upload_dir . 'default.jpg') . ' bytes' : 'N/A',
    'File uploads enabled' => ini_get('file_uploads') ? 'Yes' : 'No',
    'Upload max filesize' => ini_get('upload_max_filesize'),
    'Post max size' => ini_get('post_max_size'),
    'Upload tmp dir' => ini_get('upload_tmp_dir') ?: 'System default'
];

// Display results
echo "<html><head><title>Upload Test</title></head><body>";
echo "<h2>Upload System Test</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Test</th><th>Result</th></tr>";

foreach ($results as $test => $result) {
    echo "<tr><td>$test</td><td>$result</td></tr>";
}

echo "</table>";

// Test form
echo "<h2>Test File Upload</h2>";
echo "<form action='upload_test.php' method='post' enctype='multipart/form-data'>";
echo "<input type='file' name='test_file'><br><br>";
echo "<input type='submit' value='Upload Test File'>";
echo "</form>";

// Process test upload
if (isset($_FILES['test_file']) && $_FILES['test_file']['error'] == 0) {
    echo "<h3>Upload Results:</h3>";
    echo "<pre>";
    print_r($_FILES['test_file']);
    echo "</pre>";
    
    $test_filename = 'test_' . time() . '_' . $_FILES['test_file']['name'];
    if (move_uploaded_file($_FILES['test_file']['tmp_name'], $upload_dir . $test_filename)) {
        echo "<p style='color:green'>Upload successful! File saved as: " . $upload_dir . $test_filename . "</p>";
    } else {
        echo "<p style='color:red'>Upload failed! Error: " . error_get_last()['message'] . "</p>";
    }
}

echo "</body></html>";
?> 