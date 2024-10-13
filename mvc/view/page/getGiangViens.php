<?php
// getGiangViens.php

$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'WebThiTracNghiem';
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $user, $pass, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
// Hàm lấy danh sách giảng viên
function getGiangViens($conn)
{
    $sql = "SELECT id, hoten FROM nguoidung WHERE manhomquyen = 10";
    $result = $conn->query($sql);

    $giangViens = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $giangViens[] = $row;
        }
    }
    return $giangViens;
}
