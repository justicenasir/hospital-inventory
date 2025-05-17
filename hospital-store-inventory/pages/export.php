<?php
require_once '../core/db.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;

$type = $_GET['type'];

if ($type == 'pdf') {
    $query = $conn->query('SELECT i.item_name, ii.quantity, ii.issue_date FROM issued_items ii JOIN inventory i ON ii.item_id = i.id');
    $items = $query->fetchAll();

    $html = "<div style='font-family:sans-serif;color:#333;'>";
    $html .= "<h1 style='text-align:center;color:#4CAF50;'>Hospital Store Inventory Report</h1>";
    $html .= "<h3 style='text-align:center;'>Issued Items Report</h3><table style='width:100%;border-collapse:collapse;'>";
    $html .= "<tr style='background-color:#4CAF50;color:white;'><th>Item Name</th><th>Quantity</th><th>Date</th></tr>";
    foreach ($items as $item) {
        $html .= "<tr style='border-bottom:1px solid #ddd;'><td>{$item['item_name']}</td><td>{$item['quantity']}</td><td>{$item['issue_date']}</td></tr>";
    }
    $html .= "</table><br><footer style='text-align:center;margin-top:20px;color:#666;'>Generated on " . date('Y-m-d H:i:s') . "</footer></div>";

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="report.pdf"');

    echo $dompdf->output();
    exit();
}

if ($type == 'excel') {
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="report.xls"');

    echo "Item Name\tQuantity Issued\tDate Issued\n";
    $query = $conn->query('SELECT i.item_name, ii.quantity, ii.issue_date FROM issued_items ii JOIN inventory i ON ii.item_id = i.id');
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['item_name']}\t{$row['quantity']}\t{$row['issue_date']}\n";
    }
}
?>
