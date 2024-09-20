<?php
$server = 'localhost';
$user = 'root';
$pass = '';
$database = 'WebThiTracNghiem';

$conn = new mysqli($server, $user, $pass, $database);

if ($conn) {
    mysqli_query($conn, "SET NAMES 'UTF8' ");
    echo 'Da ket noi thanh cong';
} else {
    echo 'Ket noi that bai';
}