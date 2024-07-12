<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/cartegory_main_class.php";

$cartegory_main = new cartegory_main();

if (!isset($_GET['cartegory_main_id']) || $_GET['cartegory_main_id'] == NULL) {
    echo "<script>window.location.href = 'cartegory_mainlist.php';</script>";
} else {
    $cartegory_main_id = $_GET['cartegory_main_id'];
}

$get_cartegory_main = $cartegory_main->get_cartegory_main($cartegory_main_id);

if ($get_cartegory_main) {
    $result = $get_cartegory_main->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartegory_main_main_name = $_POST['cartegory_main_main_name'];
    $update_cartegory_main = $cartegory_main->update_cartegory_main($cartegory_main_main_name, $cartegory_main_id);

    if ($update_cartegory_main) {
        echo "<script>window.location.href = 'cartegory_mainlist.php';</script>";
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Category-Main</h6>
            <a href="cartegory_mainlist.php">Back to Category-Main List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="cartegory_main_main_name" class="form-label">Category Main Name</label>
                    <input type="text" class="form-control" id="cartegory_main_main_name" name="cartegory_main_main_name" placeholder="Enter the main category name" required value="<?php echo $result['cartegory_main_name']; ?>">
                </div>
                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
