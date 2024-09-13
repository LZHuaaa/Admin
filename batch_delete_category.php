<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {

    $ids = $_POST['ids'];

    $ids = array_map('intval', $ids);

    $idsString = implode(',', $ids);

    $deleteStmt = $_db->prepare("DELETE FROM category WHERE categoryID IN ($idsString)");
    if ($deleteStmt->execute()) {

        echo "Deleted Successfully";
    } else {
        echo "Error deleting records from the database.";
    }
} else {
    echo "Invalid request.";
}
