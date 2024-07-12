<?php
    include 'database.php';
?>
<?php
    class cartegory {
        private $db;

        public function __construct()
        {
            $this -> db = new Database();
        }
        
        public function insert_cartegory($cartegory_main_id, $cartegory_name){
            $query = "INSERT INTO tbl_cartegory (cartegory_main_id, cartegory_name) VALUES ('$cartegory_main_id', '$cartegory_name')";
            $result = $this->db->insert($query);
            header('Location: cartegorylist.php');
            return $result;
        }

        public function get_cartegories_by_cartegory_main_id($cartegory_main_id) {
            $query = "SELECT * FROM tbl_cartegory WHERE cartegory_main_id = '$cartegory_main_id' ORDER BY cartegory_id DESC";
            $result = $this->db->select($query);
            return $result;
        }

        public function show_cartegory_main(){
            $query = "SELECT * FROM tbl_cartegory_main ORDER BY cartegory_main_id DESC ";
            $result = $this->db->select($query);
            return $result;
        }

        public function show_cartegory(){
            $query = "SELECT tbl_cartegory.*, tbl_cartegory_main.cartegory_main_name
                      FROM tbl_cartegory
                      INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
                      ORDER BY tbl_cartegory.cartegory_id DESC";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_cartegory($cartegory_id){
            $query = "SELECT * FROM tbl_cartegory WHERE cartegory_id = '$cartegory_id'";
            $result = $this->db->select($query);
            return $result; 
        }

        public function update_cartegory($cartegory_main_id, $cartegory_name, $cartegory_id) {
            $query = "UPDATE tbl_cartegory SET cartegory_main_id = '$cartegory_main_id', cartegory_name = '$cartegory_name' WHERE cartegory_id = '$cartegory_id'";
            $result = $this->db->update($query);
            header('Location: cartegorylist.php');
            return $result;
        }

        public function delete_cartegory($cartegory_id){
            $query = "DELETE FROM tbl_cartegory WHERE cartegory_id = '$cartegory_id'";
            $result = $this->db->delete($query);
            header('Location: cartegorylist.php');
            return $result; 
        }
    }

   
?> 