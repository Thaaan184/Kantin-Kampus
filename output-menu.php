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
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="login.php"><img src="image\logopolos.png" class="upn"></a>
        <ul class="navigasi">
            <?php if ($role == 'admin' || $role == 'seller') { ?>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <?php if ($role == 'admin') { ?>
                    <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                    <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
                <?php } ?>
            <?php } ?>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="banner">
        <div class="mx-auto">
            <div class="card">
                <h4 class="card-header">Edit Produk</h4>
                <div class="card-body">
                    <?php if (isset($_GET['status'])) : ?>
                        <?php if ($_GET['status'] == 'gagal') { ?>
                            <div class="alert alert-danger" role="alert">
                                <label>Gagal menghapus produk</label>
                            </div>
                            <?php header("refresh:3;url=output-menu.php"); ?>
                        <?php } ?>
                        <?php if ($_GET['status'] == 'sukses') { ?>
                            <div class="alert alert-success" role="alert">
                                <label>Berhasil menghapus produk</label>
                            </div>
                            <?php header("refresh:3;url=output-menu.php"); ?>
                        <?php } ?>
                    <?php endif; ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Nama Seller</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Gambar</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($menu = mysqli_fetch_array($query)) { ?>
                                <tr>
                                    <td><?php echo $menu['nama_produk']; ?></td>
                                    <td><?php echo $menu['seller_username']; ?></td>
                                    <td><?php echo $menu['kategori']; ?></td>
                                    <td><?php echo $menu['deskripsi_produk']; ?></td>
                                    <td><?php echo $menu['harga_produk']; ?></td>
                                    <td><?php echo $menu['stok']; ?></td>
                                    <td><img src="uploads/<?php echo $menu['gambar']; ?>" alt="<?php echo $menu['nama_produk']; ?>" width="100"></td>
                                    <td>
                                        <a href="edit-menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-warning">Edit</a>
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