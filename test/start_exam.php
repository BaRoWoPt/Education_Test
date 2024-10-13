<?php
session_start();

// Kết nối đến cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Lấy mã đề từ POST
$made = $_POST['made'] ?? 0;

// Kiểm tra mã đề hợp lệ
if (!$made) {
    echo "Mã đề không hợp lệ.";
    exit;
}

// Truy vấn để lấy thông tin đề thi (số câu dễ, trung bình, khó)
$sql = "
    SELECT monthi, socaude, socautb, socaukho 
    FROM dethi 
    WHERE made = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $made);
$stmt->execute();
$examInfo = $stmt->get_result()->fetch_assoc();

if (!$examInfo) {
    echo "Đề thi không tồn tại.";
    exit;
}

// Lấy thông tin từ kết quả truy vấn
$mamonhoc = $examInfo['monthi'];
$socaude = $examInfo['socaude'];
$socautb = $examInfo['socautb'];
$socaukho = $examInfo['socaukho'];

// Hiển thị thông tin đề thi
echo "<h2>Thông Tin Đề Thi</h2>";
echo "<p>Môn học: " . htmlspecialchars($mamonhoc) . "</p>";
echo "<p>Số câu dễ: " . htmlspecialchars($socaude) . "</p>";
echo "<p>Số câu trung bình: " . htmlspecialchars($socautb) . "</p>";
echo "<p>Số câu khó: " . htmlspecialchars($socaukho) . "</p>";

// Hàm lấy câu hỏi theo độ khó
function getQuestionsByDifficulty($conn, $mamonhoc, $difficulty, $limit)
{
    $sql = "
        SELECT macauhoi 
        FROM cauhoi 
        WHERE mamonhoc = ? AND dokho = ? 
        ORDER BY RAND() 
        LIMIT ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $mamonhoc, $difficulty, $limit);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// Khởi tạo mảng đếm câu hỏi theo độ khó
$questionCountByDifficulty = [
    'Dễ' => 0,
    'Trung Bình' => 0,
    'Khó' => 0
];

// Khởi tạo mảng lưu số câu hỏi đã lấy
$questions = [];

// Lấy câu hỏi dễ
$easyQuestions = getQuestionsByDifficulty($conn, $mamonhoc, 1, $socaude); // Câu hỏi dễ
if (count($easyQuestions) < $socaude) {
    echo "Không đủ câu hỏi dễ. Yêu cầu: $socaude, Có: " . count($easyQuestions) . ".<br>";
    exit;
}
$questions = array_merge($questions, $easyQuestions);

// Lấy câu hỏi trung bình
$mediumQuestions = getQuestionsByDifficulty($conn, $mamonhoc, 2, $socautb); // Câu hỏi trung bình
if (count($mediumQuestions) < $socautb) {
    echo "Không đủ câu hỏi trung bình. Yêu cầu: $socautb, Có: " . count($mediumQuestions) . ".<br>";
    exit;
}
$questions = array_merge($questions, $mediumQuestions);

// Lấy câu hỏi khó
$hardQuestions = getQuestionsByDifficulty($conn, $mamonhoc, 3, $socaukho); // Câu hỏi khó
if (count($hardQuestions) < $socaukho) {
    echo "Không đủ câu hỏi khó. Yêu cầu: $socaukho, Có: " . count($hardQuestions) . ".<br>";
    exit;
}
$questions = array_merge($questions, $hardQuestions);

// Tổng số câu hỏi đã lấy
$totalQuestions = count($questions);

// Kiểm tra tổng số câu hỏi đã lấy
if ($totalQuestions < ($socaude + $socautb + $socaukho)) {
    echo "Không đủ câu hỏi cho đề thi này. Tổng câu hỏi lấy được: $totalQuestions.";
    exit;
}

// Tiến hành ghi vào bảng chitietdethi
$thutu = 1; // Biến để theo dõi thứ tự câu hỏi
foreach ($questions as $question) {
    $macauhoi = $question['macauhoi'];

    // Kiểm tra xem cặp made và macauhoi đã tồn tại trong chitietdethi chưa
    $checkSql = "SELECT COUNT(*) FROM chitietdethi WHERE made = ? AND macauhoi = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $made, $macauhoi);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $exists = $checkResult->fetch_row()[0];

    // Nếu chưa tồn tại thì thực hiện insert
    if ($exists == 0) {
        $insertSql = "INSERT INTO chitietdethi (made, macauhoi, thutu) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iii", $made, $macauhoi, $thutu);
        $insertStmt->execute();
    } else {
        echo "Câu hỏi với mã $macauhoi đã tồn tại trong đề thi $made. Bỏ qua.<br>";
    }

    $thutu++; // Tăng thứ tự cho câu hỏi tiếp theo
}

// Hiển thị câu hỏi cho người dùng
if ($totalQuestions > 0) {
    echo "<form method='POST' action='submit_exam.php'>";
    foreach ($questions as $question) {
        $macauhoi = $question['macauhoi'];

        // Truy vấn lấy nội dung câu hỏi và độ khó
        $sqlQuestion = "SELECT noidung, dokho FROM cauhoi WHERE macauhoi = ?";
        $stmtQuestion = $conn->prepare($sqlQuestion);
        $stmtQuestion->bind_param("i", $macauhoi);
        $stmtQuestion->execute();
        $resultQuestion = $stmtQuestion->get_result();
        $questionData = $resultQuestion->fetch_assoc();

        $questionContent = $questionData['noidung'];
        $difficulty = $questionData['dokho']; // Lấy độ khó của câu hỏi

        // Chuyển đổi độ khó thành chuỗi tương ứng
        $difficultyLabels = ['Dễ', 'Trung Bình', 'Khó']; // Cập nhật ở đây
        $difficultyLabel = $difficultyLabels[$difficulty - 1]; // Sửa đổi để phù hợp với chỉ số

        echo "<div>";
        echo "<p><strong>Câu hỏi:</strong> $questionContent</p>";
        echo "<p><strong>Mã câu hỏi:</strong> $macauhoi</p>"; // Hiển thị mã câu hỏi
        echo "<p><strong>Độ khó:</strong> $difficultyLabel</p>"; // Hiển thị độ khó

        // Truy vấn câu trả lời cho mỗi câu hỏi
        $sqlAnswers = "SELECT macautl, noidungtl FROM cautraloi WHERE macauhoi = ?";
        $stmtAns = $conn->prepare($sqlAnswers);
        $stmtAns->bind_param("i", $macauhoi);
        $stmtAns->execute();
        $answers = $stmtAns->get_result();

        // Hiển thị các câu trả lời dưới dạng checkbox
        echo "<ul>";
        while ($answer = $answers->fetch_assoc()) {
            $macautl = $answer['macautl'];
            $noidungtl = $answer['noidungtl'];
            echo "<li>
                    <input type='checkbox' name='answer[{$macauhoi}][]' value='{$macautl}'> $noidungtl
                  </li>";
        }
        echo "</ul>";
        echo "</div>";
    }
    echo "<button type='submit'>Nộp Bài</button>";
    echo "</form>";
} else {
    echo "Không có câu hỏi nào cho đề thi này.";
}
