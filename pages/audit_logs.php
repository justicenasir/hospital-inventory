<?php
require_once __DIR__ . '/../core/is_admin.php';
$audit = $conn->query('SELECT al.*, u.username FROM audit_logs al LEFT JOIN users u ON al.user_id = u.id ORDER BY al.created_at DESC LIMIT 100')->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Audit Logs</h2>
<table class="table table-bordered">
    <thead><tr><th>Name</th><th>Action</th><th>Details</th><th>Time</th></tr></thead>
    <tbody>
        <?php foreach ($audit as $row): ?>
        <tr>
            <td><?=sanitize($row['fullname'])?></td>
            <td><?=sanitize($row['action'])?></td>
            <td><?=sanitize($row['details'])?></td>
            <td><?=sanitize($row['created_at'])?></td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>