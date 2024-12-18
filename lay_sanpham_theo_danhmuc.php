<?php
include 'connect.php';

if (isset($_POST['tendm'])) {
    $tendm = $_POST['tendm'];

    // Truy vấn sản phẩm theo tên danh mục
    if ($tendm === 'all') {
        $sql = "SELECT masp, tensp, gia FROM sanpham"; // Lấy tất cả sản phẩm
    } else {
        $sql = "SELECT masp, tensp, gia FROM sanpham WHERE tendm = '$tendm'";
    }
    $result = mysqli_query($conn, $sql);

    // Hiển thị sản phẩm theo danh mục
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='product'>";
        echo "<h3>{$row['tensp']}</h3>";
        echo "<p>Giá: {$row['gia']}₫</p>";
        echo "</div>";
    }
}
?>