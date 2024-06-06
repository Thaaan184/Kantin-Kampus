<?php

include("config.php");

// cek apakah tombol simpan sudah diklik atau blum?
if (isset($_POST['simpan'])) {

    // ambil data dari formulir
    $id                 = $_POST['id'];
    $nama_produk        = $_POST['nama_produk'];
    $kategori           = $_POST['kategori'];
    $deskripsi_produk   = $_POST['deskripsi_produk'];
    $harga_produk       = $_POST['harga_produk'];
    $stok               = $_POST['stok'];
    $gambar             = $_FILES['gambar']['name'];

    if ($gambar != "") {
        $ekstensi_diperbolehkan = array('png', 'jpg', 'jpeg'); //ekstensi file gambar yang bisa diupload 
        $x = explode('.', $gambar); //memisahkan nama file dengan ekstensi yang diupload
        $ekstensi = strtolower(end($x));
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $angka_acak     = rand(1, 999);
        $nama_gambar_baru = $angka_acak . '-' . $gambar; //menggabungkan angka acak dengan nama file sebenarnya
        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            move_uploaded_file($file_tmp, 'uploads/' . $nama_gambar_baru); //memindah file gambar ke folder gambar

            // jalankan query UPDATE berdasarkan ID yang produknya kita edit
            $query  = "UPDATE menu SET nama_produk = '$nama_produk', deskripsi = '$deskripsi', kategori = '$kategori', harga_produk = '$harga_produk', gambar = '$nama_gambar_baru', 'stok = '$stok";
            $query .= "WHERE id = '$id'";
            $result = mysqli_query($koneksi, $query);
            // periska query apakah ada error
            if (!$result) {
                header('Location: output-menu.php?status=gagaledit');
            } else {
                //tampil alert dan akan redirect ke halaman index.php
                //silahkan ganti index.php sesuai halaman yang akan dituju
                header('Location: output-menu.php?status=suksesedit');
            }
        } else {
            //jika file ekstensi tidak jpg dan png maka alert ini yang tampil
            echo "<script>alert('Ekstensi gambar yang boleh hanya jpg atau png.');window.location='tambah-menu.php';</script>";
        }
    } else {
        // jalankan query UPDATE berdasarkan ID yang produknya kita edit
        $query  = "UPDATE menu SET nama_produk = '$nama_produk', deskripsi_produk = '$deskripsi_produk', kategori = '$kategori', harga_produk = '$harga_produk', stok = '$stok' WHERE id = '$id'";
        $result = mysqli_query($koneksi, $query);
        // periska query apakah ada error
        if (!$result) {
            header('Location: output-menu.php?status=gagaledit');
        } else {
            //tampil alert dan akan redirect ke halaman index.php
            //silahkan ganti index.php sesuai halaman yang akan dituju
            header('Location: output-menu.php?status=suksesedit');
        }
    }
} else {
    die("Akses dilarang...");
}
