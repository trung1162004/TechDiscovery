<?php
include "database.php"; // Thay thế bằng tên tệp và cách kết nối đúng của bạn

// Kiểm tra xem người dùng đã đăng nhập hay chưa ở đây (đảm bảo họ đã đăng nhập)

session_start(); // Đảm bảo bạn đã bắt đầu phiên làm việc

if (isset($_POST['blog_id']) && isset($_SESSION['user_id'])) {
    $blog_id = $_POST['blog_id'];
    $user_id = $_SESSION['user_id'];

    // Kiểm tra xem người dùng đã downvote bài viết này chưa (tránh downvote nhiều lần)
    $check_sql = "SELECT * FROM tbl_blog_votes WHERE blog_id = $blog_id AND user_id = $user_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) === 0) {
        // Tìm bài viết theo blog_id trong cơ sở dữ liệu
        $sql = "SELECT downvotes FROM tbl_blog WHERE blog_id = $blog_id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $current_downvotes = $row['downvotes'];

            // Tăng số lượng downvote
            $new_downvotes = $current_downvotes + 1;

            // Cập nhật số lượng downvote mới vào CSDL
            $update_sql = "UPDATE tbl_blog SET downvotes = $new_downvotes WHERE blog_id = $blog_id";
            $update_result = mysqli_query($conn, $update_sql);

            if ($update_result) {
                // Đánh dấu người dùng đã downvote bài viết này để tránh downvote nhiều lần
                $mark_sql = "INSERT INTO tbl_blog_votes (blog_id, user_id, vote_type) VALUES ($blog_id, $user_id, 'downvote')";
                $mark_result = mysqli_query($conn, $mark_sql);

                if ($mark_result) {
                    // Trả về số lượng downvote mới cho AJAX
                    echo $new_downvotes;
                } else {
                    echo "Error marking user's downvote in the database.";
                }
            } else {
                echo "Error updating downvotes in the database.";
            }
        } else {
            echo "Error fetching downvotes from the database.";
        }
    } else {
        echo "User has already downvoted this post.";
    }
} else {
    echo "Invalid request.";
}
?>
