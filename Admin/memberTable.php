<?php
require_once 'base.php'; 

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
$search = req('search', ''); 


$search = htmlspecialchars($search, ENT_QUOTES, 'UTF-8');


$searchCondition = '';
if ($search) {
    $searchCondition = "AND (username LIKE :search OR fullname LIKE :search OR email LIKE :search)";
}

require_once '../lib/SimplePager.php';


$query = "SELECT * FROM user WHERE role='member' $searchCondition ORDER BY $sort $dir";


$params = [];
if ($search) {
    $params[':search'] = "%$search%";
}

$p = new SimplePager($query, $params, 10, $page);
$arr = $p->result;
?>

<!--<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
</p>-->


<div style="font-size:13px;" class="testing">

    <table class="table">
        <tr> 
            <th><input type="checkbox" id="select-all"></th>
            <?= table_headers($fields, $sort, $dir, "page=$page&search=" . urlencode($search)) ?>
        </tr>

        <?php foreach ($arr as $s) : ?>
            <tr>
                <td>
                    <input type="checkbox" name="userID[]" value="<?= $s->userID ?>"> 
                </td>
                <td><?= htmlspecialchars($s->userID) ?></td>
                <td><?= htmlspecialchars($s->username) ?></td>
                <td><?= htmlspecialchars($s->fullname) ?></td>
                <td><?= htmlspecialchars($s->email) ?></td>
                <td><img src="../images/<?= htmlspecialchars($s->photo) ?>" alt="Photo" style="width:100px;height:100px;"></td>
                <td><?= htmlspecialchars($s->status) ?></td>
                <td>
                    <button class="btn btn-primary edit-member-btn" data-id="<?= htmlspecialchars($s->userID) ?>">Edit</button>

                    <button class="btn btn-warning reset-btn" data-id="<?= $s->userID ?>" data-username="<?= htmlspecialchars($s->username) ?>" data-role="<?= htmlspecialchars($s->role)?>">Reset Password</button>

                    <?php if (strtolower($s->status) === 'active') : ?>
                        <button class="btn btn-block block-member-btn" data-id="<?= htmlspecialchars($s->userID) ?>" data-username="<?= htmlspecialchars($s->username) ?>" data-role="<?= htmlspecialchars($s->role) ?>">Block</button>
                    <?php else : ?>
                        <button class="btn btn-unblock unblock-member-btn" data-id="<?= htmlspecialchars($s->userID) ?>" data-username="<?= htmlspecialchars($s->username) ?>" data-role="<?= htmlspecialchars($s->role) ?>">Unblock</button>
                    <?php endif; ?>

                    <button class="btn btn-danger delete-member-btn" data-id="<?= htmlspecialchars($s->userID) ?>">Delete</button>

                </td>
            </tr>
        <?php endforeach ?>

    </table>
</div>

<br>

<div class="pagination-info">
    <p>
        <?= $p->count ?> of <?= $p->item_count ?> record(s) |
        Page <?= $p->page ?> of <?= $p->page_count ?>
    </p>

    <div class="pagination-controls">
        <?= $p->html("sort=$sort&dir=$dir") ?>
    </div>
</div>

<script>
$('#select-all').click(function(event) {
    if (this.checked) {
        $('input[type="checkbox"]').each(function() {
            this.checked = true;
        });
    } else {
        $('input[type="checkbox"]').each(function() {
            this.checked = false;
        });
    }
});

</script>