<?php
include("config.php");
include("foradmin.php");

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
    <title>Daftar Produk | Kantin Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="index.php" class="logo">Kantin Online</a>
        <ul class="navigasi">
            <li><a class="nav-item nav-link active" href="output-menu.php" style="color: white; font-weight: 600;">Edit Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
            <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
            <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="banner">
        <div class="mx-auto" style="width: 1100px;">
            <div class="card">
                <h4 class="card-header text-white bg-secondary">Daftar Produk</h4>
                <div class="card-body">
                    <nav>
                        <a href="tambah-produk.php"><button class="btn btn-outline-success me-2 my-3" type="button">[+] Tambah Baru</button></a>
                    </nav>
                    <?php if (isset($_GET['status'])) : ?>
                        <?php
                        if ($_GET['status'] == 'gagalhapus') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $gagalhapus ?>
                            </div>
                        <?php
                            header("refresh:3;url=output-menu.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'gagaledit') {
                        ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $gagaledit ?>
                            </div>
                        <?php
                            header("refresh:3;url=output-menu.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'sukseshapus') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $sukseshapus ?>
                            </div>
                        <?php
                            header("refresh:3;url=output-menu.php");
                        }
                        ?>
                        <?php
                        if ($_GET['status'] == 'suksesedit') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $suksesedit ?>
                            </div>
                        <?php
                            header("refresh:3;url=output-menu.php");
                        }
                        ?>
                    <?php endif; ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Menu</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Gambar</th>
                                <th scope="col"> </th>
                            </tr>
                        <tbody>
                            <?php
                            $sql        = "SELECT * FROM menu";
                            $query      = mysqli_query($koneksi, $sql);
                            $urut       = 1;
                            while ($menu = mysqli_fetch_assoc($query)) {
                                $id                 = $menu['id'];
                                $nama_produk        = $menu['nama_produk'];
                                $kategori           = $menu['kategori'];
                                $deskripsi_produk   = $menu['deskripsi_produk'];
                                $harga_produk       = $menu['harga_produk'];
                                $stok               = $menu['stok'];
                                $gambar             = $menu['gambar'];
                            ?>
                                <tr>
                                    <th scope="row"><?php echo $urut++ ?></th>
                                    <td scope="row"><?php echo $nama_produk ?></td>
                                    <td scope="row"><?php echo $kategori ?></td>
                                    <td scope="row"><?php echo $deskripsi_produk ?></td>
                                    <td scope="row">Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></td>
                                    <td scope="row"><?php echo $stok ?></td>
                                    <td style="text-align: center;"><img src="uploads/<?php echo $gambar ?>" style="width: 90px;"></td>
                                    <td scope="row">
                                        <a href="edit-menu.php?id=<?php echo $id ?>"><button type="button" class="btn btn-warning">Edit</button></a>
                                        <a href="hapus-produk.php?id=<?php echo $id ?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger ms-1">Delete</button></a>
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
