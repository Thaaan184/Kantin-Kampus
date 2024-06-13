<?php
include("config.php");
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$namaos = $is_logged_in ? $_SESSION['username'] : null;
$nameos = '';
$role = '';
$qris_image = '';

// If user is logged in, retrieve user information
if ($is_logged_in) {
    $sql = "SELECT * FROM users WHERE username='$namaos'";
    $query = mysqli_query($koneksi, $sql);
    $user_info = mysqli_fetch_assoc($query);
    $nameos = $user_info['name'];
    $role = $user_info['role'];
    $qris_image = $user_info['qris_image'];
}

// If user is not a seller, redirect to index page
if ($role !== 'seller') {
    header("Location: index.php");
    exit();
}

// Upload QRIS
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['qris_image'])) {
    $qris_image = $_FILES['qris_image']['name'];
    $target_dir = "uploads/qris/";
    $target_file = $target_dir . basename($qris_image);

    if ($_FILES['qris_image']['size'] > 500000) {
        $error = "Sorry, your file is too large.";
    } elseif (move_uploaded_file($_FILES['qris_image']['tmp_name'], $target_file)) {
        $sql = "UPDATE users SET qris_image = '$qris_image' WHERE username = '$namaos'";
        mysqli_query($koneksi, $sql);
    } else {
        $error = "Sorry, there was an error uploading your file.";
    }

    // Refresh the page to show the updated QRIS image
    header("Location: toko.php");
    exit();
}

