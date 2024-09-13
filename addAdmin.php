<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $status = $_POST['status'];
    

    // Check if the username already exists (optional but good practice)
    $checkStmt = $_db->prepare("SELECT COUNT(*) FROM user WHERE role='admin' and username = ?");
    $checkStmt->execute([$username]);
    $existingAdminCount = $checkStmt->fetchColumn();

    if ($existingAdminCount > 0) {
        echo "Username already exists!";
        exit();
    }

     // Retrieve the uploaded image
     $f = get_file('image');
     $_err = [];
 
     // Image validation
     if ($f == null) {
         $_err['image'] = 'Image is required';
     } else if (!str_starts_with(mime_content_type($f->tmp_name), 'image/')) {
         $_err['image'] = 'The file must be an image';
     } else if ($f->size > 1 * 1024 * 1024) { // Limit image size to 1MB
         $_err['image'] = 'Maximum image size is 1MB';
     }
 
     if (!$_err) {
         // Move the uploaded image to the "images" directory
         $imageExtension = pathinfo($f->name, PATHINFO_EXTENSION);
         $newFileName = uniqid() . '.' . $imageExtension; // Unique file name
 
         $destination = "images/$newFileName"; // Save location
 
         if (move_uploaded_file($f->tmp_name, $destination)) {
             // Use SimpleImage to create a thumbnail
             require_once 'lib/SimpleImage.php';
             $img = new SimpleImage();
             $img->fromFile($destination) // Load the uploaded image
                 ->thumbnail(200, 200) // Create a 200x200 thumbnail
                 ->toFile("images/$newFileName", 'image/jpeg'); // Save the thumbnail
 
             temp('info', 'Photo uploaded successfully.');
 
             // Insert form data including the image name into the database
             $stmt = $_db->prepare("INSERT INTO user (username,fullname, email, password,role, status, photo) VALUES (?,?,?, ?, ?, ?, ?)");
             $stmt->execute([$username, $fullname, $email, $password,'Admin', $status, $newFileName]);
 
             echo "Admin added successfully.";
 
         } else {
             echo "Failed to move the uploaded file.";
         }
     } else {
         // Display validation errors
         foreach ($_err as $error) {
             echo "$error";
         }
     }
  
}
?>
