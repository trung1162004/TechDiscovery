<?php
include "class/user_class.php";

if (isset($_GET["id"])) {
    $user_id = $_GET["id"];
    $user = new User();
    $result = $user->update_user_status($user_id, 0); // Set is_online to 1 (Online)

    if ($result) {
        // Unban successful, you can redirect to userlist.php or show a success message
        header("Location: userlist.php");
        exit;
    } else {
        // Unban failed, you can redirect to userlist.php or show an error message
        header("Location: userlist.php");
        exit;
    }
}
?>
