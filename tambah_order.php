<?php
include('koneksi.php');

// Cek apakah form dikirim menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data yang dikirimkan dari form
    $pengirim = mysqli_real_escape_string($koneksi, $_POST['pengirim']);
    $nomorpengirim = mysqli_real_escape_string($koneksi, $_POST['nomorpengirim']);
    $alamatpengirim = mysqli_real_escape_string($koneksi, $_POST['alamatpengirim']);
    $penerima = mysqli_real_escape_string($koneksi, $_POST['penerima']);
    $nomorpenerima = mysqli_real_escape_string($koneksi, $_POST['nomorpenerima']);
    $alamatpenerima = mysqli_real_escape_string($koneksi, $_POST['alamatpenerima']);
    $beratbarang = mysqli_real_escape_string($koneksi, $_POST['beratbarang']);
    $layanan = mysqli_real_escape_string($koneksi, $_POST['layanan']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);

    // Validasi data pengirim (cek apakah ID pengirim ada di database)
    $cekPengirim = "SELECT id FROM users WHERE name = '$pengirim' AND role = 'pengirim'";
    $resultPengirim = mysqli_query($koneksi, $cekPengirim);

    if (mysqli_num_rows($resultPengirim) == 0) {
        echo "Pengirim tidak ditemukan di database.";
        exit();
    }

    // Ambil daftar kurir yang ada di database
    $queryKurir = "SELECT id FROM users WHERE role = 'kurir'";
    $resultKurir = mysqli_query($koneksi, $queryKurir);

    if (mysqli_num_rows($resultKurir) > 0) {
        // Masukkan semua ID kurir ke dalam array
        $kurirs = [];
        while ($row = mysqli_fetch_assoc($resultKurir)) {
            $kurirs[] = $row['id'];
        }

        // Pilih kurir secara acak
        $kurir = $kurirs[array_rand($kurirs)];
    } else {
        echo "Tidak ada kurir yang tersedia.";
        exit();
    }

    // Set status awal pesanan ke 'diproses'
    $status = 'diproses';

    // Query untuk menyimpan pesanan ke dalam database
    $sql = "INSERT INTO orders (pengirim, nomorpengirim, alamatpengirim, penerima, nomorpenerima, alamatpenerima, beratbarang, layanan, harga, kurir, status) 
            VALUES ('$pengirim', '$nomorpengirim', '$alamatpengirim', '$penerima', '$nomorpenerima', '$alamatpenerima', '$beratbarang', '$layanan', '$harga', '$kurir', '$status')";

    // Eksekusi query dan periksa apakah berhasil
    if (mysqli_query($koneksi, $sql)) {
        // Redirect ke halaman daftar pesanan setelah berhasil
        header('Location: orders.php');
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}

?>

<!-- Formulir untuk Menambah Order -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Order</title>
</head>
<body>
    <h2>Formulir Tambah Order</h2>
    <form action="tambah_order.php" method="POST">
        <label for="pengirim">Pengirim (ID Pengirim):</label><br>
        <input type="text" name="pengirim" required><br>

        <label for="nomorpengirim">Nomor Pengirim:</label><br>
        <input type="text" name="nomorpengirim" required><br>

        <label for="alamatpengirim">Alamat Pengirim:</label><br>
        <input type="text" name="alamatpengirim" required><br>

        <label for="penerima">Penerima:</label><br>
        <input type="text" name="penerima" required><br>

        <label for="nomorpenerima">Nomor Penerima:</label><br>
        <input type="text" name="nomorpenerima" required><br>

        <label for="alamatpenerima">Alamat Penerima:</label><br>
        <input type="text" name="alamatpenerima" required><br>

        <label for="beratbarang">Berat Barang (kg):</label><br>
        <input type="number" name="beratbarang" required><br>

        <label for="layanan">Layanan:</label><br>
        <select name="layanan" required>
            <option value="hemat">Hemat</option>
            <option value="normal">Normal</option>
            <option value="express">Express</option>
        </select><br>

        <label for="harga">Harga:</label><br>
        <input type="text" name="harga" required><br>

        <input type="submit" value="Buat Order">
    </form>
</body>
</html>
