<?php
session_start();
include('koneksi.php');

// Pastikan pengguna yang login adalah kurir
if ($_SESSION['role'] != 'kurir') {
    header('Location: login.php');
    exit();
}

$kurir_id = $_SESSION['id']; // ID kurir yang disimpan di session
$kurir = $_SESSION['username']; // Menyimpan username kurir untuk ditampilkan
$role = $_SESSION['role']; // Menyimpan role pengguna dari session

// Ambil semua pesanan yang hanya ditugaskan ke kurir yang login
$sql_my_orders = "SELECT * FROM orders WHERE kurir = '$kurir_id'"; // Filter berdasarkan kurir_id
$result_my_orders = mysqli_query($koneksi, $sql_my_orders);

// Cek error pada query
if (!$result_my_orders) {
    echo "Query Error: " . mysqli_error($koneksi);
    exit();
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
            position: fixed;
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .sidebar .menu-item {
            margin-bottom: 25px;
            border-bottom: 1px solid #495057;
        }
        .sidebar .menu-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 20px 15px;
            font-size: 1.6rem;
        }
        .sidebar .menu-item:hover {
            background-color: #495057;
            border-radius: 6px;
        }
        .sidebar .logout {
            margin-top: auto;
            padding: 20px 15px;
            font-size: 1.6rem;
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
    </style>
</head>
<body>
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
    <div class="main-content">
        <h3>Selamat Datang, <?php echo htmlspecialchars($kurir); ?>!</h3>
        <div class="modal fade" id="editStatusModal" tabindex="-1" aria-labelledby="editStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="update_status.php" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editStatusModalLabel">Edit Status Pesanan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="order-id" name="order_id">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="Diproses">Diproses</option>
                                    <option value="Dikirim">Dikirim</option>
                                    <option value="Diterima">Diterima</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
            <tbody>
<?php
$sql = "
SELECT 
    o.*, 
    COALESCE(u1.name, 'Belum Ditentukan') AS pengirim, 
    COALESCE(u2.name, 'Belum Ditentukan') AS kurir 
FROM orders o
LEFT JOIN users u1 ON o.pengirim = u1.id
LEFT JOIN users u2 ON o.kurir = u2.id
WHERE o.kurir = '$kurir_id'"; // Filter berdasarkan kurir yang login

// Query untuk mengambil data orders dan nama pengirim dan kurir
$result = mysqli_query($koneksi, $sql) or die("Error: " . mysqli_error($koneksi));

if (mysqli_num_rows($result) > 0):
    $no = 1;
    while ($order = mysqli_fetch_assoc($result)):
?>
    <tr>
        <td><?php echo $no++; ?></td>
        <!-- Menampilkan nama pengirim (dari alias pengirim) -->
        <td><?php echo htmlspecialchars($order['pengirim']); ?></td>  
        <td><?php echo htmlspecialchars($order['nomorpengirim']); ?></td>
        <td><?php echo htmlspecialchars($order['alamatpengirim']); ?></td>
        <td><?php echo htmlspecialchars($order['penerima']); ?></td>
        <td><?php echo htmlspecialchars($order['nomorpenerima']); ?></td>
        <td><?php echo htmlspecialchars($order['alamatpenerima']); ?></td>
        <td><?php echo htmlspecialchars($order['layanan']); ?></td>
        <!-- Menampilkan nama kurir (dari alias kurir) -->
        <td><?php echo htmlspecialchars($order['kurir']); ?></td>  
        <td><?php echo number_format($order['harga'], 0, ',', '.'); ?></td>
        <td><?php echo htmlspecialchars($order['status']); ?></td>
        <td>
            <button class="btn btn-warning btn-sm edit-status-btn" 
                    data-id="<?= htmlspecialchars($order['id']); ?>" 
                    data-status="<?= htmlspecialchars($order['status']); ?>">
                Edit Status
            </button>
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
    <script>
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
</body>
</html>
