<?php
// Kết nối cơ sở dữ liệu
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "WebThiTracNghiem"; // Đặt tên database của bạn

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
session_start();
$manhomquyen = $_SESSION['manhomquyen'] ?? 0; // Mặc định là 0 nếu không có quyền
$userId = $_SESSION['user_id'];
// Kiểm tra quyền truy cập
if ($manhomquyen != 10) {
    echo "Bạn không có quyền truy cập vào danh sách sinh viên.";
    exit; // Ngừng thực thi nếu không có quyền
}

$userId = $_SESSION['user_id'];
// Lấy ID người dùng từ session
?>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">
    <title>Danh sách sinh viên</title>
    <style>
    /* General styles */
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
        color: white;
        padding-top: 20px;
    }

    .sidebar a:hover {
        background-color: #921e24;
    }


    .sidebar h2 {
        text-align: center;
        font-weight: bold;
        color: white;
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

    .sidebar a {
        display: block;
        padding: 10px 20px;
        color: white;
        text-decoration: none;
        font-size: 18px;
    }

    /* Main content styles */
    .main-content {
        margin-left: 250px;
        padding: 20px;
        flex-grow: 1;
    }

    h1 {
        color: #AA2E25;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #f2f2f2;
    }

    /* Button styles */
    .button-add,
    .button-delete {
        background-color: #AA2E25;
        color: white;
        border: none;
        padding: 8px 15px;
        cursor: pointer;
        border-radius: 5px;
    }

    .button-add:hover {
        background-color: #E63946;
    }

    .button-delete:hover {
        background-color: #D62828;
    }

    .h2,
    h2 {
        font-size: calc(1.325rem + .9vw)
    }

    /* Search input style */
    input[type="text"] {
        padding: 10px;
        width: 100%;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    </style>
</head>

<body>

    <!-- Sidebar Section -->
    <div class="sidebar">
        <h2><span style="color:#821131;">HUFLIT</span> <span style="color:#FFD700">TEST</span> </h2>

        <div class="menu-section">
            <h3>Quản lý</h3>
            <a href="../page/dashboard.php">Tổng quan</a>
            <a href="../page/update_user.php">Quản lý thông tin</a>
            <a href="../page/classView.php">Nhóm học phần</a>
            <a href="../page/question_view.php">Câu hỏi</a>
            <a href="../page/learning.php">Môn học</a>
            <a href="../page/tao_dethi.php">Tạo đề kiểm tra</a>
            <a href="../page/exam_list.php">Bộ đề</a>
        </div>

        <!-- <div class="menu-section">
            <h3>Quản trị</h3>
            <a href="#">Nhóm quyền</a>
        </div> -->
    </div>

    <!-- Main Content Section -->
    <div class="main-content">
        <h1>Danh sách sinh viên trong nhóm</h1>

        <!-- Thêm ô nhập và nút tìm kiếm -->
        <div>
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo ID hoặc họ tên" onkeyup="searchStudents()">
        </div>

        <table id="studentTable">
            <tr>
                <th>ID Sinh viên</th>
                <th>Họ tên</th>
                <th>Hành động</th>
            </tr>
        </table>

        <h2>Danh sách sinh viên khả dụng để thêm vào nhóm</h2>
        <table id="availableStudentsTable">
            <tr>
                <th>ID Sinh viên</th>
                <th>Họ tên</th>
                <th>Hành động</th>
            </tr>
        </table>
    </div>

    <script>
    // Lấy mã nhóm từ URL
    const urlParams = new URLSearchParams(window.location.search);
    const manhom = urlParams.get('manhom');

    // Hàm để tải danh sách sinh viên trong nhóm
    function loadStudents() {
        fetch(`get_students.php?action=get_students&manhom=${manhom}`)
            .then(response => response.json())
            .then(students => {
                const table = document.getElementById('studentTable');
                table.innerHTML = '<tr><th>ID Sinh viên</th><th>Họ tên</th><th>Hành động</th></tr>'; // Reset table

                students.forEach(student => {
                    const row = table.insertRow();
                    const cellId = row.insertCell(0);
                    const cellName = row.insertCell(1);
                    const cellAction = row.insertCell(2);

                    cellId.textContent = student.manguoidung;
                    cellName.textContent = student.hoten;

                    // Thêm nút Xóa
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Xóa';
                    deleteButton.classList.add('button-delete');
                    deleteButton.onclick = () => {
                        if (confirm('Bạn có chắc chắn muốn xóa sinh viên này?')) {
                            deleteStudent(manhom, student.manguoidung);
                        }
                    };
                    cellAction.appendChild(deleteButton);
                });
            })
            .catch(error => console.error('Có lỗi xảy ra:', error));
    }

    // Hàm để tải danh sách sinh viên khả dụng
    function loadAvailableStudents() {
        fetch(`get_students.php?action=get_available_students&manhom=${manhom}`)
            .then(response => response.json())
            .then(availableStudents => {
                const table = document.getElementById('availableStudentsTable');
                table.innerHTML = '<tr><th>ID Sinh viên</th><th>Họ tên</th><th>Hành động</th></tr>'; // Reset table

                availableStudents.forEach(student => {
                    const row = table.insertRow();
                    const cellId = row.insertCell(0);
                    const cellName = row.insertCell(1);
                    const cellAction = row.insertCell(2);

                    cellId.textContent = student.id;
                    cellName.textContent = student.hoten;

                    // Thêm nút Thêm vào nhóm
                    const addButton = document.createElement('button');
                    addButton.textContent = 'Thêm';
                    addButton.classList.add('button-add');
                    addButton.onclick = () => {
                        addStudent(manhom, student.id);
                    };
                    cellAction.appendChild(addButton);
                });
            })
            .catch(error => console.error('Có lỗi xảy ra:', error));
    }

    // Hàm tìm kiếm sinh viên theo họ tên hoặc ID
    function searchStudents() {
        const input = document.getElementById('searchInput').value.toLowerCase();
        const studentTable = document.getElementById('studentTable');
        const rows = studentTable.getElementsByTagName('tr');

        for (let i = 1; i < rows.length; i++) { // Bắt đầu từ 1 để bỏ qua tiêu đề
            const cells = rows[i].getElementsByTagName('td');
            const id = cells[0].textContent.toLowerCase();
            const name = cells[1].textContent.toLowerCase();

            // Kiểm tra nếu ID hoặc họ tên chứa từ khóa tìm kiếm
            if (id.includes(input) || name.includes(input)) {
                rows[i].style.display = ''; // Hiện hàng
            } else {
                rows[i].style.display = 'none'; // Ẩn hàng
            }
        }
    }

    // Gọi hàm để tải danh sách sinh viên và sinh viên khả dụng
    loadStudents();
    loadAvailableStudents();

    // Xóa sinh viên khỏi nhóm
    function deleteStudent(manhom, manguoidung) {
        const formData = new FormData();
        formData.append('manhom', manhom);
        formData.append('manguoidung', manguoidung);
        formData.append('action', 'delete_student');

        fetch('get_students.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    loadStudents(); // Tải lại danh sách sinh viên
                } else {
                    alert(data.message);
                }
            })
            .catch(error => {
                console.error('Có lỗi xảy ra:', error);
                alert('Có lỗi xảy ra trong quá trình xóa sinh viên.');
            });
    }

    // Thêm sinh viên vào nhóm
    function addStudent(manhom, newStudentId) {
        const formData = new FormData();
        formData.append('manhom', manhom);
        formData.append('new_student_id', newStudentId);
        formData.append('action', 'add_student');

        fetch('get_students.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    loadStudents(); // Tải lại danh sách sinh viên
                    loadAvailableStudents(); // Tải lại danh sách sinh viên khả dụng
                }
            })
            .catch(error => {
                console.error('Có lỗi xảy ra:', error);
                alert('Có lỗi xảy ra trong quá trình thêm sinh viên.');
            });
    }
    </script>

</body>

</html>