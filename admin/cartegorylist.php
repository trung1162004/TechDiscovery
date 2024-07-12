<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/cartegory_class.php";
?>

<?php
$cartegory = new cartegory;
$show_cartegory = $cartegory->show_cartegory();
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Category List</h6>
            <a href="cartegory_add.php">ADD Cartegory</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Main Category Name</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($show_cartegory) {
                        $i = 0;
                        while ($result = $show_cartegory->fetch_assoc()) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['cartegory_main_name'] ?></td>
                                <td><?php echo $result['cartegory_name'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="cartegoryedit.php?cartegory_id=<?php echo $result['cartegory_id'] ?>">Update</a> |
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $result['cartegory_id']; ?>)">Delete</a>
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

<script>
    // Function for cartegory-delete
    function confirmDelete(cartegory_id) {
        if (confirm('Are you sure you want to delete this category?')) {
            window.location.href = 'cartegorydelete.php?cartegory_id=' + cartegory_id;
        }
    }
</script>

<?php
include "footer.php";
?>
