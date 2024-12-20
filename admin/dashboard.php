<?php
	
	//Start session
    session_start();

    //Set page title
    $pageTitle = 'Dashboard';

    //PHP INCLUDES
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //TEST IF THE SESSION HAS BEEN CREATED BEFORE

    if (isset($_SESSION['username']) && isset($_SESSION['password']))
    {
    	include 'Includes/templates/navbar.php';

    	?>

            <script type="text/javascript">

                var vertical_menu = document.getElementById("vertical-menu");


                var current = vertical_menu.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_menu.getElementsByClassName('dashboard_link')[0].className += " active_link";

            </script>

            <!-- TOP 4 CARDS -->

            <div class="row">
                <div class="col-sm-6 col-lg-3">
                    <div class="panel panel-green ">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="fa fa-users fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span><?php echo countItems("CustomerID","Customers")?></span></div>
                                    <div>Total Customers</div>
                                </div>
                            </div>
                        </div>
                        <a href="clients.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="fas fa-utensils fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span><?php echo countItems("WarehouseID","Warehouses")?></span></div>
                                    <div>Total Warehouses</div>
                                </div>
                            </div>
                        </div>
                        <a href="warehouses.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <div class=" col-sm-6 col-lg-3">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="far fa-calendar-alt fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span>20</span></div>
                                    <div>Total Suppliers</div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class=" col-sm-6 col-lg-3">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-sm-3">
                                    <i class="fas fa-pizza-slice fa-4x"></i>
                                </div>
                                <div class="col-sm-9 text-right">
                                    <div class="huge"><span><?php echo countItems("OrderID","Orders")?></span></div>
                                    <div>Total Orders</div>
                                </div>
                            </div>
                        </div>
                        <a href="orders.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- START ORDERS TABS -->

            <div class="card" style = "margin: 20px 10px">

                <!-- TABS BUTTONS -->

                <div class="card-header tab" style="padding:0px;">
                    <button class="tablinks_orders active" onclick="openTab(event, 'recent_orders','tabcontent_orders','tablinks_orders')">Recent Orders</button>
                    <button class="tablinks_orders" onclick="openTab(event, 'completed_orders','tabcontent_orders','tablinks_orders')">Completed Orders</button>
                    <button class="tablinks_orders" onclick="openTab(event, 'canceled_orders','tabcontent_orders','tablinks_orders')">Canceled Orders</button>
                </div>

                <!-- TABS CONTENT -->
                
                <div class="card-body">
                    <div class='responsive-table'>

                        <!-- RECENT ORDERS -->

                        <table class="table X-table tabcontent_orders" id="recent_orders" style="display:table">
                            <thead>
                                <tr>
                                    <th>
                                        Order Date
                                    </th>
                                    <th>
                                        Quantity
                                    </th>
                                    <th>
                                        Total Price
                                    </th>
                                    <th>
                                        Customer
                                    </th>
                                    <th>
                                        Manage
                                    </th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php
$stmt = $con->prepare("
SELECT 
    O.OrderDate AS OrderDate, 
    O.OrderID AS order_id,
    C.CustomerID AS customer_id,  
    C.Name AS customer_name,        
    C.PhoneNumber AS customer_phone, 
    C.Email AS customer_email,      
    SUM(OD.Quantity) AS total_quantity, 
    SUM(OD.Quantity * OD.Price) AS total_price
FROM 
    Orders O
JOIN 
    OrderDetails OD ON O.OrderID = OD.OrderID
JOIN 
    Customers C ON O.CustomerID = C.CustomerID
WHERE 
    O.Status IN ('Processing', 'shipped', 'cancelled')
GROUP BY 
    O.OrderID, C.CustomerID 
ORDER BY 
    O.OrderDate DESC;
");

$stmt->execute();
$Orders = $stmt->fetchAll();
$count = $stmt->rowCount();

if ($count == 0) {
echo "<tr>";
echo "<td colspan='5' style='text-align:center;'>";
echo "List of your recent orders will be presented here";
echo "</td>";
echo "</tr>";
} else {
foreach ($Orders as $order) {
    echo "<tr>";
    echo "<td>";
    echo $order['OrderDate']; 
    echo "</td>";
    echo "<td>";


    $stmtMenus = $con->prepare("
        SELECT 
            P.Name AS product_name,  
            OD.Quantity AS quantity, 
            OD.Price AS menu_price 
        FROM 
            OrderDetails OD
        JOIN 
            Products P ON OD.ProductID = P.ProductID 
        WHERE 
            OD.OrderID = ?
    ");
    $stmtMenus->execute(array($order['order_id'])); 
    $menus = $stmtMenus->fetchAll();

    $total_price = 0;

    foreach ($menus as $menu) {
        echo "<span style='display:block'>".$menu['product_name']." - ".$menu['quantity']." x ".$menu['menu_price']."$</span>";
        $total_price += ($menu['menu_price'] * $menu['quantity']);
    }

    echo "</td>";
    echo "<td>";
    echo $total_price."$";
    echo "</td>";
    echo "<td>";
    ?>
    <button class="btn btn-info btn-sm rounded-0" type="button" data-toggle="modal" data-target="#customer_<?php echo $order['customer_id']; ?>" data-placement="top">
        <?php echo $order['customer_id']; ?>
    </button>
<!-- Customer Modal -->
<div class="modal fade" id="<?php echo "Customer_".$order['customer_id']; ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Details</h5> 
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul>
                    <li><span style="font-weight: bold;">Full name: </span> <?php echo $order['customer_name']; ?></li> 
                    <li><span style="font-weight: bold;">Phone number: </span><?php echo $order['customer_phone']; ?></li> 
                    <li><span style="font-weight: bold;">E-mail: </span><?php echo $order['customer_email']; ?></li> 
                </ul>
            </div>
        </div>
    </div>
</div>

<?php
echo "</td>";

echo "<td>";

$cancel_data = "cancel_order" . $order["order_id"];
$deliver_data = "deliver_order" . $order["order_id"];
?>
<ul class="list-inline m-0">
<!-- Deliver Order BUTTON -->
<li class="list-inline-item" data-toggle="tooltip" title="Deliver Order">
    <button class="btn btn-info btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $deliver_data; ?>" data-placement="top">
        <i class="fas fa-truck"></i>
    </button>

    <!-- DELIVER MODAL -->
    <div class="modal fade" id="<?php echo $deliver_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $deliver_data; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deliver Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Mark order as delivered?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" data-id="<?php echo $order['order_id']; ?>" class="btn btn-info deliver_order_button">
                        Yes
                    </button>
                </div>
            </div>
        </div>
    </div>
</li>
 <!-- CANCEL BUTTON -->
<li class="list-inline-item" data-toggle="tooltip" title="Cancel Order">
    <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $cancel_data; ?>" data-placement="top">
        <i class="fas fa-calendar-times"></i>
    </button>

    <!-- CANCEL MODAL -->
    <div class="modal fade" id="<?php echo $cancel_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $cancel_data; ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Cancellation Reason</label>
                        <textarea class="form-control" id="cancellation_reason_order_<?php echo $order['order_id']; ?>" required="required"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                    <button type="button" data-id="<?php echo $order['order_id']; ?>" class="btn btn-danger cancel_order_button">
                        Cancel Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</li>
</ul>
<?php
echo "</td>";
echo "</tr>";
}
}
?>

                            </tbody>
                        </table>

<!-- COMPLETED ORDERS -->

<table class="table X-table tabcontent_orders" id="completed_orders">
    <thead>
        <tr>
            <th>
                Order Date
            </th>
            <th>
                Payment Method
            </th>
            <th>
                Customer
            </th>
            <th>
                Actions
            </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $stmt = $con->prepare("SELECT o.OrderDate, o.PaymentMethod, c.CustomerID, c.Name 
                                FROM Orders o 
                                JOIN Customers c ON o.CustomerID = c.CustomerID
                                WHERE o.Status = 'Shipped'
                                ORDER BY o.OrderDate;");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();

        if ($count == 0) {
            echo "<tr>";
            echo "<td colspan='4' style='text-align:center;'>";
            echo "List of your completed orders will be presented here";
            echo "</td>";
            echo "</tr>";
        } else {
            foreach ($rows as $order) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($order['OrderDate']) . "</td>";
                echo "<td>" . htmlspecialchars($order['PaymentMethod']) . "</td>";
                echo "<td>" . htmlspecialchars($order['Name']) . "</td>";
                echo "<td>";
                // Nút để hiển thị thông tin khách hàng
                echo "<button class='btn btn-info btn-sm' data-toggle='modal' data-target='#CustomerModal_" . $order['CustomerID'] . "'>View Customer</button>";
                echo "</td>";
                echo "</tr>";

                // Modal hiển thị thông tin khách hàng
                echo "<div class='modal fade' id='CustomerModal_" . $order['CustomerID'] . "' tabindex='-1' role='dialog' aria-labelledby='CustomerModalLabel' aria-hidden='true'>";
                echo "<div class='modal-dialog' role='document'>";
                echo "<div class='modal-content'>";
                echo "<div class='modal-header'>";
                echo "<h5 class='modal-title' id='CustomerModalLabel'>Customer Information</h5>";
                echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                echo "<span aria-hidden='true'>&times;</span>";
                echo "</button>";
                echo "</div>";
                echo "<div class='modal-body'>";

                // Lấy thông tin khách hàng từ cơ sở dữ liệu
                $customerStmt = $con->prepare("SELECT * FROM Customers WHERE CustomerID = ?");
                $customerStmt->execute([$order['CustomerID']]);
                $customer = $customerStmt->fetch();

                // Hiển thị thông tin khách hàng
                echo "<p><strong>Name:</strong> " . htmlspecialchars($customer['Name']) . "</p>";
                echo "<p><strong>Phone Number:</strong> " . htmlspecialchars($customer['PhoneNumber']) . "</p>";
                echo "<p><strong>Email:</strong> " . htmlspecialchars($customer['Email']) . "</p>";
                echo "<p><strong>Address:</strong> " . htmlspecialchars($customer['Address']) . "</p>";
                echo "<p><strong>Date of Birth:</strong> " . htmlspecialchars($customer['DateOfBirth']) . "</p>";
                echo "<p><strong>Gender:</strong> " . htmlspecialchars($customer['Gender']) . "</p>";

                echo "</div>";
                echo "<div class='modal-footer'>";
                echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </tbody>
</table>

<!-- CANCELED ORDERS -->

<table class="table X-table tabcontent_orders" id="canceled_orders">
    <thead>
        <tr>
            <th>
                Order Date
            </th>
            <th>
                Customer
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody>

        <?php
        $stmt = $con->prepare("SELECT o.OrderDate, c.CustomerID, c.Name, o.Status 
                                FROM Orders o 
                                JOIN Customers c ON o.CustomerID = c.CustomerID
                                WHERE o.Status = 'cancelled'
                                ORDER BY o.OrderDate;");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $count = $stmt->rowCount();

        if ($count == 0) {
            echo "<tr>";
            echo "<td colspan='3' style='text-align:center;'>";
            echo "List of your canceled orders will be presented here";
            echo "</td>";
            echo "</tr>";
        } else {
            foreach ($rows as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['OrderDate']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['Status']) . "</td>"; // Giả sử Status chứa lý do hủy
                echo "</tr>";
            }
        }
        ?>
                    </div>
                </div>
            </div>

            <!-- END ORDERS TABS -->

        <?php

    	include 'Includes/templates/footer.php';

    }
    else
    {
    	header("Location: index.php");
    	exit();
    }

?>

<!-- JS SCRIPTS -->

<script type="text/javascript">
    
    // WHEN DELIVER ORDER BUTTON IS CLICKED
    $('.deliver_order_button').click(function() {
        var order_id = $(this).data('id');
        var do_ = 'Deliver_Order';

        $.ajax({
            url: "ajax_files/dashboard_ajax.php",
            type: "POST",
            data: { do_: do_, order_id: order_id },
            success: function(data) {
                $('#deliver_order' + order_id).modal('hide');
                swal("Order Delivered", "The order has been marked as delivered", "success").then((value) => {
                    window.location.replace("dashboard.php");
                });
            },
            error: function(xhr, status, error) {
                alert('AN ERROR HAS OCCURRED WHILE TRYING TO PROCESS YOUR REQUEST!');
            }
        });
    });

    // WHEN CANCEL ORDER BUTTON IS CLICKED
    $('.cancel_order_button').click(function() {
        var order_id = $(this).data('id');
        var cancellation_reason_order = $('#cancellation_reason_order_' + order_id).val();
        var do_ = 'Cancel_Order';

        // Kiểm tra xem lý do hủy có được nhập hay không
        if (!cancellation_reason_order) {
            alert('Please provide a cancellation reason.');
            return;
        }

        $.ajax({
            url: "ajax_files/dashboard_ajax.php",
            type: "POST",
            data: { order_id: order_id, cancellation_reason_order: cancellation_reason_order, do_: do_ },
            success: function(data) {
                $('#cancel_order' + order_id).modal('hide');
                swal("Order Canceled", "The order has been canceled successfully", "success").then((value) => {
                    window.location.replace("dashboard.php");
                });
            },
            error: function(xhr, status, error) {
                alert('AN ERROR HAS OCCURRED WHILE TRYING TO PROCESS YOUR REQUEST!');
            }
        });
    });

</script>