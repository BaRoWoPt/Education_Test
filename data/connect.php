<?php
$servername = "localhost"; // Địa chỉ máy chủ
$username = "root"; // Tên đăng nhập
$password = ""; // Mật khẩu
$dbname = "ten_cua_database"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}