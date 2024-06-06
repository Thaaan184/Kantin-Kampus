<?php

include("config.php");

session_start();
$nama = $_SESSION['username'];
$sql2 = "SELECT *FROM users WHERE username = '$nama'";
$query2 = mysqli_query($koneksi, $sql2);
while ($user = mysqli_fetch_assoc($query2)) {
    $namaa                = $user['name'];
    $balance               = $user['balance'];
}
if (isset($_POST['beli'])) {
    $banyak = $_POST['banyak'];
    $bayar = $harga * $banyak;
    $saldo = $balance - $bayar;
    $setok = $menu['stok'];
    $stok = $setok - $banyak;

    $sql3 = "Update users set balance = '$saldo' where username = '$nama'";
    $query3 = mysqli_query($koneksi, $sql3);
    $sql4 = "Update menu set stok = '$stok' where id = $id";
    $query4 = mysqli_query($koneksi, $sql4);
    if ($query3) {
        header("Location : index.php");
    } else {
        echo ("Error description: " . mysqli_error($koneksi));
    }
} else {
    die("Akses dilarang...");
}
