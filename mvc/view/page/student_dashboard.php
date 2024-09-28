<?php
session_start(); // Khởi động phiên

// Kiểm tra quyền truy cập
if (!isset($_SESSION['manhomquyen']) || $_SESSION['manhomquyen'] != 11) {
    header("Location: login.php"); // Chuyển hướng nếu không phải sinh viên
    exit();
}

// Xử lý đăng xuất
if (isset($_POST['logout'])) {
    session_destroy(); // Hủy phiên
    header("Location: login.php"); // Chuyển hướng về trang đăng nhập
    exit();
}

// Nội dung trang sinh viên
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/mvc//view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <title>Trang Sinh Viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1>Chào mừng, <?php echo $_SESSION['user_id']; ?>!</h1> <!-- Hiển thị ID người dùng -->

        <!-- Nút đăng xuất -->
        <form method="POST" action="">
            <button type="submit" name="logout" class="btn btn-danger">Đăng Xuất</button>
        </form>

        <!-- Nội dung khác của trang sinh viên -->
        <h3>Thông tin cá nhân</h3>
        <p>Tại đây bạn có thể quản lý thông tin của mình.</p>
        <!-- Thêm nội dung tùy chọn khác tại đây -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>