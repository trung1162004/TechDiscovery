<!-- Trang change_password.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <style>
        /* Thêm CSS cho hình nền */
        body {
            background-image: url('image/profile-picture-background-l1y44ijo20ooanql.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            /* Thay đổi màu chữ để phù hợp với hình nền */
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            /* Sửa độ cao cho phù hợp với trang */
            flex-direction: column;
        }

        .change-password-form {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .change-password-form h2 {
            margin-bottom: 10px;
        }

        .change-password-form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-radius: 5px;
        }

        .change-password-form button[type="submit"] {
            background-color: #00bcd4;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .change-password-form button[type="submit"]:hover {
            background-color: #007a8a;
        }
    </style>
</head>

<body>
    <div class="center-content">
    <?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "website_td";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_SESSION['id'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];

        if ($newPassword === $confirmPassword) {
            // Mã hóa mật khẩu mới trước khi lưu vào cơ sở dữ liệu
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $sql = "UPDATE users SET password = '$hashedPassword' WHERE id = $id";
            if ($conn->query($sql) === TRUE) {
                $_SESSION['password_change_success'] = true; // Lưu thông báo thành công trong session
                header("Location: profile.php"); // Chuyển hướng người dùng trở lại trang profile
                exit();
            } else {
                echo "Lỗi khi thay đổi mật khẩu: " . $conn->error;
            }
        } else {
            echo "Mật khẩu mới và xác nhận mật khẩu không khớp.";
        }
    }
} else {
    echo "Bạn cần đăng nhập để xem trang này.";
}

$conn->close();
?>


        <div class="change-password-form">
            <h2>Thay đổi mật khẩu</h2>
            <form action="change_password.php" method="POST">
                <input type="password" name="new_password" placeholder="Mật khẩu mới" required>
                <input type="password" name="confirm_password" placeholder="Xác nhận mật khẩu mới" required>
                <button type="submit">Thay đổi mật khẩu</button>
            </form>
        </div>
    </div>
</body>

</html>
