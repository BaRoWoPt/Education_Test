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
$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền
$userId = $_SESSION['user_id'];
// Kiểm tra quyền truy cập
if ($manhomquyen != 11) {
    header("Location: login.php");
    exit; // Ngừng thực thi nếu không có quyền
}
// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy ID người dùng từ session
$userId = $_SESSION['user_id'];

// Xử lý đăng ký nhóm
if (isset($_GET['manhom'])) {
    $manhom = $_GET['manhom'];

    // Kiểm tra xem người dùng đã có trong nhóm hay chưa
    $sql_check = "SELECT * FROM chitietnhom WHERE manhom = ? AND manguoidung = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("is", $manhom, $userId);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        echo "<script>alert('Bạn đã đăng ký nhóm này rồi!');</script>";
    } else {
        // Thêm sinh viên vào bảng chitietnhom
        $sql_insert = "INSERT INTO chitietnhom (manhom, manguoidung, hienthi) VALUES (?, ?, 1)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("is", $manhom, $userId);

        if ($stmt_insert->execute()) {
            echo "<script>alert('Đăng ký thành công!');</script>";
        } else {
            echo "<script>alert('Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.');</script>";
        }
        $stmt_insert->close();
    }

    $stmt_check->close();
}

// Truy vấn danh sách nhóm
$sql = "SELECT manhom, tennhom, siso, ghichu, namhoc, hocky, giangvien 
        FROM nhom 
        WHERE trangthai = 1 AND hienthi = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

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

        .content {
            margin-left: 250px;
            padding: 20px;
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

        .main-content {
            margin-top: 20px;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #f8f9fa;
            color: #a12c2f;
            position: fixed;
            width: 100%;
            bottom: 0;
            left: 250px;
        }

        table {
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #a12c2f;
            color: white;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .btn-register {
            background-color: #FFD700;
            color: #a12c2f;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .btn-register:hover {
            background-color: #ffcc00;
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span></h2>
        <div class="menu-section">
            <h3>Quản lý</h3>
            <a href="../page/student_dashboard.php">Tổng quan</a>
            <a href="../page/update_in4_student.php">Quản lý thông tin</a>
            <a href="../page/dk_nhom.php">Đăng ký nhóm học phần</a>
            <a href="../page/Test_list.php">Kiểm tra</a>
            <a href="../page/result_list.php">Kết quả học tập</a>
        </div>
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h1>Đăng ký nhóm học phần</h1>
            <a href="logout.php" class="logout">Đăng xuất</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <h2>Danh sách các nhóm</h2>
            <?php if ($result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Mã nhóm</th>
                            <th>Tên nhóm</th>
                            <th>Sĩ số</th>
                            <th>Ghi chú</th>
                            <th>Năm học</th>
                            <th>Học kỳ</th>
                            <th>Giảng viên</th>
                            <th>Đăng ký</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['manhom']; ?></td>
                                <td><?php echo $row['tennhom']; ?></td>
                                <td><?php echo $row['siso']; ?></td>
                                <td><?php echo $row['ghichu']; ?></td>
                                <td><?php echo $row['namhoc']; ?></td>
                                <td><?php echo $row['hocky']; ?></td>
                                <td><?php echo $row['giangvien']; ?></td>
                                <td>
                                    <a class="btn-register" href="dk_nhom.php?manhom=<?php echo $row['manhom']; ?>">Đăng
                                        ký</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Không có nhóm nào để hiển thị.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        HUFLIT Test © 2024 - Crafted with ❤️ by GBAO
    </footer>
</body>

</html>