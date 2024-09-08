<?php
require_once 'base.php'; // Use require_once to avoid multiple inclusions

// (1) Sorting
$fields = [
    'id' => 'Id',
    'username' => 'Username',
    'fullname' => 'Full Name',
    'email' => 'Email',
    'dateCreated' => 'Date Created',
    'birthday' => 'Birthday',
    'photo' => 'Photo',
    'status' => 'Status',
    'Action'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

// (3) Searching
$search = req('search');
$searchCondition = $search ? "WHERE username LIKE '%$search%' OR fullname LIKE '%$search%' OR email LIKE '%$search%'" : '';

require_once 'lib/SimplePager';

$query = "SELECT * FROM member $searchCondition ORDER BY $sort $dir";
$p = new SimplePager($query, [], 10, $page);
$arr = $p->result;
?>


<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>
    
   
</p>

<table class="table">
    <tr>
        <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    </tr>

    <?php foreach ($arr as $s) : ?>
        <tr>
            <td><?= $s->id ?></td>
            <td><?= htmlspecialchars($s->username) ?></td>
            <td><?= htmlspecialchars($s->fullname) ?></td>
            <td><?= htmlspecialchars($s->email) ?></td>
            <td><?= htmlspecialchars($s->dateCreated) ?></td>
            <td><?= htmlspecialchars($s->birthday) ?></td>
            <td><img src="images/<?= htmlspecialchars($s->photo) ?>" alt="Photo" style="width:100px;height:100px;"></td>
            <td><?= htmlspecialchars($s->status) ?></td>
            <td>
                <button class="btn btn-primary edit-member-btn" data-id="<?= $s->id ?>">Edit</button>
                <button class="btn btn-danger delete-member-btn" data-id="<?= $s->id ?>">Delete</button>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<br>

<?= $p->html("sort=$sort&dir=$dir&search=" . urlencode($search)) ?>
