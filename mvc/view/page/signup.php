<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['hoten'];
    $id = $_POST['id']; // Mã sinh viên
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];
    $xacnhanmatkhau = $_POST['xacnhanmatkhau'];

    // Kiểm tra xác nhận mật khẩu
    if ($matkhau !== $xacnhanmatkhau) {
        echo "Mật khẩu xác nhận không khớp!";
    } else {
        // Mã hóa mật khẩu
        $matkhau_mahoa = password_hash($matkhau, PASSWORD_BCRYPT);

        // Kiểm tra xem email đã tồn tại chưa
        $checkEmail = "SELECT * FROM nguoidung WHERE email='$email'";
        $result = $conn->query($checkEmail);

        if ($result->num_rows > 0) {
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu với manhomquyen = 11 (Sinh viên)
            $sql = "INSERT INTO nguoidung (hoten, id, email, matkhau, trangthai, manhomquyen) 
                    VALUES ('$hoten', '$id', '$email', '$matkhau_mahoa', 1, 11)"; // Gán mã nhóm quyền là 11 (Sinh viên)

            if ($conn->query($sql) === TRUE) {
                // Chuyển hướng đến trang đăng nhập
                header("Location: login.php");
                exit();
            } else {
                echo "Lỗi: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUFLIT Test - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f0f4f8;
        /* Màu nền tổng thể */
        font-family: 'Inter', sans-serif;
    }

    .register-container {
        display: flex;
        height: 100vh;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .register-box {
        display: flex;
        flex-direction: row;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        background-color: #fff;
        /* Màu nền cho box */
    }

    .register-form {
        padding: 40px;
        background-color: #ffffff;
    }

    .register-form h1 {
        font-weight: bold;
        margin-bottom: 20px;
        color: #FFD700;
        /* Màu tiêu đề */
    }

    .register-form h1 span {
        color: #FFD700;
        /* Màu vàng cho chữ "Test" */
    }

    .register-form p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        color: #333;
    }

    .register-image {
        background-color: #a12c2f;
        /* Màu nền cho phần thông báo */
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: white;
        flex: 1;
        text-align: center;
    }

    .register-image h1 {
        font-size: 2rem;
        font-weight: bold;
    }

    .register-image p {
        font-size: 1rem;
    }

    .form-control {
        margin-bottom: 20px;
        height: 45px;
        /* border: 1px solid #007bff; */
        /* Viền input màu xanh */
        border-radius: 5px;
    }

    .btn-custom {
        width: 100%;
        height: 45px;
        margin-bottom: 20px;
        border-radius: 5px;
    }

    .btn-primary {
        background-color: #a12c2f;
        /* Màu nền nút đăng ký */
        /* border-color: #007bff; */
    }

    .google-register {
        background-color: #f8f9fa;
        /* Màu nền nút Google */
        color: #333;
        /* border: 1px solid #007bff; */
        /* Viền đồng bộ */
    }

    .google-register img {
        margin-right: 10px;
    }

    .text-small {
        font-size: 0.9rem;
        color: #FFD700;
        /* Màu liên kết */
    }

    .text-primary {
        color: #a12c2f !important;
    }

    @media (max-width: 768px) {
        .register-box {
            flex-direction: column;
            /* Chuyển đổi layout trên màn hình nhỏ */
            box-shadow: none;
            /* Bỏ bóng đổ trên màn hình nhỏ */
        }

        .register-image {
            padding: 20px;
            font-size: 1.5rem;
        }

        .register-form {
            padding: 20px;
        }
    }
    </style>
</head>

<body>
    <div class="register-container">
        <div class="register-box">
            <!-- Left Section (Form) -->
            <div class="register-form">
                <h1>HUFLIT <span class="text-primary">Test</span></h1>
                <p>Đăng ký tài khoản để tham gia kỳ thi</p>

                <!-- Form đăng ký gửi POST -->
                <form method="POST" action="signup.php">
                    <input type="text" class="form-control" name="hoten" placeholder="Họ và tên" required>
                    <input type="text" class="form-control" name="id" placeholder="Mã sinh viên/Giảng viên" required>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <input type="password" class="form-control" name="matkhau" placeholder="Mật khẩu" required>
                    <input type="password" class="form-control" name="xacnhanmatkhau" placeholder="Xác nhận mật khẩu"
                        required>
                    <button class="btn btn-primary btn-custom" type="submit">Đăng ký</button>
                </form>

                <button class="btn btn-light btn-custom google-register">
                    <img src="/mvc/view/img/logo_gg.png" width="30px" alt="">
                    Đăng ký với Google
                </button>
                <div class="d-flex justify-content-between">
                    <a href="/mvc/view/page/login.php" class="text-small">Đã có tài khoản? Đăng nhập</a>
                </div>
            </div>

            <!-- Right Section (Welcome Message) -->
            <div class="register-image">
                <div>
                    <h1>Welcome to the HUFLIT Test</h1>
                    <p>Join us and enhance your knowledge.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>