<link href="css/member.css" rel="stylesheet" />
<?php
require_once 'base.php'; // Use require_once to avoid multiple inclusions

$_title = 'Manage Member';
include 'header.php';

// (1) Sorting
$fields = [
    'userID' => 'Id',
    'username' => 'Username',
    'fullname' => 'Full Name',
    'email' => 'Email',
    'photo' => 'Photo',
    'status' => 'Status',
    'Action'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'userID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);


// (3) Searching
$search = req('search');
?>

<button style="font-size:15px;margin-bottom:20px;" class="btn btn-primary add-member-btn">Add Member</button>
<button type="submit" class="btn btn-danger" id="batch-delete-btn">Delete Selected</button>



<!-- Search Bar -->

<form id="search-form" method="get" style="margin-bottom:20px;">
    <input type="text" name="search" placeholder="Search by username, full name, or email" value="<?= htmlspecialchars($search) ?>">
    <button type="submit" class="btn btn-primary">Search</button>
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>">
</form>

<div id="member-table">
    <?php include 'memberTable.php'; ?>
</div>

<div id="edit-form-container" style="margin-top:40px;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>

