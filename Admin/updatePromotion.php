<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['promotionid'];
    $promotionName = $_POST['promotionName'];
    $discountRate = $_POST['discountRate'];
    $description = $_POST['promotionDescription'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $promoCode = $_POST['promo_code'];
    $status = $_POST['status'];
    $currentImage = $_POST['imagePath'];

    $_err = [];


    if (empty($_FILES['image']['name'])) {

        $imagePath = $currentImage;
    } else {

        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['image']['tmp_name'];
            $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
            $destination = "../images/$imageName";


            if (move_uploaded_file($imageTmpPath, $destination)) {
                $imagePath = $imageName;


                $oldImagePath = "../images/" . $currentImage;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            } else {
                $_err[] = "Failed to upload the image.";
            }
        } else {
            $_err[] = "Error uploading image. Error Code: " . $_FILES['image']['error'];
        }
    }

    if (empty($_err)) {
        $stmt = $_db->prepare("
            UPDATE promotion 
            SET name = ?, description = ?, discountRate = ?, promo_code = ?, status = ?, startDate = ?, endDate = ?, promotionimage = ? 
            WHERE promotionid = ?
        ");

        if ($stmt->execute([$promotionName, $description, $discountRate, $promoCode, $status, $startDate, $endDate, $imagePath, $id])) {
            $_SESSION['message'] = 'Promotion updated successfully.';
        } else {
            $_SESSION['message'] = 'Failed to update promotion.';
        }
    } else {

        foreach ($_err as $error) {
            echo "<p>$error</p>";
        }
    }

    header('Location: promotion.php');
    exit;
}
