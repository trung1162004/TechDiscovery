<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/cartegory_class.php";

$cartegory = new cartegory();

// Lấy cartegory_id từ URL
if(isset($_GET['cartegory_id']) && !empty($_GET['cartegory_id'])) {
    $cartegory_id = $_GET['cartegory_id'];
} else {
    echo "<script>window.location.href = 'cartegorylist.php';</script>";
}

// Lấy thông tin cartegory theo cartegory_id
$get_cartegory = $cartegory->get_cartegory($cartegory_id);
if($get_cartegory) {
    $result = $get_cartegory->fetch_assoc();
}

// Xử lý khi form được submit
if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartegory_main_id = $_POST['cartegory_main_id'];
    $cartegory_name = $_POST['cartegory_name'];
    $update_cartegory = $cartegory->update_cartegory($cartegory_main_id, $cartegory_name, $cartegory_id);

    if ($update_cartegory) {
        echo "<script>window.location.href = 'cartegorylist.php';</script>";
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Cartegory</h6>
            <a href="cartegorylist.php">Back to Category List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="cartegory_main_id" class="form-label">Category</label>
                    <select class="form-control" id="cartegory_main_id" name="cartegory_main_id" required>
                        <option value="">-- Selec Cartegory --</option>
                        <?php
                        $show_cartegory_main = $cartegory->show_cartegory_main();
                        if ($show_cartegory_main) {
                            while ($result_main = $show_cartegory_main->fetch_assoc()) {
                                $selected = ($result['cartegory_main_id'] == $result_main['cartegory_main_id']) ? "selected" : "";
                                echo "<option value='" . $result_main['cartegory_main_id'] . "' $selected>" . $result_main['cartegory_main_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cartegory_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="cartegory_name" name="cartegory_name" placeholder="Enter category name" value="<?php echo $result['cartegory_name']?>" required>
                </div>
                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
