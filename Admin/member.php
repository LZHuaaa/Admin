<link rel="stylesheet" href="../css/header.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/admin.js"></script>
<?php
require_once 'base.php';

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

<div class="button-container">
    <button class="btn btn-primary add-member-btn">Add Member</button>
    <button type="submit" class="btn btn-danger" id="batch-delete-btn" data-term="user">Delete Selected</button>
</div>
</div>




<form id="search-form" method="get" style="margin-bottom:10px;border:none;">

    <div class="form-group" style="display: inline-block; margin-right: 10px;">
        <label for="search">Search:</label>
        <input type="text" style="border: 2px solid #007bff; padding: 5px; border-radius: 4px; width:450px;" name="search" id="search" class="form-control" value="<?= htmlspecialchars($search) ?>" placeholder="Search by username, full name, or email">
    </div>

    <!--<input type="text" name="search" placeholder="Search by username, full name, or email" value="<?= htmlspecialchars($search) ?>">-->
    <button type="submit" class="btn btn-primary">Search</button>
    <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
    <input type="hidden" name="dir" value="<?= htmlspecialchars($dir) ?>">
</form>

<div id="member-table">
    <?php include 'memberTable.php'; ?>
</div>

<div id="edit-form-container" style="margin-top:40px;"></div>

<script>
    $("#batch-delete-btn").click(function(event) {
        event.preventDefault();

        var term = $(this).data("term");
        var selectedIds = $('input[name="' + term + 'ID[]"]:checked')
            .map(function() {
                return $(this).val();
            })
            .get();

        if (selectedIds.length === 0) {
            alert("Please select at least one item to delete.");
            return;
        }

        var confirmDelete = confirm("Are you sure you want to delete them?");

        if (confirmDelete) {

            $.ajax({
                url: "batch_delete.php",
                type: "POST",
                data: {
                    term: term,
                    ids: selectedIds,
                },
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    alert("An error occurred: " + error);
                },
            });
        }
    });
</script>