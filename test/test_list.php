<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa


$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Quyền mặc định là 0
$userId = $_SESSION['user_id']; // Lấy ID người dùng

// Thiết lập múi giờ cho Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách đề thi hợp lệ
$sql = "SELECT d.made, d.tende, d.thoigiantao, d.thoigianbatdau, d.thoigianketthuc 
        FROM dethi d 
        WHERE d.trangthai = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Danh Sách Đề Thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1 class="mt-5 mb-4 text-center">Danh Sách Đề Thi</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã Đề</th>
                    <th>Tên Đề</th>
                    <th>Thời Gian Tạo</th>
                    <th>Thời Gian Bắt Đầu</th>
                    <th>Thời Gian Kết Thúc</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $made = $row['made'];
                        $tende = $row['tende'];
                        $thoigiantao = $row['thoigiantao'];
                        $thoigianbatdau = $row['thoigianbatdau'];
                        $thoigianketthuc = $row['thoigianketthuc'] ?? 'Không xác định';

                        echo "<tr>";
                        echo "<td>{$made}</td>";
                        echo "<td>{$tende}</td>";
                        echo "<td>{$thoigiantao}</td>";
                        echo "<td>{$thoigianbatdau}</td>";
                        echo "<td>{$thoigianketthuc}</td>";
                        echo "<td>
                                <form method='POST' action='start_exam.php'>
                                    <input type='hidden' name='made' value='{$made}'>
                                    <button type='submit' class='btn btn-danger'>Tham gia</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>Không có đề thi nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php $conn->close(); ?>