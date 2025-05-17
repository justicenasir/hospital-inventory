<?php
// dashboard.php - Dashboard Page
require_once 'header.php';  // Include header

// Your Dashboard Content Goes Here
?>
<div class="container">
    <h1>Welcome to the Hospital Store Inventory System</h1>
    <p>This is your dashboard where you can manage the inventory, view reports, and issue items.</p>
    <ul>
        <li><a href="index.php?page=inventory">View Inventory</a></li>
        <li><a href="index.php?page=issue">Issue Items</a></li>
        <li><a href="index.php?page=reports">Generate Reports</a></li>
    </ul>
</div>

<?php
require_once 'footer.php';  // Include footer
?>
