<?php
require 'base.php';

$_title = 'Product Reviews';
include 'header.php';



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

<link rel="stylesheet" href="../css/admin.css">
<link rel="stylesheet" href="../css/header.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

</div>


<div class="charts-container">
    <div class="chart-container canvas-container" style="width:40%">
        <canvas id="reviewChart" width="100" height="100" style="margin-left:60px;"></canvas>
        <div id="averageRating"></div>
    </div>


    <script>
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