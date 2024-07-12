<?php
include "database.php";
$database = new Database;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cartId']) && isset($_POST['quantity'])) {
    $cart_id = $_POST['cartId'];
    $quantity = $_POST['quantity'];

    $get_product_query = "SELECT product_id FROM tbl_cart WHERE cart_id = $cart_id";
    $product = $database->select($get_product_query, [$cart_id])->fetch_assoc();
    
    $get_product_info_query = "SELECT product_quantity FROM tbl_product WHERE product_id = {$product['product_id']}";
    $product_info = $database->select($get_product_info_query, [$product['product_id']])->fetch_assoc();
    $product_quantity = $product_info['product_quantity'];
    
    if ($quantity <= $product_quantity) {
        $response = array('status' => 'success');
    }else {
        $response = array('status' => 'error', 'message' => 'Quantity is not enough.');
    }

    echo json_encode($response);
} else {
    // Xử lý trường hợp yêu cầu không hợp lệ
    http_response_code(400);
    echo json_encode(array('status' => 'error', 'message' => 'Yêu cầu không hợp lệ.'));
}
