<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/cartegory_main_class.php";
?>

<!-- Recent Sales Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Cartegory-Main List</h6>
            <a href="cartegory_main_add.php">ADD Cartegory-Main</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Cartegory-Main Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $cartegory_main = new cartegory_main;
                    $show_cartegory_main = $cartegory_main->show_cartegory_main();
                    if ($show_cartegory_main) {
                        $i = 0;
                        while ($result = $show_cartegory_main->fetch_assoc()) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['cartegory_main_name'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="cartegory_mainedit.php?cartegory_main_id=<?php echo $result['cartegory_main_id'] ?>">Update</a> |
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $result['cartegory_main_id']; ?>)">Delete</a>
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
    //function for cartegory_main-delete
    function confirmDelete(cartegory_main_id) {
        if (confirm('Are you sure you want to delete this category-main?')) {
            window.location.href = 'cartegory_maindelete.php?cartegory_main_id=' + cartegory_main_id;
        }
    }
</script>

<?php
include "footer.php";
?>