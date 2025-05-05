<?php
include('koneksi.php');

// Ambil data dari form
$order_id = $_POST['order_id'];
$status = $_POST['status'];

// Query untuk mengambil ID kurir secara acak
$sql_kurir = "SELECT id FROM users WHERE role = 'kurir' ORDER BY RAND() LIMIT 1";
$result_kurir = mysqli_query($koneksi, $sql_kurir);

// Periksa apakah ada kurir yang tersedia
if ($result_kurir && mysqli_num_rows($result_kurir) > 0) {
    $kurir = mysqli_fetch_assoc($result_kurir)['id'];
} else {
    echo "Tidak ada kurir yang tersedia.";
    exit;
}

// Update status dan kurir pada pesanan
$sql = "UPDATE orders SET status = '$status', kurir = '$kurir' WHERE id = '$order_id'";

if (mysqli_query($koneksi, $sql)) {
    header('Location: kurir.php'); // Redirect kembali ke halaman orders
    exit;
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>
