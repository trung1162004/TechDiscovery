<?php
session_start();
include "../mail/PHPMailer.php";
include "../mail/Exception.php";
include "../mail/OAuth.php";
include "../mail/POP3.php";
include "../mail/SMTP.php";
include "database.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$database = new Database();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["id"])) {
        $user_id = $_SESSION['id'];
    }
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $currentDateTime = date('Y-m-d H:i:s');
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $province = $_POST['province'];
    $district = $_POST['district'];
    $ward = $_POST['ward'];
    $address = $_POST['address'];
    $total_order = $_POST['total_order'];
    $order_status = 'processing';
    $payment_method = $_POST['payment_method'];
    $status_payment = $_POST['status_payment'];

    $insert_query = ("insert into tbl_order (user_id, order_date, payment_method, order_status, fullname, phone, email, province, district, ward, address, status_payment, total_order) values 
        ($user_id, '$currentDateTime', '$payment_method', '$order_status', '$fullname', '$phone', '$email', '$province', '$district', '$ward', '$address', '$status_payment', '$total_order')");
    $insert_result = $database->insert($insert_query);

    $select_query = "SELECT * FROM tbl_order where user_id = $user_id";
    $select_result = $database->select($select_query);
    if ($select_result) {
        while ($row = $select_result->fetch_assoc()) {
            $order_id = $row['order_id'];
        }
        $cart_query = "SELECT * FROM tbl_cart where user_id = $user_id";
        $cart_result = $database->select($cart_query);
        $cartItems = array();
        if ($cart_result) {
            while ($row = $cart_result->fetch_assoc()) {
                $cartItems[] = array(
                    'product_name' => $row['product_name'],
                    'product_price' => $row['product_price'],
                    'product_color' => $row['product_color'],
                    'product_memory_ram' => $row['product_memory_ram'],
                    'quantity' => $row['quantity'],
                    'product_img' => $row['product_img'],
                    'product_id' => $row['product_id']
                );
            }
        }
        foreach ($cartItems as $item) {
            $product_name = $item['product_name'];
            $product_color = $item['product_color'];
            $product_memory_ram = $item['product_memory_ram'];
            $quantity = $item['quantity'];
            $product_img = $item['product_img'];
            $product_id = $item['product_id'];
            $product_price = $item['product_price'];
            $order_query = ("insert into tbl_order_items (order_id, product_img, product_name, product_color, product_memory_ram, quantity, product_id, product_price) values 
            ($order_id, '$product_img', '$product_name', '$product_color', '$product_memory_ram', $quantity, $product_id, $product_price)");
            $order_result = $database->insert($order_query);
            $select_product = "SELECT product_quantity FROM tbl_product WHERE product_id = $product_id";
            $result_product = $database->select($select_product);

            if ($result_product) {
                $row = $result_product->fetch_assoc();
                $product_quantity = $row['product_quantity'];

                // Trừ số lượng đã mua từ số lượng hiện tại trong kho
                $product_quantity_update = $product_quantity - $quantity;

                if ($product_quantity_update >= 0) {
                    // Cập nhật số lượng mới trong kho
                    $update_product = "UPDATE tbl_product SET product_quantity = $product_quantity_update WHERE product_id = $product_id";
                    $update_result = $database->update($update_product);
                }
            }
        }
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
        $mail->FromName = "TechDiscovery Notice of successful order";

        $mail->setFrom('techdiscoverys@gmail.com');
        $mail->addAddress($email, $fullname); // Thêm email và tên người nhận

        $mail->Subject = 'Notice of successful order';

        // Tạo nội dung email bao gồm thông tin người nhận hàng, sản phẩm đã đặt và tổng đơn hàng
        $emailContent = "<p>Hello $fullname.</p>";
        $emailContent .= "<p>Thank you for your order with TechDiscovery. Here are the details of your order:</p>";

        // Lặp qua từng sản phẩm đã đặt
        foreach ($cartItems as $item) {
            $productName = $item['product_name'];
            $productColor = $item['product_color'];
            $productMemoryRam = $item['product_memory_ram'];
            $quantity = $item['quantity'];
            $product_price = $item['product_price'];
            $emailContent .= "<p>Product: $productName | $productColor | $productMemoryRam | Quantity: $quantity | $ $product_price.</p>";
        }

        $emailContent .= "<p>Phone: $phone</p>";
        $emailContent .= "<p>Shipping Address: $address, $ward, $district, $province.</p>";
        $emailContent .= "<p>Total Order: $ $total_order</p>";
        $emailContent .= "<p>Time Order: $currentDateTime</p>";
        $emailContent .= "<p>Payment method: $payment_method</p>";
        $emailContent .= "<p>Payment status: $status_payment</p>";
        $emailContent .= "<p>Order Status: $order_status</p>";

        // Thêm trạng thái đơn hàng vào nội dung email
        $mail->IsHTML(true);
        $mail->Body = $emailContent;

        // Gửi email
        $mail->send();
    } catch (Exception $e) {
        echo $e;
    }

    $_SESSION["new_order_notification"] = [
        "message" => "New order received",
        "time" => $currentDateTime,
        "url" => "order_list.php"
    ];

    $delete_query = "delete from tbl_cart where user_id = $user_id";
    $delete_result = $database->delete($delete_query);
    if ($delete_result) {
        $_SESSION["add_to_cart_result"] = "Your order has been successfully place";
        header("Location: ../myAccount_order.php");
        exit();
    }
}
