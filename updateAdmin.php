<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    $stmt = $_db->prepare("UPDATE admin SET username = ?, role = ?, status = ? WHERE id = ?");
    if ($stmt->execute([$username, $role, $status, $id])) {
        echo 'success';
    } else {
        echo 'error';
    }
}
?>
