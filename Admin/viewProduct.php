<?php
require 'base.php'; // Assuming base.php includes your database connection

// Get productID from query parameter
$productID = req('productID');

if (empty($productID)) {
    die("Invalid product ID.");
}

// Fetch the product details from the database
$query = "
    SELECT p.productID, p.productName, p.productDesc, p.price, p.stockQuantity, p.soldQuantity, 
           COALESCE(pr.name, '-') AS promotionName, c.categoryname AS categoryName
    FROM product p
    LEFT JOIN promotion pr ON p.promotionID = pr.promotionid
    LEFT JOIN category c ON p.categoryID = c.categoryid
    WHERE p.productID = :productID
";

$stmt = $_db->prepare($query);
$stmt->execute(['productID' => $productID]);
$product = $stmt->fetch(PDO::FETCH_OBJ);

if (!$product) {
    die("Product not found.");
}

// Fetch product photos from product_photo table
$queryPhotos = "
    SELECT photo_path, photo_order
    FROM product_photo
    WHERE productID = :productID
    ORDER BY photo_order ASC
";

$stmtPhotos = $_db->prepare($queryPhotos);
$stmtPhotos->execute(['productID' => $productID]);
$productPhotos = $stmtPhotos->fetchAll(PDO::FETCH_OBJ);

// Fetch product sizes from product_size table
$querySizes = "
    SELECT sizeName
    FROM product_size
    WHERE productID = :productID
";

$stmtSizes = $_db->prepare($querySizes);
$stmtSizes->execute(['productID' => $productID]);
$productSizes = $stmtSizes->fetchAll(PDO::FETCH_COLUMN);

// Fetch product video from product_video table
$queryVideo = "
    SELECT video_link
    FROM product_video
    WHERE productID = :productID
";

$stmtVideo = $_db->prepare($queryVideo);
$stmtVideo->execute(['productID' => $productID]);
$productVideo = $stmtVideo->fetch(PDO::FETCH_OBJ);

$_title = 'View Product: ' . htmlspecialchars($product->productName);
include 'header.php';
?>

<style>
    .product-container {
        width: 100%;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .product-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-bottom: 20px;
    }

    .product-details h2 {
        margin-bottom: 5px;
        grid-column: span 2;
    }

    .product-details p {
        margin: 0px 0;
        font-size: 18px;
    }

    .product-photos {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .product-photos img {
        max-width: 150px;
        max-height: 150px;
        object-fit: cover;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .product-video {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 20px;
    }

    .back-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 16px;
    }

    .back-btn:hover {
        background-color: #45a049;
    }
</style>
</div>

<div class="product-container">
    <div class="product-details">
        <h2>Product Details</h2>
        <p><strong>Product Name:</strong> <?= htmlspecialchars($product->productName) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($product->productDesc) ?></p>
        <p><strong>Category:</strong> <?= htmlspecialchars($product->categoryName) ?></p>
        <?php if (!empty($productSizes)): ?>
            <p><strong>Available Sizes:</strong> <?= implode(', ', array_map('htmlspecialchars', $productSizes)) ?></p>
        <?php else: ?>
            <p><strong>Available Sizes:</strong> No sizes available</p>
        <?php endif; ?>
        <p><strong>Price:</strong> RM<?= htmlspecialchars($product->price) ?></p>
        <p><strong>Promotion:</strong> <?= htmlspecialchars($product->promotionName) ?></p>
        <p><strong>Stock Quantity:</strong> <?= htmlspecialchars($product->stockQuantity) ?></p>
        <p><strong>Sold Quantity:</strong> <?= htmlspecialchars($product->soldQuantity) ?></p>
    </div>

    <?php if (count($productPhotos) > 0): ?>
        <div class="product-photos">
            <h2>Product Photos</h2>
            <?php foreach ($productPhotos as $photo): ?>
                <img src="../images/<?= htmlspecialchars($photo->photo_path) ?>" alt="Product Photo">
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No photos available for this product.</p>
    <?php endif; ?>

    <?php if (!empty($productVideo)): ?>
        <div class="product-video">
            <h2>Product Video</h2>
            <video width="400" controls>
                <source src="../videos/<?= htmlspecialchars($productVideo->video_link) ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    <?php else: ?>
        <p>No video available for this product.</p>
    <?php endif; ?>

    <a href="product.php" class="back-btn">Back to Products</a>
</div>
