<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/coupon_class.php"
?>
<?php
$coupon = new coupon();
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['coupon_id'])) {
    $coupon_id = $_GET['coupon_id'];
    $coupon->delete_coupon($coupon_id);
    echo "<script>window.location.href = 'coupon.php';</script>";
    exit;
}
    $coupons = $coupon->show_coupon(); 
?>
<link rel="stylesheet" type="text/css" href="styles.css">
<style>
    .container {
        max-width: 1260px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: #3300FF;
    }

    h3 {
        text-align: right;
        margin-right: 30px;
    }

    table.coupon-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .expired {
        color: red; /* Đã hết hạn: Màu chữ đỏ */
    }

    .out-of-stock {
        color: red; /* Hết hàng: Màu chữ xám */
    }

    .btn-update,
    .btn-delete {
        display: inline-block;
        padding: 5px 10px;
        text-decoration: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-update {
        background-color: #3498db;
        color: white;
    }

    .btn-delete {
        background-color: #e74c3c;
        color: white;
        margin-left: 10px;
    }

    .btn-update:hover,
    .btn-delete:hover {
        background-color: #2980b9;
    }
</style>

<div class="container">
    <h1>Coupons List</h1><br>
    <h3><a href="coupon_create.php">Create coupon</a></h3>
    <table class="coupon-table" border="1">
        <tr>
            <th>STT</th>
            <th>Code</th>
            <th>Discount Amount</th>
            <th>Expiry Date</th>
            <th>Quantity</th>
            <th>Action</th>
        </tr>
        <?php  $stt = 1; foreach ($coupons as $row) : ?>
            <?php
                $expiry_date = $row['expiry_date'];
                $expiry_date = $row['expiry_date']; // Lấy ngày/giờ từ cơ sở dữ liệu
                $expiry_datetime = DateTime::createFromFormat('Y-m-d H:i:s', $expiry_date, new DateTimeZone('Asia/Ho_Chi_Minh'));
                $current_datetime = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                 // Lấy thời gian hiện tại

if ($expiry_datetime < $current_datetime) {
    $expired_class = 'expired'; // Đã hết hạn
} else {
    $expired_class = ''; // Còn hiệu lực
}
                $quantity_coupon = $row['quantity_coupon'];
                $out_of_stock_class = ($quantity_coupon <= 0) ? 'out-of-stock' : '';
            ?>
            <tr>
                <td><?php echo $stt++; ?></td>
                <td><?php echo $row['code']; ?></td>
                <td><?php echo $row['amount']; ?></td>
                <td class="<?php echo $expired_class; ?>"><?php echo $row['expiry_date']; ?></td>
                <td class="<?php echo $out_of_stock_class; ?>"><?php echo $quantity_coupon; ?></td>
                <td>
                <a class="btn-update" href="coupon_update.php?coupon_id=<?php echo $row['coupon_id']; ?>">Update</a>

                    <a class="btn-delete" href="coupon.php?action=delete&coupon_id=<?php echo $row['coupon_id']; ?>" onclick="return confirm('Are you sure you want to delete this coupon?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php
    include "footer.php";
?>