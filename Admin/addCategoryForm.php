<link rel="stylesheet" href="../css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

<?php
require 'base.php';

?>

<button type="button" class="btn-close" id="closeBtn">Close</button>
<form id="addCategoryForm" method="POST" enctype="multipart/form-data">
    <?php
    html_text('categoryName', 'Category Name', 'required');

    html_submit('submitAddBtn', 'Add');
    ?>

</form>