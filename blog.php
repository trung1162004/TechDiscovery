<?php
include "header.php";
include "navbar.php";
include "admin/class/blog_class.php";

$blog = new Blog();
$limit = 4; // Số lượng blog trên mỗi trang

// Xử lý tìm kiếm theo tiêu đề
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
    $blogs = $blog->searchBlogsByTitle($searchTerm, $limit, 0);
} elseif (isset($_GET['category'])) {
    // Nếu có danh mục được chọn, hiển thị blog của danh mục đó
    $category_id = $_GET['category'];
    $blogs = $blog->getBlogsByCategory($category_id, $limit, 0);
} else {
    // Nếu không có danh mục nào được chọn, hiển thị tất cả blog
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;
    $totalBlogs = $blog->countBlogs();
    $totalPages = ceil($totalBlogs / $limit);
    $blogs = $blog->getBlogsPaginated($offset, $limit);
}

$categories = $blog->getCategories();
?>

<section id="cta" class="section">
    <!-- Mã HTML cho phần này -->
</section>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <h5 class="card-header">Search</h5>
                <div class="card-body">
                    <form method="post" action="">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" placeholder="Search for...">
                            <span class="input-group-btn">
                                <button class="btn btn-secondary" type="submit">Go</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <h5 class="card-header">Categories</h5>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($categories as $category) : ?>
                            <li><a href="blog.php?category=<?= $category['blog_cate_id']; ?>"><?= $category['blog_cate_name']; ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

            <div class="card mt-4">
                <h5 class="card-header">Recent Posts</h5>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <?php foreach ($blogs as $blogItem) : ?>
                            <li>
                                <a href="blog-detail.php?blog_id=<?= $blogItem['blog_id']; ?>">
                                    <h6><?= $blogItem['blog_title']; ?></h6>
                                    <p class="small">Posted on <?= $blogItem['blog_date']; ?> by <?= $blogItem['blog_author']; ?></p>
                                </a>
                            </li>
                            <br>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <?php foreach ($blogs as $blogItem) : ?>
                <div class="card mb-4">
                    <a href="blog-detail.php?blog_id=<?= $blogItem['blog_id']; ?>"><img class="card-img-top" src="admin/uploads/<?= $blogItem['blog_image']; ?>" alt="<?= $blogItem['blog_title']; ?>"></a>
                    <div class="card-body text-center">
                        <h2 class="card-title"><a href="blog-detail.php?blog_id=<?= $blogItem['blog_id']; ?>"><?= $blogItem['blog_title']; ?></a></h2>
                        <p class="card-text"><?php echo substr(strip_tags($blogItem['blog_content']), 0, 298) . '...' ?></p>
                        <a href="blog-detail.php?blog_id=<?= $blogItem['blog_id']; ?>" class="btn btn-primary">Read More &rarr;</a>
                    </div>
                    <div class="card-footer text-muted text-center">
                        <?php
                        $categoryName = $blog->getCategoryNameById($blogItem['blog_cate_id']);
                        ?>
                        <a href="category.php?category_id=<?= $blogItem['blog_cate_id']; ?>"><?= $categoryName; ?></a> / <?= $blogItem['blog_date']; ?> / <a href=""><?= $blogItem['blog_author']; ?> / <a href=""><?= $blogItem['blog_tags']; ?></a>
                    </div>
                </div>
            <?php endforeach; ?>

            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php if ($totalPages > 1) : ?>
                        <?php if ($page > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="blog.php?page=1<?php if (isset($_GET['category'])) echo "&category=" . $_GET['category']; ?><?php if (isset($_POST['search'])) echo "&search=" . $_POST['search']; ?>">&laquo;&laquo;</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="blog.php?page=<?= $page - 1; ?><?php if (isset($_GET['category'])) echo "&category=" . $_GET['category']; ?><?php if (isset($_POST['search'])) echo "&search=" . $_POST['search']; ?>">&laquo;</a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="blog.php?page=<?= $i; ?><?php if (isset($_GET['category'])) echo "&category=" . $_GET['category']; ?><?php if (isset($_POST['search'])) echo "&search=" . $_POST['search']; ?>"><?= $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages) : ?>
                            <li class="page-item">
                                <a class="page-link" href="blog.php?page=<?= $page + 1; ?><?php if (isset($_GET['category'])) echo "&category=" . $_GET['category']; ?><?php if (isset($_POST['search'])) echo "&search=" . $_POST['search']; ?>">&raquo;</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="blog.php?page=<?= $totalPages; ?><?php if (isset($_GET['category'])) echo "&category=" . $_GET['category']; ?><?php if (isset($_POST['search'])) echo "&search=" . $_POST['search']; ?>">&raquo;&raquo;</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </nav>

        </div>
    </div>
</div><br><br>

<style>
    .card-img-top {
        max-width: 100%;
        height: 350px;
    }
</style>

<?php include "footer.php"; ?>