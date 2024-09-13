<?php
require 'base.php';

$_title = 'Manage Category';
include 'header.php';


// (1) Sorting
$fields = [
    'categoryID'         => 'Id',
    'categoryName'       => 'Category Name',
    'Action'
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'categoryID';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

// (2) Paging
$page = req('page', 1);

require_once 'lib/SimplePager';

$p = new SimplePager("SELECT * FROM category ORDER BY $sort $dir", [], 10, $page);
$arr = $p->result;

// ----------------------------------------------------------------------------
$_title = 'Manage Category';
?>

<button style="font-size:15px;margin-bottom:20px;" class="btn btn-primary add-category-btn">Add Category</button>


<p>
    <?= $p->count ?> of <?= $p->item_count ?> record(s) |
    Page <?= $p->page ?> of <?= $p->page_count ?>


</p>



<table class="table" style="font-size:15px;">
    <tr>
        <!-- TODO -->
        <?= table_headers($fields, $sort, $dir, "page=$page") ?>
    </tr>

    <?php foreach ($arr as $s) : ?>
        <tr>
            <td><?= $s->categoryID ?></td>
            <td><?= $s->categoryName ?></td>
            <td>
                <button class="btn btn-primary edit-category-btn" data-id="<?= $s->categoryID ?> ">Edit</button>

                <button class="btn btn-danger delete-category-btn" data-id="<?= $s->categoryID ?>">Delete</button>
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