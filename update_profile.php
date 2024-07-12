<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "website_td";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    if (isset($_POST['email'])) {
        $newEmail = $_POST['email'];
        $sql = "UPDATE users SET email = '$newEmail' WHERE id = $id";
        $conn->query($sql);
    }

    if (isset($_POST['address'])) {
        $newAddress = $_POST['address'];
        $sql = "UPDATE users SET address = '$newAddress' WHERE id = $id";
        $conn->query($sql);
    }

    if (isset($_POST['phone'])) {
        $newPhone = $_POST['phone'];
        $sql = "UPDATE users SET phone = '$newPhone' WHERE id = $id";
        $conn->query($sql);
    }

    if (isset($_POST['password'])) {
        $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password = '$newPassword' WHERE id = $id";
        $conn->query($sql);
    }
}

$conn->close();
header("Location: profile.php"); // Điều hướng trở lại trang hồ sơ
?>
