<?php
include "class/user_class.php";

if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
    
    $user = new User();
    
    // Update user status to Banned (is_online = 2)
    $update_result = $user->update_user_status($user_id, 2);

    if ($update_result) {
        // Send email notification to the user
        $user_info = $user->get_user_by_id($user_id);
        $user_data = $user_info->fetch_assoc();
        $user_email = $user_data['email'];
        
        // Send email using appropriate methods, e.g., PHPMailer
        // Include code to send email here
        
        header("Location: userlist.php"); // Redirect back to userlist.php
        exit;
    } else {
        echo "Error updating user status.";
    }
} else {
    echo "User ID not provided.";
}
?>
