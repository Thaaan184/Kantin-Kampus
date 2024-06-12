<?php
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
//ambil id dari query string
$id = $_GET['id'];

// buat query untuk ambil data dari database
$sql = "SELECT * FROM users WHERE id=$id";
$query = mysqli_query($koneksi, $sql);
$user = mysqli_fetch_assoc($query);

if ($user['role'] == 'admin') {
    $role = 'Admin';
} else {
    $role = 'User';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data User</title>
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
            <li><a class="nav-item nav-link active" href="output-menu.php">Edit Menu</a></li>
            <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah menu</a></li>
            <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
            <li><a class="nav-item nav-link active" href="user-edit.php" style="color: white; font-weight: 600;">Edit user</a></li>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>

    </header>
    </header>
    <div class="banner">
        <div class="mx-auto">
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
                    <form action="proses-edit.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $user['id'] ?>" />
                        <div class="mb-3 row">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username'] ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="balance" class="col-sm-2 col-form-label">Balance</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="balance" name="balance" value="<?php echo $user['balance'] ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="role" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role" name="role">
                                    <option value="admin" <?php if ($user['role'] == "admin") echo "selected" ?>>Admin</option>
                                    <option value="user" <?php if ($user['role'] == "user") echo "selected" ?>>User</option>
                                    <option value="seller" <?php if ($user['role'] == "seller") echo "selected" ?>>Seller</option>
                                </select>
                            </div>
                        </div>

                        <span>
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                            <a href="user-edit.php"><input type="button" value="Kembali" class="btn btn-danger"></a>
                        </span>
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