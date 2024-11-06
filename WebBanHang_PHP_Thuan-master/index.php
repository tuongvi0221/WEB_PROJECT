<?php
function connect() {
    $conn = mysqli_connect('localhost', 'root', '', 'qlbh');
    if (!$conn) {
        die('Kết nối thất bại: ' . mysqli_connect_error());
    }
    mysqli_set_charset($conn, 'utf8');
    return $conn;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$_SESSION['rights'] = "default";
$_SESSION['limit'] = 8;

// Kết nối cơ sở dữ liệu
$conn = connect(); // Gọi hàm connect() ở đây
$_SESSION['sql'] = "SELECT * FROM sanpham";
$sql = "SELECT * FROM sanpham";
$result = mysqli_query($conn, $sql);
if (!$result) {
    die('Query failed: ' . mysqli_error($conn)); // Kiểm tra lỗi truy vấn
}
$_SESSION['total'] = mysqli_num_rows($result);

// Gọi backend-index.php
require_once 'backend-index.php';

// Kiểm tra và khởi tạo giỏ hàng
if (!isset($_SESSION['client_cart'])) {
    $_SESSION['client_cart'] = []; // Khởi tạo là mảng
    $_SESSION['client_cart'][0] = "tmp"; // Thêm phần tử vào giỏ hàng
}

$_SESSION['user_cart'] = []; // Khởi tạo là mảng
$_SESSION['user_cart'][0] = "tmp";

if (isset($_SESSION['user'])) {
    $_SESSION['rights'] = "user";
    $_SESSION['like'] = []; // Khởi tạo là mảng
    $_SESSION['like'][0] = "tmp";

    // Lấy giỏ hàng của người dùng
    $sql = "SELECT masp, soluong FROM giohang WHERE user_id = '" . $_SESSION['user']['id'] . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die('Query failed: ' . mysqli_error($conn)); // Kiểm tra lỗi truy vấn
    }
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['user_cart'][] = $row['masp']; // Thêm sản phẩm vào giỏ hàng
    }

    // Lấy sản phẩm yêu thích của người dùng
    $sql = "SELECT masp FROM sanphamyeuthich WHERE user_id = '" . $_SESSION['user']['id'] . "'";
    $result = mysqli_query($conn, $sql);
    if (!$result) {
        die('Query failed: ' . mysqli_error($conn)); // Kiểm tra lỗi truy vấn
    }
    while ($row = mysqli_fetch_assoc($result)) {
        $_SESSION['like'][] = $row['masp']; // Thêm sản phẩm yêu thích
    }
}
?>