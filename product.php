<?php
require 'base.php';

$_title = 'Manage Products'; // Update title
include 'header.php';

// (1) Sorting
$fields = [
    'productID'       => 'ID',
    'productPhoto'    => 'Image',
    'productName'     => 'Product Name',
    'productDesc'     => 'Description',
    'categoryName'    => 'Category',
    'availableSizes'  => 'Size',
    'price'           => 'Price (RM)',
    'stockQuantity'  => 'Quantity',
    //'promotionName'  => 'Promotion',
    'Action'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'productID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once 'lib/SimplePager';

$categoryID = req('categoryID', '');
$min_price = req('min_price', '');
$max_price = req('max_price', '');

$filter_conditions = "";
$parameters = [];

if (!empty($categoryID)) {
    $filter_conditions .= " AND p.categoryID = :categoryID";
    $parameters['categoryID'] = $categoryID;
}
if (!empty($min_price)) {
    $filter_conditions .= " AND p.price >= :min_price";
    $parameters['min_price'] = $min_price;
}
if (!empty($max_price)) {
    $filter_conditions .= " AND p.price <= :max_price";
    $parameters['max_price'] = $max_price;
}

$search = req('search', '');

if (!empty($search)) {
    $filter_conditions .= " AND (p.productName LIKE :search OR p.productDesc LIKE :search)";
    $parameters['search'] = '%' . $search . '%';
}

$query = "
    SELECT p.productID, p.productName, p.productDesc, p.price, p.stockQuantity,
           COALESCE(GROUP_CONCAT(ps.sizeName ORDER BY ps.sizeName ASC SEPARATOR ', '), '-') AS availableSizes,
           p.soldQuantity, COALESCE(pr.name, '-') AS promotionName, c.categoryname AS categoryName,
           pp.photo_path AS productPhoto
    FROM product p
    LEFT JOIN promotion pr ON p.promotionID = pr.promotionid
    LEFT JOIN category c ON p.categoryID = c.categoryid
    LEFT JOIN product_size ps ON p.productID = ps.productID
    LEFT JOIN product_photo pp ON p.productID = pp.productID AND pp.photo_order = 1 
    WHERE 1=1 $filter_conditions
    GROUP BY p.productID, p.productName, p.productDesc, p.price, p.stockQuantity, p.soldQuantity, 
             pr.name, c.categoryname, pp.photo_path
    ORDER BY $sort $dir
";



// Initialize pager
$p = new SimplePager($query, $parameters, 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Manage Products';
?>

<style>
    .low-stock {
        background-color: #f7f2ba;
    }

    .badge-warning {
        background-color: #ffc107;
        padding: 2px 5px;
        border-radius: 3px;
    }
</style>

<div class="button-container">
    <button type="button" class="btn btn-primary add-product-btn">Add Product</button>
    <button type="submit" class="btn btn-danger" id="batch-delete-btn" data-term="product">Delete Selected</button>
</div>
</div>


<!-- Filter form -->
<form id="filterForm" method="GET" style="display: inline-block; margin-right: 20px;margin-left:20px;">
    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="search">Search:</label>
        <input type="text" style="border: 2px solid #007bff; padding: 5px; border-radius: 4px; width:200px;" name="search" id="search" class="form-control" value="<?= htmlspecialchars(req('search', '')) ?>" placeholder="Search by name or description">
    </div>
    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="categoryID">Category:</label>
        <select name="categoryID" id="categoryID" style="border: 2px solid #007bff; padding: 5px; border-radius: 4px;" class="form-control">
            <option value="">All Categories</option>
            <?php
            $stmt = $_db->query("SELECT categoryID, categoryname FROM category");
            $categories = $stmt->fetchAll(PDO::FETCH_OBJ);

            foreach ($categories as $category) {
                $selected = ($categoryID == $category->categoryID) ? 'selected' : '';
                echo '<option value="' . $category->categoryID . '" ' . $selected . '>' . htmlspecialchars($category->categoryname) . '</option>';
            }
            ?>
        </select>
    </div>
    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="min_price">Min Price (RM):</label>
        <input type="number" style="border: 2px solid #007bff; padding: 5px; border-radius: 4px;" name="min_price" id="min_price" class="form-control" value="<?= htmlspecialchars($min_price) ?>" placeholder="Min Price">
    </div>
    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="max_price">Max Price (RM):</label>
        <input type="number" style="border: 2px solid #007bff; padding: 5px; border-radius: 4px;" name="max_price" id="max_price" class="form-control" value="<?= htmlspecialchars($max_price) ?>" placeholder="Max Price">
    </div>
    <button type="submit" class="btn btn-primary">Filter</button>
</form>

<br>

<!-- Products Table -->
<table class="table" style="font-size:14px;">
    <tr>
        <th><input type="checkbox" id="select-all"></th>
        <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    </tr>
    <?php if (count($arr) > 0): ?>
        <?php foreach ($arr as $product) : ?>

            <?php
            $isLowStock = $product->stockQuantity < 10;
            ?>
            <tr class="product-row <?= $isLowStock ? 'low-stock' : '' ?>" data-photo="<?= htmlspecialchars($product->productPhoto) ?>">

                <td><input type="checkbox" name="productID[]" value="<?= $product->productID ?>"></td>
                <td><?= htmlspecialchars($product->productID) ?></td>
                <td><img src="images/<?= htmlspecialchars($product->productPhoto) ?>" alt="Photo" style="width:50px;height:50px;"></td>
                <td><?= htmlspecialchars($product->productName) ?></td>
                <td><?= htmlspecialchars($product->productDesc) ?></td>
                <td><?= htmlspecialchars($product->categoryName) ?></td>
                <td><?= htmlspecialchars($product->availableSizes) ?></td>
                <td><?= htmlspecialchars($product->price) ?></td>
                <td><?= htmlspecialchars($product->stockQuantity) ?><?php if ($isLowStock): ?>
                    <span class="badge badge-warning">Low Stock</span>
                <?php endif; ?>
                </td>
                <!--<td><?= htmlspecialchars($product->promotionName) ?></td>-->
                <td>
                    <a href="viewProduct.php?productID=<?= $product->productID ?>" class="btn btn-unblock">View</a>
                    <button type="button" class="btn btn-primary edit-product-btn" data-id="<?= $product->productID ?>">Edit</button>
                    <button type="button" class="btn btn-danger delete-product-btn" data-id="<?= $product->productID ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php else: ?>
        <tr>
            <td colspan="11">No products found matching the filter criteria.</td>
        </tr>
    <?php endif; ?>
</table>

<br>

<div class="pagination-info">
    <p>
        <?= $p->count ?> of <?= $p->item_count ?> record(s) |
        Page <?= $p->page ?> of <?= $p->page_count ?>
    </p>
    <div class="pagination-controls">
        <?= $p->html("sort=$sort&dir=$dir") ?>
    </div>
</div>

<div id="edit-form-container" style="margin-top:40px;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link rel="stylesheet" href="css/header.css">

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($isLowStock): ?>
            alert('Product <?= htmlspecialchars($product->productName) ?> is running low on stock.');
        <?php endif; ?>
    });
</script>