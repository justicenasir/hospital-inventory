<?php
require_once __DIR__ . '/../core/auth_check.php';
// Admin resets staff password
if ($_SESSION['user_role'] === 'admin' && isset($_POST['reset_user_id'])) {
    $id = intval($_POST['reset_user_id']);
    $new_pass = $_POST['new_password'];
    $hash = password_hash($new_pass, PASSWORD_DEFAULT);
    $conn->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([$hash, $id]);
    $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Reset Password", ?)')
        ->execute([$_SESSION['user_id'], "Reset password for user ID $id"]);
    echo '<div class="alert alert-success">Password reset!</div>';
}
// Staff changes own password
if (isset($_POST['current_password']) && isset($_POST['new_password']) && $_SESSION['user_role'] === 'staff') {
    $stmt = $conn->prepare('SELECT password_hash FROM users WHERE id=?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($_POST['current_password'], $user['password_hash'])) {
        $hash = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
        $conn->prepare('UPDATE users SET password_hash=? WHERE id=?')->execute([$hash, $_SESSION['user_id']]);
        $conn->prepare('INSERT INTO audit_logs (user_id, action, details) VALUES (?, "Change Password", ?)')
            ->execute([$_SESSION['user_id'], "User changed their own password"]);
        echo '<div class="alert alert-success">Password changed!</div>';
    } else {
        echo '<div class="alert alert-danger">Current password incorrect!</div>';
    }
}
if ($_SESSION['user_role'] === 'admin') {
    $users = $conn->query('SELECT id, username FROM users')->fetchAll(PDO::FETCH_ASSOC);
}
?>
<h2>Password Reset</h2>
<?php if ($_SESSION['user_role'] === 'admin'): ?>
<form method="POST" class="mb-3">
    <select name="reset_user_id" class="form-select w-25 d-inline">
        <?php foreach ($users as $user): ?>
        <option value="<?=$user['id']?>"><?=sanitize($user['username'])?></option>
        <?php endforeach ?>
    </select>
    <input type="password" name="new_password" required placeholder="New Password" class="form-control w-25 d-inline">
    <button class="btn btn-warning">Reset Password</button>
</form>
<hr>
<?php endif; ?>
<?php if ($_SESSION['user_role'] === 'staff'): ?>
<form method="POST">
    <input type="password" name="current_password" required placeholder="Current Password" class="form-control w-25 mb-2">
    <input type="password" name="new_password" required placeholder="New Password" class="form-control w-25 mb-2">
    <button class="btn btn-primary">Change My Password</button>
</form>
<?php endif; ?>