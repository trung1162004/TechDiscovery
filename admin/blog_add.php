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
    $insert_blog = $blog->insert_blog($_POST);

    if ($insert_blog) {
        echo "<script>window.location.href = 'bloglist.php';</script>";
        exit; // Thêm dòng này để ngăn tạo ra mã HTML nữa
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Add Blog</h6>
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
                                echo '<option value="' . $category['blog_cate_id'] . '">' . $category['blog_cate_name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="blog_title">Blog Title: <span style="color:red;">*</span></label>
                    <input name="blog_title" type="text" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="blog_author">Blog Author: <span style="color:red;">*</span></label>
                    <input name="blog_author" type="text" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="blog_content">Blog Content: <span style="color:red;">*</span></label>
                    <textarea name="blog_content" id="summernote_content" cols="30" rows="10" class="form-control" required></textarea>
                </div>

                <div class="form-group">
                    <label for="blog_tags">Blog Tags: <span style="color:red;"></span></label>
                    <input name="blog_tags" type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label for="blog_image">Blog Image:<span style="color:red;">*</span></label>
                    <input required name="blog_image" type="file" class="form-control" onchange="previewImage(this, 'previewBlogImg'); validateImage(this);">
                    <img id="previewBlogImg" src="" alt="Preview Image" style="max-width: 200px; max-height: 200px; display: none;"><br>
                </div>
                <div class="error-messages">
                    <!-- Dùng để hiển thị thông báo lỗi -->
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Include Summernote and JavaScript code for it here -->
<script>
    // Function to preview the selected image
    function previewImage(input, imgId) {
        var preview = document.getElementById(imgId);
        var file = input.files[0];
        var reader = new FileReader();

        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = 'block'; // Show the image when an image is selected
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    // Hàm kiểm tra định dạng và dung lượng của ảnh
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

    function validateBlogForm() {
        var title = document.getElementById("blog_title").value.trim();
        var author = document.getElementById("blog_author").value.trim();
        var content = document.getElementById("summernote_content").value.trim();
        var imageInput = document.querySelector('input[type="file"]');

        // Kiểm tra tiêu đề
        if (title === "") {
            alert("Blog title is required.");
            return false;
        } else if (title.length > 200) {
            alert("Blog title should not exceed 200 characters.");
            return false;
        }

        // Kiểm tra tác giả
        if (author === "") {
            alert("Blog author is required.");
            return false;
        } else if (author.length > 50) {
            alert("Blog author should not exceed 50 characters.");
            return false;
        }

        // Kiểm tra nội dung
        if (content === "") {
            alert("Blog content is required.");
            return false;
        } else if (content.length < 100) {
            alert("Blog content should be at least 100 characters long.");
            return false;
        }

        // Kiểm tra định dạng và kích thước ảnh
        if (imageInput.files.length > 0) {
            var file = imageInput.files[0];
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            var fileExtension = file.name.split('.').pop().toLowerCase();
            var maxFileSize = 5 * 1024 * 1024; // 5MB

            if (!allowedExtensions.includes(fileExtension)) {
                alert("Invalid image format. Only JPG, JPEG, PNG, GIF, and WebP formats are allowed.");
                return false;
            }

            if (file.size > maxFileSize) {
                alert("Image file size is too large. Maximum file size allowed is 5MB.");
                return false;
            }
        } else {
            alert("Blog image is required.");
            return false;
        }

        // Chấp nhận việc gửi form nếu tất cả kiểm tra thành công
        return true;
    }
</script>

<?php
include "footer.php";
?>