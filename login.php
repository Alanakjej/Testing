<?php
// Memulai session dan menghubungkan ke database
session_start();
include("koneksi.php");

$error = '';
$message = '';

// Jika ada pesan sukses di session, pindahkan ke variabel lokal dan hapus dari session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

// Pseudocode untuk proses login
/*
1. Jika form login dikirimkan:
   2. Ambil email dan password dari form.
   3. Cek apakah email ada dalam database.
   4. Jika email ditemukan, verifikasi password.
   5. Jika password valid, simpan informasi user dalam session.
   6. Redirect ke halaman dashboard.
   7. Jika email tidak ditemukan atau password salah, tampilkan pesan error.
*/

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = $_POST['password'];

    // Cek email di database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Menyimpan informasi user dalam session
            $_SESSION['username'] = $row['name']; // Menyimpan nama user
            $_SESSION['email'] = $row['email'];   // Menyimpan email user
            $_SESSION['phone'] = $row['phone'];   // Menyimpan nomor telepon user
            $_SESSION['role'] = $row['role'];     // Menyimpan role user
            $_SESSION['id'] = $row['id'];         // Menyimpan ID pengguna

            // Pengalihan ke halaman dashboard setelah login berhasil
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
}

// Pseudocode untuk proses registrasi
/*
1. Jika form registrasi dikirimkan:
   2. Ambil data nama, email, telepon, role, password, dan konfirmasi password dari form.
   3. Cek apakah email sudah terdaftar di database.
   4. Jika email sudah terdaftar, tampilkan pesan error.
   5. Jika password dan konfirmasi password cocok, enkripsi password.
   6. Simpan data user ke database.
   7. Tampilkan pesan sukses dan arahkan ke halaman login.
   8. Jika ada kesalahan dalam proses, tampilkan pesan error.
*/

// Proses registrasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['role']) && isset($_POST['password']) && isset($_POST['confirm_password'])) {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $phone = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Cek apakah email sudah ada
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($koneksi, $query);
    if (mysqli_num_rows($result) > 0) {
        $error = "Email sudah terdaftar.";
    } else {
        // Cek jika password dan konfirmasi password cocok
        if ($password === $confirm_password) {
            // Enkripsi password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Simpan data ke database
            $query = "INSERT INTO users (name, email, phone, role, password) VALUES ('$name', '$email', '$phone', '$role', '$hashed_password')";
            if (mysqli_query($koneksi, $query)) {
                $_SESSION['message'] = "Pendaftaran berhasil! Silakan login.";
                header("Location: dashboard.php"); // Redirect ke halaman login
                exit();
            } else {
                $error = "Terjadi kesalahan, coba lagi.";
            }
        } else {
            $error = "Password dan konfirmasi password tidak cocok.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Sign Up - Pengiriman Paket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #402f29;
        }
        .container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            height: 100%;
        }
        .left-section {
            width: 40%;
            background-color: #2B221E;

            color: white;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            border-radius: 6px;
        }
        .left-section img {
            max-width: 150px;
            height: auto;
        }
        .right-section {
            width: 50%;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            background-color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            border-radius: 6px;
        }
        .form-container {
            width: 100%;
            max-width: 450px;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .btn-dark {
            background-color: #2B221E;
            border: none;
        }
        .btn-dark:hover {
            background-color: #402f29;
        }
        .error-message {
            color: red;
        }
        .message {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Left Section (Logo and Title) -->
        <div class="left-section">
            <div class="text-center">
                <h1 class="fw-bold">Pengiriman Paket</h1>
                <p class="lead">Kirim paket dengan mudah dan aman</p>
            </div>
        </div>

        <!-- Right Section (Form Login and Registrasi) -->
        <div class="right-section">
            <!-- Login Form -->
            <div id="loginForm" class="form-container">
                <h2 class="text-center mb-4">Login to Your Account</h2>
                <?php if ($message) { ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php } ?>
                <?php if ($error) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="user" class="form-label">Username:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="user" class="form-control" placeholder="Enter email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Login now</button>
                    <div class="text-center my-3">
                        <span class="text-muted">OR</span>
                    </div>

                    <button type="button" class="btn btn-outline-dark w-100" onclick="toggleForms('registerForm')">Sign Up now</button>
                </form>
            </div>

            <!-- Sign Up Form -->
            <div id="registerForm" class="form-container" style="display: none;">
                <h2 class="text-center mb-4">Create a New Account</h2>
                <?php if ($error) { ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php } ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter your name" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" name="phone" id="phone" class="form-control" placeholder="Enter your phone number" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <select class="form-select" id="role" name="role" required>
                                <option value="pengirim">Pengirim</option>
                                <option value="kurir">Kurir</option>
                                <option value="admin">admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Create a password" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password:</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm password" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-dark w-100">Sign Up</button>

                    <div class="divider my-3">
                        <span class="text-muted">OR</span>
                    </div>

                    <button type="button" class="btn btn-outline-dark w-100" onclick="toggleForms('loginForm')">Already have an account? Login</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleForms(form) {
            if (form === 'registerForm') {
                document.getElementById('loginForm').style.display = 'none';
                document.getElementById('registerForm').style.display = 'block';
            } else {
                document.getElementById('loginForm').style.display = 'block';
                document.getElementById('registerForm').style.display = 'none';
            }
        }
    </script>
</body>
</html>
