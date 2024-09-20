<?php
require 'base.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $files = [
        'adminsFile' => 'admins',
        'membersFile' => 'members',
        'categoriesFile' => 'categories',
        'promotionsFile' => 'promotions',
        'productsFile' => 'products',
        'photosFile' => 'product_photos',
        'videosFile' => 'product_videos'
    ];


    $isFileUploaded = false;

    foreach ($files as $inputName => $tableName) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $isFileUploaded = true;
            $fileTmpPath = $_FILES[$inputName]['tmp_name'];
            $fileContent = file($fileTmpPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            switch ($tableName) {
                case 'admins':
                    insertAdmins($fileContent);
                    break;
                case 'members':
                    insertMembers($fileContent);
                    break;
                case 'categories':
                    insertCategories($fileContent);
                    break;
                case 'promotions':
                    insertPromotions($fileContent);
                    break;
                case 'products':
                    insertProducts($fileContent);
                    break;
                case 'product_photos':
                    insertProductPhotos($fileContent);
                    break;
                case 'product_videos':
                    insertProductVideos($fileContent);
                    break;
            }
        } else {
       
            if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] !== UPLOAD_ERR_NO_FILE) {
                echo "Error uploading file for $tableName: " . $_FILES[$inputName]['error'] . "<br>";
            }
        }
    }

    if ($isFileUploaded) {
        $_SESSION['insertionSuccessful'] = 'Batch Insertion Complete.';
    } else {
        $_SESSION['insertionSuccessful'] = 'No files upload. Please upload at least one file.';
    }

   
    header('Location: batchInsertionForm.php');
    exit;
} else {
    echo "<script>alert('Invalid request.')<script>";
}


function insertAdmins($fileContent)
{
    global $_db;
    foreach ($fileContent as $line) {
        list($username, $fullname, $email, $password, $role, $status, $photoName) = explode('|', trim($line));
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $targetDir = "../images/";


        if (isset($_FILES['adminsFile']) && $_FILES['adminsFile']['error'] === UPLOAD_ERR_OK) {
            $tmpFilePath = $_FILES['adminsFile']['tmp_name'];
            $targetFilePath = $targetDir . basename($photoName);


            if (move_uploaded_file($tmpFilePath, $targetFilePath)) {

                $stmt = $_db->prepare("INSERT INTO user (username, fullname, email, password, role, status, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$username, $fullname, $email, $hashedPassword, $role, $status, basename($photoName)]);
            } else {
                echo "Error uploading the file: $photoName<br>";
            }
        }
    }
}


function insertMembers($fileContent)
{
    global $_db;
    foreach ($fileContent as $line) {
        list($username, $fullname, $email, $password, $role, $status, $photo) = explode('|', trim($line));
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $targetDir = "../images/";

        if (isset($_FILES['membersFile']) && $_FILES['membersFile']['error'] === UPLOAD_ERR_OK) {
            $tmpFilePath = $_FILES['membersFile']['tmp_name'];
            $targetFilePath = $targetDir . basename($photo);


            if (move_uploaded_file($tmpFilePath, $targetFilePath)) {

                $stmt = $_db->prepare("INSERT INTO user (username, fullname, email, password, role, status, photo) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$username, $fullname, $email, $hashedPassword, $role, $status, basename($photo)]);
            } else {
                echo "Error uploading the file: $photo<br>";
            }
        }
    }
}


function insertCategories($fileContent)
{
    global $_db;
    foreach ($fileContent as $line) {
        list($categoryName) = explode('|', trim($line));
        $stmt = $_db->prepare("INSERT INTO category (categoryName) VALUES (?)");
        $stmt->execute([$categoryName]);
    }
}


function insertPromotions($fileContent)
{
    global $_db;
    foreach ($fileContent as $line) {
        list($promotionName, $description, $discountRate, $startDate, $endDate, $promotionImage, $status, $promoCode) = explode('|', trim($line));
        $stmt = $_db->prepare("INSERT INTO promotion (name, description, discountRate, startDate, endDate, promotionImage, status, promo_code) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$promotionName, $description, $discountRate, $startDate, $endDate, $promotionImage, $status, $promoCode]);
    }
}

function insertProducts($fileContent)
{
    global $_db;
    foreach ($fileContent as $line) {
        list($productName, $productID, $productDesc, $price, $stockQuantity, $soldQuantity, $promotionID, $categoryID) = explode('|', trim($line));
        $stmt = $_db->prepare("INSERT INTO product (productName, productID, productDesc, price, stockQuantity, soldQuantity, promotionID, categoryID) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$productName, $productID, $productDesc, $price, $stockQuantity, $soldQuantity, $promotionID, $categoryID]);
    }
}


function insertProductPhotos($fileContent)
{
    global $_db;
    foreach ($fileContent as $line) {
        list($productID, $photoName, $photoOrder) = explode('|', trim($line));

        $targetDir = "../images/";


        if (isset($_FILES['photosFile']) && $_FILES['photosFile']['error'] === UPLOAD_ERR_OK) {
            $tmpFilePath = $_FILES['photosFile']['tmp_name'];
            $targetFilePath = $targetDir . basename($photoName);


            if (move_uploaded_file($tmpFilePath, $targetFilePath)) {
                $stmt = $_db->prepare("INSERT INTO product_photo (productID, photo_path, photo_order) VALUES (?, ?, ?)");
                $stmt->execute([$productID, basename($photoName), $photoOrder]);
            } else {
                echo "Error uploading the file: $photoName<br>";
            }
        }
    }
}


function insertProductVideos($fileContent)
{
    global $_db;
    foreach ($fileContent as $line) {
        list($productID, $videoLink) = explode('|', trim($line));
        $stmt = $_db->prepare("INSERT INTO product_video (productID, video_link) VALUES (?, ?)");
        $stmt->execute([$productID, $videoLink]);
    }
}
