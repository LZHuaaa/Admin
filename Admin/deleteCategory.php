<?php
require 'base.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $_db->prepare("DELETE FROM category WHERE categoryID = ?");
    if ($stmt->execute([$id])) {
        echo "Success";

    } else {
        echo "Error deleting record from database.";
    }
}
