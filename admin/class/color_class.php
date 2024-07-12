<?php
    include 'database.php';
?>
<?php
    class color {
        private $db;

        public function __construct()
        {
            $this -> db = new Database();
        }
        
        public function insert_color($color_name){
            $query = "INSERT INTO tbl_color (color_name) VALUES ('$color_name')";
            $result = $this->db->insert($query);
            header('Location: colorlist.php');
            return $result;
        }

        public function show_color(){
            $query = "SELECT * FROM tbl_color ORDER BY color_id DESC ";
            $result = $this->db->select($query);
            return $result;
        }

        public function get_color($color_id){
            $query = "SELECT * FROM tbl_color WHERE color_id = '$color_id'";
            $result = $this->db->select($query);
            return $result; 
        }

        public function update_color($color_name, $color_id){
            $query = "UPDATE tbl_color SET color_name = '$color_name' WHERE color_id = '$color_id'";
            $result = $this->db->update($query);
            header('Location: colorlist.php');
            return $result; 
        }

        public function delete_color($color_id){
            $query = "DELETE FROM tbl_color WHERE color_id = '$color_id'";
            $result = $this->db->delete($query);
            header('Location: colorlist.php');
            return $result; 
        }

        public function get_color_name_by_id($color_id){
            $query = "SELECT color_name FROM tbl_color WHERE color_id = '$color_id'";
            $result = $this->db->select($query);
            if ($result) {
                $color = $result->fetch_assoc();
                return $color['color_name'];
            } else {
                return "Không tìm thấy màu sắc";
            }
        }
    }

   
?> 