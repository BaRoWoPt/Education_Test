<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem"; // Đặt tên database của bạn

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Mã chương
$machuong = 5555;

$sql = "SELECT 
            SUM(CASE WHEN dokho = 1 THEN 1 ELSE 0 END) AS easyCount,
            SUM(CASE WHEN dokho = 2 THEN 1 ELSE 0 END) AS mediumCount,
            SUM(CASE WHEN dokho = 3 THEN 1 ELSE 0 END) AS hardCount
        FROM cauhoi
        WHERE machuong = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $machuong); // Sử dụng 'i' cho int
$stmt->execute();
$result = $stmt->get_result();

// Kiểm tra nếu có kết quả
if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo "Số câu hỏi theo độ khó:<br>";
    echo "Dễ: " . $data['easyCount'] . "<br>";
    echo "Trung bình: " . $data['mediumCount'] . "<br>";
    echo "Khó: " . $data['hardCount'] . "<br>";
} else {
    echo "Không có câu hỏi nào cho chương này.";
}

$conn->close();