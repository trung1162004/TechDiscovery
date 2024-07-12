<?php
include "class/blog_category_class.php";
$blogCategory = new BlogCategory();

if (!isset($_GET['blog_cate_id']) || $_GET['blog_cate_id'] == NULL) {
    echo "<script>window.location = 'blog_category_list.php'</script>";
} else {
    $blog_cate_id = $_GET['blog_cate_id'];
}

$get_category_blog = $blogCategory->delete_category_blog($blog_cate_id);
