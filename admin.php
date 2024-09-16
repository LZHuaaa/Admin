<?php
require 'base.php';

$_title = 'Manage Admin';
include 'header.php';


// (1) Sorting
$fields = [
    'userID'         => 'Id',
    'username'       => 'Username',
    'fullname' => 'Full Name',
    'email' => 'Email',
    'role' => 'Role',
    'status' => 'Status',
    'photo' => 'Photo',
    'Action'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'userID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once 'lib/SimplePager';

$p = new SimplePager("SELECT * FROM user Where role ='admin' ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Manage Admin';
?>


<div class="button-container">
    <button type="button" class="btn btn-primary add-btn">Add Admin</button>
    <button type="submit" class="btn btn-danger" id="batch-delete-btn" data-term="user">Delete Selected</button>
</div>
</div>





<table class="table" style="font-size:15px;">
    <tr>
        <!-- TODO -->
        <th><input type="checkbox" id="select-all"></th>
        <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    </tr>

    <?php foreach ($arr as $s) : ?>
        <tr>
            <td>
                <input type="checkbox" name="userID[]" value="<?= $s->userID ?>">
            </td>
            <td><?= $s->userID ?></td>
            <td><?= $s->username ?></td>
            <td><?= $s->fullname ?></td>
            <td><?= $s->email ?></td>
            <td><?= $s->role ?></td>
            <td><?= $s->status ?></td>
            <td><img src="images/<?= htmlspecialchars($s->photo) ?>" alt="Photo" style="width:100px;height:100px;"></td>
            <td>
                <button type="button" class="btn btn-primary edit-btn" data-id="<?= $s->userID ?> ">Edit</button>

                <button type="button" class="btn btn-warning reset-btn" data-id="<?= $s->userID ?>" data-username="<?= htmlspecialchars($s->username) ?>" data-role="<?= htmlspecialchars($s->role) ?>">Reset Password</button>

                <?php if ($s->status === 'Active') : ?>
                    <button type="button" class="btn btn-block block-member-btn" data-id="<?= htmlspecialchars($s->userID) ?>" data-username="<?= htmlspecialchars($s->username) ?>" data-role="<?= htmlspecialchars($s->role) ?>">Block</button>
                <?php else : ?>
                    <button type="button" class="btn btn-unblock unblock-member-btn" data-id="<?= htmlspecialchars($s->userID) ?>" data-username="<?= htmlspecialchars($s->username) ?>" data-role="<?= htmlspecialchars($s->role) ?>">Unblock</button>
                <?php endif; ?>

                <button type="button" class="btn btn-danger delete-btn" data-id="<?= $s->userID ?>">Delete</button>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<br>

<!-- TODO -->
<div class="pagination-info">
    <p>
        <?= $p->count ?> of <?= $p->item_count ?> record(s) |
        Page <?= $p->page ?> of <?= $p->page_count ?>
    </p>

    <div class="pagination-controls">
        <?= $p->html("sort=$sort&dir=$dir") ?>
    </div>
</div>

<!-- This div will hold the edit form -->
<div id="edit-form-container" style="margin-top:40px;"></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link rel="stylesheet" href="css/header.css">