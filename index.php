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

    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- JAVA SCRIPT -->
    <script src="js\script.js"></script>
    <script src="js\swiper-bundle.min.js"></script>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"></script>

</head>

<body>
    <header>
        <a href="index.php" style="padding: none;"><img src="image/logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <?php if ($role == 'admin') { ?>
                    <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                    <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
                    <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
                <?php } elseif ($role == 'seller') { ?>
                    <li><a class="nav-item nav-link active" href="index.php" style="color: white; font-weight: 600;">Beranda</a></li>
                    <li><a class="nav-item nav-link active" href="toko.php">Toko Saya</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                    <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                    <li><a class="nav-item nav-link active" href="histori-transaksi.php">Histori Transaksi</a></li>
                <?php } else { ?>
                    <li><a class="nav-item nav-link active" href="index.php" style="color: white; font-weight: 600;">Beranda</a></li>
                    <li><a class="nav-item nav-link active" href="histori-transaksi.php">Histori Transaksi</a></li>
                <?php } ?>
            </ul>
        </div>

        <div class="right-content">
            <ul class="navigasi">
                <?php if ($is_logged_in) { ?>
                    <li><a class="nav-item nav-link active" href="payment-status.php"><i class='bx bxs-bell' style="font-size: 2rem;"></i></a></li>
                    <li><a class="nav-item nav-link active" href="logout.php"><i class='bx bx-log-out' style="font-size: 2rem;"></i></a></li>
                <?php } else { ?>
                    <li><a class="nav-item nav-link active" href="login.php"><i class='bx bx-log-in' style="font-size: 2rem;"></i></a></li>
                    <li><a class="nav-item nav-link active" href="register.php"><i class='bx bx-user-plus' style="font-size: 2rem;"></i></a></li>
                <?php } ?>
            </ul>
        </div>
    </header>

    <div class="banner">
        <div class="album" style="border-radius: 2rem;">
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
                            <div class="card card-makanan-berat shadow-sm">
                                <img src="uploads/<?php echo $gambar ?>" class="card-img" alt="Gambar produk">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4><?php echo $nama_produk ?></h4>
                                        <h4>Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></h4>
                                    </div>
                                    <p class="card-text"><?php echo substr($deskripsi_produk, 0, 20); ?>... </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="beli-produk.php?id=<?php echo $id ?>"><button type="button" class="btn btn-outline-success me-2 px-4">Buy</button></a>
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
                            <div class="card card-makanan-ringan shadow-sm">
                                <img src="uploads/<?php echo $gambar ?>" class="card-img" alt="Gambar produk">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4><?php echo $nama_produk ?></h4>
                                        <h4>Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></h4>
                                    </div>
                                    <p class="card-text"><?php echo substr($deskripsi_produk, 0, 20); ?>... </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="beli-produk.php?id=<?php echo $id ?>"><button type="button" class="btn btn-outline-success me-2 px-4">Buy</button></a>
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
                    $sql3 = "SELECT * FROM menu WHERE kategori='minuman'";
                    $query = mysqli_query($koneksi, $sql3);
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id = $menu['id'];
                        $nama_produk = $menu['nama_produk'];
                        $harga_produk = $menu['harga_produk'];
                        $deskripsi_produk = $menu['deskripsi_produk'];
                        $stok = $menu['stok'];
                        $gambar = $menu['gambar'];
                    ?>
                        <div class="col ms-4 my-3" style="width: 300px;">
                            <div class="card card-minuman shadow-sm">
                                <img src="uploads/<?php echo $gambar ?>" class="card-img" alt="Gambar produk">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4><?php echo $nama_produk ?></h4>
                                        <h4>Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></h4>
                                    </div>
                                    <p class="card-text"><?php echo substr($deskripsi_produk, 0, 20); ?>... </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="beli-produk.php?id=<?php echo $id ?>"><button type="button" class="btn btn-outline-success me-2 px-4">Buy</button></a>
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
    <!-- Isi halaman -->
    <footer>
    <div class="">
  <p>&copy; 2024 Kantin Online. All rights reserved.</p>
  <div class="info">
    <div class="information">
    <i class='bx bx-map-alt' ></i>
      <p> <a href="https://maps.app.goo.gl/JZ7MPhayRR5WoeFE9?g_st=ac"style="color: white; text-decoration: none;">Alamat</a></p>
    </div>
    <div class="information">
    <i class='bx bx-envelope' ></i>
      <p>Than184@gmail.com</p>
    </div>
    <div class="information">
    <i class='bx bxs-phone' ></i> <span>+62 82123923909</span>
    </div>
  </div>
</div>

          <div class="social-media">
            <p>Connect with us :</p>
            <div class="social-icons">
              <a href="https://www.facebook.com/medwinaldizar.najwali?mibextid=ZbWKwL">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#">
                <i class="fab fa-tiktok"></i>
              </a>
              <a href="https://www.instagram.com/mhmd_sabil26?igsh=cmNybWsyaG1zMjBz">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="https://www.linkedin.com/in/muhammad-edwin-aldizar-najwali-50a82824a/?original_referer=https%3A%2F%2Fwww%2Ebing%2Ecom%2F&originalSubdomain=id">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </div>
            </p>
        </div>
    </footer>
</body>

</html>