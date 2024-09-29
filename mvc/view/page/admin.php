<?php
session_start(); // Khởi động session

// Khởi tạo biến để lưu thông báo lỗi
$error_message = '';

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
        // Xóa người dùng không phải là admin
        $sql = "DELETE FROM nguoidung WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $delete_id);
        $stmt->execute();
    } else {
        // Kiểm tra xem còn bao nhiêu admin
        $countAdmins = "SELECT COUNT(*) AS total_admins FROM nguoidung WHERE manhomquyen = 1";
        $countResult = $conn->query($countAdmins);
        $totalAdmins = $countResult->fetch_assoc()['total_admins'];

        if ($totalAdmins > 1) {
            // Xóa admin nếu còn nhiều hơn 1 admin
            $sql = "DELETE FROM nguoidung WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $delete_id);
            $stmt->execute();
        } else {
            // Lưu thông báo lỗi
            $error_message = "Không thể xóa admin duy nhất.";
        }
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

    // Kiểm tra nếu người dùng đang cập nhật nhóm quyền
    if ($new_manhomquyen != 1) {
        // Kiểm tra xem người dùng có phải là admin duy nhất không
        $checkAdmin = "SELECT COUNT(*) AS total_admins FROM nguoidung WHERE manhomquyen = 1";
        $result = $conn->query($checkAdmin);
        $totalAdmins = $result->fetch_assoc()['total_admins'];

        if ($totalAdmins > 1 || ($_SESSION['user_id'] != $update_id)) {
            // Cho phép cập nhật nếu không phải là admin duy nhất hoặc đang cập nhật tài khoản khác
            $sql = "UPDATE nguoidung SET email = ?, hoten = ?, gioitinh = ?, ngaysinh = ?, manhomquyen = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssisss", $new_email, $new_hoten, $new_gioitinh, $new_ngaysinh, $new_manhomquyen, $update_id);
            $stmt->execute();
        } else {
            // Lưu thông báo lỗi
            $error_message = "Không thể thay đổi quyền admin duy nhất.";
        }
    } else {
        // Xử lý cập nhật bình thường nếu không thay đổi quyền
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
    <link rel="icon" href="/mvc//view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        <!-- Hiển thị thông báo lỗi nếu có -->
        <?php if (!empty($error_message)) { ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php } ?>

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
                <?php if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['hoten']; ?></td>
                    <td><?php echo $row['gioitinh'] == 1 ? 'Nam' : 'Nữ'; ?></td>
                    <td><?php echo $row['ngaysinh']; ?></td>
                    <td>
                        <?php
                                switch ($row['manhomquyen']) {
                                    case 1:
                                        echo "Admin";
                                        break;
                                    case 10:
                                        echo "Giảng viên";
                                        break;
                                    case 11:
                                        echo "Sinh viên";
                                        break;
                                    default:
                                        echo "Khách";
                                }
                                ?>
                    </td>
                    <td>
                        <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger"
                            onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?')">Xóa</a>
                        <button class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#updateModal<?php echo $row['id']; ?>">Cập nhật</button>
                    </td>
                </tr>

                <!-- Modal cập nhật -->
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
                                        <input type="email" class="form-control" id="new_email" name="new_email"
                                            value="<?php echo $row['email']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_hoten" class="form-label">Họ và tên</label>
                                        <input type="text" class="form-control" id="new_hoten" name="new_hoten"
                                            value="<?php echo $row['hoten']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_gioitinh" class="form-label">Giới tính</label>
                                        <select class="form-select" id="new_gioitinh" name="new_gioitinh" required>
                                            <option value="1" <?php echo ($row['gioitinh'] == 1) ? 'selected' : ''; ?>>
                                                Nam</option>
                                            <option value="0" <?php echo ($row['gioitinh'] == 0) ? 'selected' : ''; ?>>
                                                Nữ</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_ngaysinh" class="form-label">Ngày sinh</label>
                                        <input type="date" class="form-control" id="new_ngaysinh" name="new_ngaysinh"
                                            value="<?php echo $row['ngaysinh']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="new_manhomquyen" class="form-label">Nhóm quyền</label>
                                        <select class="form-select" id="new_manhomquyen" name="new_manhomquyen"
                                            required>
                                            <option value="1"
                                                <?php echo ($row['manhomquyen'] == 1) ? 'selected' : ''; ?>>Admin
                                            </option>
                                            <option value="10"
                                                <?php echo ($row['manhomquyen'] == 10) ? 'selected' : ''; ?>>Giảng viên
                                            </option>
                                            <option value="11"
                                                <?php echo ($row['manhomquyen'] == 11) ? 'selected' : ''; ?>>Sinh viên
                                            </option>
                                        </select>
                                    </div>
                                    <button type="submit" name="update_user" class="btn btn-primary">Cập nhật</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }
                } else { ?>
                <tr>
                    <td colspan="7">Không có người dùng nào.</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Hiển thị phân trang -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php
                $total_pages = ceil($total_users / $users_per_page);
                for ($i = 1; $i <= $total_pages; $i++) { ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php } ?>
            </ul>
        </nav>

        <!-- Hiển thị tổng số giảng viên và sinh viên -->
        <h2>Tổng số người dùng</h2>
        <p>Giảng viên: <?php echo $total_teachers; ?></p>
        <p>Sinh viên: <?php echo $total_students; ?></p>
    </div>
</body>

</html>

<?php
$conn->close(); // Đóng kết nối
?>