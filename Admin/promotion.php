<?php
require 'base.php';

$_title = 'Manage Promotions';
include 'header.php';

// (1) Sorting
$fields = [
    'promotionID'      => 'ID',
    'name'             => 'Promotion Name',
    'description'      => 'Description',
    'discountRate'     => 'Discount Rate',
    'startDate'        => 'Start Date',
    'endDate'          => 'End Date',
    'promotionImage'   => 'Image',
    'status'           => 'Status',
    'promo_code'       => 'Promo Code',
    'Action'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'promotionID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once '../lib/SimplePager.php';

$p = new SimplePager("SELECT * FROM promotion ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Manage Promotions';
?>

<?php
if (isset($_SESSION['message'])) ?>
<script>
    alert('<?= addslashes($_SESSION['message']) ?>');
</script>
<?php unset($_SESSION['message']); ?>


<div class="button-container">
    <button type="button" class="btn btn-primary add-promotion-btn">Add Promotion</button>
    <button type="button" id="batch-update-promotion-status" class="btn btn-warning">Update Status</button>
    <button type="submit" class="btn btn-danger" id="batch-delete-btn" data-term="promotion">Delete Selected</button>
</div>
</div>

<!--filter promotion-->
<div id="batch-update-promotion-section" style="display: none; margin-top: 20px;">
    <label for="new-status">Select New Status:</label>
    <select id="new-status" name="newStatus" class="form-control" style="border: 2px solid #007bff; padding: 5px; border-radius: 4px;">
        <option value="Active">Active</option>
        <option value="Inactive">Inactive</option>
    </select>
    <button type="submit" id="batch-update-promotion-submit" class="btn btn-warning">Update</button>
</div>

<form id="batchUpdatePromotionForm" method="POST" action="batchUpdatePromotions.php">
    <table class="table" style="font-size:15px;">
        <tr>
            <th><input type="checkbox" id="select-all-promotion"></th>
            <?= table_headers($fields, $sort, $dir, "page=$page") ?>
        </tr>

        <?php foreach ($arr as $promo) : ?>
            <tr>
                <td>
                    <input type="checkbox" name="promotionID[]" value="<?= $promo->promotionID ?>">
                </td>
                <td><?= $promo->promotionID ?></td>
                <td><?= htmlspecialchars($promo->name) ?></td>
                <td><?= htmlspecialchars($promo->description) ?></td>
                <td><?= htmlspecialchars($promo->discountRate) ?>%</td>
                <td><?= htmlspecialchars($promo->startDate) ?></td>
                <td><?= htmlspecialchars($promo->endDate) ?></td>
                <td><img src="../images/<?= htmlspecialchars($promo->promotionImage) ?>" alt="Promotion Image" style="width:100px;height:100px;"></td>
                <td><?= htmlspecialchars($promo->status) ?></td>
                <td><?= htmlspecialchars($promo->promo_code) ?></td>
                <td>
                    <button type="button" class="btn btn-primary edit-promotion-btn" data-id="<?= $promo->promotionID ?>">Edit</button>
                    <button type="button" class="btn btn-danger delete-promotion-btn" data-id="<?= $promo->promotionID ?>">Delete</button>
                </td>
            </tr>
        <?php endforeach ?>
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

    <!-- This div will hold the edit form -->
    <div id="edit-form-container" style="margin-top:40px;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/admin.js"></script>
    <link rel="stylesheet" href="../css/header.css">