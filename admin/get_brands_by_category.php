

<?php
include "class/product_class.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cartegory_id'])) {
    $product = new product;
    $cartegory_id = $_POST['cartegory_id'];
    $brands = $product->get_brands_by_category($cartegory_id);
    if ($brands) {
        while ($brand = $brands->fetch_assoc()) {
            echo '<option value="' . $brand['brand_id'] . '">' . $brand['brand_name'] . '</option>';
        }
    }
}
?>