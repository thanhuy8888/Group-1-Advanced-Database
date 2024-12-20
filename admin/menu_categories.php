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
    $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

    // Lấy thông tin đơn hàng
    $stmt = $con->prepare("SELECT * FROM Orders WHERE OrderID = ?");
    $stmt->execute([$order_id]);
    $order = $stmt->fetch();

    // Lấy chi tiết đơn hàng
    $stmt = $con->prepare("SELECT od.*, p.Name AS ProductName FROM OrderDetails od JOIN Products p ON od.ProductID = p.ProductID WHERE od.OrderID = ?");
    $stmt->execute([$order_id]);
    $order_details = $stmt->fetchAll();

    ?>
    <div class="card">
        <div class="card-header">
            Order Details for Order ID: <?php echo $order['OrderID']; ?>
        </div>
        <div class="card-body">
            <h5>Order Information</h5>
            <p><strong>Customer ID:</strong> <?php echo $order['CustomerID']; ?></p>
            <p><strong>Payment Status:</strong> <?php echo $order['PaymentStatusID']; ?></p>
            <p><strong>Order Date:</strong> <?php echo $order['OrderDate']; ?></p>
            <p><strong>Status:</strong> <?php echo $order['Status']; ?></p>
            <p><strong>Payment Method:</strong> <?php echo $order['PaymentMethod']; ?></p>
            <p><strong>Delivery Date:</strong> <?php echo $order['DeliveryDate']; ?></p>
            <p><strong>Shipping Address:</strong> <?php echo $order['ShippingAddress']; ?></p>

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
            <form method="POST" action="order_details.php?order_id=<?php echo $order_id; ?>">
                <div class="form-group">
                    <label for="status">Update Order Status:</label>
                    <select name="status" class="form-control" required>
                        <option value="Processing">Processing</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </form>

            <!-- Cancel Order -->
            <form method="POST" action="order_details.php?order_id=<?php echo $order_id; ?>" style="margin-top: 20px;">
                <button type="submit" name="cancel_order" class="btn btn-danger">Cancel Order</button>
            </form>
        </div>
    </div>

    <?php
    // Xử lý cập nhật trạng thái đơn hàng
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
        $new_status = $_POST['status'];
        $stmt = $con->prepare("UPDATE Orders SET Status = ? WHERE OrderID = ?");
        $stmt->execute([$new_status, $order_id]);
        echo "<script>alert('Order status updated successfully!'); window.location.reload();</script>";
    }

    // Xử lý hủy đơn hàng
    if (isset($_POST['cancel_order'])) {
        $stmt = $con->prepare("UPDATE Orders SET Status = 'Cancelled' WHERE OrderID = ?");
        $stmt->execute([$order_id]);

        // Cập nhật lại số lượng hàng trong kho
        $stmt = $con->prepare("SELECT ProductID, Quantity FROM OrderDetails WHERE OrderID = ?");
        $stmt->execute([$order_id]);
        $order_details_to_cancel = $stmt->fetchAll();

        foreach ($order_details_to_cancel as $detail) {
            $stmt = $con->prepare("UPDATE InventoryItems SET Quantity = Quantity + ? WHERE ProductID = ?");
            $stmt->execute([$detail['Quantity'], $detail['ProductID']]);
        }

        // Xóa chi tiết đơn hàng
        $stmt = $con->prepare("DELETE FROM OrderDetails WHERE OrderID = ?");
        $stmt->execute([$order_id]);

        echo "<script>alert('Order has been cancelled successfully!'); window.location.replace('orders.php');</script>";
    }

    include 'Includes/templates/footer.php';
} else {
    header('Location: index.php');
    exit();
}
?>