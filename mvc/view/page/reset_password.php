<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/mvc//view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <style>
    body {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        font-family: 'Roboto', sans-serif;
    }

    .reset-container {
        display: flex;
        height: 100vh;
        justify-content: center;
        align-items: center;
    }

    .reset-box {
        display: flex;
        flex-direction: row;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
        max-width: 800px;
    }

    .reset-form {
        padding: 40px;
        background-color: #fff;
        flex: 1;
    }

    .reset-form h1 {
        font-weight: bold;
        margin-bottom: 20px;
        color: #a12c2f;
    }

    .reset-form h1 span {
        color: #FFD700;
    }

    .reset-image {
        background-color: #a12c2f;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 40px;
        color: white;
    }

    .reset-image h1 {
        font-size: 2rem;
        font-weight: bold;
    }

    .reset-image p {
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
    }

    .text-small {
        font-size: 0.9rem;
    }

    .password-eye {
        position: relative;
    }

    .eye-icon {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .reset-box {
            flex-direction: column;
        }

        .reset-image {
            width: 100%;
        }
    }
    </style>
</head>

<body>

    <div class="reset-container">
        <div class="reset-box">
            <!-- Left Section (Form) -->
            <div class="reset-form">
                <h1>Reset <span>Password</span></h1>
                <p>Vui lòng nhập email và mật khẩu mới của bạn</p>
                <div id="error-message" class="text-danger">
                    <?php
                    // Xử lý logic đặt lại mật khẩu
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Kết nối đến cơ sở dữ liệu
                        $servername = "localhost";
                        $username = "root";
                        $password = "";
                        $dbname = "WEBTHITRACNGHIEM";

                        $conn = new mysqli($servername, $username, $password, $dbname);

                        // Kiểm tra kết nối
                        if ($conn->connect_error) {
                            die("Kết nối thất bại: " . $conn->connect_error);
                        }

                        // Nhận dữ liệu từ form
                        $email = $_POST['email'];
                        $new_password = $_POST['new_password'];
                        $confirm_password = $_POST['confirm_password'];

                        // Kiểm tra xem mật khẩu mới và xác nhận mật khẩu có khớp không
                        if ($new_password !== $confirm_password) {
                            echo "Mật khẩu mới và xác nhận mật khẩu không khớp!";
                        } else {
                            // Hash mật khẩu mới
                            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                            // Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
                            $sql = "SELECT * FROM nguoidung WHERE email = '$email'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                // Cập nhật mật khẩu mới
                                $update_sql = "UPDATE nguoidung SET matkhau = '$hashed_password' WHERE email = '$email'";
                                if ($conn->query($update_sql) === TRUE) {
                                    // Hiển thị alert thành công và chuyển hướng
                                    echo "<script>
                                            alert('Mật khẩu đã được cập nhật thành công! Bạn sẽ được chuyển đến trang đăng nhập.');
                                            setTimeout(function() {
                                                window.location.href = 'login.php';
                                            }); // Chuyển hướng sau 2 giây
                                          </script>";
                                    exit();
                                } else {
                                    echo "Lỗi khi cập nhật mật khẩu: " . $conn->error;
                                }
                            } else {
                                echo "Không tìm thấy người dùng với email này!";
                            }
                        }

                        $conn->close();
                    }
                    ?>
                </div>
                <form method="POST" action="">
                    <input type="email" class="form-control" name="email" placeholder="Email" required id="emailInput">

                    <!-- Mật khẩu mới -->
                    <div class="password-eye">
                        <input type="password" class="form-control" name="new_password" id="new_password"
                            placeholder="Mật khẩu mới" required>
                        <span class="eye-icon" id="toggleNewPassword"
                            onclick="togglePassword('new_password', 'toggleNewPassword')">😎</span>
                    </div>

                    <!-- Xác nhận mật khẩu -->
                    <div class="password-eye">
                        <input type="password" class="form-control" name="confirm_password" id="confirm_password"
                            placeholder="Xác nhận mật khẩu" required>
                        <span class="eye-icon" id="toggleConfirmPassword"
                            onclick="togglePassword('confirm_password', 'toggleConfirmPassword')">😎</span>
                    </div>

                    <button class="btn btn-custom" type="submit">Đặt lại mật khẩu</button>
                </form>
            </div>

            <!-- Right Section (Welcome Message) -->
            <div class="reset-image">
                <div>
                    <h1>Welcome back!</h1>
                    <p>Chúng tôi rất vui được hỗ trợ bạn!</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Hàm để hiển thị hoặc ẩn mật khẩu
    function togglePassword(fieldId, toggleIconId) {
        var passwordField = document.getElementById(fieldId);
        var toggleIcon = document.getElementById(toggleIconId);
        if (passwordField.type === "password") {
            passwordField.type = "text";
            toggleIcon.innerHTML = "🫣"; // Mắt mở
        } else {
            passwordField.type = "password";
            toggleIcon.innerHTML = "😎"; // Mắt đóng
        }
    }

    // Hàm để ẩn thông báo lỗi khi người dùng nhập dữ liệu mới
    function hideErrorMessage() {
        document.getElementById("error-message").innerHTML = "";
    }

    // Lắng nghe sự kiện thay đổi trong form
    document.getElementById("emailInput").addEventListener("input", hideErrorMessage);
    document.getElementById("new_password").addEventListener("input", hideErrorMessage);
    document.getElementById("confirm_password").addEventListener("input", hideErrorMessage);
    </script>
</body>

</html>