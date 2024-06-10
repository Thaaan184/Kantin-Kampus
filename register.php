<?php
require('config.php');
session_start();
$error = '';
$sukses = '';

if (isset($_POST['submit'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $name = stripslashes($_POST['name']);
    $name = mysqli_real_escape_string($koneksi, $name);
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($koneksi, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);
    $role = 'user';

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    }

    // Check password length
    if (strlen($password) < 8) {
        $error = 'Password harus lebih dari 8 karakter';
    }

    if (!$error && !empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password))) {
        if (cek_nama($username, $koneksi) == 0) {
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, name, email, password, role) VALUES ('$username', '$name', '$email', '$pass', '$role')";
            $result = mysqli_query($koneksi, $query);
            if ($result) {
                header('Location: register.php?status=sukses');
                exit();
            } else {
                $error = 'Registrasi Gagal !!';
            }
        } else {
            $error = 'Username sudah terdaftar !!';
        }
    } else {
        if (!$error) {
            $error = 'Data tidak boleh kosong !!';
        }
    }
}

function cek_nama($username, $koneksi)
{
    $username = mysqli_real_escape_string($koneksi, $username);
    $query = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($koneksi, $query);
    return mysqli_num_rows($result);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="index.php"><img src="image/logo-putih.png" class="upn"></a>
    </header>
    <div class="banner">
        <div class="mx-auto" style="width: 500px;">
            <div class="card">
                <div class="card-body">
                    <div class="justify-content-center">
                        <form class="form-container" action="register.php" method="POST">
                            <h4 class="text-center font-weight-bold"> Registrasi </h4>
                            <?php if ($error) : ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_GET['status']) && $_GET['status'] == 'sukses') : ?>
                                <div class="alert alert-success" role="alert">
                                    Registrasi Berhasil
                                </div>
                                <?php header("refresh:3;url=login.php"); ?>
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
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Register</button>
                            <div class="form-footer mt-2">
                                <p> Sudah punya account? <a href="login.php">Login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>