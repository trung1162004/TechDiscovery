<?php
// Kết nối đến cơ sở dữ liệu
include "database.php"; // Thay thế bằng tên tệp và cách kết nối đúng của bạn

// Kiểm tra xem người dùng đã đăng nhập hay chưa (đảm bảo họ đã đăng nhập)

session_start(); // Đảm bảo bạn đã bắt đầu phiên làm việc

if (isset($_POST['comment']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $commentText = $_POST['comment'];

    // Thực hiện câu truy vấn để lưu comment vào cơ sở dữ liệu
    $sql = "INSERT INTO comments (user_id, comment_text) VALUES ('$user_id', '$commentText')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Trả về thông tin comment đã được lưu
        echo "<div class='comment-container'>
                <div class='comment-header'>
                    <span class='comment-author'>User ID: $user_id</span>
                </div>
                <p class='comment-text'>$commentText</p>
            </div>";
    } else {
        echo "Error saving comment.";
    }
} else {
    echo "Invalid request.";
}
?>
