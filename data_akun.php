<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
include 'koneksi.php';
$role = $_SESSION['role'];
$user_list = [];

$password_error = '';
if ($role === 'admin') {
    try {
        $result = $koneksi->query("SELECT id, name, email, phone, role FROM users ORDER BY id ASC");

        if (!$result) {
            throw new Exception("Error fetching users: " . $koneksi->error);
        }

        while ($row = $result->fetch_assoc()) {
            $user_list[] = $row;
        }
    } catch (Exception $e) {
        echo "Exception caught: " . $e->getMessage();
    }
}

// Memproses form jika ada update data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    // Query untuk memperbarui data
    $query = "UPDATE users SET name = ?, email = ?, phone = ? WHERE id = ?";
    
    try {
        // Persiapkan query
        $stmt = $koneksi->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $koneksi->error);
        }

        // Bind parameter
        $stmt->bind_param("sssi", $name, $email, $phone, $id);
        
        // Eksekusi query
        if ($stmt->execute()) {
            // Redirect setelah berhasil update
            header('Location: data_akun.php');
            exit();
        } else {
            throw new Exception("Error executing query: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo "Exception caught: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Order Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Styling for Sidebar and Table */
        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #2B221E;
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            padding-top: 20px;
            padding-bottom: 20px;
        }
        .sidebar .menu-item {
            margin-bottom: 25px; /* Tambah jarak antar menu */
            border-bottom: 1px solid #495057; /* Tambahkan garis sebagai pemisah */
        }
        .sidebar .menu-item a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 20px 15px; /* Perbesar padding */
            font-size: 1.6rem; /* Perbesar ukuran font */
        }
        .sidebar .menu-item:hover {
            background-color: #495057;
            border-radius: 6px;
        }
        .sidebar .logout {
            margin-top: auto; /* Letakkan di bagian paling bawah */
            padding: 20px 15px; /* Sesuaikan padding */
            font-size: 1.6rem; /* Ukuran font sesuai menu lainnya */
        }
        .sidebar .logout a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        .sidebar .logout a:hover {
            background-color: #495057;
            border-radius: 6px;
        }
        .main-content {
            margin-left: 250px;  /* Pastikan konten utama dimulai setelah sidebar */
            padding: 20px;  /* Perbesar padding agar tidak terlalu rapat */
            min-height: 100vh;    /* Konten memiliki tinggi minimal penuh */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;  /* Pastikan konten dimulai dari atas */
        }
        .user-table {
            margin-top: 10px;
            border-collapse: collapse;
            width: 100rem;
            background: #f8f9fa;
        }
        .user-table th, .user-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-size: 1.5rem;
        }
        .user-table th {
            background: #343a40;
            color: white;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div class="d-flex">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
        <ul class="nav flex-column ms-2 mb-2">
            <li class="nav-item menu-item">
                <a class="nav-link active" href="dashboard.php">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </li>

            <?php if ($role === 'pengirim' || $role === 'admin'): ?>
            <li class="nav-item menu-item">
                <a class="nav-link" href="orders.php">
                    <i class="fas fa-cart-plus me-2"></i>Orders
                </a>
            </li>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
            <li class="nav-item menu-item">
                <a class="nav-link" href="data_akun.php">
                    <i class="fas fa-users me-2"></i>Data Akun
                </a>
            </li>
            <?php endif; ?>

            <?php if ($role === 'kurir'): ?>
            <li class="nav-item menu-item">
                <a class="nav-link" href="kurir.php">
                    <i class="fas fa-truck me-2"></i>Pesanan Kurir
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <div class="logout">
            <a href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>


    <div class="main-content">
        <div class="mb-4">
            <h3>Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>
        </div>
        <div class="mb-4">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahDataModal"><i class="fas fa-plus-circle me-2"></i>Tambah Akun</button>
        </div>
        <!-- Modal untuk tambah akun -->
        <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="addAccountModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAccountModalLabel">Tambah Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="tambah_akun.php" method="POST">
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
                                    <span class="input-group-text"><i class="fas fa-user-tag me-2"></i></span>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="pengirim">Pengirim</option>
                                        <option value="kurir">Kurir</option>
                                        <option value="admin">Admin</option>
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
                        <!-- Pesan error ditampilkan di bawah form -->
                        <?php if (!empty($password_error)): ?>
                            <div class="text-danger mt-2">
                                <?php echo htmlspecialchars($password_error); ?>
                            </div>
                        <?php endif; ?>
                        </div>

                            <button type="submit" class="btn btn-primary">Buat Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal Edit Akun -->
        <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDataModalLabel"><i class="fas fa-edit me-2"></i>Edit Akun</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="ubah_akun.php" method="POST">
                            <input type="hidden" name="id" id="edit-id">
                            
                            <div class="mb-3">
                                <label for="edit-name" class="form-label"><i class="fas fa-user me-2"></i>Nama</label>
                                <input type="text" class="form-control" name="name" id="edit-name" placeholder="Nama Lengkap" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit-email" class="form-label"><i class="fas fa-envelope me-2"></i>Email</label>
                                <input type="email" class="form-control" name="email" id="edit-email" placeholder="Alamat Email" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit-phone" class="form-label"><i class="fas fa-phone me-2"></i>Telepon</label>
                                <input type="text" class="form-control" name="phone" id="edit-phone" placeholder="Nomor Telepon" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit-role" class="form-label"><i class="fas fa-user-tag me-2"></i>Role</label>
                                <select class="form-select" name="role" id="edit-role" placeholder="" required>
                                    <option value="pengirim">Pengirim</option>
                                    <option value="kurir">Kurir</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="edit-password" class="form-label"><i class="fas fa-lock me-2"></i>Password</label>
                                <input type="password" class="form-control" name="password" id="edit-password" placeholder="Kosongkan jika tidak diubah">
                            </div>
                            
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Simpan Perubahan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div>
            <table class="user-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Telepon</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    include 'koneksi.php';
                    $query = mysqli_query($koneksi, "SELECT * FROM users");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                    ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $data['name']; ?></td>
                        <td><?php echo $data['email']; ?></td>
                        <td><?php echo $data['phone']; ?></td>
                        <td><?php echo $data['role']; ?></td>
                        <td>
                            <button class="btn btn-success btn-sm me-1 edit-button"
                                data-bs-toggle="modal"
                                data-bs-target="#editDataModal"
                                data-id="<?php echo $data['id']; ?>"
                                data-name="<?php echo $data['name']; ?>"
                                data-email="<?php echo $data['email']; ?>"
                                data-phone="<?php echo $data['phone']; ?>"
                                data-role="<?php echo $data['role']; ?>">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <a href="hapus_akun.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editButtons = document.querySelectorAll('.edit-button');
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const email = this.getAttribute('data-email');
                    const phone = this.getAttribute('data-phone');
                    const role = this.getAttribute('data-role');

                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-name').value = name;
                    document.getElementById('edit-email').value = email;
                    document.getElementById('edit-phone').value = phone;
                    document.getElementById('edit-role').value = role;
                });
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
