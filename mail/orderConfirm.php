<?php
define("APPPATH", "./");

include APPPATH . "PHPMailer.php";
include APPPATH . "Exception.php";
include APPPATH . "OAuth.php";
include APPPATH . "POP3.php";
include APPPATH . "SMTP.php";
 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if(isset($_POST['submit'])) {
  //#1
  $name = $_POST["name"];
  $email = $_POST["email"];
  $phone = $_POST["phone"];
  $subject = $_POST["subject"];
  $message = $_POST["message"];

  //#2
  $mail = new PHPMailer;
  $mail->isSMTP();
  //Enable SMTP debugging
  // SMTP::DEBUG_OFF = off (for production use)
  // SMTP::DEBUG_CLIENT = client messages
  // SMTP::DEBUG_SERVER = client and server messages
  $mail->SMTPDebug = SMTP::DEBUG_OFF;
  $mail->Host = 'smtp.gmail.com';
  $mail->Port = 587;
  //Set the encryption mechanism to use - STARTTLS or SMTPS
  $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
  $mail->SMTPAuth = true;
  //Username to use for SMTP authentication - use full email address for gmail
  $mail->Username = 'minhtri120604@gmail.com';
  $mail->Password = 'qzdrwwpvstxcpmgf'; // sử dụng mật khẩu ứng dụng
  $mail->FromName = $subject;

  //#3
  $mail->setFrom("minhtri120604@gmail.com");
  $mail->addAddress("minhtri120604.1@gmail.com");
  $mail->Subject = "You have ".$subject." From: ".$email;
  $mail->msgHTML("Name: ".$name."<br> Phone: ".$phone." <br> Email: ".$email."<br> Main content: ".$message);

  //#4
  if (!$mail->send()) {
    $error = "Lỗi: " . $mail->ErrorInfo;
    session_start();
    $_SESSION['result'] = 'fail';
    $_SESSION['message'] = $error;
    header("Location: ../contact.php");
    exit();
  } else {
    session_start();
    $_SESSION['result'] = 'success';
    header("Location: ../contact.php");
    exit();
  }
}

?>
