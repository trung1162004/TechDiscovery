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

if (isset($_POST['confirm_code'])) {
    $enteredCode = $_POST['verification_code'];
    $verificationCode = $_SESSION['verification_code'];

    if ($enteredCode == $verificationCode) {
        $_SESSION['verification_code_verified'] = true;

        // Fetch user's email using the saved user_email session
        $userEmail = $_SESSION['user_email'];
        $getUserQuery = "SELECT * FROM users WHERE email = '$userEmail'";
        $getUserResult = mysqli_query($conn, $getUserQuery);

        if (mysqli_num_rows($getUserResult) > 0) {
            $userRow = mysqli_fetch_assoc($getUserResult);
            $_SESSION['user_id'] = $userRow['id']; // Store user's ID in session

            header("Location: resetpassword.php");
            exit();
        } else {
            $errors['database'] = "User not found in the database.";
        }
    } else {
        $errors['code'] = "Invalid verification code.";
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
            <form action="confirmcode.php" method="post">
                <h2>Code</h2>
                <div class="input-box">
                    <span class="icon">
                        <ion-icon name="key"></ion-icon>
                    </span>
                    <input type="text" name="verification_code" id="verification_code" required>
                    <label for="verification_code">Verification Code</label>
                </div>
                <div class="error-message">
                    <?php
                    if (!empty($errors['code'])) {
                        echo $errors['code'];
                    }
                    if (!empty($errors['database'])) {
                        echo $errors['database'];
                    }
                    ?>
                </div>
                <button type="submit" name="confirm_code">Confirm Code</button>
            </form>
        </div>
    </section>
</body>
</html>
