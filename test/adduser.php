<?php
// Thông tin kết nối
$servername = "localhost";
$username = "root"; // Thay thế bằng tên người dùng của bạn
$password = ""; // Thay thế bằng mật khẩu của bạn
$dbname = "WebThiTracNghiem"; // Tên cơ sở dữ liệu của bạn

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thông tin người dùng cần thêm
$email = "example@example.com"; // Thay thế bằng email bạn muốn thêm
$id = "user123"; // ID người dùng
$hoten = "Nguyễn Văn A"; // Họ tên người dùng
$gioitinh = 1; // Giới tính (1: Nam, 0: Nữ)
$ngaysinh = "1990-01-01"; // Ngày sinh
$matkhau = password_hash("password123", PASSWORD_DEFAULT); // Mật khẩu đã mã hóa

// Câu lệnh SQL để chèn người dùng mới
$sql = "INSERT INTO nguoidung (email, id, hoten, gioitinh, ngaysinh, matkhau, trangthai) 
        VALUES ('$email', '$id', '$hoten', $gioitinh, '$ngaysinh', '$matkhau', 1)";

// Thực thi câu lệnh SQL
if ($conn->query($sql) === TRUE) {
    echo "Người dùng mới đã được thêm thành công!";
} else {
    echo "Lỗi: " . $sql . "<br>" . $conn->error;
}

// Đóng kết nối
$conn->close();
