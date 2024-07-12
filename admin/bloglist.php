<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/blog_class.php";

$blog = new Blog;
$authors = $blog->getAuthors();
// Lấy giá trị từ URL
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : 'date-desc';
$categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
$authorFilter = isset($_GET['authorFilter']) ? $_GET['authorFilter'] : ''; // Lấy giá trị tác giả từ URL

// Thiết lập giá trị mặc định nếu cần
$blogsPerPage = 6;

// Lấy tổng số bài viết
if (!empty($searchTerm) || !empty($categoryFilter) || !empty($authorFilter)) {
    // Nếu có tìm kiếm hoặc lọc theo danh mục hoặc tác giả, tính tổng số bài viết sau khi tìm kiếm hoặc lọc
    $totalBlogs = $blog->countFilteredBlogs($searchTerm, $categoryFilter, $authorFilter);
} else {
    // Nếu không có tìm kiếm hoặc lọc, lấy tổng số bài viết
    $totalBlogs = $blog->countBlogs();
}

// Tính tổng số trang
$totalPages = ceil($totalBlogs / $blogsPerPage);

// Xác định trang hiện tại
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Đảm bảo trang hiện tại hợp lệ
if ($currentPage < 1 || $currentPage > $totalPages) {
    $currentPage = 1;
}

// Tính offset
$offset = ($currentPage - 1) * $blogsPerPage;

// Tìm kiếm và lấy danh sách blogs dựa trên sort, filter và search
$show_blogs = [];

// Tạo biến lưu trữ URL phân trang cơ bản
$basePaginationUrl = "bloglist.php?search=$searchTerm&sort=$sortBy&category=$categoryFilter";

