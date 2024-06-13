<?php

require('config.php');
include("config.php");

session_start();
$namaos = strval($_SESSION['username']);


if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
}
$sql = "SELECT * FROM users where username='$namaos'";
$query = mysqli_query($koneksi, $sql);
//mengecek apakah ada error ketika menjalankan query
$no = 1;
while ($ingfos = mysqli_fetch_assoc($query)) {
    $role               = $ingfos['role'];
}
if ($role == "admin") {
    echo "";
} else {
    header('Location: index.php?status=notadmin');
}

$error = '';
$sukses = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tambah User</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">

    <!-- JAVA SCRIPT -->
    <script src="js\script.js"></script>
</head>

<body>
    <header>
        <a href="login.php"><img src="image/logo-putih.png" class="upn"></a>
        <ul class="navigasi">
            <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-user.php" style="color: white; font-weight: 600;">Tambah User</a></li>
            <li><a class="nav-item nav-link active" href="user-edit.php">Edit user</a></li>
            <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>

    </header>
    <div class="banner">
        <div class="mx-auto" style="width : 500px;">
            <div class="card">
                <div class="card-body">

                    <div class="justify-content-center">
                        <form class="form-container" action="proses-tambah.php" method="POST">
                            <h4 class="text-center font-weight-bold"> Tambah User / Admin </h4>
                            <?php
                            if ($error) {
                            ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error ?>
                                </div>
                            <?php
                            }
                            ?>
                            <?php if (isset($_GET['status'])) : ?>
                                <?php
                                if ($_GET['status'] == 'sukses') {
                                ?>
                                    <div class="alert alert-success" role="alert">Registrasi Berhasil</div>
                                <?php
                                    header("refresh:3;url=tambah-user.php");
                                }
                                ?>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama">
                            </div>
                            <div class="form-group">
                                <label for="InputEmail">Alamat Email</label>
                                <input type="email" class="form-control" id="InputEmail" name="email" aria-describeby="emailHelp" placeholder="Masukkan email">
                            </div>
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username">
                            </div>
                            <div class="form-group">
                                <label for="InputPassword">Password</label>
                                <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role">
                                    <option value="">- Role -</option>
                                    <option value="admin" name="role">Admin</option>
                                    <option value="user" name="role">User</option>
                                    <option value="seller" name="role">Seller</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Tambah</button>
                        </form>
                    </div>
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