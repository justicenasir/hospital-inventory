<?php
require_once '../header.php'; // Make sure this path is correct

// Form submission handling
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../core/db.php';
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Insert item into database
    $sql = "INSERT INTO inventory (item_name, quantity, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$item_name, $quantity, $description]);

    echo "<p style='color: green;'>Item added successfully!</p>";
}
?>

<div class="container">
    <h1>Add New Inventory Item</h1>
    <form method="POST">
        <label for="item_name">Item Name:</label>
        <input type="text" name="item_name" required><br>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" required><br>

        <label for="description">Description:</label>
        <textarea name="description"></textarea><br>

        <button type="submit">Add Item</button>
    </form>
</div>

<?php
require_once '../footer.php'; // Make sure this path is correct
?>
