<?php
include "database.php";
session_start();

if(isset($_POST['code']) && isset($_POST['intoMoney'])){
    $user_id = $_SESSION['id'];
    $discountCode = $_POST['code'];
    $intoMoney = floatval($_POST['intoMoney']);
    
    $db = new Database;
    
    // Check if the discount code exists in the discount table
    $query = "SELECT discount_code FROM discount WHERE discount_code = '$discountCode'";
    $result = $db->select($query);
    
    if($result && $result->num_rows > 0) {
        // Discount code exists, now check if the user has already used it
        $checkQuery = "SELECT * FROM user_discounts WHERE user_id = $user_id AND discount_code = '$discountCode'";
        $checkResult = $db->select($checkQuery);
        
        if($checkResult && $checkResult->num_rows > 0){
            $_SESSION['discount_applied'] = false; 
            echo json_encode(array("valid" => false, "message" => "Discount code already used by this user."));
        }else{
            $_SESSION['discount_applied'] = true;  
            $discountedValue = $intoMoney * 0.9;
            echo json_encode(array("valid" => true, "discountedValue" => $discountedValue));   
        }
    } else {
        $_SESSION['discount_applied'] = false;  
        echo json_encode(array("valid" => false, "message" => "Invalid discount code."));
    }
}
?>