<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productName = $_POST['productName'];
    $productDescription = $_POST['productDescription'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stockQuantity'];
    $soldQuantity = $_POST['soldQuantity'];
    $categoryID = $_POST['category'];
    $promotionID = $_POST['promotion'];
    $sizes = isset($_POST['sizes']) ? $_POST['sizes'] : [];

    if ($promotionID === ' ') {
        $promotionID = NULL;  
    }

    try {
        $filePaths = [];
        if (isset($_FILES['productImages']) && $_FILES['productImages']['error'][0] === UPLOAD_ERR_OK) {
            $fileCount = count($_FILES['productImages']['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                $fileTmpPath = $_FILES['productImages']['tmp_name'][$i];
                $fileName = $_FILES['productImages']['name'][$i];
                $filePath = '../images/' . $fileName;

                if (move_uploaded_file($fileTmpPath, $filePath)) {
                    $filePaths[] = $filePath;
                }
            }
        }

        $videoPath = '';
        if (isset($_FILES['productVideo']) && $_FILES['productVideo']['error'] === UPLOAD_ERR_OK) {
            $videoTmpPath = $_FILES['productVideo']['tmp_name'];
            $videoName = $_FILES['productVideo']['name'];
            $videoPath = '../videos/' . $videoName;

            if (!move_uploaded_file($videoTmpPath, $videoPath)) {
                throw new Exception('Failed to upload video.');
            }
        }


        $stmt = $_db->prepare("
        INSERT INTO product (productName, productDesc, price, stockQuantity, soldQuantity, promotionID, categoryID) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
        $stmt->execute([$productName, $productDescription, $price, $stockQuantity, $soldQuantity, $promotionID, $categoryID]);

        $productID = $_db->lastInsertId();


        if (!empty($filePaths)) {
            $stmt = $_db->prepare("
            INSERT INTO product_photo (productID, photo_path, photo_order) 
            VALUES (?, ?, ?)
        ");
            foreach ($filePaths as $order => $filePath) {
                $stmt->execute([$productID, basename($filePath), $order + 1]);
            }
        }

        if (!empty($sizes)) {
            $stmt = $_db->prepare("
                INSERT INTO product_size (productID, sizeName) 
                VALUES (?, ?)
            ");
            foreach ($sizes as $size) {
                $stmt->execute([$productID, $size]);
            }
        }

        if (!empty($videoPath)) {
            $stmt = $_db->prepare("
            INSERT INTO product_video (productID, video_link) 
            VALUES (?, ?)
            ");
            $stmt->execute([$productID, basename($videoPath)]);
        }


        echo "Product added successfully.";

    } catch (Exception $e) {
        
        echo "Failed to add product: " . $e->getMessage();
    }
}
