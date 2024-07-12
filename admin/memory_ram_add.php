<?php
    include "header.php";
    include "sidebar.php";
    include "navbar.php";
    include "class/memory_ram_class.php";
?>

<?php
    $memory_ram = new memory_ram;
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $memory_ram_name = $_POST['memory_ram_name'];
        $insert_memory_ram = $memory_ram->insert_memory_ram($memory_ram_name);

        if($insert_memory_ram) {
            echo "<script>window.location.href = 'memory_ramlist.php';</script>";
        }
    }
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">ADD Memory - Ram</h6>
            <a href="memory_ramlist.php">Back to Memory - Ram List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="form-group row">
                    <label for="memory_ram_name" class="col-sm-2 col-form-label">Memory - Ram</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="memory_ram_name" name="memory_ram_name" placeholder="Enter memory - ram" required>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-10 offset-sm-2">
                        <button type="submit" class="btn btn-primary">ADD</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>