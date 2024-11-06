<?php 

// Kết nối đến cơ sở dữ liệu
if (!function_exists('connect')) {
    function connect() {
    $conn = mysqli_connect('localhost', 'root', '', 'qlbh');
    if (!$conn) {
        die("Kết nối thất bại: " . mysqli_connect_error());
    }
    return $conn;
}

}

function disconnect($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}

// Lấy 3 sản phẩm mới nhất
function get_3_newest() {
    $conn = connect();
    mysqli_set_charset($conn, 'utf8');
    $sql = "SELECT * FROM sanpham ORDER BY ngay_nhap DESC LIMIT 4";
    
    $result = mysqli_query($conn, $sql);

    if (!$result) {
        echo "Lỗi truy vấn (get_3_newest): " . mysqli_error($conn);
        disconnect($conn);
        return;
    }

    $i = 1; 
    while ($row = mysqli_fetch_assoc($result)) {
        $activeClass = ($i == 1) ? 'active' : ''; // Đảm bảo chỉ có 1 sản phẩm được đánh dấu là active
        ?>
<div class='item <?php echo $activeClass; ?>'>
    <img src="<?php echo htmlspecialchars($row['anhchinh']); ?>" alt="<?php echo htmlspecialchars($row['tensp']); ?>">
    <div class='container'>
        <div class='carousel-caption'>
            <p><a class='btn btn-md btn-primary' href='order.php?masp=<?php echo $row['masp']; ?>' role='button'>Mua
                    ngay</a></p>
        </div>
    </div>
</div>
<?php
        $i++;
    }
    disconnect($conn);
}

// Lấy sản phẩm mua nhiều nhất
function get_buy_the_most() {
    // Kết nối cơ sở dữ liệu
    $conn = connect();

    // Kiểm tra kết nối
    if (!$conn) {
        die("Kết nối cơ sở dữ liệu thất bại.");
    }

    // Thiết lập mã hóa UTF-8
    mysqli_set_charset($conn, 'utf8');

    // Câu truy vấn lấy sản phẩm được mua nhiều nhất
    $sql = "SELECT * FROM sanpham sp INNER JOIN danhmucsp dm ON sp.madm = dm.madm ORDER BY sp.luotmua DESC LIMIT 8";

    // Thực hiện truy vấn
    $result = mysqli_query($conn, $sql);

    // Kiểm tra lỗi truy vấn
    if (!$result) {
        echo "Lỗi truy vấn (get_buy_the_most): " . mysqli_error($conn);
        disconnect($conn);
        return;
    }

    // Kiểm tra nếu không có kết quả trả về
    if (mysqli_num_rows($result) == 0) {
        echo "Không có sản phẩm nào.";
        disconnect($conn);
        return;
    }

    // Hiển thị danh sách sản phẩm
    while ($row = mysqli_fetch_assoc($result)) {
        // Kiểm tra các trường trong $row trước khi sử dụng
        $masp = htmlspecialchars($row['masp'] ?? '');
        $anhchinh = htmlspecialchars($row['anhchinh'] ?? '');
        $tensp = htmlspecialchars($row['tensp'] ?? '');
        $gia = htmlspecialchars($row['gia'] ?? 0);
        ?>
<div class='product-container' onclick="hien_sanpham('<?php echo $masp; ?>')">
    <a data-toggle='modal' href='sanpham.php?masp=<?php echo $masp; ?>' data-target='#modal-id'>
        <div style="text-align: center;" class='product-img'>
            <img src='<?php echo $anhchinh; ?>' alt="<?php echo $tensp; ?>">
        </div>
        <div class='product-info'>
            <h4><b><?php echo $tensp; ?></b></h4>
            <b class='price'>Giá: <?php echo number_format($gia, 0, ',', '.'); ?> VND</b>
            <div class='buy'>
                <a onclick="like_action('<?php echo $masp; ?>')" class='btn btn-default btn-md unlike-container <?php
                                    if (isset($_SESSION['rights']) && $_SESSION['rights'] == 'user' && isset($_SESSION['like']) && in_array($masp, $_SESSION['like'])) {
                                        echo 'liked';
                                    } ?>'>
                    <i class='glyphicon glyphicon-heart unlike'></i>
                </a>
                <a class='btn btn-primary btn-md cart-container <?php 
                                    if (isset($_SESSION['rights'])) {
                                        if ($_SESSION['rights'] == "default" && isset($_SESSION['client_cart']) && in_array($masp, $_SESSION['client_cart'])) {
                                            echo 'cart-ordered';
                                        } else if ($_SESSION['rights'] != "default" && isset($_SESSION['user_cart']) && in_array($masp, $_SESSION['user_cart'])) {
                                            echo 'cart-ordered';
                                        }
                                    } ?>' onclick="addtocart_action('<?php echo $masp; ?>')">
                    <i title='Thêm vào giỏ hàng' class='glyphicon glyphicon-shopping-cart cart-item'></i>
                </a>
                <a class="snip0050" href='order.php?masp=<?php echo $masp; ?>'>
                    <span>Mua ngay</span><i class="glyphicon glyphicon-ok"></i>
                </a>
            </div>
        </div>
    </a>
</div>
<?php }
    // Ngắt kết nối sau khi lấy dữ liệu
    disconnect($conn);
}


