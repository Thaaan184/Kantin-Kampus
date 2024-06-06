<?php

include("config.php");
session_start();

//ambil id dari query string
$id = $_GET['id'];

// buat query untuk ambil data dari database
$sql = "SELECT * FROM menu WHERE id = '$id'";
$query = mysqli_query($koneksi, $sql);
$menu = mysqli_fetch_assoc($query);
$harga = $menu['harga_produk'];
$kategori = $menu['kategori'];
$nama_produk = $menu['nama_produk'];

$sukses = "";
$error = "";

$nama = $_SESSION['username'];
$sql2 = "SELECT * FROM users WHERE username = '$nama'";
$query2 = mysqli_query($koneksi, $sql2);
while ($user = mysqli_fetch_assoc($query2)) {
    $namaa      = $user['name'];
    $balance    = $user['balance'];
    $role       = $user['role'];
}

if (isset($_POST['beli'])) {
    $banyak = $_POST['banyak'];
    if ($banyak == "") {
        $banyak = 0;
    }
    $setok = $menu['stok'];
    if ($setok == 0) {
        $error = "Maaf, Stok " . $menu['nama_produk'] . " habis, silakan memilih menu lain";
    } else {
        if ($setok < $banyak) {
            $error = "Maaf, Hanya bisa beli " . $setok . " " . $menu['nama_produk'];
        } else {
            $bayar = $harga * $banyak;
            $saldo = $balance - $bayar;
            $stok = $setok - $banyak;
            if ($balance < $bayar) {
                $p = $harga;
                $b = 0;
                while ($p < $balance) {
                    $p = $p + $harga;
                    $b = $b + 1;
                }
                $error = "Saldo Anda tidak cukup, Hanya bisa beli " . $b . " " . $menu['nama_produk'];
            } else {
                $sql3 = "UPDATE users SET balance = '$saldo' WHERE username = '$nama'";
                $query1 = mysqli_query($koneksi, $sql3);
                $sql4 = "UPDATE menu SET stok = '$stok' WHERE id = $id";
                $query4 = mysqli_query($koneksi, $sql4);

                if ($query1) {
                    $sukses = "Berhasil membeli " . $banyak . " " . $menu['nama_produk'];
                } else {
                    $error = "Transaksi gagal";
                }
            }
        }
    }
}

if (isset($_POST['ulas'])) {
    $ulasan = $_POST['ulasan'];
    $sql = "INSERT INTO review (nama, menu, ulasan) VALUES ('$namaa', '$nama_produk', '$ulasan')";
    $query = mysqli_query($koneksi, $sql);
    if ($query) {
        $sukses = "Terima Kasih sudah makan di kantin kami :)";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <title>Halaman Transaksi</title>
</head>

<body>
    <header>
        <a href="index.php" class="logo">Kantin Online</a>
        <ul class="navigasi">
            <?php
            if ($role == 'admin') {
            ?>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
            <?php
            } else { ?>
                <li><a class="nav-item nav-link active" href="index.php" style="color: white;">Beranda</a></li>
            <?php
            }
            ?>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="banner">
        <div class="mx-auto" style="width: 1000px;">
            <div class="card">
                <h4 class="card-header">Halaman Transaksi</h4>
                <div class="card-body">
                    <section class="container-fluid my-4">
                        <section class="justify-content-center">
                            <?php
                            if ($error) {
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error ?>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                            if ($sukses) {
                            ?>
                                <div class="alert alert-success" role="alert">
                                    <?php echo $sukses ?>
                                </div>
                            <?php
                            }
                            ?>
                            <form action="" method="POST">
                                <img src="uploads/<?php echo $menu['gambar'] ?>" class="col-md-5 me-3 rounded float-sm-start img-thumbnail" style="width:400px" alt="...">
                                <h1><?php echo $menu['nama_produk'] ?></h1>
                                <p><?php echo $menu['deskripsi_produk'] ?></p>
                                <h4 style="color:darkgoldenrod">Harga: Rp.<?php echo number_format($menu['harga_produk'], 0, ',', '.'); ?></h4>
                                <h4>Stok: <?php echo $menu['stok'] ?></h4>
                                <br>
                                <div class="col-sm-3 mt-5">
                                    <input type="number" class="form-control" id="banyak" name="banyak" placeholder="Masukkan jumlah..">
                                </div>
                                <button type="submit" class="btn btn-primary px-4 my-2" id="beli" name="beli">Beli</button>
                                <a href="index.php"><button class="btn btn-outline-primary me-2 my-3" type="button">Kembali</button></a>
                            </form>

                            <?php
                            if ($sukses) {
                            ?>
                                <div class="mb-3 row">
                                    <form action="" method="POST">
                                        <label>Berikan Ulasan Anda</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" id="ulasan" name="ulasan" placeholder="Tambahkan ulasan.."></textarea>
                                        </div>
                                        <button class="btn btn-success px-3 my-3" id="ulas" name="ulas">Submit</button>
                                        <a href="beli-produk.php"><button class="btn btn-secondary px-3 my-3" type="button">Batal</button></a>
                                    </form>
                                </div>
                            <?php
                            }
                            ?>
                        </section>
                    </section>
                </div>
            </div>
        </div>

        <div class="mx-auto" style="width: 1000px;">
            <div class="card mt-3">
                <h4 class="card-header">Ulasan Konsumen</h4>
                <div class="card-body">
                    <?php
                    $sql = "SELECT * FROM review WHERE menu = '$nama_produk'";
                    $query = mysqli_query($koneksi, $sql);
                    while ($ulasan = mysqli_fetch_assoc($query)) {
                        $nama_pelanggan = $ulasan['nama'];
                        $ulas = $ulasan['ulasan'];
                    ?>
                        <h5><?php echo $nama_pelanggan ?></h5>
                        <p><?php echo $ulas ?></p>
                        <hr>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
