<?php
require 'base.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['id'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];


    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match.";
        exit;
    }

    
    $passwordPattern = '/^(?=.*[A-Z])(?=.*[\W_]).{7,}$/';
    if (!preg_match($passwordPattern, $newPassword)) {
        echo "Password must be at least 7 characters long, contain at least one capital letter, and one special character.";
        exit;
    }

    $encryptedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

   
    $stmt = $_db->prepare("UPDATE user SET password = ? WHERE userid = ?");
    $stmt->execute([$encryptedPassword, $adminId]);

    if ($stmt->rowCount() > 0) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password.";
    }
} else {
    echo "Invalid request method.";
}
