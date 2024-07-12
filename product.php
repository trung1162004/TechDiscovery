<?php
include "header.php";
include "navbar.php";
include "admin/class/product_class.php";
include "admin/class/cartegory_class.php";
include "admin/class/brand_class.php";

$product = new product();
$category = new cartegory();
$brand = new brand();

// Kiểm tra xem trang có được yêu cầu từ tham số URL không, nếu không thì mặc định là trang 1
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 6; // Số sản phẩm trên mỗi trang

$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$selectedSort = isset($_GET['sort']) ? $_GET['sort'] : '';
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : '';
$selectedBrand = isset($_GET['selected_brand']) ? $_GET['selected_brand'] : '';

// Lưu thông tin phân trang vào Session
$_SESSION['current_page'] = $page;

$offset = ($page - 1) * $limit; // Tính toán offset

// Lấy danh sách các danh mục và thương hiệu để hiển thị bên trái (không thay đổi)
$mainCategories = $category->show_cartegory_main();
$cartegory = $product->show_cartegory();
$brands = $product->show_brand();

if (!empty($search_query)) {
    // Tìm kiếm sản phẩm dựa trên từ khóa
    $products = $product->searchProductsByName($search_query);
    $totalProducts = $product->getTotalSearchProducts($search_query); // Tổng số sản phẩm từ kết quả tìm kiếm
} elseif (!empty($selectedCategory)) {
    // Lọc sản phẩm theo danh mục
    $products = $product->getProductsByCategory($selectedCategory);
    $totalProducts = $product->getTotalProductsByCategory($selectedCategory); // Tổng số sản phẩm theo danh mục
} elseif (!empty($selectedBrand)) {
    // Lọc sản phẩm theo thương hiệu
    $products = $product->getProductsByBrand($selectedBrand);
    $totalProducts = $product->getTotalProductsByBrand($selectedBrand);
} else {
    // Sắp xếp sản phẩm theo giá hoặc hiển thị tất cả sản phẩm
    if ($selectedSort === 'low-to-high') {
        $products = $product->getProductsByPriceLowToHigh($limit, $offset);
    } elseif ($selectedSort === 'high-to-low') {
        $products = $product->getProductsByPriceHighToLow($limit, $offset);
    } else {
        $products = $product->getProductsForPage($limit, $offset);
    }

    $totalProducts = $product->getTotalProducts(); // Tổng số sản phẩm
}

$totalPages = ceil($totalProducts / $limit); // Tổng số trang

?>


