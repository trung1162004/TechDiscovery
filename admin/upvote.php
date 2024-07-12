<?php
include "database.php";

// Kiểm tra xem người dùng đã đăng nhập hay chưa ở đây (đảm bảo họ đã đăng nhập)

session_start(); // Đảm bảo bạn đã bắt đầu phiên làm việc

if (isset($_POST['blog_id']) && isset($_SESSION['user_id'])) {
    $blog_id = $_POST['blog_id'];
    $user_id = $_SESSION['user_id'];

    // Kiểm tra xem người dùng đã upvote bài viết này chưa (tránh upvote nhiều lần)
    $check_sql = "SELECT * FROM tbl_blog_votes WHERE blog_id = $blog_id AND user_id = $user_id";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) === 0) {
        // Tìm bài viết theo blog_id trong cơ sở dữ liệu
        $sql = "SELECT upvotes FROM tbl_blog WHERE blog_id = $blog_id";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $current_upvotes = $row['upvotes'];

            // Tăng số lượng upvote
            $new_upvotes = $current_upvotes + 1;

            // Cập nhật số lượng upvote mới vào CSDL
            $update_sql = "UPDATE tbl_blog SET upvotes = $new_upvotes WHERE blog_id = $blog_id";
            $update_result = mysqli_query($conn, $update_sql);

            if ($update_result) {
                // Đánh dấu người dùng đã upvote bài viết này để tránh upvote nhiều lần
                $mark_sql = "INSERT INTO tbl_blog_votes (blog_id, user_id, vote_type) VALUES ($blog_id, $user_id, 'upvote')";
                $mark_result = mysqli_query($conn, $mark_sql);

                if ($mark_result) {
                    // Trả về số lượng upvote mới cho AJAX
                    echo $new_upvotes;
                } else {
                    echo "Error marking user's upvote in the database.";
                }
            } else {
                echo "Error updating upvotes in the database.";
            }
        } else {
            echo "Error fetching upvotes from the database.";
        }
    } else {
        echo "User has already upvoted this post.";
    }
} else {
    echo "Invalid request.";
}
?>
