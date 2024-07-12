<?php
include("database.php");
$database = new Database();

if (isset($_GET['id'])) {
    $cart_id = $_GET['id'];
    $query = "DELETE FROM tbl_cart WHERE cart_id = $cart_id";
    $result = $database->delete($query);

    if ($result) {
        header("Location: ../cart.php");
    }else{
        echo 'error';
    }
}
?>