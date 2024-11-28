<?php
session_start();
require_once 'backend-index.php';
// Kết nối cơ sở dữ liệu
$conn = connect();
mysqli_set_charset($conn, 'utf8');
// Nhận dữ liệu từ form
$masp = mysqli_real_escape_string($conn, $_POST['masp']);
$tensp = mysqli_real_escape_string($conn, $_POST['tensp']);
$gia = mysqli_real_escape_string($conn, $_POST['gia']);
$khuyenmai = mysqli_real_escape_string($conn, $_POST['khuyenmai']);
$so_luong = 1; // Mặc định số lượng là 1
$ngay_dat = date('Y-m-d');
$ngay_du_kien_nhan = date('Y-m-d', strtotime($ngay_dat . ' + 5 days'));
$trang_thai = 'nhận'; // Trạng thái mặc định
// Chèn vào bảng `don_dat_hang`
$sql = "INSERT INTO don_dat_hang (ma_san_pham, ten_san_pham, so_luong, ngay_dat, ngay_du_kien_nhan, trang_thai)
        VALUES ('$masp', '$tensp', '$so_luong', '$ngay_dat', '$ngay_du_kien_nhan', '$trang_thai')";
if (mysqli_query($conn, $sql)) {
    echo "Đặt hàng thành công!";
    header("Location: lich_su_mua_hang.php"); // Chuyển về trang lịch sử mua hàng
    exit();
} else {
    echo "Lỗi: " . mysqli_error($conn);
}
disconnect($conn);
?>