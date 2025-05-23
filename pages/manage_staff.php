<?php
require_once __DIR__ . '/../core/is_admin.php';
// Add staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $username = trim(sanitize($_POST['username']));
    $fullname = trim(sanitize($_POST['fullname']));
    $phone    = trim(sanitize($_POST['phone']));
    $password = $_POST['password'];
    if ($username && $fullname && $phone && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare('INSERT INTO users (username, fullname, phone, password_hash, role) VALUES (?, ?, ?, ?, "staff")');
        $stmt->execute([$username, $fullname, $phone, $hash]);
        $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Add Staff", ?)')
            ->execute([$_SESSION['user_id'], "Added staff: $fullname ($username)"]);
        echo '<div class="alert alert-success">Staff added!</div>';
    } else {
        echo '<div class="alert alert-danger">All fields are required.</div>';
    }
}
// Delete staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare('SELECT fullname, username FROM users WHERE id=?');
    $stmt->execute([$id]);
    $staff_info = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare('DELETE FROM users WHERE id=? AND role="staff"');
    $stmt->execute([$id]);
    $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Delete Staff", ?)')
        ->execute([$_SESSION['user_id'], "Deleted staff: {$staff_info['fullname']} ({$staff_info['username']})"]);
    echo '<div class="alert alert-success">Staff deleted!</div>';
}
// Pagination and search
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$per_page = 10;
$where = '';
$params = [];
if ($search !== '') {
    $where = 'AND (username LIKE ? OR fullname LIKE ? OR phone LIKE ?)';
    $params = ["%$search%", "%$search%", "%$search%"];
}
$stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE role='staff' $where");
$stmt->execute($params);
$total = $stmt->fetchColumn();
$total_pages = max(1, ceil($total / $per_page));
$offset = ($page - 1) * $per_page;
$stmt = $conn->prepare("SELECT id, username, fullname, phone FROM users WHERE role='staff' $where ORDER BY fullname ASC LIMIT $per_page OFFSET $offset");
$stmt->execute($params);
$staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="card shadow-sm mb-4">
  <div class="card-header bg-secondary text-white">
    <i class="bi bi-people"></i> Manage Staff
  </div>
  <div class="card-body">
    <form method="POST" class="mb-3" autocomplete="off">
      <div class="form-row">
        <div class="col-md-2 mb-2">
          <input type="text" name="username" required placeholder="Username" class="form-control" autocomplete="off">
        </div>
        <div class="col-md-3 mb-2">
          <input type="text" name="fullname" required placeholder="Full Name" class="form-control" autocomplete="off">
        </div>
        <div class="col-md-2 mb-2">
          <input type="text" name="phone" required placeholder="Phone Number" class="form-control" autocomplete="off">
        </div>
        <div class="col-md-3 mb-2">
          <input type="password" name="password" required placeholder="Password" class="form-control" autocomplete="off">
        </div>
        <div class="col-md-2 mb-2">
          <button name="add" class="btn btn-success w-100"><i class="bi bi-person-plus"></i> Add Staff</button>
        </div>
      </div>
    </form>
    <!-- Search form -->
    <form method="GET" class="mb-3">
      <input type="hidden" name="page" value="manage_staff">
      <div class="input-group w-50">
        <input type="text" name="search" value="<?=sanitize($search)?>" placeholder="Search username, full name, phone" class="form-control">
        <div class="input-group-append">
          <button class="btn btn-outline-secondary"><i class="bi bi-search"></i> Search</button>
        </div>
      </div>
    </form>
    <div class="table-responsive">
      <table class="table table-striped table-hover table-bordered">
        <thead class="thead-dark">
          <tr>
            <th>Username</th>
            <th>Full Name</th>
            <th>Phone</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($staff as $s): ?>
          <tr>
            <td><?=sanitize($s['username'])?></td>
            <td><?=sanitize($s['fullname'])?></td>
            <td><?=sanitize($s['phone'])?></td>
            <td>
              <form method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?=$s['id']?>">
                <button name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete this staff?')">
                  <i class="bi bi-trash"></i> Delete
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
    <!-- Pagination -->
    <nav>
      <ul class="pagination">
        <?php if ($page > 1): ?>
        <li class="page-item"><a class="page-link" href="?page=manage_staff&search=<?=urlencode($search)?>&page=<?=$page-1?>">&laquo; Prev</a></li>
        <?php endif ?>
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <li class="page-item <?=($i==$page)?'active':''?>"><a class="page-link" href="?page=manage_staff&search=<?=urlencode($search)?>&page=<?=$i?>"><?=$i?></a></li>
        <?php endfor ?>
        <?php if ($page < $total_pages): ?>
        <li class="page-item"><a class="page-link" href="?page=manage_staff&search=<?=urlencode($search)?>&page=<?=$page+1?>">Next &raquo;</a></li>
        <?php endif ?>
      </ul>
    </nav>
  </div>
</div>