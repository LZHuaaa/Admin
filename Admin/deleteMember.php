<?php
require 'base.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $stmt = $_db->prepare("SELECT photo FROM user WHERE userid = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $photo = $result['photo'];


        $stmt = $_db->prepare("SELECT paymentID FROM payment WHERE userid = ?");
        $stmt->execute([$id]);
        $result2 = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $_db->prepare("SELECT orderID FROM orders WHERE userid = ?");
        $stmt->execute([$id]);
        $result3 = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $_db->prepare("SELECT cartID FROM cart WHERE userid = ?");
        $stmt->execute([$id]);
        $result4 = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result3) {
            $stmt = $_db->prepare("DELETE FROM order_detail WHERE orderid = ?");
            $stmt->execute([$result3['orderID']]);

            $stmt = $_db->prepare("DELETE FROM orders WHERE orderid = ?");
            $stmt->execute([$result3['orderID']]);
        }


        if ($result4) {
            $stmt = $_db->prepare("DELETE FROM cart_detail WHERE cartid = ?");
            $stmt->execute([$result4['cartID']]);
        }

        $stmt = $_db->prepare("DELETE FROM cart WHERE userid = ?");
        $stmt->execute([$id]);

        if ($result2) {
            $stmt = $_db->prepare("DELETE FROM shipping_address WHERE paymentid = ?");
            $stmt->execute([$result2['paymentID']]);
        }

        $stmt = $_db->prepare("DELETE FROM payment WHERE userid = ?");
        $stmt->execute([$id]);

        $stmt = $_db->prepare("DELETE FROM wishlist WHERE userid = ?");
        $stmt->execute([$id]);

        $stmt = $_db->prepare("DELETE FROM productreviews WHERE userid = ?");
        $stmt->execute([$id]);

        $stmt = $_db->prepare("DELETE FROM user WHERE userid = ?");

        if ($stmt->execute([$id])) {
            $photoPath = "../images/$photo";
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }

            echo "Deleted Successfully";
        } else {
            echo "Error deleting record from database.";
        }
    } else {
        echo "Error: Member not found.";
    }
}
