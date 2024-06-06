<?php

include("config.php");

// cek apakah tombol simpan sudah diklik atau blum?
if (isset($_POST['simpan'])) {

    // ambil data dari formulir
    $id = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['name'];
    $email = $_POST['email'];
    $balance = $_POST['balance'];
    $role = $_POST['role'];
    // buat query update
    $sql = "Update users set username = '$username', name = '$nama', email = '$email', balance = '$balance', role = '$role' where id = '$id'";

    $query = mysqli_query($koneksi, $sql);

    // apakah query update berhasil?
    if ($query) {
        header('Location: user-edit.php?status=suksesedit');
    } else {
        header('Location: user-edit.php?status=gagaledit');
        echo ("Error description: " . mysqli_error($koneksi));
    }
} else {
    die("Akses dilarang...");
}
