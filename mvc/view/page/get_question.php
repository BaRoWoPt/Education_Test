<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost"; // Địa chỉ máy chủ cơ sở dữ liệu
$username = "root"; // Tên người dùng
$password = ""; // Mật khẩu
$dbname = "WebThiTracNghiem"; // Tên cơ sở dữ liệu của bạn

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy mã câu hỏi từ yêu cầu GET
$macauhoi = isset($_GET['macauhoi']) ? intval($_GET['macauhoi']) : 0;

if ($macauhoi > 0) {
    // Lấy thông tin câu hỏi
    $sqlQuestion = "SELECT * FROM cauhoi WHERE macauhoi = ?";
    $stmt = $conn->prepare($sqlQuestion);
    $stmt->bind_param("i", $macauhoi);
    $stmt->execute();
    $resultQuestion = $stmt->get_result();

    if ($resultQuestion->num_rows > 0) {
        $questionData = $resultQuestion->fetch_assoc();

        // Lấy các câu trả lời tương ứng
        $sqlAnswers = "SELECT * FROM cautraloi WHERE macauhoi = ?";
        $stmtAnswers = $conn->prepare($sqlAnswers);
        $stmtAnswers->bind_param("i", $macauhoi);
        $stmtAnswers->execute();
        $resultAnswers = $stmtAnswers->get_result();

        $answers = [];
        while ($rowAnswer = $resultAnswers->fetch_assoc()) {
            $answers[] = [
                'noidungtl' => $rowAnswer['noidungtl'],
                'ladapan' => $rowAnswer['ladapan']
            ];
        }

        // Chuẩn bị dữ liệu trả về
        $response = [
            'macauhoi' => $questionData['macauhoi'],
            'mamonhoc' => $questionData['mamonhoc'],
            'chuong' => $questionData['chuong'],
            'noidung' => $questionData['noidung'],
            'dokho' => $questionData['dokho'],
            'cautraloi' => $answers
        ];

        // Trả về dữ liệu dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Nếu không tìm thấy câu hỏi
        echo json_encode(['error' => 'Câu hỏi không tồn tại.']);
    }
} else {
    // Nếu mã câu hỏi không hợp lệ
    echo json_encode(['error' => 'Mã câu hỏi không hợp lệ. Giá trị nhận được: ' . $_GET['macauhoi']]);
}

// Đóng kết nối
$conn->close();