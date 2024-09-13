<link rel="stylesheet" href="css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />


<?php
require 'base.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $_db->prepare("SELECT * FROM category WHERE categoryid = ?");
    $stmt->execute([$id]);
    $category = $stmt->fetch();

    if ($category) {
 
?>

        <button type="button" class="btn-close" id="closeBtn"> Close </button>
        <form method="POST" id="editCategoryForm">
            <?php
            html_hidden('id', $category->categoryID);
            html_text2('categoryName', 'Category Name', $category->categoryName, 'required');
            html_submit('submitBtn', 'Update');
            ?>


        </form>
<?php
    } else {
        echo "Category not found.";
    }
} else {
    echo "No ID specified.";
}
?>