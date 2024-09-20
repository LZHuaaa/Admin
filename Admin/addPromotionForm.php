<link rel="stylesheet" href="../css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/admin.js"></script>

<?php
require 'base.php';
?>



<button type="button" class="btn-close" id="closeBtn">Close</button>


<form id="addPromotionForm" method="POST" enctype="multipart/form-data">
    <?php
    
    html_text('name', 'Promotion Name', 'required');
    html_textarea('description', 'Promotion Description', '');
    html_number('discountRate', 'Discount Rate (%)', 'step="0.01" min="0" max="100" required');
    html_date('startDate', 'Start Date', 'required');
    html_date('endDate', 'End Date', 'required');
    html_text('promo_code', 'Promo Code', 'required');
    html_select('status', 'Status', ['Active' => 'Active', 'Inactive' => 'Inactive']);
    html_file('promotionImage', 'Promotion Image','','accept="image/*" required');
    
    ?>

    <div id="imagePreviewContainer"></div>

    
    <?php html_submit('submitAddBtn', 'Add Promotion'); ?>
</form>

<script>

$('#promotionImage').on('change', function() {
    let reader = new FileReader();
    reader.onload = function(e) {
        $('#imagePreviewContainer').html('<img src="' + e.target.result + '" alt="Promotion Image" style="max-width: 100px; max-height: 100px;">');
    };
    reader.readAsDataURL(this.files[0]);
});

</script>
