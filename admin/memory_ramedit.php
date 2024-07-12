<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/memory_ram_class.php";
?>

<?php
$memory_ram = new memory_ram;
if (!isset($_GET['memory_ram_id']) || $_GET['memory_ram_id'] == NULL) {
    echo "<script>window.location = 'memory_ramlist.php'</script>";
} else {
    $memory_ram_id = $_GET['memory_ram_id'];
}

$get_memory_ram = $memory_ram->get_memory_ram($memory_ram_id);

if ($get_memory_ram) {
    $result = $get_memory_ram->fetch_assoc();
}
?>

<?php
$memory_ram = new memory_ram;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['memory_ram_id'])) {
        $memory_ram_id = $_POST['memory_ram_id'];
        $memory_ram_name = $_POST['memory_ram_name'];
        $update_memory_ram = $memory_ram->update_memory_ram($memory_ram_name, $memory_ram_id);

        if ($update_memory_ram) {
            echo "<script>window.location.href = 'memory_ramlist.php';</script>";
        }
    }
}
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Update Memory - Ram</h6>
            <a href="memory_ramlist.php">Back to Memory - Ram List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <input type="hidden" name="memory_ram_id" value="<?php echo $memory_ram_id; ?>">
                <div class="mb-3">
                    <label for="memory_ram_name" class="form-label">Memory - Ram</label>
                    <input type="text" class="form-control" id="memory_ram_name" name="memory_ram_name" placeholder="Enter memory - ram" required value="<?php echo $result['memory_ram_name'] ?>">
                </div>
                <button type="submit" class="btn btn-primary">UPDATE</button>
            </form>
        </div>
    </div>
</div>

<?php
include "footer.php";
?>
