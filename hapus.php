<?php

include("config.php");

if (isset($_GET['id'])) {

    // ambil id dari query string
    $id = $_GET['id'];

    // buat query hapus
    $sql = "delete from users where id = '$id'";
    $query = mysqli_query($koneksi, $sql);

    // apakah query hapus berhasil?
    if ($query) {
        header('Location: user-edit.php?status=sukseshapus');
    } else {
        header('Location: user-edit.php?status=gagalhapus');
    }
} else {
    die("akses dilarang...");
}
