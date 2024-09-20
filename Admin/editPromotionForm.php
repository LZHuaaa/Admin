<link rel="stylesheet" href="../css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/admin.js"></script>


<?php
require 'base.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $_db->prepare("SELECT * FROM promotion WHERE promotionid = ?");
    $stmt->execute([$id]);
    $promotion = $stmt->fetch();

    if ($promotion) {

?>

        <button type="button" class="btn-close" id="closeBtn"> Close </button>

        <form method="POST" id="editPromotionForm123" enctype="multipart/form-data" action="updatePromotion.php">
            <?php

            html_hidden('promotionid', $promotion->promotionID);

            html_text2('promotionName', 'Promotion Name', $promotion->name, 'required');

            html_number2('discountRate', 'Discount Rate(%)', $promotion->discountRate, 'step="0.01" min="0" max="100" required');

            html_textarea('promotionDescription', 'Promotion Description', $promotion->description);

            html_date('startDate', 'Start Date', 'required', $promotion->startDate);

            html_date('endDate', 'End Date', 'required', $promotion->endDate);
            html_text2('promo_code', 'Promo Code', $promotion->promo_code, 'required');
            html_select('status', 'Status', ['Active' => 'Active', 'Inactive' => 'Inactive'], $promotion->status, 'required');

            html_file('image', 'Promotion Image', $promotion->promotionImage, 'accept="image/*"');
            html_hidden('imagePath', $promotion->promotionImage); ?>

            <img id="imagePreview" src="../images/<?= htmlspecialchars($promotion->promotionImage) ?>" alt="Image Preview" style="width:150px;height:150px;">

            <?php

            html_submit('submitBtn', 'Update Promotion');
            ?>

        </form>
<?php
    } else {
        echo "Promotion not found.";
    }
} else {
    echo "No ID specified.";
}
?>
</body>

<script>
$(document).ready(function() {
    $(document).on("submit", "#editPromotionForm123", function (event) {
        console.log("Script is running");
        event.preventDefault(); 
        
        var startDate = new Date($("#startDate").val());
        var endDate = new Date($("#endDate").val());
      
        // Validate dates
        if (endDate < startDate) {
            alert("Error: End date cannot be earlier than start date.");
            return false; 
        }
      
        // Confirm update
        var confirmUpdate = confirm("Are you sure you want to update?");
        if (confirmUpdate) {
            // Proceed with form submission
            this.submit(); // This will submit the form to updatePromotion.php
        }
    });
});
</script>
