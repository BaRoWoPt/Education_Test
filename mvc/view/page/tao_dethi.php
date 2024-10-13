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
$sql = "SELECT manhom, tennhom FROM nhom WHERE hienthi = 1";
$result = $conn->query($sql);
if (isset($_GET['success']) && $_GET['success'] == 1) {
    echo "<script>alert('Đề thi đã được tạo thành công!');</script>";
}
$userId = $_SESSION['user_id'];
// Lấy ID người dùng từ session
?>

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

        .text_row {
            font-size: 14px;
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
                    <a href="../page/tao_dethi.php">Tạo đề kiểm tra</a>
                    <a href="../page/exam_list.php">Bộ đề</a>
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
                                <form method="POST" action="create_exam.php">
                                    <!-- Thay đổi action tương ứng -->
                                    <div class="mb-3">
                                        <label for="tende" class="form-label">Tên đề kiểm tra</label>
                                        <input type="text" class="form-control" id="tende" name="tende"
                                            placeholder="Nhập tên đề kiểm tra" required>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianbatdau" class="form-label">Thời gian bắt đầu</label>
                                            <input type="datetime-local" class="form-control" id="thoigianbatdau"
                                                name="thoigianbatdau" required>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="thoigianlambai" class="form-label">Thời gian làm bài</label>
                                            <input type="number" class="form-control" id="thoigianlambai"
                                                name="thoigianlambai" placeholder="00" min="0" required>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label for="giaochonhom" class="form-label">Giao cho</label>
                                        <select class="form-select" id="giaochonhom" name="giaochonhom" required>
                                            <option selected disabled>Chọn nhóm học phần giảng dạy...</option>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='{$row['manhom']}'>{$row['tennhom']}</option>";
                                                }
                                            } else {
                                                echo "<option disabled>Không có nhóm nào</option>";
                                            }
                                            ?>
                                        </select>

                                        <div class="mb-3">
                                            <label for="chuong" class="form-label">Chương</label>
                                            <div id="chuongContainer">
                                                <!-- Các chương sẽ được thêm vào đây dưới dạng checkbox -->
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="text_row" id="totalEasyLabel">Số câu dễ có thể chọn:
                                                    0</label>
                                                <input type="number" class="form-control" name="socau_de"
                                                    placeholder="Số câu dễ" min='0' max='0' required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="text_row" id="totalMediumLabel">Số câu trung bình có thể
                                                    chọn:
                                                    0</label>
                                                <input type="number" class="form-control" name="socau_tb"
                                                    placeholder="Số câu trung bình" min='0' max='0' required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="text_row" id="totalHardLabel">Số câu khó có thể chọn:
                                                    0</label>
                                                <input type="number" class="form-control" name="socau_kho"
                                                    placeholder="Số câu khó" min='0' max='0' required>
                                            </div>
                                        </div>
                                        <input type="hidden" id="mamonhoc" name="mamonhoc" value="">

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
                                    <input class="form-check-input" type="checkbox" id="autobank" name="autobank">
                                    <label class="form-check-label" for="autobank">Tự động lấy từ ngân hàng đề</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="xemdiem" name="xemdiem">
                                    <label class="form-check-label" for="xemdiem">Xem điểm sau khi thi xong</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="xembailam" name="xembailam">
                                    <label class="form-check-label" for="xembailam">Xem bài làm khi thi xong</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="daocauhoi" name="daocauhoi">
                                    <label class="form-check-label" for="daocauhoi">Đảo câu hỏi</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="daodapan" name="daodapan">
                                    <label class="form-check-label" for="daodapan">Đảo đáp án</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
        document.getElementById('giaochonhom').addEventListener('change', function() {
            var manhom = this.value; // Lấy giá trị manhom đã chọn

            // Gửi yêu cầu AJAX để lấy mamonhoc và tenmonhoc
            fetch('get_monhoc.php?manhom=' + manhom)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Kiểm tra lỗi từ server
                    if (data.error) {
                        console.error('Error:', data.error);
                        alert(data.error); // Thông báo lỗi cho người dùng
                        return; // Kết thúc nếu có lỗi
                    }

                    // Kiểm tra và lấy mamonhoc
                    var mamonhoc = data.mamonhoc;
                    if (mamonhoc !== undefined && mamonhoc !== null) {
                        console.log('Mã môn học:', mamonhoc);

                        // Cập nhật giá trị cho input hidden
                        document.getElementById('mamonhoc').value = mamonhoc;

                        // Gửi yêu cầu AJAX để lấy danh sách chương
                        fetch('get_chapters.php?manhom=' + manhom)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                var chuongContainer = document.getElementById('chuongContainer');
                                chuongContainer.innerHTML = ''; // Xóa các checkbox hiện tại

                                // Kiểm tra dữ liệu chương
                                if (Array.isArray(data) && data.length > 0) {
                                    // Thêm các chương vào danh sách checkbox
                                    data.forEach(function(machuong) {
                                        var checkbox = document.createElement('div');
                                        checkbox.className = 'form-check';

                                        var input = document.createElement('input');
                                        input.type = 'checkbox';
                                        input.className = 'form-check-input';
                                        input.value = machuong;
                                        input.id = 'chuong_' + machuong;

                                        var label = document.createElement('label');
                                        label.className = 'form-check-label';
                                        label.htmlFor = 'chuong_' + machuong;
                                        label.textContent = 'Chương ' + machuong;

                                        checkbox.appendChild(input);
                                        checkbox.appendChild(label);
                                        chuongContainer.appendChild(checkbox);
                                    });
                                } else {
                                    console.warn('Không có chương nào để hiển thị.');
                                    chuongContainer.innerHTML = '<p>Không có chương nào để hiển thị.</p>';
                                }
                            })
                            .catch(error => {
                                console.error('Error fetching chapters:', error);
                                alert(
                                    'Có lỗi xảy ra khi lấy danh sách chương.'
                                ); // Thông báo lỗi cho người dùng
                            });
                    } else {
                        console.error('Mã môn học không hợp lệ');
                        alert('Không tìm thấy mã môn học.'); // Thông báo cho người dùng
                    }
                })
                .catch(error => {
                    console.error('Error fetching môn học:', error);
                    alert('Có lỗi xảy ra khi lấy mã môn học.'); // Thông báo lỗi cho người dùng
                });
        });

        document.getElementById('chuongContainer').addEventListener('change', function(e) {
            // Tạo biến để lưu trữ tổng số câu hỏi
            let totalEasy = 0;
            let totalMedium = 0;
            let totalHard = 0;

            // Lấy tất cả các checkbox đã chọn
            const checkboxes = document.querySelectorAll('#chuongContainer input[type="checkbox"]:checked');

            // Nếu không có checkbox nào được chọn, thoát
            if (checkboxes.length === 0) {
                document.querySelector('input[name="socau_de"]').value = 0;
                document.querySelector('input[name="socau_tb"]').value = 0;
                document.querySelector('input[name="socau_kho"]').value = 0;
                return;
            }

            // Gửi yêu cầu AJAX cho từng chương đã chọn
            const promises = Array.from(checkboxes).map(checkbox => {
                var machuong = checkbox.value;

                return fetch('get_question_count.php?machuong=' + machuong)
                    .then(response => response.json())
                    .then(data => {
                        // Cộng dồn số lượng câu hỏi theo độ khó
                        totalEasy += data.so_cau_de;
                        totalMedium += data.so_cau_tb;
                        totalHard += data.so_cau_kho;
                    });
            });

            // Sau khi tất cả các yêu cầu hoàn thành, cập nhật giao diện
            Promise.all(promises).then(() => {
                // Cập nhật tổng số câu có thể chọn lên các label
                document.getElementById('totalEasyLabel').innerText = 'Số câu dễ có thể chọn: ' + totalEasy;
                document.getElementById('totalMediumLabel').innerText = 'Số câu trung bình có thể chọn: ' +
                    totalMedium;
                document.getElementById('totalHardLabel').innerText = 'Số câu khó có thể chọn: ' +
                    totalHard;

                // Cập nhật giá trị cho các trường nhập liệu
                document.querySelector('input[name="socau_de"]').max = totalEasy;
                document.querySelector('input[name="socau_tb"]').max = totalMedium;
                document.querySelector('input[name="socau_kho"]').max = totalHard;
            }).catch(error => console.error('Error:', error));
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>