<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo đề kiểm tra</title>
    <!-- Thêm Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="icon" href="/mvc/view/img/68e129217733aa0645b48e7c154d2303-_1_.svg" type="image/x-icon">

    <style>
    body {
        background-color: #f8f9fa;
    }

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

    .config-container {
        background-color: white;
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
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

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                    <h1 class="type_h2">Tạo mới đề thi</h1>
                </div>

                <!-- Form và Cấu hình -->
                <div class="row">
                    <!-- Form Tạo đề thi -->
                    <div class="col-lg-8 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Thông tin đề thi</h5>
                                <form>
                                    <div class="mb-3">
                                        <label for="tende" class="form-label">Tên đề kiểm tra</label>
                                        <input type="text" class="form-control" id="tende"
                                            placeholder="Nhập tên đề kiểm tra">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianbatdau" class="form-label">Thời gian bắt đầu</label>
                                            <input type="datetime-local" class="form-control" id="thoigianbatdau">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianlambai" class="form-label">Thời gian làm bài</label>
                                            <input type="number" class="form-control" id="thoigianlambai"
                                                placeholder="00" min="0">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="giaochonhom" class="form-label">Giao cho</label>
                                        <select class="form-select" id="giaochonhom">
                                            <option selected>Chọn nhóm học phần giảng dạy...</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="chuong" class="form-label">Chương</label>
                                        <select class="form-select" id="chuong" multiple>
                                            <option selected>Chọn nhiều chương...</option>
                                        </select>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" placeholder="Số câu dễ">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" placeholder="Số câu trung bình">
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <input type="number" class="form-control" placeholder="Số câu khó">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">+ TẠO ĐỀ</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Cấu hình -->
                    <div class="col-lg-4">
                        <div class="config-container">
                            <h5 class="card-title">Cấu hình</h5>
                            <form>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="autobank">
                                    <label class="form-check-label" for="autobank">Tự động lấy từ ngân hàng đề</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="xemdiem">
                                    <label class="form-check-label" for="xemdiem">Xem điểm sau khi thi xong</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="xembailam">
                                    <label class="form-check-label" for="xembailam">Xem bài làm khi thi xong</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="daocauhoi">
                                    <label class="form-check-label" for="daocauhoi">Đảo câu hỏi</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="daodapan">
                                    <label class="form-check-label" for="daodapan">Đảo đáp án</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="nopbaitab">
                                    <label class="form-check-label" for="nopbaitab">Tự động nộp bài khi chuyển
                                        tab</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Thêm Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>