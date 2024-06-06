<?php
require('config.php');
session_start();
$error = '';

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    $_SESSION['msg'] = 'Anda sudah login, redirect ke index';
    header('Location: index.php');
    exit();
}

if (isset($_POST['submit'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);

    if (!empty(trim($username)) && !empty(trim($password))) {
        $query = "SELECT * FROM users WHERE username = '$username'";
        $result = mysqli_query($koneksi, $query);
        $rows = mysqli_num_rows($result);

        if ($rows != 0) {
            $hash = mysqli_fetch_assoc($result)['password'];
            if (password_verify($password, $hash)) {
                $_SESSION['username'] = $username;
                header('Location: index.php');
                exit();
            } else {
                $error = 'Login Gagal, periksa Username dan Password Anda';
            }
        } else {
            $error = 'Login Gagal, periksa Username dan Password Anda';
        }
    } else {
        $error = 'Data tidak boleh kosong !!';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <a href="login.php" class="logo">Kantin Online</a>
    </header>
    <div class="banner">
        <div class="mx-auto" style="width: 500px; height: auto;">
            <div class="card">
                <div class="card-body">
                    <section class="container-fluid mb-4">
                        <section class="justify-content-center">
                            <form class="form-container" action="login.php" method="POST">
                                <h4 class="text-center font-weight-bold"> Sign-In </h4>
                                <?php if ($error): ?>
                                    <div class="alert alert-danger" role="alert">
                                        <?= $error ?>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group my-2">
                                    <label for="username">Username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username">
                                </div>
                                <div class="form-group my-2">
                                    <label for="InputPassword">Password</label>
                                    <input type="password" class="form-control" id="InputPassword" name="password" placeholder="Password">
                                </div>
                                <button type="submit" name="submit" class="btn btn-primary btn-block my-2">Sign In</button>
                                <div class="form-footer mt-2">
                                    <p> Belum punya account? <a href="register.php">Registrasi</a></p>
                                </div>
                            </form>
                        </section>
                    </section>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
