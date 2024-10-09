<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nhận mã nhóm từ yêu cầu AJAX
$manhom = $_GET['manhom'] ?? '';

if ($manhom) {
    // Truy vấn lấy danh sách các chương từ bảng cauhoi dựa trên manhom
    $sql = "SELECT DISTINCT machuong FROM cauhoi WHERE mamonhoc = (SELECT mamonhoc FROM nhom WHERE manhom = ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $manhom);
    $stmt->execute();
    $result = $stmt->get_result();

    $chapters = [];
    while ($row = $result->fetch_assoc()) {
        $chapters[] = $row['machuong'];
    }

    // Trả về danh sách chương ở dạng JSON
    echo json_encode($chapters);
}