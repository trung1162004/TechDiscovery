<?php
include "header.php";
include "navbar.php";
include "admin/class/blog_class.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['blog_id'])) {
    $blog_id = $_GET['blog_id'];
    $blog = new Blog();
    $blogDetail = $blog->get_blog($blog_id);

    if ($blogDetail) {
        $detail = $blogDetail->fetch_assoc();
        $categoryName = $blog->getCategoryNameById($detail['blog_cate_id']);
        $relatedBlogs = $blog->getRelatedBlogs($blog_id);
?>
        <section id="blog-detail" class="section">
            <div class="container">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Blog Post -->
                        <div class="card mb-4">
                            <h1 class="card-title"><?php echo $detail['blog_title']; ?></h1>
                            <div class="vote-count">
                                <p class="fa fa-thumbs-up" id="upvote-count"><?php echo isset($detail['upvotes']) ? $detail['upvotes'] : 0; ?></p>
                                <p class="fa fa-thumbs-down" id="downvote-count"><?php echo isset($detail['downvotes']) ? $detail['downvotes'] : 0; ?></p>
                            </div>
                            <img class="card-img-top-a" src="admin/uploads/<?php echo $detail['blog_image']; ?>">
                            <div class="card-body text-center blog-content">
                                <p class="card-text"><?php echo $detail['blog_content']; ?></p>
                            </div>
                            <div class="card-footer text-muted text-center">
                                <a href="#"><?php echo $categoryName; ?></a> /
                                <?php echo $detail['blog_date']; ?> /
                                <a href="https://github.com/duongnx03"><?php echo $detail['blog_author']; ?></a> /
                                <a href="#"><?php echo $detail['blog_tags']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Form Comment -->
        <section id="comment-section" class="section">
            <div class="container text-center">
                <div id="comment-section" class="section">
                    <div class="container">
                        <h2 class="text-center">Comments</h2>
                        <div id="comments-list">
                            <!-- Các comment sẽ được thêm vào đây -->
                        </div>
                    </div>
                </div>
                <!-- Phần Vote -->
                <div class="vote-section">
                    <h3>Rate this blog:</h3>
                    <button class="btn btn-success vote-up"><i class="fa fa-thumbs-up"></i> Upvote</button>
                    <button class="btn btn-danger vote-down"><i class="fa fa-thumbs-down"></i> Downvote</button>
                </div><br>
                <form method="post">
                    <div class="form-group">
                        <h4 class="card-title">Leave a Comment:</h4>
                        <label for="comment">Your Comment</label>
                        <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>
        </section>

        <!-- carousel blog related-->
        <?php
        if ($relatedBlogs && $relatedBlogs->num_rows > 0) {
        ?>
            <section id="related-blogs" class="section">
                <div class="container">
                    <h2 class="text-center">Related Blog</h2>
                    <div class="featured-products-box owl-carousel owl-theme">
                        <?php
                        while ($row = $relatedBlogs->fetch_assoc()) {
                            $blogDetailLink = "blog-detail.php?blog_id=" . $row['blog_id'];
                        ?>
                            <div class="item">
                                <div class="products-single fix">
                                    <div class="box-img-hover">
                                        <a href="<?php echo $blogDetailLink; ?>">
                                            <img src="admin/uploads/<?php echo $row['blog_image']; ?>" class="img-fluid" alt="Image">
                                        </a>
                                    </div>
                                    <div class="why-text">
                                        <h4><a href="<?php echo $blogDetailLink; ?>"><?php echo $row['blog_title']; ?></a></h4>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </section>
        <?php } ?>
<?php
    } else {
        echo '<p>No Blogs found.</p>';
    }
} else {
    echo '<p>No Blog ID provided.</p>';
}
?>


<br><br>


