<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";  // Tên server
$username = "root";         // Tên người dùng
$password = "";             // Mật khẩu (nếu có)
$dbname = "WebThiTracNghiem"; // Tên cơ sở dữ liệu

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
$sql = "SELECT made, tende, thoigiantao, thoigianbatdau, thoigianketthuc, trangthai FROM dethi";
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
            <a href="../page/classView.php">Nhóm học phần</a>
            <a href="../page/question_view.php">Câu hỏi</a>
            <a href="../page/learning.php">Môn học</a>
            <a href="#">Đề kiểm tra</a>
            <a href="#">Thông báo</a>
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
                            <td><?= $row['trangthai'] == 1 ? 'Kích hoạt' : 'Không kích hoạt' ?></td>
                            <td>
                                <!-- Nút cập nhật hiển thị modal -->
                                <button class='btn btn-warning btn-sm'
                                    onclick="openUpdateModal(<?= $row['made'] ?>, '<?= $row['tende'] ?>', '<?= $row['thoigianbatdau'] ?>', '<?= $row['thoigianketthuc'] ?>', <?= $row['trangthai'] ?>)">Cập
                                    nhật</button>
                                <!-- Nút xóa -->
                                <a href='exam_list.php?delete_id=<?= $row['made'] ?>'
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa đề thi này không?');"
                                    class='btn btn-danger btn-sm'>Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan='7'>Không có đề thi nào.</td>
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
                    <form id="updateExamForm">
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
                                name="thoigianketthuc" required>
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

    <!-- Bootstrap JS và jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Script để mở modal và gửi dữ liệu cập nhật -->
    <script>
        function openUpdateModal(made, tende, thoigianbatdau, thoigianketthuc, trangthai) {
            // Điền dữ liệu vào modal
            $('#update_made').val(made);
            $('#update_tende').val(tende);
            $('#update_thoigianbatdau').val(thoigianbatdau);
            $('#update_thoigianketthuc').val(thoigianketthuc);
            $('#update_trangthai').val(trangthai);

            // Mở modal
            $('#updateExamModal').modal('show');
        }

        // Xử lý form cập nhật
        $('#updateExamForm').submit(function(e) {
            e.preventDefault();
            const data = $(this).serialize();
            $.post('update_exam.php', data, function(response) {
                alert('Cập nhật thành công!');
                window.location.reload();
            }).fail(function() {
                alert('Cập nhật thất bại!');
            });
        });
    </script>
</body>

</html>