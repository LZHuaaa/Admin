<link rel="stylesheet" href="css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

<?php
require 'base.php';

if (!isset($_GET['id'])) {
    die("Admin ID not specified.");
}

$adminId = $_GET['id'];
$username = $_GET['username'];
$role = $_GET['role'];

?>


    <h2 style="display:inline;">Reset Password For <?= htmlspecialchars($username) ?></h2>
    <button type="button" class="btn-close" id="closeBtn">Close</button>


<form id="resetPasswordForm" method="POST">
    <?php html_hidden('id', htmlspecialchars($adminId)); ?>
    <?php html_hidden('role', htmlspecialchars($role)); ?>

    <?php
    html_password1('newPassword', 'password', 'New Password', 'pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters" required');
    html_password1('confirmPassword', 'confirmPassword', 'Confirm Password', 'pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters" required');
    ?>

    <?php html_submit('submitResetPasswordBtn', 'Reset Password'); ?>
</form>