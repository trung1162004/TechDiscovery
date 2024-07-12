<?php
include 'database.php';
?>
<?php
class brand
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_brand($cartegory_main_id, $cartegory_id, $brand_name)
    {
        $query = "INSERT INTO tbl_brand (cartegory_main_id, cartegory_id, brand_name) VALUES ('$cartegory_main_id', '$cartegory_id', '$brand_name')";
        $result = $this->db->insert($query);
        header('Location: brandlist.php');
        return $result;
    }

    public function show_cartegory()
    {
        $query = "SELECT tbl_cartegory.*, tbl_cartegory_main.cartegory_main_name
                      FROM tbl_cartegory
                      INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
                      ORDER BY tbl_cartegory.cartegory_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_cartegory_main()
    {
        $query = "SELECT * FROM tbl_cartegory_main ORDER BY cartegory_main_id DESC ";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_brand()
    {
        $query = "SELECT tbl_brand.*, tbl_cartegory.cartegory_name, tbl_cartegory_main.cartegory_main_name
                      FROM tbl_brand
                      INNER JOIN tbl_cartegory ON tbl_brand.cartegory_id = tbl_cartegory.cartegory_id
                      INNER JOIN tbl_cartegory_main ON tbl_brand.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
                      ORDER BY tbl_brand.brand_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_cartegories_by_cartegory_main_id($cartegory_main_id)
    {
        $query = "SELECT * FROM tbl_cartegory WHERE cartegory_main_id = '$cartegory_main_id' ORDER BY cartegory_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_brand($brand_id)
    {
        $query = "SELECT * FROM tbl_brand WHERE brand_id = '$brand_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_brand($cartegory_main_id, $cartegory_id, $brand_name, $brand_id)
    {
        $query = "UPDATE tbl_brand SET cartegory_main_id = '$cartegory_main_id', cartegory_id = '$cartegory_id', brand_name = '$brand_name' WHERE brand_id = '$brand_id'";
        $result = $this->db->update($query);
        header('Location: brandlist.php');
        return $result;
    }

    public function delete_brand($brand_id)
    {
        $query = "DELETE FROM tbl_brand WHERE brand_id = '$brand_id'";
        $result = $this->db->delete($query);
        header('Location: brandlist.php');
        return $result;
    }

    public function searchBrandsByName($search_query)
{
    // Sử dụng câu truy vấn SQL để tìm kiếm thương hiệu theo brand_name
    $query = "SELECT tbl_brand.*, tbl_cartegory.cartegory_name, tbl_cartegory_main.cartegory_main_name FROM tbl_brand
              INNER JOIN tbl_cartegory ON tbl_brand.cartegory_id = tbl_cartegory.cartegory_id
              INNER JOIN tbl_cartegory_main ON tbl_brand.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
              WHERE tbl_brand.brand_name LIKE '%$search_query%'";
    $result = $this->db->select($query);
    return $result;
}
}


?> 