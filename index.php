<?php
	require_once __DIR__ . '/config.php';
    session_start();
    require_once __DIR__ . '/core/functions.php';
    require_once __DIR__ . '/core/db.php';
?>
<?php require_once __DIR__ . '/header.php'; ?>
<main>
<?php
$page = $_GET['page'] ?? 'dashboard';

switch ($page) {
    case 'login':
        require_once __DIR__ . '/pages/login.php';
        break;
    case 'logout':
        session_destroy();
        redirect_with_message('index.php?page=login', 'You have been logged out.');
        break;
    case 'inventory':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/pages/inventory.php';
        break;
    case 'add_inventory':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/pages/add_inventory.php';
        break;
    case 'issue':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/pages/issue.php';
        break;
    case 'reports':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/pages/reports.php';
        break;
    case 'export':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/pages/export.php';
        break;
    case 'manage_staff':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/core/is_admin.php';
        require_once __DIR__ . '/pages/manage_staff.php';
        break;
    case 'audit_logs':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/core/is_admin.php';
        require_once __DIR__ . '/pages/audit_logs.php';
        break;
    case 'password_reset':
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/pages/password_reset.php';
        break;
    default:
        require_once __DIR__ . '/core/auth_check.php';
        require_once __DIR__ . '/pages/dashboard.php';
}
?>
</main>
<?php require_once __DIR__ . '/footer.php'; ?>