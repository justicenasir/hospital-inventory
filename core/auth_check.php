<?php
require_once __DIR__ . '/functions.php'; 
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit();
}
if (!isset($_SESSION['user_role'])) {
    $stmt = $conn->prepare('SELECT role FROM users WHERE id=?');
    $stmt->execute([$_SESSION['user_id']]);
    $_SESSION['user_role'] = $stmt->fetchColumn();
}
?>