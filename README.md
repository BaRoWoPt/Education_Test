🎯 Giới Thiệu Dự Án
Education Test là một ứng dụng web hỗ trợ thi trắc nghiệm trực tuyến, được xây dựng như một Đồ Án Phần Mềm. Dự án cung cấp nền tảng để giáo viên tạo đề thi và học sinh thực hiện các bài kiểm tra trực tuyến một cách tiện lợi, có tổ chức.
Hệ thống được thiết kế theo kiến trúc MVC (Model - View - Controller) thuần PHP, kết hợp với JavaScript phía client để mang lại trải nghiệm người dùng mượt mà và hiện đại. Xác thực người dùng được bảo mật bằng JSON Web Token (JWT).

✨ Tính Năng Chính

🔐 Xác thực & Phân quyền — Đăng nhập bảo mật bằng JWT, phân quyền giữa giáo viên và học sinh
📝 Quản lý đề thi — Tạo, chỉnh sửa và quản lý ngân hàng câu hỏi trắc nghiệm
🖥️ Giao diện thi trực tuyến — Học sinh làm bài thi trực tiếp trên trình duyệt
📊 Kết quả & Thống kê — Chấm điểm tự động và hiển thị kết quả ngay sau khi nộp bài
🗂️ Quản lý dữ liệu — Lưu trữ và truy xuất dữ liệu thi có hệ thống


🛠️ Tech Stack
LayerCông nghệMô tảBackendPHP 8.xXử lý logic server, routing, APIFrontendJavaScript (ES6+)Tương tác giao diện, xử lý form, gọi APIMarkupHTML5Cấu trúc trang webStylingCSS3Thiết kế giao diện người dùngAuthenticationfirebase/php-jwt ^6.10Tạo và xác thực JSON Web TokenArchitectureMVC PatternTách biệt Model, View, ControllerDependency ManagerComposerQuản lý thư viện PHP

📁 Cấu Trúc Dự Án
Education_Test/
├── mvc/                    # Kiến trúc MVC chính
│   ├── models/             # Xử lý dữ liệu & tương tác DB
│   ├── views/              # Giao diện hiển thị (HTML/PHP)
│   └── controllers/        # Xử lý logic nghiệp vụ
│
├── data/                   # Dữ liệu & cấu hình database
│
├── public/                 # Tài nguyên công khai
│   ├── css/                # File CSS
│   ├── js/                 # File JavaScript
│   └── index.php           # Entry point của ứng dụng
│
├── test/                   # Unit test & kiểm thử
│
├── vendor/                 # Thư viện bên thứ ba (Composer)
│
├── composer.json           # Khai báo dependencies
├── composer.lock           # Khóa phiên bản dependencies
└── README.md

⚙️ Hướng Dẫn Cài Đặt
Yêu cầu hệ thống

PHP >= 8.0
Composer
Web server (Apache / Nginx) hoặc PHP built-in server
MySQL / MariaDB (hoặc database tương thích)

Các bước cài đặt
1. Clone repository
bashgit clone https://github.com/BaRoWoPt/Education_Test.git
cd Education_Test
git checkout Bao_Dta
2. Cài đặt dependencies
bashcomposer install
3. Cấu hình database
Tạo database và import file SQL trong thư mục data/. Sau đó cập nhật thông tin kết nối trong file cấu hình tương ứng.
4. Cấu hình JWT Secret
Thiết lập biến môi trường hoặc file config với JWT secret key của bạn.
5. Chạy ứng dụng
Sử dụng PHP built-in server (cho môi trường dev):
bashphp -S localhost:8000 -t public/
Hoặc trỏ web server (Apache/Nginx) vào thư mục public/.
6. Truy cập ứng dụng
http://localhost:8000

🔑 Xác Thực JWT
Dự án sử dụng thư viện firebase/php-jwt phiên bản ^6.10 để xử lý xác thực:

Token được tạo khi người dùng đăng nhập thành công
Mỗi request đến API được xác thực thông qua Bearer Token
Phân quyền dựa trên payload trong token (role: admin / teacher / student)


🤝 Đóng Góp
Mọi đóng góp đều được hoan nghênh! Vui lòng:

Fork repository này
Tạo branch mới: git checkout -b feature/ten-tinh-nang
Commit thay đổi: git commit -m "feat: thêm tính năng X"
Push lên branch: git push origin feature/ten-tinh-nang
Mở Pull Request


👨‍💻 Tác Giả
Vai tròTênGitHubBackend & FrontendDao Gia Bao (Gbao)@BaRoWoPt

📄 Giấy Phép
Dự án này được phát triển phục vụ mục đích học thuật — Đồ Án Phần Mềm.

<p align="center">Made with ❤️ by <strong>Gbao</strong></p>
