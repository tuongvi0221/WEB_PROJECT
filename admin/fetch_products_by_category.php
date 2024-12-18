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
            $masp     = htmlspecialchars($row['masp'] ?? '');
            $tensp    = htmlspecialchars($row['tensp'] ?? '');
            $gia      = htmlspecialchars($row['gia'] ?? 0);
            $anhchinh = htmlspecialchars($row['anhchinh'] ?? '');

            // In ra dữ liệu
            echo "<tr>";
            echo "<td>$tensp</td>";
            echo "<td>" . number_format($gia, 0, ',', '.') . " VND</td>";
            echo "<td><img src='../$anhchinh' alt='$tensp' style='width: 100px; height: auto;'></td>";
            
            // Nút Xóa và Sửa với CSS đồng nhất
            echo "<td>
            <span class='btn btn-danger' onclick='xoa_sp(\"$masp\")'>Xóa</span>
            <span><a class='btn btn-warning' href='sua_sanpham.php?masp=$masp'>Sửa</a></span>
          </td>";
    
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

<!-- Thêm CSS trực tiếp vào file -->
<style>
/* Đảm bảo các nút có kích thước đồng đều */
.table td .btn {
    padding: 5px 10px;
    font-size: 14px;
    text-align: center;
    cursor: pointer;
    border-radius: 5px;
    display: inline-block;
    width: 80px;
    /* Đảm bảo các nút có cùng chiều rộng */
}

/* Màu nền cho nút Xóa và Sửa */
.table td .btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.table td .btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
}

/* Khi hover, thay đổi màu nền của các nút */
.table td .btn:hover {
    opacity: 0.8;
    /* Giảm độ mờ khi hover */
}
</style>