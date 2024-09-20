<?php
require 'base.php';

$_title = 'Batch Insertion (Text File)';
include 'header.php';

?>


<?php
if (isset($_SESSION['insertionSuccessful'])) ?>
<script>
    alert('<?= addslashes($_SESSION['insertionSuccessful']) ?>');
</script>
<?php unset($_SESSION['insertionSuccessful']); ?>

<link rel="stylesheet" href="../css/admin.css">

</div>

<div id="edit-form-container" style="margin-left:0;width:40%;">
<form action="uploadBatchInsertion.php" method="post" enctype="multipart/form-data">
    <?php

    html_txt_upload('adminsFile', 'Admins File');
    html_txt_upload('membersFile', 'Members File');
    html_txt_upload('categoriesFile', 'Categories File');
    html_txt_upload('promotionsFile', 'Promotions File');
    html_txt_upload('productsFile', 'Products File');
    html_txt_upload('photosFile', 'Product Photos File');
    html_txt_upload('videosFile', 'Product Videos File');
   
    html_submit('submitBtn', 'Upload');
    ?>

</form>
</div>