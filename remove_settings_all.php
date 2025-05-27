<?php
// List of PHP files to process
$files = [
    'jobs.php',
    'home.php',
    'applications.php',
    'profile.php',
    'resume.php',
    'schedule.php',
    'notifications.php'
];

// Settings link pattern
$pattern = '/<li class="nav-item">\s*<a class="nav-link" href="settings\.php">\s*<i class="fas fa-cog me-2"><\/i>\s*Settings\s*<\/a>\s*<\/li>/';
$replacement = '';

// Dropdown menu pattern
$dropdown_pattern = '/<li><a class="dropdown-item" href="settings\.php">Settings<\/a><\/li>/';
$dropdown_replacement = '';

// Count changes
$total_changes = 0;

// Process each file
foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $original_content = $content;
        
        // Replace nav menu item
        $content = preg_replace($pattern, $replacement, $content);
        
        // Replace dropdown menu item
        $content = preg_replace($dropdown_pattern, $dropdown_replacement, $content);
        
        // If changes were made, save the file
        if ($content !== $original_content) {
            file_put_contents($file, $content);
            $total_changes++;
            echo "Removed settings links from $file\n";
        } else {
            echo "No changes needed in $file\n";
        }
    } else {
        echo "File not found: $file\n";
    }
}

echo "\nCompleted! Modified $total_changes files.";
?> 