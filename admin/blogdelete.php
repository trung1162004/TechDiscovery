<?php
include "class/blog_class.php";

if(isset($_GET['blog_id']) && !empty($_GET['blog_id'])){
    $blog_id = $_GET['blog_id'];
    $blog = new Blog();
    $delete_blog = $blog->delete_blog($blog_id);

    if($delete_blog) {
        echo "<script>alert('Blog deleted successfully.');</script>";
    } else {
        echo "<script>alert('Failed to delete the blog.');</script>";
    }
} else {
    echo "<script>alert('Invalid blog ID.');</script>";
}

echo "<script>window.location = 'bloglist.php';</script>";
?>