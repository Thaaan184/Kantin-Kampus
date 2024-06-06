<?php

include("config.php");

if (isset($_GET['id'])) {

    // ambil id dari query string
    $id = $_GET['id'];

    // buat query hapus
    $sql = "delete from menu where id = '$id'";
    $query = mysqli_query($koneksi, $sql);

    // apakah query hapus berhasil?
    if ($query) {
        header('Location: output-menu.php?status=sukseshapus');
    } else {
        header('Location: output-menu.php?status=gagalhapus');
    }
} else {
    die("akses dilarang...");
}
