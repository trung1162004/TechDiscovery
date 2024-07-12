<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/blog_class.php";
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
$blog = new Blog;

$categories = $blog->show_categories();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Xử lý khi gửi biểu mẫu chỉnh sửa bài viết blog
    $update_blog = $blog->update_blog($_POST, $_GET['blog_id']);

    if ($update_blog) {
        echo "<script>window.location.href = 'bloglist.php';</script>";
        exit; // Ngăn tạo ra mã HTML thêm
    }
} elseif (isset($_GET['blog_id']) && !empty($_GET['blog_id'])) {
    // Xử lý khi tải biểu mẫu chỉnh sửa bài viết blog
    $blog_id = $_GET['blog_id'];
    $blog_data = $blog->get_blog_detail($blog_id);
    if (!$blog_data) {
        echo "<script>alert('Blog not found.');</script>";
        echo "<script>window.location.href = 'bloglist.php';</script>";
        exit; // Ngăn tạo ra mã HTML thêm
    }
    $blog_data = $blog_data->fetch_assoc();
} else {
    echo "<script>alert('Invalid blog ID.');</script>";
    echo "<script>window.location.href = 'bloglist.php';</script>";
    exit; // Ngăn tạo ra mã HTML thêm
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Edit Blog</h6>
            <a href="bloglist.php">Back to Blog List</a>
        </div>
        <div class="admin-content-right-product-add row">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="blog_cate_id">Select Blog Category <span style="color:red;">*</span></label>
                    <select name="blog_cate_id" id="blog_cate_id" class="form-control" required>
                        <option value="">--Select--</option>
                        <?php
                        if ($categories) {
                            while ($category = $categories->fetch_assoc()) {
                                $selected = ($category['blog_cate_id'] == $blog_data['blog_cate_id']) ? 'selected' : '';
                                echo '<option value="' . $category['blog_cate_id'] . '" ' . $selected . '>' . $category['blog_cate_name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="blog_title">Blog Title: <span style="color:red;">*</span></label>
                    <input name="blog_title" type="text" class="form-control" required value="<?php echo $blog_data['blog_title']; ?>">
                </div>

                <div class="form-group">
                    <label for="blog_author">Blog Author: <span style="color:red;">*</span></label>
                    <input name="blog_author" type="text" class="form-control" required value="<?php echo $blog_data['blog_author']; ?>">
                </div>

                <div class="form-group">
                    <label for="blog_content">Blog Content: <span style="color:red;">*</span></label>
                    <textarea name="blog_content" id="summernote_content" cols="30" rows="10" class="form-control" required><?php echo $blog_data['blog_content']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="blog_tags">Blog Tags: <span style="color:red;">*</span></label>
                    <input name="blog_tags" type="text" class="form-control" value="<?php echo $blog_data['blog_tags']; ?>">
                </div>

                <div class="form-group">
                    <label for="blog_image">Blog Image: (Leave blank to keep the current image)</label>
                    <input name="blog_image" id="blog_image" type="file" class="form-control" accept="image/*" onchange="previewImage(this, 'previewBlogImg')">
                    <img id="previewBlogImg" src="uploads/<?php echo $blog_data['blog_image']; ?>" alt="" style="max-width: 200px; max-height: 200px; display: block;"><br>
                </div>

                <div class="error-messages">
                    <!-- Dùng để hiển thị thông báo lỗi -->
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Summernote and JavaScript code for it here -->
<script>
    /// Function to preview the selected image
    function previewImage(input, imgId) {
        var preview = document.getElementById(imgId);
        var file = input.files[0];
        var reader = new FileReader();

        reader.onload = function() {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }

        // Thêm gọi hàm validateImage khi người dùng chọn hình ảnh mới
        validateImage(input);
    }

    // Function to validate the selected image
    function validateImage(input) {
        var errorMessagesContainer = document.querySelector('.error-messages');
        errorMessagesContainer.innerHTML = '';

        var file = input.files[0];
        if (file) {
            // Kiểm tra định dạng ảnh
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            var fileExtension = file.name.split('.').pop().toLowerCase();

            if (!allowedExtensions.includes(fileExtension)) {
                var errorMessage = document.createElement('div');
                errorMessage.innerText = "Định dạng đuôi ảnh không hợp lệ. Chỉ chấp nhận định dạng jpg, jpeg, png, gif, webp.";
                errorMessagesContainer.appendChild(errorMessage);
                input.value = ''; // Xóa giá trị của trường input
                return;
            }

            // Kiểm tra dung lượng ảnh
            var maxFileSize = 5 * 1024 * 1024; // 5MB
            if (file.size > maxFileSize) {
                var errorMessage = document.createElement('div');
                errorMessage.innerText = "Dung lượng ảnh quá lớn. Chỉ chấp nhận ảnh có dung lượng tối đa là 5MB.";
                errorMessagesContainer.appendChild(errorMessage);
                input.value = ''; // Xóa giá trị của trường input
                return;
            }
        }
    }

    $('#summernote_content').summernote({
        placeholder: 'Enter Blog Content',
        tabsize: 2,
        height: 200
    });
</script>

<?php
include "footer.php";
?>