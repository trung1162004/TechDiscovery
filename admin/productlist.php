<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/product_class.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$product = new product;
$colors = $product->show_color();
$memoryRams = $product->show_memory_ram();
$brands = $product->show_brand();

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$productsPerPage = 8;

$sortOption = isset($_GET['sort']) ? $_GET['sort'] : ''; // Di chuyển dòng này lên đầu
$filterBrand = isset($_GET['brand']) ? (int)$_GET['brand'] : '';
$filterColor = isset($_GET['color']) ? $_GET['color'] : '';
$filterMemory = isset($_GET['memory']) ? $_GET['memory'] : '';
$priceFilterValue = isset($_GET['priceFilterValue']) ? (int)$_GET['priceFilterValue'] : 0;

// Tính tổng số sản phẩm sau khi áp dụng bộ lọc
$totalProducts = $product->getTotalFilteredProducts($searchTerm, $filterColor, $filterMemory, $filterBrand, $priceFilterValue);

// Tính tổng số trang dựa trên tổng số sản phẩm và số sản phẩm trên mỗi trang
$totalPages = ceil($totalProducts / $productsPerPage);

if ($currentPage < 1 || $currentPage > $totalPages) {
    $currentPage = 1;
}

// Lấy danh sách sản phẩm sau khi áp dụng bộ lọc và phân trang
if (!empty($searchTerm)) {
    $show_product = $product->searchProductsByNamePaginated(
        $searchTerm,
        $currentPage,
        $productsPerPage,
        $filterColor,
        $filterMemory,
        $filterBrand,
        $priceFilterValue
    );
} else {
    $sortOption = isset($_GET['sort']) ? $_GET['sort'] : ''; // Di chuyển sắp xếp vào đây
    $show_product = $product->getPaginatedProducts(
        $currentPage,
        $productsPerPage,
        $sortOption,
        $filterColor,
        $filterMemory,
        $filterBrand,
        $priceFilterValue
    );
}

// Tính số thứ tự bắt đầu cho từng trang
$startNumber = ($currentPage - 1) * $productsPerPage + 1;
?>