//Gần đây nhất
function get_the_most_recent() {
    // Kết nối cơ sở dữ liệu
    $conn = connect();
    if (!$conn) {
        die("Kết nối cơ sở dữ liệu thất bại.");
    }

    // Thiết lập mã hóa ký tự
    mysqli_set_charset($conn, 'utf8');

    // Câu truy vấn
    $sql = "SELECT * FROM sanpham sp, danhmucsp dm WHERE sp.madm = dm.madm ORDER BY sp.ngay_nhap DESC LIMIT 8";

    // Thực hiện truy vấn
    $result = mysqli_query($conn, $sql);

    // Kiểm tra lỗi truy vấn
    if (!$result) {
        echo "Lỗi truy vấn: " . mysqli_error($conn);
        disconnect($conn);
        return; // Dừng thực thi nếu có lỗi
    }

    // Nếu truy vấn thành công, thực hiện xử lý dữ liệu
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Kiểm tra các trường có tồn tại trong $row
            $masp = htmlspecialchars($row['masp'] ?? '');
            $anhchinh = htmlspecialchars($row['anhchinh'] ?? '');
            $tensp = htmlspecialchars($row['tensp'] ?? '');
            $gia = htmlspecialchars($row['gia'] ?? 0);
            ?>
<div class='product-container' onclick="hien_sanpham('<?php echo $masp; ?>')">
    <a data-toggle='modal' href='sanpham.php?masp=<?php echo $masp; ?>' data-target='#modal-id'>
        <div style="text-align: center;" class='product-img'>
            <img src='<?php echo $anhchinh; ?>' alt="<?php echo $tensp; ?>">
        </div>
        <div class='product-info'>
            <h4><b><?php echo $tensp; ?></b></h4>
            <b class='price'>Giá: <?php echo number_format($gia, 0, ',', '.'); ?> VND</b>
            <div class='buy'>
                <a onclick="like_action('<?php echo $masp; ?>')" class='btn btn-default btn-md unlike-container <?php
                                if (isset($_SESSION['rights']) && $_SESSION['rights'] == 'user' && isset($_SESSION['like']) && in_array($masp, $_SESSION['like'])) {
                                    echo 'liked';
                                }
                            ?>'>
                    <i class='glyphicon glyphicon-heart unlike'></i>
                </a>
                <a class='btn btn-primary btn-md cart-container <?php 
                                if (isset($_SESSION['rights'])) {
                                    if ($_SESSION['rights'] == "default" && isset($_SESSION['client_cart']) && in_array($masp, $_SESSION['client_cart'])) {
                                        echo 'cart-ordered';
                                    } elseif ($_SESSION['rights'] != "default" && isset($_SESSION['user_cart']) && in_array($masp, $_SESSION['user_cart'])) {
                                        echo 'cart-ordered';
                                    }
                                } ?>' onclick="addtocart_action('<?php echo $masp; ?>')">
                    <i title='Thêm vào giỏ hàng' class='glyphicon glyphicon-shopping-cart cart-item'></i>
                </a>
                <a class="snip0050" href='order.php?masp=<?php echo $masp; ?>'>
                    <span>Mua ngay</span><i class="glyphicon glyphicon-ok"></i>
                </a>
            </div>
        </div>
    </a>
</div>
<?php
        }
    } else {
        echo "Không có sản phẩm mới nhất.";
    }

    // Ngắt kết nối sau khi lấy dữ liệu
    disconnect($conn);
}


//Xu ly user


$q = "";
if(isset($_POST['query'])){
	$q = $_POST['query'];
}
$m = "";
if(isset($_POST['masp_to_display'])){
	$m = $_POST['masp_to_display'];
}

