<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $birthday = $_POST['birthday'];
    $email = $_POST['email'];
    $status = $_POST['status'];
    $currentPhoto = $_POST['photo']; // Existing photo in the database

    $f = get_file('image');
    $_err = [];

 
    if ($f == null) {
        // No new image, use the current photo
        $photo = $currentPhoto;
    } else {
        // Validate the image
        if (!str_starts_with(mime_content_type($f->tmp_name), 'image/')) {
            $_err['image'] = 'The file must be an image';
        } else if ($f->size > 1 * 1024 * 1024) { // Limit image size to 1MB
            $_err['image'] = 'Maximum image size is 1MB';
        } else {
            // Generate a unique file name and move the uploaded image
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

                $photo = $newFileName; // Use new photo filename
                temp('info', 'Photo uploaded successfully.');
            } else {
                $_err['image'] = 'Failed to move the uploaded file';
            }
        }
    }

    // If there are validation errors, display them
    if (!empty($_err)) {
        foreach ($_err as $error) {
            echo "<p>$error</p>";
        }
    } else {
        // Update the database with the new data (or the same photo if not changed)
        $stmt = $_db->prepare("UPDATE member SET username = ?,fullname=?, email = ?,birthday=?, status = ?, photo = ? WHERE id = ?");
        if ($stmt->execute([$username,$fullname, $email, $birthday, $status, $photo, $id])) {
            echo "Member updated successfully.";
        } else {
            echo "Failed to update member.";
        }
    }
}
?>
