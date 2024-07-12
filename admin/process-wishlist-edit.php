<?php
include("database.php");
$database = new Database();

if (isset($_GET['id']) && isset($_POST['quantity'])) {
    $wishlist_id = $_GET['id'];
    $new_quantity = intval($_POST['quantity']); 
    
    // Truy vấn để lấy thông tin sản phẩm
    $get_product_query = "SELECT product_price, product_id FROM tbl_wishlist WHERE wishlist_id = $wishlist_id";
    $product = $database->select($get_product_query, [$wishlist_id])->fetch_assoc();
    
    $get_product_info_query = "SELECT product_quantity FROM tbl_product WHERE product_id = {$product['product_id']}";
    $product_info = $database->select($get_product_info_query, [$product['product_id']])->fetch_assoc();
    $product_quantity = $product_info['product_quantity'];
    
    if ($new_quantity > $product_quantity) {
        $get_old_data_query = "SELECT quantity, total FROM tbl_wishlist WHERE wishlist_id = $wishlist_id";
        $old_data = $database->select($get_old_data_query)->fetch_assoc();
    
        $response = array(
            "error" => true,
            "message" => "Quantity exceeds quantity in stock!",
            "old_quantity" => $old_data['quantity'], 
            "old_total" => $old_data['total']
        );
        echo json_encode($response);
        exit;
    }
    
    $new_total = $new_quantity * $product['product_price'];
    
    // Tiếp tục thực hiện cập nhật dữ liệu trong giỏ hàng
    $update_query = "UPDATE tbl_wishlist SET quantity = $new_quantity, total = $new_total WHERE wishlist_id = $wishlist_id";
    $result = $database->update($update_query);
    if ($result) {
        echo "Update successful.";
    } else {
        echo "Update failed.";
    }
}
?>
