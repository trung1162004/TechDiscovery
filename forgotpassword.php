<?php
session_start();
require '../TechDiscovery/admin/config.php';
include "../TechDiscovery/mail/PHPMailer.php";
include "../TechDiscovery/mail/Exception.php";
include "../TechDiscovery/mail/OAuth.php";
include "../TechDiscovery/mail/POP3.php";
include "../TechDiscovery/mail/SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;


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

if (isset($_POST['send_code'])) {
    $email = $_POST['email'];

    // Check if email exists in the database
    $emailCheckQuery = "SELECT * FROM users WHERE email = '$email'";
    $emailCheckResult = mysqli_query($conn, $emailCheckQuery);
    if (mysqli_num_rows($emailCheckResult) > 0) {
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
            $mail->Username = 'techdiscoverys@gmail.com'; // Update with your Gmail email
            $mail->Password = 'gzgkpyvjmuoovenp'; // Update with your Gmail password or app-specific password
            $mail->setFrom('techdiscoverys@gmail.com'); // Update with your Gmail email
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Verification Code';
            $mail->Body = 'Your verification code: ' . $verificationCode;

            $mail->send();
            $_SESSION['user_email'] = $email;
            header("Location: confirmcode.php");
            exit();
        } catch (Exception $e) {
            $errors['email'] = "Email could not be sent. Please try again later.";
        }
    } else {
        $errors['email'] = "Email not found in our records.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Forgot Password</title>
</head>
<body>
    <section>
        
        <div class="login-box">
         
            <form action="forgotpassword.php" method="post">
            <h2>Forgot Password</h2>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="email" name="email" id="email" required>
                    <label for="email">Email</label>
                </div>
                <div class="error-message">
                    <?php
                    if (!empty($errors['email'])) {
                        echo $errors['email'];
                    }
                    ?>
                </div>
                <button type="submit" name="send_code">Send Verification Code</button>
            </form>
        </div>
    </section>
</body>
</html>
