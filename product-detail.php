<?php
include "header.php";
include "navbar.php";
include "admin/class/product_class.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);
$product = new product();

$_SESSION["product_page_url"] = $_SERVER['REQUEST_URI'];


if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    $productDetail = $product->get_product($product_id);

    if ($productDetail) {
        $detail = $productDetail->fetch_assoc();

?>
        <!-- Start All Title Box -->
        <div class="all-title-box">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h2>Product Detail</h2>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">TechDiscovery</a></li>
                            <li class="breadcrumb-item active">Product Detail</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- End All Title Box -->

        <!-- Start Shop Detail  -->
        <div class="shop-detail-box-main">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-5 col-md-6">
                        <div id="carousel-example-1" class="single-product-slider carousel slide" data-ride="carousel">
                            <div class="carousel-inner" role="listbox">
                                <?php
                                $firstImage = true; // Đánh dấu phần tử đầu tiên là active
                                $product_imgs_desc = $product->get_product_imgs_desc($product_id);

                                if ($product_imgs_desc) {
                                    while ($img_row = $product_imgs_desc->fetch_assoc()) {
                                        $activeClass = $firstImage ? "active" : "";
                                        $imagePath = 'admin/uploads/' . $img_row['product_img_desc'];

                                        echo '<div class="carousel-item ' . $activeClass . '">';
                                        echo '<img class="d-block w-100" src="' . $imagePath . '" />';
                                        echo '</div>';

                                        $firstImage = false;
                                    }
                                }
                                ?>
                            </div>

                            <!-- Các điều khiển trượt -->
                            <a class="carousel-control-prev" href="#carousel-example-1" role="button" data-slide="prev">
                                <i class="fa fa-angle-left" aria-hidden="true"></i>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carousel-example-1" role="button" data-slide="next">
                                <i class="fa fa-angle-right" aria-hidden="true"></i>
                                <span class="sr-only">Next</span>
                            </a>
                            <!-- Điểm điều hướng -->
                            <ol class="carousel-indicators">
                                <?php
                                $currentSlide = 0;
                                $product_imgs_desc->data_seek(0); // Đặt con trỏ dữ liệu trở lại vị trí đầu tiên

                                while ($img_row = $product_imgs_desc->fetch_assoc()) {
                                    $activeClass = ($currentSlide === 0) ? "active" : "";

                                    echo '<li data-target="#carousel-example-1" data-slide-to="' . $currentSlide . '" class="' . $activeClass . '">';
                                    echo '<img class="d-block w-100 img-fluid" src="admin/uploads/' . $img_row['product_img_desc'] . '" alt="" />';
                                    echo '</li>';

                                    $currentSlide++;
                                }
                                ?>
                            </ol>
                        </div>
                    </div>
                    <div class="col-xl-7 col-lg-7 col-md-6">
                        <div class="single-product-details">
                            <!-- Hiển thị tên sản phẩm -->
                            <h2><?php echo $detail['product_name']; ?></h2>
                            <!-- Hiển thị giá sản phẩm -->
                            <h5> <del>$<?php echo $detail['product_price']; ?></del> $<?php echo $detail['product_price_sale']; ?></h5>
                            <!-- Hiển thị số lượng tồn kho và số lượng đã bán -->
                            <p class="available-stock">
                                <span>Stock: <?php echo $detail['product_quantity']; ?> </span>
                            </p>

                            <!-- Hiển thị tùy chọn màu sắc -->
                            <div class="form-group size-st">
                                <label class="size-label">Color</label>
                                <select id="basic" class="selectpicker show-tick form-control">
                                    <!-- Bạn có thể thay đổi mã này để hiển thị tùy chọn màu sắc -->
                                    <?php
                                    // Lấy danh sách color_id từ trường product_color
                                    $product_color_ids = explode(',', $detail['product_color']);

                                    // Lặp qua danh sách color_id và chuyển thành color_name
                                    foreach ($product_color_ids as $color_id) {
                                        $color_name = $product->get_color_name_by_id($color_id);
                                        echo '<option value="' . $color_name . '">' . $color_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Hiển thị tùy chọn dung lượng -->
                            <div class="form-group size-st">
                                <label class="size-label">Memory-Capacity</label>
                                <select id="basic2" class="selectpicker show-tick form-control">
                                    <!-- Bạn có thể thay đổi mã này để hiển thị tùy chọn dung lượng -->
                                    <?php
                                    // Lấy danh sách memory_ram_id từ trường product_memory_ram
                                    $product_memory_ram_ids = explode(',', $detail['product_memory_ram']);

                                    // Lặp qua danh sách memory_ram_id và chuyển thành memory_ram_name
                                    foreach ($product_memory_ram_ids as $memory_ram_id) {
                                        $memory_ram_name = $product->get_memory_ram_name_by_id($memory_ram_id);
                                        echo '<option value="' . $memory_ram_name . '">' . $memory_ram_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <!-- Hiển thị số lượng sản phẩm để mua -->
                            <div class="form-group quantity-box">
                                <label class="control-label">Quantity</label>
                                <input class="form-control" value="1" min="1" max="20" type="number" name="quantity" step="1" onkeydown="return false">
                            </div>

                            <!-- Hiển thị nút mua hàng và thêm vào giỏ hàng -->
                            <div class="price-box-bar">
                                <div class="cart-and-bay-btn">
                                    <form action="admin/process_buynow.php" method="post" class="price-box-bar">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $detail['product_name'] ?>">
                                        <input type="hidden" name="product_img" value="<?php echo $detail['product_img'] ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $detail['product_price_sale'] ?>">
                                        <input type="hidden" name="product_color" class="product_color" value="<?php echo $color_name ?>">
                                        <input type="hidden" name="product_memory_ram" class="product_memory_ram" value="<?php echo $memory_ram_name ?>">
                                        <input value="1" min="1" max="20" type="hidden" class="product_quantity" name="product_quantity">
                                        <button type="submit" class="btn hvr-hover btn-danger">Buy Now</button>
                                    </form>
                                    <form action="admin/process-addToCart.php" method="post" class="price-box-bar">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $detail['product_name'] ?>">
                                        <input type="hidden" name="product_img" value="<?php echo $detail['product_img'] ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $detail['product_price_sale'] ?>">
                                        <input type="hidden" name="product_color" class="product_color" value="<?php echo $color_name ?>">
                                        <input type="hidden" name="product_memory_ram" class="product_memory_ram" value="<?php echo $memory_ram_name ?>">
                                        <input value="1" min="1" max="20" type="hidden" class="product_quantity" name="product_quantity">
                                        <button type="submit" class="btn hvr-hover btn-danger">Add to cart</button>
                                    </form>
                                    <form action="admin/process-addToWishlist.php" method="post" class="price-box-bar">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                                        <input type="hidden" name="product_name" value="<?php echo $detail['product_name'] ?>">
                                        <input type="hidden" name="product_img" value="<?php echo $detail['product_img'] ?>">
                                        <input type="hidden" name="product_price" value="<?php echo $detail['product_price_sale'] ?>">
                                        <input type="hidden" name="product_color" class="product_color" value="<?php echo $color_name ?>">
                                        <input type="hidden" name="product_memory_ram" class="product_memory_ram" value="<?php echo $memory_ram_name ?>">
                                        <input value="1" min="1" max="20" type="hidden" class="product_quantity" name="product_quantity">
                                        <button type="submit" class="btn hvr-hover btn-danger"><i class="fas fa-heart"></i> Add to wishlist</button>
                                    </form>
                                </div>
                            </div>

                            <!-- Hiển thị nút thêm vào danh sách yêu thích và chia sẻ -->
                            <div class="add-to-btn">
                                <div class="share-bar">
                                    <a class="btn hvr-hover" href="https://www.facebook.com/groups/1249874295731488"><i class="fab fa-facebook" aria-hidden="true"></i></a>
                                    <a class="btn hvr-hover" href="#"><i class="fab fa-google-plus" aria-hidden="true"></i></a>
                                    <a class="btn hvr-hover" href="#"><i class="fab fa-twitter" aria-hidden="true"></i></a>
                                    <a class="btn hvr-hover" href="#"><i class="fab fa-whatsapp" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br><br>

            <!-- Hiển thị thông tin sản phẩm và các tab tương ứng -->
            <div class="product-content-right-bottom">
                <div class="product-content-right-bottom-top">
                    <span>&#812;</span>
                </div>
                <div class="product-content-right-bottom-content-big">
                    <div class="product-content-right-bottom-content-title row">
                        <div class="product-content-right-bottom-content-title-item introduce">
                            <p>Introduce</p>
                        </div>
                        <div class="product-content-right-bottom-content-title-item detail">
                            <p>Detail</p>
                        </div>
                        <div class="product-content-right-bottom-content-title-item accessory">
                            <p>Accessory</p>
                        </div>
                        <div class="product-content-right-bottom-content-title-item guarantee">
                            <p>Guarantee</p>
                        </div>
                    </div>
                    <div class="product-content-right-bottom-content">
                        <!-- Hiển thị phần giới thiệu -->
                        <div class="product-content-right-bottom-content-introduce active">
                            <h4><?php echo $detail['product_intro']; ?></h4>
                        </div>

                        <!-- Hiển thị phần chi tiết -->
                        <div class="product-content-right-bottom-content-detail">
                            <h4><?php echo $detail['product_detail']; ?></h4>
                        </div>

                        <!-- Hiển thị phần phụ kiện -->
                        <div class="product-content-right-bottom-content-accessory">
                            <h4><?php echo $detail['product_accessory']; ?></h4>
                        </div>

                        <!-- Hiển thị phần bảo hành -->
                        <div class="product-content-right-bottom-content-guarantee">
                            <h4><?php echo $detail['product_guarantee']; ?> <span><a href="" style="color: rgb(105, 159, 235);">(Xem chi tiết)</a></span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
    } else {
        echo '<p>No products found.</p>';
    }
} else {
    echo '<p>No product ID provided.</p>';
}
?>
<br><br><br><br>

<!-- ---------------danh gia ------------------- -->
<?php
include "danhgia.php"
?>
<!-- ------------------end danh gia -------------------- -->


<!-- Phần hiển thị sản phẩm tương tự -->
<div class="row my-5">
    <div class="col-lg-12">
        <div class="title-all text-center">
            <h1>Similar products</h1>
        </div>
        <div class="featured-products-box owl-carousel owl-theme">
            <?php
            $similarProducts = $product->getSimilarProducts($product_id);

            if ($similarProducts) {
                while ($row = $similarProducts->fetch_assoc()) {
            ?>
            <div class="item product-item">
                <div class="products-single fix" >
                    <div class="box-img-hover">
                    <a href="product-detail.php?id=<?php echo $row['product_id']; ?>"> <img src="admin/uploads/<?php echo $row['product_img']; ?>" class="img-fluid image-hover-effect" alt="Image"></a>
                    </div>
                    <div class="why-text">
                        <h4><a href="product-detail.php?id=<?php echo $row['product_id']; ?>"><?php echo $row['product_name']; ?></a></h4>
                        <h5><del>$<?php echo $row['product_price']; ?></del><a href="product-detail.php?id=<?php echo $row['product_id']; ?>"> $<?php echo $row['product_price_sale']; ?></a> </h5>
                    </div>
                </div>
            </div>  
            <?php
                }
            } else {
                echo '<p>No similar products found.</p>';
            }
            ?>
        </div>
    </div>
</div>

</div>
</div>
<!-- End Cart -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        console.log("DOM content loaded.");
        var colorSelect = document.getElementById("basic");
        var memoryRamSelect = document.getElementById("basic2");
        var quantityInput = document.querySelector('input[name="quantity"]');
        var productForms = document.querySelectorAll('.price-box-bar form');

        colorSelect.addEventListener("change", function() {
            var selectedColor = colorSelect.value;
            productForms.forEach(function(form) {
                form.querySelector('input[name="product_color"]').value = selectedColor;
            });
        });

        memoryRamSelect.addEventListener("change", function() {
            var selectedMemoryRam = memoryRamSelect.value;
            productForms.forEach(function(form) {
                form.querySelector('input[name="product_memory_ram"]').value = selectedMemoryRam;
            });
        });

        quantityInput.addEventListener("input", function() {
            var quantityValue = quantityInput.value;
            productForms.forEach(function(form) {
                form.querySelectorAll('.product_quantity').forEach(function(inputElement) {
                    inputElement.value = quantityValue;
                });
            });
        });
    });
</script>
<?php
include "footer.php";
?>