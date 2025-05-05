<?php
session_start();
include('koneksi.php');

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Ambil data pengguna dari session
$pengirim_id = $_SESSION['id'];  // ID pengirim dari session
$role = $_SESSION['role'];

// Cek apakah form dikirim menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nomorpengirim = mysqli_real_escape_string($koneksi, $_POST['nomorpengirim']);
    $alamatpengirim = mysqli_real_escape_string($koneksi, $_POST['alamatpengirim']);
    $penerima = mysqli_real_escape_string($koneksi, $_POST['penerima']);
    $nomorpenerima = mysqli_real_escape_string($koneksi, $_POST['nomorpenerima']);
    $alamatpenerima = mysqli_real_escape_string($koneksi, $_POST['alamatpenerima']);
    $beratbarang = mysqli_real_escape_string($koneksi, $_POST['beratbarang']);
    $layanan = mysqli_real_escape_string($koneksi, $_POST['layanan']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);

    // Validasi data pengirim (cek apakah pengirim ada di database)
    $cekPengirim = "SELECT id FROM users WHERE id = '$pengirim_id' AND role = 'pengirim'";
    $resultPengirim = mysqli_query($koneksi, $cekPengirim);

    if (mysqli_num_rows($resultPengirim) == 0) {
        echo "Pengirim tidak ditemukan di database.";
        exit();
    }

    // Pilih kurir secara acak dari database
    $queryKurir = "SELECT id FROM users WHERE role = 'kurir'";
    $resultKurir = mysqli_query($koneksi, $queryKurir);

    if (mysqli_num_rows($resultKurir) > 0) {
        $kurirArray = [];
        while ($row = mysqli_fetch_assoc($resultKurir)) {
            $kurirArray[] = $row['id'];
        }
        $kurir = $kurirArray[array_rand($kurirArray)]; // Pilih kurir secara acak
    } else {
        echo "Tidak ada kurir yang tersedia.";
        exit();
    }

    // Set status awal pesanan
    $status = 'Diproses';

    // Query untuk menyimpan pesanan ke database
    $sql = "INSERT INTO orders (pengirim, nomorpengirim, alamatpengirim, penerima, nomorpenerima, alamatpenerima, beratbarang, layanan, harga, kurir, status) 
    VALUES ('$pengirim_id', '$nomorpengirim', '$alamatpengirim', '$penerima', '$nomorpenerima', '$alamatpenerima', '$beratbarang', '$layanan', '$harga', NULL, '$status')";


if (empty($kurir)) {
    echo "Gagal menyimpan pesanan: kurir tidak tersedia.";
    exit();
}

    if (mysqli_query($koneksi, $sql)) {
        // Redirect ke halaman daftar pesanan setelah berhasil
        header('Location: orders.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kurir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #2B221E;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: fixed;
        }
        .sidebar .menu-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px;
        }
        .sidebar .menu-item:hover {
            background-color: #495057;
            border-radius: 6px;
        }
        .logout {
            margin-top: auto;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="col-md-2 sidebar pt-4">
      <ul class="nav flex-column ms-2 mb-2">
        <li class="nav-item">
          <a class="nav-link active text-white" href="dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a><hr class="bg-secondary">
        </li>

        <?php if ($role === 'pengirim' ||  $role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="orders.php"><i class="fas fa-cart-plus me-2"></i>Orders</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>

        <?php if ($role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="data_akun.php"><i class="fas fa-users me-2"></i>Data Akun</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>

        <?php if ($role === 'kurir' ||  $role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="kurir.php"><i class="fas fa-truck me-2"></i>Pesanan Kurir</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>
        <li class="nav-item">
          <a class="nav-link-logout text-white" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
      </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h3>Selamat Datang, <?php echo htmlspecialchars($kurir); ?>!</h3>
        <form action="buat_order.php" method="POST" class="row g-3">
                <div class="col-md-6">
                    <label for="pengirim" class="form-label">Pengirim</label>
                    <input type="text" class="form-control" id="pengirim" name="pengirim" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="nomorpengirim" class="form-label">Nomor Pengirim</label>
                    <input type="number" class="form-control" id="nomorpengirim" name="nomorpengirim" value="<?php echo htmlspecialchars($nomorpengirim); ?>" readonly>
                </div>
                <div class="col-md-6">
                    <label for="alamatpengirim" class="form-label">Alamat Pengirim</label>
                    <input type="text" class="form-control" id="alamatpengirim" name="alamatpengirim" required>
                </div>
                <div class="col-md-6">
                    <label for="penerima" class="form-label">Penerima</label>
                    <input type="text" class="form-control" id="penerima" name="penerima" required>
                </div>
                <div class="col-md-6">
                    <label for="nomorpenerima" class="form-label">Nomor Penerima</label>
                    <input type="number" class="form-control" id="nomorpenerima" name="nomorpenerima" required>
                </div>
                <div class="col-md-6">
                    <label for="alamatpenerima" class="form-label">Alamat Penerima</label>
                    <input type="text" class="form-control" id="alamatpenerima" name="alamatpenerima" required>
                </div>
                <div class="col-md-6">
                    <label for="beratbarang" class="form-label">Berat Barang (kg)</label>
                    <input type="number" class="form-control" id="beratbarang" name="beratbarang" required>
                </div>
                <div class="col-md-6">
                    <label for="layanan" class="form-label">Layanan</label>
                    <select class="form-control" id="layanan" name="layanan" required>
                        <option value="normal">Normal</option>
                        <option value="hemat">Hemat (Pengiriman lebih lambat)</option>
                        <option value="express">Express (Pengiriman lebih cepat)</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="harga" class="form-label">Harga</label>
                    <input type="number" class="form-control" id="harga" name="harga" required readonly>
                </div>
                <div class="col-md-6">
                    <label for="kurir" class="form-label">Kurir</label>
                    <input type="text" class="form-control" id="kurir" name="kurir" readonly> <!-- No longer an input field -->
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Kirim</button>
                </div>
            </form>
        </div>
        <!-- Pesanan Tersedia -->
        <h4 class="mt-4">Pesanan Tersedia</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>PENGIRIM</th>
                    <th>NO PENGIRIM</th>
                    <th>ALAMAT PENGIRIM</th>
                    <th>PENERIMA</th>
                    <th>NO PENERIMA</th>
                    <th>ALAMAT PENERIMA</th>
                    <th>LAYANAN</th>
                    <th>KURIR</th>
                    <th>HARGA</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result_available) > 0): ?>
                    <?php $no = 1; ?>
                    <?php while ($order = mysqli_fetch_assoc($result_available)): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($order['pengirim']); ?></td>
                            <td><?php echo htmlspecialchars($order['nomorpengirim']); ?></td>
                            <td><?php echo htmlspecialchars($order['alamatpengirim']); ?></td>
                            <td><?php echo htmlspecialchars($order['penerima']); ?></td>
                            <td><?php echo htmlspecialchars($order['nomorpenerima']); ?></td>
                            <td><?php echo htmlspecialchars($order['alamatpenerima']); ?></td>
                            <td><?php echo htmlspecialchars($order['layanan']); ?></td>
                            <td><?php echo htmlspecialchars($order['kurir']); ?></td>
                            <td><?php echo number_format($order['harga'], 0, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($order['status']); ?></td>
                            <td>
                                <a href="kurir.php?ambil=<?php echo $order['id']; ?>" class="btn btn-primary btn-sm">Ambil</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="12" class="text-center">Tidak ada pesanan tersedia.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>