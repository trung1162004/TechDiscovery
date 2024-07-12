<?php
include "class/order_class.php";
$order = new order;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_GET['order_id'];
    $order_status = $_POST['order_status'];
    $get_order = $order -> update_order_list($order_id, $order_status);
}

?>