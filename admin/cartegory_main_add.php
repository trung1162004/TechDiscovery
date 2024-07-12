<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/cartegory_main_class.php";

$cartegory_main = new cartegory_main();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cartegory_main_name = $_POST['cartegory_main_name'];
    $insert_cartegory_main = $cartegory_main->insert_cartegory_main($cartegory_main_name);
    
    if ($insert_cartegory_main) {
        echo "<script>window.location.href = 'cartegory_mainlist.php';</script>";
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">ADD Category-Main</h6>
            <a href="cartegory_mainlist.php">Back to Category-Main List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="cartegory_main_name" class="form-label">Category Main Name</label>
                    <input type="text" class="form-control" id="cartegory_main_name" name="cartegory_main_name" placeholder="Enter category main name" required>
                </div>
                <button type="submit" class="btn btn-primary">ADD</button>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
