<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";  // Tên đăng nhập của database
$password = "";  // Mật khẩu của database (nếu có)
$dbname = "WebThiTracNghiem";  // Tên database của bạn
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
    $username = $_POST['username']; // Tên đăng nhập (mã sinh viên)
    $password = $_POST['password']; // Mật khẩu

    // Kiểm tra thông tin đăng nhập
    $sql = "SELECT * FROM nguoidung WHERE id = ?"; // Tìm kiếm người dùng dựa trên mã sinh viên
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if (password_verify($password, $row['matkhau'])) {
            // Đăng nhập thành công
            session_start(); // Khởi động session
            $_SESSION['user_id'] = $row['id']; // Lưu thông tin người dùng vào session
            header("Location: dashboard.php"); // Chuyển hướng đến trang chính
            exit();
        } else {
            echo "<script>alert('Mật khẩu không chính xác!');</script>";
        }
    } else {
        echo "<script>alert('Tên đăng nhập không tồn tại!');</script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUFLIT Test - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            font-family: 'Roboto', sans-serif;
        }

        .login-container {
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            display: flex;
            flex-direction: row;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            overflow: hidden;
            width: 100%;
            max-width: 800px;
        }

        .login-form {
            padding: 40px;
            background-color: #fff;
            flex: 1;
        }

        .login-form h1 {
            font-weight: bold;
            margin-bottom: 20px;
            color: #a12c2f;
        }

        .login-form h1 span {
            color: #FFD700;
            /* Change the color of "Test" to yellow */
        }

        .login-image {
            background-color: #a12c2f;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
            color: white;
        }

        .login-image h1 {
            font-size: 2rem;
            font-weight: bold;
        }

        .login-image p {
            font-size: 1rem;
        }

        .form-control {
            margin-bottom: 20px;
            height: 45px;
        }

        .btn-custom {
            width: 100%;
            height: 45px;
            margin-bottom: 20px;
            background-color: #a12c2f;
            color: white;
            transition: background-color 0.3s ease;
        }

        .btn-custom:hover {
            background-color: #921e24;
            /* Darker shade on hover */
        }

        .google-login {
            background-color: #f8f9fa;
            color: #a12c2f;
            transition: background-color 0.3s ease;
        }

        .google-login:hover {
            background-color: #e9ecef;
            /* Light grey on hover */
        }

        .google-login img {
            margin-right: 10px;
        }

        .text-small {
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .login-box {
                flex-direction: column;
            }

            .login-image {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-box">
            <!-- Left Section (Form) -->
            <div class="login-form">
                <h1>HUFLIT <span>Test</span></h1>
                <p>Đăng nhập</p>
                <form method="POST" action="login.php">
                    <input type="text" class="form-control" name="username" placeholder="Tên đăng nhập" required>
                    <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
                    <button class="btn btn-custom" type="submit">Đăng nhập</button>
                </form>

                <button class="btn btn-light btn-custom google-login">
                    <img src="/mvc/view/img/logo_gg.png" width="20px" alt="Google logo">
                    Đăng nhập với Google
                </button>
                <div class="d-flex justify-content-between">
                    <a href="#" class="text-small">Quên mật khẩu</a>
                    <a href="/mvc/view/page/signup.php" class="text-small">+ New Account</a>
                </div>
            </div>

            <!-- Right Section (Welcome Message) -->
            <div class="login-image">
                <div>
                    <h1>Welcome to the HUFLIT Test</h1>
                    <p>Copyright © 2023</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>