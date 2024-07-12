<?php
session_start();
include("database.php");

if (isset($_SESSION["id"])) {
    $user_id = $_SESSION['id'];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST['product_id'];
    $wishlist_id = $_POST['wishlist_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_img = $_POST['product_img'];
    $product_color = $_POST['product_color'];
    $product_memory_ram = $_POST['product_memory_ram'];
    $quantity = $_POST['quantity'];
    $total = $_POST['total'];
    $db = new Database();

    $product_query = "select product_quantity from tbl_product where product_id = $product_id";
    $product_result = $db->select($product_query);
    if ($row = $product_result->fetch_assoc()) {
        $product_quantity = $row["product_quantity"];
        if ($quantity > $product_quantity) {
            $_SESSION["add_to_cart_result"] = "Quantity exceeds quantity in stock!";
            header("Location: ../wishlist.php");
            exit();
        } else {
            $query = "INSERT INTO tbl_cart (user_id, product_name, product_price, product_color, product_memory_ram, product_img, quantity, total, product_id) 
            VALUES ('$user_id', '$product_name', '$product_price', '$product_color', '$product_memory_ram', '$product_img', '$quantity', '$total', '$product_id') ORDER BY cart_id DESC";
            $result = $db->insert($query);
            $query = "delete from tbl_wishlist where wishlist_id = $wishlist_id";
            $result = $db->delete($query);
            if ($result) {
                $_SESSION["add_to_cart_result"] = "Add to Cart success!";
                header("Location: ../wishlist.php");
                exit();
            }
        }
    }
}
