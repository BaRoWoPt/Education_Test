<?php
// Kết nối cơ sở dữ liệu
$conn = mysqli_connect("localhost", "root", "", "WebThiTracNghiem");

// Kiểm tra kết nối
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Lấy dữ liệu từ request
$data = json_decode(file_get_contents('php://input'), true);

// Kiểm tra dữ liệu có tồn tại hay không
if (empty($data['answers'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Không có đáp án nào được chọn!'
    ]);
    exit;
}

// Xử lý từng câu hỏi và kiểm tra đáp án
$correctCount = 0;
$totalQuestions = count($data['answers']);

foreach ($data['answers'] as $answer) {
    $macauhoi = $answer['macauhoi'];
    $selectedAnswer = $answer['answer'];

    // Truy vấn để lấy đáp án đúng cho câu hỏi này
    $query = "SELECT ladapan FROM cautraloi WHERE macauhoi = $macauhoi AND ladapan = 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $correctAnswerRow = mysqli_fetch_assoc($result);
        $correctAnswer = $correctAnswerRow['ladapan'];

        // Kiểm tra xem đáp án người dùng chọn có đúng không
        if ($selectedAnswer == $correctAnswer) {
            $correctCount++;
        }
    }
}

// Tính toán điểm số và phản hồi lại cho người dùng
$score = ($correctCount / $totalQuestions) * 100;

echo json_encode([
    'status' => 'success',
    'message' => "Bạn đã trả lời đúng $correctCount/$totalQuestions câu hỏi. Điểm của bạn là: $score%"
]);

// Đóng kết nối cơ sở dữ liệu
mysqli_close($conn);
