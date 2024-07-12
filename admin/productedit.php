<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/product_class.php";
?>

<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
$product = new product;
$color_list = $product->show_color();
$memory_ram_list = $product->show_memory_ram();

if (!isset($_GET['product_id']) || $_GET['product_id'] == NULL) {
    echo "<script>window.location = 'productlist.php'</script>";
} else {
    $product_id = $_GET['product_id'];
}

$get_product = $product->get_product($product_id);

if ($get_product) {
    $result = $get_product->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $update_product = $product->update_product($_POST, $_FILES, $product_id);

    if ($update_product) {
        echo "<script>window.location.href = 'productlist.php';</script>";
    }
}
?>


<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Product</h6>
            <a href="productlist.php" class="btn btn-secondary">Back to Product List</a>
        </div>
        <div class="admin-content-right-product-add">
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-6">
                        <!-- Left Column -->
                        <div class="form-group">
                            <label for="cartegory_main_id">Select Cartegory-Main <span style="color:red;">*</span></label>
                            <select name="cartegory_main_id" id="cartegory_main_id" onchange="getCategoriesByMainCategory()" class="form-control">
                                <option value="">--Select--</option>
                                <?php
                                $show_cartegory_main = $product->show_cartegory_main();
                                if ($show_cartegory_main) {
                                    while ($_result = $show_cartegory_main->fetch_assoc()) {
                                        $selected = ($result['cartegory_main_id'] == $_result['cartegory_main_id']) ? 'selected' : '';
                                        echo '<option ' . $selected . ' value="' . $_result['cartegory_main_id'] . '">' . $_result['cartegory_main_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="cartegory_id">Select Cartegory <span style="color:red;">*</span></label>
                            <select name="cartegory_id" id="cartegory_id" onchange="getBrandsByCategory()" class="form-control">
                                <option value="">--Select--</option>
                                <?php
                                $show_cartegory = $product->show_cartegory();
                                if ($show_cartegory) {
                                    while ($_result = $show_cartegory->fetch_assoc()) {
                                        $selected = ($result['cartegory_id'] == $_result['cartegory_id']) ? 'selected' : '';
                                        echo '<option ' . $selected . ' value="' . $_result['cartegory_id'] . '">' . $_result['cartegory_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="brand_id">Select Brand <span style="color:red;">*</span></label>
                            <select name="brand_id" id="brand_id" class="form-control">
                                <option value="">--Select--</option>
                                <?php
                                $show_brand = $product->show_brand();
                                if ($show_brand) {
                                    while ($_result = $show_brand->fetch_assoc()) {
                                        $selected = ($result['brand_id'] == $_result['brand_id']) ? 'selected' : '';
                                        echo '<option ' . $selected . ' value="' . $_result['brand_id'] . '">' . $_result['brand_name'] . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="product_name"> Product Name <span style="color:red;">*</span></label>
                            <input name="product_name" type="text" class="form-control" required value="<?php echo $result['product_name']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="product_price"> Product Price <span style="color:red;">*</span></label>
                            <input required name="product_price" type="text" class="form-control" placeholder="" value="<?php echo $result['product_price']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="product_price_sale">Promotional Price<span style="color:red;">*</span></label>
                            <input required name="product_price_sale" type="text" class="form-control" placeholder="" value="<?php echo $result['product_price_sale']; ?>">
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_quantity">Stock <span style="color:red;">*</span></label>
                                <input required name="product_quantity" type="number" min="0" class="form-control" value="<?php echo $result['product_quantity']; ?>">
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">
                        <!-- Right Column -->
                        <div class="form-group">
                            <label>Choose Colors: <span style="color:red;">*</span></label>
                            <div class="checkbox-list">
                                <?php
                                if ($color_list) {
                                    echo '<div class="checkbox-row row">';
                                    while ($color = $color_list->fetch_assoc()) {
                                        echo '<div class="checkbox-item col-md-3" >';
                                        $isChecked = (in_array($color['color_id'], explode(', ', $result['product_color']))) ? 'checked' : '';
                                        echo '<input type="checkbox" name="product_color[]" value="' . $color['color_id'] . '" ' . $isChecked . '> ' . $color['color_name'];
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                        <br><br>
                        <div class="form-group">
                            <label>Choose Memory Capacities: <span style="color:red;">*</span></label>
                            <div class="checkbox-list">
                                <?php
                                if ($memory_ram_list) {
                                    echo '<div class="checkbox-row row">';
                                    while ($memory_ram = $memory_ram_list->fetch_assoc()) {
                                        echo '<div class="checkbox-item col-md-4">';
                                        $isChecked = (in_array($memory_ram['memory_ram_id'], explode(', ', $result['product_memory_ram']))) ? 'checked' : '';
                                        echo '<input type="checkbox" name="product_memory_ram[]" value="' . $memory_ram['memory_ram_id'] . '" ' . $isChecked . '> ' . $memory_ram['memory_ram_name'];
                                        echo '</div>';
                                    }
                                    echo '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_intro">Product Introduce <span style="color:red;">*</span></label>
                            <textarea required name="product_intro" id="summernote_intro" cols="30" rows="10" class="form-control"><?php echo $result['product_intro']; ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_detail">Product Detail <span style="color:red;">*</span></label>
                            <textarea name="product_detail" id="summernote_detail" cols="30" rows="10" class="form-control"><?php echo $result['product_detail']; ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_accessory">Product Accessory <span style="color:red;">*</span></label>
                            <textarea name="product_accessory" id="summernote_accessory" cols="30" rows="10" class="form-control"><?php echo $result['product_accessory']; ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_guarantee">Product Guarantee <span style="color:red;">*</span></label>
                            <textarea name="product_guarantee" id="summernote_guarantee" cols="30" rows="10" class="form-control"><?php echo $result['product_guarantee']; ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_img">Product Image<span style="color:red;">*</span></label>
                            <input name="product_img" type="file" class="form-control" onchange="previewImage(this, 'previewProductImg')">
                            <img id="previewProductImg" src="uploads/<?php echo $result['product_img']; ?>" alt="Preview Image" style="max-width: 200px; max-height: 200px; display: block;"><br>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="product_img_desc">Description Image<span style="color:red;">*</span></label>
                            <input name="product_img_desc[]" multiple type="file" class="form-control" onchange="previewImagesOnEdit(this)">
                            <div class="image-previews">
                                <?php
                                $product_imgs_desc = $product->get_product_imgs_desc($product_id);
                                if ($product_imgs_desc) {
                                    while ($row = $product_imgs_desc->fetch_assoc()) {
                                        echo '<div class="image-preview-item">';
                                        echo '<img src="uploads/' . $row['product_img_desc'] . '" alt="Product Image" style="max-width: 150px; height: 100px;">';
                                        echo '</div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">UPDATE</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>


<style>
    /* ... */
    .image-previews {
        display: flex;
        flex-wrap: wrap;
        justify-content: flex-start;
    }

    .image-preview-item {
        margin: 10px;
        text-align: center;
        padding: 10px;
        box-sizing: border-box;
        width: 150px;
        /* Độ rộng cố định của từng ảnh */
    }

    .image-preview-item img {
        width: 100%;
        /* Tự động điều chỉnh kích thước theo độ rộng của phần tử chứa ảnh */
        height: auto;
        /* Đảm bảo tỷ lệ chiều cao thích hợp */
        display: block;
        margin-bottom: 5px;
    }
</style>

<!--  <script> của trang productedit.php -->
<!-- Đoạn mã JavaScript -->
<script>
    $('#summernote_intro').summernote({
        placeholder: 'Enter Product Introduce',
        tabsize: 2,
        height: 200
    });

    $('#summernote_detail').summernote({
        placeholder: 'Enter Product Detail',
        tabsize: 2,
        height: 200
    });

    $('#summernote_accessory').summernote({
        placeholder: 'Enter Product Accessory',
        tabsize: 2,
        height: 200
    });
    $('#summernote_guarantee').summernote({
        placeholder: 'Enter Product Guarantee',
        tabsize: 2,
        height: 200
    });

    function previewImagesOnEdit(input) {
        var imagesContainer = document.querySelector('.image-previews');
        imagesContainer.innerHTML = '';

        // var errorMessagesContainer = document.querySelector('.error-messages');
        // errorMessagesContainer.innerHTML = '';

        var files = input.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var imageURL = URL.createObjectURL(file);

            var image = document.createElement('img');
            image.src = imageURL;
            image.style.width = '150px'; // Cài độ rộng tùy ý
            image.style.height = '150px'; // Cài chiều cao tùy ý

            imagesContainer.appendChild(image);

            // Kiểm tra định dạng và dung lượng của ảnh mô tả
            var allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            var maxFileSize = 5 * 1024 * 1024; // 5MB

            var fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedExtensions.includes(fileExtension)) {
                var errorMessage = document.createElement('div');
                errorMessage.innerText = "Định dạng đuôi ảnh không hợp lệ. Chỉ chấp nhận định dạng jpg, jpeg, png, webp, gif.";
                errorMessagesContainer.appendChild(errorMessage);
            }

            if (file.size > maxFileSize) {
                var errorMessage = document.createElement('div');
                errorMessage.innerText = "Dung lượng ảnh quá lớn. Chỉ chấp nhận ảnh có dung lượng tối đa là 5MB.";
                errorMessagesContainer.appendChild(errorMessage);
            }
        }
    }
</script>

<?php
include "footer.php";
?>