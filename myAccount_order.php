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
        <a href="myAccount_order.php" class="list-group-item list-group-item-action active">Purchase</a>
        <a href="myAccount_order_complete.php" class="list-group-item list-group-item-action">Complete</a>
        <a href="myAccount_order_cancel.php" class="list-group-item list-group-item-action">Cancel</a>
        <a href="myAccount_order_return.php" class="list-group-item list-group-item-action">Return</a>
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
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($orderItems)) {
                foreach ($orderItems as $item) {
                    if ($item['order_status'] == 'processing' || $item['order_status'] == 'delivered_carrier') {
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
                                <b style="color: blue;"><?php echo $item['order_status'] ?></b>
                            </td>
                            <td class="name-pr">
                                <b><?php echo $item['status_payment'] ?></b>
                            </td>
                            <td class="price-pr">
                                <p>$ <?php echo $item['total_order']; ?></p>
                            </td>
                            <td class="remove-pr">
                                <button class="btn btn-danger view-details" data-order-id="<?php echo $item['order_id']; ?>">View Details</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="8" class="cancel-received">
                                <?php if ($item['order_status'] === 'processing') { ?>
                                    <div class="text-right">
                                        <button class="btn btn-danger" onclick="confirmCancel(<?php echo $item['order_id'] ?>)">Cancel Order</button>
                                    </div>
                                <?php } ?>
                                <?php if ($item['order_status'] === 'delivered_carrier') { ?>
                                    <div class="text-right">
                                        <button class="btn btn-danger" onclick="confirmDelivered(<?php echo $item['order_id'] ?>)">Received Order</button>
                                    </div>
                                <?php } ?>
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
    function confirmCancel(orderId) {
        var confirmation = confirm("Are you sure you want to cancel your order?");
        if (confirmation) {
            window.location.href = "admin/process-cancel-order.php?id=" + orderId;
        } else {

        }
    }

    function confirmDelivered(orderId) {
        var confirmation = confirm("Confirm you have received the item");
        if (confirmation) {
            window.location.href = "admin/process-complete-order.php?id=" + orderId;
        } else {

        }
    }

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
    });
</script>
<?php
include "footer.php";
?>