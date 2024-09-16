<?php
require 'base.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch the photo file name
    $stmt = $_db->prepare("SELECT photo FROM user WHERE userid = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($result) {
        $photo = $result['photo'];


        $stmt3 = $_db->prepare("SELECT paymentID FROM payment WHERE userid = ?");
        $stmt3->execute([$id]);
        $result2 = $stmt3->fetch(PDO::FETCH_ASSOC);

        $stmt5 = $_db->prepare("SELECT orderID FROM orders WHERE userid = ?");
        $stmt5->execute([$id]);
        $result3 = $stmt5->fetch(PDO::FETCH_ASSOC);

        $stmt8 = $_db->prepare("SELECT cartID FROM cart WHERE userid = ?");
        $stmt8->execute([$id]);
        $result4 = $stmt8->fetch(PDO::FETCH_ASSOC);

        $stmt6 = $_db->prepare("DELETE FROM order_detail WHERE orderid = ?");
        $stmt6->execute([$result3['orderID']]);

        $stmt7 = $_db->prepare("DELETE FROM orders WHERE orderid = ?");
        $stmt7->execute([$result3['orderID']]);

        $stmt10 = $_db->prepare("DELETE FROM cart_detail WHERE cartid = ?");
        $stmt10->execute([$result4['cartID']]);

        $stmt9 = $_db->prepare("DELETE FROM cart WHERE userid = ?");
        $stmt9->execute([$id]);

        $stmt4 = $_db->prepare("DELETE FROM shipping_address WHERE paymentid = ?");
        $stmt4->execute([$result2['paymentID']]);

        $stmt2 = $_db->prepare("DELETE FROM payment WHERE userid = ?");
        $stmt2->execute([$id]);

        $stmt11 = $_db->prepare("DELETE FROM wishlist WHERE userid = ?");
        $stmt11->execute([$id]);

        $stmt12 = $_db->prepare("DELETE FROM productreviews WHERE userid = ?");
        $stmt12->execute([$id]);

        $stmt = $_db->prepare("DELETE FROM user WHERE userid = ?");

        if ($stmt->execute([$id])) {
            $photoPath = "images/$photo";
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }

            echo "Success";
        } else {
            echo "Error deleting record from database.";
        }
    } else {
        echo "Error: Member not found.";
    }
}
