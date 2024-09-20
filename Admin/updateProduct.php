<?php require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    file_put_contents('debug.log', print_r($_POST, true), FILE_APPEND);
    file_put_contents('debug.log', print_r($_FILES, true), FILE_APPEND);

    $productID = $_POST['id'];
    $productName = $_POST['productName'];
    $productDescription = $_POST['productDescription'];
    $price = $_POST['price'];
    $stockQuantity = $_POST['stockQuantity'];
    $soldQuantity = $_POST['soldQuantity'];
    $categoryID = $_POST['category'];
    $promotionID = $_POST['promotion'];
    $productVideoLink = $_POST['productVideoLink'];  // Get the YouTube link

    if (trim($promotionID) === '') {
        $promotionID = NULL;
    }

    try {
        // Update product details
        $stmt = $_db->prepare("
            UPDATE product
            SET productName = ?, productDesc = ?, price = ?, stockQuantity = ?, soldQuantity = ?, promotionID = ?, categoryID = ?
            WHERE productID = ?
        ");
        $stmt->execute([$productName, $productDescription, $price, $stockQuantity, $soldQuantity, $promotionID, $categoryID, $productID]);

        // Handle product images
        if (isset($_FILES['productImages']) && is_array($_FILES['productImages']['error'])) {
            $fileCount = count($_FILES['productImages']['name']);
            $newFilesUploaded = false;

            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES['productImages']['error'][$i] === UPLOAD_ERR_OK) {
                    $newFilesUploaded = true;
                    break;
                }
            }

            if ($newFilesUploaded) {
                $stmt = $_db->prepare("SELECT photo_path FROM product_photo WHERE productID = ?");
                $stmt->execute([$productID]);
                $existingPhotos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($existingPhotos as $photo) {
                    $filePath = '../images/' . $photo['photo_path'];
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                }

                $stmt = $_db->prepare("DELETE FROM product_photo WHERE productID = ?");
                $stmt->execute([$productID]);

                $filePaths = [];
                for ($i = 0; $i < $fileCount; $i++) {
                    if ($_FILES['productImages']['error'][$i] === UPLOAD_ERR_OK) {
                        $fileTmpPath = $_FILES['productImages']['tmp_name'][$i];
                        $fileName = $_FILES['productImages']['name'][$i];
                        $filePath = '../images/' . $fileName;

                        if (move_uploaded_file($fileTmpPath, $filePath)) {
                            $filePaths[] = $filePath;
                        } else {
                            throw new Exception("Failed to move uploaded file: $fileName");
                        }
                    } else if ($_FILES['productImages']['error'][$i] !== UPLOAD_ERR_NO_FILE) {
                        throw new Exception("File upload error for file: " . $_FILES['productImages']['name'][$i]);
                    }
                }

                if (!empty($filePaths)) {
                    $stmt = $_db->prepare("
                        INSERT INTO product_photo (productID, photo_path, photo_order) 
                        VALUES (?, ?, ?)
                    ");
                    foreach ($filePaths as $order => $filePath) {
                        $fileNameOnly = basename($filePath);
                        $stmt->execute([$productID, $fileNameOnly, $order + 1]);
                    }
                }
            }
        }

        // Handle product sizes
        if (isset($_POST['sizes']) && is_array($_POST['sizes'])) {
            $selectedSizes = $_POST['sizes'];

            $stmt = $_db->prepare("DELETE FROM product_size WHERE productID = ?");
            $stmt->execute([$productID]);

            $stmt = $_db->prepare("INSERT INTO product_size (productID, sizeName) VALUES (?, ?)");
            foreach ($selectedSizes as $sizeName) {
                $stmt->execute([$productID, $sizeName]);
            }
        }

    
        if (!empty($productVideoLink)) {
            $stmt = $_db->prepare("DELETE FROM product_video WHERE productID = ?");
            $stmt->execute([$productID]);

            $stmt = $_db->prepare("INSERT INTO product_video (productID, video_link) 
                VALUES (?, ?)");
            $stmt->execute([$productID, $productVideoLink]);
        }

        echo "Product updated successfully.";
    } catch (Exception $e) {
        echo "Failed to update product: " . $e->getMessage();
    }
}
?>