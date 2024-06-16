<?php

include("config.php");

// Mulai output buffering untuk menangani masalah header
ob_start();

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["gambar"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// cek apakah tombol daftar sudah diklik atau belum?
if (isset($_POST['simpan'])) {
  $check = getimagesize($_FILES["gambar"]["tmp_name"]);
  if ($check !== false) {
    $uploadOk = 1;
  } else {
    $uploadOk = 0;
  }

  $nama_produk        = $_POST['nama_produk'];
  $kategori           = $_POST['kategori'];
  $deskripsi_produk   = $_POST['deskripsi_produk'];
  $harga_produk       = $_POST['harga_produk'];
  $stok               = $_POST['stok'];
  $gambar             = strval(htmlspecialchars(basename($_FILES["gambar"]["name"])));
  $seller_username    = $_POST['seller_username']; // Dapatkan seller_username dari form

  // buat query
  $sql = "INSERT INTO menu (nama_produk, kategori, deskripsi_produk, harga_produk, stok, gambar, seller_username) VALUES ('$nama_produk', '$kategori', '$deskripsi_produk', '$harga_produk', '$stok', '$gambar', '$seller_username')";
  $query = mysqli_query($koneksi, $sql);
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
} else {
  if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
    echo "The file " . htmlspecialchars(basename($_FILES["gambar"]["name"])) . " has been uploaded.";
  } else {
    echo "Sorry, there was an error uploading your file.";
  }
}

if ($query) {
  // kalau berhasil alihkan ke halaman index.php dengan status=sukses
  header('Location: tambah-produk.php?status=sukses');
} else {
  // kalau gagal alihkan ke halaman index.php dengan status=gagal
  header('Location: tambah-produk.php?status=gagal');
}

// Akhiri output buffering dan kirim semua output
ob_end_flush();
?>