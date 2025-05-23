<!-- Print/Header visible only when printing -->
<div class="print-header">
  <img src="vendor/img/logo.jpg" alt="Hospital Logo">
  <h2>Hospital Inventory System</h2>
  <p>Inventory Issue Report</p>
  <p><?= date('Y-m-d') ?></p>
  <div style="margin-top:8px; font-size:1rem;">
    Dashboard &gt; Reports &gt; Issue Reports
  </div>
</div>

<div class="card shadow-sm mb-4">
  <div class="card-header bg-info text-white">
    <div class="d-flex align-items-center justify-content-between">
      <div>
        <img src="vendor/img/hospital-logo.png" alt="Hospital Logo" style="height:40px; width:auto; vertical-align:middle; margin-right:10px;">
        <span style="font-size:1.2rem; font-weight:bold;">Hospital Inventory - Issue Reports</span>
      </div>
      <button onclick="window.print()" class="btn btn-light btn-sm no-print"><i class="bi bi-printer"></i> Print</button>
    </div>
    <!-- Breadcrumb navigation -->
    <nav aria-label="breadcrumb" class="mt-2">
      <ol class="breadcrumb mb-0 bg-info pl-0" style="background:transparent;">
        <li class="breadcrumb-item"><a href="index.php?page=dashboard" class="text-white">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="index.php?page=reports" class="text-white">Reports</a></li>
        <li class="breadcrumb-item active text-white" aria-current="page">Issue Reports</li>
      </ol>
    </nav>
  </div>
  <div class="card-body">
    <!-- Place your report table here -->
    <table class="table table-bordered table-hover">
      <thead class="thead-light">
        <tr>
          <th>Item Name</th>
          <th>Quantity</th>
          <th>Department</th>
          <th>Issued By</th>
          <th>Date Issued</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($issuedItems as $item): ?>
        <tr>
          <td><?= htmlspecialchars($item['fullname']) ?></td>
          <td><?= htmlspecialchars($item['quantity']) ?></td>
          <td><?= htmlspecialchars($item['department']) ?></td>
          <td><?= htmlspecialchars($item['issued_by']) ?></td>
          <td><?= htmlspecialchars($item['date_issued']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>