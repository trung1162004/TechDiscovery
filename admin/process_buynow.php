<?php
session_start();
include("database.php");

if (isset($_SESSION["id"])) {
    $user_id = $_SESSION['id'];
}else{
    echo '<script>
            alert("Please login to add products to cart.");
            window.location.href = "../login.php";
        </script>';
        exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_img = $_POST['product_img'];
    $product_color = $_POST['product_color'];
    $product_memory_ram = $_POST['product_memory_ram'];
    $quantity = $_POST['product_quantity'];
    $total = $product_price * $quantity;
    $db = new Database();
    $product_query = "select product_quantity from tbl_product where product_id = $product_id";
    $product_result = $db->select($product_query);
    if($row = $product_result->fetch_assoc()){
        $product_quantity = $row["product_quantity"];
        if($quantity > $product_quantity){
            $_SESSION["add_to_cart_result"] = "Quantity exceeds quantity in stock!";
            header("Location: ../product-detail.php?id=$product_id");
            exit();
        }else{
            $query = "INSERT INTO tbl_cart (user_id, product_name, product_price, product_color, product_memory_ram, product_img, quantity, total, product_id) 
              VALUES ('$user_id', '$product_name', '$product_price', '$product_color', '$product_memory_ram', '$product_img', '$quantity', '$total', '$product_id') ORDER BY cart_id DESC";
            $result = $db->insert($query);
            header("Location: ../cart.php");
        }
    }
}
