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
        $sql = "UPDATE transactions SET status = 'canceled' WHERE id = '$transaction_id'";
        mysqli_query($koneksi, $sql);
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <a href="index.php"><img src="image/logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <li><a class="nav-item nav-link active" href="index.php">Beranda</a></li>
                <?php if ($role == 'seller') { ?>
                    <li><a class="nav-item nav-link active" href="toko.php">Toko Saya</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                    <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <?php } ?>
                <li><a class="nav-item nav-link active" href="histori-transaksi.php" style="color: white; font-weight: 600;">Histori Transaksi</a></li>
            </ul>
        </div>
        <div class="right-content">
            <ul class="navigasi">
                <?php if ($is_logged_in) { ?>
                    <li><a class="nav-item nav-link active" href="payment-status.php"><i class='bx bxs-bell' style="font-size: 2rem;"></i></a></li>
                    <li><a class="nav-item nav-link active" href="logout.php"><i class='bx bx-log-out' style="font-size: 2rem;"></i></a></li>
                <?php } else { ?>
                    <li><a class="nav-item nav-link active" href="login.php"><i class='bx bx-log-in' style="font-size: 2rem;"></i></a></li>
                    <li><a class="nav-item nav-link active" href="register.php"><i class='bx bx-user-plus' style="font-size: 2rem;"></i></a></li>
                <?php } ?>
            </ul>
        </div>
    </header>
    <div class="banner">
        <div class="album py-5 bg-light">
            <div class="container">
                <!-- Histori Transaksi -->
                <h2>Histori Transaksi</h2>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Nama Pembeli</th>
                                <th>Nama Produk</th>
                                <th>Nama Penjual</th>
                                <th>Total Beli</th>
                                <th>Harga</th>
                                <th>Waktu</th>
                                <th>Bukti Transfer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($role == 'seller') {
                                $sql = "SELECT t.id, u.name AS pembeli, m.nama_produk, m.seller_username AS penjual, t.quantity, m.harga_produk, t.created_at, t.payment_proof 
                                        FROM transactions t
                                        JOIN users u ON t.user_id = u.id
                                        JOIN menu m ON t.menu_id = m.id
                                        WHERE m.seller_username = '$namaos' AND t.status = 'finished'";
                            } else {
                                $sql = "SELECT t.id, u.name AS pembeli, m.nama_produk, m.seller_username AS penjual, t.quantity, m.harga_produk, t.created_at, t.payment_proof 
                                        FROM transactions t
                                        JOIN users u ON t.user_id = u.id
                                        JOIN menu m ON t.menu_id = m.id
                                        WHERE t.user_id = (SELECT id FROM users WHERE username = '$namaos') AND t.status = 'finished'";
                            }
                            $query = mysqli_query($koneksi, $sql);
                            $no = 1; // Nomor urut
                            while ($transaction = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>" . $no++ . "</td>"; // Menampilkan nomor urut
                                echo "<td>" . $transaction['id'] . "</td>";
                                echo "<td>" . $transaction['pembeli'] . "</td>";
                                echo "<td>" . $transaction['nama_produk'] . "</td>";
                                echo "<td>" . $transaction['penjual'] . "</td>";
                                echo "<td>" . $transaction['quantity'] . "</td>";
                                echo "<td>Rp. " . number_format($transaction['harga_produk'], 0, ',', '.') . "</td>";
                                echo "<td>" . $transaction['created_at'] . "</td>";
                                echo "<td><img src='uploads/bukti/" . $transaction['payment_proof'] . "' alt='Bukti Pembayaran' style='max-width: 200px; max-height: 200px;'></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Isi halaman -->
    <footer>
    <div class="">
  <p>&copy; 2024 Kantin Online. All rights reserved.</p>
  <div class="info">
    <div class="information">
    <i class='bx bx-map-alt' ></i>
      <p> <a href="https://maps.app.goo.gl/JZ7MPhayRR5WoeFE9?g_st=ac"style="color: white; text-decoration: none;">Alamat</a></p>
    </div>
    <div class="information">
    <i class='bx bx-envelope' ></i>
      <p>Than184@gmail.com</p>
    </div>
    <div class="information">
    <i class='bx bxs-phone' ></i> <span>+62 82123923909</span>
    </div>
  </div>
</div>

          <div class="social-media">
            <p>Connect with us :</p>
            <div class="social-icons">
              <a href="https://www.facebook.com/medwinaldizar.najwali?mibextid=ZbWKwL">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#">
                <i class="fab fa-tiktok"></i>
              </a>
              <a href="https://www.instagram.com/mhmd_sabil26?igsh=cmNybWsyaG1zMjBz">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="https://www.linkedin.com/in/muhammad-edwin-aldizar-najwali-50a82824a/?original_referer=https%3A%2F%2Fwww%2Ebing%2Ecom%2F&originalSubdomain=id">
                <i class="fab fa-linkedin-in"></i>
              </a>
            </div>
          </div>
            </p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>

</html>