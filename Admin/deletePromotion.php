<?php
require 'base.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $updatePromotionsStmt = $_db->prepare("UPDATE product SET promotionID = NULL WHERE promotionID = $id");
    $updatePromotionsStmt->execute();
    
    $stmt = $_db->prepare("SELECT promotionImage FROM promotion WHERE promotionID = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $photo = $result['promotionImage'];

        $stmt = $_db->prepare("DELETE FROM promotion WHERE promotionID = ?");
        if ($stmt->execute([$id])) {
          

            $photoPath = "../images/$photo";
            if (file_exists($photoPath)) {
                unlink($photoPath); 
            }

            echo "Deleted successfully";
        } else {
            echo "Error deleting record from database.";
        }
    } else {
        echo "Error: Promotion not found.";
    }
}
?>
