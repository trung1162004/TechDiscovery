<?php
include "header.php";
include "navbar.php";

if (isset($_SESSION["id"])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = -1;
}
$database = new Database();
$wishlist_query = "SELECT * FROM tbl_wishlist where user_id = $user_id ORDER BY wishlist_id DESC";
$wishlist_result = $database->select($wishlist_query);
if ($wishlist_result) {
    while ($row = $wishlist_result->fetch_assoc()) {
        $wishlistItems[] = array(
            'wishlist_id' => $row['wishlist_id'],
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'product_price' => $row['product_price'],
            'product_color' => $row['product_color'],
            'product_memory_ram' => $row['product_memory_ram'],
            'quantity' => $row['quantity'],
            'total' => $row['total'],
            'product_img' => $row['product_img']
        );
    }
}
?>

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Wishlist</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active">Wishlist</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Wishlist  -->
<div class="wishlist-box-main">
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
                                <th>Action</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <?php
                        if (!empty($wishlistItems)) {
                            foreach ($wishlistItems as $item) {
                        ?>
                                <tbody>
                                    <tr>
                                        <form action="admin/process-addWishlistToCart.php" method="post">
                                            <td class="thumbnail-img">
                                                <img class="img-fluid" src="admin/uploads/<?php echo $item['product_img']; ?>" alt=""/>
                                                <input type="hidden" value="<?php echo $item['product_id']; ?>" name="product_id">
                                                <input type="hidden" value="<?php echo $item['wishlist_id']; ?>" name="wishlist_id">
                                                <input type="hidden" value="<?php echo $item['product_img']; ?>" name="product_img">
                                            </td>
                                            <td class="name-pr">
                                                <b><?php echo $item['product_name'] ?></b>
                                                <input type="hidden" value="<?php echo $item['product_name']; ?>" name="product_name">
                                            </td>
                                            <td class="name-pr">
                                                <b><?php echo $item['product_color'] ?></b>
                                                <input type="hidden" value="<?php echo $item['product_color']; ?>" name="product_color">
                                            </td>
                                            <td class="name-pr">
                                                <b><?php echo $item['product_memory_ram'] ?></b>
                                                <input type="hidden" value="<?php echo $item['product_memory_ram']; ?>" name="product_memory_ram">
                                            </td>
                                            <td class="price-pr">
                                                <p>$ <?php echo number_format($item['product_price']); ?></p>
                                                <input type="hidden" value="<?php echo $item['product_price']; ?>" name="product_price">
                                            </td>
                                            <td class="quantity-box">
                                                <input name="quantity" type="number" size="4" value="<?php echo $item['quantity']; ?>" min="1" step="1" class="c-input-text qty text quantity-input" data-cart-id="<?php echo $item['wishlist_id']; ?>" data-product-price="<?php echo $item['product_price']; ?>" id="quantity-<?php echo $item['wishlist_id']; ?>" oninput="updateQuantity(this)" onkeydown="return false">
                                            </td>
                                            <td class="total-pr">
                                                <p id="total-<?php echo $item['wishlist_id']; ?>">$ <?php echo $item['total'] ?></p>
                                                <input type="hidden" value="<?php echo $item['total']; ?>" name="total">
                                            </td>
                                            <td class="add-pr"> 
                                                <button type="submit" class="btn hvr-hover">Add to Cart</button>
                                            </td>
                                        </form>
                                        <td class="remove-pr">
                                            <div class="button-remove" onclick="confirmDelete(<?php echo $item['wishlist_id'] ?>)">
                                                <i class="fas fa-times"></i>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                        <?php
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Wishlist -->
<script>
    function confirmDelete(wishlistId) {
        var confirmation = confirm("Are you sure you want to delete this product from the wishlist?");
        if (confirmation) {
            window.location.href = "admin/process-wishlist-delete.php?id=" + wishlistId;
        } else {

        }
    }

    function updateQuantity(input) {
        const cartId = input.getAttribute("data-cart-id");
        const newQuantity = input.value;
        const productPrice = input.getAttribute("data-product-price");
        const totalField = document.getElementById("total-" + cartId);

        const newTotal = newQuantity * productPrice;
        totalField.textContent = "$ " + newTotal.toFixed(2);

        // Update the database via Ajax
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "admin/process-wishlist-edit.php?id=" + cartId, true);
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
    }
</script>
<?php
include "footer.php";
?>