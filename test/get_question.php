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

// Lấy mã đề thi từ yêu cầu GET
$made = isset($_GET['made']) ? intval($_GET['made']) : 0;

if ($made > 0) {
    // Lấy số lượng câu hỏi theo từng độ khó từ bảng dethi
    $sqlDeThi = "SELECT socaude, socautb, socaukho FROM dethi WHERE made = ?";
    $stmtDeThi = $conn->prepare($sqlDeThi);
    $stmtDeThi->bind_param("i", $made);
    $stmtDeThi->execute();
    $resultDeThi = $stmtDeThi->get_result();

    if ($resultDeThi->num_rows > 0) {
        $rowDeThi = $resultDeThi->fetch_assoc();
        $soCauDe = $rowDeThi['socaude'];
        $soCauTB = $rowDeThi['socautb'];
        $soCauKho = $rowDeThi['socaukho'];

        $questions = [];

        // Lấy câu hỏi dễ
        if ($soCauDe > 0) {
            $sqlEasy = "SELECT * FROM cauhoi WHERE dokho = 1 ORDER BY RAND() LIMIT ?";
            $stmtEasy = $conn->prepare($sqlEasy);
            $stmtEasy->bind_param("i", $soCauDe);
            $stmtEasy->execute();
            $resultEasy = $stmtEasy->get_result();
            while ($row = $resultEasy->fetch_assoc()) {
                // Lấy câu trả lời tương ứng
                $macauhoi = $row['macauhoi'];
                $answers = getAnswers($conn, $macauhoi);
                $row['cautraloi'] = $answers; // Thêm câu trả lời vào câu hỏi
                $questions[] = $row;
            }
        }

        // Lấy câu hỏi trung bình
        if ($soCauTB > 0) {
            $sqlMedium = "SELECT * FROM cauhoi WHERE dokho = 2 ORDER BY RAND() LIMIT ?";
            $stmtMedium = $conn->prepare($sqlMedium);
            $stmtMedium->bind_param("i", $soCauTB);
            $stmtMedium->execute();
            $resultMedium = $stmtMedium->get_result();
            while ($row = $resultMedium->fetch_assoc()) {
                // Lấy câu trả lời tương ứng
                $macauhoi = $row['macauhoi'];
                $answers = getAnswers($conn, $macauhoi);
                $row['cautraloi'] = $answers; // Thêm câu trả lời vào câu hỏi
                $questions[] = $row;
            }
        }

        // Lấy câu hỏi khó
        if ($soCauKho > 0) {
            $sqlHard = "SELECT * FROM cauhoi WHERE dokho = 3 ORDER BY RAND() LIMIT ?";
            $stmtHard = $conn->prepare($sqlHard);
            $stmtHard->bind_param("i", $soCauKho);
            $stmtHard->execute();
            $resultHard = $stmtHard->get_result();
            while ($row = $resultHard->fetch_assoc()) {
                // Lấy câu trả lời tương ứng
                $macauhoi = $row['macauhoi'];
                $answers = getAnswers($conn, $macauhoi);
                $row['cautraloi'] = $answers; // Thêm câu trả lời vào câu hỏi
                $questions[] = $row;
            }
        }

        // Trả về câu hỏi dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode($questions);
    } else {
        echo json_encode(['error' => 'Đề thi không tồn tại.']);
    }
} else {
    echo json_encode(['error' => 'Mã đề thi không hợp lệ.']);
}

// Hàm lấy câu trả lời tương ứng với mã câu hỏi
function getAnswers($conn, $macauhoi)
{
    $sqlAnswers = "SELECT * FROM cautraloi WHERE macauhoi = ?";
    $stmtAnswers = $conn->prepare($sqlAnswers);
    $stmtAnswers->bind_param("i", $macauhoi);
    $stmtAnswers->execute();
    $resultAnswers = $stmtAnswers->get_result();

    $answers = [];
    while ($rowAnswer = $resultAnswers->fetch_assoc()) {
        $answers[] = [
            'macautl' => $rowAnswer['macautl'],
            'noidungtl' => $rowAnswer['noidungtl'],
            'ladapan' => $rowAnswer['ladapan']
        ];
    }
    return $answers;
}

// Đóng kết nối
$conn->close();
