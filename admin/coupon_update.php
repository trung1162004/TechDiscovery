<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/coupon_class.php";

$coupon_data = null;
$error_message = '';

$coupon = new coupon();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coupon_id = $_POST['coupon_id'];
    $code = $_POST['code'];
    $amount = $_POST['amount'];
    $expiry_date = $_POST['expiry_date'];
    $quantity_coupon = $_POST['quantity_coupon'];

    $result = $coupon->update_coupon($coupon_id, $code, $amount, $expiry_date, $quantity_coupon);

    if ($result) {
        // Cập nhật thành công, chuyển hướng người dùng về trang danh sách coupon
        echo "<script>window.location.href = 'coupon.php';</script>";
        exit(); // Đảm bảo kết thúc luồng xử lý sau khi chuyển hướng
    } else {
        $error_message = "Update failed.";
    }
} elseif (isset($_GET['coupon_id'])) {
    $coupon_id = $_GET['coupon_id'];
    $coupon_data = $coupon->get_coupon_by_id($coupon_id);
    //print_r($coupon_data);
    //$coupon_data->fetch_all(MYSQLI_ASSOC);
}

?>
<style>
body, h1, form {
    margin: 0;
    padding: 0;
}
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h1 {

margin-bottom: 20px;
color: red;
text-align: center;

}

form {
display: flex;
flex-direction: column;
}
.coupon-form {
    background-color: #f5f5f5;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
}

label {
    font-weight: bold;
    margin-bottom: 5px;
}

input[type="text"],
input[type="number"],
input[type="datetime-local"] {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

input[type="submit"] {
    background-color: #3498db;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #2980b9;
}
</style>
<link rel="stylesheet" type="text/css" href="styles.css">

<body>
<div class="container">
    <h1>Update Coupon</h1>
   
    <?php if ($error_message) : ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form action="" method="post" id="update-form" onsubmit="return validateForm()">
        <input type="hidden" name="coupon_id" value="<?php echo $coupon_data['coupon_id']; ?>">

        <label for="code">Code:</label>
        <input type="text" id="code" name="code" value="<?php echo $coupon_data['code']; ?>"><br>

        <label for="amount">Discount Amount:</label>
        <input type="number" id="amount" name="amount" value="<?php echo $coupon_data['amount']; ?>" min="0"><br>

        <label for="expiry_date">Expiry Date:</label>
        <input type="datetime-local" id="expiry_date" name="expiry_date" value="<?php echo ($coupon_data['expiry_date']) ?>"><br>

        <label for="quantity_coupon">Quantity:</label>
        <input type="number" id="quantity_coupon" name="quantity_coupon" value="<?php echo ($coupon_data['quantity_coupon']) ?>" min="0"><br>

        <input type="submit" value="Update Coupon">
    </form>
</div>
</body>
<script>
function validateForm() {
    var code = document.getElementById("code").value;
    var amount = document.getElementById("amount").value;
    var expiry_date = document.getElementById("expiry_date").value;
    var quantity_coupon = document.getElementById("quantity_coupon").value;

    if (code === "" && amount === "" && expiry_date === "" && quantity_coupon === "") {
        alert("Please fill in at least 1 information.");
        return false;
    }
}
</script>

<?php
    include "footer.php";
?>
