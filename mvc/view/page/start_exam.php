<?php
session_start();
$userId = $_SESSION['user_id'];

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

// Truy vấn để lấy thông tin đề thi (số câu dễ, trung bình, khó và thời gian làm bài)
$sql = "
    SELECT monthi, socaude, socautb, socaukho, thoigianthi 
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
$thoigianthi = $examInfo['thoigianthi']; // Thời gian làm bài

// HTML cải tiến bắt đầu từ đây
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Đề Thi</title>
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
        }

        .container {
            margin-top: 30px;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
        }

        header h2 {
            margin: 0;
            font-size: 1.5rem;
        }

        .timer {
            font-size: 1.25rem;
        }

        .question-card {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #f9f9f9;
            transition: background-color 0.3s;
        }

        .question-card.selected {
            background-color: #d1e7dd;
        }

        .btn-submit {
            background-color: #007bff;
            border: none;
            color: white;
            width: 100%;
            padding: 10px;
            font-size: 1.25rem;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>

    <div class="container">
        <header>
            <h2>Thí sinh: <?= htmlspecialchars($_SESSION['hoten'] ?? '') ?></h2>
            <div class="timer" id="timer">
                <span id="countdown"><?= gmdate("H:i:s", $thoigianthi * 60) ?></span>
            </div>
        </header>

        <h2 class="text-center mb-4">Thông Tin Đề Thi</h2>

        <div class="alert alert-info">
            <p><strong>Mã Đề:</strong> <?= htmlspecialchars($made) ?></p>
        </div>

        <?php
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

        // Lấy các câu hỏi theo độ khó (câu hỏi dễ, trung bình, khó)
        $questions = [];
        $questions = array_merge($questions, getQuestionsByDifficulty($conn, $mamonhoc, 1, $socaude));
        $questions = array_merge($questions, getQuestionsByDifficulty($conn, $mamonhoc, 2, $socautb));
        $questions = array_merge($questions, getQuestionsByDifficulty($conn, $mamonhoc, 3, $socaukho));

        // Hiển thị câu hỏi cho người dùng
        if (count($questions) > 0) {
            echo "<form method='POST' action='submit_exam.php'>";
            echo "<input type='hidden' name='made' value='" . htmlspecialchars($made) . "'>"; // Thêm mã đề vào form
            echo "<input type='hidden' name='thoigianlambai' value='" . ($thoigianthi * 60) . "'>"; // Thêm thời gian làm bài vào form
            $questionNumber = 1; // Khởi tạo biến đếm thứ tự câu hỏi
            foreach ($questions as $question) {
                $macauhoi = $question['macauhoi'];

                // Truy vấn nội dung câu hỏi
                $sqlQuestion = "SELECT noidung, dokho FROM cauhoi WHERE macauhoi = ?";
                $stmtQuestion = $conn->prepare($sqlQuestion);
                $stmtQuestion->bind_param("i", $macauhoi);
                $stmtQuestion->execute();
                $questionData = $stmtQuestion->get_result()->fetch_assoc();

                // Truy vấn các câu trả lời
                $sqlAnswers = "SELECT macautl, noidungtl FROM cautraloi WHERE macauhoi = ?";
                $stmtAns = $conn->prepare($sqlAnswers);
                $stmtAns->bind_param("i", $macauhoi);
                $stmtAns->execute();
                $answers = $stmtAns->get_result();

                echo "<div class='question-card' id='question-$macauhoi'>";
                echo "<h5>Câu hỏi $questionNumber:</h5> <p>" . htmlspecialchars($questionData['noidung']) . "</p>";
                echo "<ul class='list-group'>";
                while ($answer = $answers->fetch_assoc()) {
                    $macautl = $answer['macautl'];
                    $noidungtl = htmlspecialchars($answer['noidungtl']);
                    echo "<li class='list-group-item'>";
                    echo "<input type='checkbox' name='answer[{$macauhoi}][]' value='{$macautl}' onchange='updateSelectedAnswers($macauhoi)'> $noidungtl";
                    echo "</li>";
                }
                echo "</ul>";
                echo "</div>";

                $questionNumber++; // Tăng thứ tự câu hỏi
            }
            echo "<button type='submit' class='btn btn-submit mt-4'>Nộp Bài</button>";
            echo "</form>";
        } else {
            echo "<div class='alert alert-warning'>Không có câu hỏi nào cho đề thi này.</div>";
        }
        ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Thực hiện tự động nộp bài khi người dùng chuyển tab
        document.addEventListener("visibilitychange", function() {
            if (document.visibilityState === 'hidden') {
                alert("Bạn đã chuyển tab. Bài thi sẽ tự động nộp.");
                document.querySelector('form').submit(); // Tự động nộp bài
            }
        });

        // Cảnh báo khi người dùng cố gắng rời khỏi trang
        window.onbeforeunload = function(e) {
            const confirmationMessage = "Bạn có chắc muốn rời khỏi trang? Nếu rời, bài thi sẽ được nộp.";
            // Tự động nộp bài nếu rời khỏi trang
            document.querySelector('form').submit(); // Tự động nộp bài
            e.returnValue = confirmationMessage; // Hiển thị cảnh báo
            return confirmationMessage; // Một số trình duyệt vẫn yêu cầu trả về giá trị này
        };

        // Hàm cập nhật màu sắc cho câu hỏi đã chọn đáp án
        function updateSelectedAnswers(questionId) {
            const questionCard = document.getElementById(`question-${questionId}`);
            questionCard.classList.toggle('selected');
        }

        // Đếm ngược thời gian làm bài
        let timeRemaining = <?= $thoigianthi * 60 ?>; // Thời gian còn lại tính bằng giây
        const countdownElement = document.getElementById("countdown");

        function updateTimer() {
            const hours = Math.floor(timeRemaining / 3600);
            const minutes = Math.floor((timeRemaining % 3600) / 60);
            const seconds = timeRemaining % 60;

            countdownElement.innerHTML =
                `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (timeRemaining > 0) {
                timeRemaining--;
            } else {
                alert("Hết thời gian làm bài. Bài thi sẽ tự động nộp.");
                document.querySelector('form').submit(); // Tự động nộp bài
            }
        }

        setInterval(updateTimer, 1000); // Cập nhật đồng hồ mỗi giây
    </script>
</body>

</html>