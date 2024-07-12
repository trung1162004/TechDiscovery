<?php

class Stock {
    private $db;
    private $product; 

    public function __construct()
    {
        $this->db = new Database();
        $this->product = new Product();
    }

    public function updateStockQuantity($stock_id, $new_quantity) {
        $query = "UPDATE tbl_stock SET quantity = '$new_quantity' WHERE stock_id = '$stock_id'";
        $result = $this->db->update($query);
        return $result;
    }

    public function getStockInfo($stock_id) {
        $query = "SELECT * FROM tbl_stock WHERE stock_id = '$stock_id'";
        $result = $this->db->select($query);
        return ($result->num_rows > 0) ? $result->fetch_assoc() : null;
    }

    public function showStock() {
        $query = "SELECT s.stock_id, p.product_id, p.product_name, s.quantity 
                  FROM tbl_stock s
                  JOIN tbl_product p ON s.product_id = p.product_id
                  ORDER BY s.stock_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function insertStock($product_name, $quantity) {
        $product_id = $this->product->getProductIDByName($product_name);
        if ($product_id !== null) {
            $query = "INSERT INTO tbl_stock (product_id, quantity) VALUES ('$product_id', '$quantity')";
            $result = $this->db->insert($query);
            return $result;
        }
        return false;
    }
    

    public function updateStock($stock_id, $quantity) {
        $query = "UPDATE tbl_stock SET stock_quantity = '$quantity' WHERE stock_id = '$stock_id'";
        $result = $this->db->update($query);
        return $result;
    }

    public function deleteStock($stock_id) {
        $query = "DELETE FROM tbl_stock WHERE stock_id = '$stock_id'";
        $result = $this->db->delete($query);
        return $result;
    }

    public function getStock($stock_id) {
        $query = "SELECT * FROM tbl_stock WHERE stock_id = '$stock_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getAllStocks() {
        $query = "SELECT * FROM tbl_stock";
        $result = $this->db->select($query);
        return $result;
    }
}

?>
