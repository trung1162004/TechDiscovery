<?php
include "header.php";
include "navbar.php";

$order_query = "SELECT * FROM tbl_order where user_id = $user_id order by order_id desc";
$order_result = $database->select($order_query);
if ($order_result) {
    while ($row = $order_result->fetch_assoc()) {
        $orderItems[] = array(
            'order_id' => $row['order_id'],
            'order_date' => $row['order_date'],
            'payment_method' => $row['payment_method'],
            'order_status' => $row['order_status'],
            'fullname' => $row['fullname'],
            'phone' => $row['phone'],
            'email' => $row['email'],
            'province' => $row['province'],
            'district' => $row['district'],
            'ward' => $row['ward'],
            'address' => $row['address'],
            'status_payment' => $row['status_payment'],
            'return_status' => $row['return_status'],
            'total_order' => $row['total_order']
        );
    }
}
?>

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>My Account</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">My Account</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Shop Page  -->
<div class="container">
    <div class="directional">
        <a href="myAccount_order.php" class="list-group-item list-group-item-action">Purchase</a>
        <a href="myAccount_order_complete.php" class="list-group-item list-group-item-action">Complete</a>
        <a href="myAccount_order_cancel.php" class="list-group-item list-group-item-action">Cancel</a>
        <a href="myAccount_order_return.php" class="list-group-item list-group-item-action active">Return</a>
    </div>


    <table class="table_info">
        <thead>
            <tr>
                <th>Code Orders</th>
                <th>Receiver's Information</th>
                <th>Payment Method</th>
                <th>Order Date</th>
                <th>Status Order</th>
                <th>Status Payment</th>
                <th>Return Status</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($orderItems)) {
                foreach ($orderItems as $item) {
                    if ($item['order_status'] == 'return') {
            ?>
                        <tr>
                            <td class="name-pr">
                                <b><?php echo $item['order_id'] ?></b>
                            </td>
                            <td class="info">
                                <?php echo '<b>Name:</b> ' . $item['fullname'] . ' <br> <b>Phone:</b> ' . $item['phone'] . ' <br> <b>Email:</b> ' . $item['email'] . ' <br> <b>Address:</b> ' . $item['address'] . ', ' . $item['ward'] . ', <br> ' . $item['district'] . ', ' . $item['province'] ?>
                            </td>
                            <td class="name-pr">
                                <b><?php echo $item['payment_method'] ?></b>
                            </td>
                            <td class="name-pr">
                                <b><?php echo $item['order_date'] ?></b>
                            </td>
                            <td class="name-pr">
                                <b style="color: green;"><?php echo $item['order_status'] ?></b>
                            </td>
                            <td class="name-pr">
                                <b><?php echo $item['status_payment'] ?></b>
                            </td>
                            <td class="price-pr">
                                <b><?php echo $item['return_status']; ?></b>
                            </td>
                            <td class="price-pr">
                                <p>$ <?php echo $item['total_order']; ?></p>
                            </td>
                            <td class="remove-pr">
                                <button class="btn btn-danger view-details" data-order-id="<?php echo $item['order_id']; ?>">View Details</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="9" class="cancel-received">
                                <div class="text-right">    
                                    <button class="btn btn-danger view-return-details" data-order-id="<?php echo $item['order_id']; ?>">View order return details</button>
                                </div>
                            </td>
                        </tr>
            <?php
                    }
                }
            }
            ?>
        </tbody>
    </table>
</div>
<!-- End Shop Page -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Xử lý sự kiện khi người dùng click vào nút "Xem Chi Tiết"
        $(".view-details").click(function() {
            var orderId = $(this).data("order-id");

            // Gửi yêu cầu AJAX để lấy chi tiết đơn hàng từ get_order_details.php
            $.ajax({
                url: "admin/order_details.php",
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
                                    <h5 class="modal-title" id="orderDetailsModalLabel${orderId}">Order details</h5>
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
                    console.log("Lỗi khi lấy chi tiết đơn hàng.");
                }
            });
        });

        $(".view-return-details").click(function() {
            var orderId = $(this).data("order-id");

            // Gửi yêu cầu AJAX để lấy chi tiết đơn hàng từ get_order_details.php
            $.ajax({
                url: "admin/order_return_details.php",
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
                                    <h5 class="modal-title" id="orderDetailsModalLabel${orderId}">Order details</h5>
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
                    console.log("Lỗi khi lấy chi tiết đơn hàng.");
                }
            });
        });
    });
</script>
<?php
include "footer.php";
?>