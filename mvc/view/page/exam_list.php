<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";  // Tên server
$username = "root";         // Tên người dùng
$password = "";             // Mật khẩu (nếu có)
$dbname = "WebThiTracNghiem"; // Tên cơ sở dữ liệu
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start();
$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền

// Kiểm tra quyền truy cập
if ($manhomquyen != 10) {
    echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
    exit; // Ngừng thực thi nếu không có quyền
}

// Xóa đề thi nếu nhận được yêu cầu xóa
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM dethi WHERE made = ?";
    $stmt = $conn->prepare($delete_sql);
    $stmt->bind_param('i', $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Đề thi đã được xóa thành công!');</script>";
        echo "<script>window.location.href='exam_list.php';</script>";
    } else {
        echo "<script>alert('Xóa đề thi thất bại!');</script>";
    }
}

// Lấy danh sách đề thi
$sql = "SELECT made, tende, thoigiantao, thoigianbatdau, thoigianketthuc, thoigianthi, trangthai FROM dethi";
$result = $conn->query($sql);
$exams = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $exams[] = $row;
    }
}

// Đóng kết nối cơ sở dữ liệu
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <title>Danh sách đề thi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
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

        .text_row {
            font-size: 14px;
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
    </style>
</head>

<body>
    <div class="sidebar">
        <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span></h2>
        <div class="menu-section">
            <h3>Quản lý</h3>
            <a href="../page/dashboard.php">Tổng quan</a>
            <a href="../page/update_user.php">Quản lý thông tin</a>
            <a href="../page/classView.php">Nhóm học phần</a>
            <a href="../page/question_view.php">Câu hỏi</a>
            <a href="../page/learning.php">Môn học</a>
            <a href="../page/tao_dethi.php">Tạo đề kiểm tra</a>
            <a href="../page/exam_list.php">Bộ đề</a>
        </div>
    </div>

    <div class="content">
        <h2>Danh sách đề thi</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên đề thi</th>
                    <th>Thời gian tạo</th>
                    <th>Thời gian bắt đầu</th>
                    <th>Thời gian kết thúc</th>
                    <th>Thời gian thi</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($exams)): ?>
                    <?php foreach ($exams as $row): ?>
                        <tr>
                            <td><?= $row['made'] ?></td>
                            <td><?= $row['tende'] ?></td>
                            <td><?= $row['thoigiantao'] ?></td>
                            <td><?= $row['thoigianbatdau'] ?></td>
                            <td><?= $row['thoigianketthuc'] ?></td>
                            <td><?= $row['thoigianthi'] ?></td>
                            <td><?= $row['trangthai'] == 1 ? 'Kích hoạt' : 'Không kích hoạt' ?></td>
                            <td>
                                <!-- Nút cập nhật hiển thị modal -->
                                <button class='btn btn-warning btn-sm' data-bs-toggle="modal" data-bs-target="#updateExamModal"
                                    data-made="<?= $row['made'] ?>" data-tende="<?= $row['tende'] ?>"
                                    data-thoigianbatdau="<?= $row['thoigianbatdau'] ?>"
                                    data-thoigianthi="<?= $row['thoigianthi'] ?>" data-trangthai="<?= $row['trangthai'] ?>">
                                    Cập nhật
                                </button>
                                <!-- Nút xóa -->
                                <a href='exam_list.php?delete_id=<?= $row['made'] ?>'
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa đề thi này không?');"
                                    class='btn btn-danger btn-sm'>Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan='8'>Không có đề thi nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <!-- Modal cập nhật đề thi -->
    <div class="modal fade" id="updateExamModal" tabindex="-1" aria-labelledby="updateExamModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateExamModalLabel">Cập nhật đề thi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateExamForm" method="POST" action="update_exam.php">
                        <!-- Chỉ định trang xử lý cập nhật -->
                        <input type="hidden" id="update_made" name="made">
                        <div class="mb-3">
                            <label for="update_tende" class="form-label">Tên đề thi</label>
                            <input type="text" class="form-control" id="update_tende" name="tende" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_thoigianbatdau" class="form-label">Thời gian bắt đầu</label>
                            <input type="datetime-local" class="form-control" id="update_thoigianbatdau"
                                name="thoigianbatdau" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_thoigianketthuc" class="form-label">Thời gian kết thúc</label>
                            <input type="datetime-local" class="form-control" id="update_thoigianketthuc"
                                name="thoigianketthuc" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="update_thoigianthi" class="form-label">Thời gian thi</label>
                            <input type="number" id="update_thoigianthi" name="thoigianthi"
                                placeholder="Nhập thời gian thi (phút)" required>
                        </div>
                        <div class="mb-3">
                            <label for="update_trangthai" class="form-label">Trạng thái</label>
                            <select class="form-select" id="update_trangthai" name="trangthai" required>
                                <option value="1">Kích hoạt</option>
                                <option value="0">Không kích hoạt</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Lắng nghe sự kiện khi modal mở để tự động điền thông tin
        const updateExamModal = document.getElementById('updateExamModal');
        updateExamModal.addEventListener('show.bs.modal', event => {
            const button = event.relatedTarget; // Lấy nút đã nhấp
            const made = button.getAttribute('data-made');
            const tende = button.getAttribute('data-tende');
            const thoigianbatdau = button.getAttribute('data-thoigianbatdau');
            const thoigianthi = button.getAttribute('data-thoigianthi');
            const trangthai = button.getAttribute('data-trangthai');

            // Cập nhật thông tin trong modal
            const modalTitle = updateExamModal.querySelector('.modal-title');
            const modalBodyInputMade = updateExamModal.querySelector('#update_made');
            const modalBodyInputTende = updateExamModal.querySelector('#update_tende');
            const modalBodyInputThoiGianBatDau = updateExamModal.querySelector('#update_thoigianbatdau');
            const modalBodyInputThoiGianThi = updateExamModal.querySelector('#update_thoigianthi');
            const modalBodyInputTrangThai = updateExamModal.querySelector('#update_trangthai');

            modalTitle.textContent = 'Cập nhật đề thi: ' + tende;
            modalBodyInputMade.value = made;
            modalBodyInputTende.value = tende;
            modalBodyInputThoiGianBatDau.value = thoigianbatdau;
            modalBodyInputThoiGianThi.value = thoigianthi;
            modalBodyInputTrangThai.value = trangthai;

            // Tính toán thời gian kết thúc
            calculateEndTime();
        });

        function calculateEndTime() {
            const startTimeInput = document.getElementById('update_thoigianbatdau');
            const examDurationInput = document.getElementById('update_thoigianthi');

            if (startTimeInput.value && examDurationInput.value) {
                const startTime = new Date(startTimeInput.value);
                const examDuration = parseInt(examDurationInput.value, 10);

                if (!isNaN(examDuration) && examDuration >= 0) {
                    const endTime = new Date(startTime.getTime());
                    endTime.setMinutes(startTime.getMinutes() + examDuration);

                    const year = endTime.getFullYear();
                    const month = String(endTime.getMonth() + 1).padStart(2, '0');
                    const day = String(endTime.getDate()).padStart(2, '0');
                    const hours = String(endTime.getHours()).padStart(2, '0');
                    const minutes = String(endTime.getMinutes()).padStart(2, '0');

                    const formattedEndTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                    document.getElementById('update_thoigianketthuc').value = formattedEndTime;
                }
            }
        }

        // Lắng nghe sự kiện khi thay đổi thời gian bắt đầu và thời gian thi
        document.getElementById('update_thoigianbatdau').addEventListener('input', calculateEndTime);
        document.getElementById('update_thoigianthi').addEventListener('input', calculateEndTime);
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>