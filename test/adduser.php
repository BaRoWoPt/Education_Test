<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";  // Tên đăng nhập của database
$password = "";  // Mật khẩu của database (nếu có)
$dbname = "WebThiTracNghiem";  // Tên database của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Mã hóa mật khẩu
$plain_password = '290504'; // Mật khẩu gốc
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT); // Mã hóa mật khẩu

// Thêm người dùng
$sql = "INSERT INTO nguoidung (email, id, hoten, gioitinh, ngaysinh, matkhau, trangthai, manhomquyen) 
        VALUES ('123124@example.com', '123123123', 'Hồng Hoa', 0, '2000-01-01', ?, 1, 11)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed_password);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Thêm người dùng thành công.";
} else {
    echo "Có lỗi xảy ra khi thêm người dùng: " . $conn->error;
}

$stmt->close();
$conn->close();
