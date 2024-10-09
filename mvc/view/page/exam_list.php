<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start();
$manhomquyen = $_SESSION['manhomquyen'] ?? 0;

if ($manhomquyen != 10) {
    echo "Bạn không có quyền truy cập.";
    exit;
}

// Lấy danh sách đề thi
$sql = "SELECT * FROM de_thi ORDER BY thoigianbatdau DESC"; // Sắp xếp theo thời gian bắt đầu
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Đề Thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container">
        <h1 class="mt-5">Danh Sách Đề Thi</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>Tên Đề</th>
                    <th>Thời Gian Bắt Đầu</th>
                    <th>Thời Gian Làm Bài</th>
                    <th>Nhóm</th>
                    <th>Số Câu Dễ</th>
                    <th>Số Câu Trung Bình</th>
                    <th>Số Câu Khó</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                        <td>{$row['tende']}</td>
                        <td>{$row['thoigianbatdau']}</td>
                        <td>{$row['thoigianlambai']}</td>
                        <td>{$row['manhom']}</td>
                        <td>{$row['socau_de']}</td>
                        <td>{$row['socau_tb']}</td>
                        <td>{$row['socau_kho']}</td>
                    </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Không có đề thi nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>