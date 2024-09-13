<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['categoryName'];


    // Check if the username already exists (optional but good practice)
    $checkStmt = $_db->prepare("SELECT COUNT(*) FROM category WHERE categoryname = ?");
    $checkStmt->execute([$categoryName]);
    $existingCategoryCount = $checkStmt->fetchColumn();

    if ($existingCategoryCount > 0) {
        echo "This category already exists!";
        exit();
    }


    // Insert form data including the image name into the database
    $stmt = $_db->prepare("INSERT INTO category (categoryName) VALUES (?)");
    $stmt->execute([$categoryName]);

    echo "Category added successfully.";
} else {
    // Display validation errors
    foreach ($_err as $error) {
        echo "$error";
    }
}

?>