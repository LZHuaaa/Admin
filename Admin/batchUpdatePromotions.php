<?php
require 'base.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  
    $promotionIDs = $_POST['promotionID'] ?? [];
    $newStatus = $_POST['newStatus'] ?? '';

  
    if (empty($promotionIDs) || empty($newStatus)) {
        $_SESSION['message'] = 'No promotions selected or no status specified.';
    } else {
      
        $placeholders = implode(',', array_fill(0, count($promotionIDs), '?'));
        $query = "UPDATE promotion SET status = ? WHERE promotionID IN ($placeholders)";
        

        $stmt = $_db->prepare($query);


        $params = array_merge([$newStatus], $promotionIDs);

    
        if ($stmt->execute($params)) {
            $_SESSION['message'] = 'Status updated successfully.';
        } else {
            $_SESSION['message'] = 'Failed to update the status.';
        }
    }


    header('Location: promotion.php');
    exit;
}
?>
