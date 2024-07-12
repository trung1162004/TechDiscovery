<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/order_class.php";
$order = new order;

// Xác định trang hiện tại và số đơn hàng trên mỗi trang
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$itemsPerPage = 9;

// Lấy tổng số đơn hàng
$totalOrders = $order->getTotalOrdersCompleted();

// Tính tổng số trang
$totalPages = ceil($totalOrders / $itemsPerPage);

// Lấy danh sách đơn hàng cho trang hiện tại
$show_order = $order->show_completed_orders($page, $itemsPerPage);

$isSearching = isset($_GET['search']);

if (isset($_GET["search"])) {
    $order_id = $_GET["order_id"];
    $show_order = $order->search_order_list($order_id);
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h6 class="mb-0">Order Completed</h6>
        <form class="d-none d-md-flex ms-4" method="GET">
            <input class="form-control bg-dark border-0" type="search" name="order_id" placeholder="Search by order code">
            <button type="submit" name="search" class="btn btn-primary">Search</button>
        </form>
    </div>
    <div class="bg-secondary text-center rounded p-4">
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Code</th>
                        <th scope="col">User Info</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Status</th>
                        <th scope="col">Total Order</th>
                        <th scope="col">Status Payment</th>
                        <th scope="col">Order Details</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($show_order) {
                        $orderNumber = ($page - 1) * $itemsPerPage;
                        while ($result = $show_order->fetch_assoc()) {
                            $orderNumber++;
                    ?>
                                <tr>
                                    <td><?php echo $orderNumber ?></td>
                                    <td><?php echo $result['order_id'] ?></td>
                                    <td class="info"><?php echo $result['fullname'] . ' | ' . $result['phone'] . ' | ' . $result['email'] . ' | ' . $result['address'] . ', ' . $result['ward'] . ', ' . $result['district'] . ', ' . $result['province'] ?></td>
                                    <td><?php echo $result['order_date'] ?></td>
                                    <td><?php echo $result['payment_method'] ?></td>
                                    <td class="status">
                                        <?php echo $result['order_status'] ?>
                                    </td>
                                    <td>$<?php echo $result['total_order'] ?></td>
                                    <td><?php echo $result['status_payment'] ?></td>
                                    <td>
                                        <button class="btn btn-link view-details" data-order-id="<?php echo $result['order_id']; ?>">View Details</button>
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
    <?php
    // Tạo liên kết phân trang
    if (!$isSearching && $totalOrders > 9) {
        // Tính tổng số trang
        $totalPages = ceil($totalOrders / $itemsPerPage);
    
        // Tạo liên kết phân trang
        if ($totalPages > 1) {
            echo '<div class="text-center mt-4">';
            echo '<ul class="pagination">';
            for ($i = 1; $i <= $totalPages; $i++) {
                $isActive = ($i == $page) ? 'active' : '';
                echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }
    ?>
</div>
<?php
include "footer.php";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Handle click event for the "View Details" button
        $(".view-details").click(function() {
            var orderId = $(this).data("order-id");

            // Make an AJAX request to fetch the details from get_order_details.php
            $.ajax({
                url: "get_order_details.php",
                method: "GET",
                data: {
                    order_id: orderId
                },
                success: function(response) {
                    var modal = `
                        <div class="modal fade" id="orderDetailsModal${orderId}" tabindex="-1" aria-labelledby="orderDetailsModalLabel${orderId}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="orderDetailsModalLabel${orderId}">Order Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ${response}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    $("body").append(modal);
                    $(`#orderDetailsModal${orderId}`).modal("show");
                    $(`#orderDetailsModal${orderId}`).on("hidden.bs.modal", function() {
                        $(this).remove();
                    });
                },
                error: function() {
                    console.log("Error fetching order details.");
                }
            });
        });
    });
</script>