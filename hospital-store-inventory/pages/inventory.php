<?php
// inventory.php - View Inventory and Delete Items

require_once 'header.php';

// Pagination setup
$items_per_page = 10; // Items per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $items_per_page;

// Database connection
require_once '../core/db.php';

// Fetch items with pagination
$sql = "SELECT * FROM inventory LIMIT $start, $items_per_page";
$stmt = $conn->query($sql);
$items = $stmt->fetchAll();

// Delete item logic
if (isset($_GET['delete'])) {
    $item_id = $_GET['delete'];
    $delete_sql = "DELETE FROM inventory WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->execute([$item_id]);
    echo "<p>Item deleted successfully!</p>";
}

// Count total items for pagination
$total_items_sql = "SELECT COUNT(*) FROM inventory";
$total_items_stmt = $conn->query($total_items_sql);
$total_items = $total_items_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

?>

<div class="container">
    <h1>Inventory List</h1>
    <table>
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($items as $item) : ?>
        <tr>
            <td><?php echo htmlspecialchars($item['item_name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo htmlspecialchars($item['description']); ?></td>
            <td><a href="index.php?page=inventory&delete=<?php echo $item['id']; ?>">Delete</a></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="pagination">
        <ul>
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <li><a href="index.php?page=inventory&pg=<?php echo $i; ?>"><?php echo $i; ?></a></li>
            <?php endfor; ?>
        </ul>
    </div>
</div>

<?php
require_once 'footer.php';
?>
