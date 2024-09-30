<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'WebThiTracNghiem';

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $user, $pass, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập mã hóa để làm việc với UTF-8
$conn->set_charset('utf8');

// Lấy mã nhóm từ tham số GET và kiểm tra tính hợp lệ
$manhom = isset($_GET['manhom']) ? filter_var($_GET['manhom'], FILTER_VALIDATE_INT) : 0;

if ($manhom === false) {
    die("Mã nhóm không hợp lệ.");
}

// Lấy danh sách sinh viên từ bảng chitietnhom dựa vào mã nhóm
$studentsQuery = "
    SELECT nguoidung.id, nguoidung.hoten 
    FROM chitietnhom 
    JOIN nguoidung ON chitietnhom.manguoidung = nguoidung.id 
    WHERE chitietnhom.manhom = ? AND nguoidung.trangthai = 1"; // Chỉ lấy sinh viên đang hoạt động

$stmt = $conn->prepare($studentsQuery);
$stmt->bind_param("i", $manhom);
$stmt->execute();
$result = $stmt->get_result();

$students = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = [
            'id' => $row['id'],
            'hoten' => $row['hoten']
        ];
    }
}

$stmt->close();
$conn->close();

// Trả về danh sách sinh viên dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($students);