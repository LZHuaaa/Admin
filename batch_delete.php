<?php
require 'base.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ids'])) {
    
    $ids = $_POST['ids'];

    $ids = array_map('intval', $ids);

    $idsString = implode(',', $ids);

    $stmt = $_db->prepare("SELECT photo FROM user WHERE userID IN ($idsString)");
    $stmt->execute();
    $photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

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
} else {
    echo "Invalid request.";
}

?>
