<?php
// Kết nối đến cơ sở dữ liệu
error_reporting(E_ALL);
ini_set('display_errors', 1);
$servername = "localhost"; // Thay đổi nếu cần
$username = "root"; // Thay đổi nếu cần
$password = ""; // Thay đổi nếu cần
$dbname = "WebThiTracNghiem"; // Thay đổi nếu cần

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start();
$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền

// Kiểm tra quyền truy cập
if ($manhomquyen != 10) {
    echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
    exit; // Ngừng thực thi nếu không có quyền
}
// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit'])) {
        // Cập nhật câu hỏi
        $macauhoi = $_POST['macauhoi'];
        $mamonhoc = $_POST['mamonhoc'];
        $noidungcauhoi = $_POST['noidungcauhoi'];
        $chuong = $_POST['chuong'];
        $cautraloi = $_POST['cautraloi'];
        $ladapan = $_POST['ladapan'];

        // Chuyển đổi độ khó từ chuỗi sang số
        $do_kho_mapping = [
            'Cơ bản' => 1,
            'Trung bình' => 2,
            'Nâng cao' => 3
        ];
        $do_kho = $do_kho_mapping[$_POST['do_kho']];

        // Cập nhật câu hỏi
        $sql = "UPDATE cauhoi SET mamonhoc='$mamonhoc', noidung='$noidungcauhoi', dokho='$do_kho', machuong='$chuong' WHERE macauhoi='$macauhoi'";

        if ($conn->query($sql) === TRUE) {
            // Xóa câu trả lời cũ
            $sqlDelete = "DELETE FROM cautraloi WHERE macauhoi='$macauhoi'";
            $conn->query($sqlDelete);

            // Thêm câu trả lời mới
            foreach ($cautraloi as $key => $value) {
                $ladapan_value = (isset($ladapan[$key]) && $ladapan[$key] == 'on') ? 1 : 0;
                $sqlCauTraLoi = "INSERT INTO cautraloi (macauhoi, noidungtl, ladapan) VALUES ('$macauhoi', '$value', $ladapan_value)";
                $conn->query($sqlCauTraLoi);
            }

            // Redirect sau khi xử lý thành công để tránh form resubmission
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Lỗi: " . $conn->error;
        }
    } else {
        // Thêm câu hỏi mới
        $mamonhoc = $_POST['mamonhoc'];
        $noidungcauhoi = $_POST['noidungcauhoi'];
        $chuong = $_POST['chuong'];
        $cautraloi = $_POST['cautraloi'];
        $ladapan = $_POST['ladapan'];

        // Chuyển đổi độ khó từ chuỗi sang số
        $do_kho_mapping = [
            'Cơ bản' => 1,
            'Trung bình' => 2,
            'Nâng cao' => 3
        ];
        $do_kho = $do_kho_mapping[$_POST['do_kho']];

        // Thêm câu hỏi vào cơ sở dữ liệu
        $sql = "INSERT INTO cauhoi (mamonhoc, noidung, dokho, machuong) VALUES ('$mamonhoc', '$noidungcauhoi', '$do_kho', '$chuong')";

        if ($conn->query($sql) === TRUE) {
            $macauhoi = $conn->insert_id; // Lấy ID câu hỏi vừa thêm

            // Thêm câu trả lời vào cơ sở dữ liệu
            foreach ($cautraloi as $key => $value) {
                $ladapan_value = (isset($ladapan[$key]) && $ladapan[$key] == 'on') ? 1 : 0;
                $sqlCauTraLoi = "INSERT INTO cautraloi (macauhoi, noidungtl, ladapan) VALUES ('$macauhoi', '$value', $ladapan_value)";
                $conn->query($sqlCauTraLoi);
            }

            // Redirect sau khi xử lý thành công để tránh form resubmission
            header('Location: ' . $_SERVER['REQUEST_URI']);
            exit();
        } else {
            echo "Lỗi: " . $conn->error;
        }
    }
}

