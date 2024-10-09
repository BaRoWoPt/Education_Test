<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Kết nối thất bại: ' . $conn->connect_error]));
}

// Nhận tham số machuong từ GET và chuyển thành mảng
$machuong = isset($_GET['machuong']) ? explode(',', $_GET['machuong']) : [];

// Kiểm tra xem có chương nào được chọn không và loại bỏ các giá trị không hợp lệ
$machuong = array_filter(array_map('intval', $machuong));

if (empty($machuong)) {
    echo json_encode([
        'so_cau_de' => 0,
        'so_cau_tb' => 0,
        'so_cau_kho' => 0
    ]);
    exit;
}

// Chuẩn bị câu truy vấn SQL với điều kiện IN để lọc theo nhiều chương
$in  = str_repeat('?,', count($machuong) - 1) . '?';
$sql = "SELECT 
            SUM(CASE WHEN dokho = '1' THEN 1 ELSE 0 END) AS so_cau_de,
            SUM(CASE WHEN dokho = '2' THEN 1 ELSE 0 END) AS so_cau_tb,
            SUM(CASE WHEN dokho = '3' THEN 1 ELSE 0 END) AS so_cau_kho
        FROM cauhoi 
        WHERE machuong IN ($in)";

// Chuẩn bị câu lệnh
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'Lỗi chuẩn bị câu lệnh: ' . $conn->error]));
}

// Ràng buộc các tham số với truy vấn
$stmt->bind_param(str_repeat('i', count($machuong)), ...$machuong);

// Thực thi truy vấn
if (!$stmt->execute()) {
    die(json_encode(['error' => 'Lỗi thực thi truy vấn: ' . $stmt->error]));
}

$result = $stmt->get_result();

// Lấy kết quả và tính tổng
if ($row = $result->fetch_assoc()) {
    echo json_encode([
        'so_cau_de' => (int)$row['so_cau_de'],
        'so_cau_tb' => (int)$row['so_cau_tb'],
        'so_cau_kho' => (int)$row['so_cau_kho']
    ]);
} else {
    echo json_encode([
        'so_cau_de' => 0,
        'so_cau_tb' => 0,
        'so_cau_kho' => 0
    ]);
}

// Đóng kết nối
$stmt->close();
$conn->close();