<?php

require '../TechDiscovery/admin/config.php';
session_start();
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'website_td';

// Tạo lại kết nối tới cơ sở dữ liệu
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check nếu kết nối thành công
if (!$conn) {
    die("Database connection error: " . mysqli_connect_error());
}
if (isset($_POST["submit"])) {
    $emailusername = $_POST["emailusername"];
    $password = $_POST["password"];
    $sql = "SELECT * FROM users WHERE username ='$emailusername' OR email ='$emailusername'";
    $result = mysqli_query($conn, $sql);
    if (isset($_POST["remember"])) {
        // Thiết lập cookie để lưu tên người dùng trong 30 ngày
        setcookie("remembered_username", $emailusername, time() + (30 * 24 * 60 * 60), "/");
    }
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row["password"])) {
            if ($row["is_online"] != 2) {
                $_SESSION["id"] = $row["id"];
                $user_id = $row["id"];
                $is_online = 1; // Trạng thái online
                $sql_update_online = "UPDATE users SET is_online = $is_online WHERE id = $user_id";
                mysqli_query($conn, $sql_update_online);
                if ($row["role"] == "admin") {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                echo "<script>alert('This account is banned. Please contact support for assistance.');</script>";
            }
        } else {
            echo "<script>alert('Wrong Password');</script>";
        }
    } else {
        echo "<script>alert('Username Not Registered');</script>";
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="styles.css">
    <style>
        .toggle-password-icon {
            position: absolute;
            top: 30px;
            right: 35px;
            transform: translateY(-50%);
            cursor: pointer;
        }
        .eye-icon {
            color: white;
        }
    </style>
    <style>
        .google-login-link {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <section>
        <div class="login-box">
            <form action="" method="post" autocomplete="off">
                <h2>Login</h2>
                <?php if (isset($banned_message)) : ?>
                    <div class="alert alert-danger"><?php echo $banned_message; ?></div>
                <?php endif; ?>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="mail"></ion-icon>
                    </span>
                    <input type="text" name="emailusername" id="emailusername" value="<?php echo isset($_COOKIE['remembered_username']) ? $_COOKIE['remembered_username'] : ''; ?>">

                    <label for="emailusername">Username</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="password" id="password">
                    <label for="password">Password</label>
                    <span id="togglePassword" class="toggle-password-icon">
                        <ion-icon class="eye-icon" name="eye"></ion-icon>
                    </span>
                </div>
                <div class="remember-forgot">
                    <!-- <label><input type="checkbox" name="remember">Remember me</label> -->
                    <a href="forgotpassword.php">Forgot Password?</a>
                </div>
                <button type="submit" name="submit" class="google-login-link">Login</button>
                <br> <br>
                <div class="register-link">
                    <p>Don't have an account you can<a href="register.php">Register now</a></p>
                </div>
            </form>
        </div>
    </section>
    <script>
        const passwordInput = document.getElementById("password");
        const togglePassword = document.getElementById("togglePassword");
        const eyeIcon = togglePassword.querySelector(".eye-icon");

        togglePassword.addEventListener("click", function() {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.setAttribute("name", "eye-off");
            } else {
                passwordInput.type = "password";
                eyeIcon.setAttribute("name", "eye");
            }
        });
    </script>

</body>

</html>
