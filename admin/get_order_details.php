<?php
include "database.php";
$db = new Database;
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $query = "select * from tbl_order_items where order_id = $order_id";
    $result = $db->select($query);
}
?>
<style>
    .modal-dialog {
        max-width: 80%; /* Adjust the width as needed */
    }

    .modal-body {
        padding: 20px;
    }

    .modal-title {
        font-size: 18px;
    }

    .order-details-table {
        width: 100%;
    }

    .order-details-table th,
    .order-details-table td {
        padding: 10px;
    }

    .order-details-table th {
        background-color: #f2f2f2;
    }
</style>
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Order User List</h6>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Product</th>
                        <th scope="col">Name</th>
                        <th scope="col">Color</th>
                        <th scope="col">Memory</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result) {
                        $i = 0;
                        while ($row = $result->fetch_assoc()) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><img src="uploads/<?php echo $row['product_img'] ?>" alt="Product Image" style="max-width: 100px;"></td>
                                <td><?php echo $row['product_name'] ?></td>
                                <td><?php echo $row['product_color'] ?></td>
                                <td><?php echo $row['product_memory_ram'] ?></td>
                                <td>$ <?php echo $row['product_price'] ?></td>
                                <td><?php echo $row['quantity'] ?></td>
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