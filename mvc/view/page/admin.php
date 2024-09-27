<?php
session_start(); // Khởi động session

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Kiểm tra quyền truy cập
if ($_SESSION['manhomquyen'] != 1) {
    header("Location: login.php");
    exit();
}

// Xử lý đăng xuất
if (isset($_GET['logout'])) {
    session_destroy(); // Hủy tất cả các session
    header("Location: login.php"); // Chuyển hướng về trang đăng nhập
    exit();
}

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";  // Tên đăng nhập của database
$password = "";  // Mật khẩu của database (nếu có)
$dbname = "WebThiTracNghiem";  // Tên database của bạn
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xử lý xóa người dùng
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Kiểm tra xem người dùng có phải là admin không
    $checkAdmin = "SELECT * FROM nguoidung WHERE id = ? AND manhomquyen = 1";
    $stmt = $conn->prepare($checkAdmin);
    $stmt->bind_param("s", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        $sql = "DELETE FROM nguoidung WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $delete_id);
        $stmt->execute();
    }
}

// Xử lý cập nhật thông tin người dùng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $update_id = $_POST['update_id'];
    $new_email = $_POST['new_email'];
    $new_hoten = $_POST['new_hoten'];
    $new_gioitinh = $_POST['new_gioitinh'];
    $new_ngaysinh = $_POST['new_ngaysinh'];
    $new_manhomquyen = $_POST['new_manhomquyen'];

    // Kiểm tra xem người dùng có phải là admin không
    if ($new_manhomquyen != 1 || $_SESSION['user_id'] != $update_id) {
        $sql = "UPDATE nguoidung SET email = ?, hoten = ?, gioitinh = ?, ngaysinh = ?, manhomquyen = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisss", $new_email, $new_hoten, $new_gioitinh, $new_ngaysinh, $new_manhomquyen, $update_id);
        $stmt->execute();
    }
}

// Số lượng người dùng trên mỗi trang
$users_per_page = 10;

// Xác định trang hiện tại
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $users_per_page;

// Lấy tổng số người dùng
$total_sql = "SELECT COUNT(*) AS total_users FROM nguoidung";
$total_result = $conn->query($total_sql);
$total_users = $total_result->fetch_assoc()['total_users'];

// Lấy danh sách người dùng cho trang hiện tại
$sql = "SELECT * FROM nguoidung LIMIT $users_per_page OFFSET $offset";
$result = $conn->query($sql);

// Lấy tổng số lượng giảng viên và sinh viên
$summary_sql = "SELECT
                    SUM(CASE WHEN manhomquyen = 10 THEN 1 ELSE 0 END) AS total_teachers,
                    SUM(CASE WHEN manhomquyen = 11 THEN 1 ELSE 0 END) AS total_students
                FROM nguoidung";
$summary_result = $conn->query($summary_sql);
$summary = $summary_result->fetch_assoc();
$total_teachers = $summary['total_teachers'];
$total_students = $summary['total_students'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <!-- Thanh điều hướng và nút đăng xuất -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Dashboard</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="?logout=true">Đăng xuất</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h1>Quản lý người dùng</h1>

        <!-- Bảng thông tin người dùng -->
        <h2 class="mt-5">Danh sách người dùng</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Mã sinh viên</th>
                    <th>Họ và tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Nhóm quyền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['hoten']; ?></td>
                        <td><?php echo $row['gioitinh'] == 0 ? 'Nam' : 'Nữ'; ?></td>
                        <td><?php echo $row['ngaysinh']; ?></td>
                        <td>
                            <?php
                            if ($row['manhomquyen'] == 1) echo 'Admin';
                            elseif ($row['manhomquyen'] == 10) echo 'Giảng viên';
                            else echo 'Sinh viên';
                            ?>
                        </td>
                        <td>
                            <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Xóa</a>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#updateModal<?php echo $row['id']; ?>">Cập nhật</button>

                            <!-- Modal cập nhật thông tin -->
                            <div class="modal fade" id="updateModal<?php echo $row['id']; ?>" tabindex="-1"
                                aria-labelledby="updateModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="updateModalLabel">Cập nhật thông tin người dùng</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="">
                                                <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="new_email" class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="new_email"
                                                        value="<?php echo $row['email']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_hoten" class="form-label">Họ và tên</label>
                                                    <input type="text" class="form-control" name="new_hoten"
                                                        value="<?php echo $row['hoten']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_gioitinh" class="form-label">Giới tính</label>
                                                    <select class="form-select" name="new_gioitinh">
                                                        <option value="0"
                                                            <?php echo $row['gioitinh'] == 0 ? 'selected' : ''; ?>>Nam
                                                        </option>
                                                        <option value="1"
                                                            <?php echo $row['gioitinh'] == 1 ? 'selected' : ''; ?>>Nữ
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_ngaysinh" class="form-label">Ngày sinh</label>
                                                    <input type="date" class="form-control" name="new_ngaysinh"
                                                        value="<?php echo $row['ngaysinh']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="new_manhomquyen" class="form-label">Nhóm quyền</label>
                                                    <select class="form-select" name="new_manhomquyen">
                                                        <option value="1"
                                                            <?php echo $row['manhomquyen'] == 1 ? 'selected' : ''; ?>>Admin
                                                        </option>
                                                        <option value="10"
                                                            <?php echo $row['manhomquyen'] == 10 ? 'selected' : ''; ?>>Giảng
                                                            viên</option>
                                                        <option value="11"
                                                            <?php echo $row['manhomquyen'] == 11 ? 'selected' : ''; ?>>Sinh
                                                            viên</option>
                                                    </select>
                                                </div>
                                                <button type="submit" name="update_user" class="btn btn-primary">Cập
                                                    nhật</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Phân trang -->
        <?php
        $total_pages = ceil($total_users / $users_per_page);
        if ($total_pages > 1) {
            echo '<nav><ul class="pagination">';
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<li class="page-item' . ($i == $page ? ' active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
            }
            echo '</ul></nav>';
        }
        ?>

        <!-- Thống kê -->
        <h3 class="mt-5">Thống kê</h3>
        <p>Tổng số giảng viên: <?php echo $total_teachers; ?></p>
        <p>Tổng số sinh viên: <?php echo $total_students; ?></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>