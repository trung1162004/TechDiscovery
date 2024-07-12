<?php
    include "header.php";
    include "sidebar.php";
    include "navbar.php";
    include "class/user_class.php";

    $errors = []; // Initialize an empty array for errors
    $successMessage = $errorMessage = ""; // Initialize success and error messages

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_POST["id"];
        $email = $_POST["email"];
        $username = $_POST["username"];
        $fullname = $_POST["fullname"];
        $address = $_POST["address"];
        $phone = $_POST["phone"];
        
        $user = new User();
        $update_result = $user->update_user($user_id, $email, $username, $fullname, $address, $phone);

        if ($update_result) {
            $successMessage = "User updated successfully!";
            
        } else {
            $errorMessage = "Failed to update user.";
            
        }
    } else {
        if (isset($_GET["id"])) {
            $user_id = $_GET["id"];
            $user = new User();
            $user_info = $user->get_user_by_id($user_id);

            if ($user_info && $user_info->num_rows > 0) {
                $row = $user_info->fetch_assoc();
                $user_id = $row["id"];
                $email = $row['email'];
                $username = $row['username'];
                $password = $row['password'];
                $fullname = $row["fullname"];
                $address = $row["address"];
                $phone = $row["phone"];
            } else {
                header("Location: ../admin/userlist.php");
                exit;
            }
        } else {
            header("Location: ../admin/userlist.php");
            exit;
        }
    }

    ?>
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Edit User Information</h6>
            <a href="userlist.php">Back to User List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="id">ID</label>
                    <input name="id" type="text" class="form-control" required value="<?php echo $user_id; ?>">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control" required value="<?php echo $email; ?>">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control" required value="<?php echo $username; ?>">
                </div>      
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input name="fullname" type="text" class="form-control" required value="<?php echo $fullname; ?>">
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input name="address" type="text" class="form-control" required value="<?php echo $address; ?>">
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input name="phone" type="text" class="form-control" required value="<?php echo $phone; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update User</button>
            </form>
        </div>
    </div>
</div>
<?php
if (isset($errorMessage)) {
    echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
} elseif (isset($successMessage)) {
    echo '<div class="alert alert-success" role="alert">' . $successMessage . '</div>';

}

if (!isset($_POST["id"])) {
    echo '<div class="alert alert-info" role="alert">Make your changes and click "Update User" to save.</div>';
    header("Location: ../admin/userlist.php");  
}
?>
<?php include "footer.php"; // Include your footer 
?>
