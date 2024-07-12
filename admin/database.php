<?php
include "config.php";

?>
<?php
class Database
{
    public $host = DB_HOST;
    public $user = DB_USER;
    public $pass = DB_PASS;
    public $dbname = DB_NAME;


    public $link;
    public $error;

    public function __construct()
    {
        $this->connectDB();
    }


    private function connectDB()
    {
        try {
            $this->link = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            if ($this->link->connect_error) {
                throw new Exception("Connection failed: " . $this->link->connect_error);
            }
        } catch (Exception $e) {
            // Xử lý lỗi ở đây
            echo "Error: " . $e->getMessage();
        }
    }

    //select or read data
    public function select($query)
    {
        try {
            $result = $this->link->query($query);
            if (!$result) {
                throw new Exception("Query failed: " . $this->link->error);
            }
            
            if ($result->num_rows > 0) {
                return $result;
            } else {
                return false;
            }
        } catch (Exception $e) {
            // Xử lý lỗi ở đây
            echo "Error: " . $e->getMessage();
        }
    }

    //insert data
    public function insert($query)
    {
        try {
            $insert_row = $this->link->query($query);
            if (!$insert_row) {
                throw new Exception("Insert failed: " . $this->link->error);
            }
            return $insert_row;
        } catch (Exception $e) {
            // Xử lý lỗi ở đây
            echo "Error: " . $e->getMessage();
        }
    }

    //update data
    public function update($query)
    {
        try {
            $update_row = $this->link->query($query);
            if (!$update_row) {
                throw new Exception("Update failed: " . $this->link->error);
            }
            return $update_row;
        } catch (Exception $e) {
            // Xử lý lỗi ở đây
            echo "Error: " . $e->getMessage();
        }
    }

    //delete data
    public function delete($query)
    {
        try {
            $delete_row = $this->link->query($query);
            if (!$delete_row) {
                throw new Exception("Delete failed: " . $this->link->error);
            }
            return $delete_row;
        } catch (Exception $e) {
            // Xử lý lỗi ở đây
            echo "Error: " . $e->getMessage();
        }
    }

    public function close()
    {
        $this->link->close();
    }
}
?>