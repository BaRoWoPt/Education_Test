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

// Xử lý thêm và cập nhật môn học
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mamonhoc'])) {
    $mamonhoc = $_POST['mamonhoc'];
    $tenmonhoc = $_POST['tenmonhoc'];
    $sotinchi = $_POST['sotinchi'];
    $sotietlythuyet = $_POST['sotietlythuyet'];
    $sotietthuchanh = $_POST['sotietthuchanh'];
    $trangthai = isset($_POST['trangthai']) ? 1 : 0; // Kiểm tra trạng thái

    if (isset($_POST['id']) && !empty($_POST['id'])) {
        $id = $_POST['id'];
        // Cập nhật môn học
        $sql = "UPDATE monhoc SET 
                tenmonhoc='$tenmonhoc',
                sotinchi='$sotinchi',
                sotietlythuyet='$sotietlythuyet',
                sotietthuchanh='$sotietthuchanh',
                trangthai='$trangthai'
                WHERE mamonhoc='$id'";
    } else {
        // Thêm môn học mới
        $sql = "INSERT INTO monhoc (mamonhoc, tenmonhoc, sotinchi, sotietlythuyet, sotietthuchanh, trangthai)
                VALUES ('$mamonhoc', '$tenmonhoc', '$sotinchi', '$sotietlythuyet', '$sotietthuchanh', '$trangthai')";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Lỗi: " . $conn->error; // Hiển thị lỗi nếu có
    }
}

// Xử lý xóa môn học
if (isset($_GET['delete'])) {
    $mamonhoc = $_GET['delete'];
    $sql = "DELETE FROM monhoc WHERE mamonhoc = '$mamonhoc'";
    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Lấy danh sách môn học từ cơ sở dữ liệu
$sql = "SELECT * FROM monhoc";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý môn học</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* CSS cho giao diện */
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
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
        flex-grow: 1;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }

    .btn-primary {
        background-color: #821131;
        color: #FFD700;
        border: none;
    }

    th {
        background-color: #f2f2f2;
    }

    .edit,
    .delete {
        text-decoration: none;
        color: white;
        padding: 5px 10px;
        margin: 2px;
        border-radius: 3px;
    }

    .edit {
        background-color: green;
    }

    .delete {
        background-color: red;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .content {
            margin-left: 200px;
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            width: 100%;
            position: relative;
            height: auto;
        }

        .content {
            margin-left: 0;
            padding: 10px;
        }
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
        <div class="container-fluid">
            <h2 class="text-left">Quản lý môn học</h2>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addModal">Thêm môn học
                mới</button>

            <!-- Modal thêm môn học -->
            <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">Thêm môn học mới</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <label>Mã môn học</label>
                                <input type="text" name="mamonhoc" class="form-control" required>
                                <label>Tên môn học</label>
                                <input type="text" name="tenmonhoc" class="form-control" required>
                                <label>Số tín chỉ</label>
                                <input type="number" name="sotinchi" class="form-control">
                                <label>Số tiết lý thuyết</label>
                                <input type="number" name="sotietlythuyet" class="form-control">
                                <label>Số tiết thực hành</label>
                                <input type="number" name="sotietthuchanh" class="form-control">
                                <label>Trạng thái</label>
                                <input type="checkbox" name="trangthai" value="1" checked> Hoạt động
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Thêm mới</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Hiển thị danh sách môn học -->
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Mã môn học</th>
                        <th>Tên môn học</th>
                        <th>Số tín chỉ</th>
                        <th>Số tiết lý thuyết</th>
                        <th>Số tiết thực hành</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['mamonhoc']; ?></td>
                        <td><?php echo $row['tenmonhoc']; ?></td>
                        <td><?php echo $row['sotinchi']; ?></td>
                        <td><?php echo $row['sotietlythuyet']; ?></td>
                        <td><?php echo $row['sotietthuchanh']; ?></td>
                        <td><?php echo $row['trangthai'] ? 'Hoạt động' : 'Ngưng hoạt động'; ?></td>
                        <td>
                            <a href="#" class="edit" data-toggle="modal" data-target="#editModal"
                                data-mamonhoc="<?php echo $row['mamonhoc']; ?>"
                                data-tenmonhoc="<?php echo $row['tenmonhoc']; ?>"
                                data-sotinchi="<?php echo $row['sotinchi']; ?>"
                                data-sotietlythuyet="<?php echo $row['sotietlythuyet']; ?>"
                                data-sotietthuchanh="<?php echo $row['sotietthuchanh']; ?>"
                                data-trangthai="<?php echo $row['trangthai']; ?>">Sửa</a>
                            <a href="?delete=<?php echo $row['mamonhoc']; ?>" class="delete">Xóa</a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="7">Không có dữ liệu môn học.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Modal chỉnh sửa môn học -->
            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="post" action="">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Chỉnh sửa môn học</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="id" id="edit-id">
                                <label>Mã môn học</label>
                                <input type="text" name="mamonhoc" id="edit-mamonhoc" class="form-control" required
                                    readonly>
                                <label>Tên môn học</label>
                                <input type="text" name="tenmonhoc" id="edit-tenmonhoc" class="form-control" required>
                                <label>Số tín chỉ</label>
                                <input type="number" name="sotinchi" id="edit-sotinchi" class="form-control">
                                <label>Số tiết lý thuyết</label>
                                <input type="number" name="sotietlythuyet" id="edit-sotietlythuyet"
                                    class="form-control">
                                <label>Số tiết thực hành</label>
                                <input type="number" name="sotietthuchanh" id="edit-sotietthuchanh"
                                    class="form-control">
                                <label>Trạng thái</label>
                                <input type="checkbox" name="trangthai" id="edit-trangthai" value="1"> Hoạt động
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    // JavaScript xử lý cho modal chỉnh sửa
    $('#editModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Lấy button đã kích hoạt modal
        var mamonhoc = button.data('mamonhoc');
        var tenmonhoc = button.data('tenmonhoc');
        var sotinchi = button.data('sotinchi');
        var sotietlythuyet = button.data('sotietlythuyet');
        var sotietthuchanh = button.data('sotietthuchanh');
        var trangthai = button.data('trangthai');

        var modal = $(this);
        modal.find('#edit-id').val(mamonhoc);
        modal.find('#edit-mamonhoc').val(mamonhoc);
        modal.find('#edit-tenmonhoc').val(tenmonhoc);
        modal.find('#edit-sotinchi').val(sotinchi);
        modal.find('#edit-sotietlythuyet').val(sotietlythuyet);
        modal.find('#edit-sotietthuchanh').val(sotietthuchanh);
        modal.find('#edit-trangthai').prop('checked', trangthai == 1);
    });
    </script>
</body>

</html>