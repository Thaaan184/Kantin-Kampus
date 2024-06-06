<?php

include("config.php");
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'notadmin') {
        echo '<script language="javascript">alert("Hubungi Admin untuk melakukan top up saldo!"); document.location="index.php";</script>';
    }
}
?>
<?php
//inisialisasi session
session_start();

//mengecek username pada session
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
}
$namaos = $_SESSION['username'];
$sql = "SELECT * FROM users where username='$namaos'";
$query = mysqli_query($koneksi, $sql);
$no = 1;
while ($ingfos = mysqli_fetch_assoc($query)) {
    $nameos                = $ingfos['name'];
    $balance               = $ingfos['balance'];
    $role                   = $ingfos['role'];
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman depan | Beauty Basics</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/album/">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>

<body>
    <header>
        <a href="index.php" class="logo">Beauty Basics</a>
        <ul class="navigasi">
            <?php
            if ($role == 'admin') {
            ?>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php">Edit user</a></li>
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
        <div class="album py-5 bg-light">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <h2>Selamat datang, <?php echo $nameos; ?></h2>
                    <div>
                        <span>
                            Saldo : <?php echo 'Rp. ' . number_format((int)$balance, 2, ",", "."); ?>
                            <a href="user-edit.php" class="btn btn-outline-success mx-2 pt-0 pb-1 px-3"> + </a>
                        </span>

                    </div>

                </div>
                <hr>
                <h2 class="ms-4">Skincare</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    // jalankan query untuk menampilkan semua data diurutkan berdasarkan nim
                    $sql = "SELECT * FROM menu WHERE kategori='skincare'";
                    $query = mysqli_query($koneksi, $sql);
                    //mengecek apakah ada error ketika menjalankan query
                    $no = 1;
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id                 = $menu['id'];
                        $nama_produk        = $menu['nama_produk'];
                        $kategori           = $menu['kategori'];
                        $deskripsi_produk   = $menu['deskripsi_produk'];
                        $harga_produk       = $menu['harga_produk'];
                        $stok               = $menu['stok'];
                        $gambar             = $menu['gambar'];

                        //buat perulangan untuk element tabel dari data mahasiswa
                        //variabel untuk membuat nomor urut
                        // hasil query akan disimpan dalam variabel $data dalam bentuk array
                        // kemudian dicetak dengan perulangan while
                    ?>

                        <div class="col ms-4 my-3" style="width: 300px;">
                            <div class="card shadow-sm">
                                <img src="uploads/<?php echo $gambar ?>" height="200px">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4><?php echo $nama_produk ?></h4>
                                        <h4>Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></h4>
                                    </div>
                                    <p class="card-text"><?php echo substr($deskripsi_produk, 0, 20); ?>... </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="beli-produk.php?id=<?php echo $id ?>"><button type="button" class="btn btn-outline-success me-2 px-4">Detail</button></a>
                                        <small class="text-muted">Stok : <?php echo $stok ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $no++; //untuk nomor urut terus bertambah 1
                    }
                    ?>
                </div>
                <h2 class="ms-4">Alat Make Up</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    $sql2 = "SELECT * FROM menu WHERE kategori='makeup'";
                    $query = mysqli_query($koneksi, $sql2);
                    $no = 1;
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id                 = $menu['id'];
                        $nama_produk        = $menu['nama_produk'];
                        $kategori           = $menu['kategori'];
                        $deskripsi_produk   = $menu['deskripsi_produk'];
                        $harga_produk       = $menu['harga_produk'];
                        $stok               = $menu['stok'];
                        $gambar             = $menu['gambar'];
                    ?>

                        <div class="col ms-4 my-3" style="width: 300px;">
                            <div class="card shadow-sm">
                                <img src="uploads/<?php echo $gambar ?>" height="200px">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4><?php echo $nama_produk ?></h4>
                                        <h4>Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></h4>
                                    </div>
                                    <p class="card-text"><?php echo substr($deskripsi_produk, 0, 20); ?>... </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="beli-produk.php?id=<?php echo $id ?>"><button type="button" class="btn btn-outline-danger me-2 px-4">Detail</button></a>
                                        <small class="text-muted">Stok : <?php echo $stok ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $no++;
                        //untuk nomor urut terus bertambah 1
                    }
                    ?>
                </div>
                <h2 class="ms-4">Lainnya</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    // jalankan query untuk menampilkan semua data diurutkan berdasarkan nim
                    $sql = "SELECT * FROM menu WHERE kategori='lainnya'";
                    $query = mysqli_query($koneksi, $sql);
                    //mengecek apakah ada error ketika menjalankan query
                    $no = 1;
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id                 = $menu['id'];
                        $nama_produk        = $menu['nama_produk'];
                        $kategori           = $menu['kategori'];
                        $deskripsi_produk   = $menu['deskripsi_produk'];
                        $harga_produk       = $menu['harga_produk'];
                        $stok               = $menu['stok'];
                        $gambar             = $menu['gambar'];

                        //buat perulangan untuk element tabel dari data mahasiswa
                        //variabel untuk membuat nomor urut
                        // hasil query akan disimpan dalam variabel $data dalam bentuk array
                        // kemudian dicetak dengan perulangan while
                    ?>

                        <div class="col ms-4 my-3" style="width: 300px;">
                            <div class="card shadow-sm">
                                <img src="uploads/<?php echo $gambar ?>" height="200px">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4><?php echo $nama_produk ?></h4>
                                        <h4>Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></h4>
                                    </div>
                                    <p class="card-text"><?php echo substr($deskripsi_produk, 0, 20); ?>... </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="beli-produk.php?id=<?php echo $id ?>"><button type="button" class="btn btn-outline-success me-2 px-4">Detail</button></a>
                                        <small class="text-muted">Stok : <?php echo $stok ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $no++; //untuk nomor urut terus bertambah 1
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>

</html>