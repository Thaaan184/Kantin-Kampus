<?php
include("config.php");
session_start();
$namaos = strval($_SESSION['username']);


if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
}
$sql = "SELECT * FROM users where username='$namaos'";
$query = mysqli_query($koneksi, $sql);
//mengecek apakah ada error ketika menjalankan query
$no = 1;
while ($ingfos = mysqli_fetch_assoc($query)) {
    $role               = $ingfos['role'];
}
if ($role == "admin") {
    echo "";
} else {
    header('Location: index.php?status=notadmin');
}
