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

// Khởi tạo biến thông báo
$thongbao = '';

// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $hoten = $_POST['hoten'];
    $id = $_POST['id']; // Mã sinh viên
    $email = $_POST['email'];
    $matkhau = $_POST['matkhau'];
    $xacnhanmatkhau = $_POST['xacnhanmatkhau'];

    // Kiểm tra xác nhận mật khẩu
    if ($matkhau !== $xacnhanmatkhau) {
        $thongbao = "Mật khẩu xác nhận không khớp!";
    } else {
        // Mã hóa mật khẩu
        $matkhau_mahoa = password_hash($matkhau, PASSWORD_BCRYPT);

        // Kiểm tra xem email đã tồn tại chưa
        $checkEmail = "SELECT * FROM nguoidung WHERE email='$email'";
        $resultEmail = $conn->query($checkEmail);

        // Kiểm tra xem mã sinh viên đã tồn tại chưa
        $checkId = "SELECT * FROM nguoidung WHERE id='$id'";
        $resultId = $conn->query($checkId);

        if ($resultEmail->num_rows > 0) {
            $thongbao = "Email đã tồn tại! Vui lòng sử dụng email khác.";
        } elseif ($resultId->num_rows > 0) {
            $thongbao = "Mã sinh viên đã tồn tại! Vui lòng sử dụng mã khác.";
        } else {
            // Thêm người dùng mới vào cơ sở dữ liệu với manhomquyen = 11 (Sinh viên)
            $sql = "INSERT INTO nguoidung (hoten, id, email, matkhau, trangthai, manhomquyen) 
                    VALUES ('$hoten', '$id', '$email', '$matkhau_mahoa', 1, 11)"; // Gán mã nhóm quyền là 11 (Sinh viên)

            if ($conn->query($sql) === TRUE) {
                // Chuyển hướng đến trang đăng nhập
                header("Location: login.php");
                exit();
            } else {
                $thongbao = "Lỗi: " . $sql . "<br>" . $conn->error;
            }
        }
    }
}

// Đóng kết nối
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/mvc//view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <title>HUFLIT Test - Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f0f4f8;
        font-family: 'Inter', sans-serif;
    }

    .register-container {
        display: flex;
        height: 100vh;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .text-small {
        color: #FFD700;
    }

    .text-primary {
        color: #a12c2f !important;
    }

    .register-box {
        display: flex;
        flex-direction: row;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        background-color: #fff;
    }

    .register-form {
        padding: 40px;
        background-color: #ffffff;
    }

    .register-form h1 {
        font-weight: bold;
        margin-bottom: 20px;
        color: #FFD700;
    }

    .register-form p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        color: #333;
    }

    .register-image {
        background-color: #a12c2f;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: white;
        flex: 1;
        text-align: center;
    }

    .form-control {
        margin-bottom: 20px;
        height: 45px;
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
        border-color: #a12c2f;
    }

    .btn-primary:hover {
        background-color: #e57373;
        border-color: #e57373;
    }

    @media (max-width: 768px) {
        .register-box {
            flex-direction: column;
            box-shadow: none;
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
            <div class="register-form">
                <h1>HUFLIT <span class="text-primary">Test</span></h1>
                <p>Đăng ký tài khoản để tham gia kỳ thi</p>

                <!-- Thông báo lỗi -->
                <?php if (!empty($thongbao)): ?>
                <div class="alert alert-danger" role="alert" id="error-message">
                    <?php echo $thongbao; ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="signup.php" id="register-form">
                    <input type="text" class="form-control" name="hoten" placeholder="Họ và tên" required>
                    <input type="text" class="form-control" name="id" placeholder="Mã sinh viên/Giảng viên" required>
                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                    <input type="password" class="form-control" name="matkhau" placeholder="Mật khẩu" required>
                    <input type="password" class="form-control" name="xacnhanmatkhau" placeholder="Xác nhận mật khẩu"
                        required>
                    <button class="btn btn-primary btn-custom" type="submit">Đăng ký</button>
                </form>
                <div class="d-flex justify-content-between">
                    <a href="/mvc/view/page/login.php" class="text-small">Đã có tài khoản? Đăng nhập</a>
                </div>
            </div>

            <div class="register-image">
                <div>
                    <h1>Welcome to the HUFLIT Test</h1>
                    <p>Join us and enhance your knowledge.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Lắng nghe sự kiện input trên form để ẩn thông báo lỗi
    document.getElementById('register-form').addEventListener('input', function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none'; // Ẩn thông báo lỗi
        }
    });
    </script>
</body>

</html>