<?php
include("config.php");

// Start session
session_start();

// Fetch id from query string
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$is_logged_in = isset($_SESSION['username']);
$namaos = $is_logged_in ? $_SESSION['username'] : null;
$nameos = '';
$role = '';

// Fetch menu information
$sql = "SELECT * FROM menu WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$query = mysqli_stmt_get_result($stmt);
$menu = mysqli_fetch_assoc($query);

// If user is logged in, fetch user information
if ($is_logged_in) {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($koneksi, $sql);
    mysqli_stmt_bind_param($stmt, 's', $namaos);
    mysqli_stmt_execute($stmt);
    $query = mysqli_stmt_get_result($stmt);
    $user_info = mysqli_fetch_assoc($query);
    $nameos = $user_info['name'];
    $role = $user_info['role'];
}

// Proses update produk
if (isset($_POST['update'])) {
    $nama_produk = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $deskripsi_produk = $_POST['deskripsi_produk'];
    $harga_produk = $_POST['harga_produk'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];

    if ($gambar) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($gambar);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file);
        $sql = "UPDATE menu SET nama_produk='$nama_produk', kategori='$kategori', deskripsi_produk='$deskripsi_produk', harga_produk='$harga_produk', stok='$stok', gambar='$gambar' WHERE id='$id'";
    } else {
        $sql = "UPDATE menu SET nama_produk='$nama_produk', kategori='$kategori', deskripsi_produk='$deskripsi_produk', harga_produk='$harga_produk', stok='$stok' WHERE id='$id'";
    }

    $query = mysqli_query($koneksi, $sql);

    if ($query) {
        header('Location: output-menu.php?status=sukses');
    } else {
        header('Location: output-menu.php?status=gagal');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- JAVA SCRIPT -->
    <script src="js\script.js"></script>
</head>

<body>
    <header>
        <a href="login.php"><img src="image\logopolos.png"></a>
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
        </ul>
    </header>
    <div class="banner">
        <div class="mx-auto" style="width: 1080px;">
            <div class="card">
                <h4 class="card-header">Edit Data</h4>
                <div class="card-body">
                    <?php if (isset($_GET['status'])) : ?>
                        <?php
                        if ($_GET['status'] == 'gagal') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <label>Gagal mengedit data</label>
                            </div>
                        <?php
                            header("refresh:3;url=index.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'sukses') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <label>Berhasil mengedit data</label>
                            </div>
                        <?php
                            header("refresh:3;url=index.php");
                        }
                        ?>
                    <?php endif; ?>
                    <form action="edit-menu.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $menu['id'] ?>">

                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama Menu</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="nama" name="nama_produk" value="<?php echo $menu['nama_produk'] ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="kategori" class="col-sm-2 col-form-label">Kategori</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="kategori" name="kategori">
                                    <option value="makanan_berat" <?php if ($menu['kategori'] == "makanan_berat") echo "selected" ?>>Makanan Berat</option>
                                    <option value="makanan_ringan" <?php if ($menu['kategori'] == "makanan_ringan") echo "selected" ?>>Makanan Ringan</option>
                                    <option value="minuman" <?php if ($menu['kategori'] == "minuman") echo "selected" ?>>Minuman</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="deskripsi" class="col-sm-2 col-form-label">Deskripsi</label>
                            <div class="col-sm-10">
                                <textarea class="form-control" id="deskripsi" name="deskripsi_produk"><?php echo $menu['deskripsi_produk']; ?></textarea>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="harga" name="harga_produk" value="<?php echo $menu['harga_produk'] ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                            <div class="col-sm-10">
                                <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $menu['stok'] ?>">
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                            <div class="col-sm-10">
                                <img src="uploads/<?php echo $menu['gambar'] ?>" style="width: 100px;" class="mb-2">
                                <div class="file-upload">
                                    <button type="button" class="browse-button" onclick="document.getElementById('gambar').click();">Pilih Gambar</button>
                                    <input type="file" id="gambar" name="gambar" style="display: none;" onchange="document.getElementById('file-name').textContent = this.files[0].name;">
                                    <span id="file-name" class="ml-2"></span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" name="update" id="update" class="btn btn-primary px-4 py-2 me-3">Simpan</button>
                        <a href="output-menu.php"><button class="btn btn-outline-secondary me-2 my-3 px-4" type="button">Batal</button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
</body>

</html>