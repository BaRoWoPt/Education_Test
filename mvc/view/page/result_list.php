<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

// Kết nối cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách kết quả từ bảng ketqua
$sql = "SELECT * FROM ketqua WHERE manguoidung = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Kết Quả</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
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
            align-items: center;
            width: 100%;
            margin-left: 280px;
            max-width: 1000px;
            padding: 20px;
            box-sizing: border-box;
        }



        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f44336;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        .score {
            font-weight: bold;
        }
    </style>
</head>

<body>
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
    <div class="container">
        <h1>Danh Sách Kết Quả Của Bạn</h1>
        <table>
            <thead>
                <tr>
                    <th>Mã Kết Quả</th>
                    <th>Mã Đề</th>
                    <th>Điểm Thi</th>
                    <th>Số Câu Đúng</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['makq']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['made']) . "</td>";
                        echo "<td class='score'>" . htmlspecialchars($row['diemthi']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['thoigianvaothi']) . "</td>";
                        // echo "<td>" . htmlspecialchars($row['thoigianlambai']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['socaudung']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Chưa có kết quả nào!</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>