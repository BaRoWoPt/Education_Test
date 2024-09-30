<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'WebThiTracNghiem';

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $user, $pass, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập mã hóa để làm việc với UTF-8
$conn->set_charset('utf8');

// Giả định manhomquyen được lấy từ phiên
session_start();
$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền

// Kiểm tra quyền truy cập
if ($manhomquyen != 10) {
    echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
    exit; // Ngừng thực thi nếu không có quyền
}

// Phân trang
$limit = 5; // Số lượng nhóm mỗi trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Lấy số trang hiện tại
$offset = ($page - 1) * $limit; // Tính toán vị trí bắt đầu

// Lấy danh sách sinh viên từ bảng nguoidung
$sinhVienQuery = "SELECT id, hoten FROM nguoidung WHERE trangthai = 1 AND manhomquyen = 11"; // Chỉ lấy sinh viên đang hoạt động
$sinhVienResult = $conn->query($sinhVienQuery);
$sinhViens = [];

if ($sinhVienResult->num_rows > 0) {
    while ($row = $sinhVienResult->fetch_assoc()) {
        $sinhViens[] = $row;
    }
}

// Kiểm tra nếu có yêu cầu thêm nhóm
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['action'])) {
    $manhom = $_POST['manhom'];
    $tennhom = $_POST['tennhom'];
    $siso = $_POST['siso'];
    $giangvien = $_POST['giangvien'];
    $mamonhoc = $_POST['mamonhoc'];
    $students = $_POST['students'];

    // Thêm nhóm vào bảng nhom
    $addGroupQuery = "INSERT INTO nhom (manhom, tennhom, siso, giangvien, mamonhoc) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($addGroupQuery);
    $stmt->bind_param("isisi", $manhom, $tennhom, $siso, $giangvien, $mamonhoc);

    if ($stmt->execute()) {
        // Thêm sinh viên vào bảng chitietnhom
        foreach ($students as $student) {
            $addDetailQuery = "INSERT INTO chitietnhom (manhom, manguoidung) VALUES (?, ?)";
            $detailStmt = $conn->prepare($addDetailQuery);
            $detailStmt->bind_param("is", $manhom, $student);
            $detailStmt->execute();
        }
        // Chuyển hướng về trang hiện tại
        header("Location: " . $_SERVER['PHP_SELF']);
        exit; // Dừng thực thi script sau khi chuyển hướng
    } else {
        echo "<script>alert('Có lỗi khi thêm nhóm học phần.');</script>";
    }

    $stmt->close();
}

// Kiểm tra nếu có yêu cầu xóa sinh viên
// Kiểm tra nếu có yêu cầu xóa sinh viên
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'delete_student') {
    $manhom = $_POST['manhom'];
    $manguoidung = $_POST['manguoidung'];

    // Xóa sinh viên khỏi nhóm
    $deleteStudentQuery = "DELETE FROM chitietnhom WHERE manhom = ? AND manguoidung = ?";
    $deleteStmt = $conn->prepare($deleteStudentQuery);
    $deleteStmt->bind_param("is", $manhom, $manguoidung);

    if ($deleteStmt->execute()) {
        // Thiết lập biến thông báo
        $_SESSION['delete_message'] = 'Xóa sinh viên thành công.';
    } else {
        $_SESSION['delete_message'] = 'Có lỗi khi xóa sinh viên.';
    }

    $deleteStmt->close();
    // Chuyển hướng về trang hiện tại sau khi xóa
    header("Location: " . $_SERVER['PHP_SELF']);
    exit; // Dừng thực thi script sau khi chuyển hướng
}


// Lấy danh sách nhóm học phần hiện có với phân trang
$groupsQuery = "SELECT * FROM nhom LIMIT $limit OFFSET $offset";
$groupsResult = $conn->query($groupsQuery);

