<!DOCTYPE html>
<html lang="en">
<?php
include("config.php");

session_start();

// Cek apakah user sudah login
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

// Ambil data username seller dari database
$sellers = [];
if ($role == 'admin') {
    $sql_sellers = "SELECT username FROM users WHERE role='seller'";
    $query_sellers = mysqli_query($koneksi, $sql_sellers);
    while ($row = mysqli_fetch_assoc($query_sellers)) {
        $sellers[] = $row['username'];
    }
}
?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">

    <!-- JAVA SCRIPT -->
    <script src="js\script.js"></script>
</head>

<body>
    <header>
        <a href="login.php"><img src="image\logopolos.png" class="upn"></a>
        <ul class="navigasi">
            <?php if ($role == 'admin') { ?>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php" style="color: white; font-weight: 600;">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
                <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
            <?php } ?>
            <?php if ($role == 'seller') { ?>
                <li><a class="nav-item nav-link active" href="index.php">Beranda</a></li>
                <li><a class="nav-item nav-link active" href="toko.php">Toko Saya</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php" style="color: white; font-weight: 600;">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="histori-transaksi.php">Histori Transaksi</a></li>
            <?php } ?>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="banner">
        <div class="mx-auto">
            <div class="card">
                <h4 class="card-header">Tambahkan Menu</h4>
                <div class="card-body">
                    <?php if (isset($_GET['status'])) : ?>
                        <?php
                        if ($_GET['status'] == 'gagal') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <label>Gagal memasukkan data</label>
                            </div>
                        <?php
                            header("refresh:3;url=index.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'sukses') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <label>Berhasil memasukkan data baru</label>
                            </div>
                        <?php
                            header("refresh:3;url=index.php");
                        }
                        ?>
                    <?php endif; ?>
                    <form action="proses-input.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Produk</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama" name="nama_produk" placeholder="Masukkan nama..">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="">- Kategori -</option>
                                    <option value="makanan_berat">Makanan Berat</option>
                                    <option value="makanan_ringan">Makanan Ringan</option>
                                    <option value="minuman">Minuman</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="deskripsi" name="deskripsi_produk" placeholder="Tambahkan deskripsi.."></textarea>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="harga" name="harga_produk" placeholder="Masukkan harga..">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="stok" name="stok" placeholder="Masukkan stok..">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" name="gambar">
                            </div>
                        </div>

                        <?php if ($role == 'admin') { ?>
                            <div class="mb-3 row">
                                <label for="seller_username" class="col-sm-2 col-form-label">Seller Username</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="seller_username" name="seller_username">
                                        <option value="">- Pilih Seller -</option>
                                        <?php foreach ($sellers as $seller) { ?>
                                            <option value="<?php echo $seller; ?>"><?php echo $seller; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        <?php } else { ?>
                            <!-- Input hidden untuk seller_username -->
                            <input type="hidden" name="seller_username" value="<?php echo $username; ?>">
                        <?php } ?>

                        <span>
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                            <input type="reset" value="Reset" class="btn btn-danger ms-1">
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- Isi halaman -->
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
<script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

</html>