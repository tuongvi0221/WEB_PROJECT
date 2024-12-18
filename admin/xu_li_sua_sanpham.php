<?php
require_once 'function.php'; // Kết nối đến cơ sở dữ liệu

if (isset($_POST['masp']) && !empty($_POST['masp'])) {
    $masp = $_POST['masp'];
    $tensp = $_POST['tensp'];
    $gia = $_POST['gia'];
    $khuyenmai = $_POST['khuyenmai'];
    $chatlieu = $_POST['chatlieu'];
    $mau = $_POST['mau'];
    $danhcho = $_POST['danhcho'];

    // Lấy ảnh cũ nếu không có thay đổi ảnh mới
    $anhchinh = $_POST['anhchinh'] ?? '';

    // Kiểm tra nếu có thay đổi ảnh
    if (isset($_FILES['anhchinh']) && $_FILES['anhchinh']['error'] == 0) {
        // Lưu ảnh mới vào thư mục uploads/
        $upload_dir = 'uploads/'; // Thư mục lưu ảnh
        $upload_file = $upload_dir . basename($_FILES['anhchinh']['name']);

        // Di chuyển ảnh từ tmp_name vào thư mục uploads/
        if (move_uploaded_file($_FILES['anhchinh']['tmp_name'], $upload_file)) {
            // Lưu đường dẫn ảnh vào cơ sở dữ liệu (đường dẫn tương đối)
            $anhchinh = $upload_file;
        } else {
            echo "Lỗi tải ảnh lên.";
            exit;
        }
    }

    // Kết nối cơ sở dữ liệu
    $conn = connect();
    mysqli_set_charset($conn, 'utf8');

    // Cập nhật thông tin sản phẩm vào cơ sở dữ liệu
    $sql = "UPDATE sanpham SET tensp='$tensp', gia='$gia', khuyenmai='$khuyenmai', chatlieu='$chatlieu', mau='$mau', danhcho='$danhcho', anhchinh='$anhchinh' WHERE masp='$masp'";

    if (mysqli_query($conn, $sql)) {
        // Chuyển hướng về trang foradmin.php sau khi thành công
        header("Location: foradmin.php");
        exit;
    } else {
        echo "Lỗi: " . mysqli_error($conn);
    }

    // Đóng kết nối cơ sở dữ liệu
    disconnect($conn);
}
?>