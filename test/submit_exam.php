<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy đáp án đã chọn từ form
$answers = $_POST['answer'] ?? [];
$score = 0; // Khởi tạo điểm số

foreach ($answers as $macauhoi => $selectedAnswers) {
    // Lấy danh sách đáp án đúng cho câu hỏi hiện tại
    $sqlCorrectAnswers = "
        SELECT macautl 
        FROM cautraloi 
        WHERE macauhoi = ? AND ladapan = 1";
    $stmt = $conn->prepare($sqlCorrectAnswers);
    $stmt->bind_param("i", $macauhoi);
    $stmt->execute();
    $result = $stmt->get_result();

    $correctAnswers = [];
    while ($row = $result->fetch_assoc()) {
        $correctAnswers[] = $row['macautl'];
    }

    // Kiểm tra đáp án đúng
    $isCorrect = !array_diff($correctAnswers, $selectedAnswers) && count($correctAnswers) === count($selectedAnswers);

    // Thông báo cho câu hỏi có nhiều đáp án
    if (count($correctAnswers) > 1) {
        // Thông báo nếu người dùng trả lời không hoàn toàn chính xác
        if (!$isCorrect) {
            echo "Câu hỏi {$macauhoi} có nhiều đáp án đúng!<br>";
            echo "Đáp án đúng: " . implode(", ", $correctAnswers) . "<br>";
        } else {
            $score++; // Tăng điểm nếu đáp án đúng
            echo "Câu hỏi {$macauhoi}: Đúng!<br>";
        }
    } else {
        // Câu hỏi chỉ có một đáp án đúng
        if ($isCorrect) {
            $score++; // Tăng điểm nếu đáp án đúng
            echo "Câu hỏi {$macauhoi}: Đúng!<br>";
        } else {
            echo "Câu hỏi {$macauhoi}: Sai.<br>";
            echo "Đáp án đúng: " . implode(", ", $correctAnswers) . "<br>";
        }
    }
}

// Hiển thị điểm số
echo "Bạn đã trả lời đúng {$score} trên tổng số " . count($answers) . " câu hỏi.";

$conn->close();