switch ($q) {
    case 'dang_nhap':
        signin();
        break;
    case 'dang_xuat':
        signout();
        break;
    case 'dang_ky':
        signup();
        break;
    case 'addtocart_action':
        addtocart_action();
        break;
    case 'hien_sanpham':
        hien_sanpham($m);
        break;
    case 'tinh_tien':
        tinh_tien();
        break;
    case 'like':
        like();
        break;
    case 'thongtin_user':
        thongtin_user();
        break;
    case 'php_edit_info_db':
        php_edit_info_db();
        break;
    case 'xoasanpham':
        if (isset($_GET['masp'])) {
            xoasanpham($_GET['masp']); // Truyền mã sản phẩm vào hàm
        }
        break;
}

function tinh_tien() {
    session_start();
    
    if (!isset($_SESSION['user_cart']) || empty($_SESSION['user_cart'])) {
        echo json_encode(['total' => 0, 'message' => 'Giỏ hàng trống.']);
        return;
    }

    $total = 0;

    foreach ($_SESSION['user_cart'] as $item) {
        // Giả sử mỗi item có 'price' và 'quantity'
        $total += $item['price'] * $item['quantity'];
    }

    // Nếu cần thêm phí hoặc thuế
    $tax_rate = 0.1; // 10% thuế
    $total_with_tax = $total * (1 + $tax_rate);

    echo json_encode([
        'total' => number_format($total_with_tax, 2, '.', ''),
        'message' => 'Tổng số tiền đã tính thành công.'
    ]);
}

