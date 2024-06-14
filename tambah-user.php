<?php
require('config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = 'Anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
    exit();
}

$namaos = $_SESSION['username'];
$sql = "SELECT role FROM users WHERE username='$namaos'";
$query = mysqli_query($koneksi, $sql);
$user = mysqli_fetch_assoc($query);

if ($user['role'] != 'admin') {
    header('Location: index.php?status=notadmin');
    exit();
}

$error = '';
$sukses = '';

if (isset($_POST['submit'])) {
    $role = stripslashes($_POST['role']);
    $role = mysqli_real_escape_string($koneksi, $role);
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $name = stripslashes($_POST['name']);
    $name = mysqli_real_escape_string($koneksi, $name);
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($koneksi, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);

    if (!empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($role))) {
        if (cek_nama($username, $koneksi) == 0 && cek_email($email, $koneksi) == 0) {
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (role, username, name, email, password) VALUES ('$role','$username','$name','$email','$pass')";
            $result = mysqli_query($koneksi, $query);
            if ($result) {
                header('Location: tambah-user.php?status=sukses');
                exit();
            } else {
                $error = 'Registrasi Gagal !!';
            }
        } else {
            $error = 'Username atau Email sudah terdaftar !!';
        }
    } else {
        $error = 'Data tidak boleh kosong dan role harus dipilih !!';
    }
}

function cek_nama($username, $koneksi)
{
    $username = mysqli_real_escape_string($koneksi, $username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    return mysqli_num_rows($result);
}

function cek_email($email, $koneksi)
{
    $email = mysqli_real_escape_string($koneksi, $email);
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);
    return mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tambah User</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- JAVA SCRIPT -->
    <script src="js/script.js"></script>
</head>

<body>
    <header>
        <a href="login.php"><img src="image\logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php" style="color: white; font-weight: 600;">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php">Edit user</a></li>
                <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
            </ul>
        </div>
        <div class="right-content">
            <ul class="navigasi">
                <li><a class="nav-item nav-link active" href="payment-status.php"><i class='bx bxs-bell' style="font-size: 2rem;"></i></a></li>
                <li><a class="nav-item nav-link active" href="logout.php"><i class='bx bx-log-out' style="font-size: 2rem;"></i></a></li>
            </ul>
        </div>
    </header>
    <div class="banner">
        <div class="mx-auto" style="width: 500px;">
            <div class="card">
                <div class="card-body">
                    <div class="justify-content-center">
                        <form class="form-container" action="tambah-user.php" method="POST">
                            <h4 class="text-center font-weight-bold">Tambah User / Admin</h4>
                            <?php if ($error) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?php echo $error ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses') : ?>
                                <div class="alert alert-success" role="alert">
                                    Registrasi Berhasil
                                </div>
                                <?php header("refresh:3;url=tambah-user.php"); ?>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan Nama">
                            </div>
                            <div class="form-group">
                                <label for="InputEmail">Alamat Email</label>
                                <input type="email" class="form-control" id="InputEmail" name="email" placeholder="Masukkan email">
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
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                    <option value="seller">Seller</option>
                                </select>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Tambah</button>
                        </form>
                    </div>
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
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>

</html>