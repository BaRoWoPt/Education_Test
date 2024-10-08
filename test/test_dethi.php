<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem"; // Đặt tên database của bạn

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start();
$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền
$userId = $_SESSION['user_id'];
// Kiểm tra quyền truy cập
if ($manhomquyen != 10) {
    echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
    exit; // Ngừng thực thi nếu không có quyền
}

// Lấy danh sách môn học
$mamonhoc_list = $conn->query("SELECT mamonhoc, tenmonhoc FROM monhoc");

// Khởi tạo mảng chứa chương
$chapters = [];
if ($mamonhoc_list->num_rows > 0) {
    while ($row = $mamonhoc_list->fetch_assoc()) {
        $chapters[$row['mamonhoc']] = $row['tenmonhoc'];
    }
}

// Lấy chương khi người dùng chọn môn học (nếu có)
$selected_mamonhoc = isset($_POST['mamonhoc']) ? $_POST['mamonhoc'] : null;
$chapter_options = '';
if ($selected_mamonhoc) {
    $chapter_result = $conn->query("SELECT DISTINCT machuong FROM cauhoi WHERE mamonhoc = $selected_mamonhoc");
    while ($row = $chapter_result->fetch_assoc()) {
        $chapter_options .= "<option value='" . $row['machuong'] . "'>Chương " . $row['machuong'] . "</option>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo đề kiểm tra</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">

    <style>
    /* Các style tùy chỉnh */
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="sidebar">
                <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span></h2>
                <div class="menu-section">
                    <h3>Quản lý</h3>
                    <a href="../page/dashboard.php">Tổng quan</a>
                    <a href="../page/classView.php">Nhóm học phần</a>
                    <a href="../page/question_view.php">Câu hỏi</a>
                    <a href="../page/learning.php">Môn học</a>
                    <a href="#">Đề kiểm tra</a>
                    <a href="#">Thông báo</a>
                </div>
            </div>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="type_h2">Tạo mới đề thi</h1>
                </div>

                <!-- Form và Cấu hình -->
                <div class="row">
                    <!-- Form Tạo đề thi -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Thông tin đề thi</h5>
                                <form method="post">
                                    <div class="mb-3">
                                        <label for="tende" class="form-label">Tên đề kiểm tra</label>
                                        <input type="text" class="form-control" id="tende"
                                            placeholder="Nhập tên đề kiểm tra" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianbatdau" class="form-label">Thời gian bắt đầu</label>
                                            <input type="datetime-local" class="form-control" id="thoigianbatdau"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianlambai" class="form-label">Thời gian làm bài</label>
                                            <input type="number" class="form-control" id="thoigianlambai"
                                                placeholder="00" min="0" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="mamonhoc" class="form-label">Môn học</label>
                                        <select class="form-select" id="mamonhoc" name="mamonhoc"
                                            onchange="this.form.submit()">
                                            <option selected>Chọn môn học...</option>
                                            <?php foreach ($chapters as $mamonhoc => $tenmonhoc): ?>
                                            <option value="<?= $mamonhoc ?>"><?= $tenmonhoc ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="chuong" class="form-label">Chương</label>
                                        <select class="form-select" id="chuong" name="chuong" multiple>
                                            <option selected>Chọn nhiều chương...</option>
                                            <?= $chapter_options ?>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" placeholder="Số câu dễ" required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" placeholder="Số câu trung bình"
                                                required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" placeholder="Số câu khó" required>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">+ TẠO ĐỀ</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình -->
                    <div class="col-lg-4">
                        <div class="config-container">
                            <h5 class="card-title">Cấu hình</h5>
                            <form>
                                <!-- Các tùy chọn cấu hình -->
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
// Đóng kết nối
$conn->close();
?>