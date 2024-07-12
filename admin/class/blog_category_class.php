<?php
include 'database.php';

class BlogCategory
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert_category_blog($blog_cate_name)
    {
        $query = "INSERT INTO tbl_blog_category (blog_cate_name) VALUES ('$blog_cate_name')";
        $result = $this->db->insert($query);
        header('Location: blog_category_list.php');
        return $result;
    }

    public function show_category_blog()
    {
        $query = "SELECT * FROM tbl_blog_category ORDER BY blog_cate_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_category_blog($blog_cate_id)
    {
        $query = "SELECT * FROM tbl_blog_category WHERE blog_cate_id = '$blog_cate_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_category_blog($blog_cate_name, $blog_cate_id)
    {
        $query = "UPDATE tbl_blog_category SET blog_cate_name = '$blog_cate_name' WHERE blog_cate_id = '$blog_cate_id'";
        $result = $this->db->update($query);
        header('Location: blog_category_list.php');
        return $result;
    }

    public function delete_category_blog($blog_cate_id)
    {
        $query = "DELETE FROM tbl_blog_category WHERE blog_cate_id = '$blog_cate_id'";
        $result = $this->db->delete($query);
        header('Location: blog_category_list.php');
        return $result;
    }
}
?>