// Process Orders
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $transaction_id = $_POST['transaction_id'];
    if ($_POST['action'] == 'konfirmasi') {
        $sql = "UPDATE transactions SET status = 'processing' WHERE id = '$transaction_id'";
        mysqli_query($koneksi, $sql);
    } elseif ($_POST['action'] == 'batal') {
        // Update transaction status to canceled
        $sql = "UPDATE transactions SET status = 'canceled' WHERE id = '$transaction_id'";
        if (mysqli_query($koneksi, $sql)) {
            // Retrieve the transaction details
            $sql = "SELECT menu_id, quantity FROM transactions WHERE id = '$transaction_id'";
            $result = mysqli_query($koneksi, $sql);
            $transaction = mysqli_fetch_assoc($result);

            // Return the stock
            $menu_id = $transaction['menu_id'];
            $quantity = $transaction['quantity'];
            $sql = "UPDATE menu SET stok = stok + $quantity WHERE id = '$menu_id'";
            mysqli_query($koneksi, $sql);
        }
    } elseif ($_POST['action'] == 'selesai') {
        $sql = "UPDATE transactions SET status = 'ready' WHERE id = '$transaction_id'";
        mysqli_query($koneksi, $sql);
    }

    // Refresh the page to show updated orders
    header("Location: toko.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Saya | Kantin Online</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">

    <!-- JAVA SCRIPT -->
    <script src="js\script.js"></script>
</head>

<body>
    <header>
        <a href="index.php"><img src="image\logopolos.png" class="upn"></a>
        <ul class="navigasi">
            <li><a class="nav-item nav-link active" href="index.php">Beranda</a></li>
            <li><a class="nav-item nav-link active" href="toko.php" style="color: white; font-weight: 600;">Toko Saya</a></li>
            <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
            <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
            <li><a class="nav-item nav-link active" href="histori-transaksi.php">Histori Transaksi</a></li>
            <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
        </ul>
    </header>
    <div class="banner">
        <div class="album py-5 bg-light">
            <div class="container">
                <?php if ($is_logged_in) { ?>
                    <div class="d-flex justify-content-between align-items-center">
                        <h2>Toko Saya - Selamat datang, <?php echo $nameos; ?></h2>
                    </div>
                    <hr>
                <?php } ?>

                <!-- Upload QRIS Section -->
                <h3>QRIS Saya</h3>
                <?php if (isset($error)) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <?php if (!empty($qris_image)) { ?>
                    <div class="mb-3">
                        <img src="uploads/qris/<?php echo $qris_image; ?>" alt="QRIS" style="max-width: 200px;">
                    </div>
                <?php } ?>
                <form action="toko.php" method="post" enctype="multipart/form-data">
                    <label for="qris_image"><?php echo !empty($qris_image) ? 'Ganti QRIS:' : 'Upload QRIS:'; ?></label>
                    <input type="file" name="qris_image" id="qris_image" required>
                    <button type="submit" class="btn btn-primary"><?php echo !empty($qris_image) ? 'Ganti QRIS' : 'Upload'; ?></button>
                </form>
                <hr>

                <!-- Produk Saya Section -->
                <h2 class="ms-4">Produk Saya</h2>
                <hr>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                    <?php
                    $sql = "SELECT * FROM menu WHERE seller_username='$namaos'";
                    $query = mysqli_query($koneksi, $sql);
                    while ($menu = mysqli_fetch_assoc($query)) {
                        $id = $menu['id'];
                        $nama_produk = $menu['nama_produk'];
                        $harga_produk = $menu['harga_produk'];
                        $deskripsi_produk = $menu['deskripsi_produk'];
                        $stok = $menu['stok'];
                        $gambar = $menu['gambar'];
                    ?>
                        <div class="col ms-4 my-3" style="width: 300px;">
                            <div class="card shadow-sm">
                                <img src="uploads/<?php echo $gambar ?>" height="200px">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4><?php echo $nama_produk ?></h4>
                                        <h4>Rp. <?php echo number_format($harga_produk, 0, ',', '.'); ?></h4>
                                    </div>
                                    <p class="card-text"><?php echo substr($deskripsi_produk, 0, 20); ?>... </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="hapus-produk.php?id=<?php echo $menu['id']; ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
                                        <a href="edit-menu.php?id=<?php echo $menu['id']; ?>" class="btn btn-primary">Edit</a>
                                        <small class="text-muted">Stok: <?php echo $stok ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <!-- Notifikasi Pesanan Baru -->
                <h2>Notifikasi Pesanan Baru</h2>
                <?php
                $sql = "SELECT t.*, u.name, m.nama_produk, t.total_price 
                        FROM transactions t
                        JOIN users u ON t.user_id = u.id
                        JOIN menu m ON t.menu_id = m.id
                        WHERE m.seller_username = '$namaos' AND t.status IN ('waiting', 'processing', 'ready')";
                $query = mysqli_query($koneksi, $sql);
                while ($transaction = mysqli_fetch_assoc($query)) {
                    $action_buttons = '';
                    if ($transaction['status'] == 'waiting') {
                        $action_buttons = "
                            <form action='toko.php' method='post'>
                                <input type='hidden' name='transaction_id' value='{$transaction['id']}'><br>
                                <button type='submit' name='action' value='konfirmasi' class='btn btn-success'>Konfirmasi</button>  
                                <button type='submit' name='action' value='batal' class='btn btn-danger'>Batal</button>
                            </form>
                        ";
                    } elseif ($transaction['status'] == 'processing') {
                        $action_buttons = "
                            <form action='toko.php' method='post'>
                                <input type='hidden' name='transaction_id' value='{$transaction['id']}'><br>
                                <button type='submit' name='action' value='selesai' class='btn btn-primary'>Selesai</button>
                            </form>
                        ";
                    } elseif ($transaction['status'] == 'ready') {
                        $action_buttons = "
                            <p>Menunggu konfirmasi dari pembeli</p>
                        ";
                    }
                ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Pesanan dari: <?php echo $transaction['name']; ?></h5>
                            <p class="card-text">Produk: <?php echo $transaction['nama_produk']; ?></p>
                            <p class="card-text">Jumlah: <?php echo $transaction['quantity']; ?></p>
                            <p class="card-text">Total Harga: Rp. <?php echo number_format($transaction['total_price'], 0, ',', '.'); ?></p>
                            <p class="card-text">Status: <?php echo $transaction['status']; ?></p>
                            <!-- Display payment proof if available -->
                            <?php if (!empty($transaction['payment_proof'])) { ?>
                                <p class="card-text">Bukti Pembayaran:</p>
                                <img src="uploads/bukti/<?php echo $transaction['payment_proof']; ?>" alt="Bukti Pembayaran" style="max-width: 200px;">
                            <?php } ?>
                            <?php echo $action_buttons; ?>
                        </div>
                    </div>
                <?php } ?>
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