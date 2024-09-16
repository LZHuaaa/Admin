<?php
require 'base.php';

if (isset($_POST['id'])) {

    $productID = intval($_POST['id']);

    try {
        $_db->beginTransaction(); // Begin transaction
        
        $stmt = $_db->prepare("SELECT photo_path FROM product_photo WHERE productID = ?");
        $stmt->execute([$productID]);
        $photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($photos) {
            foreach ($photos as $photo) {
                $photoPath = "images/" . $photo; 
                if (file_exists($photoPath)) {
                    if (!unlink($photoPath)) {
                        throw new Exception("Failed to delete file: $photoPath");
                    }
                }
            }

            $stmt = $_db->prepare("DELETE FROM product_photo WHERE productID = ?");
            $stmt->execute([$productID]);
        }

        $stmt = $_db->prepare("DELETE FROM order_detail WHERE productID = ?");
        $stmt->execute([$productID]);

        $stmt = $_db->prepare("DELETE FROM product_size WHERE productID = ?");
        $stmt->execute([$productID]);

        // Delete product from database
        $stmt = $_db->prepare("DELETE FROM product WHERE productID = ?");
        $stmt->execute([$productID]);

        $_db->commit(); // Commit transaction

        echo "Product and associated photos deleted successfully.";

    } catch (Exception $e) {
        $_db->rollBack(); // Rollback transaction on error
        file_put_contents('error.log', $e->getMessage(), FILE_APPEND); // Log error
        echo "Failed to delete product: " . $e->getMessage();
    }

} else {
    echo "Invalid request.";
}
?>
