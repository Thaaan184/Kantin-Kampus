<?php
include("config.php");
session_start();

// Mengecek apakah user sudah login
$is_logged_in = isset($_SESSION['username']);
$namaos = $is_logged_in ? $_SESSION['username'] : null;
$nameos = '';
$balance = 0;
$role = '';

// Jika user sudah login, ambil informasi user
if ($is_logged_in) {
    $sql = "SELECT * FROM users WHERE username='$namaos'";
    $query = mysqli_query($koneksi, $sql);
    $user_info = mysqli_fetch_assoc($query);
    $nameos = $user_info['name'];
    $balance = $user_info['balance'];
    $role = $user_info['role'];
}

// Jika user bukan seller, redirect ke halaman index
if ($role !== 'seller') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Saya | Kantin Online</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="index.php"><img src="image/logo-putih.png" class="upn"></a>
        <ul class="navigasi">
            <li><a class="nav-item nav-link active" href="toko.php" style="color: white;">Toko Saya</a></li>
            <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="banner">
        <div class="album py-5 bg-light">
            <div class="container">
                <?php if ($is_logged_in) { ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Toko Saya - Selamat datang, <?php echo $nameos; ?></h2>
                        <div>
                            <span>
                                Saldo: <?php echo 'Rp. ' . number_format((int)$balance, 2, ",", "."); ?>
                                <a href="user-edit.php" class="btn btn-outline-success mx-2 pt-0 pb-1 px-3"> + </a>
                            </span>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
                <!-- Produk Saya Section -->
                <h2 class="ms-4">Produk Saya</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    $sql = "SELECT * FROM menu WHERE seller_username='$namaos'";
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
                                        <a href="hapus-produk.php?id=<?php echo $menu['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                                        <a href="edit-menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-primary">Edit</a>
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