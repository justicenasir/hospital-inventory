<?php
// index.php - Main Entry Point

session_start();

// Include configuration and core files
require_once 'config.php';
require_once 'core/functions.php';

// Routing Logic
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

switch ($page) {
    case 'login':
        require_once 'pages/login.php';
        break;
    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        exit();
    case 'inventory':
        require_once 'pages/inventory.php';
        break;
    case 'issue':
        require_once 'pages/issue.php';
        break;
    case 'reports':
        require_once 'pages/reports.php';
        break;
    case 'export':
        require_once 'pages/export.php';
        break;
    default:
        require_once 'pages/dashboard.php';
}

// Database Connection
require_once 'core/db.php';

// Core DB Configuration
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'hospital_store';

try {
    $conn = new PDO("mysql:host=$host;dbname=$database", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>

<!-- Here, HTML content starts -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Store Inventory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Welcome to Hospital Store Inventory System</h1>
    </header>
    <main>
        <!-- Dynamic content based on routing will go here -->
    </main>
</body>
</html>
