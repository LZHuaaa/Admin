<?php
require 'base.php';

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Fetch the photo file name
    $stmt = $_db->prepare("SELECT photo FROM member WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $photo = $result['photo'];

        // Delete the record from the database
        $stmt = $_db->prepare("DELETE FROM member WHERE id = ?");
        if ($stmt->execute([$id])) {
            echo "Success";

            // Check if the photo exists and delete it from the folder
            $photoPath = "images/$photo";
            if (file_exists($photoPath)) {
                unlink($photoPath); // Delete the file
            }
        } else {
            echo "Error deleting record from database.";
        }
    } else {
        echo "Error: Member not found.";
    }
}
?>
