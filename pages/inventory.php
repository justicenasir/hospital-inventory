<?php
require_once __DIR__ . '/../core/auth_check.php';

// Only admin or staff can add items
$can_add = ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'staff');
$error = '';
$success = '';

// Handle add item
if ($can_add && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_item'])) {
    $item_name = trim(sanitize($_POST['item_name']));
    $quantity = intval($_POST['quantity']);
    $department = trim(sanitize($_POST['department']));
    $category = trim(sanitize($_POST['category']));
    $supplier = trim(sanitize($_POST['supplier']));
    if ($item_name && $quantity >= 0) {
        $stmt = $conn->prepare("INSERT INTO inventory (item_name, quantity, department, category, supplier) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$item_name, $quantity, $department, $category, $supplier]);
        $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Add Item", ?)')
            ->execute([$_SESSION['user_id'], "Added item: $item_name, qty: $quantity"]);
        $success = "Item added!";
    } else {
        $error = "Please fill all required fields and enter a valid quantity.";
    }
}

// Handle edit item
if ($can_add && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_item'])) {
    $item_id = intval($_POST['item_id']);
    $item_name = trim(sanitize($_POST['item_name']));
    $quantity = intval($_POST['quantity']);
    $department = trim(sanitize($_POST['department']));
    $category = trim(sanitize($_POST['category']));
    $supplier = trim(sanitize($_POST['supplier']));
    if ($item_id && $item_name && $quantity >= 0) {
        $stmt = $conn->prepare("UPDATE inventory SET item_name=?, quantity=?, department=?, category=?, supplier=? WHERE id=?");
        $stmt->execute([$item_name, $quantity, $department, $category, $supplier, $item_id]);
        $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Edit Item", ?)')
            ->execute([$_SESSION['user_id'], "Edited item ID: $item_id"]);
        $success = "Item updated!";
    } else {
        $error = "Please fill all required fields and enter a valid quantity.";
    }
}

// Handle delete item (admin only)
if ($_SESSION['user_role'] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $item_id = intval($_POST['item_id']);
    if ($item_id) {
        $stmt = $conn->prepare("DELETE FROM inventory WHERE id=?");
        $stmt->execute([$item_id]);
        $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Delete Item", ?)')
            ->execute([$_SESSION['user_id'], "Deleted item ID: $item_id"]);
        $success = "Item deleted!";
    }
}

// Fetch items
$items = $conn->query("SELECT * FROM inventory ORDER BY item_name ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<h2>Inventory</h2>
<?php if ($error): ?>
    <div class="alert alert-danger"><?=sanitize($error)?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?=sanitize($success)?></div>
<?php endif; ?>

<?php if ($can_add): ?>
<!-- Add Item Form -->
<form method="POST" class="mb-4 w-75">
    <input type="hidden" name="add_item" value="1">
    <div class="row mb-2">
        <div class="col">
            <input type="text" name="item_name" required class="form-control" placeholder="Item Name">
        </div>
        <div class="col">
            <input type="number" name="quantity" min="0" required class="form-control" placeholder="Quantity">
        </div>
        <div class="col">
            <input type="text" name="department" class="form-control" placeholder="Department">
        </div>
        <div class="col">
            <input type="text" name="category" class="form-control" placeholder="Category">
        </div>
        <div class="col">
            <input type="text" name="supplier" class="form-control" placeholder="Supplier">
        </div>
        <div class="col">
            <button class="btn btn-success">Add Item</button>
        </div>
    </div>
</form>
<?php endif; ?>

<!-- Inventory Table -->
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>Item Name</th>
            <th>Qty</th>
            <th>Department</th>
            <th>Category</th>
            <th>Supplier</th>
            <?php if ($can_add): ?>
                <th>Edit</th>
            <?php endif; ?>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
                <th>Delete</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item): ?>
        <tr>
            <form method="POST">
                <input type="hidden" name="item_id" value="<?=$item['id']?>">
                <td>
                    <?php if ($can_add): ?>
                        <input type="text" name="item_name" value="<?=sanitize($item['item_name'])?>" required class="form-control">
                    <?php else: ?>
                        <?=sanitize($item['item_name'])?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($can_add): ?>
                        <input type="number" name="quantity" value="<?=sanitize($item['quantity'])?>" min="0" required class="form-control" style="width:90px;">
                    <?php else: ?>
                        <?=sanitize($item['quantity'])?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($can_add): ?>
                        <input type="text" name="department" value="<?=sanitize($item['department'])?>" class="form-control">
                    <?php else: ?>
                        <?=sanitize($item['department'])?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($can_add): ?>
                        <input type="text" name="category" value="<?=sanitize($item['category'])?>" class="form-control">
                    <?php else: ?>
                        <?=sanitize($item['category'])?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($can_add): ?>
                        <input type="text" name="supplier" value="<?=sanitize($item['supplier'])?>" class="form-control">
                    <?php else: ?>
                        <?=sanitize($item['supplier'])?>
                    <?php endif; ?>
                </td>
                <?php if ($can_add): ?>
                    <td>
                        <button name="edit_item" class="btn btn-primary btn-sm">Save</button>
                    </td>
                <?php endif; ?>
                <?php if ($_SESSION['user_role'] === 'admin'): ?>
                    <td>
                        <button name="delete_item" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">Delete</button>
                    </td>
                <?php endif; ?>
            </form>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>