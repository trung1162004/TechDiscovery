<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/blog_category_class.php";
?>

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Blog Category List</h6>
            <a href="blog_category_add.php">ADD Blog Category</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Category ID</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $blogCategory = new BlogCategory();
                    $categoryList = $blogCategory->show_category_blog();
                    
                    if ($categoryList) {
                        $i = 0;
                        foreach ($categoryList as $category) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $category['blog_cate_id'] ?></td>
                                <td><?php echo $category['blog_cate_name'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="blog_category_edit.php?blog_cate_id=<?php echo $category['blog_cate_id'] ?>">Update</a> |
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $category['blog_cate_id']; ?>)">Delete</a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Recent Sales End -->

<script>
    // Function for blog_category delete
    function confirmDelete(blog_cate_id) {
        if (confirm('Are you sure you want to delete this blog category?')) {
            window.location.href = 'blog_category_delete.php?blog_cate_id=' + blog_cate_id;
        }
    }
</script>

<?php
include "footer.php";