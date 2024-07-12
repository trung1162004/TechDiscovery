<?php
include "header.php";
include "navbar.php";
$user_query = "SELECT * FROM users where id = $user_id";
$user_result = $database->select($user_query);
if ($user_result) {
    $row = $user_result->fetch_assoc();
    $fullname = $row['fullname'];
    $email = $row['email'];
    $address = $row['address'];
    $phone = $row['phone'];
}
// Lấy giá trị từ session
$amountDiscount = isset($_SESSION['amountDiscount']) ? $_SESSION['amountDiscount'] : 0;
$giadagiam = isset($_SESSION['giadagiam']) ? $_SESSION['giadagiam'] : $totalPrice;
$code = isset($_SESSION['code']) ? $_SESSION['code'] : '';

// Xóa dữ liệu trong session sau khi sử dụng
// unset($_SESSION['amountDiscount']);
unset($_SESSION['giadagiam']);
unset($_SESSION['code']);
// Sau khi xử lý xong đơn hàng, bạn cần kiểm tra xem có mã giảm giá nào được sử dụng hay không
if (!empty($code)) {
    // Kiểm tra số lượng mã giảm giá còn lớn hơn 0
    $quantityQuery = "SELECT quantity_coupon FROM coupon WHERE code = '$code'";
    $quantityResult = $database->select($quantityQuery);

    if ($quantityResult && $quantityResult->num_rows > 0) {
        $couponQuantityData = $quantityResult->fetch_assoc();
        $quantity_coupon = $couponQuantityData['quantity_coupon'];

        if ($quantity_coupon > 0) {
            // Giảm số lượng mã giảm giá đi 1
            $updatedQuantity = $quantity_coupon - 1;
            $updateQuantityQuery = "UPDATE coupon SET quantity_coupon = $updatedQuantity WHERE code = '$code'";
            $database->update($updateQuantityQuery);
        }
    }
}
?>
<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Checkout</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->
<script src='https://www.paypal.com/sdk/js?client-id=AUbOEvIMIXKSLOwnIgiCu0q7iRKK2hJtW55odcvAgtYO7heyQAa2ZDIv7ziZkzD-sGM3L2rKH5SIaxad&currency=USD'></script>
<!-- Start Cart  -->
<div class="cart-box-main">
    <div class="container">
        <form id="order-form" onsubmit="return validateForm();" action="admin/process-order.php" method="post">
            <div class="row">
                <div class="col-sm-6 col-lg-6 mb-3">
                    <div class="checkout-address">
                        <div class="title-left">
                            <h3>Billing address</h3>
                        </div>
                        <div class="mb-3">
                            <label for="username">Full name *</label>
                            <div class="input-group">
                                <input type="text" name="fullname" class="form-control" id="username" required value="<?php if (!empty($fullname)) {
                                                                                                                            echo $fullname;
                                                                                                                        } ?>">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email">Phone *</label>
                            <input type="text" name="phone" class="form-control" id="phone" required value="<?php if (!empty($phone)) {
                                                                                                                echo $phone;
                                                                                                            } ?>">
                        </div>
                        <div class="mb-3">
                            <label for="email">Email Address *</label>
                            <input type="email" name="email" class="form-control" id="email" required value="<?php if (!empty($email)) {
                                                                                                                    echo $email;
                                                                                                                } ?>">
                        </div>
                        <div class="mb-3">
                            <label for="provinceSelect">Province *</label>
                            <select class="form-control" id="province" required>
                                <option value="">Select Province</option>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="districtSelect">District *</label>
                                <select class="form-control" id="district" required>
                                    <option value="">Select District</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="wardSelect">Ward *</label>
                                <select class="form-control" id="ward" required>
                                    <option value="">Select Ward</option>
                                </select>
                            </div>
                        </div>
                        <div class=" mb-3">
                            <label for="address">Address *</label>
                            <input type="text" name="address" class="form-control" id="address" required value="<?php if (!empty($address)) {
                                                                                                                    echo $address;
                                                                                                                } ?>">
                        </div>
                        <div>
                            <input type="hidden" id="selectedProvince" name="province">
                            <input type="hidden" id="selectedDistrict" name="district">
                            <input type="hidden" id="selectedWard" name="ward">
                        </div>
                        <hr class="mb-4">
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 mb-3">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="odr-box">
                                <div class="title-left">
                                    <h3>Shopping cart</h3>
                                </div>
                                <?php
                                if (!empty($cartItems)) {
                                    foreach ($cartItems as $item) {
                                ?>
                                        <div class="rounded p-2 bg-light">
                                            <div class="media mb-2 border-bottom">
                                                <div class="media-body"><?php echo $item['product_name'] ?> | <?php echo $item['product_color'] ?> | <?php echo $item['product_memory_ram'] ?></a>
                                                    <div class="small text-muted">Price: $<?php echo $item['product_price']; ?> <span class="mx-2">|</span> Qty: <?php echo $item['quantity']; ?> <span class="mx-2">|</span> Subtotal: $<?php echo $item['total'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="order-box">
                                <div class="title-left">
                                    <h3>Your order</h3>
                                </div>
                                <div class="d-flex">
                                    <div class="font-weight-bold">Product</div>
                                    <div class="ml-auto font-weight-bold">Total</div>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex">
                                    <h4>Discount</h4>
                                    <div class="ml-auto font-weight-bold"> </div>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex">
                                    <h4>Coupon Discount</h4>
                                    <div class="ml-auto font-weight-bold">$- <?php echo $amountDiscount; ?></div>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex">
                                    <h4>Shipping Cost</h4>
                                    <div class="ml-auto font-weight-bold" id="shippingCost"> </div>
                                </div>
                                <hr>
                                <div class="d-flex gr-total">
                                    <h5>Grand Total</h5>
                                    <div class="ml-auto h5" id="totalPrice">$ <?php echo isset($giadagiam) ? max(0, ($totalPrice - $amountDiscount)) : $totalPrice; ?></div>
                                    <input type="hidden" name="total_order" id="finalTotalPrice" value="">
                                </div>
                                <hr>
                            </div>
                        </div>
                        <input type="hidden" name="payment_method" id="payment_method" value="">
                        <input type="hidden" name="status_payment" id="status_payment" value="">
                        <input type="hidden" name="paypal_id" id="paypal_id" value="">
                        <div class="col-12 d-flex shopping-box"><button type="submit" class="btn btn-danger cod" onclick="setPaymentMethod()">Confirm and place order | COD</button></div>
                        <div class="col-12 d-flex shopping-box" id='paypal-button-container'></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- End Cart -->

<!-- End Cart -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.26.1/axios.min.js" integrity="sha512-bPh3uwgU5qEMipS/VOmRqynnMXGGSRv+72H/N260MQeXZIK4PG48401Bsby9Nq5P5fz7hy5UGNmC/W1Z51h2GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="js/address.js"></script>
<script>
    $(document).ready(function() {
        var provinceSelected = false;
        var districtSelected = false;
        var wardSelected = false;
        var originalTotalPrice = <?php echo $totalPrice; ?>;

        $("#province").change(function() {
            provinceSelected = true;
            resetTotalOrder();
            trySendAjax();
        });

        $("#district").change(function() {
            districtSelected = true;
            resetTotalOrder();
            trySendAjax();
        });

        $("#ward").change(function() {
            wardSelected = true;
            resetTotalOrder();
            trySendAjax();
        });

        function trySendAjax() {
            if (provinceSelected && districtSelected && wardSelected) {
                calculateShipping();
            }
        }

        function resetTotalOrder() {
            $("#finalTotalPrice").val(originalTotalPrice.toFixed(2));
        }

        // Function to calculate shipping
        function calculateShipping() {
            var provinceId = $("#selectedProvince").val();
            var districtId = $("#selectedDistrict").val();
            var wardId = $("#selectedWard").val();

            var requestData = {
                "pick_province": "Hồ Chí Minh",
                "pick_district": "Quận 3",
                "pick_ward": "Phường Võ Thị Sáu",
                "pick_street": "391a Đường Nam Kỳ Khởi Nghĩa",
                "province": provinceId,
                "district": districtId,
                "ward": wardId,
                "weight": 500,
                "transport": "road",
                "deliver_option": "xteam",
                "tags": [1]
            };

            // Gửi yêu cầu Ajax tới API của GHTK
            $.ajax({
                url: "admin/process-shipping-method.php", // Đường dẫn tới mã xử lý server
                method: "POST",
                data: requestData,
                success: function(response) {
                    var jsonResponse = JSON.parse(response);

                    // Extract fee from the JSON response (in VND)
                    var shippingFeeVND = jsonResponse.fee.fee;

                    // Convert VND to USD using the exchange rate (e.g., 1 USD = 23000 VND)
                    var exchangeRate = 23000;
                    var shippingFeeUSD = shippingFeeVND / exchangeRate;

                    // Display the shipping fee in USD in the specified <div>
                    $("#shippingCost").text("$" + shippingFeeUSD.toFixed(2));

                    // Update the grand total including shipping fee
                    var totalOrder = parseFloat($("#finalTotalPrice").val());
                    var grandTotal = totalOrder + shippingFeeUSD - <?php echo $amountDiscount; ?>;
                    if (grandTotal < 0) {
                        grandTotal = 0;
                    }
                    $("#totalPrice").text("$ " + grandTotal.toFixed(2));

                    // Calculate the total order value including shipping fee
                    var totalOrderWithShipping = totalOrder + shippingFeeUSD - <?php echo $amountDiscount; ?>;
                    if (totalOrderWithShipping < 0) {
                        totalOrderWithShipping = 0;
                    }
                    // Update the input value with the total order value including shipping fee
                    $("#finalTotalPrice").val(totalOrderWithShipping.toFixed(2));
                }
            });
        }
        calculateShipping();
    });

    function validateForm() {
        var username = document.getElementById("username").value;
        var phone = document.getElementById("phone").value;
        var email = document.getElementById("email").value;
        var province = document.getElementById("province").value;
        var district = document.getElementById("district").value;
        var ward = document.getElementById("ward").value;
        var address = document.getElementById("address").value;
        if (username === "" || phone === "" || email === "" || province === "" || district === "" || ward === "" || address === "") {
            alert("All fields are required.");
            return false;
        }
        var phonePattern = /^[0-9]{10}$/;
        if (!phone.match(phonePattern)) {
            alert("Phone must be a 10-digit numeric value.");
            return false;
        }

        var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!email.match(emailPattern)) {
            alert("Please enter a valid email address.");
            return false;
        }

        return true;
    }

    document.getElementById('payment_method').value = 'PayPal';
    document.getElementById('status_payment').value = 'uncompleted';

    function setPaymentMethod() {
        document.getElementById('payment_method').value = 'COD';
    }

    paypal.Buttons({
        onClick: function(details) {
            var username = document.getElementById("username").value;
            var phone = document.getElementById("phone").value;
            var email = document.getElementById("email").value;
            var province = document.getElementById("province").value;
            var district = document.getElementById("district").value;
            var ward = document.getElementById("ward").value;
            var address = document.getElementById("address").value;
            if (username === "" || phone === "" || email === "" || province === "" || district === "" || ward === "" || address === "") {
                alert("All fields are required.");
                return false;
            }
            var phonePattern = /^[0-9]{10}$/;
            if (!phone.match(phonePattern)) {
                alert("Phone must be a 10-digit numeric value.");
                return false;
            }

            var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!email.match(emailPattern)) {
                alert("Please enter a valid email address.");
                return false;
            }

            return true;
        },
        createOrder: function(data, actions) {
            var paypalAmount = parseFloat(document.getElementById('finalTotalPrice').value);
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: paypalAmount
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(orderData) {
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
                document.getElementById('status_payment').value = 'completed';
                document.getElementById("order-form").submit();
            });
        }
    }).render('#paypal-button-container');
</script>
<?php
include "footer.php";
?>