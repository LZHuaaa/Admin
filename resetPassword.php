<?php
require 'base.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adminId = $_POST['id'];
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // Ensure passwords match
    if ($newPassword !== $confirmPassword) {
        echo "Passwords do not match.";
        exit;
    }

    // Validate password (min 7 characters, 1 capital letter, 1 special char)
    $passwordPattern = '/^(?=.*[A-Z])(?=.*[\W_]).{7,}$/';
    if (!preg_match($passwordPattern, $newPassword)) {
        echo "Password must be at least 7 characters long, contain at least one capital letter, and one special character.";
        exit;
    }

    // Encrypt password
    $encryptedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password in the database
    $stmt = $_db->prepare("UPDATE admin SET password = ? WHERE id = ?");
    $stmt->execute([$encryptedPassword, $adminId]);

    if ($stmt->rowCount() > 0) {
        echo "Password updated successfully.";
    } else {
        echo "Error updating password.";
    }
} else {
    echo "Invalid request method.";
}
