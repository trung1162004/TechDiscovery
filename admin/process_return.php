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
    $products_to_return = $_POST['products_to_return'];
    $return_reasons = $_POST['return_reasons'];
    $upload_directory = "../admin/uploads/";

    // Update order status
    $order_query = "UPDATE tbl_order SET order_status = 'return', return_status = 'processing' WHERE order_id = $order_id";
    $order_result = $db->update($order_query);

    $info_query = "select * from tbl_order where order_id  = $order_id";
    $info_result = $db->select($info_query);
    if($info_result){
        $row = $info_result->fetch_assoc();
        $fullname = $row['fullname'];
        $email = $row['email'];
    }

    foreach ($products_to_return as $order_item_id) {
        $return_reason = $return_reasons[$order_item_id];

        // Handle uploaded images
        if (isset($_FILES['return_images'])) {
            $uploaded_images = $_FILES['return_images'];
            $image_name = $uploaded_images['name'][$order_item_id];
            $image_tmp = $uploaded_images['tmp_name'][$order_item_id];
            $target_file = $upload_directory . basename($image_name);
            move_uploaded_file($image_tmp, $target_file);
        } else {
            $image_name = ""; 
        }

        // Update order item with return data
        $order_item_query = "UPDATE tbl_order_items SET return_reason = '$return_reason', return_img = '$image_name', is_returned = 1 WHERE order_item_id = $order_item_id";
        $order_item_result = $db->update($order_item_query); 
    }

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
        $mail->FromName = "TechDiscovery Notice of return request order";

        $mail->setFrom('techdiscoverys@gmail.com');
        $mail->addAddress($email, $fullname); // Thêm email và tên người nhận

        $mail->Subject = 'Notice of return request';

        // Tạo nội dung email bao gồm thông tin người nhận hàng, sản phẩm đã đặt và tổng đơn hàng
        $emailContent = "<p>Hello $fullname.</p>";
        $emailContent .= "<p>We have received your request for a refund, we will contact you as soon as possible.</p>";

        // Thêm trạng thái đơn hàng vào nội dung email
        $mail->IsHTML(true);
        $mail->Body = $emailContent;

        // Gửi email
        $mail->send();
    } catch (Exception $e) {
        echo $e;
    }
    
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $currentDateTime = date('Y-m-d H:i:s');
    $_SESSION["return_order_notification"] = [
        "message" => "Order return",
        "time" => $currentDateTime,
        "url" => "order_return.php"
    ];
    
    header("Location: ../myAccount_order_return.php");
}
?>