<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Shop</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active"><a href="product.php"> Shop</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Shop Page  -->
<div class="shop-box-inner">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-sm-12 col-xs-12 sidebar-shop-left">
                <div class="product-categori">
                    <div class="search-product">
                        <form action="product.php" method="GET"> <!-- Sử dụng phương thức GET để truyền từ khóa tìm kiếm -->
                            <input class="form-control" placeholder="Search here..." type="text" name="search"> <!-- Thêm name="search" để lấy từ khóa tìm kiếm -->
                            <button type="submit"> <i class="fa fa-search"></i> </button>
                        </form>
                    </div>
                    <div class="filter-sidebar-left">
                        <div class="title-left">
                            <h3>Categories</h3>
                        </div>
                        <div class="list-group list-group-collapse list-group-sm list-group-tree" id="list-group-men" data-children=".sub-men">
                            <?php
                            if ($mainCategories) {
                                while ($mainCategory = $mainCategories->fetch_assoc()) {
                                    echo '<div class="list-group-collapse sub-men">';
                                    echo '<a class="list-group-item list-group-item-action" href="#sub-men' . $mainCategory['cartegory_main_id'] . '" data-toggle="collapse" aria-expanded="true" aria-controls="sub-men' . $mainCategory['cartegory_main_id'] . '">' . $mainCategory['cartegory_main_name'] . '</a>';
                                    echo '<div class="collapse" id="sub-men' . $mainCategory['cartegory_main_id'] . '" data-parent="#list-group-men">';
                                    echo '<div class="list-group">';

                                    $subCategories = $category->get_cartegories_by_cartegory_main_id($mainCategory['cartegory_main_id']);
                                    if ($subCategories) {
                                        while ($subCategory = $subCategories->fetch_assoc()) {
                                            echo '<a href="?category=' . $subCategory['cartegory_name'] . '" class="list-group-item list-group-item-action">' . $subCategory['cartegory_name'] . '</a>';
                                        }
                                    }

                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="filter-brand-left">
                        <div class="title-left">
                            <h3>Brand</h3>
                        </div>
                        <div class="brand-box">
                            <form action="#" method="GET"> <!-- Thay đổi action và method theo cần thiết -->
                                <ul>
                                    <?php
                                    if ($brands) {
                                        while ($brand = $brands->fetch_assoc()) {
                                            echo '<li>';
                                            echo '<div class="radio radio-danger">';
                                            echo '<input name="selected_brand" id="Radios' . $brand['brand_id'] . '" value="' . $brand['brand_name'] . '" type="radio">';
                                            echo '<label for="Radios' . $brand['brand_id'] . '">' . $brand['brand_name'] . '</label>';
                                            echo '</div>';
                                            echo '</li>';
                                        }
                                    }
                                    ?>
                                </ul>
                                <button type="submit" class="btn btn-primary">Filter</button> <!-- Nút gửi form -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-9 col-sm-12 col-xs-12 shop-content-right">
                <div class="right-product-box">
                    <div class="product-item-filter row">
                        <div class="col-12 col-sm-8 text-center text-sm-left">
                            <div class="toolbar-sorter-right">
                                <span>Sort by </span>
                                <select id="sortSelect" class="selectpicker show-tick form-control" data-placeholder="$ USD">
                                    <option data-display="Select">Nothing</option>
                                    <option value="low-to-high">Low Price → High Price</option>
                                    <option value="high-to-low">High Price → Low Price</option>
                                </select>
                            </div>
                            <?php
                            echo '<p>Showing all <span>' . $totalProducts . '</span> results</p>';
                            ?>
                        </div>
                    </div>

                    <div class="row product-categorie-box">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active" id="grid-view">
                                <div class="row">
                                    <?php
                                    if ($products) {
                                        while ($product = $products->fetch_assoc()) {
                                            echo '<div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">';
                                            echo '<div class="products-single fix">';

                                            // Check if the product is on sale or new

                                            echo '<div class="type-lb"><p class="new">New</p></div>';


                                            echo '<a href="product-detail.php?id=' . $product['product_id'] . '"><img src="admin/uploads/' . $product['product_img'] . '" class="img-fluid product-image" alt="Image"></a>';
                                            echo '<ul>';
                                            echo '<li><a href="product-detail.php?id=' . $product['product_id'] . '"></a></li>';
                                            echo '</ul>';
                                            echo '</div>';
                                            echo '<div class="why-text">';
                                            echo '<h4><a href="product-detail.php?id=' . $product['product_id'] . '">' . $product['product_name'] . '</a></h4>';
                                            echo '<h5> <del> $' . $product['product_price'] . '</del> <a href="product-detail.php?id=' . $product['product_id'] . '"> $' . $product['product_price_sale'] . '</a></h5>';
                                            echo '</div>';
                                            echo '</div>';
                                        }
                                    } else {
                                        echo '<p>No products found.</p>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container d-flex justify-content-center align-items-center" style="min-height: 10vh;">

                        <!-- Start Pagination -->
                        <div class="pagination-area">
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <?php if ($page > 1) : ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=1" aria-label="First">
                                                <span aria-hidden="true">&laquo;&laquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page - 1; ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                        <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($page < $totalPages) : ?>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $page + 1; ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="?page=<?php echo $totalPages; ?>" aria-label="Last">
                                                <span aria-hidden="true">&raquo;&raquo;</span>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                        <!-- End Pagination -->
                    </div>
                    <br><br>
                </div>
            </div>
        </div>
    </div>
    <!-- End Shop Page -->

    <style>
        .product-image {
            transition: transform 0.3s ease;
            /* Hiệu ứng thay đổi trong 0.3 giây */
        }

        .product-image:hover {
            transform: scale(1.1);
            /* Khi con trỏ chuột hover, phóng to hình ảnh lên 1.1 lần kích thước ban đầu */
        }
    </style>

    <script>
        document.getElementById("sortSelect").addEventListener("change", function() {
            var selectedSort = this.value;
            if (selectedSort !== "Nothing") {
                window.location.href = "product.php?sort=" + selectedSort;
            }
        });
    </script>

    <?php
    include "footer.php";
    ?>