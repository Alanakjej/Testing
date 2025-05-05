<?php
include 'koneksi.php';
    $id = $_POST['id'];
    $namapengirim = $_POST['namapengirim'];
    $alamatpengambilan = $_POST['alamatpengambilan'];
    $namakurir = $_POST['namakurir'];
    $namapenerima = $_POST['namapenerima'];
    $alamatpengantaran = $_POST['alamatpengantaran'];
    $layananpengiriman = $_POST['layananpengiriman'];
    $status = $_POST['status'];

    $result = mysqli_query($koneksi,$query = "UPDATE transaksi 
    SET namapengirim = '$namapengirim',
        alamatpengambilan = '$alamatpengambilan',
        namakurir = '$namakurir',
        namapenerima = '$namapenerima',
        alamatpengantaran = '$alamatpengantaran',
        layananpengiriman = '$layananpengiriman',
        status = '$status'
    WHERE id = '$id'");
    header("location: transaksi.php");
?>