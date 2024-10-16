<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $made = $_POST['made'];
    $tende = $_POST['tende'];
    $thoigianbatdau = $_POST['thoigianbatdau'];
    $thoigianthi = $_POST['thoigianthi'];
    $trangthai = $_POST['trangthai'];

    $sql = "UPDATE dethi SET tende='$tende', thoigianbatdau='$thoigianbatdau', thoigianthi='$thoigianthi', trangthai='$trangthai' WHERE made='$made'";

    if ($conn->query($sql) === TRUE) {
        echo "Cập nhật thành công";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}

$conn->close();
header("Location: exam_list.php"); // Chuyển hướng về trang danh sách đề thi
exit();