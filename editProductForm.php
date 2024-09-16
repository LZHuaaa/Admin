<link rel="stylesheet" href="css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

<?php
require 'base.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $_db->prepare("SELECT * FROM product WHERE productID = ?");
    $stmt->execute([$id]);
    $product = $stmt->fetch();

    if ($product) {

        $promotions = [];
        $categories = [];

        $stmt = $_db->query("SELECT promotionID, name FROM promotion");
        $promotions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stmt = $_db->query("SELECT categoryID, categoryName FROM category");
        $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($product) {
            $stmt = $_db->prepare("SELECT sizeName FROM product_size WHERE productID = ?");
            $stmt->execute([$id]);
            $productSizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
?>

        <button type="button" class="btn-close" id="closeBtn">Close</button>
        <form method="POST" id="editProductForm" enctype="multipart/form-data">
            <?php
            html_hidden('id', $product->productID);
            html_text2('productName', 'Product Name', $product->productName, 'required');
            html_textarea2('productDescription', 'Product Description', $product->productDesc, '');
            html_size_selector($productSizes);
            html_number2('price', 'Price(RM)', $product->price, 'step="0.01" min="0" required');
            html_number2('stockQuantity', 'Stock Quantity', $product->stockQuantity, 'min="0" required');
            html_number2('soldQuantity', 'Sold Quantity', $product->soldQuantity, 'min="0" required');

            html_select_category2('category', 'Category', $categories, $product->categoryID, true);
            html_select_promotion2('promotion', 'Promotion', $promotions, $product->promotionID, true);

            // For image uploads
            html_file_multiple('productImages', 'Product Images', 'accept="image/*" multiple');?>


            <div id="imagePreviewContainer2">
                <?php
                $stmt = $_db->prepare("SELECT photo_path FROM product_photo WHERE productID = ?");
                $stmt->execute([$id]);
                $photos = $stmt->fetchAll(PDO::FETCH_COLUMN);

                foreach ($photos as $photo) {
                    echo '<img src="images/' . htmlspecialchars($photo) . '" alt="Product Image" style="width:100px;height:100px;margin-right:10px;">';
                }
                ?>
            </div>

            <?php html_file_video('productVideo', 'Product Video', 'accept="video/*"'); ?>

            <div id="videoPreviewContainer">
                <?php
                $stmt = $_db->prepare("SELECT video_link FROM product_video WHERE productID = ?");
                $stmt->execute([$id]);
                $video = $stmt->fetchColumn();

                if ($video) {
                    echo '<video controls style="width:300px;">
                            <source src="videos/' . htmlspecialchars($video) . '" type="video/mp4">
                          Your browser does not support the video tag.
                          </video>';
                } else {
                    echo '<p>No video available for this product.</p>';
                }
                ?>
            </div>

            <?php html_submit('submitBtn', 'Update Product'); ?>
        </form>
<?php
    } else {
        echo "Product not found.";
    }
} else {
    echo "No ID specified.";
}
?>

<script>
    $(document).ready(function() {

        $('#productImages').on('change', function() {
            var files = this.files;
            var previewContainer = $('#imagePreviewContainer2');
            previewContainer.html('');

            if (files.length) {
                $.each(files, function(index, file) {
                    if (file.type.match('image.*')) {
                        var reader = new FileReader();

                        reader.onload = function(e) {
                            var img = $('<img>').attr('src', e.target.result)
                                .css({
                                    'width': '100px',
                                    'height': '100px',
                                    'margin-right': '10px'
                                });
                            previewContainer.append(img);
                        };

                        reader.readAsDataURL(file);
                    }
                });
            }
        });

    });
</script>
