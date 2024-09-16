<?php
require 'base.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch the photo file name
    $stmt = $_db->prepare("SELECT photo FROM user WHERE userID = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $photo = $result['photo'];

        $stmt = $_db->prepare("DELETE FROM user WHERE userID = ?");
        if ($stmt->execute([$id])) {
            echo "Success";

            $photoPath = "images/$photo";
            if (file_exists($photoPath)) {
                unlink($photoPath); 
            }
        } else {
            echo "Error deleting record from database.";
        }
    } else {
        echo "Error: Admin not found.";
    }
}
?>
