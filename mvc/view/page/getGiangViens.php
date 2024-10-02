<?php
// getGiangViens.php

include 'connect.php'; // Bao gồm file kết nối cơ sở dữ liệu

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