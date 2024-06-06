<?php

include("config.php");
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["gambar"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// cek apakah tombol daftar sudah diklik atau blum?
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
  // buat query
  $sql = "insert into menu(id,nama_produk,kategori,deskripsi_produk,harga_produk,stok,gambar) values ('$id','$nama_produk','$kategori','$deskripsi_produk','$harga_produk','$stok','$gambar')";
  $query = mysqli_query($koneksi, $sql);
}
// apakah query simpan berhasil?

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
  // kalau gagal alihkan ke halaman indek.php dengan status=gagal
  header('Location: tambah-produk.php?status=gagal');
}
