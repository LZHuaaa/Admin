<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids']) && isset($_POST['term'])) {

    $ids = $_POST['ids'];
    $term = $_POST['term'];

    $ids = array_map('intval', $ids);
    $idsString = implode(',', $ids);

    try {
        switch ($term) {
            case 'user':
                $stmt = $_db->prepare("SELECT photo FROM user WHERE userID IN ($idsString)");
                $stmt->execute();
                $photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

                // Loop through each user ID in the list (assuming $idsString contains a comma-separated list of IDs)
                $idsArray = explode(',', $idsString);

                foreach ($idsArray as $id) {
                    // Fetch paymentID for this user
                    $stmt3 = $_db->prepare("SELECT paymentID FROM payment WHERE userid = ?");
                    $stmt3->execute([$id]);
                    $result2 = $stmt3->fetch(PDO::FETCH_ASSOC);

                    // Fetch orderID for this user
                    $stmt5 = $_db->prepare("SELECT orderID FROM orders WHERE userid = ?");
                    $stmt5->execute([$id]);
                    $result3 = $stmt5->fetch(PDO::FETCH_ASSOC);

              
                    $stmt8 = $_db->prepare("SELECT cartID FROM cart WHERE userid = ?");
                    $stmt8->execute([$id]);
                    $result4 = $stmt8->fetch(PDO::FETCH_ASSOC);

                    if ($result3) {
                        $stmt6 = $_db->prepare("DELETE FROM order_detail WHERE orderid = ?");
                        $stmt6->execute([$result3['orderID']]);

                        $stmt7 = $_db->prepare("DELETE FROM orders WHERE orderid = ?");
                        $stmt7->execute([$result3['orderID']]);
                    }

                    if ($result4) {
                        $stmt10 = $_db->prepare("DELETE FROM cart_detail WHERE cartid = ?");
                        $stmt10->execute([$result4['cartID']]);

                        $stmt9 = $_db->prepare("DELETE FROM cart WHERE userid = ?");
                        $stmt9->execute([$id]);
                    }

                    if ($result2) {
                        $stmt4 = $_db->prepare("DELETE FROM shipping_address WHERE paymentid = ?");
                        $stmt4->execute([$result2['paymentID']]);

                        $stmt2 = $_db->prepare("DELETE FROM payment WHERE userid = ?");
                        $stmt2->execute([$id]);
                    }

                    $stmt11 = $_db->prepare("DELETE FROM wishlist WHERE userid = ?");
                    $stmt11->execute([$id]);

                    $stmt12 = $_db->prepare("DELETE FROM productreviews WHERE userid = ?");
                    $stmt12->execute([$id]);
                }

                $deleteStmt = $_db->prepare("DELETE FROM user WHERE userID IN ($idsString)");
                if ($deleteStmt->execute()) {
                    foreach ($photos as $photo) {
                        $photoPath = "images/$photo";
                        if (file_exists($photoPath)) {
                            unlink($photoPath);
                        }
                    }
                    echo "Deleted Successfully";
                } else {
                    echo "Error deleting records from the database.";
                }
                break;

            case 'product':
                $stmt = $_db->prepare("SELECT photo_path FROM product_photo WHERE productID IN ($idsString)");
                $stmt->execute();
                $photos = $stmt->fetchAll(PDO::FETCH_COLUMN);


                $deletePhotosStmt = $_db->prepare("DELETE FROM product_photo WHERE productID IN ($idsString)");
                $deletePhotosStmt->execute();


                $stmt = $_db->prepare("DELETE FROM product_photo WHERE productID IN ($idsString)");
                $stmt->execute();

                $stmt = $_db->prepare("DELETE FROM order_detail WHERE productID IN ($idsString)");
                $stmt->execute();

                $stmt = $_db->prepare("DELETE FROM product_size WHERE productID IN ($idsString)");
                $stmt->execute();

                $deleteProductsStmt = $_db->prepare("DELETE FROM product WHERE productID IN ($idsString)");
                $deleteProductsStmt->execute();


                foreach ($photos as $photo) {
                    $photoPath = "images/$photo";
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }
                }
                echo "Products deleted successfully.";
                break;

            case 'category':

                $updateProductsStmt = $_db->prepare("UPDATE product SET categoryID = NULL WHERE categoryID IN ($idsString)");
                $updateProductsStmt->execute();

                $deleteCategoriesStmt = $_db->prepare("DELETE FROM category WHERE categoryID IN ($idsString)");
                if ($deleteCategoriesStmt->execute()) {
                    echo "Deleted Successfully";
                } else {
                    echo "Error deleting records from the database.";
                }
                break;

            case 'order':

                $deleteOrderDetailsStmt = $_db->prepare("DELETE FROM order_detail WHERE orderID IN ($idsString)");
                $deleteOrderDetailsStmt->execute();

                $deleteOrdersStmt = $_db->prepare("DELETE FROM orders WHERE orderID IN ($idsString)");
                if ($deleteOrdersStmt->execute()) {
                    echo "Orders and their details deleted successfully.";
                } else {
                    echo "Error deleting order records.";
                }
                break;

            default:
                echo "Invalid term specified.";
                break;
        }
    } catch (Exception $e) {
        echo "Error deleting records: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
