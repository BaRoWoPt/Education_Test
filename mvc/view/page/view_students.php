<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    button {
        background-color: #ff4d4d;
        color: white;
        border: none;
        padding: 5px 10px;
        cursor: pointer;
    }

    button:hover {
        background-color: #e60000;
    }

    input[type="text"] {
        padding: 5px;
        margin-bottom: 10px;
    }
    </style>
</head>

<body>
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