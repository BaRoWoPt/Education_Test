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
if (isset($_GET['made'])) {
    $made = $_GET['made'];
    $sql = "SELECT * FROM dethi WHERE made = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $made);
    $stmt->execute();
    $result = $stmt->get_result();
    $exam = $result->fetch_assoc();
}

// Cập nhật đề thi sau khi người dùng gửi form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $made = $_POST['made'];
    $tende = $_POST['tende'];
    $thoigianbatdau = $_POST['thoigianbatdau'];
    $thoigianketthuc = $_POST['thoigianketthuc'];
    $trangthai = $_POST['trangthai'];

    $update_sql = "UPDATE dethi SET tende = ?, thoigianbatdau = ?, thoigianketthuc = ?, trangthai = ? WHERE made = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param('sssii', $tende, $thoigianbatdau, $thoigianketthuc, $trangthai, $made);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật đề thi thành công!');</script>";
        echo "<script>window.location.href='exam_list.php';</script>";
    } else {
        echo "<script>alert('Cập nhật đề thi thất bại!');</script>";
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
        <input type="hidden" name="made" value="<?= $exam['made'] ?>">
        <label for="tende">Tên đề thi:</label>
        <input type="text" id="tende" name="tende" value="<?= $exam['tende'] ?>"><br><br>

        <label for="thoigianbatdau">Thời gian bắt đầu:</label>
        <input type="datetime-local" id="thoigianbatdau" name="thoigianbatdau"
            value="<?= $exam['thoigianbatdau'] ?>"><br><br>

        <label for="thoigianketthuc">Thời gian kết thúc:</label>
        <input type="datetime-local" id="thoigianketthuc" name="thoigianketthuc"
            value="<?= $exam['thoigianketthuc'] ?>"><br><br>

        <label for="trangthai">Trạng thái:</label>
        <select id="trangthai" name="trangthai">
            <option value="1" <?= $exam['trangthai'] == 1 ? 'selected' : '' ?>>Kích hoạt</option>
            <option value="0" <?= $exam['trangthai'] == 0 ? 'selected' : '' ?>>Không kích hoạt</option>
        </select><br><br>

        <input type="submit" value="Cập nhật">
    </form>
</body>

</html>