<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <h1>Chào mừng bạn đến với Dashboard</h1>
    <p>ID Người Dùng: <?php echo $_SESSION['user_id']; ?></p>
    <a href="logout.php">Đăng xuất</a>
</body>

</html>