<?php
require 'base.php';

$orderID = req('orderID');

if (empty($orderID)) {
    die("Invalid order ID.");
}

$query = "
    SELECT o.orderID, o.orderDate, o.total, o.status,u.fullname, u.email, sa.addressLine1, sa.addressLine2, sa.city, sa.state, sa.postalCode, pm.paymentType
    FROM orders o
    LEFT JOIN user u ON o.userID = u.userID
    LEFT JOIN payment p ON o.paymentID = p.paymentID
    LEFT JOIN payment_method pm ON p.paymentMethodID = pm.paymentMethodID
    LEFT JOIN shipping_address sa ON o.addressID = sa.addressID
    WHERE o.orderID = :orderID
";

$stmt = $_db->prepare($query);
$stmt->execute(['orderID' => $orderID]);
$order = $stmt->fetch(PDO::FETCH_OBJ);

if (!$order) {
    die("Order not found.");
}


$query = "
    SELECT od.orderDetailID, p.productName, od.qty,od.size,od.price 
    FROM order_detail od
    LEFT JOIN product p ON od.productID = p.productID
    WHERE od.orderID = :orderID
";
$stmt = $_db->prepare($query);
$stmt->execute(['orderID' => $orderID]);
$orderItems = $stmt->fetchAll(PDO::FETCH_OBJ);

$_title = 'Order #' . htmlspecialchars($order->orderID);
include 'header.php';

?>

<style>
    .order-container {
        width: 100%;
        margin: 20px auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .order-header {
        font-size: 20px;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .order-details-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .order-details,
    .shipping-address {
        width: 48%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        background-color: #fff;
    }

    .order-details p,
    .shipping-address p {
        margin: 5px 0;
        font-size: 16px;
    }

    .order-items {
        width: 100%;
        margin-top: 20px;
        border-collapse: collapse;
    }

    .order-items th,
    .order-items td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: left;
    }

    .order-items th {
        background-color: #f0f0f0;
    }

    .back-btn {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
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

<div class="order-container">

    <div class="order-details-container">
        <!-- Order Details Section -->
        <div class="order-details">
            <h2>Order Details</h2>
            <p><strong>Customer Name:</strong> <?= htmlspecialchars($order->fullname) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($order->email) ?></p>
            <p><strong>Order Date:</strong> <?= htmlspecialchars($order->orderDate) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars($order->paymentType) ?></p>
            <p><strong>Total:</strong> RM<?= htmlspecialchars($order->total) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($order->status) ?></p>
        </div>

        <!-- Shipping Address Section -->
        <div class="shipping-address">
            <h2>Shipping Address</h2>
            <p><?= htmlspecialchars($order->addressLine1) ?></p>
            <p><?= htmlspecialchars($order->addressLine2) ?></p>
            <p><?= htmlspecialchars($order->city) ?>, <?= htmlspecialchars($order->state) ?>, <?= htmlspecialchars($order->postalCode) ?></p>
        </div>
    </div>
    <br>
    <h2>Order Items</h2>
    <table class="order-items">
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Size</th>
            <th>Price (RM)</th>
            <th>Total (RM)</th>
        </tr>

        <?php if (count($orderItems) > 0): ?>
            <?php foreach ($orderItems as $item) : ?>
                <tr>
                    <td><?= htmlspecialchars($item->productName) ?></td>
                    <td><?= htmlspecialchars($item->qty) ?></td>
                    <td><?= !empty($item->size) ? htmlspecialchars($item->size) : '-' ?></td>
                    <td><?= htmlspecialchars($item->price) ?></td>
                    <td><?= htmlspecialchars($item->qty * $item->price) ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">No items found for this order.</td>
            </tr>
        <?php endif; ?>
    </table>

    <a href="order.php" class="back-btn">Back to Orders</a>
</div>