<style>
    .blog-detail{
        background: grey;
    }

    body {
        background-image: url('image/nen1.jpeg') !important; /* Thay 'URL_CUA_HINH_ANH_NEN' bằng URL thực tế của hình ảnh nền */
        background-size: cover; /* Để đảm bảo hình ảnh nền phủ kín toàn bộ nền trang */
        background-repeat: no-repeat; /* Ngăn lặp lại hình ảnh nền */
        background-attachment: fixed; /* Giữ hình ảnh nền cố định khi trang cuộn */
    }
 
    .featured-products-box .item .box-img-hover:hover img {
    transform: scale(1.05); /* Increase image size slightly */
    transition: transform 0.3s ease; /* Add a smooth transition */
}

    /* CSS for the Related Blog Section */
    #related-blogs {
        padding: 30px 0;
        /* Add some padding to the section */
    }

    .featured-products-box {
        margin-top: 0px;
        /* Add margin to the top of the carousel */
    }

    .featured-products-box .item {
        margin-right: 20px;
        /* Add margin between the blog items */
    }

    /* Set a max-width for the images and make them centered */
    .featured-products-box .item .box-img-hover img {
        max-width: 100%;
        /* Ensure images don't overflow their containers */
        display: block;
        /* Center the images */
        margin: 0 auto;
    }

    /* Style the blog title */
    .featured-products-box .item .why-text h4 {
        font-size: 18px;
        /* Adjust the font size as needed */
        margin-top: 10px;
        /* Add spacing below the title */
        text-align: center;
        /* Center the text horizontally */
        height: 60px;
        /* Set a fixed height for the title container */
        overflow: hidden;
        /* Hide any overflowing content */
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Limit the title to 2 lines */
        -webkit-box-orient: vertical;
    }

    /* Ensure consistent image and title heights for uniform appearance */
    .featured-products-box .item .box-img-hover,
    .featured-products-box .item .why-text {
        height: 150px;
        /* Set a fixed height for both image and title containers */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }


    h1 {
        padding-top: 30px;
        text-align: center;
        font-weight: 600;
        font-size: 35px;
    }

    .carousel-item {
        background-color: #ddd;
    }

    .card-img-top-a {
        max-width: 80%;
        height: auto;
        display: block;
        /* Để giữ cho ảnh căn giữa */
        margin: 0 auto;
        /* Để căn giữa theo chiều ngang */
    }

    .card-img-top-a:hover {
        /* Bất kỳ hiệu ứng hover nào cũng được loại bỏ bằng cách thiết lập các thuộc tính sau về giá trị ban đầu */
        transform: none;
        opacity: 1;
    }

    /* CSS để tạo khuôn viền cho phần comment */
    .comment-section {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #f7f7f7;
        /* Màu nền cho phần comment */
        border-radius: 5px;
        /* Định dạng góc bo tròn */
    }

    .comment-container {
        border: 1px solid #ddd;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #f7f7f7;
        /* Màu nền cho mỗi comment */
        border-radius: 5px;
        /* Định dạng góc bo tròn */
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .comment-author {
        font-weight: bold;
    }

    .comment-text {
        margin-top: 5px;
    }

    #comment-section {
        background-color: #f7f7f7;
        /* Màu nền cho phần comment */
        padding: 30px 0;
        /* Khoảng cách giữa nội dung và mép trang */
    }

    .container.text-center {
        max-width: 800px;
        /* Đặt kích thước tối đa cho phần comment */
        margin: 0 auto;
        /* Căn giữa theo chiều ngang */
    }

    .vote-section {
        margin-top: 20px;
        text-align: center;
    }

    .rating {
        font-size: 24px;
        cursor: pointer;
    }

    .fa-star {
        color: #ddd;
    }

    .fa-star.checked {
        color: gold;
    }

    .blog-content {
        max-width: 800px;
        /* Đặt kích thước tối đa của nội dung */
        margin: 0 auto;
        /* Căn giữa theo chiều ngang */
        padding: 20px;
        /* Thêm phần đệm cho khoảng cách từ nội dung đến mép trang */
    }

    .vote-count {
        text-align: center;
        margin-top: 10px;
    }

    .fa.fa-thumbs-up,
    .fa.fa-thumbs-down {
        font-size: 24px;
        margin-right: 10px;
        cursor: pointer;
    }

    .fa.fa-thumbs-up:hover,
    .fa.fa-thumbs-down:hover {
        color: #007bff;
        /* Màu khi di chuột qua */
    }
</style>

<script>
    $(document).ready(function() {
        $("#relatedBlogsCarousel").owlCarousel({
            items: 3, // Số lượng phần tử hiển thị
            loop: true, // Lặp vô hạn
            margin: 10, // Khoảng cách giữa các phần tử
            nav: true, // Hiển thị nút Previous và Next
            responsive: {
                0: {
                    items: 1 // Số lượng phần tử hiển thị ở màn hình nhỏ
                },
                600: {
                    items: 2 // Số lượng phần tử hiển thị ở màn hình có độ rộng từ 600px trở lên
                },
                1000: {
                    items: 3 // Số lượng phần tử hiển thị ở màn hình có độ rộng từ 1000px trở lên
                }
            }
        });
    });
</script>

<?php
include "footer.php";
?>