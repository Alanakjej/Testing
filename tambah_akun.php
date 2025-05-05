<?php
include 'koneksi.php';

$password_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    if ($password !== $confirm_password) {
        $password_error = "Password dan konfirmasi password tidak cocok.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Simpan data ke database
        $query = "INSERT INTO users (name, email, phone, role, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("sssss", $name, $email, $phone, $role, $hashed_password);

        if ($stmt->execute()) {
            header('Location: data_akun.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
?>
