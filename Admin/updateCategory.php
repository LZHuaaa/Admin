<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $categoryName = $_POST['categoryName'];
    $_err = [];


    if (!empty($_err)) {
        foreach ($_err as $error) {
            echo "<p>$error</p>";
        }
    } else {
        // Update the database with the new data (or the same photo if not changed)
        $stmt = $_db->prepare("UPDATE category SET categoryName = ? WHERE categoryid = ?");
        if ($stmt->execute([$categoryName, $id])) {
            echo "Category updated successfully.";
        } else {
            echo "Failed to update category.";
        }
    }
}
