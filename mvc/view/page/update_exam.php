<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy thông tin đề thi cần cập nhật
$exam = null; // Initialize $exam variable
if (isset($_GET['made']) && is_numeric($_GET['made'])) {
    $made = (int)$_GET['made']; // Cast to integer for security
    $sql = "SELECT * FROM dethi WHERE made = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $made);
    $stmt->execute();
    $result = $stmt->get_result();
    $exam = $result->fetch_assoc();

    // Kiểm tra xem đề thi có tồn tại không
    if (!$exam) {
        echo "<script>alert('Đề thi không tồn tại!'); window.location.href='exam_list.php';</script>";
        exit();
    }
}

// Cập nhật đề thi sau khi người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy dữ liệu từ form và kiểm tra
    $made = $_POST['made'];
    $tende = trim($_POST['tende']);
    $thoigianbatdau = $_POST['thoigianbatdau'];
    $thoigianlambai = $_POST['thoigianlambai']; // Thời gian làm bài
    $trangthai = $_POST['trangthai'];

    // Kiểm tra dữ liệu trước khi cập nhật
    if (empty($tende) || !$made || !is_numeric($made)) {
        echo "<script>alert('Dữ liệu không hợp lệ!');</script>";
    } else {
        // Tính toán thời gian kết thúc
        $start_time = new DateTime($thoigianbatdau);
        $duration = new DateInterval('PT' . $thoigianlambai . 'M'); // Giả sử $thoigianlambai là phút
        $end_time = $start_time->add($duration)->format('Y-m-d H:i:s');

        $update_sql = "UPDATE dethi SET tende = ?, thoigianbatdau = ?, thoigianketthuc = ?, trangthai = ? WHERE made = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param('sssii', $tende, $thoigianbatdau, $end_time, $trangthai, $made);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật đề thi thành công!');</script>";
            echo "<script>window.location.href='exam_list.php';</script>";
        } else {
            echo "<script>alert('Cập nhật đề thi thất bại: " . $stmt->error . "');</script>"; // Show detailed error
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập nhật đề thi</title>
</head>

<body>
    <h2>Cập nhật đề thi</h2>
    <form action="update_exam.php" method="POST">
        <input type="hidden" name="made" value="<?= htmlspecialchars($exam['made']) ?>">
        <label for="tende">Tên đề thi:</label>
        <input type="text" id="tende" name="tende" value="<?= htmlspecialchars($exam['tende']) ?>" required><br><br>

        <label for="thoigianbatdau">Thời gian bắt đầu:</label>
        <input type="datetime-local" id="thoigianbatdau" name="thoigianbatdau"
            value="<?= htmlspecialchars(date('Y-m-d\TH:i', strtotime($exam['thoigianbatdau']))) ?>" required><br><br>

        <label for="thoigianlambai">Thời gian làm bài (phút):</label>
        <input type="number" id="thoigianlambai" name="thoigianlambai" min="1"
            value="<?= htmlspecialchars($exam['thoigianlambai']) ?>" required><br><br>

        <label for="trangthai">Trạng thái:</label>
        <select id="trangthai" name="trangthai" required>
            <option value="1" <?= $exam['trangthai'] == 1 ? 'selected' : '' ?>>Kích hoạt</option>
            <option value="0" <?= $exam['trangthai'] == 0 ? 'selected' : '' ?>>Không kích hoạt</option>
        </select><br><br>

        <input type="submit" value="Cập nhật">
    </form>
</body>

</html>