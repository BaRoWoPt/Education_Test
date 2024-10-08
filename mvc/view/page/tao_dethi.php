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
$sql = "SELECT manhom, tennhom FROM nhom WHERE hienthi = 1";
$result = $conn->query($sql);

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
    body {
        background-color: #f8f9fa;
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

    .config-container {
        background-color: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
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
                                <form method="POST" action="your_action_page.php">
                                    <!-- Thay đổi action tương ứng -->
                                    <div class="mb-3">
                                        <label for="tende" class="form-label">Tên đề kiểm tra</label>
                                        <input type="text" class="form-control" id="tende" name="tende"
                                            placeholder="Nhập tên đề kiểm tra" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianbatdau" class="form-label">Thời gian bắt đầu</label>
                                            <input type="datetime-local" class="form-control" id="thoigianbatdau"
                                                name="thoigianbatdau" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianlambai" class="form-label">Thời gian làm bài</label>
                                            <input type="number" class="form-control" id="thoigianlambai"
                                                name="thoigianlambai" placeholder="00" min="0" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="giaochonhom" class="form-label">Giao cho</label>
                                        <select class="form-select" id="giaochonhom" name="giaochonhom" required>
                                            <option selected disabled>Chọn nhóm học phần giảng dạy...</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='{$row['manhom']}'>{$row['tennhom']}</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Không có nhóm nào</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="chuong" class="form-label">Chương</label>
                                        <select class="form-select" id="chuong" name="chuong[]" multiple required>
                                            <option selected disabled>Chọn nhiều chương...</option>
                                            <!-- Các chương sẽ được thêm vào đây -->
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" name="socau_de"
                                                placeholder="Số câu dễ" min='0' required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" name="socau_tb"
                                                placeholder="Số câu trung bình" min='0' required>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" name="socau_kho"
                                                placeholder="Số câu khó" min='0' required>
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
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="autobank" name="autobank">
                                    <label class="form-check-label" for="autobank">Tự động lấy từ ngân hàng đề</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="xemdiem" name="xemdiem">
                                    <label class="form-check-label" for="xemdiem">Xem điểm sau khi thi xong</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="xembailam" name="xembailam">
                                    <label class="form-check-label" for="xembailam">Xem bài làm khi thi xong</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="daocauhoi" name="daocauhoi">
                                    <label class="form-check-label" for="daocauhoi">Đảo câu hỏi</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="daodapan" name="daodapan">
                                    <label class="form-check-label" for="daodapan">Đảo đáp án</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
    document.getElementById('giaochonhom').addEventListener('change', function() {
        var manhom = this.value;

        // Gửi yêu cầu AJAX để lấy danh sách chương
        fetch('get_chapters.php?manhom=' + manhom)
            .then(response => response.json())
            .then(data => {
                var chuongSelect = document.getElementById('chuong');
                chuongSelect.innerHTML = ''; // Xóa các option hiện tại

                // Thêm các chương vào dropdown
                data.forEach(function(machuong) {
                    var option = document.createElement('option');
                    option.value = machuong;
                    option.textContent = 'Chương ' + machuong;
                    chuongSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error:', error));
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>