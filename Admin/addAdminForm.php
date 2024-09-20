<link rel="stylesheet" href="../css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

<?php
require 'base.php';
?>


<button type="button" class="btn-close" id="closeBtn">Close</button>
<form id="addAdminForm" method="POST" enctype="multipart/form-data">
    <?php
    html_text('username', 'Username', 'required');
    html_text('fullname', 'Full Name', 'required');
    html_email('email', 'Email', '', 'Enter a valid email address.');
    html_password('password', 'Password', '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}', 'Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters', 'required');
    html_password('confirmPassword', 'Confirm Password', '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}', 'Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters', 'required');
    html_select('status', 'Status', ['Active' => 'Active', 'Inactive' => 'Inactive']);
    html_file('image', 'Profile Image','', 'accept="image/*" required'); ?>
    <img id="imagePreview" src="../images/photo.jpg" alt="Image Preview" style="display:block;width:150px;height:150px;">

    <?php html_submit('submitAddBtn', 'Add');
    ?>

</form>
