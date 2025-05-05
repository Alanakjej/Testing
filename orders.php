<?php
session_start(); 
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$role = $_SESSION['role'];

include('koneksi.php');

// Ambil id pengguna berdasarkan username
$username = $_SESSION['username'];
$sql = "SELECT id, phone FROM users WHERE name COLLATE utf8mb4_general_ci = '$username'";
$result = mysqli_query($koneksi, $sql);

// Query untuk mengambil semua kurir
$sql_kurir = "SELECT id, name FROM users WHERE role = 'kurir'";
$result_kurir = mysqli_query($koneksi, $sql_kurir);

// Periksa apakah query berhasil
if (!$result_kurir) {
    die('Query Error: ' . mysqli_error($koneksi));
}
// Cek apakah query berhasil
if (!$result) {
    die('Query Error: ' . mysqli_error($koneksi));
}

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $id = $row['id'];
    $nomorpengirim = $row['phone'];  // Mengambil nomor telepon pengirim
} else {
    $nomorpengirim = '';  // Jika tidak ada data, kosongkan nomor pengirim
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Paket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Sidebar */
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #2B221E;
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            padding-top: 20px;
            padding-bottom: 20px;
        }

        .sidebar .menu-item {
            margin-bottom: 25px; /* Tambah jarak antar menu */
            border-bottom: 1px solid #495057; /* Tambahkan garis sebagai pemisah */
        }

        .sidebar .menu-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 20px 15px; /* Perbesar padding */
            font-size: 1.6rem; /* Perbesar ukuran font */
        }

        .sidebar .menu-item:hover {
            background-color: #495057;
            border-radius: 6px;
        }

        .sidebar .logout {
            margin-top: auto; /* Letakkan di bagian paling bawah */
            padding: 20px 15px; /* Sesuaikan padding */
            font-size: 1.6rem; /* Ukuran font sesuai menu lainnya */
        }

        .sidebar .logout a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .sidebar .logout a:hover {
            background-color: #495057;
            border-radius: 6px;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }

        /* Form Kirim Paket */
        #send-package-form {
            display: none;
        }

        table th, table td {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
        <ul class="nav flex-column ms-2 mb-2">
            <li class="nav-item menu-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </li>

            <?php if ($role === 'pengirim' || $role === 'admin'): ?>
            <li class="nav-item menu-item">
                <a class="nav-link" href="orders.php">
                    <i class="fas fa-cart-plus me-2"></i>Orders
                </a>
            </li>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
            <li class="nav-item menu-item">
                <a class="nav-link" href="data_akun.php">
                    <i class="fas fa-users me-2"></i>Data Akun
                </a>
            </li>
            <?php endif; ?>

            <?php if ($role === 'kurir'): ?>
            <li class="nav-item menu-item">
                <a class="nav-link" href="kurir.php">
                    <i class="fas fa-truck me-2"></i>Pesanan Kurir
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <div class="logout">
            <a href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>



    <!-- Main Content -->
    <div class="main-content w-100">
        <div class="mb-4">
            <h3>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
        </div>
        <div class="d-flex justify-content-between mb-3">
            <input type="text" class="form-control w-25" placeholder="Cari pesanan..." id="searchInput">
            <button class="btn btn-primary" id="send-package-btn">Kirim Paket</button>
        </div>

        <!-- Form Kirim Paket -->
        <div id="send-package-form" class="mb-4">
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
                <div class="col-12">
                    <button type="submit" class="btn btn-success">Kirim</button>
                </div>
            </form>
        </div>
        
        
        <!-- Tabel Pesanan -->
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
                    <?php if ($role === 'admin'): ?>
                    <th>AKSI</th>
                    <?php endif; ?>
                    
                </tr>
            </thead>
            <tbody id="ordersTable">
            <?php
            $sql = "SELECT * FROM orders";
            $sql = "
            SELECT 
                o.*, 
                COALESCE(u1.name, 'Belum Ditentukan') AS pengirim, 
                COALESCE(u2.name, 'Belum Ditentukan') AS kurir 
            FROM orders o
            LEFT JOIN users u1 ON o.pengirim = u1.id
            LEFT JOIN users u2 ON o.kurir = u2.id
        ";
        $result = mysqli_query($koneksi, $sql) or die("Error: " . mysqli_error($koneksi));

        // Jalankan ulang query untuk pesanan dengan kurir kosong
        $sql_available = "SELECT * FROM orders WHERE kurir IS NULL";
        $result_available = mysqli_query($koneksi, $sql_available);

        if (mysqli_num_rows($result_available) > 0) {
            while ($order = mysqli_fetch_assoc($result_available)) {
                // Pilih kurir secara acak dari tabel users
                $sql_random_kurir = "SELECT id FROM users WHERE role = 'kurir' ORDER BY RAND() LIMIT 1";
                $result_random_kurir = mysqli_query($koneksi, $sql_random_kurir);
                
                if ($result_random_kurir && mysqli_num_rows($result_random_kurir) > 0) {
                    $kurir_id = mysqli_fetch_assoc($result_random_kurir)['id'];
                    
                    // Update kolom kurir di tabel orders
                    $update_sql = "UPDATE orders SET kurir = '$kurir_id' WHERE id = '{$order['id']}'";
                    mysqli_query($koneksi, $update_sql) or die("Error updating kurir: " . mysqli_error($koneksi));
                }
            }
        }
            if (mysqli_num_rows($result) > 0) {
                $nomor = 1;
                while ($data = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?= $nomor++; ?></td>
                        <td><?= htmlspecialchars($data['pengirim']); ?></td>
                        <td><?= htmlspecialchars($data['nomorpengirim']); ?></td>
                        <td><?= htmlspecialchars($data['alamatpengirim']); ?></td>
                        <td><?= htmlspecialchars($data['penerima']); ?></td>
                        <td><?= htmlspecialchars($data['nomorpenerima']); ?></td>
                        <td><?= htmlspecialchars($data['alamatpenerima']); ?></td>
                        <td><?= htmlspecialchars($data['layanan']); ?></td>
                        <td><?= htmlspecialchars($data['kurir']); ?></td>
                        <td><?= number_format($data['harga'], 0, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($data['status']); ?></td>
                        <?php if ($role === 'admin' || $role === 'kurir'): ?>
                        <td>
                            <button class="btn btn-warning btn-sm edit-status-btn" 
                                data-id="<?= $data['id']; ?>" 
                                data-status="<?= htmlspecialchars($data['status']); ?>"
                                data-kurir="<?= htmlspecialchars($data['kurir']); ?>">
                            Edit Status
                            </button>
    
                        <?php endif; ?>
                        <?php if ($role === 'admin'): ?>
                
                        <a href="hapus_order.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus order ini?');">Hapus</a>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php }
            } else {
                echo "<tr><td colspan='9' class='text-center'>Tidak ada data pesanan.</td></tr>";
            }
            ?>
            </tbody>
        </table>
        <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="update_status.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editStatusModalLabel">Edit Status Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Dikirim">Dikirim</option>
                                    <option value="Diterima">Diterima</option>
                                </select>
                            </div>
                            <input type="hidden" id="order-id" name="order_id">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Harga layanan berdasarkan berat barang (5000 per kg)
    const hargaPerKg = 5000;
    const layananHarga = {
        hemat: 0.8,   // Tambahan 10% untuk hemat
        normal: 1,     // Tidak ada perubahan harga untuk normal
        express: 2  // Tambahan 10% untuk express
    };

    // Event listener untuk menghitung harga
    document.getElementById('beratbarang').addEventListener('input', hitungHarga);
    document.getElementById('layanan').addEventListener('change', hitungHarga);

    function hitungHarga() {
        const beratBarang = parseFloat(document.getElementById('beratbarang').value);
        const layanan = document.getElementById('layanan').value;
        const harga = beratBarang * hargaPerKg * layananHarga[layanan];
        document.getElementById('harga').value = Math.round(harga);
    }

    // Tampilkan atau sembunyikan form kirim paket
    document.getElementById('send-package-btn').addEventListener('click', function () {
        const form = document.getElementById('send-package-form');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        
    });
   
    document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi Modal Bootstrap
    var editStatusModal = new bootstrap.Modal(document.getElementById('editStatusModal'));
    
    // Event listener untuk tombol edit
    document.querySelectorAll('.edit-status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const orderId = this.getAttribute('data-id');
            const currentStatus = this.getAttribute('data-status');
            
            // Set nilai pada form modal
            document.getElementById('order-id').value = orderId;
            document.getElementById('status').value = currentStatus;
            
            // Tampilkan modal
            editStatusModal.show();
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
