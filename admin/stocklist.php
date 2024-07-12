<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/stock_class.php";
include "class/product_class.php";

$stock = new Stock();
$stockList = $stock->showStock();

?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Stock List</h6>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($stockList) {
                        $i = 0;
                        while ($row = $stockList->fetch_assoc()) {
                            $i++;
                            $productInfo = $stock->getStockInfo($row['stock_id']); // Lấy thông tin của stock
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $row['product_id'] ?></td>
                                <td><?php echo $productInfo['product_name'] ?></td>
                                <td><?php echo $row['quantity'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="stock_edit.php?stock_id=<?php echo $row['stock_id'] ?>">Update</a>
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

<?php
include "footer.php";
?>
