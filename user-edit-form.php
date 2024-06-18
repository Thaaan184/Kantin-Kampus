<?php
include("config.php");
session_start();
$namaos = strval($_SESSION['username']);

if (!isset($_SESSION['username'])) {
    $_SESSION['msg'] = 'anda harus login untuk mengakses halaman ini';
    header('Location: login.php');
    exit();
}

$sql = "SELECT * FROM users WHERE username='$namaos'";
$query = mysqli_query($koneksi, $sql);

while ($ingfos = mysqli_fetch_assoc($query)) {
    $role = $ingfos['role'];
}

if ($role != "admin") {
    header('Location: index.php?status=notadmin');
    exit();
}

$id = $_GET['id'];
$sql = "SELECT * FROM users WHERE id=$id";
$query = mysqli_query($koneksi, $sql);
$user = mysqli_fetch_assoc($query);

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($koneksi, $_POST['confirm_password']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid';
    }

    // Validate password length
    if (!empty($password) && strlen($password) < 8) {
        $error = 'Password harus minimal 8 karakter';
    }

    // Check if username or email already exists in the database, except for the current user
    $sql_check = "SELECT * FROM users WHERE (username='$username' OR email='$email') AND id != $id";
    $result_check = mysqli_query($koneksi, $sql_check);
    if (mysqli_num_rows($result_check) > 0) {
        $error = 'Username atau Email sudah terdaftar';
    }

    if (!empty($password) && $password !== $confirm_password) {
        $error = 'Password dan Konfirmasi Password tidak sama';
    }

    if (empty($error)) {
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET 
                    username='$username', 
                    name='$name', 
                    email='$email', 
                    role='$role', 
                    password='$hashed_password' 
                    WHERE id=$id";
        } else {
            $sql = "UPDATE users SET 
                    username='$username', 
                    name='$name', 
                    email='$email', 
                    role='$role' 
                    WHERE id=$id";
        }

        if (mysqli_query($koneksi, $sql)) {
            header('Location: user-edit.php?id=' . $id . '&status=sukses');
        } else {
            $error = 'Gagal mengedit data';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data User</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- JAVA SCRIPT -->
    <script src="js\script.js"></script>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"></script>
</head>

<body>
    <header>
        <a href="login.php"><img src="image\logopolos.png"></a>
        <div class="left-content">
            <ul class="navigasi">
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php" style="color: white; font-weight: 600;">Edit user</a></li>
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
        <div class="mx-auto">
            <div class="card">
                <h4 class="card-header">Edit Data</h4>
                <div class="card-body">
                    <?php if (isset($_GET['status'])) : ?>
                        <?php
                        if ($_GET['status'] == 'sukses') {
                        ?>
                            <div class="alert alert-success" role="alert">
                                <label>Berhasil mengedit data</label>
                            </div>
                        <?php
                            header("refresh:3;url=index.php");
                        }
                        ?>
                    <?php endif; ?>
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <label><?php echo $error; ?></label>
                        </div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?php echo $user['id'] ?>" />
                        <div class="mb-3 row">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username'] ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name'] ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="role" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role" name="role">
                                    <option value="admin" <?php if ($user['role'] == "admin") echo "selected" ?>>Admin</option>
                                    <option value="user" <?php if ($user['role'] == "user") echo "selected" ?>>User</option>
                                    <option value="seller" <?php if ($user['role'] == "seller") echo "selected" ?>>Seller</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="confirm_password" class="col-sm-2 col-form-label">Confirm Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                        <span>
                            <input type="submit" name="simpan" value="Simpan Data" class="btn btn-primary">
                            <a href="user-edit.php"><input type="button" value="Kembali" class="btn btn-danger"></a>
                        </span>
                    </form>
                </div>
            </div>
        </div>
    </div>
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