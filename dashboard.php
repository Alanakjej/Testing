<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
$role = $_SESSION['role'];

// Koneksi ke database
include('koneksi.php');

// Query untuk menghitung total pesanan dan total pembayaran
$totalOrdersQuery = "SELECT COUNT(*) AS total_orders FROM orders WHERE status != 'cancelled'";  // Filter jika ada status yang dibatalkan
$totalOrdersResult = mysqli_query($koneksi, $totalOrdersQuery);
$totalOrders = mysqli_fetch_assoc($totalOrdersResult)['total_orders'];

$totalPaidQuery = "SELECT SUM(harga) AS total_paid FROM orders WHERE status != 'cancelled'"; // Filter jika ada status yang dibatalkan
$totalPaidResult = mysqli_query($koneksi, $totalPaidQuery);
$totalPaid = mysqli_fetch_assoc($totalPaidResult)['total_paid'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Order Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Sidebar Style */
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
            width: calc(100% - 270px);
            padding: 20px;
        }

        .stats-summary {
            display: flex;
            gap: 20px;
            justify-content: space-between;
        }

        .stat-card {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            flex: 1;
            cursor: pointer;
            text-decoration: none; /* Menghilangkan underline */
            color: black; /* Mengatur warna teks menjadi hitam */
        }

        .stat-card:hover {
            background-color: #e9ecef;
        }

        #lineChart {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
        }
    </style>
</head>
<body>
<div class="row g-0">
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
        <div class="mb-2">
            <h3>Selamat Datang, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
        </div>
        <!-- Statistics Section -->
        <div class="stats-summary">
            <a href="orders.php" class="stat-card">
                <h3><?php echo $totalOrders; ?></h3>
                <p>Total Orders</p>
            </a>
            <a href="orders.php" class="stat-card">
                <h3>Rp. <?php echo number_format($totalPaid, 0, ',', '.'); ?></h3>
                <p>Total Paid</p>
            </a>
        </div>

        <!-- Line Chart -->
        <div id="lineChart">
            <canvas id="myLineChart"></canvas>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myLineChart').getContext('2d');
        const myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Total Orders',
                    data: [120, 200, 300, 450, 500, 600],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderWidth: 2,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Orders'
                        },
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
