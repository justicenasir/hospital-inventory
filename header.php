<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Hospital Inventory System</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="vendor/css/bootstrap.min.css">
  <link rel="stylesheet" href="vendor/css/bootstrap-icons.css">
  <style>
    body { background: #f8fafb; }
    .navbar-brand { font-weight: bold; }
    .navbar-brand img { height: 40px; width: auto; margin-right: 10px; }
    .nav-link.active { color: #fff !important; background: #0069d9; border-radius: 5px; }
    .table td, .table th { vertical-align: middle; }
    .form-control, .btn { border-radius: 0.3rem; }
    .card { border-radius: 0.6rem; }
    .footer { background: #343a40; color: #fff; padding: 16px 0; }
    .card-header { font-size: 1.1rem; }
    .pagination .page-link { border-radius: 2px; }
    @media print {
      nav, .footer, .btn, .no-print { display: none !important; }
      .print-header { display: block !important; }
      .card { box-shadow: none !important; border: none !important; }
      body { background: #fff; }
    }
    .print-header { display: none; text-align: center; margin-bottom: 24px; }
    .print-header img { height: 60px; }
    .print-header h2 { margin: 8px 0 0 0; font-size: 2rem; }
    .print-header p { margin: 0; }
  </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow mb-4">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php?page=dashboard">
      <img src="vendor/img/logo.jpg" alt="Hospital Logo">
      <span>Hospital Inventory</span>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto align-items-center">
        <li class="nav-item <?=($page??'')=='dashboard'?'active':''?>">
          <a class="nav-link" href="index.php?page=dashboard"><i class="bi bi-house-door"></i> Dashboard</a>
        </li>
        <li class="nav-item <?=($page??'')=='inventory'?'active':''?>">
          <a class="nav-link" href="index.php?page=inventory"><i class="bi bi-box-seam"></i> Inventory</a>
        </li>
        <li class="nav-item <?=($page??'')=='add_inventory'?'active':''?>">
          <a class="nav-link" href="index.php?page=add_inventory"><i class="bi bi-plus-circle"></i> Add Item</a>
        </li>
        <li class="nav-item <?=($page??'')=='issue'?'active':''?>">
          <a class="nav-link" href="index.php?page=issue"><i class="bi bi-arrow-up-right-circle"></i> Issue</a>
        </li>
        <li class="nav-item <?=($page??'')=='reports'?'active':''?>">
          <a class="nav-link" href="index.php?page=reports"><i class="bi bi-bar-chart"></i> Reports</a>
        </li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <li class="nav-item <?=($page??'')=='manage_staff'?'active':''?>">
          <a class="nav-link" href="index.php?page=manage_staff"><i class="bi bi-people"></i> Staff</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container mb-5">