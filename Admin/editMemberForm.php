<link rel="stylesheet" href="../css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/admin.js"></script>


<?php
require 'base.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];


    $stmt = $_db->prepare("SELECT * FROM user WHERE userid = ?");
    $stmt->execute([$id]);
    $member = $stmt->fetch();

    if ($member) {
?>

        <button type="button" class="btn-close" id="closeBtn"> Close </button>
        <form method="POST" id="editMemberForm">
            <?php
            html_hidden('id', $member->userID);
            html_text2('username', 'Username', $member->username, 'required');
            html_text2('fullname', 'Full Name', $member->fullname, 'required');
            html_email2('email', 'Email', $member->email, '', 'Enter a valid email address.');
            //html_password1('newPassword', 'password', 'New Password', 'pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters" required');
            //html_password1('confirmPassword', 'confirmPassword', 'Confirm Password', 'pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters" required');
            //html_select('status', 'Status', ['Active' => 'Active', 'Blocked' => 'Blocked'], $member->status, 'required');
            html_file('image', 'Profile Image', $member->photo, 'accept="image/*"');
            html_hidden('photo', $member->photo); ?>
            <img id="imagePreview" src="../images/<?= htmlspecialchars($member->photo) ?>" alt="Image Preview" style="width:150px;height:150px;">


            <?php
            html_submit('submitBtn', 'Update');
            ?>


        </form>
<?php
    } else {
        echo "Member not found.";
    }
} else {
    echo "No ID specified.";
}
?>