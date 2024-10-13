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

// Lấy một câu hỏi ngẫu nhiên
$sqlRandomQuestion = "SELECT * FROM cauhoi ORDER BY RAND() LIMIT 1";
$resultQuestion = $conn->query($sqlRandomQuestion);

if ($resultQuestion->num_rows > 0) {
    $questionData = $resultQuestion->fetch_assoc();
    $macauhoi = $questionData['macauhoi'];

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
    echo json_encode(['error' => 'Không có câu hỏi nào trong cơ sở dữ liệu.']);
}

// Đóng kết nối
$conn->close();
