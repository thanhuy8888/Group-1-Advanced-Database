<?php
ob_start();
session_start();

$pageTitle = 'Inventory Management';

if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';
    include 'Includes/templates/navbar.php';

    $do = '';

    if (isset($_GET['do']) && in_array(htmlspecialchars($_GET['do']), array('Add', 'Edit'))) {
        $do = $_GET['do'];
    } else {
        $do = 'Manage';
    }

    if ($do == "Manage") {
        $stmt = $con->prepare("
            SELECT w.WarehouseID, w.Name AS WarehouseName, w.Location, i.Quantity, i.LastUpdated 
            FROM Warehouses w 
            LEFT JOIN InventoryItems i ON w.WarehouseID = i.WarehouseID
        ");
        $stmt->execute();
        $warehouses = $stmt->fetchAll();

        ?>
        <div class="card">
            <div class="card-header">
                <?php echo $pageTitle; ?>
            </div>
            <div class="card-body">

                <!-- ADD NEW WAREHOUSE BUTTON -->
                <div class="above-table" style="margin-bottom: 1rem!important;">
                    <a href="warehouses.php?do=Add" class="btn btn-success">
                        <i class="fa fa-plus"></i> 
                        <span>Add new Warehouse</span>
                    </a>
                </div>
<!-- ADD REPORT BUTTON -->
<div class="above-table" style="margin-bottom: 1rem!important;">
    <button onclick="generateReport()" class="btn btn-info">
        <i class="fa fa-file"></i> 
        <span>Generate Inventory Report</span>
    </button>
</div>
                <!-- WAREHOUSES TABLE -->
                <table class="table table-bordered warehouses-table">
                    <thead>
                        <tr>
                            <th scope="col">Warehouse Name</th>
                            <th scope="col">Location</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Last Updated</th>
                            <th scope="col">Manage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($warehouses as $warehouse) {
                            echo "<tr>";
                                echo "<td>" . htmlspecialchars($warehouse['WarehouseName']) . "</td>";
                                echo "<td>" . htmlspecialchars($warehouse['Location']) . "</td>";
                                echo "<td>" . htmlspecialchars($warehouse['Quantity']) . "</td>";
                                echo "<td>" . htmlspecialchars($warehouse['LastUpdated']) . "</td>";
                                echo "<td>";
                                    $delete_data = "delete_" . $warehouse["WarehouseID"];
                                    ?>
                                    <a href="warehouses.php?do=Edit&warehouse_id=<?php echo $warehouse['WarehouseID']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm delete_warehouse_bttn" data-id="<?php echo $warehouse['WarehouseID']; ?>">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                    <?php
                                echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>  
            </div>
        </div>
        <?php
} elseif ($do == 'Report') {
    // Generate Inventory Report
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

    ?>
    <div class="card">
        <div class="card-header">
            Inventory Report
        </div>
        <div class="card-body">
            <table class="table table-bordered report-table">
                <thead>
                    <tr>
                        <th scope="col">Warehouse Name</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Total Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($reportData as $row) {
                        echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['WarehouseName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['ProductName']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['TotalQuantity']) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
        <?php
    } elseif ($do == 'Add') {
        ?>
        <div class="card">
            <div class="card-header">
                Add New Warehouse
            </div>
            <div class="card-body">
                <form method="POST" class="warehouse_form" action="warehouses.php? do=Add" enctype="multipart/form-data">
                    <div class="panel-X">
                        <div class="panel-header-X">
                            <div class="main-title">
                                Add New Warehouse
                            </div>
                        </div>
                        <div class="save-header-X">
                            <div style="display:flex">
                                <div class="icon">
                                    <i class="fa fa-sliders-h"></i>
                                </div>
                                <div class="title-container">Warehouse details</div>
                            </div>
                            <div class="button-controls">
                                <button type="submit" name="add_new_warehouse" class="btn btn-primary">Save</button>
                            </div>
                        </div>
                        <div class="panel-body-X">

                            <!-- WAREHOUSE NAME INPUT -->
                            <div class="form-group">
                                <label for="warehouse_name">Warehouse Name</label>
                                <input type="text" class="form-control" onkeyup="this.value=this.value.replace(/[^\sa-zA-Z]/g,'');" value="<?php echo (isset($_POST['warehouse_name'])) ? htmlspecialchars($_POST['warehouse_name']) : '' ?>" placeholder="Warehouse Name" name="warehouse_name" required>
                                <?php
                                $flag_add_warehouse_form = 0;

                                if (isset($_POST['add_new_warehouse'])) {
                                    if (empty(test_input($_POST['warehouse_name']))) {
                                        ?>
                                        <div class="invalid-feedback" style="display: block;">
                                            Warehouse name is required.
                                        </div>
                                        <?php
                                        $flag_add_warehouse_form = 1;
                                    }
                                }
                                ?>
                            </div>

                            <!-- LOCATION INPUT -->
                            <div class="form-group">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" value="<?php echo (isset($_POST['location'])) ? htmlspecialchars($_POST['location']) : '' ?>" placeholder="Location" name="location" required>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php

/*** ADD NEW WAREHOUSE ***/

        if (isset($_POST['add_new_warehouse']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_warehouse_form == 0) {
            $warehouse_name = test_input($_POST['warehouse_name']);
            $location = test_input($_POST['location']);

            try {
                $stmt = $con->prepare("INSERT INTO Warehouses (Name, Location) VALUES (?, ?)");
                $stmt->execute(array($warehouse_name, $location));

                ?>
                <!-- SUCCESS MESSAGE -->
                <script type="text/javascript">
                    swal("New Warehouse", "The new warehouse has been inserted successfully", "success").then((value) =>
                    {
                        window.location.replace("warehouses.php");
                    });
                </script>
                <?php

            } catch (Exception $e) {
                echo 'Error occurred: ' . $e->getMessage();
            }
        }

    } elseif ($do == 'Edit') {
        $warehouse_id = (isset($_GET['warehouse_id']) && is_numeric($_GET['warehouse_id'])) ? intval($_GET['warehouse_id']) : 0;

        if ($warehouse_id) {
            $stmt = $con->prepare("SELECT * FROM Warehouses WHERE WarehouseID = ?");
            $stmt->execute(array($warehouse_id));
            $warehouse = $stmt->fetch();
            $count = $stmt->rowCount();

            if ($count > 0) {
                ?>
                <div class="card">
                    <div class="card-header">
                        Edit Warehouse
                    </div>
                    <div class="card-body">
                        <form method="POST" class="warehouse_form" action="warehouses.php?do=Edit&warehouse_id=<?php echo $warehouse['WarehouseID'] ?>" enctype="multipart/form-data">
                            <div class="panel-X">
                                <div class="panel-header-X">
                                    <div class="main-title">
                                        <?php echo htmlspecialchars($warehouse['Name']); ?>
                                    </div>
                                </div>
                                <div class="save-header-X">
                                    <div style="display:flex">
                                        <div class="icon">
                                            <i class="fa fa-sliders-h"></i>
                                        </div>
                                        <div class="title-container">Warehouse details</div>
                                    </div>
                                    <div class="button-controls">
                                        <button type="submit" name="edit_warehouse_sbmt" class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                                <div class="panel-body-X">

                                    <!-- WAREHOUSE ID -->
                                    <input type="hidden" name="warehouse_id" value="<?php echo $warehouse['WarehouseID']; ?>">

                                    <!-- WAREHOUSE NAME INPUT -->
                                    <div class="form-group">
                                        <label for="warehouse_name">Warehouse Name</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($warehouse['Name']); ?>" placeholder="Warehouse Name" name="warehouse_name" required>
                                        <?php
                                        if (isset($_POST['edit_warehouse_sbmt'])) {
                                            if (empty(test_input($_POST['warehouse_name']))) {
                                                ?>
                                                <div class="invalid-feedback" style="display: block;">
                                                    Warehouse name is required.
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>

                                    <!-- LOCATION INPUT -->
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($warehouse['Location']); ?>" placeholder="Location" name="location" required>
                                        <?php
                                        if (isset($_POST['edit_warehouse_sbmt'])) {
                                            if (empty(test_input($_POST['location']))) {
                                                ?>
                                                <div class="invalid-feedback" style="display: block;">
                                                    Location is required.
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <?php

                /*** EDIT WAREHOUSE ***/
                if (isset($_POST['edit_warehouse_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
                    $warehouse_id = test_input($_POST['warehouse_id']);
                    $warehouse_name = test_input($_POST['warehouse_name']);
                    $location = test_input($_POST['location']);

                    try {
                        $stmt = $con->prepare("UPDATE Warehouses SET Name = ?, Location = ? WHERE WarehouseID = ?");
                        $stmt->execute(array($warehouse_name, $location, $warehouse_id));

                        ?>
                        <!-- SUCCESS MESSAGE -->
                        <script type="text/javascript">
                            swal("Edit Warehouse", "Warehouse has been updated successfully", "success").then((value) => {
                                window.location.replace("warehouses.php");
                            });
                        </script>
                        <?php

                    } catch (Exception $e) {
                        echo 'Error occurred: ' . $e->getMessage();
                    }
                }
            } else {
                header('Location: warehouses.php');
                exit();
            }
        } else {
            header('Location: warehouses.php');
            exit();
        }
    }

    include 'Includes/templates/footer.php';
} else {
    header('Location: index.php');
    exit();
}
?>
<!-- JS SCRIPT -->

<script type="text/javascript">
function generateReport() {
    // Redirect to the report generation and export script
    window.location.href = "export_report.php";
}
</script>