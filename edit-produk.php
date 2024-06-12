<?php
include("config.php");
session_start();

// Cek apakah user sudah login dan apakah user adalah penjual
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'seller') {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$id = $_GET['id'];

// Query untuk mendapatkan data produk
$sql = "SELECT * FROM menu WHERE id='$id' AND seller_username='$username'";
$query = mysqli_query($koneksi, $sql);
$menu = mysqli_fetch_assoc($query);

// Jika produk tidak ditemukan atau bukan milik penjual, redirect
if (!$menu) {
    header('Location: output-menu.php?status=gagal');
    exit();
}

// Proses update produk
if (isset($_POST['update'])) {
    $harga_produk = $_POST['harga_produk'];
    $stok = $_POST['stok'];

    $sql = "UPDATE menu SET harga_produk='$harga_produk', stok='$stok' WHERE id='$id' AND seller_username='$username'";
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
    <title>Edit Produk</title>
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
            <li><a class="nav-item nav-link active" href="index.php">Beranda</a></li>
            <li><a class="nav-item nav-link active" href="toko.php" style="color: white;">Toko Saya</a></li>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card">
                        <h4 class="card-header">Edit Produk</h4>
                        <div class="card-body">
                            <form action="edit-produk.php?id=<?php echo $id; ?>" method="POST">
                                <div class="mb-3 row">
                                    <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="harga" name="harga_produk" value="<?php echo $menu['harga_produk']; ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="stok" class="col-sm-2 col-form-label">Stok</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="stok" name="stok" value="<?php echo $menu['stok']; ?>" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <div class="col-sm-10 offset-sm-2">
                                        <button type="submit" class="btn btn-primary" name="update">Update Produk</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>