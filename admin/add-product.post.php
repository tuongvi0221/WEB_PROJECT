<?php
require_once 'function.php';

// Kiểm tra nếu form đã được submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form
    $tensp = $_POST['tensp'];
    $gia = $_POST['gia'];
    $danhcho = $_POST['danhcho'];
    $khuyenmai = $_POST['khuyenmai'];
    $madm = $_POST['madanhmuc'];
    $chatlieu = $_POST['chatlieu'];
    $mau = $_POST['mau'];
    $anhchinh = '';

    // Truy xuất tên danh mục từ mã danh mục
    $conn = connect();
    $madm = validate_input_sql($conn, $madm);
    $query = "SELECT tendm FROM danhmucsp WHERE madm = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $madm);
    $stmt->execute();
    $stmt->bind_result($tendm);
    $stmt->fetch();
    $stmt->close();

    $tendm = strtolower(remove_diacritics($tendm));  // Loại bỏ dấu và chuyển thành chữ thường
    $tendm = str_replace(' ', '-', $tendm);  // Thay khoảng trắng bằng dấu gạch nối

    // Kiểm tra và xử lý hình ảnh tải lên
    if (isset($_FILES['anhchinh']) && $_FILES['anhchinh']['error'] == 0) {
        // Đường dẫn thư mục gốc
        $base_dir = "images/";

        // Tạo thư mục con theo tên danh mục (chỉ thêm thư mục mới)
        $target_dir = $base_dir . $tendm . "/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Đặt tên file ảnh (bao gồm cả đường dẫn)
        $target_file = $target_dir . basename($_FILES["anhchinh"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Kiểm tra loại file ảnh hợp lệ
        $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            echo "Chỉ hỗ trợ ảnh định dạng JPG, PNG, JPEG, GIF.";
            exit;
        }

        // Kiểm tra kích thước file ảnh
        if ($_FILES['anhchinh']['size'] > 500000) { // Giới hạn kích thước 500KB
            echo "Ảnh quá lớn. Vui lòng chọn ảnh có kích thước nhỏ hơn 500KB.";
            exit;
        }

        // Tiến hành upload ảnh
        if (move_uploaded_file($_FILES["anhchinh"]["tmp_name"], $target_file)) {
            $anhchinh = 'admin/'.$target_file; // Lưu đường dẫn ảnh
        } else {
            echo "Có lỗi xảy ra khi tải ảnh lên.";
            exit;
        }
    } else {
        echo "Vui lòng chọn ảnh.";
        exit;
    }

    // Làm sạch dữ liệu đầu vào để tránh SQL injection
    $tensp = validate_input_sql($conn, $tensp);
    $gia = validate_input_sql($conn, $gia);
    $danhcho = validate_input_sql($conn, $danhcho);
    $khuyenmai = validate_input_sql($conn, $khuyenmai);
    $chatlieu = validate_input_sql($conn, $chatlieu);
    $mau = validate_input_sql($conn, $mau);

    // Thêm sản phẩm vào cơ sở dữ liệu
    $now = date('Y-m-d H:i:s');
    $luotmua = rand(200, 1000);
    $luotxem = rand(1001, 5000);

    $stmt = $conn->prepare("INSERT INTO sanpham (tensp, gia, danhcho, khuyenmai, chatlieu, mau, madm, anhchinh, luotmua, luotxem, ngaytao) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssss", $tensp, $gia, $danhcho, $khuyenmai, $chatlieu, $mau, $madm, $anhchinh, $luotmua, $luotxem, $now);

    if ($stmt->execute()) {
        echo "<script>alert('Thêm sản phẩm thành công!');</script>";
        header("Location: foradmin.php");
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

<?php
function remove_diacritics($string) {
    $replacements = array(
        'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a', 'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
        'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
        'đ' => 'd',
        'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e', 'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
        'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o', 'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
        'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
        'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u', 'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
        'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
        'Á' => 'a', 'À' => 'a', 'Ả' => 'a', 'Ã' => 'a', 'Ạ' => 'a', 'Ă' => 'a', 'Ắ' => 'a', 'Ằ' => 'a', 'Ẳ' => 'a', 'Ẵ' => 'a', 'Ặ' => 'a',
        'Â' => 'a', 'Ấ' => 'a', 'Ầ' => 'a', 'Ẩ' => 'a', 'Ẫ' => 'a', 'Ậ' => 'a',
        'Đ' => 'd',
        'É' => 'e', 'È' => 'e', 'Ẻ' => 'e', 'Ẽ' => 'e', 'Ẹ' => 'e', 'Ê' => 'e', 'Ế' => 'e', 'Ề' => 'e', 'Ể' => 'e', 'Ễ' => 'e', 'Ệ' => 'e',
        'Í' => 'i', 'Ì' => 'i', 'Ỉ' => 'i', 'Ĩ' => 'i', 'Ị' => 'i',
        'Ó' => 'o', 'Ò' => 'o', 'Ỏ' => 'o', 'Õ' => 'o', 'Ọ' => 'o', 'Ô' => 'o', 'Ố' => 'o', 'Ồ' => 'o', 'Ổ' => 'o', 'Ỗ' => 'o', 'Ộ' => 'o',
        'Ơ' => 'o', 'Ớ' => 'o', 'Ờ' => 'o', 'Ở' => 'o', 'Ỡ' => 'o', 'Ợ' => 'o',
        'Ú' => 'u', 'Ù' => 'u', 'Ủ' => 'u', 'Ũ' => 'u', 'Ụ' => 'u', 'Ư' => 'u', 'Ứ' => 'u', 'Ừ' => 'u', 'Ử' => 'u', 'Ữ' => 'u', 'Ự' => 'u',
        'Ý' => 'y', 'Ỳ' => 'y', 'Ỷ' => 'y', 'Ỹ' => 'y', 'Ỵ' => 'y',
    );
    return strtr($string, $replacements);
}
?>