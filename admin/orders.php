<?php
ob_start();
session_start();

$pageTitle = 'Order Details';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    include 'connect.php';
    include 'Includes/functions/functions.php';
    include 'Includes/templates/header.php';
    include 'Includes/templates/navbar.php';

    // Lấy OrderID từ tham số GET
    $order_id = isset($_GET['OrderID']) ? intval($_GET['OrderID']) : 0;

    if ($order_id <= 0) {
        echo "<div class='alert alert-danger'>Invalid Order ID. Please check the URL.</div>";
    } else {
        try {
            // Lấy thông tin đơn hàng
            $stmt = $con->prepare("SELECT * FROM Orders WHERE OrderID = ?");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch();

            if (!$order) {
                echo "<div class='alert alert-danger'>Order not found. Please check the Order ID or ensure it exists in the database.</div>";
            } else {
                // Lấy chi tiết đơn hàng
                $stmt = $con->prepare(
                    "SELECT od.*, p.Name AS ProductName 
                     FROM OrderDetails od 
                     JOIN Products p ON od.ProductID = p.ProductID 
                     WHERE od.OrderID = ?"
                );
                $stmt->execute([$order_id]);
                $order_details = $stmt->fetchAll();
                ?>
                <div class="card">
                    <div class="card-header">
                        Order Details for Order ID: <?php echo htmlspecialchars($order['OrderID']); ?>
                    </div>
                    <div class="card-body">
                        <h5>Order Information</h5>
                        <p><strong>Customer ID:</strong> <?php echo htmlspecialchars($order['CustomerID']); ?></p>
                        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($order['PaymentStatusID']); ?></p>
                        <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['OrderDate']); ?></p>
                        <p><strong>Status:</strong> <?php echo htmlspecialchars($order['Status']); ?></p>
                        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['PaymentMethod']); ?></p>
                        <p><strong>Delivery Date:</strong> <?php echo htmlspecialchars($order['DeliveryDate']); ?></p>
                        <p><strong>Shipping Address:</strong> <?php echo htmlspecialchars($order['ShippingAddress']); ?></p>

                        <h5>Order Details</h5>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Discount</th>
                                    <th>Tax</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($order_details as $detail) { ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($detail['ProductName']); ?></td>
                                        <td><?php echo htmlspecialchars($detail['Quantity']); ?></td>
                                        <td><?php echo htmlspecialchars($detail['Price']); ?></td>
                                        <td><?php echo htmlspecialchars($detail['Discount']); ?></td>
                                        <td><?php echo htmlspecialchars($detail['Tax']); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <!-- Update Order Status -->
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="status">Update Order Status:</label>
                                <select name="status" class="form-control" required>
                                    <option value="Processing">Processing</option>
                                    <option value="Shipped">Shipped</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                            <button type="submit" name="update_status" class="btn btn-primary">Update Status</button>
                        </form>

                        <!-- Cancel Order -->
                        <form method="POST" action="" style="margin-top: 20px;">
                            <button type="submit" name="cancel_order" class="btn btn-danger">Cancel Order</button>
                        </form>
                    </div>
                </div>
                <?php

                // Xử lý cập nhật trạng thái đơn hàng
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
                    $new_status = $_POST['status'];
                    $stmt = $con->prepare("UPDATE Orders SET Status = ? WHERE OrderID = ?");
                    $stmt->execute([$new_status, $order_id]);
                    echo "<script>alert('Order status updated successfully!'); window.location.reload();</script>";
                }

                // Xử lý hủy đơn hàng
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel_order'])) {
                    $stmt = $con->prepare("SELECT od.ProductID, od.Quantity FROM OrderDetails od WHERE od.OrderID = ?");
                    $stmt->execute([$order_id]);
                    $order_details_to_cancel = $stmt->fetchAll();

                    // Start transaction
                    $con->beginTransaction();
                    try {
                        // Update order status to 'Cancelled'
                        $stmt = $con->prepare("UPDATE Orders SET Status = 'Cancelled' WHERE OrderID = ?");
                        $stmt->execute([$order_id]);

                        // Update inventory quantities
                        foreach ($order_details_to_cancel as $detail) {
                            $stmt = $con->prepare("UPDATE InventoryItems SET Quantity = Quantity + ? WHERE ProductID = ? AND WarehouseID = 1");
                            $stmt->execute([$detail['Quantity'], $detail['ProductID']]);
                        }

                        // Commit transaction
                        $con->commit();
                        echo "<script>alert('Order has been cancelled successfully!'); window.location.replace('orders.php');</script>";
                    } catch (Exception $e) {
                        // Rollback transaction in case of error
                        $con->rollBack();
                        echo "<div class='alert alert-danger'>Error cancelling order: " . htmlspecialchars($e->getMessage()) . "</div>";
                    }
                }
            }
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>An error occurred: " . htmlspecialchars($e->getMessage()) . "</div>";
        }
    }
    include 'Includes/templates/footer.php';
} else {
    header('Location: index.php');
    exit();
}
?>
