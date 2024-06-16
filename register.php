<?php
require('config.php');
session_start();
$error = '';
$sukses = '';

// Mulai output buffering
ob_start();

if (isset($_POST['submit'])) {
    $username = stripslashes($_POST['username']);
    $username = mysqli_real_escape_string($koneksi, $username);
    $name = stripslashes($_POST['name']);
    $name = mysqli_real_escape_string($koneksi, $name);
    $email = stripslashes($_POST['email']);
    $email = mysqli_real_escape_string($koneksi, $email);
    $password = stripslashes($_POST['password']);
    $password = mysqli_real_escape_string($koneksi, $password);
    $confirm_password = stripslashes($_POST['confirm_password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $confirm_password);
    $role = 'user';

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid';
    }

    // Check password length
    if (strlen($password) < 8) {
        $error = 'Password harus lebih dari 8 karakter';
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok';
    }

    if (!$error && !empty(trim($name)) && !empty(trim($username)) && !empty(trim($email)) && !empty(trim($password)) && !empty(trim($confirm_password))) {
        if (cek_nama($username, $koneksi) == 0 && cek_email($email, $koneksi) == 0) {
            $pass = password_hash($password, PASSWORD_DEFAULT);
            $query = "INSERT INTO users (username, name, email, password, role) VALUES ('$username', '$name', '$email', '$pass', '$role')";
            $result = mysqli_query($koneksi, $query);
            if ($result) {
                // Menghentikan output buffering dan mengirim header
                ob_end_clean();
                header('Location: register.php?status=sukses');
                exit();
            } else {
                $error = 'Registrasi Gagal !!';
            }
        } else {
            $error = 'Username atau Email sudah terdaftar !!';
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

function cek_email($email, $koneksi)
{
    $email = mysqli_real_escape_string($koneksi, $email);
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);
    return mysqli_num_rows($result);
}

// Mengakhiri output buffering
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.0/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <a href="index.php"><img src="image/logopolos.png"></a>
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
                                <script>
                                    $(document).ready(function() {
                                        $('#successModal').modal('show');
                                        setTimeout(function() {
                                            window.location.href = 'login.php';
                                        }, 3000);
                                    });
                                </script>
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
                                <label for="ConfirmPassword">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="ConfirmPassword" name="confirm_password" placeholder="Konfirmasi Password">
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

    <!-- Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Registrasi Berhasil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Anda telah berhasil registrasi.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Oke</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
