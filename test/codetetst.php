// Kết nối tới cơ sở dữ liệu
include('connect.php');

// Hàm để xoá môn học và các nhóm liên quan
function deleteMonHoc($mamon) {
global $conn;

// Bắt đầu giao dịch
mysqli_begin_transaction($conn);
try {
// Xoá nhóm liên quan đến môn học
$sqlDeleteNhom = "DELETE FROM nhom WHERE mamon = ?";
$stmtDeleteNhom = mysqli_prepare($conn, $sqlDeleteNhom);
mysqli_stmt_bind_param($stmtDeleteNhom, "s", $mamon);
mysqli_stmt_execute($stmtDeleteNhom);

// Xoá môn học
$sqlDeleteMonHoc = "DELETE FROM monhoc WHERE mamon = ?";
$stmtDeleteMonHoc = mysqli_prepare($conn, $sqlDeleteMonHoc);
mysqli_stmt_bind_param($stmtDeleteMonHoc, "s", $mamon);
mysqli_stmt_execute($stmtDeleteMonHoc);

// Cam kết giao dịch
mysqli_commit($conn);
echo "Xoá môn học và nhóm liên quan thành công.";
} catch (Exception $e) {
// Nếu có lỗi, hủy giao dịch
mysqli_rollback($conn);
echo "Lỗi: " . $e->getMessage();
}

// Đóng kết nối
mysqli_stmt_close($stmtDeleteNhom);
mysqli_stmt_close($stmtDeleteMonHoc);
mysqli_close($conn);
}

// Gọi hàm xoá môn học
if (isset($_POST['mamon'])) {
$mamon = $_POST['mamon'];
deleteMonHoc($mamon);
}