<?php
include "header.php";
include "navbar.php";
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Cart</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Cart  -->
<div class="cart-box-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-main table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Images</th>
                                <th>Product Name</th>
                                <th>Color</th>
                                <th>Memory Storage</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($cartItems)) {
                                foreach ($cartItems as $item) {
                                    $cart_id = $item["cart_id"];
                                    $quantity = $item["quantity"];
                            ?>
                                    <tr>
                                        <td class="thumbnail-img">
                                            <img class="img-fluid" src="admin/uploads/<?php echo $item['product_img']; ?>" alt="" />
                                        </td>
                                        <td class="name-pr">
                                            <b><?php echo $item['product_name'] ?></b>
                                        </td>
                                        <td class="name-pr">
                                            <b><?php echo $item['product_color'] ?></b>
                                        </td>
                                        <td class="name-pr">
                                            <b><?php echo $item['product_memory_ram'] ?></b>
                                        </td>
                                        <td class="price-pr">
                                            <p>$ <?php echo $item['product_price'] ?></p>
                                        </td>
                                        <td class="quantity-box">
                                            <input type="number" size="4" value="<?php echo $item['quantity']; ?>" min="1" step="1" class="c-input-text qty text quantity-input" data-cart-id="<?php echo $item['cart_id']; ?>" data-product-price="<?php echo $item['product_price']; ?>" id="quantity-<?php echo $item['cart_id']; ?>" oninput="updateQuantity(this)" onkeydown="return false">
                                        </td>
                                        <td class="total-pr">
                                            <p id="total-<?php echo $item['cart_id']; ?>">$ <?php echo $item['total'] ?></p>
                                        </td>
                                        <td class="remove-pr">
                                            <div class="button-remove" onclick="confirmDelete(<?php echo $item['cart_id'] ?>)">
                                                <i class="fas fa-times"></i>
                                            </div>
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
        <!------------------------------ ma giam gia ----------------------------- -->
        <?php
        $couponAmount = 0;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $code = $_POST['code'];
            $database = new Database();

            // Kiểm tra mã giảm giá trong cơ sở dữ liệu
            $couponQuery = "SELECT * FROM coupon WHERE code = '$code'";
            $couponResult = $database->select($couponQuery);

            if (!$couponResult || $couponResult->num_rows === 0) {
                // Mã giảm giá không tồn tại, hiển thị thông báo cho người dùng
                echo "<p>Coupon code is invalid or does not exist.</p>";
            } else {
                // Xử lý logic khi mã giảm giá hợp lệ
                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $couponData = $couponResult->fetch_assoc();
                $expiryDate = $couponData['expiry_date'];
                $currentDate = date('Y-m-d H:i:s'); // Lấy thời gian hiện tại theo múi giờ của máy chủ

                // So sánh thời hạn với thời gian hiện tại
                if ($currentDate > $expiryDate) {
                    echo "<p>Coupon code has expired.</p>";
                } else {
                    // Kiểm tra số lượng mã giảm giá còn lớn hơn 0
                    $quantityQuery = "SELECT quantity_coupon FROM coupon WHERE code = '$code'";
                    $quantityResult = $database->select($quantityQuery);

                    if (!$quantityResult || $quantityResult->num_rows === 0) {
                        echo "<p>Coupon code is invalid or does not exist.</p>";
                    } else {
                        $couponQuantityData = $quantityResult->fetch_assoc();
                        $quantity_coupon = $couponQuantityData['quantity_coupon'];

                        if ($quantity_coupon > 0) {
                            // Xử lý logic khi mã giảm giá hợp lệ và số lượng > 0
                            $couponAmount = $couponData['amount'];

                            // Giảm số lượng mã giảm giá đi 1
                            // $updatedQuantity = $quantity - 1;
                            // $updateQuantityQuery = "UPDATE coupon SET quantity = $updatedQuantity WHERE code = '$code'";
                            // $database->update($updateQuantityQuery);
                        } else {
                            echo "<p>Coupon code is no longer available.</p>";
                        }
                    }
                }
            }
        }
        if ($totalPrice === 0) {
            echo "<p>You cannot use the coupon code as there are no products in your cart.</p>";
            $couponAmount = $totalPrice;
        } else {
            $giadagiam = $totalPrice - $couponAmount;
            // Lưu giá trị vào session
            $_SESSION['amountDiscount'] = $couponAmount;
            $_SESSION['giadagiam'] = $giadagiam;
            if ($couponAmount !== 0) {
                $_SESSION['code'] = $code;
            }
        } ?>
        <form action="cart.php" method="POST">
            <div class="row my-5">
                <div class="col-lg-6 col-sm-6">
                    <div class="coupon-box">
                        <div class="input-group input-group-sm">
                            <input class="form-control" placeholder="Enter your coupon code" aria-label="Coupon code" type="text" name="code">
                            <div class="input-group-append">
                                <button class="btn btn-theme" type="submit">Apply Coupon</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- ---------------------------------------end ma giảm giá -------------------------------------- -->
        <div class="row my-5">
            <div class="col-lg-8 col-sm-12"></div>
            <div class="col-lg-4 col-sm-12">
                <div class="order-box">
                    <h3>Order summary</h3>
                    <div class="d-flex">
                        <h4>Discount</h4>
                        <div class="ml-auto font-weight-bold"> $ 0</div>
                    </div>
                    <hr class="my-1">
                    <div class="d-flex">
                        <h4>Coupon Discount</h4>
                        <div class="ml-auto font-weight-bold">$- <?php echo isset($couponAmount) ? $couponAmount : 0 ?></div>
                    </div>
                    <hr>
                    <div class="d-flex gr-total">
                        <h5>Grand Total</h5>
                        <div class="ml-auto h5" id="grand-total"> $ <?php echo $couponAmount !== 0 ? max(0, ($totalPrice - $couponAmount)) : $totalPrice; ?></div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col-12 d-flex shopping-box justify-content-end">
                <button class="btn btn-danger" onclick="checkProductQuantity(<?php echo $cart_id; ?>, <?php echo $quantity; ?>)">Checkout</button>
            </div>
        </div>

    </div>
    <!-- End Cart -->
</div>
<!-- End Cart -->
<script>
    function checkProductQuantity(cartId, quantity) {
        $.ajax({
            type: "POST",
            url: "admin/check_quantity.php",
            data: {
                cartId: cartId,
                quantity: quantity
            },
            success: function(response) {
                var parsedResponse = JSON.parse(response); // Parse phản hồi JSON
                if (parsedResponse.status === 'success') {
                    window.location.href = 'checkout.php';
                } else {
                    alert(parsedResponse.message);
                }
            }
        });
    }

    function confirmDelete(cartId) {
        var confirmation = confirm("Are you sure you want to delete this product from the cart?");
        if (confirmation) {
            window.location.href = "admin/process-cart-delete.php?id=" + cartId;
        } else {

        }
    }

    function updateQuantity(input) {
        const cartId = input.getAttribute("data-cart-id");
        const newQuantity = input.value;
        const productPrice = input.getAttribute("data-product-price");
        const totalField = document.getElementById("total-" + cartId);
        const grandTotalField = document.getElementById("grand-total");

        const newTotal = newQuantity * productPrice;
        totalField.textContent = "$ " + newTotal.toFixed(2);

        // Update the database via Ajax
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "admin/process-cart-edit.php?id=" + cartId, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.error) {
                    alert(response.message);
                    input.value = response.old_quantity;
                    totalField.textContent = "$ " + response.old_total;
                } else {
                    console.log(response.message);
                }
            }
        };
        xhr.send("quantity=" + newQuantity);
        updateGrandTotal();
    }

    function updateGrandTotal() {
        const totalFields = document.querySelectorAll(".total-pr p");
        let newGrandTotal = 0;

        totalFields.forEach(function(field) {
            const totalAmount = parseFloat(field.textContent.replace("$", "").trim());
            newGrandTotal += totalAmount;
        });

        const grandTotalField = document.getElementById("grand-total");
        grandTotalField.textContent = "$ " + newGrandTotal.toFixed(2);
    }
</script>
<?php
include "footer.php";
?>