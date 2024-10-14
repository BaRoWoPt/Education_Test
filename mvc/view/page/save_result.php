<?php
// Bắt đầu phiên làm việc
session_start();

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli('localhost', 'root', '', 'WebThiTracNghiem');

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Thiết lập báo lỗi
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Nếu có yêu cầu POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ yêu cầu AJAX
    $made = isset($_POST['made']) ? $_POST['made'] : null;
    $scorePoints = isset($_POST['scorePoints']) ? $_POST['scorePoints'] : null;
    $score = isset($_POST['score']) ? $_POST['score'] : null;

    // Lấy userId từ session
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Kiểm tra nếu các biến cần thiết đã được gửi
    if ($made === null || $userId === null || $scorePoints === null || $score === null) {
        echo json_encode(['status' => 'error', 'message' => 'Tất cả các trường đều phải được cung cấp.']);
        exit;
    }

    // Tạo mã kết quả
    $makq = $userId . $made; // Sử dụng manguoidung (userId) và made để tạo makq

    // Lưu kết quả vào bảng ketqua
    $sqlInsertResult = "
        INSERT INTO ketqua (makq, made, manguoidung, diemthi, socaudung) 
        VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = $conn->prepare($sqlInsertResult);

    // Kiểm tra nếu câu lệnh đã được chuẩn bị thành công
    if ($stmtInsert === false) {
        echo json_encode(['status' => 'error', 'message' => 'Chuẩn bị câu lệnh thất bại: ' . $conn->error]);
        exit;
    }

    // Ràng buộc các tham số
    $stmtInsert->bind_param("ssssi", $makq, $made, $userId, $scorePoints, $score);

    // Thực hiện câu lệnh
    if ($stmtInsert->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Kết quả đã được lưu thành công!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Lưu kết quả thất bại: ' . $stmtInsert->error]);
    }

    // Đóng câu lệnh
    $stmtInsert->close();
}

// Đóng kết nối
$conn->close();