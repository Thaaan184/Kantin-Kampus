<?php
include("config.php");

session_start();
$is_logged_in = isset($_SESSION['username']);
$role = '';

if ($is_logged_in) {
    $username = $_SESSION['username'];
    $sql = "SELECT role FROM users WHERE username='$username'";
    $query = mysqli_query($koneksi, $sql);
    $user_info = mysqli_fetch_assoc($query);
    $role = $user_info['role'];
}

// Jika user bukan admin atau seller, redirect ke halaman index
if ($role !== 'admin' && $role !== 'seller') {
    header("Location: index.php");
    exit();
}

// Proses penghapusan produk
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Verifikasi bahwa produk tersebut milik seller yang sedang login (jika role seller)
    if ($role == 'seller') {
        $sql = "DELETE FROM menu WHERE id='$id' AND seller_username='$username'";
    } else {
        $sql = "DELETE FROM menu WHERE id='$id'";
    }

    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        header("Location: output-menu.php?status=sukses");
    } else {
        header("Location: output-menu.php?status=gagal");
    }
    exit();
}
?>