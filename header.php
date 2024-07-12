<?php
session_start();
include "admin/database.php";
$db = new Database();
$loginLink = '<li><a href="login.php">Login</a></li>';
if (isset($_SESSION["id"])) {
    $loginLink = '<li><a href="logout.php">Logout</a></li>';
}

if (isset($_SESSION["id"])) {
    $user_id = $_SESSION["id"];
    $query = "SELECT is_online FROM users WHERE id = $user_id";
    $result = $db->select($query);
    $userData = $result->fetch_assoc();
    
    if ($userData["is_online"] == 2) {
        session_destroy();
        header("Location: login.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TechDiscovery</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="image/logocr.png" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/responsive.css">
    <link rel="stylesheet" href="css/custom.css">
    <script src="https://kit.fontawesome.com/99cf1e4b98.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="main-top">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="text-slid-box">
                        <div id="offer-box" class="carouselTicker">
                            <ul class="offer-box">
                                <li>
                                    <i class="fab fa-opencart"></i> Off 10%! Shop Now
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 10% - 20% off on Smartphone
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 15% off Entire Purchase Promo code: offTD15
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 5/9 Free full day shipping
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 8%! Shop Now
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 5% - 8% off on Apple
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> 5% off Enter discount Code to buy: offTD5
                                </li>
                                <li>
                                    <i class="fab fa-opencart"></i> Off 30% Accessory! Shop Now
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="our-link">
                        <?php
                        if (isset($_SESSION["id"])) {
                            $user_id = $_SESSION["id"];
                            $is_online = 1;
                            $sql = "UPDATE users SET is_online = $is_online WHERE id = $user_id";
                            $result = $db->update($sql);
                            echo '<ul>';
                            echo '<li><a href="my-account.php">My Account</a></li>';
                            echo '<li><a href="https://www.google.com/maps/place/391A+%C4%90.+Nam+K%E1%BB%B3+Kh%E1%BB%9Fi+Ngh%C4%A9a,+Ph%C6%B0%E1%BB%9Dng+14,+Qu%E1%BA%ADn+3,+Th%C3%A0nh+ph%E1%BB%91+H%E1%BB%93+Ch%C3%AD+Minh+700000,+Vietnam/@10.7907758,106.6792676,17z/data=!3m1!4b1!4m6!3m5!1s0x317528d4a8afdb7b:0x2e46c4ada94947dd!8m2!3d10.7907758!4d106.6818425!16s%2Fg%2F11h89s2mz2?entry=ttu">Our location</a></li>';
                            echo '<li><a href="contact-us.php">Contact Us</a></li>';
                            echo '<li><a href="logout.php">Logout</a></li>';
                            echo '</ul>';
                        } else {
                            echo '<ul>';
                            echo '<li><a href="https://www.google.com/maps/place/391A+%C4%90.+Nam+K%E1%BB%B3+Kh%E1%BB%9Fi+Ngh%C4%A9a,+Ph%C6%B0%E1%BB%9Dng+14,+Qu%E1%BA%ADn+3,+Th%C3%A0nh+ph%E1%BB%91+H%E1%BB%93+Ch%C3%AD+Minh+700000,+Vietnam/@10.7907758,106.6792676,17z/data=!3m1!4b1!4m6!3m5!1s0x317528d4a8afdb7b:0x2e46c4ada94947dd!8m2!3d10.7907758!4d106.6818425!16s%2Fg%2F11h89s2mz2?entry=ttu">Our location</a></li>';
                            echo '<li><a href="contact-us.php">Contact Us</a></li>';
                            echo '<li><a href="login.php">Login</a></li>';
                            echo '</ul>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <script>
            window.addEventListener('beforeunload', function() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'logout.php', false);
                xhr.send();
            });
            var onlineStatusTimeout = null;
            var hangingStatusTimeout = null;
            var onlineStatusInterval = 30000; // 30000ms = 30 seconds
            var hangingStatusInterval = 15000; // 15000ms = 15 seconds
            var isHanging = false;

            function updateUserStatusToOnline() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'update_user_status.php?status=online', true);
                xhr.send();
                isHanging = false;
            }

            function updateUserStatusToHanging() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'update_user_status.php?status=hanging', true);
                xhr.send();
                isHanging = true;
            }

            function resetOnlineStatusTimeout() {
                clearTimeout(onlineStatusTimeout);
                clearTimeout(hangingStatusTimeout);

                if (!isHanging) {
                    onlineStatusTimeout = setTimeout(updateUserStatusToHanging, hangingStatusInterval);
                }
            }
            document.addEventListener('mousemove', function() {
                updateUserStatusToOnline();
                resetOnlineStatusTimeout();
            });
            var inactivityTimeout = null;

            function updateUserStatusToOffline() {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'update_user_status.php?status=offline', true);
                xhr.send();
            }

            function resetInactivityTimeout() {
                clearTimeout(inactivityTimeout);
                inactivityTimeout = setTimeout(updateUserStatusToOffline, 30000);
            }
            document.addEventListener('mousemove', resetInactivityTimeout);
            document.addEventListener('keydown', resetInactivityTimeout);
        </script>
</body>

</html>
