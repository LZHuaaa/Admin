<?php
require_once 'base.php'; 

if (isset($_GET['action']) && isset($_GET['id']) && isset($_GET['username'])) {
    $action = $_GET['action'];
    $id = $_GET['id'];
    $username = $_GET['username'];


    if ($action === 'block' || $action === 'unblock') {
    
        $status = $action === 'block' ? 'Inactive' : 'Active';

        $stmt = $_db->prepare("UPDATE user SET status = ? WHERE userID = ?");
        $stmt->execute([$status, $id]);

        if ($stmt->rowCount()) {
            echo "$username $action successfully.";
        } else {
            echo "Failed to update the status.";
        }
    } else {
        echo "Invalid action.";
    }
} else {
    echo "Invalid parameters.";
}
?>
