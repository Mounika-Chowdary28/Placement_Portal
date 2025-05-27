<?php
// Read the jobs.php file
$content = file_get_contents('jobs.php');

// Replace the settings link list item
$pattern = '/<li class="nav-item">\s*<a class="nav-link" href="settings\.php">\s*<i class="fas fa-cog me-2"><\/i>\s*Settings\s*<\/a>\s*<\/li>/';
$replacement = '';
$content = preg_replace($pattern, $replacement, $content);

// Write the modified content back to the file
file_put_contents('jobs.php', $content);

echo "Settings link removed successfully!";
?> 