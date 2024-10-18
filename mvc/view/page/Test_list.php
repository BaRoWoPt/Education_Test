<?php
session_start();

$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền
$userId = $_SESSION['user_id'];
// Kiểm tra quyền truy cập
if ($manhomquyen != 11) {
    echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
    echo "<script>window.location.href='login.php';</script>";
    exit; // Ngừng thực thi nếu không có quyền
}

// Thiết lập múi giờ cho Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Thay đổi nếu cần
$password = ""; // Thay đổi nếu cần
$dbname = "WebThiTracNghiem"; // Thay đổi tên database nếu cần

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Truy vấn SQL để lấy danh sách đề thi hợp lệ
$sql = "SELECT DISTINCT d.made, d.tende, m.mamonhoc, n.manhom, d.thoigiantao, d.thoigianbatdau, d.thoigianketthuc
        FROM dethi d
        JOIN monhoc m ON d.monthi = m.mamonhoc
        JOIN nhom n ON m.mamonhoc = n.mamonhoc
        WHERE d.trangthai = 1;";


$result = $conn->query($sql);

$printedMades = [];

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký nhóm học phần</title>
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #a12c2f;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            padding-top: 20px;
        }

        .sidebar h2 {
            text-align: center;
            font-weight: bold;
            color: white;
        }

        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #921e24;
        }

        .menu-section {
            margin-bottom: 20px;
            margin-top: 60px;
        }

        .menu-section h3 {
            font-size: 16px;
            text-transform: uppercase;
            margin-left: 20px;
            margin-bottom: 10px;
            color: #FFD700;
        }

        .container {
            width: 100%;
            margin-left: 250px;
            max-width: 1200px;
            padding: 20px;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }
        }

        h1 {
            color: #a12c2f;
            margin-bottom: 20px;
            text-align: center;
        }

        .table th {
            background-color: #a12c2f;
            color: white;
        }

        .table {
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #a12c2f;
            color: white;
        }

        .btn {
            border-style: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-danger {
            background-color: #dc3545;
            /* Màu đỏ */
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
            /* Đậm hơn khi hover */
            transform: translateY(-2px);
            /* Hiệu ứng nổi khi hover */
        }

        .btn-secondary {
            background-color: #A04747;
            /* Màu xám cho "Quá giờ" */
            color: white;
        }

        .btn-secondary.disabled {
            background-color: #C7253E;
            /* Màu xám nhạt khi disabled */
            pointer-events: none;
        }

        .btn-secondary:hover:not(.disabled) {
            background-color: #5a6268;
            /* Đậm hơn khi hover */
            transform: translateY(-2px);
            /* Hiệu ứng nổi khi hover */
        }

        .btn-primary {
            background-color: #15B392;
            /* Màu vàng */
            color: white;
        }

        .btn-primary:hover {
            background-color: #73EC8B;
            /* Đậm hơn khi hover */
            transform: translateY(-2px);
            /* Hiệu ứng nổi khi hover */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .header h1 {
            color: #a12c2f;
            font-weight: bold;
        }

        .header .logout {
            background-color: #a12c2f;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .header .logout:hover {
            background-color: #921e24;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span></h2>
        <div class="menu-section">
            <h3>Quản lý</h3>
            <a href="../page/student_dashboard.php">Tổng quan</a>
            <a href="../page/dk_nhom.php">Đăng ký nhóm học phần</a>
            <a href="../page/Test_list.php">Kiểm tra</a>
            <a href="../page/result_list.php">Kết quả học tập</a>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Danh Sách Đề Thi</h1>
            <a href="logout.php" class="logout">Đăng xuất</a>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã Đề</th>
                    <th>Tên Đề</th>
                    <th>Thời Gian Tạo</th>
                    <th>Thời Gian Bắt Đầu</th>
                    <th>Thời Gian Kết Thúc</th>
                    <th>Thời Gian Hiện Tại</th>
                    <th>Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $made = $row["made"];
                        $tende = $row["tende"];
                        $thoigiantao = $row["thoigiantao"];
                        $thoigianbatdau = $row["thoigianbatdau"];
                        $thoigianketthuc = $row["thoigianketthuc"];

                        // Kiểm tra và bỏ qua nếu mã đề đã hiển thị
                        if (in_array($made, $printedMades)) {
                            continue;
                        }
                        $printedMades[] = $made; // Lưu mã đề vào mảng

                        echo "<tr>";
                        echo "<td>$made</td>";
                        echo "<td>$tende</td>";
                        echo "<td>$thoigiantao</td>";
                        echo "<td>$thoigianbatdau</td>";
                        echo "<td>" . ($thoigianketthuc ? $thoigianketthuc : "Không xác định") . "</td>";

                        $currentTime = new DateTime();
                        $startTime = new DateTime($thoigianbatdau);
                        echo "<td>" . $currentTime->format('Y-m-d H:i:s') . " - " . $startTime->format('Y-m-d H:i:s') . "</td>";

                        $allowEnterUntil = clone $startTime;
                        $allowEnterUntil->modify('+15 minutes');

                        if ($currentTime >= $allowEnterUntil) {
                            echo "<td><button class='btn btn-secondary disabled'>Quá giờ</button></td>";
                        } elseif ($currentTime >= $startTime) {
                            echo "<td><form method='POST' action='start_exam.php'>
                                    <input type='hidden' name='made' value='{$made}'>
                                    <button type='submit' class='btn btn-danger'>Tham gia</button>
                                </form></td>";
                        } else {
                            echo "<td><button class='btn btn-primary'>Chưa bắt đầu</button></td>";
                        }

                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>Không có đề thi nào.</td></tr>";
                }
                ?>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
?>