<link rel="stylesheet" href="../css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

<?php
require 'base.php';


$promotions = [];
$categories = [];

$stmt = $_db->query("SELECT promotionid, name FROM promotion");
$promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $_db->query("SELECT categoryid, categoryname FROM category");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<button type="button" class="btn-close" id="closeBtn">Close</button>
<form id="addProductForm" method="POST" enctype="multipart/form-data">
    <?php
    html_text('productName', 'Product Name', 'required');
    html_textarea('productDescription', 'Product Description', '');
    html_select_size_type();
    html_number('price', 'Price(RM)', 'step="0.01" min="0" required');
    html_number('stockQuantity', 'Stock Quantity', 'min="0" required');
    html_number('soldQuantity', 'Sold Quantity', 'min="0" required');
    html_select_category('category', 'Category', $categories, '', true);
    html_select_promotion('promotion', 'Promotions', $promotions, '', true);
    html_file_multiple('productImages', 'Product Images', 'multiple accept="image/*" required'); ?>
    <div id="imagePreviewContainer"></div>
    <?php
    html_file_video('productVideo', 'Product Video', 'accept="video/*"');
    ?>

    <div id="videoPreviewContainer"></div>
    <?php html_submit('submitAddBtn', 'Add Product'); ?>
</form>