<?php
// Dependencies: session_start(), $conn (PDO), CSRF_TOKEN_NAME, sanitize(), check_csrf_token(), redirect_with_message() loaded before this
if (!empty($_SESSION['user_id'])) {
    header('Location: index.php?page=dashboard');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(sanitize($_POST['username']));
    $password = $_POST['password'];
    $csrf = $_POST[CSRF_TOKEN_NAME] ?? '';

    if (!check_csrf_token($csrf)) {
        $error = 'CSRF token mismatch.';
    } else {
        $stmt = $conn->prepare('SELECT id, password_hash FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            redirect_with_message('index.php?page=dashboard', 'Login successful!');
        } else {
            $error = 'Invalid username or password.';
        }
    }
}
?>
<h2>Login</h2>
<?php if ($error): ?>
    <div class="alert alert-danger"><?=sanitize($error)?></div>
<?php endif; ?>
<form method="POST" class="w-25 mx-auto mt-3">
    <input type="hidden" name="<?=CSRF_TOKEN_NAME?>" value="<?=generate_csrf_token()?>">
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" required class="form-control">
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" required class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>