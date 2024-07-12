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
    $order_query = "UPDATE tbl_order SET return_status = 'accept requests' WHERE order_id = $order_id";
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
        $emailContent .= "<p>After checking, we have approved your return request, Please pack and send the goods to the address 391a Nam Ky Khoi Nghia street, Vo Thi Sau ward, district 3, Ho Chi Minh city. and please reply to this email with your bank account number so that after we receive the goods you sent and check 1 more time, if all is ok then we will refund you. Appreciation and thanks</p>";

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