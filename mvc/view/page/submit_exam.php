<?php
session_start();
$hoten = $_SESSION['hoten'];
$made = $_POST['made'] ?? 0; // ID của đề thi
$userId = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem";

error_reporting(E_ALL);
ini_set('display_errors', 1);
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy đáp án đã chọn từ form
$answers = $_POST['answer'] ?? [];
$score = 0; // Khởi tạo điểm số
$questionNumber = 1; // Biến để theo dõi số thứ tự câu hỏi

// Mảng để lưu trữ kết quả chi tiết
$resultDetails = [];
foreach ($answers as $macauhoi => $selectedAnswers) {
    // Lấy danh sách đáp án đúng cho câu hỏi hiện tại
    $sqlCorrectAnswers = "
        SELECT macautl 
        FROM cautraloi 
        WHERE macauhoi = ? AND ladapan = 1";
    $stmt = $conn->prepare($sqlCorrectAnswers);

    if ($stmt) {
        $stmt->bind_param("i", $macauhoi);
        $stmt->execute();
        $result = $stmt->get_result();

        $correctAnswers = [];
        while ($row = $result->fetch_assoc()) {
            $correctAnswers[] = $row['macautl'];
        }

        // Kiểm tra đáp án đúng
        $isCorrect = !array_diff($correctAnswers, $selectedAnswers) && count($correctAnswers) === count($selectedAnswers);

        // Lưu kết quả vào mảng
        if (count($correctAnswers) > 1) {
            if (!$isCorrect) {
                $resultDetails[] = "<div class='result incorrect'>Câu {$questionNumber} [{$macauhoi}] có nhiều đáp án đúng!<br>Đáp án đúng: " . implode(", ", $correctAnswers) . "</div>";
            } else {
                $score++; // Tăng điểm nếu đáp án đúng
                $resultDetails[] = "<div class='result correct'>Câu {$questionNumber} [{$macauhoi}]: Đúng!</div>";
            }
        } else {
            // Câu hỏi chỉ có một đáp án đúng
            if ($isCorrect) {
                $score++; // Tăng điểm nếu đáp án đúng
                $resultDetails[] = "<div class='result correct'>Câu {$questionNumber} [{$macauhoi}]: Đúng!</div>";
            } else {
                $resultDetails[] = "<div class='result incorrect'>Câu {$questionNumber} [{$macauhoi}]: Sai.<br>Đáp án đúng: " . implode(", ", $correctAnswers) . "</div>";
            }
        }

        $questionNumber++; // Tăng biến đếm sau mỗi câu hỏi
        $stmt->close(); // Đóng biến $stmt sau khi sử dụng
    } else {
        echo "Lỗi khi chuẩn bị câu truy vấn: " . $conn->error;
    }
}

// Lấy thông tin đề thi để xác định môn học
$sqlExamInfo = "SELECT monthi FROM dethi WHERE made = ?";
$stmtExamInfo = $conn->prepare($sqlExamInfo);
$stmtExamInfo->bind_param("i", $made);
$stmtExamInfo->execute();
$resultExamInfo = $stmtExamInfo->get_result();

// Kiểm tra xem có kết quả hay không
if ($resultExamInfo->num_rows > 0) {
    $examInfo = $resultExamInfo->fetch_assoc();
    $monthi = $examInfo['monthi'];

    // Lấy tổng số câu hỏi trong đề thi dựa vào monthi
    $sqlTotalQuestions = "SELECT COUNT(*) as total FROM cauhoi WHERE mamonhoc = ?";
    $stmtTotal = $conn->prepare($sqlTotalQuestions);
    $stmtTotal->bind_param("i", $monthi);
    $stmtTotal->execute();
    $resultTotal = $stmtTotal->get_result();
    $totalQuestions = $resultTotal->fetch_assoc()['total'];

    // Tính phần trăm và hiển thị điểm số
    $percentage = $totalQuestions > 0 ? ($score / $totalQuestions) * 100 : 0; // Tính phần trăm
    $maxScore = 10; // Giả sử điểm tối đa là 10
    $scorePoints = ($percentage / 100) * $maxScore; // Tính điểm thực tế

    // Lưu các thông tin cần thiết vào session để sử dụng sau này
    $_SESSION['scorePoints'] = $scorePoints;
    $_SESSION['score'] = $score;
    $_SESSION['makq'] = $userId . $made;
    $_SESSION['made'] = $made;
    $_SESSION['resultDetails'] = $resultDetails; // Lưu kết quả chi tiết vào session
} else {
    // Xử lý khi không tìm thấy thông tin đề thi
    echo "<div class='error'>Không tìm thấy thông tin đề thi. Vui lòng kiểm tra lại.</div>";
}

