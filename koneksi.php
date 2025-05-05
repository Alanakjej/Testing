<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "nonaka"; // Nama Database
// melakukan koneksi ke db
$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    echo "Gagal konek: " . mysqli_error($koneksi); // Menampilkan pesan error
    die(); // Menghentikan eksekusi jika koneksi gagal
}
?>
