<?php
include "class/user_class.php";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "website_td";
$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
if (isset($_GET["id"])) {
    $user_id = $_GET["id"];

    $queryCheckOrderStatus = "SELECT order_status FROM tbl_order WHERE user_id = '$user_id' AND order_status = 'processing'";
    $resultCheckOrderStatus = $db->query($queryCheckOrderStatus);
    if ($resultCheckOrderStatus->num_rows > 0) {
        echo "<script>alert('Tài khoản này đang thực hiện đặt hàng. Vui lòng tạm ngưng đơn hàng để tiếp tục.'); window.history.back();</script>";
        } else {
        $user = new User();
        $result = $user->delete_user($user_id);

        if ($result) {
            echo "<script>alert('Xóa tài khoản thành công.'); window.location.href = 'userlist.php';</script>";
            // Sử dụng window.location.href để chuyển hướng đến trang userlist.php
            exit;
        } else {
            echo "<script>alert('Xóa tài khoản thất bại.') window.location.href = 'userlist.php';</script>";
            // Không thành công, hiển thị thông báo lỗi và vẫn ở trên trang hiện tại
        }
    }
}