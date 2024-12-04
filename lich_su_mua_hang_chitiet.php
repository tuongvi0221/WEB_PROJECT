<?php
$id = $_GET['id']; // Lấy giá trị id từ URL
if (!$id) {
    echo "<script>alert(''ID mua hàng không hợp lệ');</script>";
    header("Location: lich_su_mua_hang.php");
    // echo json_encode(['message' => 'ID không hợp lệ']);
    exit;
}
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect('localhost', 'root', '', 'qlbh');
if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
// Lấy dữ liệu từ bảng lịch sử mua hàng chỉ với những đơn hàng đã hoàn thành hoặc giao hàng
//$sql = "SELECT * FROM lich_su_mua_hang WHERE trang_thai IN ('Đã giao hàng', 'Hoàn tất')";
$sql = "
SELECT
    lsmh.id AS id,
    sp.tensp AS tensp,
    lsmh_sp.soluong AS soluong,
    sp.gia AS gia,
    sp.anhchinh AS hinhanh
FROM 
    lich_su_mua_hang lsmh
INNER JOIN 
    lich_su_mua_hang_sanpham lsmh_sp ON lsmh.id = lsmh_sp.maLSmuahang
INNER JOIN 
    sanpham sp ON lsmh_sp.sanpham_id = sp.masp
WHERE 
    lsmh.id = $id
";
$result = mysqli_query($conn, $sql);
$donhangSql = "SELECT * FROM lich_su_mua_hang WHERE id = $id";
$donhangResult = mysqli_query($conn, $donhangSql);
$donhang = mysqli_fetch_assoc($donhangResult);


// Hiển thị dữ liệu
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử mua hàng - đơn hàng</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f8f9fa;
    }

    h2 {
        text-align: center;
        color: #007bff;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
    }

    table,
    th,
    td {
        border: 1px solid #dee2e6;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    .btn {
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn:hover {
        opacity: 0.9;
        cursor: pointer;
        text-decoration: underline;
    }

    .thong_tin_dh {
        display: flex;
        flex-direction: column;
        gap: 2px;
        padding: 0px 16px;
    }

    .thong_tin_dh p {
        margin: 4px 0px 0px 0px;
    }
    </style>
</head>

<body>
    <h2>Lịch sử mua hàng - đơn hàng <?php echo htmlspecialchars($id); ?></h2>
    <a href="lich_su_mua_hang.php" class="btn btn-primary">Quay lại</a>
    <h4>Thông tin đơn hàng</h4>
    <div class="thong_tin_dh">
        <p><strong>Ngày đặt:</strong> <?php echo htmlspecialchars($donhang['ngay_dat']); ?></p>
        <p><strong>Ngày dự kiến nhận:</strong> <?php echo htmlspecialchars($donhang['ngay_du_kien_nhan']); ?> </p>
        <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($donhang['trang_thai']); ?> </p>
        <p><strong>Tổng tiền:</strong> <?php echo htmlspecialchars($donhang['tong_tien']); ?> </p>
    </div>


    <table>
        <tr>
            <th>Tên sản phẩm</th>
            <th>Hình ảnh</th>
            <th>Số lượng</th>
            <th>Giá</th>
            <th>Thành tiền</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo htmlspecialchars($row['tensp']); ?></td>
            <!-- <td><?php echo htmlspecialchars($row['hinhanh']); ?></td> -->
            <td>
                <img src=<?php echo htmlspecialchars($row['hinhanh']); ?> alt="Hình ảnh"
                    style="width:50px; height:50px;">
            </td>
            <!-- echo '<img src="' . $category['image_url'] . '" alt="Hình ảnh" style="width:50px; height:50px;">'; -->
            <td><?php echo htmlspecialchars($row['soluong']); ?></td>
            <td><?php echo htmlspecialchars($row['gia']); ?></td>
            <td><?php echo htmlspecialchars($row['gia'] * $row['soluong']); ?></td>
        </tr>
        <?php } ?>
    </table>
</body>

</html>
<?php mysqli_close($conn); ?>