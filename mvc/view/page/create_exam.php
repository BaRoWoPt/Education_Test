<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

session_start();
$userId = $_SESSION['user_id'];

// Kiểm tra xem dữ liệu POST có được gửi hay không
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tende = $_POST['tende'];
    $thoigianbatdau = $_POST['thoigianbatdau'];
    $socau_de = $_POST['socau_de'];
    $socau_tb = $_POST['socau_tb'];
    $socau_kho = $_POST['socau_kho'];
    $mamonhoc = $_POST['mamonhoc'];
    $thoigianlambai = $_POST['thoigianlambai']; // Thời gian làm bài tính bằng phút

    // Kiểm tra biến thoigianlambai có giá trị hợp lệ
    if (empty($thoigianlambai) || !is_numeric($thoigianlambai) || $thoigianlambai < 0) {
        die("Thời gian làm bài không hợp lệ.");
    }

    // Chuyển đổi thời gian làm bài từ phút sang giây
    $thoigianlambai_in_seconds = $thoigianlambai * 60; // Chuyển đổi phút thành giây

    // Thực hiện các bước lưu dữ liệu vào cơ sở dữ liệu
    $sql = "INSERT INTO dethi (tende, thoigianbatdau, thoigianthi, nguoitao, thoigianketthuc, socaude, socautb, socaukho, monthi)
            VALUES ('$tende', '$thoigianbatdau', '$thoigianlambai_in_seconds', '$userId', 
                    DATE_ADD('$thoigianbatdau', INTERVAL $thoigianlambai MINUTE), 
                    '$socau_de', '$socau_tb', '$socau_kho', '$mamonhoc')";

    if ($conn->query($sql) === TRUE) {
        // Sau khi thành công, chuyển hướng với thông báo thành công
        header("Location: tao_dethi.php?success=1");
        exit();
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();