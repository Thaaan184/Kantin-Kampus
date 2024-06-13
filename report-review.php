<?php
include("config.php");

session_start();
$is_logged_in = isset($_SESSION['username']);
$role = '';

if ($is_logged_in) {
    $username = $_SESSION['username'];
    $stmt = $koneksi->prepare("SELECT role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_info = $result->fetch_assoc();
    $role = $user_info['role'];
    $stmt->close();
}

// Redirect if not admin
if ($role !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle report status update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report_id']) && isset($_POST['status'])) {
    $report_id = $_POST['report_id'];
    $status = $_POST['status'];

    $stmt = $koneksi->prepare("UPDATE reports SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $report_id);
    $stmt->execute();
    $stmt->close();

    header("Location: report-review.php");
    exit();
}

// Query to fetch reports
$sql = "SELECT r.id, r.report_text, r.status, r.created_at, u.username, t.menu_id, m.nama_produk 
        FROM reports r
        JOIN users u ON r.user_id = u.id
        JOIN transactions t ON r.transaction_id = t.id
        JOIN menu m ON t.menu_id = m.id
        WHERE r.status IN ('pending','spam','finished')";
$query = $koneksi->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100">
    <header>
        <a href="login.php"><img src="image/logopolos.png" class="upn"></a>
        <ul class="navigasi">
            <?php if ($role == 'admin') { ?>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
                <li><a class="nav-item nav-link active" href="report-review.php" style="color: white; font-weight: 600;">Report Review</a></li>
                <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li><a class="nav-item nav-link active" href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </header>
    <div class="banner">
        <div class="container my-4 flex-grow-1">
            <div class="card p-3">
                <h4 class="card-header">Report Review</h4>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Transaction</th>
                                <th>Report Text</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($report = $query->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $report['id']; ?></td>
                                    <td><?php echo $report['username']; ?></td>
                                    <td><?php echo $report['menu_id']; ?> (<?php echo $report['nama_produk']; ?>)</td>
                                    <td><?php echo htmlspecialchars($report['report_text']); ?></td>
                                    <td><?php echo htmlspecialchars($report['status']); ?></td>
                                    <td><?php echo $report['created_at']; ?></td>
                                    <td>
                                        <form method="POST" action="" class="d-inline">
                                            <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                            <select name="status" class="form-control d-inline w-auto">
                                                <option value="pending">Abaikan</option>
                                                <option value="spam">Spam</option>
                                                <option value="finished">Selesai</option>
                                            </select>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <footer class="mt-auto">
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
</body>

</html>