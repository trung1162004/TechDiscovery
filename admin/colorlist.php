<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/color_class.php";
?>

<?php
$color = new color;
$show_colors = $color->show_color();
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Color List</h6>
            <a href="color_add.php">ADD Color</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Color</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($show_colors) {
                        $i = 0;
                        while ($result = $show_colors->fetch_assoc()) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['color_name'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="coloredit.php?color_id=<?php echo $result['color_id'] ?>">Update</a> | 
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $result['color_id'] ?>)">Delete</a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Function for color-delete
    function confirmDelete(color_id) {
        if (confirm('Are you sure you want to delete this color?')) {
            window.location.href = 'colordelete.php?color_id=' + color_id;
        }
    }
</script>

<?php
include "footer.php";
?>
