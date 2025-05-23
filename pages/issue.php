<?php
require_once __DIR__ . '/../core/auth_check.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_id = intval($_POST['item_id']);
    $quantity = intval($_POST['quantity']);
    $issued_to = trim(sanitize($_POST['issued_to']));
    $issued_by = $_SESSION['user_id'];
    // Update inventory and add to issued_items
    $stmt = $conn->prepare('UPDATE inventory SET quantity = quantity - ? WHERE id = ? AND quantity >= ?');
    if ($stmt->execute([$quantity, $item_id, $quantity]) && $stmt->rowCount() > 0) {
        $conn->prepare('INSERT INTO issued_items (item_id, quantity, issued_to, issued_by) VALUES (?, ?, ?, ?)')
            ->execute([$item_id, $quantity, $issued_to, $issued_by]);
        $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Issue Item", ?)')
            ->execute([$issued_by, "Issued $quantity of Item ID $item_id to $issued_to"]);
        echo '<div class="alert alert-success">Item issued!</div>';
    } else {
        echo '<div class="alert alert-danger">Not enough stock.</div>';
    }
}
$items = $conn->query('SELECT id, item_name, quantity FROM inventory WHERE quantity > 0')->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Issue Item</h2>
<form method="POST" class="w-50">
    <div class="mb-2">
        <select name="item_id" required class="form-select">
            <?php foreach ($items as $item): ?>
            <option value="<?=$item['id']?>"><?=sanitize($item['item_name'])?> (<?= $item['quantity']?> left)</option>
            <?php endforeach ?>
        </select>
    </div>
    <div class="mb-2">
        <input type="number" name="quantity" min="1" required class="form-control" placeholder="Quantity">
    </div>
    <div class="mb-2">
        <input type="text" name="issued_to" required class="form-control" placeholder="Name of person collecting">
    </div>
    <button class="btn btn-primary">Issue</button>
</form>