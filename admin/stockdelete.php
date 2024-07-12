<?php
include "class/stock_class.php";

if (isset($_GET['stock_id'])) {
    $stock_id = $_GET['stock_id'];
    $stock = new Stock();

    // Lấy thông tin của stock để xác nhận xóa
    $stockInfo = $stock->getStockInfo($stock_id);

    if ($stockInfo) {
        // Thực hiện xóa stock
        $result = $stock->deleteStock($stock_id);

        if ($result) {
            // Xóa thành công, chuyển hướng về trang stocklist.php
            header("Location: stocklist.php");
            exit();
        } else {
            // Xóa không thành công, thông báo lỗi
            echo "Failed to delete stock.";
        }
    } else {
        // Stock không tồn tại, chuyển hướng về trang stocklist.php
        header("Location: stocklist.php");
        exit();
    }
} else {
    // Không có thông tin stock_id, chuyển hướng về trang stocklist.php
    header("Location: stocklist.php");
    exit();
}
?>
