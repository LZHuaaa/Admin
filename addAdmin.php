<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Encrypt password
    $role = $_POST['role'];
    $status = $_POST['status'];

    // Insert into the database
    $stmt = $_db->prepare("INSERT INTO admin (username, password, role, status) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$username, $password, $role, $status])) {
        echo 'Admin added successfully!';
    } else {
        echo 'Error adding admin.';
    }
}
?>
