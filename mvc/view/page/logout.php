<?php
session_start();
session_unset(); // Xóa tất cả các biến session
session_destroy(); // Hủy phiên làm việc
header("Location: login.php"); // Chuyển hướng đến trang đăng nhập
exit();
