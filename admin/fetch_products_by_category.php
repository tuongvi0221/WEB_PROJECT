<?php
// fetch_products_by_category.php
require_once 'function.php'; // Kết nối đến cơ sở dữ liệu

if (isset($_GET['category_id']) && !empty($_GET['category_id'])) {
    $category_id = $_GET['category_id'];

    // Kết nối cơ sở dữ liệu
    $conn = connect();
    mysqli_set_charset($conn, 'utf8');

    // Bảo vệ tham số 'category_id' để tránh SQL Injection
    $category_id = mysqli_real_escape_string($conn, $category_id);

    // Truy vấn để lấy sản phẩm theo danh mục
    $sql = "SELECT * FROM sanpham sp
            JOIN danhmucsp dm ON sp.madm = dm.madm
            WHERE sp.madm = '$category_id'";

    $result = mysqli_query($conn, $sql);

    // Kiểm tra nếu có sản phẩm trong danh mục
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Lấy thông tin sản phẩm và bảo vệ HTML
            $masp = htmlspecialchars($row['masp'] ?? '');
            $tensp = htmlspecialchars($row['tensp'] ?? '');
            $gia = htmlspecialchars($row['gia'] ?? 0);
            $anhchinh = htmlspecialchars($row['anhchinh'] ?? '');

            // In ra dữ liệu
            echo "<tr>";
            echo "<td>$tensp</td>";
            echo "<td>" . number_format($gia, 0, ',', '.') . " VND</td>";
            echo "<td><img src='../$anhchinh' alt='$tensp' style='width: 100px; height: auto;'></td>";
            
            // Sửa xử lý onclick để tránh lỗi dấu nháy trong JavaScript
            echo "<td><span class='btn btn-danger' onclick='xoa_sp(\"$masp\")'>Xóa</span></td>";
            echo "<td><span><a class='btn btn-warning' href='#sua_sp-area' onclick=\"display_edit_sanpham('$masp')\">Sửa</a></span></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr>
                <td colspan='4'>Không tìm thấy sản phẩm nào trong danh mục này.</td>
              </tr>";
    }

    // Đóng kết nối cơ sở dữ liệu
    disconnect($conn);
}
?>