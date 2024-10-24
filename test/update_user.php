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

$userId = $_SESSION['user_id'];

// Xử lý khi người dùng gửi form cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $hoten = $_POST['hoten'];
    $gioitinh = $_POST['gioitinh'];
    $ngaysinh = $_POST['ngaysinh'];
    $sodienthoai = $_POST['sodienthoai'];
    $avatar = $_POST['avatar'];

    // Cập nhật thông tin người dùng trong cơ sở dữ liệu
    $stmt = $conn->prepare("UPDATE nguoidung SET hoten = ?, gioitinh = ?, ngaysinh = ?, sodienthoai = ?, avatar = ? WHERE id = ?");
    $stmt->bind_param("sisssi", $hoten, $gioitinh, $ngaysinh, $sodienthoai, $avatar, $userId);

    if ($stmt->execute()) {
        echo "Cập nhật thông tin thành công!";
    } else {
        echo "Có lỗi xảy ra, vui lòng thử lại.";
    }

    $stmt->close();
}

// Lấy thông tin người dùng để hiển thị trong form
$stmt = $conn->prepare("SELECT email, hoten, gioitinh, ngaysinh, sodienthoai, avatar FROM nguoidung WHERE id = ?");
$stmt->bind_param("i", $userId);
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
        input[type="date"],
        select {
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

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Cập nhật thông tin cá nhân</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="hoten">Họ và tên:</label>
                <input type="text" id="hoten" name="hoten" value="<?php echo $user['hoten']; ?>" required>
            </div>

            <div class="form-group">
                <label for="gioitinh">Giới tính:</label>
                <select id="gioitinh" name="gioitinh">
                    <option value="1" <?php if ($user['gioitinh'] == 1) echo 'selected'; ?>>Nam</option>
                    <option value="0" <?php if ($user['gioitinh'] == 0) echo 'selected'; ?>>Nữ</option>
                </select>
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