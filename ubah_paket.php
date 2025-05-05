<?php
include 'koneksi.php';
    $id = $_POST['id'];
    $deskripsi = $_POST['deskripsi'];
    $berat = $_POST['berat'];
    $tujuan = $_POST['tujuan'];
    $kurir = $_POST['kurir'];

    $result = mysqli_query($koneksi,"UPDATE paket SET deskripsi= '$deskripsi',berat= '$berat', tujuan= '$tujuan',kurir= '$kurir' WHERE id= '$id'");
    header("location: paket.php");
?>