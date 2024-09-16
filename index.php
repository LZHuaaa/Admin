<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script> 

<?php
require 'base.php';

$_title = 'Dashboard';
include 'header.php';

// Top 5 Selling Products
$stmt = $_db->query("
    SELECT productName, soldQuantity
    FROM product
    ORDER BY soldQuantity DESC
    LIMIT 5
");
$topSellingProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total Sales
$totalSalesStmt = $_db->query("
    SELECT SUM(total) AS totalSales
    FROM orders
    WHERE status = 'completed'
");
$totalSales = $totalSalesStmt->fetch(PDO::FETCH_ASSOC)['totalSales'];

//Total Product Sold
$totalProductStmt = $_db->query("
    SELECT SUM(soldQuantity) AS totalProduct
    FROM product
");
$totalProduct = $totalProductStmt->fetch(PDO::FETCH_ASSOC)['totalProduct'];

//Total Order
$totalOrderStmt = $_db->query("
    SELECT COUNT(*) AS totalOrder
    FROM ORDERS;
");
$totalOrder = $totalOrderStmt->fetch(PDO::FETCH_ASSOC)['totalOrder'];

// Sales by Category
$salesByCategoryStmt = $_db->query("
    SELECT c.categoryName, SUM(p.soldQuantity) AS totalSold
    FROM product p
    JOIN category c ON p.categoryID = c.categoryID
    GROUP BY c.categoryName
");
$salesByCategory = $salesByCategoryStmt->fetchAll(PDO::FETCH_ASSOC);

// Sales Trendsssss
$salesTrendsStmt = $_db->query("
    SELECT DATE_FORMAT(orderDate, '%Y-%m') AS month, SUM(total) AS totalSales
    FROM orders
    WHERE status = 'completed'
    GROUP BY DATE_FORMAT(orderDate, '%Y-%m')
    ORDER BY month
");
$salesTrends = $salesTrendsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    #salesByCategoryChart,
    #salesTrendsChart {
        max-width: 100%;
        height: auto;
    }

    .chart-container {
        text-align: center;
        margin: 10px auto;
    }

    .charts-container {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
    }

    .total-sales-container,
    .top-selling-products-container {
        text-align:center;
        width: 30%;
        border: 2px solid #ddd;
        padding:20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        box-sizing: border-box;
    }

    .total-sales {
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }

    .chart-container {
        width: 48%;
        margin: 10px 0;
        border: 2px solid #ddd;
        border-radius: 8px;
        padding: 20px;
        padding-bottom:50px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .chart-container.canvas-container {
        max-height: 400px;
    }
</style>

</div>

<div class="charts-container">
    <div class="total-sales-container">
        <h2>Total Sales</h2>
        <p class="total-sales">RM <?= number_format($totalSales, 2) ?></p>
    </div>

    <div class="total-sales-container">
        <h2>Total Product Sold</h2>
        <p class="total-sales"><?= number_format($totalProduct) ?> units</p>
    </div>

    <div class="total-sales-container">
        <h2>Total Orders</h2>
        <p class="total-sales"><?= number_format($totalOrder) ?></p>
    </div>
   
</div>

<div class="charts-container" style="margin-top:40px;">
<div class="chart-container canvas-container">
    <h2>Top 5 Selling Products</h2>
    <canvas id="topSellingProductsChart" width="500" height="200"></canvas>
</div>

<div class="chart-container canvas-container">
    <h2>Sales by Category</h2>
    <canvas id="salesByCategoryChart" width="500" height="350"></canvas>
</div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        var ctxTopProducts = document.getElementById('topSellingProductsChart').getContext('2d');
        var topSellingProductsChart = new Chart(ctxTopProducts, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($topSellingProducts, 'productName')); ?>,
                datasets: [{
                    label: 'Top 5 Selling Products',
                    data: <?php echo json_encode(array_column($topSellingProducts, 'soldQuantity')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' units sold';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Units Sold'
                        },
                        beginAtZero: true
                    },
                    y: {
                        title: {
                    
                        }
                    }
                }
            }
        });

        var ctxCategory = document.getElementById('salesByCategoryChart').getContext('2d');
        var salesByCategoryChart = new Chart(ctxCategory, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($salesByCategory, 'categoryName')); ?>,
                datasets: [{
                    label: 'Sales by Category',
                    data: <?php echo json_encode(array_column($salesByCategory, 'totalSold')); ?>,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw + ' units';
                            }
                        }
                    }
                }
            }
        });

        var ctxTrends = document.getElementById('salesTrendsChart').getContext('2d');
        var salesTrendsChart = new Chart(ctxTrends, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($salesTrends, 'month')); ?>,
                datasets: [{
                    label: 'Monthly Sales',
                    data: <?php echo json_encode(array_column($salesTrends, 'totalSales')); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': RM ' + tooltipItem.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Total Sales (RM)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
