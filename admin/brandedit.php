<?php
    include "header.php";
    include "sidebar.php";
    include "navbar.php";
    include "class/brand_class.php";
?>

<?php
    $brand = new brand;
    $brand_id = $_GET['brand_id'];
    $get_brand = $brand->get_brand($brand_id);
    if ($get_brand) {
        $resultA = $get_brand->fetch_assoc();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cartegory_main_id = $_POST['cartegory_main_id'];
        $cartegory_id = $_POST['cartegory_id'];
        $brand_name = $_POST['brand_name'];
        $update_brand = $brand->update_brand($cartegory_main_id, $cartegory_id, $brand_name, $brand_id);
        if ($update_brand) {
            echo "<script>window.location.href = 'brandlist.php';</script>";
        }
    }
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Brand</h6>
            <a href="brandlist.php">Back to Brand List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="cartegory_main_id" class="form-label">Category Main</label>
                    <select class="form-control" id="cartegory_main_id" name="cartegory_main_id" required>
                        <option value="">-- Select Category Main --</option>
                        <?php
                        $show_cartegory_main = $brand->show_cartegory_main();
                        if ($show_cartegory_main) {
                            while ($result = $show_cartegory_main->fetch_assoc()) {
                                $selected = ($resultA['cartegory_main_id'] == $result['cartegory_main_id']) ? "selected" : "";
                                echo "<option value='" . $result['cartegory_main_id'] . "' " . $selected . ">" . $result['cartegory_main_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="cartegory_id" class="form-label">Category</label>
                    <select class="form-control" id="cartegory_id" name="cartegory_id" required>
                        <option value="">-- Select Category --</option>
                        <?php
                        $show_cartegory = $brand->get_cartegories_by_cartegory_main_id($resultA['cartegory_main_id']);
                        if ($show_cartegory) {
                            while ($result = $show_cartegory->fetch_assoc()) {
                                $selected = ($resultA['cartegory_id'] == $result['cartegory_id']) ? "selected" : "";
                                echo "<option value='" . $result['cartegory_id'] . "' " . $selected . ">" . $result['cartegory_name'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="brand_name" class="form-label">Brand Name</label>
                    <input type="text" class="form-control" id="brand_name" name="brand_name" placeholder="Enter brand name" value="<?php echo $resultA['brand_name'] ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Get the select elements
    var cartegoryMainSelect = document.getElementById("cartegory_main_id");
    var cartegorySelect = document.getElementById("cartegory_id");

    // Add event listener to update the category options when category main is selected
    cartegoryMainSelect.addEventListener("change", function() {
        var cartegoryMainId = this.value;
        // Send an AJAX request to get category options based on selected category main
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState === 4 && this.status === 200) {
                cartegorySelect.innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "get_categories_by_cartegory_main_id.php?cartegory_main_id=" + cartegoryMainId, true);
        xhttp.send();
    });

    // Trigger the change event to load categories initially
    cartegoryMainSelect.dispatchEvent(new Event('change'));
</script>

<?php
    include "footer.php";
?>
