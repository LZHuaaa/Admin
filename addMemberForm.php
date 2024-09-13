<link rel="stylesheet" href="css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

<?php
require 'base.php';


/*if(is_post()) {
    $f = get_file('image');

    // Validate: photo (file)
    if ($f == null) {
        $_err['photo'] = 'Required';
    } else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    } else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }

    if (!$_err) {
        //move_uploaded_file($f->tmp_name, "uploads/$f->name");

        $photo = uniqid() . ' .jpg';

        require_once 'lib/SimpleImage.php';
        $img = new SimpleImage();
        $img->fromFile($f->tmp_name)
        ->thumbnail(200,200)
        ->toFile("uploads/$photo",'image/jpeg');

        temp('info', 'Photo uploaded');
        redirect();
    }
}*/


?>


<button type="button" class="btn-close" id="closeBtn">Close</button>
<form id="addMemberForm" method="POST" enctype="multipart/form-data">
    <?php
    html_text('username', 'Username', 'required');
    html_text('fullname', 'Full Name', 'required');
    html_email('email', 'Email', '', 'Enter a valid email address.');   
    html_password('password', 'Password', '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}', 'Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters', 'required');
    html_password('confirmPassword', 'Confirm Password', '(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{7,}', 'Must contain at least one number and one uppercase and lowercase letter, and at least 7 or more characters', 'required');
    html_select('status', 'Status', ['Active' => 'Active', 'Inactive' => 'Inactive']);
    html_file('image', 'Profile Image', 'accept="image/*" required'); ?>

    <img id="imagePreview" src="/images/photo.jpg" alt="Image Preview" style="display:block;">

    <?php html_submit('submitAddBtn', 'Add');  ?>

</form>


<!--<div class="form-group">
        <label for="image">Profile Image</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
        <img id="imagePreview" src="/images/photo.jpg" alt="Image Preview" style="display:block;">
    </div>-->