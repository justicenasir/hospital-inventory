<?php
// Example usage: include this in your header file for navigation
?>
<ul class="nav">
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link" href="index.php?page=manage_staff">Manage Staff</a></li>
        <li class="nav-item"><a class="nav-link" href="index.php?page=audit_logs">Audit Logs</a></li>
    <?php endif; ?>
    <li class="nav-item"><a class="nav-link" href="index.php?page=issue">Issue Item</a></li>
    <li class="nav-item"><a class="nav-link" href="index.php?page=reports">Reports</a></li>
    <li class="nav-item"><a class="nav-link" href="index.php?page=password_reset">Password Reset</a></li>
    <li class="nav-item"><a class="nav-link" href="index.php?page=logout">Logout</a></li>
</ul>