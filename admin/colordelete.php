<?php
    include "class/color_class.php";
    $color = new color;

    if(!isset($_GET['color_id']) || $_GET['color_id'] == NULL){
        echo "<script>window.location = 'colorlist.php'</script>";
     }else{
         $color_id = $_GET['color_id'];
     }
 
     $get_color = $color -> delete_color($color_id);
?>