function cart_list() {
    session_start();
    
    if (empty($_SESSION['user_cart'])) {
        echo "<p>Giỏ hàng của bạn đang trống.</p>";
        return;
    }

    echo '<table class="table table-striped">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Sản phẩm</th>';
    echo '<th>Giá</th>';
    echo '<th>Số lượng</th>';
    echo '<th>Tổng</th>';
    echo '<th>Hành động</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    $total = 0;
    
    foreach ($_SESSION['user_cart'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;

        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['name']) . '</td>';
        echo '<td>' . number_format($item['price'], 2) . ' VNĐ</td>';
        echo '<td>' . $item['quantity'] . '</td>';
        echo '<td>' . number_format($subtotal, 2) . ' VNĐ</td>';
        echo '<td><button class="btn btn-danger" onclick="remove_from_cart(' . $item['id'] . ')">Xóa</button></td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';

    echo '<h3>Tổng cộng: ' . number_format($total, 2) . ' VNĐ</h3>';
}

function signin(){
    session_start();
    $conn = connect();
    mysqli_set_charset($conn, 'utf8');
    $un = $pw = $isR = "";

    if(isset($_POST['un'])){
        $un = $_POST['un'];
    }
    if(isset($_POST['isR'])){
        $isR = $_POST['isR'];
    }
    if(isset($_POST['pw'])){
        $pw = $_POST['pw'];
    }
    
    if($un == "" || $pw == ""){
        echo "<div class='errorMes'>Không được để trống!</div>";
        require_once 'signIn.php';
        return 0;
    }

    // Prepare and execute the SQL query
    $sql = "SELECT * FROM thanhvien WHERE tentaikhoan = '".$un."' AND matkhau = '".$pw."'";
    $result = mysqli_query($conn, $sql);
    
    // Check if the query was successful
    if (!$result) {
        echo "<div class='errorMes'>Lỗi truy vấn: " . mysqli_error($conn) . "</div>";  // Display error message
        require_once 'signIn.php';
        return 0;
    }

    if(mysqli_num_rows($result) == 0){
        echo "<div class='errorMes'>Sai tên tài khoản hoặc mật khẩu!</div>";
        require_once 'signIn.php';
        return 0;
    } else {
        while ($row = mysqli_fetch_assoc($result)) {
            if($row['quyen'] == 1){
                $_SESSION['admin'] = "ok";
                echo "<script>window.location.replace('admin/foradmin.php');</script>";
                return 0;
            }
            if($isR == "true"){
                echo "vao roi ne";
                $cookie_name = "usidtf";
                $cookie_value = $row['id'];
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
            }
            $_SESSION['user'] = $row;
            echo "<script>location.reload()</script>";
        }
    }
}

function signout(){
	session_start();
	session_destroy();
	setcookie("usidtf", "", time() - (86400 * 30), "/");
	echo "<script>location.reload()</script>";
}
function signup() {
    session_start();
    $conn = connect();
    mysqli_set_charset($conn, 'utf8');

    $name = $un = $pw = $cpw = $addr = $tel = $email = "";
    if(isset($_POST['name'])){
        $name = $_POST['name'];
    }
    if(isset($_POST['un'])){
        $un = $_POST['un'];
    }
    if(isset($_POST['pw'])){
        $pw = $_POST['pw'];
    }
    if(isset($_POST['cpw'])){
        $cpw = $_POST['cpw'];
    }
    if(isset($_POST['addr'])){
        $addr = $_POST['addr'];
    }
    if(isset($_POST['tel'])){
        $tel = $_POST['tel'];
    }
    if(isset($_POST['email'])){
        $email = $_POST['email'];
    }

    if($name == "" || $un == "" || $pw == "" || $cpw == "" || $addr == "" || $tel == "" || $email == ""){
        echo "<div class='errorMes'>Không được để trống!</div>";
        require_once 'signUp.php';
        return 0;
    }

    if($pw != $cpw){
        echo "<div class='errorMes'>Mật khẩu nhập lại không trùng khớp!</div>";
        require_once 'signUp.php';
        return 0;
    }

    // Sanitize inputs
    $name = validate_input_sql($conn, $name);
    $un = validate_input_sql($conn, $un);
    $pw = validate_input_sql($conn, $pw);
    $addr = validate_input_sql($conn, $addr);
    $tel = validate_input_sql($conn, $tel);
    $email = validate_input_sql($conn, $email);

    // Check if username exists
    $sqla = "SELECT tentaikhoan FROM thanhvien WHERE tentaikhoan = '".$un."'";
    $resulta = mysqli_query($conn, $sqla);
    
    // Check if the query was successful
    if (!$resulta) {
        echo "<div class='errorMes'>Lỗi truy vấn: " . mysqli_error($conn) . "</div>"; // Display error message
        require_once 'signUp.php';
        return 0;
    }
    
    if(mysqli_num_rows($resulta) > 0){
        echo "<div class='errorMes'>Tên tài khoản đã tồn tại!</div>";
        require_once 'signUp.php';
        return 0;
    }

    // Insert new user
    date_default_timezone_set('Asia/Ho_Chi_Minh');
    $now = date("Y-m-d h:i:s");
    $sql = "INSERT INTO thanhvien VALUES ('','".$name."','".$un."','".$pw."','".$addr."','".$tel."','".$email."','".$now."',0)";
    $result = mysqli_query($conn, $sql);
    
    // Check if the insert was successful
    if (!$result) {
        echo "<div class='errorMes'>".mysqli_error($conn)."</div>";  
        require_once 'signUp.php';
        return 0;
    } else {
        // Retrieve the newly created user
        $sql = "SELECT * FROM thanhvien WHERE tentaikhoan = '".$un."'";
        $result = mysqli_query($conn, $sql);
        
        // Check if the query was successful
        if (!$result) {
            echo "<div class='errorMes'>Lỗi truy vấn: " . mysqli_error($conn) . "</div>"; // Display error message
            require_once 'signUp.php';
            return 0;
        }
        
        while ($row = mysqli_fetch_assoc($result)) {
            $_SESSION['user'] = $row;
            echo "<script>location.reload()</script>";
        }
    }
}

function addtocart_action() {
    session_start();
    $masp = "";

    // Kiểm tra nếu POST có chứa mã sản phẩm
    if (isset($_POST['masp'])) {
        $masp = $_POST['masp'];
    }

    $cart_name = "";

    // Xác định giỏ hàng tùy thuộc vào quyền hạn của người dùng
    if ($_SESSION['rights'] == "default") {
        $cart_name = "client_cart";
    } else {
        $cart_name = "user_cart";
    }

    // Kiểm tra xem giỏ hàng có tồn tại không, nếu chưa có thì khởi tạo mảng trống
    if (!isset($_SESSION[$cart_name])) {
        $_SESSION[$cart_name] = array();  // Tạo một mảng trống nếu giỏ hàng chưa tồn tại
    }

    // Tìm kiếm sản phẩm trong giỏ hàng
    $pos = array_search($masp, $_SESSION[$cart_name]);

    // Nếu sản phẩm đã có trong giỏ hàng, xóa sản phẩm khỏi giỏ hàng
    if ($pos !== false) {
        unset($_SESSION[$cart_name][$pos]);
        echo count($_SESSION[$cart_name]) - 1;
    } else {
        // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm vào giỏ
        $_SESSION[$cart_name][] = $masp;
        echo count($_SESSION[$cart_name]) - 1;
    }

    // Nếu người dùng có quyền "user", cập nhật giỏ hàng vào cơ sở dữ liệu
    if ($_SESSION['rights'] == "user") {
        $conn = connect();
        $sql = "";

        // Nếu sản phẩm đã có trong giỏ, xóa sản phẩm khỏi giỏ hàng trong cơ sở dữ liệu
        if ($pos !== false) {
            $sql = "DELETE FROM giohang WHERE user_id = '".$_SESSION['user']['id']."' AND masp = '".$masp."'";
        } else {
            // Nếu sản phẩm chưa có trong giỏ, thêm sản phẩm vào cơ sở dữ liệu
            $sql = "INSERT INTO giohang VALUES ('".$_SESSION['user']['id']."','".$masp."','1')";
        }

  
        $result = mysqli_query($conn, $sql);

        // Kiểm tra nếu truy vấn thất bại
        if (!$result) {
            echo "Lỗi thêm vào giỏ hàng";
        }
    }
}

function hien_sanpham($m){
	$_GET['masp'] = $m;
	require_once 'sanpham.php';
}

function like(){
	session_start();
	$masp = "";
	if(isset($_POST['masp_to_like'])){
		$masp = $_POST['masp_to_like'];
	}
	$pos = "";
	$pos = array_search($masp, $_SESSION['like']);
	if($pos != ""){
		unset($_SESSION['like'][$pos]);
		echo count($_SESSION['like'])-1;
	} else {
		$_SESSION['like'][] = $masp;
		echo count($_SESSION['like'])-1;
	}
	$conn = connect();

	$sql = "";
	if($pos != ""){
		$sql = "DELETE FROM sanphamyeuthich WHERE user_id = '".$_SESSION['user']['id']."' AND masp = '".$masp."'"; 
	} else {
		$sql = "INSERT INTO sanphamyeuthich VALUES ('".$_SESSION['user']['id']."','".$masp."')";
	}
	$result = mysqli_query($conn, $sql);
	if(!$result){echo "Loi them sp yeu thich";}
}

function thongtin_user(){
	session_start();
	?>
<script type="text/javascript">
function edit_info() {
    $($('#info-user').children()[2]).replaceWith("<h4>Tên: <input type='text' id='name2c'></h4>");
    $($('#info-user').children()[4]).replaceWith("<h4>Mật khẩu: <input type='password' id='pw2c'></h4>");
    $($('#info-user').children()[5]).replaceWith("<h4>Địa chỉ: <input type='text' id='dc2c'></h4>");
    $($('#info-user').children()[6]).replaceWith("<h4>Số điện thoại: <input type='text' id='sdt2c'></h4>");
    $($('#info-user').children()[7]).replaceWith("<h4>Email: <input type='text' id='email2c'></h4>");
    $('#edit-btn').replaceWith("<div class='btn btn-success' onclick='ajax_edit_info()' id='edit-btn'>Lưu lại</div>");
    $('#edit-btn').after(
        "<div class='btn btn-primary' style='margin-left:10px'  onclick='call_to_thongtin()' id='edit-btn'>Hủy bỏ</div>"
    );
}

function ajax_edit_info() {
    var ten = $('#name2c').val();
    var mk = $('#pw2c').val();
    var dc = $('#dc2c').val();
    var sdt = $('#sdt2c').val();
    var email = $('#email2c').val();
    $.ajax({
        url: "backend-index.php",
        type: "post",
        dataType: "text",
        data: {
            query: 'php_edit_info_db',
            ten,
            mk,
            dc,
            sdt,
            email
        },
        success: function(result) {
            $('#edit-info-error').html(result);
        }
    });
}
</script>
<div class="contaner">
    <div class="row" style="margin: 0!important">
        <div class="col-md-3"></div>
        <div class="col-md-6" style="margin-bottom: 20px" id="info-user">
            <div id="edit-info-error"></div>
            <h4>ID: <span class="label label-default"><?php echo $_SESSION['user']['id'] ?></span></h4>
            <h4>Tên: <span class="label label-primary"><?php echo $_SESSION['user']['ten'] ?></span></h4>
            <h4>Tên tài khoản: <span class="label label-primary"><?php echo $_SESSION['user']['tentaikhoan'] ?></span>
            </h4>
            <h4>Mật khẩu: <span class="label label-primary">**********</span></h4>
            <h4>Địa chỉ: <span class="label label-primary"><?php echo $_SESSION['user']['diachi'] ?></span></h4>
            <h4>Số điện thoại: <span class="label label-primary"><?php echo $_SESSION['user']['sodt'] ?></span></h4>
            <h4>Email: <span class="label label-primary"><?php echo $_SESSION['user']['email'] ?></span></h4>
            <h4>Ngày tạo: <span class="label label-primary"><?php echo $_SESSION['user']['date'] ?></span></h4>
            <div class="btn btn-success" onclick="edit_info()" id='edit-btn'>Chỉnh sửa thông tin</div>
        </div>
    </div>
</div>

<?php
}

function validate_input_sql($conn, $str) {
    return mysqli_real_escape_string($conn, $str);
}

function php_edit_info_db() {
    session_start();
    $ten = $mk = $dc = $sdt = $email = "";
    
    if (isset($_POST['ten'])) {
        $ten = $_POST['ten'];
    }
    if (isset($_POST['dc'])) {
        $dc = $_POST['dc'];
    }
    if (isset($_POST['sdt'])) {
        $sdt = $_POST['sdt'];
    }
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
    }
    if (isset($_POST['mk'])) {
        $mk = $_POST['mk'];
    }
    
    $n = [];
    $data = [];
    $set = '';
    
    $conn = connect(); // Kết nối cơ sở dữ liệu

    if ($ten != "") {
        $n[] = 'ten'; 
        $data[] = validate_input_sql($conn, $ten);
    }
    if ($mk != "") {
        $n[] = 'matkhau'; 
        $data[] = validate_input_sql($conn, $mk); // Sửa lại biến
    }
    if ($dc != "") {
        $n[] = 'diachi'; 
        $data[] = validate_input_sql($conn, $dc); // Sửa lại biến
    }
    if ($sdt != "") {
        $n[] = 'sodt'; 
        $data[] = validate_input_sql($conn, $sdt);
    }
    if ($email != "") {
        $n[] = 'email'; 
        $data[] = validate_input_sql($conn, $email);
    }
    
    // Tạo chuỗi gán cho câu lệnh SQL
    for ($i = 0; $i < count($n); $i++) {
        $set .= $n[$i] . "='" . $data[$i] . "',";
        $_SESSION['user'][$n[$i]] = $data[$i]; // Cập nhật session
    }
    
    $set = trim($set, ','); // Bỏ dấu phẩy ở cuối
    
    // Thực hiện câu lệnh UPDATE
    $sql = "UPDATE thanhvien SET $set WHERE id = '" . $_SESSION['user']['id'] . "'";
    
    if (!mysqli_query($conn, $sql)) {
        echo "<span class='label label-danger'>Đã xảy lỗi trong quá trình gửi dữ liệu, vui lòng thử lại!</span>";
    } else {
        echo "<script type='text/javascript'>
                alert('Thay đổi thông tin THÀNH CÔNG!');
                location.reload();
              </script>";
    }
    
    // Đóng kết nối
    mysqli_close($conn);
}

