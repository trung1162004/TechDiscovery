<?php
include 'database.php';
?>

<?php
class product
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function show_cartegory()
    {
        $query = "SELECT * FROM tbl_cartegory ORDER BY cartegory_id DESC ";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_brand()
    {
        //$query = "SELECT * FROM tbl_brand ORDER BY brand_id DESC ";
        $query = "SELECT tbl_brand.*, tbl_cartegory.cartegory_name
              FROM tbl_brand
              INNER JOIN tbl_cartegory ON tbl_brand.cartegory_id = tbl_cartegory.cartegory_id
              ORDER BY tbl_brand.brand_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_cartegory_main()
    {
        $query = "SELECT * FROM tbl_cartegory_main ORDER BY cartegory_main_id DESC ";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_color()
    {
        $query = "SELECT * FROM tbl_color ORDER BY color_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    // Hàm để lấy danh sách các bộ nhớ RAM từ bảng tbl_memory_ram
    public function show_memory_ram()
    {
        $query = "SELECT * FROM tbl_memory_ram ORDER BY memory_ram_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function show_product()
    {
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name
        FROM tbl_product
        INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
        INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
        INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
        ORDER BY tbl_product.product_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function insert_product($post_data, $files_data)
    {
        $errors = array();

        // Validate input data
        $product_name = isset($post_data['product_name']) ? trim($post_data['product_name']) : '';
        $cartegory_main_id = isset($post_data['cartegory_main_id']) ? trim($post_data['cartegory_main_id']) : '';
        $cartegory_id = isset($post_data['cartegory_id']) ? trim($post_data['cartegory_id']) : '';
        $brand_id = isset($post_data['brand_id']) ? trim($post_data['brand_id']) : '';
        $product_price = isset($post_data['product_price']) ? floatval($post_data['product_price']) : 0;
        $product_price_sale = isset($post_data['product_price_sale']) ? floatval($post_data['product_price_sale']) : 0;
        $product_color = isset($_POST['product_color']) ? $_POST['product_color'] : array();
        $product_memory_ram = isset($_POST['product_memory_ram']) ? $_POST['product_memory_ram'] : array();
        $product_quantity = isset($post_data['product_quantity']) ? intval($post_data['product_quantity']) : 0;
        $product_intro = isset($post_data['product_intro']) ? trim($post_data['product_intro']) : '';
        $product_detail = isset($post_data['product_detail']) ? trim($post_data['product_detail']) : '';
        $product_accessory = isset($post_data['product_accessory']) ? trim($post_data['product_accessory']) : '';
        $product_guarantee = isset($post_data['product_guarantee']) ? trim($post_data['product_guarantee']) : '';
        $product_img = isset($_FILES['product_img']['name']) ? $_FILES['product_img']['name'] : '';

        // Check required fields
        if (empty($product_name)) {
            $errors[] = "Product name cannot be empty.";
        }

        // Check for duplicate product_name
        $query_check_name = "SELECT COUNT(*) AS count FROM tbl_product WHERE product_name = '$product_name'";
        $result_check_name = $this->db->select($query_check_name);
        $row_check_name = $result_check_name->fetch_assoc();
        if ($row_check_name['count'] > 0) {
            $errors[] = "Product name already exists.";
        }

        if (empty($cartegory_main_id)) {
            $errors[] = "Please select the main category.";
        }
        if (empty($cartegory_id)) {
            $errors[] = "Please select the category.";
        }
        if (empty($brand_id)) {
            $errors[] = "Please select the brand.";
        }
        if (empty($product_price) || !is_numeric($product_price) || $product_price <= 0 || $product_price >= 5000) {
            $errors[] = "Invalid product price.";
        }
        if (empty($product_price_sale) || !is_numeric($product_price_sale) || $product_price_sale < 0 || $product_price_sale >= 5000) {
            $errors[] = "Invalid sale price.";
        }
        if (empty($product_color)) {
            $errors[] = "Color cannot be empty.";
        }
        if (empty($product_memory_ram)) {
            $errors[] = "Memory RAM cannot be empty.";
        }
        if (empty($product_quantity) || !is_numeric($product_quantity) || $product_quantity < 0) {
            $errors[] = "Invalid product quantity.";
        }

        // Handle errors if any
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            return false;
        }
        move_uploaded_file($_FILES['product_img']['tmp_name'], "uploads/" . $_FILES['product_img']['name']);

        $query = "INSERT INTO tbl_product 
                (product_name, 
                cartegory_main_id,
                cartegory_id, 
                brand_id, 
                product_price, 
                product_price_sale, 
                product_color,
                product_memory_ram,
                product_quantity,
                product_intro,
                product_detail,
                product_accessory,
                product_guarantee,
                product_img) VALUES (
                    '$product_name', 
                    '$cartegory_main_id',
                    '$cartegory_id',
                    '$brand_id',
                    '$product_price',
                    '$product_price_sale',
                    '$product_color',
                    '$product_memory_ram',
                    '$product_quantity',
                    '$product_intro',
                    '$product_detail',
                    '$product_accessory',
                    '$product_guarantee',
                    '$product_img')";
        $result = $this->db->insert($query);
        if ($result) {
            $query = "SELECT * FROM tbl_product ORDER BY product_id DESC LIMIT 1";
            $result = $this->db->select($query)->fetch_assoc();
            $product_id = $result['product_id'];

            // Xử lý upload ảnh mô tả cho từng sản phẩm
            if (isset($_FILES['product_img_desc'])) {
                $product_img_descs = $_FILES['product_img_desc'];

                // Sử dụng vòng lặp để xử lý từng ảnh mô tả
                foreach ($product_img_descs['tmp_name'] as $key => $tmp_name) {
                    // Kiểm tra xem có lỗi upload không
                    if ($product_img_descs['error'][$key] === UPLOAD_ERR_OK) {
                        // Lấy tên và đường dẫn tạm thời của ảnh mô tả
                        $filename = $product_img_descs['name'][$key];
                        $filetmp = $product_img_descs['tmp_name'][$key];

                        // Upload ảnh mô tả vào thư mục "uploads"
                        move_uploaded_file($filetmp, "uploads/" . $filename);

                        // Thêm thông tin ảnh mô tả vào cơ sở dữ liệu
                        $query = "INSERT INTO tbl_product_img_desc (product_id, product_img_desc) VALUES ('$product_id', '$filename')";
                        $this->db->insert($query);
                    }
                }
            }
        }

        header('Location: productlist.php');
        return $result;
    }

    public function getProductsByCategory($category)
    {
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name
    FROM tbl_product
    INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
    INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
    INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
    WHERE tbl_cartegory.cartegory_name = '$category'
    ORDER BY tbl_product.product_id DESC";

        $result = $this->db->select($query);
        return $result;
    }

    public function get_product_detail($product_id)
    {
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name
                      FROM tbl_product
                      INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
                      INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
                      WHERE tbl_product.product_id = '$product_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_product($product_id)
    {
        $query = "SELECT * FROM tbl_product WHERE product_id = '$product_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_product_imgs_desc($product_id)
    {
        $query = "SELECT product_img_desc FROM tbl_product_img_desc WHERE product_id = '$product_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function update_product($post_data, $files_data, $product_id)
    {
        $errors = array();

        $product_name = $post_data['product_name'];
        $cartegory_id = $post_data['cartegory_id'];
        $brand_id = $post_data['brand_id'];
        $product_price = $post_data['product_price'];
        $product_price_sale = $post_data['product_price_sale'];
        $product_color = isset($_POST['product_color']) ? implode(', ', $_POST['product_color']) : '';
        $product_memory_ram = isset($_POST['product_memory_ram']) ? implode(', ', $_POST['product_memory_ram']) : '';
        $product_quantity = $post_data['product_quantity'];
        $product_intro = $post_data['product_intro'];
        $product_detail = $post_data['product_detail'];
        $product_accessory = $post_data['product_accessory'];
        $product_guarantee = $post_data['product_guarantee'];

        // Validate input fields
        if (empty($product_name)) {
            $errors[] = "Product name cannot be empty.";
        }
        if (empty($cartegory_id)) {
            $errors[] = "Please select the category.";
        }
        if (empty($brand_id)) {
            $errors[] = "Please select the brand.";
        }
        if (empty($product_price) || !is_numeric($product_price) || $product_price <= 0 || $product_price >= 5000) {
            $errors[] = "Invalid product price.";
        }
        if (empty($product_price_sale) || !is_numeric($product_price_sale) || $product_price_sale < 0 || $product_price_sale >= 5000) {
            $errors[] = "Invalid sale price.";
        }
        if (empty($product_color)) {
            $errors[] = "Color cannot be empty.";
        }
        if (empty($product_memory_ram)) {
            $errors[] = "Memory RAM cannot be empty.";
        }
        if (empty($product_quantity) || !is_numeric($product_quantity) || $product_quantity < 0) {
            $errors[] = "Invalid product quantity.";
        }

        // Handle errors if any
        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
            return false;
        }

        // Kiểm tra nếu người dùng đã chọn ảnh sản phẩm mới
        if (isset($files_data['product_img']['name']) && !empty($files_data['product_img']['name'])) {
            // Xóa ảnh sản phẩm cũ trước khi cập nhật ảnh mới
            $old_img_path = "uploads/" . $this->get_product_img_by_id($product_id);
            if (file_exists($old_img_path)) {
                unlink($old_img_path);
            }

            $product_img = $files_data['product_img']['name'];
            move_uploaded_file($files_data['product_img']['tmp_name'], "uploads/" . $files_data['product_img']['name']);
        } else {
            // Nếu người dùng không chọn ảnh mới, giữ nguyên ảnh cũ
            $product_img = $this->get_product_img_by_id($product_id);
        }

        // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
        $query = "UPDATE tbl_product 
              SET product_name = '$product_name',
                  cartegory_id = '$cartegory_id',
                  brand_id = '$brand_id',
                  product_price = '$product_price',
                  product_price_sale = '$product_price_sale',
                  product_color = '$product_color',
                  product_memory_ram = '$product_memory_ram',
                  product_quantity = '$product_quantity',
                  product_intro = '$product_intro',
                  product_detail = '$product_detail',
                  product_accessory = '$product_accessory',
                  product_guarantee = '$product_guarantee',
                  product_img = '$product_img'
              WHERE product_id = '$product_id'";

        $result = $this->db->update($query);

        // Xóa các ảnh mô tả cũ trước khi cập nhật các ảnh mô tả mới
        if (isset($files_data['product_img_desc']['name'][0]) && !empty($files_data['product_img_desc']['name'][0])) {
            $this->delete_product_imgs_desc_by_product_id($product_id);
        }

        // Lưu các ảnh mô tả mới vào cơ sở dữ liệu
        if (isset($files_data['product_img_desc'])) {
            $product_img_descs = $files_data['product_img_desc'];

            foreach ($product_img_descs['tmp_name'] as $key => $tmp_name) {
                // Kiểm tra xem có lỗi upload không
                if ($product_img_descs['error'][$key] === UPLOAD_ERR_OK) {
                    // Lấy tên và đường dẫn tạm thời của ảnh mô tả
                    $filename = $product_img_descs['name'][$key];
                    $filetmp = $product_img_descs['tmp_name'][$key];

                    // Upload ảnh mô tả vào thư mục "uploads"
                    move_uploaded_file($filetmp, "uploads/" . $filename);

                    // Thêm thông tin ảnh mô tả vào cơ sở dữ liệu
                    $query = "INSERT INTO tbl_product_img_desc (product_id, product_img_desc) VALUES ('$product_id', '$filename')";
                    $this->db->insert($query);
                }
            }
        }

        header('Location: productlist.php');
        return $result;
    }



    public function get_product_img_by_id($product_id)
    {
        $query = "SELECT product_img FROM tbl_product WHERE product_id = '$product_id'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['product_img'];
        }
        return '';
    }

    // Hàm xóa các ảnh mô tả theo product_id
    public function delete_product_imgs_desc_by_product_id($product_id)
    {
        $query = "DELETE FROM tbl_product_img_desc WHERE product_id = '$product_id'";
        $result = $this->db->delete($query);
    }


    public function delete_product($product_id)
    {
        $query = "DELETE FROM tbl_product WHERE product_id = '$product_id'";
        $result = $this->db->delete($query);
        header('Location: productlist.php');
        return $result;
    }

    //ham lay cartegory_id de show brand 
    public function get_brands_by_category($cartegory_id)
    {
        $query = "SELECT * FROM tbl_brand WHERE cartegory_id = '$cartegory_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_cartegories_by_cartegory_main_id($cartegory_main_id)
    {
        $query = "SELECT * FROM tbl_cartegory WHERE cartegory_main_id = '$cartegory_main_id' ORDER BY cartegory_id DESC";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_colors_by_product_id($product_id)
    {
        $query = "SELECT product_color FROM tbl_product WHERE product_id = '$product_id'";
        $result = $this->db->select($query)->fetch_assoc();
        return explode(', ', $result['product_color']);
    }


    public function get_memory_rams_by_product_id($product_id)
    {
        $query = "SELECT product_memory_ram FROM tbl_product WHERE product_id = '$product_id'";
        $result = $this->db->select($query)->fetch_assoc();
        return explode(', ', $result['product_memory_ram']);
    }

    public function get_color_name_by_id($color_id)
    {
        $query = "SELECT color_name FROM tbl_color WHERE color_id = '$color_id'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['color_name'];
        }
        return '';
    }

    public function get_all_colors()
    {
        $query = "SELECT * FROM tbl_color";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_memory_ram_name_by_id($memory_ram_id)
    {
        $query = "SELECT memory_ram_name FROM tbl_memory_ram WHERE memory_ram_id = '$memory_ram_id'";
        $result = $this->db->select($query);
        if ($result) {
            $row = $result->fetch_assoc();
            return $row['memory_ram_name'];
        }
        return '';
    }

    public function getProductIDByName($product_name)
    {
        $query = "SELECT product_id FROM tbl_product WHERE product_name = '$product_name'";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['product_id'];
        }
        return null;
    }

    public function showProductOptions()
    {
        $query = "SELECT product_id, product_name FROM tbl_product";
        $result = $this->db->select($query);
        return $result;
    }

    public function getProductsForPage($limit, $offset)
    {
        $query = "SELECT p.*, c.cartegory_name, b.brand_name, cm.cartegory_main_name
              FROM tbl_product AS p
              INNER JOIN tbl_cartegory AS c ON p.cartegory_id = c.cartegory_id
              INNER JOIN tbl_brand AS b ON p.brand_id = b.brand_id
              INNER JOIN tbl_cartegory_main AS cm ON c.cartegory_main_id = cm.cartegory_main_id
              ORDER BY p.product_id DESC LIMIT $limit OFFSET $offset";

        $result = $this->db->select($query);
        return $result;
    }


    public function getTotalProducts()
    {
        $query = "SELECT COUNT(*) as total FROM tbl_product";
        $result = $this->db->select($query)->fetch_assoc();
        return $result['total'];
    }

    public function getTotalProductsByCategory($category)
    {
        $query = "SELECT COUNT(*) as total FROM tbl_product
              INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
              WHERE tbl_cartegory.cartegory_name = '$category'";

        $result = $this->db->select($query)->fetch_assoc();
        return $result['total'];
    }

    public function searchProductsByName($search_query)
    {
        // Sử dụng câu truy vấn SQL để tìm kiếm sản phẩm theo tên
        $query = "SELECT * FROM tbl_product WHERE product_name LIKE '%$search_query%'";

        // Sử dụng phương thức select của lớp Database để thực hiện truy vấn
        $result = $this->db->select($query);

        return $result;
    }

    // Hàm để lấy tổng số sản phẩm từ kết quả tìm kiếm
    public function getTotalSearchProducts($search_query)
    {
        $query = "SELECT COUNT(*) as total FROM tbl_product WHERE product_name LIKE '%$search_query%'";
        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getProductsByBrand($selectedBrand)
    {
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name
    FROM tbl_product
    INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
    INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
    INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
    WHERE tbl_brand.brand_name = '$selectedBrand'
    ORDER BY tbl_product.product_id DESC";

        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalProductsByBrand($selectedBrand)
    {
        $query = "SELECT COUNT(*) as total FROM tbl_product
    INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
    WHERE tbl_brand.brand_name = '$selectedBrand'";

        $result = $this->db->select($query);
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    public function getProductsByPriceLowToHigh($limit, $offset, $sortField = 'product_price', $sortOrder = 'ASC')
    {
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name
              FROM tbl_product
              INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
              INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
              INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
              ORDER BY $sortField $sortOrder
              LIMIT $limit OFFSET $offset";

        $result = $this->db->select($query);
        return $result;
    }

    public function getProductsByPriceHighToLow($limit, $offset)
    {
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name
              FROM tbl_product
              INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
              INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
              INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
              ORDER BY tbl_product.product_price DESC
              LIMIT $limit OFFSET $offset";

        $result = $this->db->select($query);
        return $result;
    }

    public function getSimilarProductsByCategory($product_id)
    {
        $query = "SELECT * FROM tbl_product WHERE cartegory_id = (SELECT cartegory_id FROM tbl_product WHERE product_id = '$product_id') AND product_id != '$product_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function getSimilarProductsByBrand($product_id)
    {
        $query = "SELECT * FROM tbl_product WHERE brand_id = (SELECT brand_id FROM tbl_product WHERE product_id = '$product_id') AND product_id != '$product_id'";
        $result = $this->db->select($query);
        return $result;
    }

    public function get_products_by_brand($brand_name)
    {
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name
                  FROM tbl_product
                  INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
                  INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
                  INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
                  WHERE tbl_brand.brand_name = '$brand_name'
                  ORDER BY tbl_product.product_id DESC";

        $result = $this->db->select($query);
        return $result;
    }

    //pagination admin
    public function getPaginatedProducts(
        $page,
        $productsPerPage,
        $sortOption = '',
        $filterColor = '',
        $filterMemory = '',
        $filterBrand = '',
        $filterPriceMin = 0,
        $filterPriceMax = PHP_INT_MAX,
        $selectedFields = ''
    ) {
        // Tính toán offset dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang.
        $offset = ($page - 1) * $productsPerPage;

        // Xây dựng truy vấn SQL dựa trên các thông tin sắp xếp và bộ lọc.
        $selectedFields = !empty($selectedFields) ? $selectedFields : 'tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name';

        $query = "SELECT $selectedFields
                  FROM tbl_product
                  INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
                  INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
                  INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
                  WHERE 1";

        // Thêm điều kiện bộ lọc màu sắc nếu được chọn
        if (!empty($filterColor)) {
            // Sử dụng LIKE để tìm kiếm các sản phẩm có màu sắc tương tự
            $query .= " AND tbl_product.product_color LIKE '%$filterColor%'";
        }

        // Thêm điều kiện bộ lọc bộ nhớ RAM nếu được chọn
        if (!empty($filterMemory)) {
            $query .= " AND tbl_product.product_memory_ram LIKE '%$filterMemory%'";
        }

        // Thêm điều kiện bộ lọc thương hiệu nếu được chọn
        if (!empty($filterBrand)) {
            $query .= " AND tbl_brand.brand_id = '$filterBrand'";
        }

        // Thêm điều kiện bộ lọc giá
        $query .= " AND tbl_product.product_price_sale BETWEEN $filterPriceMin AND $filterPriceMax";

        // Thêm điều kiện sắp xếp nếu được chọn
        if (!empty($sortOption)) {
            switch ($sortOption) {
                case 'price_asc':
                    $query .= " ORDER BY tbl_product.product_price_sale ASC";
                    break;
                case 'price_desc':
                    $query .= " ORDER BY tbl_product.product_price_sale DESC";
                    break;
                    // Thêm các trường hợp sắp xếp khác ở đây nếu cần
            }
        } else {
            // Mặc định sắp xếp theo ID giảm dần nếu không có yêu cầu sắp xếp
            $query .= " ORDER BY tbl_product.product_id DESC";
        }

        // Thêm LIMIT và OFFSET vào truy vấn để phân trang
        $query .= " LIMIT $productsPerPage OFFSET $offset";

        // Thực hiện truy vấn SQL
        $result = $this->db->select($query);
        return $result;
    }

    public function searchProductsByNamePaginated(
        $searchTerm,
        $currentPage,
        $productsPerPage,
        $filterColor = '',
        $filterMemory = '',
        $filterBrand = '',
        $filterPriceMin = 0,
        $filterPriceMax = PHP_INT_MAX
    ) {
        // Tính toán offset dựa trên trang hiện tại và số lượng sản phẩm trên mỗi trang.
        $offset = ($currentPage - 1) * $productsPerPage;

        // Xây dựng truy vấn SQL dựa trên các thông tin sắp xếp và bộ lọc.
        $query = "SELECT tbl_product.*, tbl_cartegory.cartegory_name, tbl_brand.brand_name, tbl_cartegory_main.cartegory_main_name
            FROM tbl_product
            INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
            INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
            INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
            WHERE tbl_product.product_name LIKE '%$searchTerm%'";

        // Thêm điều kiện bộ lọc cho thương hiệu nếu được chọn
        if (!empty($filterBrand)) {
            $query .= " AND tbl_brand.brand_id = '$filterBrand'";
        }

        // Thêm điều kiện bộ lọc cho màu sắc nếu được chọn
        if (!empty($filterColor)) {
            // Sử dụng LIKE để tìm kiếm các sản phẩm có màu sắc tương tự
            $query .= " AND tbl_product.product_color LIKE '%$filterColor%'";
        }

        // Thêm điều kiện bộ lọc cho bộ nhớ RAM nếu được chọn
        if (!empty($filterMemory)) {
            $query .= " AND tbl_product.product_memory_ram LIKE '%$filterMemory%'";
        }

        // Thêm điều kiện bộ lọc cho giá
        $query .= " AND tbl_product.product_price_sale BETWEEN $filterPriceMin AND $filterPriceMax";

        // Thêm LIMIT và OFFSET vào truy vấn để phân trang
        $query .= " LIMIT $productsPerPage OFFSET $offset";

        // Thực hiện truy vấn SQL
        $result = $this->db->select($query);
        return $result;
    }

    public function getTotalFilteredProducts(
        $searchTerm,
        $filterColor = '',
        $filterMemory = '',
        $filterBrand = '',
        $filterPriceMin = 0,
        $filterPriceMax = PHP_INT_MAX
    ) {
        // Xây dựng truy vấn SQL dựa trên các thông tin sắp xếp và bộ lọc.
        $query = "SELECT COUNT(*) as total
        FROM tbl_product
        INNER JOIN tbl_cartegory ON tbl_product.cartegory_id = tbl_cartegory.cartegory_id
        INNER JOIN tbl_brand ON tbl_product.brand_id = tbl_brand.brand_id
        INNER JOIN tbl_cartegory_main ON tbl_cartegory.cartegory_main_id = tbl_cartegory_main.cartegory_main_id
        WHERE tbl_product.product_name LIKE '%$searchTerm%'";

        // Thêm điều kiện bộ lọc cho thương hiệu nếu được chọn
        if (!empty($filterBrand)) {
            $query .= " AND tbl_brand.brand_id = '$filterBrand'";
        }

        // Thêm điều kiện bộ lọc cho màu sắc nếu được chọn
        if (!empty($filterColor)) {
            // Sử dụng LIKE để tìm kiếm các sản phẩm có màu sắc tương tự
            $query .= " AND tbl_product.product_color LIKE '%$filterColor%'";
        }

        // Thêm điều kiện bộ lọc cho bộ nhớ RAM nếu được chọn
        if (!empty($filterMemory)) {
            $query .= " AND tbl_product.product_memory_ram LIKE '%$filterMemory%'";
        }

        // Thêm điều kiện bộ lọc cho giá
        $query .= " AND tbl_product.product_price_sale BETWEEN $filterPriceMin AND $filterPriceMax";

        // Thực hiện truy vấn SQL
        $result = $this->db->select($query);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['total'];
        } else {
            return 0;
        }
    }



    public function getSimilarProducts($product_id)
    {
        $query = "SELECT brand_id FROM tbl_product WHERE product_id = '$product_id'";
        $result = $this->db->select($query);
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $brand_id = $row['brand_id'];

            // Sau đó, lấy các sản phẩm khác có cùng thương hiệu
            $query = "SELECT * FROM tbl_product WHERE brand_id = '$brand_id' AND product_id != '$product_id'";
            $result = $this->db->select($query);
            return $result;
        }
        return null;
    }
}


?> 