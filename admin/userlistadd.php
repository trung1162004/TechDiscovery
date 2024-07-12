<?php
include "header.php";
include "sidebar.php";
include "navbar.php";
include "class/user_class.php";

$errors = array(); // Khởi tạo mảng chứa lỗi
$successMessage = ""; // Khởi tạo thông báo thành công
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (các biến và kiểm tra khác)

    $email = $_POST["email"];
    if (empty($email)) {
        $errors['email'] = "Email là bắt buộc";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ";
    }

    $username = $_POST["username"];
    if (empty($username)) {
        $errors['username'] = "Username là bắt buộc";
    }

    $password = $_POST["password"];
    if (empty($password)) {
        $errors['password'] = "Password là bắt buộc";
    }

    $fullname = $_POST["fullname"];
    if (empty($fullname)) {
        $errors['fullname'] = "Full Name là bắt buộc";
    }

    $address = $_POST["address"];
    if (empty($address)) {
        $errors['address'] = "Address là bắt buộc";
    }

    $phone = $_POST["phone"];
    if (empty($phone)) {
        $errors['phone'] = "Số điện thoại là bắt buộc";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors['phone'] = "Số điện thoại phải gồm 10 chữ số";
    }

    // Kiểm tra lỗi từ việc thêm người dùng vào cơ sở dữ liệu
    if (empty($errors)) {
        $user = new User();
        $insertResult = $user->insert_user($email, $username, $password, $fullname, $address, $phone);

        if (is_string($insertResult)) {
            $errors['insert'] = $insertResult;
        } elseif ($insertResult === true) {
            $successMessage = "User added successfully!";
        }
    }
}
?>
<div class="container-fluid pt-4 px-4">
    <div class="bg-secondary text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">ADD User Information</h6>
            <a href="userlist.php">Back to User List</a>
        </div>
        <div class="admin-content-right-category-add">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input name="email" type="email" class="form-control"  placeholder="Enter Email">
                    <?php if (isset($errors['email'])) { echo '<span class="text-danger">' . $errors['email'] . '</span>'; } ?>
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input name="username" type="text" class="form-control"  placeholder="Enter Username">
                    <?php if (isset($errors['username'])) { echo '<span class="text-danger">' . $errors['username'] . '</span>'; } ?>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input name="password" type="password" class="form-control"  placeholder="Enter Password">
                    <?php if (isset($errors['password'])) { echo '<span class="text-danger">' . $errors['password'] . '</span>'; } ?>
                </div>
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input name="fullname" type="text" class="form-control"  placeholder="Enter Full Name">
                    <?php if (isset($errors['fullname'])) { echo '<span class="text-danger">' . $errors['fullname'] . '</span>'; } ?>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input name="address" type="text" class="form-control"  placeholder="Enter Address">
                    <?php if (isset($errors['address'])) { echo '<span class="text-danger">' . $errors['address'] . '</span>'; } ?>
                </div>
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input name="phone" type="tel" class="form-control"  placeholder="Enter Phone Number">
                    <?php if (isset($errors['phone'])) { echo '<span class="text-danger">' . $errors['phone'] . '</span>'; } ?>
                </div>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
        </div>
    </div>
</div>
<?php
if (!empty($errors)) {
    echo '<div class="alert alert-danger" role="alert">';
    foreach ($errors as $error) {
        echo $error . "<br>";
    }
    echo '</div>';
}

if (isset($successMessage)) {
    echo '<div class="alert alert-success" role="alert">' . $successMessage . '</div>';
}
?>
<?php include "footer.php"; // Include your footer ?>
