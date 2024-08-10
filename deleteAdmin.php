<?php
require 'base.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Delete admin based on ID
    $stmt = $_db->prepare("DELETE FROM admin WHERE id = ?");
    if ($stmt->execute([$id])) {
        echo "Success";
    } else {
        echo "Error";
    }
}
?>
