<?php
$totalPrice = 0;
if (isset($_SESSION["id"])) {
    $user_id = $_SESSION['id'];
} else {
    $user_id = -1;
}
$database = new Database();
$cart_query = "SELECT * FROM tbl_cart where user_id = $user_id ORDER BY cart_id DESC";
$cart_result = $database->select($cart_query);
if ($cart_result) {
    while ($row = $cart_result->fetch_assoc()) {
        $cartItems[] = array(
            'cart_id' => $row['cart_id'],
            'product_id' => $row['product_id'],
            'product_name' => $row['product_name'],
            'product_price' => $row['product_price'],
            'product_color' => $row['product_color'],
            'product_memory_ram' => $row['product_memory_ram'],
            'quantity' => $row['quantity'],
            'total' => $row['total'],
            'product_img' => $row['product_img']
        );
        $totalPrice += $row['total'];
    }
}
$count_query  = "SELECT COUNT(*) as total_items FROM tbl_cart WHERE user_id = $user_id ORDER BY cart_id DESC";
$count_result = $database->select($count_query);
if ($count_result) {
    $count_row = $count_result->fetch_assoc();
    $total_items = $count_row['total_items'];
} else {
    $total_items = 0;
}

$result = '';
if (isset($_SESSION["add_to_cart_result"])) {
    $result = $_SESSION["add_to_cart_result"];
    unset($_SESSION["add_to_cart_result"]);
        echo "<script>
                alert('$result');
            </script>";
    }
?>

<!-- Start Main Top -->
<header class="main-header">
    <!-- Start Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
        <div class="container">
            <!-- Start Header Navigation -->
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu" aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.php"><img src="image/logocr.png" class="logo" alt="" width="90px" height="80px"></a>
            </div>
            <!-- End Header Navigation -->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="product.php">Shop</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="product-detail.php">Sale</a></li> -->
                    <li class="nav-item"><a class="nav-link" href="wishlist.php">Wishlist</a></li>
                    <li class="nav-item"><a class="nav-link" href="survey.php">Survey</a></li>
                    <li class="nav-item"><a class="nav-link" href="blog.php">Blog</a></li>
                    <li class="dropdown">
                        <a href="shop.php" class="nav-link dropdown-toggle arrow" data-toggle="dropdown">About Shop</a>
                        <ul class="dropdown-menu">
                            <li><a href="contact-us.php">Contact US</a></li>
                            <li><a href="about.php">About US</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
            <!-- Start Atribute Navigation -->
            <div class="attr-nav">
                <ul>
                    <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                    <li class="side-menu"><a href="cart.php">
                            <i class="fa fa-shopping-bag"></i>
                            <span class="badge"><?php echo $total_items; ?></span>
                        </a></li>
                </ul>
            </div>
            <!-- End Atribute Navigation -->
        </div>
        <!-- Start Side Menu -->
        <div class="side">
            <a href="#" class="close-side"><i class="fa fa-times"></i></a>
            <li class="cart-box">
                <ul class="cart-list">
                    <?php
                    if (!empty($cartItems)) {
                        foreach ($cartItems as $item) {
                    ?>
                            <li>
                                <a href="#" class="photo"><img src="admin/uploads/<?php echo $item['product_img']; ?>" class="cart-thumb" alt="" /></a>
                                <h6><a href="#"><?php echo $item['product_name'] ?></a></h6>
                                <p><?php echo $item['quantity']; ?>x - <span class="price">$<?php echo number_format($item['product_price']); ?></span></p>
                            </li>
                    <?php
                        }
                    } else {
                        echo '<li class="empty-cart">Cart is empty!</li>';
                    }
                    ?>
                    <li class="total">
                        <a href="cart.php" class="btn btn-default hvr-hover btn-cart">VIEW CART</a>
                        <span class="float-right"><strong>Total</strong>: $<?php echo number_format($totalPrice) ?></span>
                    </li>
                </ul>
            </li>
        </div>
        <!-- End Side Menu -->
    </nav>
    <!-- End Navigation -->
</header>
<!-- End Main Top -->
<!-- Start Top Search -->
<div class="top-search">
    <div class="container">
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Search">
            <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
        </div>
    </div>
</div>
<!-- End To
