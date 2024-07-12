<?php
session_start();
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "website_td";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = array();

if (isset($_POST['reset_password'])) {
    if ($_SESSION['verification_code_verified']) {
        $newPassword = $_POST['new_password'];
        $confirmNewPassword = $_POST['confirm_new_password'];

        if ($newPassword === $confirmNewPassword) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $userId = $_SESSION['user_id']; // You need to set the user's ID in the session when confirming the code
            $updatePasswordQuery = "UPDATE users SET password = '$hashedPassword' WHERE id = '$userId'";

            if (mysqli_query($conn, $updatePasswordQuery)) {
                // Clear the verification session data
                unset($_SESSION['user_id']);
                unset($_SESSION['verification_code_verified']);
                unset($_SESSION['verification_code']);

                header("Location: login.php"); // Redirect to login page
                exit();
            } else {
                $errors['database'] = "Error updating password in the database.";
            }
        } else {
            $errors['password'] = "Passwords do not match.";
        }
    } else {
        header("Location: confirmcode.php"); // Redirect to confirm code page
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="styles.css">

</head>

<body>
    <section>
        <div class="login-box">
            <form action="resetpassword.php" method="post">
                <h2>Resest Password</h2>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="new_password" id="new_password" required>
                    <label for="new_password">New Password</label>
                </div>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="lock-closed"></ion-icon>
                    </span>
                    <input type="password" name="confirm_new_password" id="confirm_new_password" required>
                    <label for="confirm_new_password">Confirm New Password</label>
                </div>
                <div class="error-message">
                    <?php
                    if (!empty($errors['password'])) {
                        echo $errors['password'];
                    }
                    if (!empty($errors['database'])) {
                        echo $errors['database'];
                    }
                    ?>
                </div>
                <button type="submit" name="reset_password">Reset Password</button>
            </form>
        </div>
    </section>
</body>

</html>
