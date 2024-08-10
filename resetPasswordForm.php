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

?>

<h2>Reset Password For <?= $username ?></h2>
<button type="button" class="btn-close" id="closeBtn">Close</button>

<form id="resetPasswordForm" action="resetPassword.php" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($adminId) ?>">

    <div class="form-group">
        <label for="newPassword">New Password</label>
        <input type="password" class="form-control" id="password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters" required>
    </div>

    <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters" required>
    </div>

    <button type="submit" class="btn-success" id="submitResetPasswordBtn">Reset Password</button>
</form>
