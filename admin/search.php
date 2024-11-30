<?php
// Kết nối cơ sở dữ liệu
require_once 'function.php'; 
$conn = connect();
mysqli_set_charset($conn, 'utf8');

// Kiểm tra tham số tìm kiếm từ người dùng
if (isset($_GET['textSearch'])) {
    $textSearch = htmlspecialchars($_GET['textSearch']); // Xử lý dữ liệu đầu vào
    $textSearch = mysqli_real_escape_string($conn, $textSearch);

    $sql = "SELECT * FROM sanpham sp
            JOIN danhmucsp dm ON sp.madm = dm.madm
            WHERE sp.tensp LIKE '%$textSearch%'";
    error_log("SQL Query: " . $sql); // Ghi query vào log để kiểm tra

    $result = mysqli_query($conn, $sql);

    // Kiểm tra kết quả và trả về dữ liệu
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['tensp']) . "</td>
                    <td>" . number_format($row['gia'], 0, ',', '.') . " VNĐ</td>
                    <td><img src='../" . htmlspecialchars($row['anhchinh']) . "' alt='" . htmlspecialchars($row['tensp']) . "' style='width: 100px; height: auto;'></td>
                    <td>
                       <span><a class='btn btn-warning' href='#sua_sp-area' onclick=\"display_edit_sanpham('" . $row['masp'] . "')\">Sửa</a></span>
                       <span class='btn btn-danger' onclick=\"xoa_sp('" . $row['masp'] . "')\">Xóa</span>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr>
                <td colspan='4' class='text-center'>Không tìm thấy sản phẩm phù hợp</td>
              </tr>";
    }
} else {
    echo "<tr>
            <td colspan='4' class='text-center'>Vui lòng nhập từ khóa tìm kiếm</td>
          </tr>";
}

// Đóng kết nối
disconnect($conn);
?>