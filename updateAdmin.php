<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    //$status = $_POST['status'];
    $currentPhoto = $_POST['photo'];
    //$newPassword = $_POST['password'];
    //$confirmPassword = $_POST['confirmPassword'];

    /*if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match.";
        exit;
    }

    $passwordPattern = '/^(?=.*[A-Z])(?=.*[\W_]).{7,}$/';
    if (!preg_match($passwordPattern, $newPassword)) {
        echo "Password must be at least 7 characters long, contain at least one capital letter, and one special character.";
        exit;
    }

    $encryptedPassword = password_hash($newPassword, PASSWORD_BCRYPT);*/

    $f = get_file('image');
    $_err = [];


    if ($f == null) {

        $photo = $currentPhoto;
    } else {

        if (!str_starts_with(mime_content_type($f->tmp_name), 'image/')) {
            $_err['image'] = 'The file must be an image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['image'] = 'Maximum image size is 1MB';
        } else {

            $imageExtension = pathinfo($f->name, PATHINFO_EXTENSION);
            $newFileName = uniqid() . '.' . $imageExtension;
            $destination = "images/$newFileName";

            if (move_uploaded_file($f->tmp_name, $destination)) {

                require_once 'lib/SimpleImage.php';
                $img = new SimpleImage();
                $img->fromFile($destination)
                    ->thumbnail(200, 200)
                    ->toFile("images/$newFileName", 'image/jpeg');

                $photo = $newFileName;
                temp('info', 'Photo uploaded successfully.');
            } else {
                $_err['image'] = 'Failed to move the uploaded file';
            }
        }
    }


    if (!empty($_err)) {
        foreach ($_err as $error) {
            echo "<p>$error</p>";
        }
    } else {
        $stmt = $_db->prepare("UPDATE user SET username = ?,fullname=?, email = ?,  photo = ? WHERE userid = ?");
        if ($stmt->execute([$username, $fullname, $email, $photo, $id])) {
            echo "Admin updated successfully.";
        } else {
            echo "Failed to update admin.";
        }
    }
}
