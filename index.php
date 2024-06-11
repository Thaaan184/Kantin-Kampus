<?php
include("config.php");
session_start();

// Mengecek apakah user sudah login
$is_logged_in = isset($_SESSION['username']);
$namaos = $is_logged_in ? $_SESSION['username'] : null;
$nameos = '';
$role = '';

// Jika user sudah login, ambil informasi user
if ($is_logged_in) {
    $sql = "SELECT * FROM users WHERE username='$namaos'";
    $query = mysqli_query($koneksi, $sql);
    $user_info = mysqli_fetch_assoc($query);
    $nameos = $user_info['name'];
    $role = $user_info['role'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Depan | Kantin Online</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
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
        <a href="index.php"><img src="image/logo-putih.png" class="upn"></a>
        <ul class="navigasi">
            <?php if ($role == 'admin') { ?>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
            <?php } elseif ($role == 'seller') { ?>
                <li><a class="nav-item nav-link active" href="toko.php">Toko Saya</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
            <?php } else { ?>
                <li><a class="nav-item nav-link active" href="index.php" style="color: white;">Beranda</a></li>
            <?php } ?>
            <?php if ($is_logged_in) { ?>
                <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li><a class="nav-item nav-link active" href="login.php">Login</a></li>
                <li><a class="nav-item nav-link active" href="register.php">Register</a></li>
            <?php } ?>
        </ul>
    </header>
    <div class="banner">
        <div class="album py-5 bg-light">
            <div class="container">
                <?php if ($is_logged_in) { ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Selamat datang, <?php echo $nameos; ?></h2>
                    </div>
                    <hr>
                <?php } ?>
                <?php if ($role == 'user' || $role == 'seller') { ?>
                    <div class="alert alert-info" role="alert">
                        Punya toko? <a href="kontak-admin.php">Kontak admin</a>
                    </div>
                <?php } ?>
                <!-- Makanan Berat Section -->
                <h2 class="ms-4">Makanan Berat</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    $sql = "SELECT * FROM menu WHERE kategori='makanan_berat'";
                    $query = mysqli_query($koneksi, $sql);
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id = $menu['id'];
                        $nama_produk = $menu['nama_produk'];
                        $harga_produk = $menu['harga_produk'];
                        $deskripsi_produk = $menu['deskripsi_produk'];
                        $stok = $menu['stok'];
                        $gambar = $menu['gambar'];
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
                                        <small class="text-muted">Stok: <?php echo $stok ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- Makanan Ringan Section -->
                <h2 class="ms-4">Makanan Ringan</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    $sql2 = "SELECT * FROM menu WHERE kategori='makanan_ringan'";
                    $query = mysqli_query($koneksi, $sql2);
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id = $menu['id'];
                        $nama_produk = $menu['nama_produk'];
                        $harga_produk = $menu['harga_produk'];
                        $deskripsi_produk = $menu['deskripsi_produk'];
                        $stok = $menu['stok'];
                        $gambar = $menu['gambar'];
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
                                        <small class="text-muted">Stok: <?php echo $stok ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- Minuman Section -->
                <h2 class="ms-4">Minuman</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    $sql = "SELECT * FROM menu WHERE kategori='minuman'";
                    $query = mysqli_query($koneksi, $sql);
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id = $menu['id'];
                        $nama_produk = $menu['nama_produk'];
                        $harga_produk = $menu['harga_produk'];
                        $deskripsi_produk = $menu['deskripsi_produk'];
                        $stok = $menu['stok'];
                        $gambar = $menu['gambar'];
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
                                        <a href="beli-produk.php?id=<?php echo $id ?>"><button type="button" class="btn btn-outline-primary me-2 px-4">Detail</button></a>
                                        <small class="text-muted">Stok: <?php echo $stok ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <footer>
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    </footer>
</body>

</html>