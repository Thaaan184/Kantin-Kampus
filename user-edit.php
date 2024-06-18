<?php
include("config.php");
session_start();

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = 'Anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
    exit();
}

$namaos = strval($_SESSION['username']);

$sql = "SELECT * FROM users WHERE username='$namaos'";
$query = mysqli_query($koneksi, $sql);
// mengecek apakah ada error ketika menjalankan query
$no = 1;
while ($ingfos = mysqli_fetch_assoc($query)) {
    $role = $ingfos['role'];
}
if ($role == "admin") {
    echo "";
} else {
    header('Location: index.php?status=notadmin');
    exit();
}
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- JAVA SCRIPT -->
    <script src="js/script.js"></script>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <a href="login.php"><img src="image/logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php" style="color: white; font-weight: 600;">Edit User</a></li>
                <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
            </ul>
        </div>
        <div class="right-content">
            <ul class="navigasi">
                <li><a class="nav-item nav-link active" href="payment-status.php"><i class='bx bxs-bell' style="font-size: 2rem;"></i></a></li>
                <li><a class="nav-item nav-link active" href="logout.php"><i class='bx bx-log-out' style="font-size: 2rem;"></i></a></li>
            </ul>
        </div>
    </header>
    <div class="banner">
        <div class="album py-5 bg-light">
            <div class="container">
                <h2>Data User</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Username</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            <th scope="col"> </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM users";
                        $query = mysqli_query($koneksi, $sql);
                        $urut = 1;
                        while ($user = mysqli_fetch_array($query)) {
                            $id = $user['id'];
                            $username = $user['username'];
                            $nama = $user['name'];
                            $email = $user['email'];
                            $role = $user['role'];
                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $username ?></td>
                                <td scope="row"><?php echo $nama ?></td>
                                <td scope="row"><?php echo $email ?></td>
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
                </table>
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