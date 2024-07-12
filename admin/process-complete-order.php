<?php
session_start();
include "database.php";
$db = new Database;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $order_id = $_GET['id'];
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $received_date = date("Y-m-d H:i:s");
    $query = "UPDATE tbl_order SET order_status = 'delivered', status_payment = 'Order has been paid', delivery_time = '$received_date' WHERE order_id = $order_id";
    $result = $db->update($query);

    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $currentDateTime = date('Y-m-d H:i:s');
    $_SESSION["complete_order_notification"] = [
        "message" => "Order complete",
        "time" => $currentDateTime,
        "url" => "order_completed.php"
    ];

    if($result){
        header("Location: ../myAccount_order_complete.php");
    }
}
?>