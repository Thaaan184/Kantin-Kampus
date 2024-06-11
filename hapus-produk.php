<?php
include("config.php");
session_start();

if (isset($_GET['id']) && isset($_SESSION['username'])) {
    $id = $_GET['id'];
    $username = $_SESSION['username'];

    // Cek role user yang sedang login
    $sql_role = "SELECT role FROM users WHERE username='$username'";
    $query_role = mysqli_query($koneksi, $sql_role);
    $user_info = mysqli_fetch_assoc($query_role);
    $role = $user_info['role'];

    // Jika user adalah admin, maka langsung hapus produk
    if ($role == 'admin') {
        $sql = "DELETE FROM menu WHERE id='$id'";
        $query = mysqli_query($koneksi, $sql);

        if ($query) {
            header('Location: output-menu.php?status=sukses');
        } else {
            header('Location: output-menu.php?status=gagal');
        }
    } else {
        // Cek apakah produk milik user yang sedang login jika bukan admin
        $sql_check = "SELECT * FROM menu WHERE id='$id' AND seller_username='$username'";
        $query_check = mysqli_query($koneksi, $sql_check);
        $product = mysqli_fetch_assoc($query_check);

        if ($product) {
            // Jika produk milik user yang sedang login, hapus produk
            $sql = "DELETE FROM menu WHERE id='$id'";
            $query = mysqli_query($koneksi, $sql);

            if ($query) {
                header('Location: output-menu.php?status=sukses');
            } else {
                header('Location: output-menu.php?status=gagal');
            }
        } else {
            // Jika produk bukan milik user yang sedang login, tampilkan pesan kesalahan
            header('Location: output-menu.php?status=gagal');
        }
    }
} else {
    header('Location: output-menu.php?status=gagal');
}
