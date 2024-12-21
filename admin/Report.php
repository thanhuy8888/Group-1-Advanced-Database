<?php
function reporting($con)
{
    // Lấy danh sách người dùng từ cơ sở dữ liệu
    try {
        $stmt = $con->prepare("SELECT u.UserID, u.Username, u.Email, u.CreatedAtDate, u.CreatedAtTime, r.RoleName 
                                FROM Users u 
                                JOIN Roles r ON u.RoleID = r.RoleID");
        $stmt->execute();
        $users = $stmt->fetchAll();

        // Hiển thị bảng báo cáo
        echo '<div class="card">';
        echo '    <div class="card-header">';
        echo '        User Report';
        echo '        <a href="users.php?do=Add" class="btn btn-primary float-right">Add User</a>';
        echo '    </div>';
        echo '    <div class="card-body">';

        echo '        <table class="table table-bordered users-table">';
        echo '            <thead>';
        echo '                <tr>';
        echo '                    <th scope="col">UserID</th>';
        echo '                    <th scope="col">Username</th>';
        echo '                    <th scope="col">E-mail</th>';
        echo '                    <th scope="col">Role</th>';
        echo '                    <th scope="col">Created At</th>';
        echo '                </tr>';
        echo '            </thead>';
        echo '            <tbody>';

        // Hiển thị từng người dùng trong bảng
        foreach ($users as $user) {
            echo '                <tr>';
            echo '                    <td>' . htmlspecialchars($user['UserID']) . '</td>';
            echo '                    <td>' . htmlspecialchars($user['Username']) . '</td>';
            echo '                    <td>' . htmlspecialchars($user['Email']) . '</td>';
            echo '                    <td>' . htmlspecialchars($user['RoleName']) . '</td>';
            echo '                    <td>' . htmlspecialchars($user['CreatedAtDate']) . ' ' . htmlspecialchars($user['CreatedAtTime']) . '</td>';
            echo '                </tr>';
        }

        echo '            </tbody>';
        echo '        </table>';
        echo '    </div>';
        echo '</div>';

    } catch (Exception $e) {
        // Hiển thị lỗi nếu xảy ra vấn đề
        echo '<div class="alert alert-danger">Error: ' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Sử dụng function này trong trang PHP của bạn
if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
    include 'connect.php';
    reporting($con);
} else {
    header('Location: index.php');
    exit();
}
?>
