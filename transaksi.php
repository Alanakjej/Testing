<?php 
session_start();
if (isset($_SESSION['ussername'])) {
  header("location: login.php");
  exit; 
}
$role = $_SESSION['role'];

?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="admin.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>ADMINISTRATOR</title>
    <style>
     .navbar {
        background-color: #2B221E;
        color: aliceblue;
    }

        .nav-link:hover {
            background-color: gold;
            color: white !important;
        }
        #layananpengiriman {
            display  : flex;
            gap: 6px;
            align-items: center;
        }
        #edit-layananpengiriman {
          display  : flex;
            gap: 6px;
            align-items: center;
        }
        .container-fluid {
    z-index: 1;
   height: 6vh;
  }
  .sidebar {
            height: 100vh;
            background-color: #2B221E;
          
        }
        .nav-item-logout {
            padding: 16px;
        }
      
  </style>
</head>
<body>
  
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light  fixed-top">
  <div class="container-fluid">
    <h1>selamat datang, <?php echo htmlspecialchars($_SESSION['username']);?>!</h1>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <div class="ms-auto d-flex align-items-center">
            <li class="nav-item-logout">
          <a class="nav-link active text-white" href="logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a><hr class="bg-secondary">
        </li>
          </div>
        </div>
      </div>
    </div>
  </nav>
  <div class="row g-0 ">
      <!-- Sidebar -->
      <div class="col-md-2 sidebar mt-5 pt-5">
      <ul class="nav flex-column ms-3 mb-3">
        <li class="nav-item">
          <a class="nav-link active text-white" href="dashboard.php"><i class="fas fa-home me-2"></i>Dashboard</a><hr class="bg-secondary">
        </li>

        <?php if ($role === 'pengirim' ||  $role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="pengirim.php"><i class="fas fa-user me-2"></i>Daftar Pengirim</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>

        <?php if ($role === 'kurir' ||  $role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="kurir.php"><i class="fas fa-car me-2"></i>Daftar Kurir</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>

        <?php if ($role === 'penerima' ||  $role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="penerima.php"><i class="fas fa-user me-2"></i>Daftar Penerima</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>
        <?php if ($role === 'transaksi' ||  $role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="transaksi.php"><i class="fas fa-cart-plus me-2"></i>Daftar Transaksi</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>
        <?php if ($role === 'paket' ||  $role === 'admin'): ?>
        <li class="nav-item">
          <a class="nav-link text-white" href="paket.php"><i class="fas fa-box me-2"></i>Daftar paket</a><hr class="bg-secondary">
        </li>
        <?php endif; ?>
      </ul>
    </div>

        <div class="col-md-10 p-5 pt-5 ">
            <h3><i class="fas fa-user-graduate mb-2"></i> Daftar Transaksi</h3>
            <hr>
            <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#tambahDataModal">
                <i class="fas fa-plus-circle me-2"></i>TAMBAH DATA Transaksi
            </button>
            <div class="modal fade" id="tambahDataModal" tabindex="-1" aria-labelledby="tambahDataLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahDataLabel">Tambah Data Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="tambah_transaksi.php" method="POST">
                            <div class="mb-3">
                                    <input type="hidden" class="form-control" id="id" name="id" required>
                                </div>
                                <div class="mb-3">
                                    <label for="namapengirim" class="form-label">Nama Pengirim</label>
                                    <input type="text" class="form-control" id="namapengirim" name="namapengirim" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamatpengambilan" class="form-label">Alamat Pengambilan</label>
                                    <input type="text" class="form-control" id="alamatpengambilan" name="alamatpengambilan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="namakurir" class="form-label">Nama Kurir</label>
                                    <input type="text" class="form-control" id="namakurir" name="namakurir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="namapenerima" class="form-label">Nama Penerima</label>
                                    <input type="text" class="form-control" id="namapenerima" name="namapenerima" required>
                                </div> <div class="mb-3">
                                    <label for="alamatpengantaran" class="form-label">Alamat Pengantaran</label>
                                    <input type="text" class="form-control" id="alamatpengantaran" name="alamatpengantaran" required>
                                </div>
                                 <div class="mb-3">
                                    <label for="layananpengiriman" class="form-label">Layanan Pengiriman</label>
                                    <div id="layananpengiriman">
                                     <div class="form-check">
                                        <input class="form-check-input" type="radio" name="layananpengiriman" id="Hemat" value="Hemat"required>
                                        <label class="form-check-label" for="Hemat">Hemat</label>
                                </div>
                                     <div class="form-check">
                                        <input class="form-check-input" type="radio" name="layananpengiriman" id="regular" value="regular"required>
                                        <label class="form-check-label" for="regular">Regular</label>
                                </div>
                                <div class="form-check">
                                        <input class="form-check-input" type="radio" name="layananpengiriman" id="express" value="express"required>
                                        <label class="form-check-label" for="express">Express</label>
                                </div>
                                    </div>
                              </div>
                              <div class="mb-3 ">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status" required>
                                      <option value="" disabled 
                                      selected>Pilih status</option>
                                        <option value="pengambilan">Pengambilan</option>
                                        <option value="Dalam perjalanan">Dalam Perjalanan</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Edit Data -->
            <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataLabel" ariahidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editDataLabel">Edit Order</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                data-bs-target="#editDataModal"></button>
                        </div>
                        <div class="modal-body">
                            <form action="ubah_transaksi.php" method="POST">
                            <input type="hidden" id="edit-id" name="id">
                            <div class="mb-3">
                                <label for="edit-namapengirim" class="form-label">Nama Pengirim</label>
                                <input type="text" class="form-control" id="edit-namapengirim" name="namapengirim" required>
                            </div>
                                <div class="mb-3">
                                    <label for="edit-alamatpengambilan" class="form-label">Alamat Pengambilan</label>
                                    <input type="text" class="form-control" id="edit-alamatpengambilan" name="alamatpengambilan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-namakurir" class="form-label">Nama Kurir</label>
                                    <input type="text" class="form-control" id="edit-namakurir" name="namakurir" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-namapenerima" class="form-label">Nama Penerima</label>
                                    <input type="text" class="form-control" id="edit-namapenerima" name="namapenerima" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-alamatpengantaran" class="form-label">Alamat Pengantaran</label>
                                    <input type="text" class="form-control" id="edit-alamatpengantaran" name="alamatpengantaran" required>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-layananpengiriman" class="form-label">Layanan Pengiriman</label>
                                    <div id="edit-layananpengiriman">
                                     <div class="form-check">
                                        <input class="form-check-input" type="radio" name="layananpengiriman" id="Hemat" value="Hemat"required>
                                        <label class="form-check-label" for="Hemat">Hemat</label>
                                </div>
                                     <div class="form-check">
                                        <input class="form-check-input" type="radio" name="layananpengiriman" id="regular" value="regular"required>
                                        <label class="form-check-label" for="regular">Regular</label>
                                </div>
                                <div class="form-check">
                                        <input class="form-check-input" type="radio" name="layananpengiriman" id="express" value="express"required>
                                        <label class="form-check-label" for="express">Express</label>
                                </div>
                                    </div>
                              </div>
                              <div class="mb-3 ">
                                    <label for="edit-status" class="form-label">Status</label>
                                    <select class="form-control" id="edit-status" name="status" required>
                                      <option value="" disabled 
                                      selected>Pilih status</option>
                                        <option value="pengambilan">Pengambilan</option>
                                        <option value="Dalam perjalanan">Dalam Perjalanan</option>
                                        <option value="Selesai">Selesai</option>
                                        <option value="Dibatalkan">Dibatalkan</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">NO</th>
                        <th scope="col">ID</th>
                        <th scope="col">NOMOR PENGIRIM</th>
                        <th scope="col">ALAMAT PENGAMBILAN</th>
                        <th scope="col">NAMA KURIR</th>
                        <th scope="col">NAMA PENERIMA</th>
                        <th scope="col">ALAMAT PENGIRIMAN</th>
                        <th scope="col">LAYANAN PENGIRIMAN</th>
                        <th center scope="col">STATUS</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    include 'koneksi.php';
                    $query = mysqli_query($koneksi, "SELECT * FROM transaksi");
                    $no = 1;
                    while ($data = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $data['id']; ?></td>
                            <td><?php echo $data['namapengirim']; ?></td>
                            <td><?php echo $data['alamatpengambilan']; ?></td>
                            <td><?php echo $data['namakurir']; ?></td>
                            <td><?php echo $data['namapenerima']; ?></td>
                            <td><?php echo $data['alamatpengantaran']; ?></td>
                            <td><?php echo $data['layananpengiriman']; ?></td>
                            <td><?php echo $data['status']; ?></td>

                            <td>
                                <button class="btn btn-success btn-sm me-1 edit-button"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editDataModal"
                                    data-id="<?php echo $data['id']; ?>"
                                    data-namapengirim="<?php echo $data['namapengirim']; ?>"
                                    data-alamatpengambilan="<?php echo $data['alamatpengambilan']; ?>"
                                    data-namakurir="<?php echo $data['namakurir']; ?>"
                                    data-namapenerima="<?php echo $data['namapenerima']; ?>"
                                    data-alamatpengantaran="<?php echo $data['alamatpengantaran']; ?>"
                                    data-layananpengiriman="<?php echo $data['layananpengiriman']; ?>"
                                    data-status="<?php echo $data['status']; ?>">
                                    <i class="fas fa-edit"></i>edit
                                </button>
                                <a href="hapus_transaksi.php?id=<?php echo urlencode($data['id']); ?>" 
   class="btn btn-danger btn-sm"
   onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
    <i class="fas fa-trash-alt"></i>Delete
</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const editButtons = document.querySelectorAll('.edit-button');
                editButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        // Ambil data dari atribut data-*
                        const id = this.getAttribute('data-id');
                        const namapengirim = this.getAttribute('data-namapengirim');
                        const alamatpengambilan = this.getAttribute('data-alamatpengambilan');
                        const namakurir = this.getAttribute('data-namakurir');
                        const namapenerima = this.getAttribute('data-namapenerima');
                        const alamatpengantaran = this.getAttribute('data-alamatpengantaran');
                        const layananpengiriman = this.getAttribute('data-layananpengiriman');
                        const status = this.getAttribute('data-status');


                        // Masukkan data ke dalam form modal edit
                        document.getElementById('edit-id').value = id;
                        document.getElementById('edit-namapengirim').value = namapengirim;
                        document.getElementById('edit-alamatpengambilan').value = alamatpengambilan;
                        document.getElementById('edit-namakurir').value = namakurir;
                        document.getElementById('edit-namapenerima').value = namapenerima;
                        document.getElementById('edit-alamatpengantaran').value = alamatpengantaran;
                        document.getElementById('edit-layananpengiriman').value = layananpengiriman;
                        document.getElementById('edit-status').value = status;

                    });
                });
            });
        </script>

</body>

</html>