if (!empty($categoryFilter) && !empty($authorFilter)) {
    // Nếu cả danh mục và tác giả đều có giá trị
    $show_blogs = $blog->getBlogsByCategoryAndAuthor($categoryFilter, $authorFilter, $blogsPerPage, $offset, $sortBy);
    // Cập nhật URL phân trang
    $paginationUrl = "$basePaginationUrl&authorFilter=$authorFilter";
} elseif (!empty($authorFilter)) {
    // Lọc theo tác giả
    $show_blogs = $blog->getBlogsByAuthor($authorFilter, $blogsPerPage, $offset, $sortBy);
    // Cập nhật URL phân trang
    $paginationUrl = "$basePaginationUrl&authorFilter=$authorFilter";
} elseif (!empty($searchTerm)) {
    // Tìm kiếm
    $show_blogs = $blog->searchBlogsByTitle($searchTerm, $blogsPerPage, $offset);
    // Cập nhật URL phân trang
    $paginationUrl = "$basePaginationUrl";
} elseif (!empty($categoryFilter)) {
    // Lọc theo danh mục
    $show_blogs = $blog->getBlogsByCategory($categoryFilter, $blogsPerPage, $offset, $sortBy);
    // Cập nhật URL phân trang
    $paginationUrl = "$basePaginationUrl";
} else {
    // Hiển thị toàn bộ blogs đã sắp xếp
    $show_blogs = $blog->getSortedBlogs($blogsPerPage, $offset, $sortBy);
    // Cập nhật URL phân trang
    $paginationUrl = "$basePaginationUrl";
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Blog List</h6>
            <form class="d-none d-md-flex ms-4" method="GET" action="bloglist.php">
                <input class="form-control bg-dark border-0" type="search" name="search" placeholder="Search by Title" value="<?php echo $searchTerm; ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <a href="blog_add.php">ADD Blog</a>
        </div>

        <div>
            <label for="sort">Sort by:</label>
            <select name="sort" id="sort" onchange="sortBlogs(this.value)">
                <option value="">-- Select --</option>
                <option value="title-asc" <?php echo ($sortBy === 'title-asc') ? 'selected' : ''; ?>>Title A-Z</option>
                <option value="title-desc" <?php echo ($sortBy === 'title-desc') ? 'selected' : ''; ?>>Title Z-A</option>
                <option value="date-asc" <?php echo ($sortBy === 'date-asc') ? 'selected' : ''; ?>>Date Ascending</option>
                <option value="date-desc" <?php echo ($sortBy === 'date-desc') ? 'selected' : ''; ?>>Date Descending</option>
            </select>

            <label for="filter">Filter by Category:</label>
            <select name="filter" id="filter" onchange="filterBlogs(this.value)">
                <option value="">-- All Categories --</option>
                <?php
                $categories = $blog->show_categories();
                while ($category = $categories->fetch_assoc()) {
                    echo '<option value="' . $category['blog_cate_id'] . '" ' . (($categoryFilter === $category['blog_cate_id']) ? 'selected' : '') . '>' . $category['blog_cate_name'] . '</option>';
                }
                ?>
            </select>

            <label for="authorFilter">Filter by Author:</label>
            <select name="authorFilter" id="authorFilter" onchange="filterBlogsByAuthor(this.value)">
                <option value="">-- All Authors --</option>
                <?php
                while ($author = $authors->fetch_assoc()) {
                    $authorName = $author['blog_author'];
                    $selected = ($authorFilter === $authorName) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($authorName) . '" ' . $selected . '>' . htmlspecialchars($authorName) . '</option>';
                }
                ?>
            </select>
        </div><br>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Blog Title</th>
                        <th scope="col">Date</th>
                        <th scope="col">Author</th>
                        <th scope="col">Content</th>
                        <th scope="col">Tags</th>
                        <th scope="col">Image</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($show_blogs->num_rows > 0) {
                        $i = ($currentPage - 1) * $blogsPerPage + 1;
                        while ($result = $show_blogs->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo htmlspecialchars($result['blog_title']) ?></td>
                                <td><?php echo $result['blog_date'] ?></td>
                                <td><?php echo htmlspecialchars($result['blog_author']) ?></td>
                                <td><?php echo substr(strip_tags($result['blog_content']), 0, 100) . '...' ?></td>
                                <td><?php echo htmlspecialchars($result['blog_tags']) ?></td>
                                <td>
                                    <img src="uploads/<?php echo $result['blog_image'] ?>" alt="Blog Image" style="max-width: 100px;">
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="blogedit.php?blog_id=<?php echo $result['blog_id'] ?>">Update</a> |
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $result['blog_id'] ?>)">Delete</a>
                                </td>
                            </tr>
                        <?php
                            $i++;
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="9">No blogs found.</td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div><br>
        <?php if ($totalPages > 1) : ?>
            <div class="pagination-container">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($currentPage > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="bloglist.php?page=1<?php echo (!empty($categoryFilter)) ? '&category=' . $categoryFilter : ''; ?>&search=<?php echo $searchTerm; ?>&sort=<?php echo $sortBy; ?>&authorFilter=<?php echo $authorFilter; ?>" aria-label="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="bloglist.php?page=<?php echo $currentPage - 1; ?><?php echo (!empty($categoryFilter)) ? '&category=' . $categoryFilter : ''; ?>&search=<?php echo $searchTerm; ?>&sort=<?php echo $sortBy; ?>&authorFilter=<?php echo $authorFilter; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="bloglist.php?page=<?php echo $i; ?><?php echo (!empty($categoryFilter)) ? '&category=' . $categoryFilter : ''; ?>&search=<?php echo $searchTerm; ?>&sort=<?php echo $sortBy; ?>&authorFilter=<?php echo $authorFilter; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages) : ?>
                            <li class="page-item">
                                <a class="page-link" href="bloglist.php?page=<?php echo $currentPage + 1; ?><?php echo (!empty($categoryFilter)) ? '&category=' . $categoryFilter : ''; ?>&search=<?php echo $searchTerm; ?>&sort=<?php echo $sortBy; ?>&authorFilter=<?php echo $authorFilter; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="bloglist.php?page=<?php echo $totalPages; ?><?php echo (!empty($categoryFilter)) ? '&category=' . $categoryFilter : ''; ?>&search=<?php echo $searchTerm; ?>&sort=<?php echo $sortBy; ?>&authorFilter=<?php echo $authorFilter; ?>" aria-label="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Định dạng container chứa phân trang */
    .pagination-container {
        text-align: center;
        /* Căn giữa nội dung */
    }

    /* Định dạng nút phân trang */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 16px;
        text-decoration: none;
        color: red;
        border: 1px solid red;
        margin: 2px;
        border-radius: 4px;
    }

    /* Định dạng nút phân trang hoạt động */
    .pagination a.active {
        background-color: red;
        color: white;
        border: 1px solid black;
    }

    /* Định dạng nút phân trang khi di chuột qua */
    .pagination a:hover {
        background-color: black;
        color: white;
    }
</style>

<script>
    // Function for blog-delete
    function confirmDelete(blog_id) {
        if (confirm('Are you sure you want to delete this blog?')) {
            window.location.href = 'blogdelete.php?blog_id=' + blog_id;
        }
    }

    function sortBlogs(sortBy) {
        const currentUrl = window.location.href;
        const url = new URL(currentUrl);
        url.searchParams.set('sort', sortBy);
        window.location.href = url.toString();
    }

    function filterBlogs(categoryId) {
    // Lấy giá trị tác giả hiện tại từ URL (nếu có)
    const currentUrl = new URL(window.location.href);
    const currentAuthorFilter = currentUrl.searchParams.get('authorFilter');

    // Cập nhật URL với cả categoryId và authorFilter
    const url = new URL('bloglist.php', window.location.href);
    url.searchParams.set('category', categoryId);
    if (currentAuthorFilter) {
        url.searchParams.set('authorFilter', currentAuthorFilter);
    }

    // Chuyển đến URL mới
    window.location.href = url.toString();
}

    function filterBlogsByAuthor(author, page) {
        // Sử dụng encodeURIComponent để tránh lỗi với tên tác giả có ký tự đặc biệt
        window.location.href = 'bloglist.php?authorFilter=' + encodeURIComponent(author) + '&sort=<?php echo $sortBy; ?>&search=<?php echo $searchTerm; ?>&category=<?php echo $categoryFilter; ?>&page=' + page;
    }
</script>

<?php
include "footer.php";
?>