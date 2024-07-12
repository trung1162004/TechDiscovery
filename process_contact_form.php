<?php
// Kiểm tra xem có yêu cầu POST từ biểu mẫu không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ biểu mẫu
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $message = $_POST["message"];
    
    // Địa chỉ email để nhận thông điệp
    $to = "techdiscoverys@gmail.com";
    
    // Tiêu đề email
    $subject = "Contact Form Submission from $fullname";
    
    // Nội dung email
    $email_content = "Name: $fullname\n";
    $email_content .= "Email: $email\n";
    $email_content .= "Message:\n$message\n";
    
    // Gửi email
    mail($to, $subject, $email_content);
    
    // Chuyển hướng người dùng trở lại trang liên hệ với thông báo gửi thành công
    header("Location: contact-us.php?success=true");
    exit();
} else {
    // Nếu không phải là yêu cầu POST, chuyển hướng người dùng trở lại trang liên hệ
    header("Location: contact-us.php");
    exit();
}
?>
