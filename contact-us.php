<?php
include "header.php";
include "navbar.php";
if (!isset($_SESSION['id'])) {
    echo "<p>You need to <big><b><a href='login.php'>Login</a></b></big> to leave a message.</p>";
} else {
    $user_id = $_SESSION['id'];
    // Truy vấn dữ liệu từ bảng users
    $sql = "SELECT fullname, email FROM users WHERE id = $user_id";
    $result = $db->select($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $fullname = $row['fullname'];
        $email = $row['email'];
    }

}
?>

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Contact Us</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active"> Contact Us </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Contact Us  -->
<div class="contact-box-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-sm-12">
                <div class="contact-info-left">
                    <h2>CONTACT INFO</h2>
                    <p>Thank you for choosing TechDiscovery as your preferred destination for all your technology needs. We value your business and are committed to providing exceptional customer service. If you have any questions, concerns, or need assistance, we are here to help. </p>
                    <ul>
                        <li>
                            <p><i class="fas fa-map-marker-alt"></i>Address:<a href="">391a Nam Ky Khoi Nghia Street,<br>Ward 14, District 3, Ho Chi Minh City,<br> VietNam </a></p>
                        </li>
                        <li>
                            <p><i class="fas fa-phone-square"></i>Phone: <a href="tel:+1-888705770">+84123.456.789</a></p>
                        </li>
                        <li>
                            <p><i class="fas fa-envelope"></i>Email: <a href="mailto:techdiscoverys@gmail.com">TechDiscoverys@gmail.com</a></p>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 col-sm-12">
                <div class="contact-form-right">
                    <h2>GET IN TOUCH</h2>
                    <p>Hi, We are happy to connect with you at our sales site! We are always ready to listen and assist you in all your shopping needs. If you have any questions, suggestions or need advice, don't hesitate to contact us. Our customer care team will be available to answer all your questions and ensure that you have the best online shopping experience.
                        <br><br>
                        Best regards,<br>
                        Customer support team.</p>
                        <?php
if (!isset($_SESSION['id'])) {
    echo "<p>You need to <big><b><a href='login.php'>Login</a></b></big> to leave a message.</p>";
} else {
?>
                        <form id="contactForm" action="process_contact_form.php" method="POST">
    <div class="row">
        <div class="col-md-12">
            <!-- Hiển thị thông tin người dùng -->
            <div class="form-group">
                <input type="text" class="form-control" id="fullname" name="fullname" value="<?php echo isset($fullname) ? $fullname : ''; ?>" >
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <!-- Hiển thị thông tin người dùng -->
                <input type="email" id="email" class="form-control" name="email" value="<?php echo isset($email) ? $email : ''; ?>" >
            </div>
        </div>
        <!-- ... Phần khác của biểu mẫu ... -->
        <div class="col-md-12">
            <div class="form-group">
                <textarea class="form-control" name="message" id="message" placeholder="Your Message" rows="4" data-error="Write your message" required></textarea>
                <div class="help-block with-errors"></div>
            </div>
            <div class="submit-button text-center">
                <button class="btn hvr-hover" id="submit" type="submit">Send Message</button>
                <div id="msgSubmit" class="h3 text-center hidden"></div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</form>
<?php
}
?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Cart -->

<?php
include "footer.php";
?>