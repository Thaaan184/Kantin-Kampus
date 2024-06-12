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


$gagalhapus = "Gagal Menghapus Data";
$gagaledit = "Gagal Mengedit Data";
$sukseshapus = "Berhasil Menghapus Data";
$suksesedit = "Berhasil Mengedit Data";
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Data Page</title>
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
            <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
            <li><a class="nav-item nav-link active" href="user-edit.php" style="color: white; font-weight: 600;">Edit user</a></li>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>

    </header>
    <div class="banner">
        <div class="mx-auto">
            <div class="card">
                <h4 class="card-header text-white bg-secondary">Data User</h4>
                <div class="card-body">

                    <?php if (isset($_GET['status'])) : ?>
                        <?php
                        if ($_GET['status'] == 'gagalhapus') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $gagalhapus ?>
                            </div>
                        <?php
                            header("refresh:3;url=user-edit.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'gagaledit') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $gagaledit ?>
                            </div>
                        <?php
                            header("refresh:3;url=user-edit.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'sukseshapus') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $sukseshapus ?>
                            </div>
                        <?php
                            header("refresh:3;url=user-edit.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'suksesedit') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $suksesedit ?>
                            </div>
                        <?php
                            header("refresh:3;url=user-edit.php");
                        }
                        ?>
                    <?php endif; ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Username</th>
                                <th scope="col">Nama</th>
                                <th scope="col">Email</th>
                                <th scope="col">Balance</th>
                                <th scope="col">Role</th>
                                <th scope="col"> </th>
                            </tr>
                        <tbody>
                            <?php
                            $sql        = "SELECT *FROM users";
                            $query      = mysqli_query($koneksi, $sql);
                            $urut       = 1;
                            while ($user  = mysqli_fetch_array($query)) {
                                $id         = $user['id'];
                                $username   = $user['username'];
                                $nama       = $user['name'];
                                $email     = $user['email'];
                                $balance    = $user['balance'];
                                $role    = $user['role'];
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $urut++ ?></th>
                                    <td scope="row"><?php echo $username ?></td>
                                    <td scope="row"><?php echo $nama ?></td>
                                    <td scope="row"><?php echo $email ?></td>
                                    <td scope="row"><?php echo $balance ?></td>
                                    <td scope="row"><?php echo $role ?></td>
                                    <td scope="row">
                                        <a href="user-edit-form.php?id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                        <a href="hapus.php?id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger ms-1">Delete</button></a>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>

</html>