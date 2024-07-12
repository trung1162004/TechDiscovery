<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "website_td";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxFileSize = 5 * 1024 * 1024;

        if (in_array($_FILES['avatar']['type'], $allowedTypes) && $_FILES['avatar']['size'] <= $maxFileSize) {
            $newAvatarName = uniqid() . "_" . $_FILES['avatar']['name'];
            $targetDir = "uploads/";
            $targetFile = $targetDir . $newAvatarName;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $targetFile)) {
                list($width, $height) = getimagesize($targetFile);
                $newWidth = 150;
                $newHeight = 150;
                $imageResized = imagecreatetruecolor($newWidth, $newHeight);

                if ($_FILES['avatar']['type'] === 'image/jpeg' || $_FILES['avatar']['type'] === 'image/jpg') {
                    $image = imagecreatefromjpeg($targetFile);
                } else if ($_FILES['avatar']['type'] === 'image/png') {
                    $image = imagecreatefrompng($targetFile);
                }

                imagecopyresampled($imageResized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagejpeg($imageResized, $targetFile, 80);

                imagedestroy($image);
                imagedestroy($imageResized);

                $sql = "UPDATE users SET avatar = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $newAvatarName, $id);

                if ($stmt->execute()) {
                    $stmt->close();
                    $conn->close();
                    header("Location: profile.php");
                    exit;
                } else {
                    echo "Lỗi khi cập nhật: " . $conn->error;
                }
            } else {
                echo "Lỗi khi tải lên tệp.";
            }
        } else {
            echo "Loại tệp không hợp lệ hoặc kích thước vượt quá giới hạn.";
        }
    } else {
        echo "Bạn chưa thay đổi gì.";
    }
}
?>
