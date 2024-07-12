<?php
    include 'admin/database.php';

class coupon {
    private $db;
    private $conn;
    public function __construct() {
        $this->db = new Database();
    }

    // public function insert_survey($web, $gia, $spcu) {
    //     $query = "INSERT INTO survey(web, gia, spcu) 
    //               VALUES ('$web', '$gia', '$spcu')";

    //     $result = $this->db->insert($query);
    //     return $result;
    // }
    public function get_valid_coupon_code() {
        $timezone = new DateTimeZone('Asia/Ho_Chi_Minh');
        $current_date = new DateTime('now', $timezone);
    
        $query = "SELECT code, expiry_date, quantity_coupon FROM coupon WHERE expiry_date > ? AND quantity_coupon > 0 ORDER BY RAND() LIMIT 1";
        $stmt = $this->db->link->prepare($query);
        $current_date_format = $current_date->format('Y-m-d H:i:s');
        $stmt->bind_param("s", $current_date_format);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
    
            $expiry_date = DateTime::createFromFormat('Y-m-d H:i:s', $row['expiry_date'], $timezone);
    
            if ($current_date < $expiry_date) {
                return $row['code'];
            }
        }
    
        return null;
    }
    
    public function get_coupon_quantity_coupon($coupon_id) {
        $query = "SELECT quantity_coupon FROM coupon WHERE coupon_id = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("i", $coupon_id);
        $stmt->execute();
        $stmt->bind_result($quantity_coupon);
        $stmt->fetch();
        $stmt->close();
        return $quantity_coupon;
    }

    // Cập nhật số lượng của mã giảm giá
    public function update_coupon_quantity_coupon($coupon_id, $quantity_coupon) {
        $query = "UPDATE coupon SET quantity_coupon = ? WHERE coupon_id = ?";
        $stmt = $this->db->link->prepare($query);
        $stmt->bind_param("i", $coupon_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        return $result;
    }
    public function get_coupon_by_id($coupon_id) {
        $query = "SELECT coupon_id, code, amount, expiry_date, created_at, quantity_coupon FROM coupon WHERE coupon_id = '$coupon_id'";
        $result = $this->db->select($query);
        return $result;
    }    
}
?>