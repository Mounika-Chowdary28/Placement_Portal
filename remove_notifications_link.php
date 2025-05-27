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

// The pattern to find and remove - looking for the entire <li> element containing "View all notifications"
$search = '<li><a class="dropdown-item text-center small" href="notifications.php">View all notifications</a></li>';

// Track changes
$total_changes = 0;
$changed_files = [];

// Process each file
foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $original_content = $content;
        
        // Remove the "View all notifications" line
        $content = str_replace($search, '', $content);
        
        // If changes were made, save the file
        if ($content !== $original_content) {
            file_put_contents($file, $content);
            $total_changes++;
            $changed_files[] = $file;
            echo "Removed 'View all notifications' link from $file\n";
        } else {
            echo "No changes needed in $file\n";
        }
    } else {
        echo "File not found: $file\n";
    }
}

echo "\nCompleted! Modified $total_changes files: " . implode(", ", $changed_files);
?> 