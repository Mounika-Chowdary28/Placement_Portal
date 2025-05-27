<?php
// List of files to update
$files = [
    'jobs.php',
    'home.php',
    'applications.php',
    'profile.php',
    'resume.php',
    'schedule.php',
    'notifications.php'
];

// The text to find and replace
$search = 'Resume Testing';
$replace = 'Resume Upload';

// Track changes
$total_changes = 0;
$changed_files = [];

// Process each file
foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $original_content = $content;
        
        // Replace the text
        $content = str_replace($search, $replace, $content);
        
        // If changes were made, save the file
        if ($content !== $original_content) {
            file_put_contents($file, $content);
            $total_changes++;
            $changed_files[] = $file;
            echo "Updated '$search' to '$replace' in $file\n";
        } else {
            echo "No changes needed in $file\n";
        }
    } else {
        echo "File not found: $file\n";
    }
}

echo "\nCompleted! Modified $total_changes files: " . implode(", ", $changed_files);
?> 