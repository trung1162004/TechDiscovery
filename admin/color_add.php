<?php
    include "header.php";
    include "sidebar.php";
    include "navbar.php";
    include "class/color_class.php";

    $color = new color;
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $color_name = $_POST['color_name'];
        $insert_color = $color->insert_color($color_name);

        if ($insert_color) {
            echo "<script>window.location.href = 'colorlist.php';</script>";
        }
    }
?>


<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">ADD Color</h6>
            <a href="colorlist.php">Back to Color List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="form-group row">
                    <label for="color_name" class="col-sm-2 col-form-label">Color Name</label>
                    <div class="col-sm-10">
                        <input name="color_name" type="text" class="form-control" placeholder="Enter color name" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
    include "footer.php";
?>
