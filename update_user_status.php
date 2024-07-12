<?php
session_start();
include "admin/database.php"; 
if (isset($_SESSION["id"])) {
    $user_id = $_SESSION["id"];
    $db = new Database();
    if (isset($_GET["status"])) {
        $status = $_GET["status"];
        if ($status === "online") {
            $is_online = 1; 
        } elseif ($status === "offline") {
            $is_online = 0; 
        } elseif ($status === "hanging") {
            $is_online = 3; 
        } else {
            echo "Invalid status parameter.";
            exit;
        }
        $sql = "UPDATE users SET is_online = $is_online WHERE id = $user_id";
        $result = $db->update($sql); 
        if ($result) {
            echo "User status updated successfully.";
        } else {
            echo "Error updating user status.";
        }
    } else {
        echo "Status parameter not found.";
    }
} else {
    echo "User session not found.";
}
