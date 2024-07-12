<?php
require '../TechDiscovery/admin/config.php';
include "../TechDiscovery/mail/PHPMailer.php";
include "../TechDiscovery/mail/Exception.php";
include "../TechDiscovery/mail/OAuth.php";
include "../TechDiscovery/mail/POP3.php";
include "../TechDiscovery/mail/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

session_start();
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "website_td";

// Create a new mysqli connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = array();

if (isset($_POST["register"])) {
    $email = $_POST['email'];
    $_SESSION['user_email'] = $email;
    $emailCheckQuery = "SELECT * FROM users WHERE email = '$email'";
    $emailCheckResult = mysqli_query($conn, $emailCheckQuery);
    if (mysqli_num_rows($emailCheckResult) > 0) {
        $errors['email'] = "Địa chỉ email đã tồn tại";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Định dạng email không hợp lệ";
    } elseif (!preg_match('/@gmail\.com$/', $email)) {
        $errors['email'] = "Email phải là địa chỉ Gmail (@gmail.com)";
    }

    $username = $_POST["username"];
    // Kiểm tra xem tên người dùng đã tồn tại trong cơ sở dữ liệu hay chưa
    $usernameCheckQuery = "SELECT * FROM users WHERE username = '$username'";
    $usernameCheckResult = mysqli_query($conn, $usernameCheckQuery);
    if (mysqli_num_rows($usernameCheckResult) > 0) {
        $errors['username'] = "Tên người dùng đã tồn tại";
    } elseif (empty($username)) {
        $errors['username'] = "Tên người dùng không được để trống";
    }
        $password = $_POST["password"];
        if (empty($password)) {
            $errors['password'] = "Mật khẩu không được để trống";
        }
    
        $confirmpassword = $_POST["confirmpassword"];
        if (empty($confirmpassword)) {
            $errors['confirmpassword'] = "Xác nhận mật khẩu không được để trống";
        } elseif ($password !== $confirmpassword) {
            $errors['confirmpassword'] = "Mật khẩu xác nhận không khớp";
        }

        $fullname = $_POST["fullname"];
        if (empty($fullname)) {
            $errors['fullname'] = "Fullname  là bắt buộc";
        }

        $address = $_POST["address"];
        if (empty($address)) {
            $errors['address'] = "Địa chỉ là bắt buộc";
        }
        
        $phone = $_POST["phone"];
        if (empty($phone)) {
            $errors['phone'] = "Số điện thoại là bắt buộc";
        } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
            $errors['phone'] = "Số điện thoại phải gồm 10 chữ số";
        }

        if (count($errors) === 0) {
            $verificationCode = rand(100000, 999999);
            $_SESSION['verification_code'] = $verificationCode;

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = "tls";
                $mail->SMTPAuth = true;
                $mail->Username = 'techdiscoverys@gmail.com';
                $mail->Password = 'gzgkpyvjmuoovenp'; // sử dụng mật khẩu ứng dụng
                $mail->FromName = "TechDiscovery Registration";

                $mail->setFrom('techdiscoverys@gmail.com');
                if (!$mail->addAddress($email)) {
                    $errors['email'] = "Invalid address: " . $mail->ErrorInfo;
                } else {
                    $mail->Subject = 'Email Validation';
                    $mail->Body = 'This is a validation email from TechDiscovery Registration. Verification code: ' . $verificationCode;
                }

                if (!$mail->send()) {
                    $errors['email'] = "Email không tồn tại hoặc không thể gửi được ";
                } else {    
                    $currentTime = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
                    $query = "INSERT INTO users (email, username, password, fullname, address, phone, registration_time, verification_code) 
                    VALUES ('$email', '$username', '$hashedPassword', '$fullname', '$address', '$phone', '$currentTime', '$verificationCode')";

                    if (mysqli_query($conn, $query)) {
                        header("Location: ../TechDiscovery/mail/verify_code.php");
                        exit();
                    } else {
                        $errors['database'] = "Lỗi khi lưu dữ liệu vào cơ sở dữ liệu. Vui lòng thử lại sau.";
                    }
                }
            } catch (Exception $e) {
                $errors['email'] = "Email không tồn tại hoặc không thể gửi được";
            }
        }
    }

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
         .error-message {
            pointer-events: none; /* Vô hiệu hóa tương tác chuột */
        }
        .error-message {
            font-size: 14px;
            color: red;
            position: absolute;
            top: 0;
            right: -230px;
            padding-top: 20px;
            transition: right 0.3s ease;
        }
    </style>
    <title>REGISTER</title>
  
</head>

<body>
    <section>
        <div class="">
            <form action="register.php" method="post" autocomplete="off">
                <h2>Register</h2>
                <div class="input-box input-container">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="text" name="email" id="email" >
                    <label for="email">Email</label>
                    <div class="error-message">
                        <?php
                        if (!empty($errors['email'])) {
                            echo $errors['email'];
                        }
                        
                        ?>
                        
                    </div>
                </div>
                <div class="input-box input-container">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" name="username" id="username" >
                    <label for="username">Username</label>
                    <div class="error-message">
                        <?php
                        if (!empty($errors['username'])) {
                            echo $errors['username'];
                        }
                        ?>
                    </div>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" id="password" >
                    <label class='password'>Password</label>
                    <div class="error-message">
                        <?php
                        if (!empty($errors['password'])) {
                            echo $errors['password'];
                        }
                        ?>
                    </div>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="confirmpassword" id="confirmpassword" >
                    <label class='confirmpassword'>Confirm Password</label>
                    <div class="error-message">
                        <?php
                        if (!empty($errors['confirmpassword'])) {
                            echo $errors['confirmpassword'];
                        }
                        ?>
                    </div>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="person"></ion-icon>
                    </span>
                    <input type="text" name="fullname" id="fullname" >
                    <label class='fullname'>Full Name</label>
                    <div class="error-message">
                        <?php
                        if (!empty($errors['fullname'])) {
                            echo $errors['fullname'];
                        }
                        ?>
                    </div>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="map"></ion-icon>
                    </span>
                    <input type="text" name="address" id="address" >
                    <label class='address'>Address</label>

                    <div class="error-message">
                        <?php
                        if (!empty($errors['address'])) {
                            echo $errors['address'];
                        }
                        ?>
                    </div>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="call"></ion-icon>
                    </span>
                    <input type="number" name="phone" id="phone" >
                    <label class='phone'>Phone</label>
                    <div class="error-message">
                        <?php
                        if (!empty($errors['phone'])) {
                            echo $errors['phone'];
                        }
                        ?>
                    </div>
                </div>
               
                <button type="submit" name="register">Register Now</button>
            </form>
        </div>
    </section>


</body>

</html>
