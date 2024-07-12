<?php
    include 'database.php';
?>
<?php
    class memory_ram {
        private $db;

        public function __construct()
        {
            $this -> db = new Database();
        }
        
        public function insert_memory_ram($memory_ram_name){
            $query = "INSERT INTO tbl_memory_ram (memory_ram_name) VALUES ('$memory_ram_name')";
            $result = $this->db->insert($query);
            header('Location: memory_ramlist.php');
            return $result;
        }

        public function show_memory_ram(){
            $query = "SELECT * FROM tbl_memory_ram ORDER BY memory_ram_id DESC ";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_memory_ram($memory_ram_id){
            $query = "SELECT * FROM tbl_memory_ram WHERE memory_ram_id = '$memory_ram_id'";
            $result = $this->db->select($query);
            return $result; 
        }

        public function update_memory_ram($memory_ram_name, $memory_ram_id){
            $query = "UPDATE tbl_memory_ram SET memory_ram_name = '$memory_ram_name' WHERE memory_ram_id = '$memory_ram_id'";
            $result = $this->db->update($query);
            header('Location: memory_ramlist.php');
            return $result; 
        }

        public function delete_memory_ram($memory_ram_id){
            $query = "DELETE FROM tbl_memory_ram WHERE memory_ram_id = '$memory_ram_id'";
            $result = $this->db->delete($query);
            header('Location: memory_ramlist.php');
            return $result; 
        }
    }

   
?> 