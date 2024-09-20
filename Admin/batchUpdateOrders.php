<?php
require 'base.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderIDs = $_POST['orderID'] ?? [];
    $newStatus = $_POST['newStatus'] ?? '';

    if (empty($orderIDs) || empty($newStatus)) {
        $_SESSION['message'] = 'No orders selected or no status specified.';
    } else {
        $query = "UPDATE orders SET status = :newStatus WHERE orderID IN (" . implode(',', array_map('intval', $orderIDs)) . ")";
        $stmt = $_db->prepare($query);

        if ($stmt->execute(['newStatus' => $newStatus])) {
            $_SESSION['message'] = 'Status updated successfully.';
        } else {
            $_SESSION['message'] = 'Failed to update the status.';
        }
    }

    header('Location: order.php');
    exit;
}
