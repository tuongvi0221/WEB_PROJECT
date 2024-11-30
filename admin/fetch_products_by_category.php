<?php
// fetch_products_by_category.php
require_once 'function.php'; // Kết nối đến cơ sở dữ liệu

if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    $conn = connect();
    mysqli_set_charset($conn, 'utf8');

    // Truy vấn để lấy sản phẩm theo danh mục
    $sql = "SELECT * FROM sanpham sp
            JOIN danhmucsp dm ON sp.madm = dm.madm
            WHERE sp.madm = '" . mysqli_real_escape_string($conn, $category_id) . "'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $masp = htmlspecialchars($row['masp'] ?? '');
            $tensp = htmlspecialchars($row['tensp'] ?? '');
            $gia = htmlspecialchars($row['gia'] ?? 0);
            $anhchinh = htmlspecialchars($row['anhchinh'] ?? '');
            echo "<tr>";
            echo "<td>$tensp</td>";
            echo "<td>" . number_format($gia, 0, ',', '.') . " VND</td>";
            echo "<td><img src='../$anhchinh' alt='$tensp' style='width: 100px; height: auto;'></td>";
            
            // Sử dụng dấu ngoặc đơn trong onclick để tránh xung đột với dấu ngoặc kép
            echo "<td><span class='btn btn-danger' onclick='xoa_sp(\"$masp\")'>Xóa</span></td>";
            echo "<td><button class='btn btn-warning btn-sm'>Sửa</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>
            <td colspan='4'>Không tìm thấy sản phẩm nào trong danh mục này.</td>
        </tr>";
    }

    disconnect($conn);
}
?>