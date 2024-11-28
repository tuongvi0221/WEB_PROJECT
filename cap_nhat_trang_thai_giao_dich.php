<?php
// Giả sử mã sản phẩm và trạng thái giao dịch được lấy từ đơn đặt hàng
$ma_sp = 'SP123';
$trang_thai = 0; // Trạng thái giao dịch chưa hoàn thành
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'qlbh');
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
// Cập nhật trạng thái giao dịch
$sql = "UPDATE giao_dich SET trang_thai = 1 WHERE ma_sp = '$ma_sp' AND trang_thai = $trang_thai";
mysqli_query($conn, $sql);
mysqli_close($conn);
?>