<?php
    include "class/cartegory_main_class.php";
    $cartegory_main = new cartegory_main;

    if(!isset($_GET['cartegory_main_id']) || $_GET['cartegory_main_id'] == NULL){
        echo "<script>window.location = 'cartegory_mainlist.php'</script>";
     }else{
         $cartegory_main_id = $_GET['cartegory_main_id'];
     }
 
     $get_cartegory_main = $cartegory_main -> delete_cartegory_main($cartegory_main_id);
?>