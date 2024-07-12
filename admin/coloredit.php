<?php
    include "header.php";
    include "sidebar.php";
    include "navbar.php";
    include "class/color_class.php";

    $color = new color;
    if (!isset($_GET['color_id']) || $_GET['color_id'] == NULL) {
        echo "<script>window.location = 'colorlist.php'</script>";
    } else {
        $color_id = $_GET['color_id'];
    }

    $get_color = $color->get_color($color_id);

    if ($get_color) {
        $result = $get_color->fetch_assoc();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $color_name = $_POST['color_name'];
        $update_color = $color->update_color($color_name, $color_id);

        if ($update_color) {
            echo "<script>window.location.href = 'colorlist.php';</script>";
        }
    }
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Color</h6>
            <a href="colorlist.php">Back to Color List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="mb-3">
                    <label for="color_name" class="form-label">Color Name</label>
                    <input type="text" class="form-control" id="color_name" name="color_name" placeholder="Enter color name" required value="<?php echo $result['color_name'] ?>">
                </div>
                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
        </div>
    </div>
</div>

<?php
    include "footer.php";
?>
