<?php
include("config.php");
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
$namaos = $is_logged_in ? $_SESSION['username'] : null;
$nameos = '';
$role = '';

if ($is_logged_in) {
    $sql = "SELECT * FROM users WHERE username='$namaos'";
    $query = mysqli_query($koneksi, $sql);
    $user_info = mysqli_fetch_assoc($query);
    $nameos = $user_info['name'];
    $role = $user_info['role'];
}

// Handle 'Pesanan Selesai' button click
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id']) && !isset($_FILES['payment_proof'])) {
    $transaction_id = $_POST['transaction_id'];
    $sql = "UPDATE transactions SET status='finished' WHERE id='$transaction_id'";
    mysqli_query($koneksi, $sql);
    header("Location: payment-status.php");
    exit();
}

// Handle upload payment proof
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id']) && isset($_FILES['payment_proof'])) {
    $transaction_id = $_POST['transaction_id'];
    $payment_proof = $_FILES['payment_proof']['name'];
    $target_dir = "uploads/bukti/";
    $target_file = $target_dir . basename($payment_proof);

    // Check file size and move uploaded file
    if ($_FILES['payment_proof']['size'] > 500000) {
        $error = "Sorry, your file is too large.";
    } elseif (move_uploaded_file($_FILES['payment_proof']['tmp_name'], $target_file)) {
        // Update transaction status and payment proof
        $sql = "UPDATE transactions SET payment_proof = '$payment_proof', status='waiting' WHERE id = '$transaction_id'";
        mysqli_query($koneksi, $sql);

        // Reduce stock
        $sql = "SELECT menu_id, quantity FROM transactions WHERE id = '$transaction_id'";
        $query = mysqli_query($koneksi, $sql);
        $transaction = mysqli_fetch_assoc($query);

        $menu_id = $transaction['menu_id'];
        $quantity = $transaction['quantity'];

        $sql = "UPDATE menu SET stok = stok - $quantity WHERE id = '$menu_id'";
        mysqli_query($koneksi, $sql);

        header("Location: payment-status.php");
        exit();
    } else {
        $error = "Sorry, there was an error uploading your file.";
    }
}

// Handle report submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report_transaction_id'])) {
    $transaction_id = $_POST['report_transaction_id'];
    $report_text = mysqli_real_escape_string($koneksi, $_POST['report_text']);
    $user_id = $user_info['id'];

    $sql = "INSERT INTO reports (user_id, transaction_id, report_text) VALUES ('$user_id', '$transaction_id', '$report_text')";
    mysqli_query($koneksi, $sql);

    // Update the transaction status to 'reported'
    $sql = "UPDATE transactions SET status='reported' WHERE id='$transaction_id'";
    mysqli_query($koneksi, $sql);

    header("Location: payment-status.php");
    exit();
}

// Handle delete transaction
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_transaction_id'])) {
    $transaction_id = $_POST['delete_transaction_id'];
    $sql = "DELETE FROM transactions WHERE id='$transaction_id'";
    mysqli_query($koneksi, $sql);
    header("Location: payment-status.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran | Kantin Online</title>
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
        <a href="index.php" style="padding: none;"><img src="image/logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <?php if ($role == 'admin') { ?>
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
                <h2>Status Pembayaran</h2>
                <hr>
                <?php if ($is_logged_in) { ?>
                    <?php
                    $sql = "SELECT t.*, m.nama_produk 
                            FROM transactions t
                            JOIN menu m ON t.menu_id = m.id
                            LEFT JOIN reports r ON t.id = r.transaction_id
                            WHERE t.user_id = '$user_info[id]' AND (t.status IN ('pending', 'waiting', 'processing', 'ready', 'canceled', 'reported')) AND (r.status IS NULL OR r.status NOT IN ('spam', 'finished'))";
                    $query = mysqli_query($koneksi, $sql);
                    while ($transaction = mysqli_fetch_assoc($query)) {
                        $status_message = '';
                        if ($transaction['status'] == 'pending') {
                            $status_message = 'Menunggu upload bukti pembayaran';
                        } elseif ($transaction['status'] == 'waiting') {
                            $status_message = 'Menunggu konfirmasi penjual';
                        } elseif ($transaction['status'] == 'processing') {
                            $status_message = 'Pesanan sudah di konfirmasi penjual mohon ditunggu.';
                        } elseif ($transaction['status'] == 'ready') {
                            $status_message = 'Pesanan sudah siap silahkan diambil.';
                        } elseif ($transaction['status'] == 'canceled') {
                            $status_message = 'Maaf pesanan kamu telah dibatalkan oleh penjual. Jika memiliki masalah lebih lanjut hubungi admin.';
                        } elseif ($transaction['status'] == 'reported') {
                            $status_message = 'Laporan kamu sudah diterima dan sedang diproses.';
                        }

                        $alert_class = $transaction['status'] == 'canceled' ? 'alert-danger' : 'alert-info';
                        echo "<div class='alert $alert_class'>
                                <p>Transaksi untuk produk {$transaction['nama_produk']} sejumlah {$transaction['quantity']} sedang $status_message.</p>
                              </div>";
                        if ($transaction['status'] == 'pending') {
                            echo "<form method='POST' action='' enctype='multipart/form-data'>
                                    <input type='hidden' name='transaction_id' value='{$transaction['id']}'>
                                    <label for='payment_proof'>Upload Bukti Pembayaran:</label>
                                    <div class='input-group mb-3'>
                                        <div class='custom-file'>
                                            <input type='file' class='custom-file-input' id='payment_proof' name='payment_proof' accept='image/*' required>
                                            <label class='custom-file-label' for='payment_proof'>Choose file</label>
                                        </div>
                                    </div>
                                    <button type='submit' name='upload_bukti' class='btn btn-primary'>Upload</button>
                                  </form>";
                        }
                        if ($transaction['status'] == 'ready') {
                            echo "<form method='POST' action=''>
                                    <input type='hidden' name='transaction_id' value='{$transaction['id']}'>
                                    <button type='submit' class='btn btn-success'>Pesanan Selesai</button>
                                  </form>";
                        }
                        if ($transaction['status'] == 'canceled') {
                            echo "<form method='POST' action=''>
                                    <input type='hidden' name='report_transaction_id' value='{$transaction['id']}'>
                                    <div class='form-group'>
                                        <label for='report_text'>Buat Laporan:</label>
                                        <textarea class='form-control' id='report_text' name='report_text' rows='3' required></textarea>
                                    </div>
                                    <button type='submit' class='btn btn-warning'>Buat Laporan</button>
                                  </form>
                                  <form method='POST' action=''>
                                    <input type='hidden' name='delete_transaction_id' value='{$transaction['id']}'>
                                    <button type='submit' class='btn btn-danger'>Abaikan</button>
                                  </form>";
                        }
                    }
                    ?>
                <?php } else { ?>
                    <div class="alert alert-warning">Anda harus login untuk melihat status pembayaran.</div>
                <?php } ?>
            </div>
        </div>
    </div>
    <!-- Footer -->
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
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.custom-file-input').addEventListener('change', function(e) {
                var fileName = document.getElementById("payment_proof").files[0].name;
                var nextSibling = e.target.nextElementSibling;
                nextSibling.innerText = fileName;
            });
        });
    </script>
</body>

</html>