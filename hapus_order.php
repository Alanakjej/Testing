<?php
require_once 'koneksi.php';

if (isset($_GET['id'])) {
    $order_id = $_GET['id']; // Mengambil ID dari parameter URL
    
    // Query untuk menghapus order berdasarkan ID
    $query = "DELETE FROM orders WHERE id = ?";
    
    if ($stmt = mysqli_prepare($koneksi, $query)) {
        mysqli_stmt_bind_param($stmt, 'i', $order_id); // Menggunakan 'i' untuk integer
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Order Berhasil Dihapus');
                    window.location.href = 'orders.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal Menghapus Order: " . mysqli_error($koneksi) . "');
                    window.location.href = 'orders.php';
                  </script>";
        }
        
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<script>
            alert('ID tidak ditemukan');
            window.location.href = 'orders.php';
          </script>";
}

mysqli_close($koneksi);
?>