<?php
include("config.php");
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$namaos = $is_logged_in ? $_SESSION['username'] : null;
$nameos = '';
$role = '';

// If user is logged in, retrieve user information
if ($is_logged_in) {
    $sql = "SELECT * FROM users WHERE username='$namaos'";
    $query = mysqli_query($koneksi, $sql);
    $user_info = mysqli_fetch_assoc($query);
    $nameos = $user_info['name'];
    $role = $user_info['role'];
}

// Retrieve product information from query string
$id = $_GET['id'];
$sql = "SELECT m.*, u.qris_image 
        FROM menu m 
        JOIN users u ON m.seller_username = u.username 
        WHERE m.id = '$id'";
$query = mysqli_query($koneksi, $sql);
$menu = mysqli_fetch_assoc($query);

$harga = $menu['harga_produk'];
$kategori = $menu['kategori'];
$nama_produk = $menu['nama_produk'];
$stok = $menu['stok']; // Tambahkan ini untuk mendapatkan stok produk
$qris_image = $menu['qris_image'];

$sukses = "";
$error = "";

if (isset($_POST['beli']) && $is_logged_in) {
    $banyak = $_POST['banyak'];
    $total_price = $harga * $banyak;

    // Check stock
    if ($menu['stok'] == 0) {
        $error = "Maaf, Stok " . $menu['nama_produk'] . " habis, silakan memilih menu lain";
    } elseif ($menu['stok'] < $banyak) {
        $error = "Maaf, Hanya bisa beli " . $menu['stok'] . " " . $menu['nama_produk'];
    } else {
        // Save transaction
        $sql = "INSERT INTO transactions (user_id, menu_id, quantity, total_price, status) VALUES ('$user_info[id]', '$menu[id]', '$banyak', '$total_price', 'pending')";
        if (mysqli_query($koneksi, $sql)) {
            $transaction_id = mysqli_insert_id($koneksi);
            $sukses = "Berhasil memilih $banyak $menu[nama_produk]. Silakan upload bukti pembayaran.";
        } else {
            $error = "Terjadi kesalahan. Silakan coba lagi.";
        }
    }
} elseif (isset($_POST['beli'])) {
    $error = "Anda harus login untuk membeli produk.";
}

if (isset($_POST['upload_bukti']) && $is_logged_in) {
    $transaction_id = $_POST['transaction_id'];
    $payment_proof = $_FILES['payment_proof']['name'];
    $target_dir = "uploads/bukti/";
    $target_file = $target_dir . basename($payment_proof);

    if (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
        $sql = "UPDATE transactions SET payment_proof = '$payment_proof', status = 'waiting' WHERE id = '$transaction_id'";
        if (mysqli_query($koneksi, $sql)) {
            // Reduce stock
            $sql = "UPDATE menu SET stok = stok - (SELECT quantity FROM transactions WHERE id = '$transaction_id') WHERE id = '$menu[id]'";
            if (mysqli_query($koneksi, $sql)) {
                $sukses = "Bukti pembayaran berhasil diupload. Menunggu konfirmasi.";
                header("Location: payment-status.php");
                exit();
            } else {
                $error = "Terjadi kesalahan saat mengurangi stok.";
            }
        } else {
            $error = "Terjadi kesalahan saat mengupload bukti pembayaran.";
        }
    } else {
        $error = "Terjadi kesalahan saat mengupload bukti pembayaran.";
    }
} elseif (isset($_POST['upload_bukti'])) {
    $error = "Anda harus login untuk mengupload bukti pembayaran.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beli Produk | Kantin Online</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('select_picture').addEventListener('click', function() {
                document.getElementById('payment_proof').click();
            });
        });
    </script>

</head>

<body>
    <header>
        <a href="index.php" style="padding: none;"><img src="image/logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <?php if ($role == 'admin') { ?>
                    <li><a class="nav-item nav-link active" href="index.php"></i>Beranda</a></li>
                    <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-produk.php"></i>Tambah Produk</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                    <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
                    <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
                <?php } elseif ($role == 'seller') { ?>
                    <li><a class="nav-item nav-link active" href="index.php"></i>Beranda</a></li>
                    <li><a class="nav-item nav-link active" href="toko.php"></i>Toko Saya</a></li>
                    <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                    <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                    <li><a class="nav-item nav-link active" href="histori-transaksi.php"></i>Histori Transaksi</a></li>
                <?php } else { ?>
                    <li><a class="nav-item nav-link active" href="index.php"></i>Beranda</a></li>
                    <li><a class="nav-item nav-link active" href="histori-transaksi.php">Histori Transaksi</a></li>
                <?php } ?>
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
                <h2>Beli Produk: <?php echo $nama_produk; ?></h2>
                <hr>
                <?php if ($sukses) { ?>
                    <div class="alert alert-success"><?php echo $sukses; ?></div>
                <?php } elseif ($error) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <div class="row">
                    <div class="col-md-6">
                        <img src="uploads/<?php echo $menu['gambar']; ?>" class="img-fluid">
                    </div>
                    <div class="col-md-6">
                        <h3>Harga: Rp. <?php echo number_format($harga, 0, ',', '.'); ?></h3>
                        <h4>Kategori: <?php echo $kategori; ?></h4>
                        <h4>Stok: <?php echo $stok; ?></h4> <!-- Tambahkan ini untuk menampilkan stok -->
                        <h5>Deskripsi:</h5>
                        <p><?php echo $menu['deskripsi_produk']; ?></p>
                        <form action="beli-produk.php?id=<?php echo $menu['id']; ?>" method="post">
                            <div class="form-group">
                                <label for="banyak">Jumlah:</label>
                                <input type="number" class="form-control" name="banyak" id="banyak" min="1" max="<?php echo $menu['stok']; ?>" required>
                            </div>
                            <?php if ($is_logged_in) { ?>
                                <button type="submit" name="beli" class="btn btn-primary">Beli</button>
                            <?php } else { ?>
                                <a href="login.php" class="btn btn-primary">Login untuk Beli</a>
                            <?php } ?>
                        </form>
                    </div>
                </div>

                <?php if ($sukses && isset($transaction_id)) { ?>
                    <form action="beli-produk.php?id=<?php echo $menu['id']; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="transaction_id" value="<?php echo $transaction_id; ?>">
                        <label for="payment_proof">Upload Bukti Pembayaran:</label>
                        <div class="input-group mb-3">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="payment_proof" name="payment_proof" accept="image/*" required>
                                <label class="custom-file-label" for="payment_proof">Choose file</label>
                            </div>

                        </div>
                        <button type="submit" name="upload_bukti" class="btn btn-primary">Upload</button>
                    </form>
                    <!-- Display QRIS Image -->
                    <h2>QRIS Pembayaran</h2>
                    <?php if ($qris_image) { ?>
                        <img src="uploads/qris/<?php echo $qris_image; ?>" class="img-fluid" alt="QRIS Payment">
                    <?php } else { ?>
                        <div class="alert alert-warning">QRIS tidak tersedia untuk produk ini.</div>
                    <?php } ?>
                <?php } ?>
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
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>

</html>