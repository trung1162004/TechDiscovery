<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/blog_category_class.php";

$blogCategory = new BlogCategory();

if (!isset($_GET['blog_cate_id']) || $_GET['blog_cate_id'] == NULL) {
    echo "<script>window.location.href = 'blog_category_list.php';</script>";
} else {
    $blog_cate_id = $_GET['blog_cate_id'];
}

$get_category_blog = $blogCategory->get_category_blog($blog_cate_id);

if ($get_category_blog) {
    $result = $get_category_blog->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blog_cate_name = $_POST['blog_cate_name'];
    $update_category_blog = $blogCategory->update_category_blog($blog_cate_name, $blog_cate_id);

    if ($update_category_blog) {
        echo "<script>window.location.href = 'blog_category_list.php';</script>";
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Blog Category</h6>
            <a href="blog_categorylist.php">Back to Blog Category List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="blog_cate_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="blog_cate_name" name="blog_cate_name" placeholder="Enter the category name" required value="<?php echo $result['blog_cate_name']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
