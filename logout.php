<?php
session_start();
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'website_td';

// Tạo lại kết nối tới cơ sở dữ liệu
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check nếu kết nối thành công
if (!$conn) {
    die("Database connection error: " . mysqli_connect_error());
}


if (isset($_SESSION["id"])) {
    $user_id = $_SESSION["id"];
    $is_online = 0; // Trạng thái offline
    $sql_update_offline = "UPDATE users SET is_online = $is_online WHERE id = $user_id";
    mysqli_query($conn, $sql_update_offline);

    session_unset(); // Xóa tất cả các biến session
    session_destroy(); // Hủy session
}

// Đóng kết nối
mysqli_close($conn);

header("Location: index.php"); // Chuyển hướng về trang chính
exit();
?>
