<?php include('header.php'); ?>
<div class="container">
    <h1>Issue Items</h1>
    <form action="issue.php" method="POST">
        <select name="item_id" required>
            <option value="">Select Item</option>
            <?php
            $query = $conn->query('SELECT * FROM inventory');
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id']}'>{$row['item_name']}</option>";
            }
            ?>
        </select>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="submit" value="Issue">
    </form>
</div>
<?php include('footer.php'); ?>
