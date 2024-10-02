<?php
session_start(); // Khởi động phiên làm việc

$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'WebThiTracNghiem';

// Tạo kết nối đến cơ sở dữ liệu
$conn = new mysqli($server, $user, $pass, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Hàm xóa sinh viên khỏi nhóm
function deleteStudent($conn, $manhom, $manguoidung)
{
    $deleteStudentQuery = "DELETE FROM chitietnhom WHERE manhom = ? AND manguoidung = ?";
    $deleteStmt = $conn->prepare($deleteStudentQuery);
    $deleteStmt->bind_param("is", $manhom, $manguoidung);

    if ($deleteStmt->execute()) {
        return ['success' => true, 'message' => 'Xóa sinh viên thành công.'];
    } else {
        return ['success' => false, 'message' => 'Có lỗi khi xóa sinh viên.'];
    }
}

// Hàm thêm sinh viên vào nhóm
function addStudent($conn, $manhom, $newStudentId)
{
    // Kiểm tra xem sinh viên đã có trong nhóm chưa
    $checkStudentQuery = "SELECT * FROM chitietnhom WHERE manhom = ? AND manguoidung = ?";
    $checkStmt = $conn->prepare($checkStudentQuery);
    $checkStmt->bind_param("is", $manhom, $newStudentId);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        return ['success' => false, 'message' => 'Sinh viên đã có trong nhóm.'];
    } else {
        // Thêm sinh viên vào nhóm
        $addStudentQuery = "INSERT INTO chitietnhom (manhom, manguoidung) VALUES (?, ?)";
        $addStmt = $conn->prepare($addStudentQuery);
        $addStmt->bind_param("is", $manhom, $newStudentId);

        if ($addStmt->execute()) {
            return ['success' => true, 'message' => 'Thêm sinh viên thành công.'];
        } else {
            return ['success' => false, 'message' => 'Có lỗi khi thêm sinh viên.'];
        }
    }
}

// Hàm lấy danh sách sinh viên trong nhóm
function getStudentsInGroup($conn, $manhom)
{
    $getStudentsInGroupQuery = "SELECT ct.*, nd.hoten FROM chitietnhom ct INNER JOIN nguoidung nd ON ct.manguoidung = nd.id WHERE ct.manhom = ?";
    $stmt = $conn->prepare($getStudentsInGroupQuery);
    $stmt->bind_param("i", $manhom);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }

    return $students;
}

// Hàm lấy danh sách sinh viên khả dụng (chưa có trong nhóm)
function getAvailableStudents($conn, $manhom)
{
    $getAvailableStudentsQuery = "
        SELECT * FROM nguoidung 
        WHERE manhomquyen = 11 
        AND id NOT IN (SELECT manguoidung FROM chitietnhom WHERE manhom = ?)
    ";
    $stmt = $conn->prepare($getAvailableStudentsQuery);
    $stmt->bind_param("i", $manhom);
    $stmt->execute();
    $availableResult = $stmt->get_result();

    $availableStudents = [];
    while ($row = $availableResult->fetch_assoc()) {
        $availableStudents[] = $row;
    }

    return $availableStudents;
}

// Xử lý các yêu cầu POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete_student') {
        $response = deleteStudent($conn, $_POST['manhom'], $_POST['manguoidung']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    if ($_POST['action'] == 'add_student') {
        $response = addStudent($conn, $_POST['manhom'], $_POST['new_student_id']);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}

// Xử lý các yêu cầu GET
if (isset($_GET['action'])) {
    if ($_GET['action'] == 'get_students') {
        $students = getStudentsInGroup($conn, $_GET['manhom']);
        header('Content-Type: application/json');
        echo json_encode($students);
        exit;
    }

    if ($_GET['action'] == 'get_available_students') {
        $availableStudents = getAvailableStudents($conn, $_GET['manhom']);
        header('Content-Type: application/json');
        echo json_encode($availableStudents);
        exit;
    }
}

$conn->close(); // Đóng kết nối