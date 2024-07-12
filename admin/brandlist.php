<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/brand_class.php";
?>

<?php
$brand = new brand;

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($searchTerm)) {
    // Thực hiện tìm kiếm theo brand_name
    $foundBrands = $brand->searchBrandsByName($searchTerm);
} else {
    // Nếu không có tham số tìm kiếm, hiển thị tất cả thương hiệu
    $show_brand = $brand->show_brand();
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Brand List</h6>
            <form class="d-none d-md-flex ms-4" method="GET" action="brandlist.php">
                <input class="form-control bg-dark border-0" type="search" name="search" placeholder="Search by Brand Name">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            <a href="brand_add.php">ADD Brand</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Category Main Name</th>
                        <th scope="col">Category Name</th>
                        <th scope="col">Brand Name</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (!empty($searchTerm) && $foundBrands) {
                        $i = 0;
                        while ($result = $foundBrands->fetch_assoc()) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['cartegory_main_name'] ?></td>
                                <td><?php echo $result['cartegory_name'] ?></td>
                                <td><?php echo $result['brand_name'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="brandedit.php?brand_id=<?php echo $result['brand_id'] ?>">Update</a> |
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $result['brand_id'] ?>)">Delete</a>
                                </td>
                            </tr>
                        <?php
                        }
                    } elseif (!$searchTerm && $show_brand) {
                        $i = 0;
                        while ($result = $show_brand->fetch_assoc()) {
                            $i++;
                        ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['cartegory_main_name'] ?></td>
                                <td><?php echo $result['cartegory_name'] ?></td>
                                <td><?php echo $result['brand_name'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="brandedit.php?brand_id=<?php echo $result['brand_id'] ?>">Update</a> |
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $result['brand_id'] ?>)">Delete</a>
                                </td>
                            </tr>
                    <?php
                        }
                    } else {
                        // Hiển thị thông báo nếu không tìm thấy kết quả
                        echo '<tr><td colspan="6">No brands found.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Function for brand-delete
    function confirmDelete(brand_id) {
        if (confirm('Are you sure you want to remove this brand?')) {
            window.location.href = 'branddelete.php?brand_id=' + brand_id;
        }
    }
</script>

<?php
include "footer.php";
?>