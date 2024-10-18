<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

// Kết nối với database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(['error' => 'Kết nối thất bại: ' . $conn->connect_error]));
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Kiểm tra và nhận tham số mamonhoc từ GET
$mamonhoc = isset($_GET['mamonhoc']) ? intval($_GET['mamonhoc']) : 0;

// Kiểm tra và nhận tham số machuong từ GET
$machuong = isset($_GET['machuong']) ? explode(',', $_GET['machuong']) : [];

// Lọc các giá trị không hợp lệ trong machuong
$machuong = array_filter(array_map('intval', $machuong));

// Kiểm tra nếu thiếu tham số hoặc giá trị không hợp lệ
if ($mamonhoc === 0 || empty($machuong)) {
    echo json_encode([
        'so_cau_de' => 0,
        'so_cau_tb' => 0,
        'so_cau_kho' => 0
    ]);
    exit;
}

// Tạo chuỗi dấu hỏi tương ứng với số lượng giá trị trong machuong
$in  = str_repeat('?,', count($machuong) - 1) . '?';

// Câu truy vấn SQL
$sql = "SELECT 
            SUM(CASE WHEN dokho = '1' THEN 1 ELSE 0 END) AS so_cau_de,
            SUM(CASE WHEN dokho = '2' THEN 1 ELSE 0 END) AS so_cau_tb,
            SUM(CASE WHEN dokho = '3' THEN 1 ELSE 0 END) AS so_cau_kho
        FROM cauhoi 
        WHERE mamonhoc = ? AND machuong IN ($in)";

// Chuẩn bị câu lệnh
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'Lỗi chuẩn bị câu lệnh: ' . $conn->error]));
}

// Gộp mamonhoc với các giá trị của machuong
$params = array_merge([$mamonhoc], $machuong);

// Ràng buộc các tham số vào truy vấn
$types = str_repeat('i', count($params)); // 'i' là kiểu số nguyên
$stmt->bind_param($types, ...$params);

// Thực thi câu lệnh
if (!$stmt->execute()) {
    die(json_encode(['error' => 'Lỗi thực thi truy vấn: ' . $stmt->error]));
}

// Lấy kết quả
$result = $stmt->get_result();
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
