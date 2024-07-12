<?php
    include 'database.php';
?>
<?php
    class order {
        private $db;

        public function __construct()
        {
            $this -> db = new Database();
        }

        public function delete_order_list($order_id){
            $query = "DELETE FROM tbl_order WHERE order_id = '$order_id'";
            $result = $this->db->delete($query);
            header('Location: order_list.php');
            return $result; 
        }

        public function update_order_list($order_id, $order_status){
            $query = "UPDATE tbl_order SET order_status = '$order_status' WHERE order_id = '$order_id'";
            $result = $this->db->update($query);
            header('Location: order_list.php');
            return $result; 
        }

        public function search_order_list($order_id){
            $query = "select * from tbl_order WHERE order_id = '$order_id'";
            $result = $this->db->select($query);
            return $result; 
        }

        public function show_processing_orders($page, $itemsPerPage) {
            $offset = ($page - 1) * $itemsPerPage;
            $query = "SELECT * FROM tbl_order WHERE order_status = 'processing' ORDER BY order_id DESC LIMIT $itemsPerPage OFFSET $offset";
            $result = $this->db->select($query);
        
            if ($result && $result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        }
        
        // Phương thức lấy danh sách đơn hàng đã hoàn thành
        public function show_completed_orders($page, $itemsPerPage) {
            $offset = ($page - 1) * $itemsPerPage;
            $query = "SELECT * FROM tbl_order WHERE order_status = 'delivered' ORDER BY order_id DESC LIMIT $itemsPerPage OFFSET $offset";
            $result = $this->db->select($query);
        
            if ($result && $result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        }
        
        // Phương thức lấy danh sách đơn hàng đã hủy
        public function show_cancelled_orders($page, $itemsPerPage) {
            $offset = ($page - 1) * $itemsPerPage;
            $query = "SELECT * FROM tbl_order WHERE order_status = 'cancelled' ORDER BY order_id DESC LIMIT $itemsPerPage OFFSET $offset";
            $result = $this->db->select($query);
        
            if ($result && $result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        }

        public function show_processed_orders($page, $itemsPerPage) {
            $offset = ($page - 1) * $itemsPerPage;
            $query = "SELECT * FROM tbl_order WHERE order_status = 'delivered_carrier' ORDER BY order_id DESC LIMIT $itemsPerPage OFFSET $offset";
            $result = $this->db->select($query);
        
            if ($result && $result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        }

        public function show_return_orders($page, $itemsPerPage) {
            $offset = ($page - 1) * $itemsPerPage;
            $query = "SELECT * FROM tbl_order WHERE order_status = 'return' ORDER BY order_id DESC LIMIT $itemsPerPage OFFSET $offset";
            $result = $this->db->select($query);
        
            if ($result && $result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        }

        public function getTotalOrdersProcessing() {
            $query = "SELECT COUNT(*) as total_orders FROM tbl_order where order_status = 'processing'";
            $result = $this->db->select($query);
        
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                return $row['total_orders'];
            } else {
                return 0;
            }
        }

        public function getTotalOrdersProcessed() {
            $query = "SELECT COUNT(*) as total_orders FROM tbl_order WHERE order_status = 'delivered_carrier'";
            $result = $this->db->select($query);
        
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                return $row['total_orders'];
            } else {
                return 0;
            }
        }

        public function getTotalOrdersCompleted() {
            $query = "SELECT COUNT(*) as total_orders FROM tbl_order WHERE order_status = 'completed'";
            $result = $this->db->select($query);
        
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                return $row['total_orders'];
            } else {
                return 0;
            }
        }

        public function getTotalOrdersReturn() {
            $query = "SELECT COUNT(*) as total_orders FROM tbl_order WHERE order_status = 'return'";
            $result = $this->db->select($query);
        
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                return $row['total_orders'];
            } else {
                return 0;
            }
        }

        public function getTotalOrdersCancel() {
            $query = "SELECT COUNT(*) as total_orders FROM tbl_order WHERE order_status = 'cancelled'";
            $result = $this->db->select($query);
        
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                return $row['total_orders'];
            } else {
                return 0;
            }
        }
    }

   
?>