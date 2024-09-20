<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['categoryName'];



    $checkStmt = $_db->prepare("SELECT COUNT(*) FROM category WHERE categoryname = ?");
    $checkStmt->execute([$categoryName]);
    $existingCategoryCount = $checkStmt->fetchColumn();

    if ($existingCategoryCount > 0) {
        echo "This category already exists!";
        exit();
    }



    $stmt = $_db->prepare("INSERT INTO category (categoryName) VALUES (?)");
    $stmt->execute([$categoryName]);

    echo "Category added successfully.";
} else {
    
    foreach ($_err as $error) {
        echo "$error";
    }
}

?>