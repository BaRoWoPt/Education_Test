<?php
// Kết nối đến cơ sở dữ liệu
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Thay đổi nếu cần
$password = ""; // Thay đổi nếu cần
$dbname = "WebThiTracNghiem"; // Thay đổi nếu cần

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy ID câu hỏi từ URL
if (isset($_GET['id'])) {
    $macauhoi = $_GET['id'];

    // Xóa các câu trả lời liên quan đến câu hỏi
    $sqlDeleteAnswers = "DELETE FROM cautraloi WHERE macauhoi = '$macauhoi'";
    if ($conn->query($sqlDeleteAnswers) === TRUE) {

        // Xóa câu hỏi
        $sqlDeleteQuestion = "DELETE FROM cauhoi WHERE macauhoi = '$macauhoi'";
        if ($conn->query($sqlDeleteQuestion) === TRUE) {
            // Xóa thành công, chuyển hướng về trang danh sách câu hỏi
            header("Location: question_view.php");
            exit();
        } else {
            echo "Lỗi khi xóa câu hỏi: " . $conn->error;
        }
    } else {
        echo "Lỗi khi xóa câu trả lời: " . $conn->error;
    }
} else {
    echo "ID câu hỏi không hợp lệ.";
}

// Đóng kết nối
$conn->close();