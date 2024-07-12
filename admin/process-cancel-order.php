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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $order_id = $_GET['id'];
    $query = "UPDATE tbl_order SET order_status = 'cancelled' WHERE order_id = $order_id";
    $result = $db->update($query);

    $product_query = "select * from tbl_order_items where order_id = $order_id";
    $product_result = $db->select($product_query);
    $productItems = array();
    if ($product_result) {
        while ($row = $product_result->fetch_assoc()) {
            $productItems[] = array(
                'product_name' => $row['product_name'],
                'product_price' => $row['product_price'],
                'product_color' => $row['product_color'],
                'product_memory_ram' => $row['product_memory_ram'],
                'quantity' => $row['quantity'],
                'product_id' => $row['product_id']
            );
        }
        foreach ($productItems as $item) {
            $quantity = $item['quantity'];
            $product_id = $item['product_id'];
            $select_product = "SELECT product_quantity FROM tbl_product WHERE product_id = $product_id";
            $result_product = $db->select($select_product);
            if ($result_product) {
                $row = $result_product->fetch_assoc();
                $product_quantity = $row['product_quantity'];
            }
            $update_quantity = $quantity + $product_quantity;
            $update_query = "update tbl_product set product_quantity = $update_quantity where product_id = $product_id";
            $update_result = $db->update($update_query);
        }
    }
    $select_query = "select * from tbl_order where order_id = $order_id";
    $select_result = $db->select($select_query);
    if ($select_result) {
        // Lấy thông tin từ kết quả truy vấn
        $row = $select_result->fetch_assoc();
        $fullname = $row["fullname"];
        $email = $row["email"];
        $total_order = $row['total_order'];

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
        $mail->FromName = "TechDiscovery Notice of successful cancel order";

        $mail->setFrom('techdiscoverys@gmail.com');
        $mail->addAddress($email, $fullname); // Thêm email và tên người nhận

        $mail->Subject = 'Order cancellation notice';

        // Tạo nội dung email bao gồm thông tin người nhận hàng, sản phẩm đã đặt và tổng đơn hàng
        $emailContent = "<p>Hello $fullname.</p>";
        $emailContent .= "<p>Confirmed order cancellation successfully: </p>";

        // Lặp qua từng sản phẩm đã đặt
        foreach ($productItems as $item) {
            $productName = $item['product_name'];
            $productColor = $item['product_color'];
            $productMemoryRam = $item['product_memory_ram'];
            $quantity = $item['quantity'];
            $product_price = $item['product_price'];
            $emailContent .= "<p>Product: $productName | $productColor | $productMemoryRam | Quantity: $quantity | $ $product_price.</p>";
        }

        $emailContent .= "<p>Total order: $ $total_order</p>";
        $emailContent .= "<p>If you have already paid please provide your account number and reply back to this message so that we can proceed to refund you, thanks and best regards.</p>";

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
    $_SESSION["cancel_order_notification"] = [
        "message" => "Order canceled",
        "time" => $currentDateTime,
        "url" => "order_canceled.php"
    ];

   header("Location: ../myAccount_order_cancel.php");
}
