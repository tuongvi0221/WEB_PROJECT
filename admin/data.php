<?php
require_once 'function.php'; // Kết nối cơ sở dữ liệu

// Kết nối cơ sở dữ liệu
$conn = connect();
mysqli_set_charset($conn, 'utf8');

// Truy vấn để lấy tổng tiền theo tháng và năm, chỉ lấy giao dịch đã giao
$sql = "SELECT YEAR(date) AS year, MONTH(date) AS month, SUM(tongtien) AS total_amount
        FROM giaodich
        WHERE tinhtrang = 1
        GROUP BY YEAR(date), MONTH(date)
        ORDER BY year DESC, month DESC";

$result = mysqli_query($conn, $sql);

// Khởi tạo mảng dữ liệu
$months = [];
$totals = [];

// Lấy dữ liệu từ cơ sở dữ liệu
while ($row = mysqli_fetch_assoc($result)) {
    $months[] = $row['month'] . '/' . $row['year']; // Tháng/Năm
    $totals[] = $row['total_amount']; // Tổng tiền
}

// Đóng kết nối cơ sở dữ liệu
disconnect($conn);

// Trả về dữ liệu dưới dạng JSON
echo json_encode(['months' => $months, 'totals' => $totals]);
?>