?>

<?php
function xoasanpham($masp) {
    session_start();
    if ($masp) {
        if (isset($_SESSION['user_cart'])) {
            // Tìm kiếm sản phẩm trong giỏ hàng
            $pos = array_search($masp, $_SESSION['user_cart']);

            // Nếu sản phẩm tồn tại trong giỏ hàng, xóa sản phẩm đó
            if ($pos !== false) {
                unset($_SESSION['user_cart'][$pos]);
                // Đặt lại chỉ số mảng để tránh lỗ hổng chỉ mục
                $_SESSION['user_cart'] = array_values($_SESSION['user_cart']);
                echo "Sản phẩm đã được xóa";
            } else {
                echo "Sản phẩm không tồn tại trong giỏ hàng!";
            }
        } elseif (isset($_SESSION['client_cart'])) {
            $pos = array_search($masp, $_SESSION['client_cart']);
            if ($pos !== false) {
                unset($_SESSION['client_cart'][$pos]);
                $_SESSION['client_cart'] = array_values($_SESSION['client_cart']);
                echo "Sản phẩm đã được xóa";
            } else {
                echo "Sản phẩm không tồn tại trong giỏ hàng!";
            }
        } else {
            echo "Giỏ hàng trống!";
        }

        // Kiểm tra quyền và xóa trong cơ sở dữ liệu nếu cần
        if (isset($_SESSION['rights']) && $_SESSION['rights'] == "user") {
            $conn = connect();
            $sql = "DELETE FROM giohang WHERE user_id = '".$_SESSION['user']['id']."' AND masp = '".$masp."'";
            mysqli_query($conn, $sql);
        }
    } else {
        echo "Không tìm thấy mã sản phẩm!";
    }
}
?>