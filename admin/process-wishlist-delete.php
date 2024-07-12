<?php
include("database.php");
$database = new Database();

if (isset($_GET['id'])) {
    $wishlist_id = $_GET['id'];
    $query = "DELETE FROM tbl_wishlist WHERE wishlist_id = $wishlist_id";
    $result = $database->delete($query);

    if ($result) {
        header("Location: ../wishlist.php");
    }else{
        echo 'error';
    }
}
?>