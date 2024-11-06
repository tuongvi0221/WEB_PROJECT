<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
</div>
<?php
require_once 'backend-index.php';

// Lấy mã sản phẩm từ URL
$masp = "";
if (isset($_GET['masp'])) {
    $masp = $_GET['masp'];
}

// Kết nối cơ sở dữ liệu
$conn = connect();
mysqli_set_charset($conn, 'utf8');

// Sử dụng mysqli_real_escape_string để bảo vệ chống SQL injection
$masp_safe = mysqli_real_escape_string($conn, $masp);

// Câu truy vấn lấy thông tin sản phẩm
$sql = "SELECT * FROM sanpham sp 
        INNER JOIN danhmucsp dm ON sp.madm = dm.madm 
        WHERE sp.masp = '$masp_safe'";

$result = mysqli_query($conn, $sql);

// Kiểm tra xem truy vấn có thành công không
if (!$result) {
    echo "Lỗi truy vấn: " . mysqli_error($conn);
    disconnect($conn);
    return; // Dừng thực thi nếu có lỗi
}

// Nếu truy vấn thành công, thực hiện xử lý dữ liệu
while ($row = mysqli_fetch_assoc($result)) {
    ?>
    <div class="container-fluid form" style="margin-top: -23px; padding: 20px">
        <div class="row">
            <div class="col-sm-12">
                <div class="main-prd">
                    <!-- Hình ảnh chính -->
                    <img src="<?php echo htmlspecialchars($row['anhchinh']); ?>" class="main-prd-img">
                    <!-- Hình ảnh phụ -->
                    <?php if (!empty($row['anhphu'])): ?>
                        <img src="<?php echo htmlspecialchars($row['anhphu']); ?>" class="main-prd-img" style="margin-top: 10px;">
                    <?php endif; ?>
                    <div class="basic-info">
                        <h2><?php echo htmlspecialchars($row['tensp']); ?></h2>
                        <span class="main-prd-price"><?php echo number_format($row['gia'], 0, ',', '.'); ?> VND</span>
                        <h4><b>Thông tin cơ bản</b></h4>
                        <ul>
                            <li>Xuất xứ: <?php echo htmlspecialchars($row['xuatsu']); ?></li>
                            <li>Màu sắc: <?php echo htmlspecialchars($row['mau']); ?></li>
                            <li>Năng lượng sử dụng: <?php echo htmlspecialchars($row['nangluong']); ?></li>
                            <li>Chống nước: <?php echo $row['chongnuoc'] ? "Có" : "Không"; ?></li>
                            <li>Bảo hành: <?php echo htmlspecialchars($row['baohanh']); ?> tháng</li>
                            <li><span class="km">Khuyến mãi: <?php echo htmlspecialchars($row['khuyenmai']); ?> %</span></li>
                            <br><a class="btn btn-primary" href="order.php?masp=<?php echo htmlspecialchars($masp_safe); ?>">Mua ngay</a>
                        </ul>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="introduce-prd">
                    <h3>Thông số kỹ thuật</h3>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Đặc điểm</th><th>Giá trị</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Bảo hành</td><td><?php echo htmlspecialchars($row['baohanh']); ?> tháng</td>
                            </tr>
                            <tr>
                                <td>Trọng lượng(g)</td><td><?php echo htmlspecialchars($row['trongluong']); ?></td>
                            </tr>
                            <tr>
                                <td>Chất liệu</td><td><?php echo htmlspecialchars($row['chatlieu']); ?></td>
                            </tr>
                            <tr>
                                <td>Loại hình bảo hành</td><td><?php echo htmlspecialchars($row['loaibh']); ?></td>
                            </tr>
                            <tr>
                                <td>Kích thước (d x r x c) (cm)</td><td><?php echo htmlspecialchars($row['kichthuoc']); ?></td>
                            </tr>
                            <tr>
                                <td>Màu</td><td><?php echo htmlspecialchars($row['mau']); ?></td>
                            </tr>
                            <tr>
                                <td>Dành cho</td><td><?php echo htmlspecialchars($row['danhcho']); ?></td>
                            </tr>
                            <tr>
                                <td>Phụ kiện đi kèm</td><td><?php echo htmlspecialchars($row['phukien']); ?></td>
                            </tr>
                            <tr>
                                <td>Khuyễn mãi/ Quà tặng</td><td><?php echo htmlspecialchars($row['khuyenmai']); ?> %</td>
                            </tr>
                            <tr>
                                <td>Tình trạng</td><td><?php echo htmlspecialchars($row['tinhtrang']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php
}
disconnect($conn);
?>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
