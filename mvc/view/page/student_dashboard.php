<?php
session_start();
include 'connect.php'; // Kết nối đến cơ sở dữ liệu
// Kiểm tra xem người dùng đã đăng nhập chưa


// Lấy ID người dùng từ session
$userId = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin đăng nhập từ biểu mẫu
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn cơ sở dữ liệu để lấy thông tin người dùng
    $stmt = $conn->prepare("SELECT id, manhomquyen FROM nguoidung WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password); // Giả sử mật khẩu được lưu trữ dưới dạng plaintext (không nên làm như vậy trong thực tế)
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Nếu tìm thấy người dùng, lấy ID và lưu vào session
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['manhomquyen'] = $user['manhomquyen'];

        // Chuyển hướng đến trang dashboard
        header("Location: student_dashboard.php");
        exit();
    } else {
        $error_message = "Tên đăng nhập hoặc mật khẩu không đúng.";
    }
    session_start();
    $manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền
    $userId = $_SESSION['user_id'];
    // Kiểm tra quyền truy cập
    if ($manhomquyen != 11) {
        echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
        exit; // Ngừng thực thi nếu không có quyền
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
        /* Đẩy footer ra ngoài sidebar */
    }
    </style>

</head>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span> </h2>

        <div class="menu-section">
            <h3>Quản lý</h3>
            <a href="../page/student_dashboard.php">Tổng quan</a>
            <a href="../page/dk_nhom.php">Đăng ký nhóm học phần</a>
            <a href="../page/Test_list.php">Kiểm tra</a>
            <a href="../page/result_list.php">Kết quả học tập</a>
        </div>

        <!-- <div class="menu-section">
            <h3>Quản trị</h3>
            <a href="#">Nhóm quyền</a>
        </div> -->
    </div>

    <!-- Content -->
    <div class="content">
        <!-- Header -->
        <div class="header">
            <h1>Chào mừng bạn đến với HUFLIT TEST</h1>
            <a href="logout.php" class="logout">Đăng xuất</a>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Hình ảnh -->
            <img src="/mvc/view/img/huflit.png" alt="Campus" style="width: 100%; object-fit: cover;">
        </div>
    </div>

    <!-- Footer -->
    <footer>
        HUFLIT Test © 2024 - Crafted with ❤️ by GBAO
    </footer>

</body>

</html>