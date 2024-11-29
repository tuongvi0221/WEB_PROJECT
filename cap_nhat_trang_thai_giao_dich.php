<?php
// Giả sử mã sản phẩm và trạng thái giao dịch được lấy từ đơn đặt hàng
$masp = 'SP123';
$tinhtrang = 0; // Trạng thái giao dịch chưa hoàn thành
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'qlbh');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Cập nhật trạng thái giao dịch
$sql = "UPDATE giaodich SET tinhtrang = 1 WHERE masp = '$masp' AND tinhtrang = $tinhtrang";
mysqli_query($conn, $sql);
mysqli_close($conn);
?>