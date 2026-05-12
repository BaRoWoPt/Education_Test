
⚙️ Hướng Dẫn Cài Đặt
Yêu cầu hệ thống
PHP >= 8.0

Composer

Web server (Apache / Nginx) hoặc PHP built-in server

MySQL / MariaDB

Các bước cài đặt
Clone repository

Bash
git clone [https://github.com/BaRoWoPt/Education_Test.git](https://github.com/BaRoWoPt/Education_Test.git)
cd Education_Test
git checkout Bao_Dta
Cài đặt dependencies

Bash
composer install
Cấu hình database

Tạo database mới trên MySQL.

Import file SQL trong thư mục data/.

Cập nhật thông tin kết nối (host, dbname, username, password) trong file cấu hình tương ứng.

Cấu hình JWT Secret

Thiết lập biến môi trường hoặc file config với JWT_SECRET_KEY của bạn.

Chạy ứng dụng
Sử dụng PHP built-in server (cho môi trường phát triển):

Bash
php -S localhost:8000 -t public/
Truy cập
Mở trình duyệt và truy cập: http://localhost:8000

🔑 Xác Thực JWT
Dự án sử dụng thư viện firebase/php-jwt ^6.10 để đảm bảo an toàn hệ thống:

Khởi tạo: Token được tạo khi người dùng đăng nhập thành công.

Xác thực: Mỗi request đến API được kiểm tra thông qua Bearer Token trong Header.

Phân quyền: Vai trò (admin, teacher, student) được mã hóa trực tiếp trong payload của token.

🤝 Đóng Góp
Mọi đóng góp đều được hoan nghênh! Vui lòng thực hiện các bước sau:

Fork repository.

Tạo branch mới: git checkout -b feature/ten-tinh-nang.

Commit thay đổi: git commit -m "feat: thêm tính năng X".

Push lên branch: git push origin feature/ten-tinh-nang.

Mở Pull Request.

👨‍💻 Tác Giả
Vai trò	Tên	GitHub
Backend & Frontend	Đào Gia Bảo	@BaRoWoPt
📄 Giấy Phép
Dự án này được phát triển phục vụ mục đích học thuật — Đồ Án Phần Mềm.
