<?php
require_once 'function.php'; // Kết nối đến cơ sở dữ liệu

// Kiểm tra nếu có tham số 'masp' trong URL
if (isset($_GET['masp']) && !empty($_GET['masp'])) {
    $masp = $_GET['masp'];

    // Kết nối cơ sở dữ liệu
    $conn = connect();
    mysqli_set_charset($conn, 'utf8');

    // Bảo vệ tham số 'masp' để tránh SQL Injection
    $masp = mysqli_real_escape_string($conn, $masp);

    // Truy vấn để lấy thông tin sản phẩm theo mã sản phẩm
    $sql = "SELECT * FROM sanpham WHERE masp = '$masp'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Lấy thông tin sản phẩm
        $tensp = htmlspecialchars($row['tensp']);
        $gia = htmlspecialchars($row['gia']);
        $chatlieu = htmlspecialchars($row['chatlieu']);
        $mau = htmlspecialchars($row['mau']);
        $danhcho = htmlspecialchars($row['danhcho']);
        $khuyenmai = htmlspecialchars($row['khuyenmai']);
        $anhchinh = htmlspecialchars($row['anhchinh']);
    } else {
        echo "Sản phẩm không tồn tại.";
        exit;
    }

    // Đóng kết nối cơ sở dữ liệu
    disconnect($conn);
} else {
    echo "Mã sản phẩm không hợp lệ.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sản Phẩm</title>
    <style>
    /* Thiết kế lại phần form */
    .card {
        max-width: 600px;
        margin: 0 auto;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
        background-color: #fff;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #333;
    }

    .form-control {
        padding: 12px;
        border: 1px solid #ccc;
        border-radius: 6px;
        width: 100%;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .form-control:focus {
        border-color: #007bff;
        outline: none;
    }

    .mb-3 {
        margin-bottom: 15px;
    }

    .text-center {
        text-align: center;
    }

    .text-warning {
        color: #ffbf00;
    }

    .btn {
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 6px;
        cursor: pointer;
        border: none;
    }

    .btn-success {
        background-color: #28a745;
        color: white;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .d-flex {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    img {
        width: 100px;
        height: auto;
        margin-top: 10px;
    }

    /* Style cho các thẻ thông báo lỗi hoặc hướng dẫn */
    .error {
        color: red;
        font-size: 14px;
        margin-top: 10px;
    }

    .success {
        color: green;
        font-size: 14px;
        margin-top: 10px;
    }
    </style>
</head>

<body>

    <div id="sua_sp-area" class="card shadow-sm p-4 mb-4">
        <h4 class="text-center text-warning mb-4">Sửa Sản Phẩm</h4>
        <form action="xu_li_sua_sanpham.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="masp" value="<?php echo $masp; ?>">

            <div class="mb-3">
                <label for="tensp-edit" class="form-label">Tên sản phẩm</label>
                <input type="text" id="tensp-edit" name="tensp" class="form-control" value="<?php echo $tensp; ?>"
                    required>
            </div>
            <div class="mb-3">
                <label for="gia-edit" class="form-label">Giá</label>
                <input type="text" id="gia-edit" name="gia" class="form-control" value="<?php echo $gia; ?>" required>
            </div>
            <div class="mb-3">
                <label for="khuyenmai-edit" class="form-label">Khuyến mãi</label>
                <input type="text" id="khuyenmai-edit" name="khuyenmai" class="form-control"
                    value="<?php echo $khuyenmai; ?>" required>
            </div>
            <div class="mb-3">
                <label for="chatlieu-edit" class="form-label">Chất liệu</label>
                <input type="text" id="chatlieu-edit" name="chatlieu" class="form-control"
                    value="<?php echo $chatlieu; ?>" required>
            </div>
            <div class="mb-3">
                <label for="mau-edit" class="form-label">Màu</label>
                <input type="text" id="mau-edit" name="mau" class="form-control" value="<?php echo $mau; ?>" required>
            </div>
            <div class="mb-3">
                <label for="danhcho-edit" class="form-label">Dành cho</label>
                <input type="text" id="danhcho-edit" name="danhcho" class="form-control" value="<?php echo $danhcho; ?>"
                    required>
            </div>
            <div class="mb-3">
                <label for="anhchinh-edit" class="form-label">Ảnh sản phẩm</label>
                <input type="file" id="anhchinh-edit" name="anhchinh" class="form-control">
                <!-- Hiển thị ảnh cũ nếu có -->
                <?php if ($anhchinh): ?>
                <img src="../<?php echo $anhchinh; ?>" alt="Ảnh sản phẩm">
                <?php endif; ?>
                <input type="hidden" name="anhchinh" value="<?php echo $anhchinh; ?>"> <!-- Lưu lại đường dẫn ảnh cũ -->
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-success me-2" type="submit">Lưu</button>
                <button class="btn btn-secondary" onclick="window.location.href='index.php'">Hủy</button>
            </div>
        </form>
    </div>

</body>

</html>