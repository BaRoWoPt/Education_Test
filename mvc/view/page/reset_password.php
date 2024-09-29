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
                <form method="POST" action="reset_password.php">
                    <input type="email" class="form-control" name="email" placeholder="Email" required>

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
    </script>
</body>

</html>