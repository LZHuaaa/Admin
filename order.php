<?php
require 'base.php';

$_title = 'Manage Orders'; 
include 'header.php';

// (1) Sorting
$fields = [
    'orderID'         => 'ID',
    'orderDate'       => 'Order Date',
    'fullname'        => 'Full Name',
    'items'     => 'Total Items',
    'total'           => 'Total (RM)',
    'addressLine1'    => 'Shipping Address',
    'status'          => 'Order Status',
    'Action'
];

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'orderID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once 'lib/SimplePager';


$userID = req('userID', '');
$status = req('status', '');
$start_date = req('start_date', '');
$end_date = req('end_date', '');

$filter_conditions = "";
$parameters = [];

if (!empty($userID)) {
    $filter_conditions .= " AND o.userID = :userID";
    $parameters['userID'] = $userID;
}
if (!empty($status)) {
    $filter_conditions .= " AND o.status = :status";
    $parameters['status'] = $status;
}
if (!empty($start_date) && !empty($end_date)) {
    $filter_conditions .= " AND o.orderDate BETWEEN :start_date AND :end_date";
    $parameters['start_date'] = $start_date;
    $parameters['end_date'] = $end_date;
}

// Search
$search = req('search', '');
if (!empty($search)) {
    $filter_conditions .= " AND (u.username LIKE :search OR u.fullname LIKE :search)";
    $parameters['search'] = '%' . $search . '%';
}

$query = "
    SELECT o.orderID, o.orderDate, u.username, u.fullname, u.email, u.role, o.total, o.status, 
           sa.addressLine1, sa.addressLine2, sa.city, sa.state, sa.postalCode,
           SUM(od.qty) AS totalItems
    FROM orders o
    LEFT JOIN user u ON o.userID = u.userID
    LEFT JOIN shipping_address sa ON o.addressID = sa.addressID
    LEFT JOIN order_detail od ON o.orderID = od.orderID
    WHERE 1=1 $filter_conditions
    GROUP BY o.orderID
    ORDER BY $sort $dir
";




// Initialize pager
$p = new SimplePager($query, $parameters, 10, $page);
$arr = $p->result;

$_title = 'Manage Orders';
?>

<div class="button-container">
    <button type="button" class="btn btn-primary add-order-btn">Add Order</button>
    <button type="button" id="batch-update-status" class="btn btn-warning">Batch Update Status</button>
    <button type="button" id="batch-delete-btn" class="btn btn-danger" data-term="order">Delete Selected</button>

</div>
</div>

<!-- Filter form -->

<div id="batch-update-section" style="display: none; margin-top: 20px;">
    <label for="new-status">Select New Status:</label>
    <select id="new-status" name="newStatus" class="form-control" style="border: 2px solid #007bff; padding: 5px; border-radius: 4px;">
        <option value="Processing">Processing</option>
        <option value="Shipped">Shipped</option>
        <option value="Completed">Completed</option>
        <option value="Cancelled">Cancelled</option>
    </select>
    <button type="submit" id="batch-update-submit" class="btn btn-warning">Update</button>
</div>
<!--
    <br>
<form id="filterForm" method="GET" style="display: inline-block; margin-right: 20px;margin-left:20px;">
    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="search">Search User:</label>
        <input type="text" style="width:200px;" name="search" id="search" class="form-control" value="<?= htmlspecialchars(req('search', '')) ?>" placeholder="Search by username or full name">
    </div>

    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="status">Order Status:</label>
        <select name="status" id="status" class="form-control">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="shipped">Shipped</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= htmlspecialchars($start_date) ?>">
    </div>

    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= htmlspecialchars($end_date) ?>">
    </div>

    <button type="submit" class="btn btn-primary">Filter</button>
</form> -->


<?php if ($message): ?>
    <script>
        alert('<?= addslashes($message) ?>');
    </script>
<?php endif; ?>

<!-- Orders Table -->
<form id="batchUpdateForm" method="POST" action="batchUpdateOrders.php">
    <table class="table" style="font-size:15px;">
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <?= table_headers($fields, $sort, $dir, "page=$page") ?>
        </tr>

        <?php if (count($arr) > 0): ?>
            <?php foreach ($arr as $order) : ?>
                <tr>
                    <td>
                        <input type="checkbox" name="orderID[]" value="<?= $order->orderID ?>">
                    </td>
                    <td><?= htmlspecialchars($order->orderID) ?></td>
                    <td><?= date('d-m-Y', strtotime($order->orderDate)) ?></td>
                    <td><?= htmlspecialchars($order->fullname) ?></td>
                    <td><?= htmlspecialchars($order->totalItems) ?> </td>
                    <td><?= htmlspecialchars($order->total) ?></td>
                    <td><?= htmlspecialchars($order->addressLine1 . ', ' . $order->addressLine2 . ', ' . $order->postalCode . ', ' . $order->city . ', ' . $order->state) ?></td>
                    <td><?= htmlspecialchars($order->status) ?></td>


                    <td>
                        <a href="viewOrderDetails.php?orderID=<?= $order->orderID ?>" class="btn btn-unblock">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="10">No orders found matching the filter criteria.</td>
            </tr>
        <?php endif; ?>
    </table>




</form>

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
<!-- This div will hold the order details -->
<div id="order-details-container" style="margin-top:40px;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link rel="stylesheet" href="css/header.css">