<?php
include_once 'header.php';
include_once 'sidebar.php';
include_once 'navbar.php';
include_once 'class/Stock.php';

$stock = new Stock();

if (!isset($_GET['stock_id']) || empty($_GET['stock_id'])) {
    echo "<script>window.location = 'stocklist.php'</script>";
}

$stock_id = $_GET['stock_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_quantity = $_POST['new_quantity'];

    $update_result = $stock->updateStockQuantity($stock_id, $new_quantity);

    if ($update_result) {
        echo "<script>window.location.href = 'stocklist.php';</script>";
    } else {
        echo "Failed to update stock quantity.";
    }
}

$stock_info = $stock->getStockInfo($stock_id);

if (!$stock_info) {
    echo "<script>window.location = 'stocklist.php'</script>";
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Stock Quantity</h6>
            <a href="stocklist.php">Back to Stock List</a>
        </div>
        <div class="admin-content-right-product-add row">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="new_quantity">New Quantity <span style="color:red;">*</span></label>
                    <input type="number" name="new_quantity" class="form-control" value="<?php echo $stock_info['quantity']; ?>" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Quantity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include_once 'footer.php';
?>
