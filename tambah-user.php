<?php

require('config.php');
include("foradmin.php");

$error = '';
$sukses = '';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Tambah User</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="index.php" class="logo">Beauty Basics</a>
        <ul class="navigasi">
            <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-user.php" style="color: white; font-weight: 600;">Tambah User</a></li>
            <li><a class="nav-item nav-link active" href="user-edit.php">Edit user</a></li>
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
                                <label for="InputPassword">Balance</label>
                                <input type="number" class="form-control" id="balance" name="balance" placeholder="Balance">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role">
                                    <option value="">- Role -</option>
                                    <option value="admin" name="role">Admin</option>
                                    <option value="user" name="role">User</option>
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

</html>