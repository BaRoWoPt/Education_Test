<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Thay đổi nếu cần
$username = "root";        // Tên người dùng
$password = "";            // Mật khẩu
$dbname = "WebThiTracNghiem"; // Thay đổi tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy manhom từ tham số truy vấn
if (isset($_GET['manhom'])) {
    $manhom = $_GET['manhom'];

    // Kiểm tra manhom có phải là số nguyên không
    if (!is_numeric($manhom)) {
        echo json_encode(['error' => 'Mã nhóm không hợp lệ']);
        exit;
    }

    // Truy vấn để lấy mamonhoc
    $sql = "SELECT nhom.mamonhoc 
            FROM nhom 
            WHERE nhom.manhom = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $manhom);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Trả về mamonhoc dưới dạng JSON
        echo json_encode(['mamonhoc' => $row['mamonhoc']]);
    } else {
        // Nếu không tìm thấy, trả về một thông báo lỗi
        echo json_encode(['error' => 'Không tìm thấy mã môn học']);
    }
} else {
    echo json_encode(['error' => 'Thiếu tham số manhom']);
}

// Đóng kết nối
$conn->close();