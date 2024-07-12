<?php
    include 'database.php';
?>
<?php
    class cartegory_main {
        private $db;

        public function __construct()
        {
            $this -> db = new Database();
        }
        
        public function insert_cartegory_main($cartegory_main_name){
            $query = "INSERT INTO tbl_cartegory_main (cartegory_main_name) VALUES ('$cartegory_main_name')";
            $result = $this->db->insert($query);
            header('Location: cartegory_mainlist.php');
            return $result;
        }

        public function show_cartegory_main() {
            $query = "SELECT * FROM tbl_cartegory_main ORDER BY cartegory_main_id DESC";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_cartegory_by_main_id($cartegory_main_id) {
            $query = "SELECT * FROM tbl_cartegory WHERE cartegory_main_id = '$cartegory_main_id'";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_cartegory_main($cartegory_main_id){
            $query = "SELECT * FROM tbl_cartegory_main WHERE cartegory_main_id = '$cartegory_main_id'";
            $result = $this->db->select($query);
            return $result; 
        }

        public function update_cartegory_main($cartegory_main_name, $cartegory_main_id){
            $query = "UPDATE tbl_cartegory_main SET cartegory_main_name = '$cartegory_main_name' WHERE cartegory_main_id = '$cartegory_main_id'";
            $result = $this->db->update($query);
            header('Location: cartegory_mainlist.php');
            return $result; 
        }

        public function delete_cartegory_main($cartegory_main_id){
            $query = "DELETE FROM tbl_cartegory_main WHERE cartegory_main_id = '$cartegory_main_id'";
            $result = $this->db->delete($query);
            header('Location: cartegory_mainlist.php');
            return $result; 
        }
    }

   
?> 