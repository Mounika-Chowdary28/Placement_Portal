<?php
// Create a folder for profile pictures if it doesn't exist
$upload_dir = 'uploads/profile_pics/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Since GD library is not available, we'll create a simple text file with instructions
$instructions = <<<EOT
This is a placeholder file. 

To add a default profile image:
1. Find a default profile image online (a simple silhouette or avatar)
2. Save it as "default.jpg" in this directory (uploads/profile_pics/)
3. The image should be square (e.g. 200x200 pixels)

Once added, the default profile image will be used for users who haven't uploaded their own profile picture.
EOT;

file_put_contents($upload_dir . 'README.txt', $instructions);

echo "Please add a default profile image manually. See " . $upload_dir . "README.txt for instructions.";

// For now, let's create an empty default.jpg file so the application doesn't break
touch($upload_dir . 'default.jpg');
?> 