<?php
session_start();
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'WebThiTracNghiem';
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $user, $pass, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền

$userId = $_SESSION['user_id']; // Lấy ID người dùng từ session

// Kiểm tra quyền truy cập
if ($manhomquyen != 10 && $manhomquyen != 11) {
    echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
    exit; // Ngừng thực thi nếu không có quyền
}


// Xử lý khi người dùng gửi form cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoten = $_POST['hoten'];
    $ngaysinh = $_POST['ngaysinh'];
    $sodienthoai = $_POST['sodienthoai'];

    // Đảm bảo giá trị số điện thoại là số
    if (!is_numeric($sodienthoai)) {
        echo "<script>alert('Số điện thoại không hợp lệ!');</script>";
        exit();
    }

    // Cập nhật thông tin người dùng trong cơ sở dữ liệu
    $stmt = $conn->prepare("UPDATE nguoidung SET hoten = ?, ngaysinh = ?, sodienthoai = ? WHERE id = ?");
    $stmt->bind_param("ssis", $hoten, $ngaysinh, $sodienthoai, $userId);

    if ($stmt->execute()) {
        // Hiển thị thông báo thành công và chuyển hướng về trang dashboard
        echo "<script>
                alert('Cập nhật thông tin thành công!');
                window.location.href = 'http://localhost:3000/mvc/view/page/dashboard.php';
              </script>";
    } else {
        echo "Có lỗi xảy ra, vui lòng thử lại.";
    }

    $stmt->close();
}

// Lấy thông tin người dùng để hiển thị trong form
$stmt = $conn->prepare("SELECT hoten, ngaysinh, sodienthoai FROM nguoidung WHERE id = ?");
$stmt->bind_param("s", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật thông tin</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
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
            transition: width 0.3s;
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
    </style>
</head>

<body>
    <div class="sidebar">
        <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span></h2>
        <div class="menu-section">
            <h3>Quản lý</h3>
            <a href="../page/dashboard.php">Tổng quan</a>
            <a href="../page/update_user.php">Quản lý thông tin</a>
            <a href="../page/classView.php">Nhóm học phần</a>
            <a href="../page/question_view.php">Câu hỏi</a>
            <a href="../page/learning.php">Môn học</a>
            <a href="../page/tao_dethi.php">Tạo đề kiểm tra</a>
            <a href="../page/exam_list.php">Bộ đề</a>
        </div>
    </div>
    <div class="container">
        <h1>Cập nhật thông tin cá nhân</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="hoten">Họ và tên:</label>
                <input type="text" id="hoten" name="hoten" value="<?php echo $user['hoten']; ?>" required>
            </div>

            <div class="form-group">
                <label for="ngaysinh">Ngày sinh:</label>
                <input type="date" id="ngaysinh" name="ngaysinh" value="<?php echo $user['ngaysinh']; ?>" required>
            </div>

            <div class="form-group">
                <label for="sodienthoai">Số điện thoại:</label>
                <input type="text" id="sodienthoai" name="sodienthoai" value="<?php echo $user['sodienthoai']; ?>">
            </div>

            <button type="submit">Cập nhật</button>
        </form>
    </div>
</body>

</html>