<?php
include 'database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function insert_user($email, $username, $password, $fullname, $address, $phone) {
        // Check if email or username already exist in the database
        $checkQuery = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
        $checkResult = $this->db->select($checkQuery);
    
        if ($checkResult && $checkResult->num_rows > 0) {
            // Email or username already exists, show error message
            return "Email or username already exists. Please choose a different one.";
        }
    
        // Add code to hash the password before storing it into the database
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        $insertQuery = "INSERT INTO users (email, username, password, fullname, address, phone) 
                        VALUES ('$email', '$username', '$hashedPassword', '$fullname', '$address', '$phone')";
    
        $result = $this->db->insert($insertQuery);
    
        if ($result) {
            return true;
        } else {
            // Handle error
            return "Error adding user. Please try again.";
        }
    }
    public function insert_upate_user($email, $username, $fullname, $address, $phone) {
        // Check if email or username already exist in the database
        $checkQuery = "SELECT * FROM users WHERE email = '$email' OR username = '$username'";
        $checkResult = $this->db->select($checkQuery);
    
        if ($checkResult && $checkResult->num_rows > 0) {
            // Email or username already exists, show error message
            return "Email or username already exists. Please choose a different one.";
        }
    
    
        $insertQuery = "INSERT INTO users (email, username, fullname, address, phone) 
                        VALUES ('$email', '$username', '$fullname', '$address', '$phone')";
    
        $result = $this->db->insert($insertQuery);
    
        if ($result) {
            return true;
        } else {
            // Handle error
            return "Error adding user. Please try again.";
        }
    }
    public function show_users() {
        $query = "SELECT id, email, username, password, fullname, address, phone, is_online FROM users ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }
    public function search_users($search) {
        $query = "SELECT * FROM users WHERE id LIKE '%$search%' OR email LIKE '%$search%' OR username LIKE '%$search%'";
        return $this->db->select($query);
    }
    public function show_users_except_admin() {
        $query = "SELECT id, email, username, password, fullname, address, phone, is_online FROM users WHERE role != 'admin' ORDER BY id DESC";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_user_by_id($user_id) {
        $query = "SELECT id, email, username, password , fullname, address, phone FROM users WHERE id = '$user_id'";
        $result = $this->db->select($query);
        return $result;
    }
    public function get_user_by_email($email) {
        $query = "SELECT * FROM users WHERE email = '$email'";
        return $this->db->select($query);
    }

    public function get_user_by_username($username) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        return $this->db->select($query);
    }
    public function update_user($user_id, $email, $username, $fullname, $address, $phone) {
        // Add code to hash the password before updating it into the database

        $query = "UPDATE users SET 
                  email = '$email', 
                  username = '$username', 
                  fullname = '$fullname', 
                  address = '$address', 
                  phone = '$phone' 
                  WHERE id = '$user_id'";

        $result = $this->db->update($query);

        return $result;
    }

    public function delete_user($user_id) {
        $query = "DELETE FROM users WHERE id = '$user_id'";
        $result = $this->db->delete($query);
        header('Location: userlist.php');
        return $result;
    }
    public function update_user_status($user_id, $is_online) {
        // Add code to update the user status
        $sql = "UPDATE users SET is_online = $is_online WHERE id = $user_id";
        $result = $this->db->update($sql);
        return $result;
    }
    public function update_avatar($user_id, $avatar) {
        $query = "UPDATE users SET avatar = '$avatar' WHERE id = '$user_id'";
        $result = $this->db->update($query);
        if ($result) {
            echo "Avatar updated in the database.";
        } else {
            echo "Error updating avatar in the database.";
        }
        return $result;
    }
}
?>
