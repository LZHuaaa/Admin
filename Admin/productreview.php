<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="../css/header.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<?php
require 'base.php';

$_title = 'Manage Reviews';
include 'header.php';

// (1) Sorting
$fields = [
    'reviewID' => 'Review ID',
    'username' => 'User Name',
    'productName' => 'Product Name',
    'rating' => 'Rating',
    'reviewText' => 'Review',
    'reviewed_at' => 'Reviewed At'
];

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'reviewID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once '../lib/SimplePager';

// Search filter
$search = req('search', '');

// Search condition
$filter_conditions = '';
$parameters = [];
if (!empty($search)) {
    $filter_conditions .= " AND (u.username LIKE :search OR p.productName LIKE :search)";
    $parameters['search'] = '%' . $search . '%';
}

// Query to fetch reviews
$query = "
    SELECT pr.reviewID, u.username, p.productName, pr.rating, pr.reviewText, pr.reviewed_at 
    FROM productreviews pr
    JOIN user u ON pr.userID = u.userID
    JOIN product p ON pr.productID = p.productID
    WHERE 1=1 $filter_conditions
    ORDER BY $sort $dir
";

$p = new SimplePager($query, $parameters, 10, $page);
$arr = $p->result;


$stmt = $_db->query("SELECT rating, reviewed_at FROM productreviews ORDER BY reviewed_at");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);


$dates = [];
$ratingsByDate = [];

foreach ($reviews as $review) {
    $date = date('Y-m-d', strtotime($review['reviewed_at'])); // Group by day
    if (!isset($ratingsByDate[$date])) {
        $ratingsByDate[$date] = [];
    }
    $ratingsByDate[$date][] = $review['rating'];
}


$averageRatings = [];
foreach ($ratingsByDate as $date => $ratings) {
    $averageRatings[$date] = array_sum($ratings) / count($ratings);
}


$dates = array_keys($averageRatings);
$averageRatings = array_values($averageRatings);


$stmt = $_db->query("SELECT rating FROM productreviews");
$ratings = $stmt->fetchAll(PDO::FETCH_COLUMN);


$totalReviews = count($ratings);
$goodReviews = array_filter($ratings, function ($rating) {
    return $rating >= 4; // Consider ratings of 4 and 5 as good
});
$goodPercentage = ($totalReviews > 0) ? (count($goodReviews) / $totalReviews) * 100 : 0;
$badPercentage = 100 - $goodPercentage;


$averageRating = ($totalReviews > 0) ? array_sum($ratings) / $totalReviews : 0;
?>



<?php if ($message): ?>
    <script>
        alert('<?= addslashes($message) ?>');
    </script>
<?php endif; ?>

</div>

<div class="charts-container">
    <div class="chart-container canvas-container" style="width:50%">
        <canvas id="reviewChart" width="100" height="100" style="margin-left:120px;"></canvas>
        <div id="averageRating"></div>
    </div>

    <div class="chart-container canvas-container" style="width:50%">
        <canvas id="ratingGrowthChart" width="400" height="400" style="margin-left:60px;"></canvas>
    </div>
</div>

<!-- Reviews Table -->
<form id="batchDeleteForm" method="POST" action="batchDeleteReviews.php">
    <table class="table" style="font-size:15px;">
        <tr>
            <?= table_headers($fields, $sort, $dir, "page=$page") ?>
        </tr>

        <?php if (count($arr) > 0): ?>
            <?php foreach ($arr as $review) : ?>
                <tr>
                    <td><?= htmlspecialchars($review->reviewID) ?></td>
                    <td><?= htmlspecialchars($review->username) ?></td>
                    <td><?= htmlspecialchars($review->productName) ?></td>
                    <td><?= htmlspecialchars($review->rating) ?></td>
                    <td><?= htmlspecialchars($review->reviewText) ?></td>
                    <td><?= date('d-m-Y H:i:s', strtotime($review->reviewed_at)) ?></td>
                
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No reviews found matching the filter criteria.</td>
            </tr>
        <?php endif; ?>
    </table>
</form>

<br>




<script>
    $(document).ready(function() {

        var dates = <?php echo json_encode($dates); ?>;
        var averageRatings = <?php echo json_encode($averageRatings); ?>;

        var ctxGrowth = document.getElementById('ratingGrowthChart').getContext('2d');
        var ratingGrowthChart = new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [{
                    label: 'Average Rating Over Time',
                    data: averageRatings,
                    borderColor: '#4caf50',
                    fill: false,
                    borderWidth: 2,
                    tension: 0.1
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
                                return 'Average Rating: ' + tooltipItem.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Average Rating'
                        },
                        min: 0,
                        max: 5
                    }
                }
            }
        });
    });

    $(document).ready(function() {
        // Fetch data from PHP directly into JavaScript variables
        var goodPercentage = <?php echo json_encode($goodPercentage); ?>;
        var badPercentage = <?php echo json_encode($badPercentage); ?>;
        var averageRating = <?php echo json_encode($averageRating); ?>;

        // Set up the chart
        var ctx = document.getElementById('reviewChart').getContext('2d');
        var reviewChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Good Reviews', 'Bad Reviews'],
                datasets: [{
                    label: 'Review Percentage',
                    data: [goodPercentage, badPercentage],
                    backgroundColor: ['#4caf50', '#f44336'],
                    borderColor: ['#ffffff', '#ffffff'],
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
                                return tooltipItem.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                            }
                        }
                    }
                }
            }
        });

        $('#averageRating').text('Average Rating: ' + averageRating.toFixed(2));
    });
</script>
</body>

</html>