// Đếm tổng số nhóm để tính toán số trang
$totalGroupsQuery = "SELECT COUNT(*) as total FROM nhom";
$totalGroupsResult = $conn->query($totalGroupsQuery);
$totalGroups = $totalGroupsResult->fetch_assoc()['total'];
$totalPages = ceil($totalGroups / $limit);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Nhóm Học Phần</title>
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
    }

    .sidebar {
        height: 100vh;
        width: 250px;
        background-color: #a12c2f;
        position: fixed;
        color: white;
        padding-top: 20px;
    }

    .sidebar a:hover {
        background-color: #921e24;
    }


    .sidebar h2 {
        text-align: center;
        font-weight: bold;
        color: white;
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

    .sidebar a {
        display: block;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        font-size: 18px;
    }

    .content {
        margin-left: 250px;
        padding: 20px;
    }

    .btn-primary,
    .btn-info {
        background-color: #821131;
        color: #FFD700;
        border: none;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="sidebar">
                <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span> </h2>

                <div class="menu-section">
                    <h3>Quản lý</h3>
                    <a href="../page/dashboard.php">Tổng quan</a>
                    <a href="../page/classView.php">Nhóm học phần</a>
                    <a href="#">Câu hỏi</a>
                    <a href="#">Người dùng</a>
                    <a href="#">Môn học</a>
                    <!-- <a href="#">Phân công</a> -->
                    <a href="#">Đề kiểm tra</a>
                    <a href="#">Thông báo</a>
                </div>

                <!-- <div class="menu-section">
            <h3>Quản trị</h3>
            <a href="#">Nhóm quyền</a>
        </div> -->
            </div>

            <div class="col-md-10 content">
                <h4>Danh Sách Nhóm Học Phần</h4>
                <?php if (isset($_SESSION['delete_message'])) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $_SESSION['delete_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['delete_message']); // Xóa thông báo sau khi hiển thị 
                    ?>
                <?php endif; ?>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGroupModal">+ Thêm
                    Nhóm</button>

                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Mã Nhóm</th>
                            <th>Tên Nhóm</th>
                            <th>Sĩ Số</th>
                            <th>Giảng Viên</th>
                            <th>Mã Môn Học</th>
                            <th>Danh Sách Sinh Viên</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($group = $groupsResult->fetch_assoc()) : ?>
                        <tr>
                            <td><?php echo $group['manhom']; ?></td>
                            <td><?php echo $group['tennhom']; ?></td>
                            <td><?php echo $group['siso']; ?></td>
                            <td><?php echo $group['giangvien']; ?></td>
                            <td><?php echo $group['mamonhoc']; ?></td>
                            <td>
                                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#viewStudentsModal"
                                    data-manhom="<?php echo $group['manhom']; ?>">Xem Sinh Viên</button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <!-- Phân trang -->
                <nav>
                    <ul class="pagination">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal để Thêm Nhóm -->
    <div class="modal fade" id="addGroupModal" tabindex="-1" aria-labelledby="addGroupModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGroupModalLabel">Thêm Nhóm Học Phần</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="manhom" class="form-label">Mã Nhóm</label>
                            <input type="text" class="form-control" id="manhom" name="manhom" required>
                        </div>
                        <div class="mb-3">
                            <label for="tennhom" class="form-label">Tên Nhóm</label>
                            <input type="text" class="form-control" id="tennhom" name="tennhom" required>
                        </div>
                        <div class="mb-3">
                            <label for="siso" class="form-label">Sĩ Số</label>
                            <input type="number" class="form-control" id="siso" name="siso" required>
                        </div>
                        <div class="mb-3">
                            <label for="giangvien" class="form-label">Giảng Viên</label>
                            <input type="text" class="form-control" id="giangvien" name="giangvien" required>
                        </div>
                        <div class="mb-3">
                            <label for="mamonhoc" class="form-label">Mã Môn Học</label>
                            <input type="text" class="form-control" id="mamonhoc" name="mamonhoc" required>
                        </div>
                        <div class="mb-3">
                            <label for="students" class="form-label">Chọn Sinh Viên</label>
                            <select multiple class="form-select" id="students" name="students[]" required>
                                <?php foreach ($sinhViens as $sinhVien): ?>
                                <option value="<?php echo $sinhVien['id']; ?>"><?php echo $sinhVien['hoten']; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm Nhóm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal để Xem Sinh Viên -->
    <div class="modal fade" id="viewStudentsModal" tabindex="-1" aria-labelledby="viewStudentsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewStudentsModalLabel">Danh Sách Sinh Viên</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tên Sinh Viên</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody id="studentListBody">
                            <!-- Danh sách sinh viên sẽ được nạp bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Nạp danh sách sinh viên vào modal khi nhấn nút "Xem Sinh Viên"
    document.addEventListener('DOMContentLoaded', function() {
        var viewStudentsButtons = document.querySelectorAll('[data-bs-target="#viewStudentsModal"]');

        viewStudentsButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var manhom = button.getAttribute('data-manhom');
                var studentListBody = document.getElementById('studentListBody');

                // Xóa nội dung cũ
                studentListBody.innerHTML = '';

                // Gửi yêu cầu AJAX để lấy danh sách sinh viên trong nhóm
                var xhr = new XMLHttpRequest();
                xhr.open('GET', 'get_students.php?manhom=' + manhom, true);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var students = JSON.parse(xhr.responseText);
                        students.forEach(function(student) {
                            var row = document.createElement('tr');
                            row.innerHTML = '<td>' + student.hoten + '</td>' +
                                '<td><form method="POST" action=""><input type="hidden" name="manhom" value="' +
                                manhom + '">' +
                                '<input type="hidden" name="manguoidung" value="' +
                                student.id + '">' +
                                '<button type="submit" name="action" value="delete_student" class="btn btn-danger">Xóa</button></form></td>';
                            studentListBody.appendChild(row);
                        });
                    }
                };
                xhr.send();
            });
        });
    });
    </script>
</body>

</html>