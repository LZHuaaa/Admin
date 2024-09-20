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

                $deleteStmt = $_db->prepare("
                    DELETE FROM order_detail WHERE orderid IN (
                        SELECT orderID FROM orders WHERE userid IN ($idsString)
                    );
                    DELETE FROM orders WHERE userid IN ($idsString);
                    DELETE FROM cart_detail WHERE cartid IN (
                        SELECT cartID FROM cart WHERE userid IN ($idsString)
                    );
                    DELETE FROM cart WHERE userid IN ($idsString);
                    DELETE FROM shipping_address WHERE paymentid IN (
                        SELECT paymentID FROM payment WHERE userid IN ($idsString)
                    );
                    DELETE FROM payment WHERE userid IN ($idsString);
                    DELETE FROM wishlist WHERE userid IN ($idsString);
                    DELETE FROM productreviews WHERE userid IN ($idsString);
                    DELETE FROM user WHERE userID IN ($idsString)
                ");
                if ($deleteStmt->execute()) {
                    foreach ($photos as $photo) {
                        $photoPath = "../images/$photo";
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

                $stmt = $_db->prepare("SELECT video_link FROM product_video WHERE productID IN ($idsString)");
                $stmt->execute();
                $videos = $stmt->fetchAll(PDO::FETCH_COLUMN);



                $deleteStmt = $_db->prepare("
                    DELETE FROM product_photo WHERE productID IN ($idsString);
                    DELETE FROM wishlist WHERE productID IN ($idsString);
                    DELETE FROM productreviews WHERE productID IN ($idsString);
                    DELETE FROM cart_detail WHERE productID IN ($idsString);
                    DELETE FROM product_video WHERE productID IN ($idsString);
                    DELETE FROM order_detail WHERE productID IN ($idsString);
                    DELETE FROM product_size WHERE productID IN ($idsString);
                    DELETE FROM product WHERE productID IN ($idsString);

                ");
                if ($deleteStmt->execute()) {
                    foreach ($photos as $photo) {
                        $photoPath = "../images/$photo";
                        if (file_exists($photoPath)) {
                            unlink($photoPath);
                        }
                    }

                    /*foreach ($videos as $video) {
                        $videoPath = "../videos/$video";
                        if (file_exists($videoPath)) {
                            unlink($videoPath);
                        }
                    }*/
                    
                    echo "Products deleted successfully.";
                } else {
                    echo "Error deleting product records.";
                }
                break;

            case 'category':
                $updateStmt = $_db->prepare("UPDATE product SET categoryID = NULL WHERE categoryID IN ($idsString)");
                $updateStmt->execute();

                $deleteStmt = $_db->prepare("DELETE FROM category WHERE categoryID IN ($idsString)");
                if ($deleteStmt->execute()) {
                    echo "Deleted Successfully";
                } else {
                    echo "Error deleting records from the database.";
                }
                break;

            case 'order':
                $deleteStmt = $_db->prepare("
                    DELETE FROM order_detail WHERE orderID IN ($idsString);
                    DELETE FROM orders WHERE orderID IN ($idsString)
                ");
                if ($deleteStmt->execute()) {
                    echo "Orders and their details deleted successfully.";
                } else {
                    echo "Error deleting order records.";
                }
                break;

            case 'promotion':

                $updateStmt = $_db->prepare("UPDATE product SET promotionID = NULL WHERE promotionID IN ($idsString)");
                $updateStmt->execute();


                foreach ($ids as $id) {
                    $stmt = $_db->prepare("SELECT promotionImage FROM promotion WHERE promotionID = ?");
                    $stmt->execute([$id]);
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($result) {
                        $photo = $result['promotionImage'];

                        $deleteStmt = $_db->prepare("DELETE FROM promotion WHERE promotionID = ?");
                        if ($deleteStmt->execute([$id])) {



                            $photoPath = "../images/$photo";
                            if (file_exists($photoPath)) {
                                unlink($photoPath);
                            }

                            echo "Promotion deleted successfully.";
                        } else {
                            echo "Error deleting promotion record.";
                        }
                    }
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
