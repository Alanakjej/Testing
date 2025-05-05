<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];

    // Query untuk memperbarui data
    $query = "UPDATE users SET name = ?, email = ?, phone = ?, role = ? WHERE id = ?";

    // Persiapkan query
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("ssssi", $name, $email, $phone, $role, $id);

    // Eksekusi query
    if ($stmt->execute()) {
        header('Location: data_akun.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
