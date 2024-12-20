<?php
    ob_start();
	session_start();

	$pageTitle = 'Users';

	if(isset($_SESSION['username']) && isset($_SESSION['password']))
	{
		include 'connect.php';
  		include 'Includes/functions/functions.php'; 
		include 'Includes/templates/header.php';
		include 'Includes/templates/navbar.php';

        ?>
            <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
            <script type="text/javascript">

                var vertical_menu = document.getElementById("vertical-menu");


                var current = vertical_menu.getElementsByClassName("active_link");

                if(current.length > 0)
                {
                    current[0].classList.remove("active_link");   
                }
                
                vertical_menu.getElementsByClassName('users_link')[0].className += " active_link";

            </script>

<?php
$do = '';

// Kiểm tra xem có tham số 'do' trong URL không
if (isset($_GET['do']) && in_array(htmlspecialchars($_GET['do']), array('Add', 'Edit'))) {
    $do = $_GET['do'];
} else {
    $do = 'Manage';
}

if ($do == "Manage") {
    // Lấy danh sách người dùng từ cơ sở dữ liệu
    $stmt = $con->prepare("SELECT u.UserID, u.Username, u.Email, u.CreatedAtDate, u.CreatedAtTime, r.RoleName 
                            FROM Users u 
                            JOIN Roles r ON u.RoleID = r.RoleID");
    $stmt->execute();
    $users = $stmt->fetchAll();
    ?>
    <div class="card">
        <div class="card-header">
            <?php echo $pageTitle; ?>
            <a href="users.php?do=Add" class="btn btn-primary float-right">Add User</a>
        </div>
        <div class="card-body">

            <!-- USERS TABLE -->

            <table class="table table-bordered users-table">
                <thead>
                    <tr>
                        <th scope="col">Username</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Role</th>
                        <th scope="col">Created At</th>
                        <th scope="col">Manage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Hiển thị danh sách người dùng
                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($user['Username']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['Email']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['RoleName']) . "</td>";
                        echo "<td>" . htmlspecialchars($user['CreatedAtDate']) . " " . htmlspecialchars($user['CreatedAtTime']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-success btn-sm rounded-0'>";
                        echo "<a href='users.php?do=Edit&user_id=" . htmlspecialchars($user['User ID']) . "' style='color: white;'>";
                        echo "<i class='fa fa-edit'></i>";
                        echo "</a>";
                        echo "</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>  
        </div>
    </div>
    <?php
} 
# Edit the user details
elseif ($do == 'Edit') {
    $user_id = (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) ? intval($_GET['user_id']) : 0;

    if ($user_id) {
        // Lấy thông tin người dùng từ cơ sở dữ liệu
        $stmt = $con->prepare("SELECT u.UserID, u.Username, u.Email, u.RoleID, r.RoleName 
                                FROM Users u 
                                JOIN Roles r ON u.RoleID = r.RoleID 
                                WHERE u.UserID = ?");
        $stmt->execute(array($user_id));
        $user = $stmt->fetch();
        $count = $stmt->rowCount();

        if ($count > 0) {
            ?>

            <div class="card">
                <div class="card-header">
                    Edit User
                </div>
                <div class="card-body">
                    <form method="POST" class="menu_form" action="users.php?do=Edit&user_id=<?php echo $user['User ID']; ?>">
                        <div class="panel-X">
                            <div class="panel-header-X">
                                <div class="main-title">
                                    <?php echo htmlspecialchars($user['Username']); ?>
                                </div>
                            </div>
                            <div class="save-header-X">
                                <div style="display:flex">
                                    <div class="icon">
                                        <i class="fa fa-sliders-h"></i>
                                    </div>
                                    <div class="title-container">User  details</div>
                                </div>
                                <div class="button-controls">
                                    <button type="submit" name="edit_user_sbmt" class="btn btn-primary">Save</button>
                                </div>
                            </div>
<div class="panel-body-X">
    <!-- User ID -->
    <input type="hidden" name="user_id" value="<?php echo $user['User ID']; ?>">

    <!-- Username INPUT -->
    <div class="form-group">
        <label for="user_name">Username</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['Username']); ?>" placeholder="Username" name="user_name" required>
        <?php
        $flag_edit_user_form = 0;

        if (isset($_POST['edit_user_sbmt'])) {
            if (empty(test_input($_POST['user_name']))) {
                ?>
                <div class="invalid-feedback" style="display: block;">
                    Username is required.
                </div>
                <?php
                $flag_edit_user_form = 1;
            }
        }
        ?>
    </div>

    <!-- FULL NAME INPUT -->
    <div class="form-group">
        <label for="full_name">Full Name</label>
        <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['FullName']); ?>" placeholder="Full Name" name="full_name" required>
        <?php
        if (isset($_POST['edit_user_sbmt'])) {
            if (empty(test_input($_POST['full_name']))) {
                ?>
                <div class="invalid-feedback" style="display: block;">
                    Full name is required.
                </div>
                <?php
                $flag_edit_user_form = 1;
            }
        }
        ?>
    </div>

    <!-- User Email INPUT -->
    <div class="form-group">
        <label for="user_email">User  E-mail</label>
        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" placeholder="User  Email" name="user_email" required>
        <?php
        if (isset($_POST['edit_user_sbmt'])) {
            if (empty(test_input($_POST['user_email']))) {
                ?>
                <div class="invalid-feedback" style="display: block;">
                    User E-mail is required.
                </div>
                <?php
                $flag_edit_user_form = 1;
            } elseif (!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                ?>
                <div class="invalid-feedback" style="display: block;">
                    Invalid e-mail.
                </div>
                <?php
                $flag_edit_user_form = 1;
            }
        }
        ?>
    </div>

    <!-- User Password INPUT -->
    <div class="form-group">
        <label for="user_password">User  Password</label>
        <input type="password" class="form-control" placeholder="Change password" name="user_password">
        <?php
        if (isset($_POST['edit_user_sbmt'])) {
            if (!empty($_POST['user_password']) && strlen($_POST['user_password']) < 8) {
                ?>
                <div class="invalid-feedback" style="display: block;">
                    Password length must be at least 8 characters.
                </div>
                <?php
                $flag_edit_user_form = 1;
            }
        }
        ?>
    </div>

    <!-- Role Selection -->
    <div class="form-group">
        <label for="role_id">Role</label>
        <select class="form-control" id="role_id" name="role_id" required>
            <?php
            // Lấy danh sách vai trò để hiển thị trong dropdown
            $roleStmt = $con->prepare("SELECT * FROM Roles");
            $roleStmt->execute();
            $roles = $roleStmt->fetchAll();

            foreach ($roles as $role) {
                $selected = ($role['RoleID'] == $user['RoleID']) ? 'selected' : '';
                echo "<option value='" . htmlspecialchars($role['RoleID']) . "' $selected>" . htmlspecialchars($role['RoleName']) . "</option>";
            }
            ?>
        </select>
    </div>
</div>

<?php
// Kiểm tra xem người dùng đã đăng nhập hay chưa
session_start(); // Đảm bảo rằng phiên đã được khởi động

if (isset($_SESSION['user_id'])) {
    // Kiểm tra xem có yêu cầu chỉnh sửa người dùng không
    if (isset($_POST['edit_user_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $flag_edit_user_form = 0; // Biến cờ để kiểm tra lỗi

        // Lấy dữ liệu từ biểu mẫu và làm sạch
        $user_id = test_input($_POST['user_id']);
        $user_name = test_input($_POST['user_name']);
        $user_fullname = test_input($_POST['full_name']);
        $user_email = test_input($_POST['user_email']);
        $user_password = $_POST['user_password'];
        $role_id = test_input($_POST['role_id']); // Lấy role_id từ biểu mẫu

        try {
            // Cập nhật thông tin người dùng
            if (empty($user_password)) {
                // Nếu không có mật khẩu mới, chỉ cập nhật các trường khác
                $stmt = $con->prepare("UPDATE Users SET Username = ?, Email = ?, FullName = ?, RoleID = ? WHERE UserID = ?");
                $stmt->execute(array($user_name, $user_email, $user_fullname, $role_id, $user_id));
            } else {
                // Nếu có mật khẩu mới, mã hóa mật khẩu và cập nhật
                $user_password = password_hash($user_password, PASSWORD_DEFAULT); // Mã hóa mật khẩu
                $stmt = $con->prepare("UPDATE Users SET Username = ?, Email = ?, FullName = ?, PasswordHash = ?, RoleID = ? WHERE UserID = ?");
                $stmt->execute(array($user_name, $user_email, $user_fullname, $user_password, $role_id, $user_id));
            }

            // Thông báo thành công
            echo '<script type="text/javascript">
                    swal("Edit User", "User  has been updated successfully", "success").then((value) => {
                        window.location.replace("users.php");
                    });
                  </script>';
        } catch (Exception $e) {
            // Xử lý lỗi
            echo 'Error occurred: ' . htmlspecialchars($e->getMessage());
        }
    } else {
        // Nếu không có yêu cầu hợp lệ, chuyển hướng về trang người dùng
        header('Location: users.php');
        exit();
    }
} else {
    // Nếu người dùng chưa đăng nhập, chuyển hướng về trang index
    header('Location: index.php');
    exit();
}

/* FOOTER BOTTOM */
include 'Includes/templates/footer.php';
?>