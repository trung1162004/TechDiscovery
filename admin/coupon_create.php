<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include 'class/coupon_class.php';
?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $coupon_id = null; // Since ID is AUTO_INCREMENT, it will be automatically generated
    $code = $_POST['code'];
    $amount = $_POST['amount'];
    $expiry_date = $_POST['expiry_date'];
    $created_at = date('Y-m-d H:i:s');
    $quantity_coupon = $_POST['quantity_coupon'];
    $coupon = new coupon();

    $existing_coupon = $coupon->get_coupon_by_code($code); // Assume get_coupon_by_code() retrieves coupon by code
    
    if ($existing_coupon) {
        echo "Coupon code already exists.";
    } else {
        // Check if expiry date is in the future
        if (strtotime($expiry_date) <= time()) {
            // Expiry date is not in the future
            echo "Expiry date must be in the future.";
        } else {
            $coupon->insert_coupon($coupon_id, $code, $amount, $expiry_date, $created_at, $quantity_coupon);
            echo "<script>window.location.href = 'coupon.php';</script>";
            exit;
        }
    }
}
?>
<head>
<link rel="stylesheet" type="text/css" href="path/to/styles.css">
</head>
<style>
    /* Reset some default styles */
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

<head>
</head>
<body>
<div class="container">
    <h1>Create New Coupon</h1>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="code">Code:</label>
        <input type="text" id="code" name="code" required><br>

        <label for="amount">Discount Amount:</label>
        <input type="number" id="amount" name="amount" min="0" required><br>

        <label for="expiry_date">Expiry Date:</label>
        <input type="datetime-local" id="expiry_date" name="expiry_date" required><br>

        <label for="quantity_coupon">Quantity:</label>
        <input type="number" id="quantity_coupon" name="quantity_coupon" min="0" required><br>

        <input type="submit" value="Create Coupon">
        
    </form>
    </div>
</body >
<?php
    include "footer.php";
?>