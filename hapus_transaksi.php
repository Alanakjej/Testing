<?php
require_once 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $query = "DELETE FROM transaksi WHERE id = ?";
    
    if ($stmt = mysqli_prepare($koneksi, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $id);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Data Berhasil Dihapus');
                    window.location.href = 'transaksi.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal Menghapus Data: " . mysqli_error($koneksi) . "');
                    window.location.href = 'transaksi.php';
                  </script>";
        }
        
        mysqli_stmt_close($stmt);
    }
} else {
    echo "<script>
            alert('ID tidak ditemukan');
            window.location.href = 'transaksi.php';
          </script>";
}

mysqli_close($koneksi);
?>