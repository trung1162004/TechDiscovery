<?php
include "database.php";
$db = new Database;
if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $query = "select * from tbl_order_items where order_id = $order_id";
    $result = $db->select($query);
}

while ($order_item = $result->fetch_assoc()) {
    $order_item_id = $order_item['order_item_id'];
?>
    <div class="form-check">
        <input class="form-check-input" type="checkbox" name="products_to_return[]" value="<?php echo $order_item['order_item_id']; ?>">
        <label class="form-check-label">
            <?php echo $order_item['product_name']; ?>
            (Color: <?php echo $order_item['product_color']; ?>,
            Memory: <?php echo $order_item['product_memory_ram']; ?>,
            Quantity: <?php echo $order_item['quantity']; ?>,
            Price: $<?php echo $order_item['product_price']; ?>)
        </label>
        <div class="form-group return-details" style="display: none;">
            <label>Reason for return:</label>
            <textarea class="form-control" name="return_reasons[<?php echo $order_item['order_item_id']; ?>]" rows="3"></textarea>
        </div>
        <div class="form-group return-details" style="display: none;">
            <label>Upload image:</label>
            <input type="file" class="form-control-file" name="return_images[<?php echo $order_item['order_item_id']; ?>]" multiple>
        </div>
    </div>
<?php
}
?>

