<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/cartegory_class.php";

$cartegory = new cartegory();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartegory_main_id = $_POST['cartegory_main_id'];
    $cartegory_name = $_POST['cartegory_name'];
    $insert_cartegory = $cartegory->insert_cartegory($cartegory_main_id, $cartegory_name);

    if ($insert_cartegory) {
        echo "<script>window.location.href = 'cartegorylist.php';</script>";
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">ADD Category</h6>
            <a href="cartegorylist.php">Back to Category List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="cartegory_main_id" class="form-label">Category Main</label>
                    <select class="form-control" id="cartegory_main_id" name="cartegory_main_id" required>
                        <option value="">-- Select --</option>
                        <?php
                        $show_cartegory_main = $cartegory->show_cartegory_main();
                        if ($show_cartegory_main) {
                            while ($result = $show_cartegory_main->fetch_assoc()) {
                                echo "<option value='" . $result['cartegory_main_id'] . "'>" . $result['cartegory_main_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cartegory_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="cartegory_name" name="cartegory_name" placeholder="Enter category name" required>
                </div>
                <button type="submit" class="btn btn-primary">ADD</button>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
