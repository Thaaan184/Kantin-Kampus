<?php
include("config.php");
session_start();

// Check if the user is logged in and if the user is an admin
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$namaos = $_SESSION['username'];
$role = '';

$stmt = $koneksi->prepare("SELECT role FROM users WHERE username = ?");
$stmt->bind_param("s", $namaos);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();
$role = $user_info['role'];
$stmt->close();

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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Reports | Kantin Online</title>
    <!-- CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="style2.css?v=<?php echo time(); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Palanquin+Dark&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <a href="index.php"><img src="image/logopolos.png" class="upn" alt="Logo"></a>
        <ul class="navigasi">
            <?php if ($role == 'admin') { ?>
                <li><a class="nav-item nav-link active" href="output-menu.php">Edit Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-produk.php">Tambah Produk</a></li>
                <li><a class="nav-item nav-link active" href="tambah-user.php">Tambah User</a></li>
                <li><a class="nav-item nav-link active" href="user-edit.php">Edit User</a></li>
                <li><a class="nav-item nav-link active" href="report-review.php">Report Review</a></li>
                <li><a class="nav-item nav-link active" href="logout.php">Logout</a></li>
            <?php } else { ?>
                <li><a class="nav-item nav-link active" href="login.php">Login</a></li>
            <?php } ?>
        </ul>
    </header>

    <div class="container mt-5">
        <h2>Admin Panel - Reports</h2>
        <hr>
        <h4>Reported Transactions</h4>
        <div class="table-responsive">
            <table class="table table-bordered">
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
                    <?php
                    $sql = "SELECT r.id, r.report_text, r.status, r.created_at, u.username, t.menu_id, m.nama_produk 
                            FROM reports r
                            JOIN users u ON r.user_id = u.id
                            JOIN transactions t ON r.transaction_id = t.id
                            JOIN menu m ON t.menu_id = m.id
                            WHERE r.status IN ('pending', 'reported')";
                    $query = $koneksi->query($sql);

                    while ($report = $query->fetch_assoc()) {
                        echo "<tr>
                                <td>{$report['id']}</td>
                                <td>{$report['username']}</td>
                                <td>{$report['menu_id']} ({$report['nama_produk']})</td>
                                <td>" . htmlspecialchars($report['report_text']) . "</td>
                                <td>" . htmlspecialchars($report['status']) . "</td>
                                <td>{$report['created_at']}</td>
                                <td>
                                    <form method='POST' action='' class='d-inline'>
                                        <input type='hidden' name='report_id' value='{$report['id']}'>
                                        <select name='status' class='form-control d-inline w-auto'>
                                            <option value='spam'>Spam</option>
                                            <option value='finished'>Finished</option>
                                        </select>
                                        <button type='submit' class='btn btn-primary'>Update</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer class="footer mt-auto py-3 bg-light">
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