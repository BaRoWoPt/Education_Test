<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý câu hỏi</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f9;
        margin: 0;
        padding: 0;
    }

    .sidebar {
        width: 220px;
        background-color: #2c3e50;
        height: 100vh;
        padding: 20px;
        position: fixed;
        left: 0;
        top: 0;
        color: white;
    }

    .sidebar h2 {
        color: #fff;
        font-size: 20px;
        margin-bottom: 20px;
    }

    .sidebar a {
        color: #b8c6db;
        text-decoration: none;
        display: block;
        padding: 10px 0;
        margin-bottom: 10px;
        border-left: 4px solid transparent;
    }

    .sidebar a:hover {
        border-left: 4px solid #3498db;
        background-color: #34495e;
    }

    .content {
        margin-left: 260px;
        padding: 20px;
    }

    .content h1 {
        font-size: 24px;
        color: #333;
    }

    .content .filters {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .filters select,
    .filters input {
        padding: 10px;
        font-size: 16px;
        width: 200px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .filters input {
        flex: 1;
        margin-left: 10px;
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

    table {
        width: 100%;
        border-collapse: collapse;
        background-color: white;
    }

    table,
    th,
    td {
        border: 1px solid #ddd;
    }

    th,
    td {
        padding: 12px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .actions button {
        margin-right: 10px;
        padding: 5px 10px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .actions button.delete {
        background-color: #dc3545;
    }

    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination a {
        padding: 10px 15px;
        margin: 0 5px;
        text-decoration: none;
        background-color: #007bff;
        color: white;
        border-radius: 5px;
    }

    .pagination a.active {
        background-color: #0056b3;
    }

    .pagination a:hover {
        background-color: #0056b3;
    }

    footer {
        text-align: center;
        margin-top: 20px;
        font-size: 12px;
        color: #666;
    }
    </style>
</head>

<body>

    <div class="sidebar">
        <h3>Quản lý</h3>
        <a href="../page/dashboard.php">Tổng quan</a>
        <a href="../page/classView.php">Nhóm học phần</a>
        <a href="../page/question_view.php">Câu hỏi</a>
        <a href="../page/learning.php">Môn học</a>
        <a href="#">Đề kiểm tra</a>
        <a href="#">Thông báo</a>
    </div>

    <div class="content">
        <h1>Tất cả câu hỏi</h1>
        <div class="filters">
            <div>
                <select>
                    <option>Chọn môn học</option>
                    <option>Lập trình hướng đối tượng</option>
                    <option>Toán học</option>
                    <option>Vật lý</option>
                </select>
                <select>
                    <option>Chọn chương</option>

                </select>
                <select>
                    <option>Độ khó: Tất cả</option>
                    <option>Cơ bản</option>
                    <option>Trung bình</option>
                    <option>Nâng cao</option>
                </select>
            </div>
            <a href="#" class="add-button">+ Thêm câu hỏi mới</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nội dung câu hỏi</th>
                    <th>Môn học</th>
                    <th>Độ khó</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="questionTable">
                <!-- Các câu hỏi sẽ được hiển thị ở đây -->
            </tbody>
        </table>

        <div class="pagination">
            <a href="#">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">4</a>
            <a href="#">5</a>
        </div>
    </div>

    <footer>
        HUFLIT TEST © 2023
    </footer>



</body>

</html>