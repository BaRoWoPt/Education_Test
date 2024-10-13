<?php
// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Nhận mã câu hỏi từ yêu cầu GET (hoặc POST)
$macauhoi = isset($_GET['macauhoi']) ? intval($_GET['macauhoi']) : 0;

// Kiểm tra nếu mã câu hỏi hợp lệ
if ($macauhoi > 0) {
    // Lấy câu hỏi theo mã câu hỏi
    $sqlQuestion = "SELECT * FROM cauhoi WHERE macauhoi = ?";
    $stmtQuestion = $conn->prepare($sqlQuestion);
    $stmtQuestion->bind_param("i", $macauhoi);
    $stmtQuestion->execute();
    $resultQuestion = $stmtQuestion->get_result();

    if ($resultQuestion->num_rows > 0) {
        $questionData = $resultQuestion->fetch_assoc();

        // Lấy các câu trả lời tương ứng
        $sqlAnswers = "SELECT * FROM cautraloi WHERE macauhoi = ?";
        $stmtAnswers = $conn->prepare($sqlAnswers);
        $stmtAnswers->bind_param("i", $macauhoi);
        $stmtAnswers->execute();
        $resultAnswers = $stmtAnswers->get_result();

        $answers = [];
        if ($resultAnswers->num_rows > 0) {
            while ($rowAnswer = $resultAnswers->fetch_assoc()) {
                $answers[] = [
                    'noidungtl' => $rowAnswer['noidungtl'],
                    'ladapan' => $rowAnswer['ladapan']
                ];
            }
        } else {
            $answers = ['error' => 'Không tìm thấy câu trả lời cho câu hỏi này.'];
        }

        // Chuẩn bị dữ liệu trả về
        $response = [
            'macauhoi' => $questionData['macauhoi'],
            'mamonhoc' => $questionData['mamonhoc'],
            'chuong' => $questionData['chuong'], // Sử dụng `machuong` thay vì `chuong`
            'noidung' => $questionData['noidung'],
            'dokho' => $questionData['dokho'],
            'cautraloi' => $answers
        ];

        // Trả về dữ liệu dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        // Nếu không tìm thấy câu hỏi
        echo json_encode(['error' => 'Không tìm thấy câu hỏi với mã đã cho.']);
    }
} else {
    // Nếu không nhận được mã câu hỏi hoặc mã câu hỏi không hợp lệ
    echo json_encode(['error' => 'Mã câu hỏi không hợp lệ.']);
}

// Đóng kết nối
$conn->close();
