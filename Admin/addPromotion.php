<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $promotionName = $_POST['name'];
    $promotionDescription = $_POST['description'];
    $discountRate = $_POST['discountRate'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $promoCode = $_POST['promo_code'];
    $status = $_POST['status'];


    try {

        $promotionImagePath = '';
        if (isset($_FILES['promotionImage']) && $_FILES['promotionImage']['error'] === UPLOAD_ERR_OK) {
            $imageTmpPath = $_FILES['promotionImage']['tmp_name'];
            $imageName = $_FILES['promotionImage']['name'];
            $promotionImagePath = '../images/' . $imageName;

            if (!move_uploaded_file($imageTmpPath, $promotionImagePath)) {
                throw new Exception('Failed to upload promotion image.');
            }
        }


        $stmt = $_db->prepare("
            INSERT INTO promotion (name, description, discountRate, startDate, endDate, promotionImage, status, promo_code) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $promotionName,
            $promotionDescription,
            $discountRate,
            $startDate,
            $endDate,
            basename($promotionImagePath), 
            $status,
            $promoCode
        ]);

        echo "Promotion added successfully.";
    } catch (Exception $e) {

        echo "Failed to add promotion: " . $e->getMessage();
    }
}
