<?php
session_start();
require_once 'layout/second_header.php';  // Gọi header của trang
require_once 'backend-index.php';  // Kết nối cơ sở dữ liệu
// Kiểm tra quyền truy cập của người dùng
if ($_SESSION['rights'] != 'user') {
    echo "<script>alert('Bạn không có quyền truy cập trang này!'); window.location.href='index.php';</script>";
    exit();
}
$user_id = $_SESSION['user']['id'];  // Giả sử bạn lưu ID người dùng trong session
// Kết nối cơ sở dữ liệu
$conn = connect(); // Gọi hàm kết nối với cơ sở dữ liệu
mysqli_set_charset($conn, 'utf8');
// Truy vấn lấy lịch sử đơn hàng của người dùng
$sql = "SELECT * FROM donhang WHERE user_id = '$user_id' ORDER BY order_date DESC";  // Giả sử bảng "donhang" lưu thông tin đơn hàng của người dùng
$result = mysqli_query($conn, $sql);
if (!$result) {
    echo "Error executing query: " . mysqli_error($conn);
} else {
    ?>
<div class="container">
    <h2>Lịch sử đơn hàng của bạn</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Mã đơn hàng</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th>Chi tiết</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['order_id']; ?></td>
                <td><?php echo $row['order_date']; ?></td>
                <td><?php echo number_format($row['total_price'], 0, ',', '.'); ?> VND</td>
                <td><?php echo $row['status']; ?></td>
                <td><a href="order_details.php?order_id=<?php echo $row['order_id']; ?>">Xem chi tiết</a></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
}
require_once 'layout/second_footer.php';  // Gọi footer của trang
?>