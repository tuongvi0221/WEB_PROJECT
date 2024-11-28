<?php
require_once 'function.php';

// Kiểm tra nếu form đã được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tensp = $_POST['tensp'];
    $gia = $_POST['gia'];
    $danhcho = $_POST['danhcho'];
    $khuyenmai = $_POST['khuyenmai'];
    $madm = $_POST['madanhmuc'];  // Lấy mã danh mục được chọn
    $anhchinh = '';

    // Kiểm tra và xử lý hình ảnh tải lên
    if (isset($_FILES['anhchinh']) && $_FILES['anhchinh']['error'] == 0) {
        $target_dir = "images/";  // Đảm bảo ảnh lưu trong thư mục "admin/images/"
        
        // Kiểm tra nếu thư mục chưa tồn tại thì tạo mới
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);  // Tạo thư mục nếu chưa tồn tại
        }

        // Đặt tên cho file hình ảnh
        $target_file = $target_dir . basename($_FILES["anhchinh"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra loại file ảnh hợp lệ
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            // Kiểm tra kích thước file ảnh
            if ($_FILES['anhchinh']['size'] > 500000) { // Giới hạn kích thước ảnh
                echo "Ảnh quá lớn. Vui lòng chọn ảnh có kích thước nhỏ hơn 500KB.";
                exit;
            }

            // Tiến hành upload ảnh
            if (move_uploaded_file($_FILES["anhchinh"]["tmp_name"], $target_file)) {
                $anhchinh = $target_file;  // Lưu đường dẫn ảnh
            } else {
                echo "Có lỗi khi tải ảnh lên.";
                exit;
            }
        } else {
            echo "Chỉ hỗ trợ ảnh JPG, PNG, JPEG, GIF.";
            exit;
        }
    } else {
        echo "Vui lòng chọn ảnh.";
        exit;
    }

    // Kết nối cơ sở dữ liệu
    $conn = connect();

    // Làm sạch dữ liệu đầu vào để tránh SQL injection và lỗi XSS
    $tensp = validate_input_sql($conn, $tensp);
    $gia = validate_input_sql($conn, $gia);
    $danhcho = validate_input_sql($conn, $danhcho);
    $khuyenmai = validate_input_sql($conn, $khuyenmai);
    $madm = validate_input_sql($conn, $madm);

    // Thêm sản phẩm vào cơ sở dữ liệu
    $now = date('d-m-Y h:i:s');
    $luotmua = rand(200, 1000);
    $luotxem = rand(1001, 5000);

    // Chuẩn bị câu lệnh SQL để thêm sản phẩm vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO sanpham (tensp, gia, danhcho, khuyenmai, madm, anhchinh, luotmua, luotxem, ngaytao) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $tensp, $gia, $danhcho, $khuyenmai, $madm, $anhchinh, $luotmua, $luotxem, $now);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo "<script>alert('Thêm sản phẩm thành công!');</script>";
        header("Location: foradmin.php"); // Redirect về trang danh sách sản phẩm
        exit;
    } else {
        echo "<script>alert('Có lỗi khi thêm sản phẩm vào cơ sở dữ liệu.');</script>";
    }

    // Đóng kết nối
    $stmt->close();
    disconnect($conn);
}
?>

<?php
// Hàm làm sạch dữ liệu đầu vào để bảo vệ khỏi SQL Injection và XSS
function validate_input_sql($conn, $data) {
    // Loại bỏ khoảng trắng ở đầu và cuối chuỗi
    $data = trim($data);
    // Chuyển đổi ký tự đặc biệt thành HTML entities để bảo vệ khỏi XSS
    $data = htmlspecialchars($data);
    // Dùng mysqli_real_escape_string để bảo vệ khỏi SQL injection
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}
?>