// Truy vấn để lấy danh sách môn học
$sql = "SELECT mamonhoc, tenmonhoc FROM monhoc";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý câu hỏi</title>
    <link rel="icon" href="/mvc//view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        display: flex;
        height: 100vh;
    }

    /* Sidebar styles */
    .sidebar {
        height: 100vh;
        width: 250px;
        background-color: #a12c2f;
        position: fixed;
        top: 0;
        left: 0;
        color: white;
        padding-top: 20px;
        transition: width 0.3s;
    }

    .sidebar h2 {
        text-align: center;
        font-weight: bold;
        color: white;
    }

    .sidebar a {
        display: block;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        font-size: 18px;
    }

    .sidebar a:hover {
        background-color: #921e24;
    }

    .menu-section {
        margin-bottom: 20px;
        margin-top: 60px;
    }

    .menu-section h3 {
        font-size: 16px;
        text-transform: uppercase;
        margin-left: 20px;
        margin-bottom: 10px;
        color: #FFD700;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
    }

    .add-button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 5px;
        text-decoration: none;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
    }

    .modal-content {
        background-color: #fff;
        margin: auto;
        padding: 20px;
        border-radius: 12px;
        /* Bo góc cho mềm mại */
        width: 600px;
        /* Tăng độ rộng để có nhiều không gian */
        max-width: 100%;
        /* Đảm bảo không vượt quá chiều rộng màn hình */
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        /* Thêm hiệu ứng đổ bóng */
        position: relative;
        /* Để vị trí của phần tử con dễ quản lý */
    }

    .modal-header {
        font-weight: bold;
        text-align: center;
        /* Canh giữa tiêu đề modal */
        font-size: 24px;
        /* Tăng kích thước chữ tiêu đề */
        color: #007bff;
        /* Màu xanh chủ đạo */
        margin-bottom: 15px;
    }

    .close {
        position: absolute;
        /* Đặt nút đóng ở góc trên bên phải */
        top: 10px;
        right: 15px;
        color: #333;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: #d9534f;
        /* Màu đỏ khi hover hoặc focus */
    }

    .form-group label {
        font-size: 16px;
        font-weight: bold;
        color: #333;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 14px;
        padding: 10px;
    }

    .form-group input[type="checkbox"] {
        width: auto;
        /* Sửa checkbox để không chiếm chiều rộng của input */
    }

    .save-button {
        width: 100%;
        padding: 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        text-transform: uppercase;
        /* Chữ in hoa */
    }

    .save-button:hover {
        background-color: #218838;
        /* Đổi màu khi hover */
    }

    .question_list {
        margin-top: 20px;
    }

    label {
        font-weight: bold;
    }

    input,
    textarea,
    select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        margin-bottom: 10px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .save-button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span></h2>
        <div class="menu-section">
            <h3>Quản lý</h3>
            <a href="../page/dashboard.php">Tổng quan</a>
            <a href="../page/classView.php">Nhóm học phần</a>
            <a href="../page/question_view.php">Câu hỏi</a>
            <a href="../page/learning.php">Môn học</a>
            <a href="#">Đề kiểm tra</a>
            <a href="#">Thông báo</a>
        </div>
    </div>

    <div class="content">
        <h1>Danh sách câu hỏi</h1>
        <button id="openModalBtn" class="add-button">+ Thêm câu hỏi mới</button>

        <!-- Modal for Adding New Question -->
        <div id="addQuestionModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeModalBtn">&times;</span>
                <h2 class="modal-header">Thêm câu hỏi mới</h2>
                <form method="POST" action="">
                    <input type="hidden" name="macauhoi" id="macauhoi">
                    <div class="form-group">
                        <label for="mamonhoc">Môn học:</label>
                        <select id="mamonhoc" name="mamonhoc" required>
                            <option value="">Chọn môn học</option>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['mamonhoc'] . '">' . $row['tenmonhoc'] . '</option>';
                                }
                            } else {
                                echo '<option value="">Không có môn học nào</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="chuong">Chương:</label>
                        <input type="text" id="chuong" name="chuong" placeholder="Nhập chương" required>
                    </div>
                    <div class="form-group">
                        <label for="noidungcauhoi">Nội dung câu hỏi:</label>
                        <textarea id="noidungcauhoi" name="noidungcauhoi" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="do_kho">Độ khó:</label>
                        <select id="do_kho" name="do_kho" required>
                            <option value="Cơ bản">Cơ bản</option>
                            <option value="Trung bình">Trung bình</option>
                            <option value="Nâng cao">Nâng cao</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cautraloi[]">Câu trả lời:</label>
                        <div>
                            <input type="text" name="cautraloi[]" placeholder="Câu trả lời 1" required>
                            <input type="checkbox" name="ladapan[0]"> Đáp án đúng
                        </div>
                        <div>
                            <input type="text" name="cautraloi[]" placeholder="Câu trả lời 2" required>
                            <input type="checkbox" name="ladapan[1]"> Đáp án đúng
                        </div>
                        <div>
                            <input type="text" name="cautraloi[]" placeholder="Câu trả lời 3" required>
                            <input type="checkbox" name="ladapan[2]"> Đáp án đúng
                        </div>
                        <div>
                            <input type="text" name="cautraloi[]" placeholder="Câu trả lời 4" required>
                            <input type="checkbox" name="ladapan[3]"> Đáp án đúng
                        </div>
                    </div>
                    <button class="save-button" type="submit" name="submit">Thêm câu hỏi</button>
                </form>
            </div>
        </div>

        <!-- Modal for Editing Question -->
        <div id="editQuestionModal" class="modal">
            <div class="modal-content">
                <span class="close" id="closeEditModalBtn">&times;</span>
                <h2 class="modal-header">Sửa câu hỏi</h2>
                <form method="POST" action="">
                    <input type="hidden" name="macauhoi" id="editMacauhoi">
                    <div class="form-group">
                        <label for="editMamonhoc">Môn học:</label>
                        <select id="editMamonhoc" name="mamonhoc" required>
                            <option value="">Chọn môn học</option>
                            <?php
                            $result->data_seek(0); // Đưa con trỏ về đầu
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row['mamonhoc'] . '">' . $row['tenmonhoc'] . '</option>';
                                }
                            } else {
                                echo '<option value="">Không có môn học nào</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editChuong">Chương:</label>
                        <input type="text" id="editChuong" name="chuong" placeholder="Nhập chương" required>
                    </div>
                    <div class="form-group">
                        <label for="editNoidungcauhoi">Nội dung câu hỏi:</label>
                        <textarea id="editNoidungcauhoi" name="noidungcauhoi" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editDo_kho">Độ khó:</label>
                        <select id="editDo_kho" name="do_kho" required>
                            <option value="Cơ bản">Cơ bản</option>
                            <option value="Trung bình">Trung bình</option>
                            <option value="Nâng cao">Nâng cao</option>
                        </select>
                    </div>
                    <div class="form-group" id="editCauTraLoiContainer">
                        <label>Câu trả lời:</label>
                        <!-- Các câu trả lời sẽ được thêm vào đây -->
                    </div>
                    <button class="save-button" type="submit" name="edit">Cập nhật câu hỏi</button>
                </form>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Mã câu hỏi</th>
                    <th>Nội dung</th>
                    <th>Chương</th>
                    <th>Độ khó</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Lấy danh sách câu hỏi
                $sqlQuestions = "SELECT * FROM cauhoi";
                $resultQuestions = $conn->query($sqlQuestions);
                if ($resultQuestions->num_rows > 0) {
                    while ($row = $resultQuestions->fetch_assoc()) {
                        echo "<tr>
                            <td>{$row['macauhoi']}</td>
                            <td>{$row['noidung']}</td>
                            <td>{$row['machuong']}</td>
                            <td>" . ($row['dokho'] == 1 ? 'Cơ bản' : ($row['dokho'] == 2 ? 'Trung bình' : 'Nâng cao')) . "</td>
                            <td>
                                <button class='edit-btn btn btn-warning btn-sm' data-id='{$row['macauhoi']}'>Sửa</button>
                        <a href='delete_question.php?id=" . $row['macauhoi'] . "' class='delete-btn btn btn-danger btn-sm' onclick='return confirm(\"Bạn có chắc muốn xóa câu hỏi này?\")'>Xóa</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có câu hỏi nào</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
    // Mở modal thêm câu hỏi mới
    document.getElementById('openModalBtn').onclick = function() {
        document.getElementById('addQuestionModal').style.display = 'block';
    }

    // Đóng modal
    document.getElementById('closeModalBtn').onclick = function() {
        document.getElementById('addQuestionModal').style.display = 'none';
    }
    document.getElementById('closeEditModalBtn').onclick = function() {
        document.getElementById('editQuestionModal').style.display = 'none';
    }

    // Mở modal chỉnh sửa câu hỏi
    const editButtons = document.querySelectorAll('.edit-btn');
    editButtons.forEach(button => {
        button.onclick = function() {
            const macauhoi = this.getAttribute('data-id');

            // Gọi API hoặc thực hiện AJAX để lấy thông tin câu hỏi và câu trả lời
            fetch('get_question.php?macauhoi=' + macauhoi)
                .then(response => response.json())
                .then(data => {
                    // Điền dữ liệu vào modal
                    document.getElementById('editMacauhoi').value = data.macauhoi;
                    document.getElementById('editMamonhoc').value = data.mamonhoc;
                    document.getElementById('editChuong').value = data.chuong;
                    document.getElementById('editNoidungcauhoi').value = data.noidung;
                    document.getElementById('editDo_kho').value = data.dokho == 1 ? 'Cơ bản' : (data
                        .dokho == 2 ? 'Trung bình' : 'Nâng cao');

                    // Làm sạch container câu trả lời
                    const editCauTraLoiContainer = document.getElementById('editCauTraLoiContainer');
                    editCauTraLoiContainer.innerHTML = ''; // Xóa các câu trả lời cũ

                    data.cautraloi.forEach((cautraloi, index) => {
                        const div = document.createElement('div');
                        div.innerHTML =
                            `<input type="text" name="cautraloi[]" value="${cautraloi.noidungtl}" placeholder="Câu trả lời ${index + 1}" required>
                                            <input type="checkbox" name="ladapan[${index}]" ${cautraloi.ladapan ? 'checked' : ''}> Đáp án đúng`;
                        editCauTraLoiContainer.appendChild(div);
                    });

                    // Mở modal chỉnh sửa
                    document.getElementById('editQuestionModal').style.display = 'block';
                });
        }
    });

    // Xử lý xóa câu hỏi
    </script>
</body>

</html>

<?php
$conn->close();
?>