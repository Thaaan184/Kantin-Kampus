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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['transaction_id'])) {
    $transaction_id = $_POST['transaction_id'];
    $sql = "UPDATE transactions SET status='finished' WHERE id='$transaction_id'";
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
</head>

<body>
    <header>
        <a href="index.php"><img src="image/logo-putih.png" class="upn"></a>
        <ul class="navigasi">
            <li><a class="nav-item nav-link active" href="index.php" style="color: white;">Beranda</a></li>
            <?php if ($is_logged_in) { ?>
                <li><a class="nav-item nav-link active" href="payment-status.php">Status Pembayaran</a></li>
                <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li><a class="nav-item nav-link active" href="login.php">Login</a></li>
            <?php } ?>
        </ul>
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
                            WHERE t.user_id = '$user_info[id]' AND (t.status = 'pending' OR t.status = 'waiting' OR t.status = 'processing' OR t.status = 'ready' OR t.status = 'canceled')";
                    $query = mysqli_query($koneksi, $sql);
                    while ($transaction = mysqli_fetch_assoc($query)) {
                        $status_message = '';
                        if ($transaction['status'] == 'pending') {
                            $status_message = 'Sedang menunggu upload bukti pembayaran';
                        } elseif ($transaction['status'] == 'waiting') {
                            $status_message = 'Sedang dicek penjual untuk mengonfirmasi pesanan';
                        } elseif ($transaction['status'] == 'processing') {
                            $status_message = 'Telah dikonfirmasi dan diproses oleh penjual, mohon ditunggu.';
                        } elseif ($transaction['status'] == 'ready') {
                            $status_message = 'Sedang dipacking dan siap diambil, silahkan menuju ke kantin untuk mengambil pesanan';
                        } elseif ($transaction['status'] == 'canceled') {
                            $status_message = 'Telah dicancel penjual sebab alasan tertentu, silahkan pesan menu lain';
                        }
                        echo "<div class='alert alert-info'>
                                Pesanan untuk produk {$transaction['nama_produk']} dengan jumlah {$transaction['quantity']}, $status_message.
                              </div>";
                        if ($transaction['status'] == 'ready' || $transaction['status'] == 'canceled') {
                            echo "<form method='POST' action=''>
                                    <input type='hidden' name='transaction_id' value='{$transaction['id']}'>
                                    <button type='submit' class='btn btn-success'>Akhiri Pesanan</button>
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
    <footer>
        <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    </footer>
</body>

</html