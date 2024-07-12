<?php
session_start();
include "database.php";
include "../mail/PHPMailer.php";
include "../mail/Exception.php";
include "../mail/OAuth.php";
include "../mail/POP3.php";
include "../mail/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
$db = new Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];

    $info_query = "select * from tbl_order where order_id  = $order_id";
    $info_result = $db->select($info_query);
    if($info_result){
        $row = $info_result->fetch_assoc();
        $fullname = $row['fullname'];
        $email = $row['email'];
    }

    // Update order status
    $order_query = "UPDATE tbl_order SET return_status = 'do not accept the request' WHERE order_id = $order_id";
    $order_result = $db->update($order_query);

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = "tls";
        $mail->SMTPAuth = true;
        $mail->Username = 'techdiscoverys@gmail.com';
        $mail->Password = 'gzgkpyvjmuoovenp'; // sử dụng mật khẩu ứng dụng
        $mail->FromName = "TechDiscovery Responding to Order Return Requests";

        $mail->setFrom('techdiscoverys@gmail.com');
        $mail->addAddress($email, $fullname); // Thêm email và tên người nhận

        $mail->Subject = 'Responding to Order Return Requests';

        // Tạo nội dung email bao gồm thông tin người nhận hàng, sản phẩm đã đặt và tổng đơn hàng
        $emailContent = "<p>Hello $fullname.</p>";
        $emailContent .= "<p>After inspection, we do not approve your return request through the terms of return, refund policy. If there are still problems, please contact us again. Appreciation and thanks</p>";

        // Thêm trạng thái đơn hàng vào nội dung email
        $mail->IsHTML(true);
        $mail->Body = $emailContent;

        // Gửi email
        $mail->send();
    } catch (Exception $e) {
        echo $e;
    }
    
    header("Location: order_return.php");
}
?>