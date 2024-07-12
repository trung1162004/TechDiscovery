<?php
 include 'class/cartegory_class.php';

// Kiểm tra xem cartegory_main_id được gửi từ yêu cầu AJAX có tồn tại không
if(isset($_GET['cartegory_main_id'])){
    $cartegory_main_id = $_GET['cartegory_main_id'];
    
    // Tạo một đối tượng cartegory từ class cartegory
    $cartegory = new cartegory;
    
    // Gọi hàm get_cartegories_by_cartegory_main_id để lấy danh sách cartegory dựa trên cartegory_main_id
    $cartegories = $cartegory->get_cartegories_by_cartegory_main_id($cartegory_main_id);
    
    // Kiểm tra xem có kết quả trả về hay không
    if($cartegories){
        // Duyệt qua từng dòng dữ liệu và in ra các tùy chọn cartegory
        while($row = $cartegories->fetch_assoc()){
            echo '<option value="'.$row['cartegory_id'].'">'.$row['cartegory_name'].'</option>';
        }
    } else {
        // Trường hợp không có cartegory nào dựa trên cartegory_main_id, bạn có thể in ra một tùy chọn mặc định hoặc thông báo không có cartegory nào.
        echo '<option value="">-- No Category Available --</option>';
    }
}
?>
