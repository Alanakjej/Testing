// Mengecek apakah form dikirim melalui metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $phone = mysqli_real_escape_string($koneksi, $_POST['phone']);
    $role = $_POST['role'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi input form
    if (empty($name) || empty($email) || empty($phone) || empty($role) || empty($password) || empty($confirm_password)) {
        echo "Semua field harus diisi.";
        exit();
    }

    // Validasi email harus mengandung @gmail.com
    if (strpos($email, '@gmail.com') === false) {
        echo "Email harus beralamat @gmail.com.";
        exit();
    }

    // Mengecek apakah password dan konfirmasi password cocok
    if ($password !== $confirm_password) {
        echo "Password dan konfirmasi password tidak cocok.";
        exit();
    }

    // Mengecek apakah email sudah terdaftar di database
    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
    $check_email_result = mysqli_query($koneksi, $check_email_query);

    if (mysqli_num_rows($check_email_result) > 0) {
        echo "Email sudah terdaftar. Gunakan email lain.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Menyimpan data ke database
    $insert_query = "INSERT INTO users (name, email, phone, role, password) VALUES ('$name', '$email', '$phone', '$role', '$hashed_password')";
    
    if (mysqli_query($koneksi, $insert_query)) {
        // Mengarahkan ke halaman login setelah berhasil mendaftar
        header("Location: login.php");
        exit();
    } else {
        echo "Terjadi kesalahan: " . mysqli_error($koneksi);
        exit();
    }
} else {
    echo "Permintaan tidak valid.";
}