<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Product List</h6>
            <form class="d-none d-md-flex ms-4" method="GET" action="productlist.php">
                <input class="form-control bg-dark border-0" type="search" name="search" placeholder="Search">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <a href="productadd.php">ADD Product</a>
        </div>

        <form method="GET" action="productlist.php">
            <label for="sort">Sort by: </label>
            <select name="sort" id="sort">
                <option value="">-- Select --</option>
                <option value="price_asc" <?php echo ($sortOption === 'price_asc') ? 'selected' : ''; ?>>Prices go up</option>
                <option value="price_desc" <?php echo ($sortOption === 'price_desc') ? 'selected' : ''; ?>>Prices go down</option>
                <option value="name_asc" <?php echo ($sortOption === 'name_asc') ? 'selected' : ''; ?>>Name (A-Z)</option>
                <option value="name_desc" <?php echo ($sortOption === 'name_desc') ? 'selected' : ''; ?>>Name(Z-A)</option>
            </select>
            <label for="brand">Choose Brand:</label>
            <select name="brand" id="brand">
                <option value="">ALL</option>
                <?php foreach ($brands as $brand) : ?>
                    <option value="<?php echo $brand['brand_id']; ?>"><?php echo $brand['brand_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <label for="color">Color:</label>
            <select name="color" id="color">
                <option value="">ALL</option>
                <?php foreach ($colors as $color) : ?>
                    <option value="<?php echo $color['color_id']; ?>"><?php echo $color['color_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="memory"> RAM:</label>
            <select name="memory" id="memory">
                <option value="">ALL</option>
                <?php foreach ($memoryRams as $memoryRam) : ?>
                    <option value="<?php echo $memoryRam['memory_ram_id']; ?>"><?php echo $memoryRam['memory_ram_name']; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="priceFilterValue"> Price:</label>
            <input type="range" id="priceFilterValue" name="priceFilterValue" min="100" max="5000" value="<?php echo $priceFilterValue; ?>">
            <output name="priceOutput" for="priceFilterValue"></output>

            <input type="submit" value="Sort And Filter">
        </form><br>

        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Promotional Price</th>
                        <th scope="col">Color</th>
                        <th scope="col">Memory-Capacity</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Image</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($show_product) {
                        $i = $startNumber; // Sử dụng biến startNumber thay vì $i
                        while ($result = $show_product->fetch_assoc()) {
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['product_name'] ?></td>
                                <td><?php echo $result['product_price'] ?></td>
                                <td><?php echo $result['product_price_sale'] ?></td>
                                <td>
                                    <?php
                                    $product_colors = $product->get_colors_by_product_id($result['product_id']);
                                    foreach ($product_colors as $color_id) {
                                        $color_name = $product->get_color_name_by_id($color_id);
                                        echo '<span class="badge badge-primary">' . $color_name . '</span> ';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $product_memory_rams = $product->get_memory_rams_by_product_id($result['product_id']);
                                    foreach ($product_memory_rams as $memory_ram_id) {
                                        $memory_ram_name = $product->get_memory_ram_name_by_id($memory_ram_id);
                                        echo '<span class="badge badge-primary">' . $memory_ram_name . '</span> ';
                                    }
                                    ?>
                                </td>
                                <td><?php echo $result['product_quantity'] ?></td>
                                <td>
                                    <img src="uploads/<?php echo $result['product_img'] ?>" alt="Product Image" style="max-width: 100px;">
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="productedit.php?product_id=<?php echo $result['product_id'] ?>">Update</a>
                                </td>
                            </tr>
                    <?php
                            $i++; // Tăng biến startNumber sau mỗi lần hiển thị sản phẩm
                        }
                    } else {
                        echo '<tr><td colspan="10">No products found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div><br>
        <?php if ($totalPages > 1) : ?>
            <div class="pagination-container">
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($currentPage > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="productlist.php?page=1<?php echo (!empty($searchTerm)) ? '&search=' . $searchTerm : ''; ?>&sort=<?php echo $sortOption; ?>&color=<?php echo $filterColor; ?>&memory=<?php echo $filterMemory; ?>&brand=<?php echo $filterBrand; ?>&priceFilterValue=<?php echo $priceFilterValue; ?>" aria-label="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="productlist.php?page=<?php echo $currentPage - 1; ?><?php echo (!empty($searchTerm)) ? '&search=' . $searchTerm : ''; ?>&sort=<?php echo $sortOption; ?>&color=<?php echo $filterColor; ?>&memory=<?php echo $filterMemory; ?>&brand=<?php echo $filterBrand; ?>&priceFilterValue=<?php echo $priceFilterValue; ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                            <li class="page-item <?php echo ($currentPage == $i) ? 'active' : ''; ?>">
                                <a class="page-link" href="productlist.php?page=<?php echo $i; ?><?php echo (!empty($searchTerm)) ? '&search=' . $searchTerm : ''; ?>&sort=<?php echo $sortOption; ?>&color=<?php echo $filterColor; ?>&memory=<?php echo $filterMemory; ?>&brand=<?php echo $filterBrand; ?>&priceFilterValue=<?php echo $priceFilterValue; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages) : ?>
                            <li class="page-item">
                                <a class="page-link" href="productlist.php?page=<?php echo $currentPage + 1; ?><?php echo (!empty($searchTerm)) ? '&search=' . $searchTerm : ''; ?>&sort=<?php echo $sortOption; ?>&color=<?php echo $filterColor; ?>&memory=<?php echo $filterMemory; ?>&brand=<?php echo $filterBrand; ?>&priceFilterValue=<?php echo $priceFilterValue; ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="productlist.php?page=<?php echo $totalPages; ?><?php echo (!empty($searchTerm)) ? '&search=' . $searchTerm : ''; ?>&sort=<?php echo $sortOption; ?>&color=<?php echo $filterColor; ?>&memory=<?php echo $filterMemory; ?>&brand=<?php echo $filterBrand; ?>&priceFilterValue=<?php echo $priceFilterValue; ?>" aria-label="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>


    </div>
</div>

<style>
    /* Định dạng container chứa phân trang */
    .pagination-container {
        text-align: center;
        /* Căn giữa nội dung */
    }

    /* Định dạng nút phân trang */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 16px;
        text-decoration: none;
        color: red;
        border: 1px solid red;
        margin: 2px;
        border-radius: 4px;
    }

    /* Định dạng nút phân trang hoạt động */
    .pagination a.active {
        background-color: red;
        color: white;
        border: 1px solid black;
    }

    /* Định dạng nút phân trang khi di chuột qua */
    .pagination a:hover {
        background-color: black;
        color: white;
    }
</style>

<script>
    function confirmDelete(productId) {
        if (confirm("Are you sure you want to delete this product?")) {
            window.location.href = "productdelete.php?product_id=" + productId;
        }
    }

    var priceFilterValue = document.getElementById("priceFilterValue");
    var priceOutput = document.getElementsByName("priceOutput")[0];

    priceOutput.innerHTML = priceFilterValue.value;

    priceFilterValue.oninput = function() {
        priceOutput.innerHTML = this.value;
    };
</script>

<?php include "footer.php"; ?>