// Kết thúc kết nối
$stmtTotal->close();
$stmtExamInfo->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        color: #333;
        margin: 0;
        padding: 20px;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
        text-align: center;
        color: #f44336;
    }

    .score {
        font-size: 1.2em;
        font-weight: bold;
        text-align: center;
    }

    .result {
        margin: 10px 0;
        padding: 10px;
        border-radius: 5px;
    }

    .correct {
        color: #77CDFF;
        background-color: #e8f5e9;
    }

    .incorrect {
        color: #f44336;
        background-color: #ffebee;
    }

    .result-container {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    .circle {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
    }

    button {
        background-color: #f44336;
        /* Màu nền */
        color: white;
        /* Màu chữ */
        padding: 15px 30px;
        /* Kích thước padding */
        border: none;
        /* Xóa viền */
        border-radius: 8px;
        /* Bo góc */
        font-size: 16px;
        /* Kích thước chữ */
        cursor: pointer;
        /* Thay đổi con trỏ khi hover */
        transition: background-color 0.3s, box-shadow 0.3s;
        /* Thêm hiệu ứng chuyển đổi */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        /* Đổ bóng cho nút */
    }

    button:hover {
        background-color: #f44336;
        /* Màu nền khi hover */
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.2);
        /* Tăng đổ bóng khi hover */
    }

    button:active {
        background-color: #f44336;
        /* Màu nền khi nhấn */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        /* Giảm đổ bóng khi nhấn */
        transform: translateY(2px);
        /* Hiệu ứng nhấn xuống */
    }

    canvas {
        background: #fff;
        border-radius: 50%;
    }
    </style>

</head>

<body>
    <div class="container">
        <h1>Kết Quả Bài Thi</h1>
        <div class="score">Bạn đã trả lời đúng <?php echo $score; ?> trên tổng số <?php echo $totalQuestions; ?> câu
            hỏi.</div>
        <div class="result-container">
            <?php
            foreach ($resultDetails as $result) {
                echo $result; // Hiển thị từng kết quả
            }
            ?>
        </div>
        <div class="circle">
            <canvas id="scoreCanvas" width="200" height="200"></canvas>
        </div>

        <!-- Nút xác nhận lưu kết quả -->
        <button id="saveResultBtn">Lưu Kết Quả</button>
    </div>

    <script>
    // Lấy đối tượng canvas và context để vẽ hình tròn
    const canvas = document.getElementById('scoreCanvas');
    const ctx = canvas.getContext('2d');

    // Hàm vẽ hình tròn dựa trên phần trăm
    function drawCircle(percentage) {
        ctx.clearRect(0, 0, canvas.width, canvas.height); // Xóa canvas
        ctx.beginPath();
        ctx.arc(100, 100, 90, 1.5 * Math.PI, (1.5 + (percentage / 100) * 2) * Math.PI); // Vẽ đường tròn
        ctx.lineWidth = 15;
        ctx.strokeStyle = percentage >= 50 ? '#4CAF50' : '#f44336'; // Màu sắc dựa trên điểm số
        ctx.stroke();
        ctx.closePath();

        // Vẽ phần trăm
        ctx.font = '24px Arial';
        ctx.fillStyle = '#333';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(Math.round(percentage) + '%', 100, 100); // Hiển thị phần trăm
    }

    // Gọi hàm vẽ với phần trăm đã tính
    drawCircle(<?php echo $percentage; ?>);

    // Xử lý sự kiện khi nhấn nút "Lưu Kết Quả"
    document.getElementById('saveResultBtn').addEventListener('click', function() {
        const makq = "<?php echo $_SESSION['makq']; ?>";
        const made = "<?php echo $_SESSION['made']; ?>";
        const scorePoints = "<?php echo $_SESSION['scorePoints']; ?>";
        const score = "<?php echo $_SESSION['score']; ?>";

        // Tạo đối tượng XMLHttpRequest để gửi yêu cầu AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "save_result.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Xử lý khi server phản hồi
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText); // Parse phản hồi JSON từ server

                // Kiểm tra trạng thái phản hồi từ server
                if (response.status === 'success') {
                    alert(response.message); // Thông báo thành công
                    window.location.href =
                        "result_list.php"; // Chuyển hướng đến trang student_dashboard.php
                } else {
                    alert(response.message); // Thông báo lỗi nếu có
                }
            }
        };

        // Gửi yêu cầu với dữ liệu đã lấy từ session
        xhr.send(`makq=${makq}&made=${made}&scorePoints=${scorePoints}&score=${score}`);
    });
    </script>

</body>

</html>