<?php
include("config.php");

session_start();
$is_logged_in = isset($_SESSION['username']);
$role = '';

if ($is_logged_in) {
    $username = $_SESSION['username'];
    $sql = "SELECT role FROM users WHERE username='$username'";
    $query = mysqli_query($koneksi, $sql);
    $user_info = mysqli_fetch_assoc($query);
    $role = $user_info['role'];
}

// Jika user bukan admin atau seller, redirect ke halaman index
if ($role !== 'admin' && $role !== 'seller') {
    header("Location: index.php");
    exit();
}

// Query untuk mengambil produk berdasarkan penjual jika seller
if ($role == 'seller') {
    $sql = "SELECT * FROM menu WHERE seller_username='$username'";
} else {
    $sql = "SELECT * FROM menu";
}
$query = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk | Kantin Online</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <header>
        <a href="login.php"><img src="image/logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <?php if ($role == 'admin') { ?>
                    <li><a class="nav-item nav-link active" href="output-menu.php" style="color: white; font-weight: 600;">Edit Produk</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                    <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
                    <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
                <?php } else { ?>
                    <li><a class="nav-item nav-link active" href="index.php">Beranda</a></li>
                    <li><a class="nav-item nav-link active" href="toko.php">Toko Saya</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                    <li><a class="nav-item nav-link active" href="output-menu.php" style="color: white; font-weight: 600;">Edit Produk</a></li>
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
        <div class="album py-5 bg-light">
            <div class="container">
                <h2>Edit Produk</h2>
                <?php if (isset($_GET['status'])) : ?>
                    <?php if ($_GET['status'] == 'gagal') { ?>
                        <div class="alert alert-danger" role="alert">
                            <label>Gagal menghapus produk</label>
                        </div>
                        <script>
                            setTimeout(function() {
                                window.location.href = 'output-menu.php';
                            }, 3000);
                        </script>
                    <?php } ?>
                    <?php if ($_GET['status'] == 'sukses') { ?>
                        <div class="alert alert-success" role="alert">
                            <label>Berhasil menghapus produk</label>
                        </div>
                        <script>
                            setTimeout(function() {
                                window.location.href = 'output-menu.php';
                            }, 3000);
                        </script>
                    <?php } ?>
                <?php endif; ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Produk</th>
                                <th>Nama Seller</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Gambar</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1; // Nomor urut
                            while ($menu = mysqli_fetch_array($query)) { ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $menu['nama_produk']; ?></td>
                                    <td><?php echo $menu['seller_username']; ?></td>
                                    <td><?php echo $menu['kategori']; ?></td>
                                    <td><?php echo $menu['deskripsi_produk']; ?></td>
                                    <td>Rp. <?php echo number_format($menu['harga_produk'], 0, ',', '.'); ?></td>
                                    <td><?php echo $menu['stok']; ?></td>
                                    <td><img src="uploads/<?php echo $menu['gambar']; ?>" alt="<?php echo $menu['nama_produk']; ?>" width="100"></td>
                                    <td>
                                        <a href="edit-menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-warning">Edit</a><br>
                                        <a href="hapus-produk.php?id=<?php echo $menu['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah anda yakin ingin menghapus produk ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; 2024 Kantin Online. All rights reserved.</p>
            <p>
                <a href="#">Privacy Policy</a> |
                <a href="#">Terms of Service</a> |
                <a href="#">Contact Us</a>
            </p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>

</html>