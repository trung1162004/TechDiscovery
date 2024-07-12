<?php
define("APPPATH", "./");
require APPPATH . "PHPMailer.php";
require APPPATH . "Exception.php";
require APPPATH . "SMTP.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

session_start();

$errors = array();
    
if (isset($_POST['resend_verification'])) {
    // Tạo mã xác minh mới
  
    $userEmail = $_SESSION['user_email'];

    $newVerificationCode = rand(100000, 999999);
    $_SESSION['verification_code'] = $newVerificationCode;
   
    $mail = new PHPMailer();

    try {
        // Cấu hình thông tin email
        $mail->isSMTP();
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = "tls";
        $mail->SMTPAuth = true;
        $mail->Username = 'techdiscoverys@gmail.com';
        $mail->Password = 'gzgkpyvjmuoovenp';

        $mail->setFrom('techdiscoverys@gmail.com', '');
        $mail->addAddress($userEmail); // Địa chỉ email của người dùng

        // Tạo nội dung email
        $mail->Subject = 'New Verification Code';
        $mail->Body = 'Your new verification code: ' . $newVerificationCode;

        // Gửi email
        if ($mail->send()) {
            $jsAlert = "Mã xác minh mới đã được gửi tới địa chỉ email của bạn.";
        } else {
            $jsAlert = "Lỗi khi gửi email. Vui lòng thử lại sau.";
        }
    } catch (Exception $e) {
        $jsAlert = "Lỗi khi gửi email. Vui lòng thử lại sau.";
    }
}

if (isset($_POST['verification_code'])) {
    $verificationCode = $_POST['verification_code'];

   
    if (empty($verificationCode) || !ctype_digit($verificationCode) || strlen($verificationCode) !== 6) {
        $errors['verification'] = "Invalid verification code. Please enter a 6-digit code.";
    } elseif ($verificationCode == $_SESSION['verification_code']) {
        echo "Verification successful! You can now complete the registration process.";
        header("Location: ../login.php");
        exit;
    } else {
        // Mã xác nhận không chính xác
        $errors['verification'] = "Incorrect verification code. Please try again.";
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
    var jsAlert = "<?php echo addslashes($jsAlert); ?>"; // Lấy giá trị thông báo từ biến PHP
    if (jsAlert) {
        alert(jsAlert); // Hiển thị thông báo
    }

    // Hiển thị thông báo lỗi nếu có
    var errorMessage = document.getElementById("error-message");
    if (errorMessage.innerText !== "") {
        errorMessage.style.display = "block"; // Hiển thị phần tử
    }
</script>

    <style>
       .error-message {
            pointer-events: none; /* Vô hiệu hóa tương tác chuột */
        }
        .error-message {
            font-size: 14px;
            color: white;
            position: absolute;
            top: -120px;
            right: 0px;
            padding-top: -200px;
        }
    </style>
</head>
<body>
<section>
        <div class="login-box">
            <form action="" method="post" autocomplete="off">
                <h2>Verification Code</h2>
               
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="key"></ion-icon>
                    </span>
                    <input type="text" name="verification_code" id="verification_code">
                    <label for="verification_code">Verification Code</label>
                <div class="error-message" id="error-message">
                    <?php if(isset($errors['verification'])) echo $errors['verification']; ?>
                </div>
                </div>
                <button type="submit">Verify</button>
                <br><br>
                <button type="submit" name="resend_verification">Resend Verification Code</button>
            </form>
        </div>
    </section>  
    <script>
        // Lấy thẻ input
        var verificationCodeInput = document.getElementById("verification_code");
        
        // Sự kiện focus: ẩn thông báo lỗi khi người dùng bắt đầu nhập liệu
        verificationCodeInput.addEventListener("focus", function() {
            document.getElementById("error-message").style.display = "none";
        });
    </script>

    
</body>
</html>
