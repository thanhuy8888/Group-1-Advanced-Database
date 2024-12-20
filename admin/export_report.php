<?php
session_start();
include 'connect.php';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    // Prepare the SQL statement to get the report data
    $stmt = $con->prepare("
        SELECT w.Name AS WarehouseName, p.Name AS ProductName, SUM(i.Quantity) AS TotalQuantity
        FROM InventoryItems i
        JOIN Warehouses w ON i.WarehouseID = w.WarehouseID
        JOIN Products p ON i.ProductID = p.ProductID
        GROUP BY w.Name, p.Name
        ORDER BY w.Name, p.Name
    ");
    $stmt->execute();
    $reportData = $stmt->fetchAll();

    // Create a file name
    $filename = "inventory_report_" . date('Y-m-d') . ".txt";

    // Set headers to download the file
    header('Content-Type: text/plain');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Write the report data to the file
    foreach ($reportData as $row) {
        echo "Warehouse Name: " . htmlspecialchars($row['WarehouseName']) . "\n";
        echo "Product Name: " . htmlspecialchars($row['ProductName']) . "\n";
        echo "Total Quantity: " . htmlspecialchars($row['TotalQuantity']) . "\n";
        echo "-------------------------\n";
    }
    exit();
} else {
    header('Location: index.php');
    exit();
}
?>