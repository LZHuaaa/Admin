<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
    $status = $_POST['status'];
    

    
    $checkStmt = $_db->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
    $checkStmt->execute([$username]);
    $existingAdminCount = $checkStmt->fetchColumn();

    if ($existingAdminCount > 0) {
        echo "Email already exists!";
        exit();
    }

   
     $f = get_file('image');
     $_err = [];
 

     if ($f == null) {
         $_err['image'] = 'Image is required';
     } else if (!str_starts_with(mime_content_type($f->tmp_name), 'image/')) {
         $_err['image'] = 'The file must be an image';
     } else if ($f->size > 1 * 1024 * 1024) { 
         $_err['image'] = 'Maximum image size is 1MB';
     }
 



         if (!$_err) {
    
            $imageExtension = strtolower(pathinfo($f->name, PATHINFO_EXTENSION));
            $newFileName = uniqid() . '.' . $imageExtension; 
            $destination = "../images/$newFileName";  
 
         if (move_uploaded_file($f->tmp_name, $destination)) {
 
             temp('info', 'Photo uploaded successfully.');
 
             $stmt = $_db->prepare("INSERT INTO user (username,fullname, email, password,role, status, photo) VALUES (?,?,?, ?, ?, ?, ?)");
             $stmt->execute([$username, $fullname, $email, $password,'Admin', $status, $newFileName]);
 
             echo "Admin added successfully.";
 
         } else {
             echo "Failed to move the uploaded file.";
         }
     } else {
    
         foreach ($_err as $error) {
             echo "$error";
         }
     }
  
}
?>
