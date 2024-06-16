<?php

include("config.php");

if (isset($_GET['id'])) {
    // ambil id dari query string
    $id = $_GET['id'];

    // Cek role dari user yang ingin dihapus
    $sql_check_role = "SELECT role FROM users WHERE id = '$id'";
    $query_check_role = mysqli_query($koneksi, $sql_check_role);
    $user_data = mysqli_fetch_assoc($query_check_role);

    if ($user_data['role'] == 'admin') {
        // Menggunakan JavaScript untuk menampilkan pesan dan mengalihkan halaman
        echo "<script>
            alert('Cannot delete admin user');
            window.location.href = 'user-edit.php';
        </script>";
        exit();
    }

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // Hapus dari transactions
        $sql_transactions = "DELETE FROM transactions WHERE user_id = '$id'";
        mysqli_query($koneksi, $sql_transactions);

        // Hapus dari reports
        $sql_reports = "DELETE FROM reports WHERE user_id = '$id'";
        mysqli_query($koneksi, $sql_reports);

        // Hapus dari menu berdasarkan seller_username yang terkait dengan user_id
        $sql_user = "SELECT username FROM users WHERE id = '$id'";
        $query_user = mysqli_query($koneksi, $sql_user);
        $user = mysqli_fetch_assoc($query_user);
        $username = $user['username'];

        $sql_menu = "DELETE FROM menu WHERE seller_username = '$username'";
        mysqli_query($koneksi, $sql_menu);

        // Hapus dari users
        $sql_users = "DELETE FROM users WHERE id = '$id'";
        mysqli_query($koneksi, $sql_users);

        // Commit transaksi
        mysqli_commit($koneksi);

        // Menggunakan JavaScript untuk menampilkan pesan dan mengalihkan halaman
        echo "<script>
            alert('User berhasil dihapus');
            window.location.href = 'user-edit.php';
        </script>";
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($koneksi);
        // Menggunakan JavaScript untuk menampilkan pesan dan mengalihkan halaman
        echo "<script>
            alert('Gagal menghapus user: " . $e->getMessage() . "');
            window.location.href = 'user-edit.php';
        </script>";
    }
} else {
    die("akses dilarang...");
}
?>