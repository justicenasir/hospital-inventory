<?php
require_once __DIR__ . '/../core/auth_check.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name  = trim(sanitize($_POST['item_name'] ?? ''));
    $quantity   = intval($_POST['quantity'] ?? 0);
    $department = trim(sanitize($_POST['department'] ?? ''));
    $category   = trim(sanitize($_POST['category'] ?? ''));
    $supplier   = trim(sanitize($_POST['supplier'] ?? ''));
    $message = '';
    if ($item_name && $quantity > 0) {
        $stmt = $conn->prepare('INSERT INTO inventory (item_name, quantity, department, category, supplier, created_by) VALUES (?, ?, ?, ?, ?, ?)');
        $stmt->execute([$item_name, $quantity, $department, $category, $supplier, $_SESSION['user_id']]);
        $message = '<div class="alert alert-success mt-2">Inventory item added!</div>';
    } else {
        $message = '<div class="alert alert-danger mt-2">Item name and positive quantity are required.</div>';
    }
}
?>
<h2>Add Inventory</h2>
<form method="POST" class="mb-3" autocomplete="off">
  <div class="form-row">
    <div class="col-md-3 mb-2">
      <input type="text" name="item_name" required class="form-control" placeholder="Item Name" autocomplete="off">
    </div>
    <div class="col-md-2 mb-2">
      <input type="number" name="quantity" min="1" required class="form-control" placeholder="Quantity" autocomplete="off">
    </div>
    <div class="col-md-2 mb-2">
      <input type="text" name="department" class="form-control" placeholder="Department" autocomplete="off">
    </div>
    <div class="col-md-2 mb-2">
      <input type="text" name="category" class="form-control" placeholder="Category" autocomplete="off">
    </div>
    <div class="col-md-2 mb-2">
      <input type="text" name="supplier" class="form-control" placeholder="Supplier" autocomplete="off">
    </div>
    <div class="col-md-1 mb-2">
      <button class="btn btn-success w-100">Add</button>
    </div>
  </div>
</form>
<?php if (!empty($message)) echo $message; ?>