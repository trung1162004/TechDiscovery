<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/memory_ram_class.php";
?>

<?php
$memory_ram = new memory_ram;
$show_memory_ram = $memory_ram->show_memory_ram();
?>

<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Memory - Ram List</h6>
            <a href="memory_ram_add.php">ADD Memory-Ram</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-white">
                        <th scope="col">#</th>
                        <th scope="col">Memory - Ram</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($show_memory_ram) {
                        $i = 0;
                        while ($result = $show_memory_ram->fetch_assoc()) {
                            $i++;
                    ?>
                            <tr>
                                <td><?php echo $i ?></td>
                                <td><?php echo $result['memory_ram_name'] ?></td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="memory_ramedit.php?memory_ram_id=<?php echo $result['memory_ram_id'] ?>">Update</a> | 
                                    <a class="btn btn-sm btn-primary" href="#" onclick="confirmDelete(<?php echo $result['memory_ram_id'] ?>)">Delete</a>
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
    // Function for memory_ram-delete
    function confirmDelete(memory_ram_id) {
        if (confirm('Are you sure you want to delete this memory_ram?')) {
            window.location.href = 'memory_ramdelete.php?memory_ram_id=' + memory_ram_id;
        }
    }
</script>

<?php
include "footer.php";
?>
