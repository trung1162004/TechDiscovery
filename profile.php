<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            background-image: url('image/profile-picture-background-l1y44ijo20ooanql.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #ffffff;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .center-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80vh;
            flex-direction: column;
        }

        .profile-container {
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            max-width: 400px;
            margin: 0 auto;
        }

        .avatar-frame {
            width: 150px;
            height: 150px;
            overflow: hidden;
            border-radius: 50%;
            border: 2px solid #ffffff;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            margin: 0 auto;
            margin-bottom: 20px;
        }

        .avatar-frame img {
            width: 100%;
            height: auto;
            display: block;
        }

        .profile-item {
            position: relative;
            margin-bottom: 10px;
        }

        .edit-icon {
            font-size: 1.2rem;
            color: #00bcd4;
            cursor: pointer;
            transition: color 0.3s;
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
        }

        .edit-icon:hover {
            color: #007a8a;
        }

        .edit-form {
            display: none;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 5px;
            border-radius: 5px;
            position: absolute;
            right: 0;
            top: 0;
            transform: translate(100%, -50%);
        }

        .show-edit-form {
            display: block;
        }

        .edit-form input[type="text"] {
            width: 100%;
            padding: 5px;
            border: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border-radius: 5px;
        }

        .edit-form button[type="submit"] {
            background-color: #00bcd4;
            color: #ffffff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .edit-form button[type="submit"]:hover {
            background-color: #007a8a;
        }

        .button-style {
            background-color: #00bcd4;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
            text-align: center;
            display: inline-block;
            margin: 5px 0;
        }

        .button-style:hover {
            background-color: #007a8a;
        }
    </style>
</head>

<body>
    <div class="center-content">
        <?php
        session_start();
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "website_td";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];

            $sql = "SELECT * FROM users WHERE id = $id";
            $result = $conn->query($sql);

            if ($result) {
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
        ?>
                    <div class="profile-container">
                        <div class="avatar-frame">

                            <img src="uploads/<?php echo $row['avatar']; ?>" alt="User Avatar">
                            </label>
                        </div>

                        <div class="avatar-container">
                            <form action="update_avatar.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id" value="<?php echo $id; ?>">
                                <input type="file" name="avatar" id="avatar-input" class="file-input" accept="image/*">
                                <button type="submit" class="button-style change-password-btn">Cập nhật Avatar</button>
                            </form>
                        </div>
                        <h1>Profile <?php echo $row['username'] ?></h1>
                        <div class="profile-item">Email: <?php echo $row['email']; ?></div>
                        <div class="profile-item">

                            Address: <?php echo $row['address']; ?>
                            <i class="fas fa-edit edit-icon edit-address" id="edit-address-icon"></i>
                            <div class="edit-form edit-address-form">
                                <form action="update_profile.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="text" name="address" value="<?php echo $row['address']; ?>">
                                    <button type="submit">Lưu</button>
                                </form>
                            </div>
                        </div>
                        <div class="profile-item">
                            Phone: <?php echo $row['phone']; ?>
                            <i class="fas fa-edit edit-icon edit-phone"></i>
                            <div class="edit-form edit-phone-form">
                                <form action="update_profile.php" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="text" name="phone" value="<?php echo $row['phone']; ?>">
                                    <button type="submit">Lưu</button>
                                </form>
                            </div>
                        </div>
                        <button type="button" id="change-password-btn" class="button-style change-password-btn">Đổi mật khẩu</button>

                    </div>
                    <button type="button" id="back" class="button-style change-password-btn">Quay Lại</button>

                    <script>
                        const changePasswordBtn = document.getElementById('change-password-btn');

                        changePasswordBtn.addEventListener('click', () => {
                            window.location.href = 'change_password.php';
                        });

                        const editIcons = document.querySelectorAll('.edit-icon');
                        const editForms = document.querySelectorAll('.edit-form');

                        editIcons.forEach((icon, index) => {
                            icon.addEventListener('click', () => {
                                editForms[index].classList.toggle('show-edit-form');
                            });
                        });
                        const Back = document.getElementById('back');

                        Back.addEventListener('click', () => {
                            window.location.href = 'my-account.php';
                        });
                        const avatarInput = document.getElementById('avatar-input');
                        const chooseAvatarBtn = document.querySelector('.choose-avatar-btn');
                        const avatarFrame = document.querySelector('.avatar-frame img');

                        avatarInput.addEventListener('change', function() {
                            const file = this.files[0];
                            const url = URL.createObjectURL(file);
                            avatarFrame.src = url;
                        });

                        chooseAvatarBtn.addEventListener('click', function(event) {
                            event.stopPropagation();
                            avatarInput.click();
                        });
                    </script>
        <?php
                } else {
                    echo "Không tìm thấy thông tin người dùng.";
                }
            } else {
                echo "Lỗi truy vấn: " . $conn->error;
            }
        } else {
            echo "Bạn cần đăng nhập để xem trang này.";
        }

        $conn->close();
        ?>
    </div>
</body>

</html>