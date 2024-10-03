<?php
require 'vendor/autoload.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secretKey = '290504y'; // Khóa bí mật của bạn
$payload = [
    'iat' => time(), // Thời gian tạo
    'exp' => time() + 3600, // Hết hạn sau 1 giờ
    'user_id' => 1 // ID người dùng, thay đổi theo nhu cầu
];

try {
    // Mã hóa JWT
    $jwt = JWT::encode($payload, $secretKey, 'HS256');
    echo "JWT: " . $jwt . "\n";

    // Giải mã JWT
    $decoded = JWT::decode($jwt, new Key($secretKey, 'HS256'));
    print_r($decoded);
} catch (Exception $e) {
    echo "Lỗi: " . $e->getMessage();
}
