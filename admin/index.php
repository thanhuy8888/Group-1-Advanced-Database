<?php 
session_start();
$pageTitle = 'Admin Login';

// Kiểm tra nếu người dùng đã đăng nhập
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    header('Location: dashboard.php');
}

// PHP INCLUDES
include 'connect.php'; 
include 'Includes/functions/functions.php'; 
include 'Includes/templates/header.php'; 

// LOGIN FORM
?>

<div class="login">
    <form class="login-container validate-form" name="login-form" action="index.php" method="POST" onsubmit="return validateLoginForm()">
        <span class="login100-form-title p-b-32">Admin Login</span>
        <?php
        // Kiểm tra nếu người dùng nhấn nút submit
        if (isset($_POST['admin_login'])) {
            $username = test_input($_POST['Username']);
            $password = test_input($_POST['PasswordHash']);
            $hashedPass = $password; // Giả sử mật khẩu đã được băm trước khi lưu vào cơ sở dữ liệu

            // Kiểm tra xem người dùng có tồn tại trong cơ sở dữ liệu không
            $stmt = $con->prepare("SELECT UserID, Username, PasswordHash FROM Users WHERE Username = ? AND PasswordHash = ?");
            $stmt->execute(array($username, $hashedPass));
            $row = $stmt->fetch();
            $count = $stmt->rowCount();

            // Kiểm tra nếu có bản ghi nào được tìm thấy
            if ($count > 0) {
                $_SESSION['username'] = $username; // Lưu tên người dùng vào phiên
                $_SESSION['password'] = $password; // Lưu thông tin admin vào phiên
                $_SESSION['userid'] = $row['UserID']; // Lưu ID người dùng vào phiên
                header('Location: dashboard.php');
                die();
            } else {
                ?>
                <div class="alert alert-danger">
                    <button data-dismiss="alert" class="close close-sm" type="button">
                        <span aria-hidden="true">×</span>
                    </button>
                    <div class="messages">
                        <div>Tên người dùng và/hoặc mật khẩu không chính xác!</div>
                    </div>
                </div>
                <?php 
            }
        }
        ?>

        <!-- USERNAME INPUT -->
        <div class="form-input">
            <span class="txt1">Username</span>
            <input type="text" name="Username" class="form-control username" oninput="document.getElementById('username_required').style.display = 'none'" id="user" autocomplete="off">
            <div class="invalid-feedback" id="username_required">Username is required!</div>
        </div>

        <!-- PASSWORD INPUT -->
        <div class="form-input">
            <span class="txt1">Password</span>
            <input type="password" name="PasswordHash" class="form-control" oninput="document.getElementById('password_required').style.display = 'none'" id="password" autocomplete="new-password">
            <div class="invalid-feedback" id="password_required">Password is required!</div>
        </div>

        <!-- SIGNIN BUTTON -->
        <p>
            <button type="submit" name="admin_login">Sign In</button>
        </p>

        <!-- FORGOT PASSWORD PART -->
        <span class="forgotPW">Forgot your password? <a href="#">Reset it here.</a></span>
    </form>
</div>

<?php include 'Includes/templates/footer.php'; ?>