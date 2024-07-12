<?php
    include "class/memory_ram_class.php";
    $memory_ram = new memory_ram;

    if(!isset($_GET['memory_ram_id']) || $_GET['memory_ram_id'] == NULL){
        echo "<script>window.location = 'memory_ramlist.php'</script>";
     }else{
         $memory_ram_id = $_GET['memory_ram_id'];
     }
 
     $get_memory_ram = $memory_ram -> delete_memory_ram($memory_ram_id);
?>