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

// Lấy mã nhóm từ tham số GET
$manhom = $_GET['manhom'] ?? 0;

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

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['hoten']}</td>
                <td>
                    <button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteStudentModal'
                    data-manguoidung='{$row['id']}' data-manhom='{$manhom}'>Xóa</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='2' class='text-center'>Không có sinh viên nào.</td></tr>";
}

$stmt->close();
$conn->close();