<?php
require_once __DIR__ . '/auth_check.php';
if ($_SESSION['user_role'] !== 'admin') {
    header('Location: index.php?page=dashboard');
    exit();
}
?>