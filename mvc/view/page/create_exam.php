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
    // Nhập thời gian thi (phút)
    $thoigianlambai = $_POST['thoigianlambai']; // Sử dụng biến đúng

    // Kiểm tra biến thoigianthi có giá trị hợp lệ
    if (empty($thoigianlambai) || !is_numeric($thoigianlambai)) {
        die("Thời gian làm bài không hợp lệ.");
    }

    // Thực hiện các bước lưu dữ liệu vào cơ sở dữ liệu
    $sql = "INSERT INTO dethi (tende,thoigianbatdau, thoigianthi,nguoitao,thoigianketthuc, socaude, socautb, socaukho,monthi)
        VALUES ('$tende', '$thoigianbatdau','$thoigianlambai','$userId', DATE_ADD('$thoigianbatdau', INTERVAL $thoigianlambai MINUTE), '$socau_de', '$socau_tb', '$socau_kho','$mamonhoc')";

    if ($conn->query($sql) === TRUE) {
        echo "Tạo đề kiểm tra thành công!";
        header("Location: tao_dethi.php");
        exit(); // Ngăn chặn việc thực hiện thêm mã sau khi chuyển hướng
    } else {
        echo "Lỗi: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
