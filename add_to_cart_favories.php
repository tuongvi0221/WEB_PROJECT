<?php
session_start();
require_once 'connection2.session.sql'; // Replace with your actual database connection file

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Vui lòng đăng nhập để thêm vào sản phẩm yêu thích.']);
    exit();
}

// Check if the product ID (masp) is provided
if (!isset($_POST['masp']) || empty($_POST['masp'])) {
    echo json_encode(['status' => 'error', 'message' => 'Mã sản phẩm bị thiếu.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$masp = intval($_POST['masp']);

// Check if the product is already in the favorites
$query = "SELECT * FROM sanphamyeuthich WHERE user_id = ? AND masp = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $user_id, $masp);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'error', 'message' => 'Sản phẩm này đã có trong danh sách yêu thích của bạn.']);
} else {
    // Insert into favorites if not already present
    $insert_query = "INSERT INTO sanphamyeuthich (user_id, masp) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("ii", $user_id, $masp);
    $insert_stmt->execute();

    if ($insert_stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Sản phẩm đã được thêm vào danh sách yêu thích của bạn.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Thêm sản phẩm vào danh sách yêu thích thất bại.']);
    }
}

$stmt->close();
$conn->close();
?>