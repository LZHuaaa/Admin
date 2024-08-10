

<?php
require 'base.php';

$_title = 'Manage Admin';
include 'header.php';


// (1) Sorting
$fields = [
    'id'         => 'Id',
    'username'       => 'Name',
    'role' => 'Role',
    'status' => 'Status',
    'last_login' => 'Last Login',
    'Action'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once 'lib/SimplePager';

$p = new SimplePager("SELECT * FROM admin ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Manage Admin';
?>

<button style="font-size:15px;margin-bottom:20px;" class="btn btn-primary add-btn">Add Admin</button>


<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
    
   
</p>



<table class="table">
    <tr>
        <!-- TODO -->
        <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    </tr>

    <?php foreach ($arr as $s) : ?>
        <tr>
            <td><?= $s->id ?></td>
            <td><?= $s->username ?></td>
            <td><?= $s->role ?></td>
            <td><?= $s->status ?></td>
            <td><?= $s->last_login ?></td>
            <td>
                <button class="btn btn-primary edit-btn" data-id="<?= $s->id ?> ">Edit</button>
                <button class="btn btn-danger delete-btn" data-id="<?= $s->id ?>">Delete</button>
                <button class="btn btn-warning reset-btn" data-id="<?= $s->id ?>" data-username="<?= htmlspecialchars($s->username) ?>">Reset Password</button>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<br>

<!-- TODO -->
<?= $p->html("sort=$sort&dir=$dir") ?>

<!-- This div will hold the edit form -->
<div id="edit-form-container" style="margin-top:40px;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>