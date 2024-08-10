<link rel="stylesheet" href="css/admin.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/admin.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />


<?php
require 'base.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch admin data based on ID
    $stmt = $_db->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$id]);
    $admin = $stmt->fetch();

    if ($admin) {
        // Output the edit form
?>

    <button type="button" class="btn-close" id="closeBtn"> Close </button>
        <form action="updateAdmin.php" method="POST">
            <input type="hidden" name="id" value="<?= $admin->id ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?= $admin->username ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>

                <select class="form-control" id="role" name="role" required>
                    <option value="admin" <?= $admin->role === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="staff" <?= $admin->role === 'staff' ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="Active" <?= $admin->status === 'Active' ? 'selected' : '' ?>>Active</option>
                    <option value="Inactive" <?= $admin->status === "Inactive" ? 'selected' : '' ?>>Inactive</option>
                </select>

            </div>
            <button type="submit" class="btn-success" id="submitBtn">Update</button>
        </form>
<?php
    } else {
        echo "Admin not found.";
    }
} else {
    echo "No ID specified.";
}
?>