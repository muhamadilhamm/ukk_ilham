<?php
// Start the session
session_start();

// Check if 'nama' is set in the session, if not, redirect to the login page
if (!isset($_SESSION['password'])) {
  header("Location: ../../login.php");
  exit();
}

if (!isset($_SESSION['nisn'])) {
  header("Location: ../../login.php");
  exit();
}

require "../../config/config.php";
pengembalian();
$nisn = $_SESSION['nisn'];
$statusArray = [0, 1, 2];
$statusString = implode(',', $statusArray);  // Mengubah array menjadi string terpisah koma
// Assuming $id is the specific value you want to match
$peminjaman = queryReadData("SELECT peminjaman.id AS peminjaman_id,
buku.cover AS cover,
buku.id_buku AS id_buku, 
buku.judul AS judul,
member.nisn AS nisn, 
user.username AS username,
user.no_telp AS telp,
peminjaman.tgl_pinjam AS tgl_pinjam,
peminjaman.tgl_kembali AS tgl_kembali,
peminjaman.harga AS harga,
peminjaman.status AS status
FROM peminjaman
INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
INNER JOIN member ON peminjaman.nisn = member.nisn
INNER JOIN user ON peminjaman.id_user = user.id
WHERE peminjaman.nisn = '$nisn' AND peminjaman.status IN ($statusString)
order by status ASC");

//search Peminjaman
if (isset($_POST["search"])) {
  $peminjaman = searchPinjamMember($_POST["keyword"]);
}


?>


<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Readbooks.com</title>
  <link rel="icon" href="../../assets/iconblack.png" type="image/png">
</head>
<style>
  body {
    background-image: url(../../assets/perpus.jpg);
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    background-attachment: relative;
    margin-top: 60px;
    /* Adjust the margin-top based on your navbar height */
  }

  #content {
    margin-top: 20px;
    /* Adjust the margin-top for spacing below the navbar */
  }

  .navbar {
    margin-bottom: 20px;
    /* Adjust the margin-bottom for spacing below the navbar */
  }
</style>
<style>
  table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
  }

  th,
  td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: center;
  }

  th {
    background-color: #f2f2f2;
  }

  .scrollable-card {
    max-height: 450px;
    /* Atur tinggi maksimum sesuai kebutuhan */
    overflow-y: auto;
    /* Aktifkan scrolling vertikal jika kontennya melebihi tinggi maksimum */
  }
</style>

<body>

  <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <div class="dropdown" data-bs-theme="dark">
        <button class="btn btn-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../../assets/memberLogo.png" alt="memberLogo" width="40px">
        </button>
        <ul style="margin-right: -7rem;" class="dropdown-menu position-absolute mt-2 p-2">
          <li>
            <a class="dropdown-item text-center" href="#">
              <img src="../../assets/memberLogo.png" alt="adminLogo" width="30px">
            </a>
          </li>
          <li>
            <a class="dropdown-item text-center" href="#"> <span class="text-capitalize"><?= $_SESSION['nama']; ?></span></a>
            <a class="dropdown-item text-center mb-2" href="#">Siswa</a>
          </li>
          <li>
            <a class="dropdown-item text-center p-2 bg-danger text-light rounded" href="../logout.php" onclick="return confirm('Apakah anda ingin keluar dari aplikasi ini?');">Logout <i class="fa-solid fa-right-to-bracket"></i></a>
          </li>
        </ul>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mynavbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="../dashboard.php">Daftar Buku</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="daftar_pinjam.php">Daftar Pinjam</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../riwayat/history.php">History</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Main Content Area -->
      <main id="content" class="col-md-12">
        <!-- Content goes here -->
        <div class="card">
          <div class="card-header bg-dark text-white">
            <h2 class="mt-2">Data Peminjaman Buku Anda</h2>
          </div>
          <div class="card-body scrollable-card">
            <?php
            $alertDisplayed = false;

            foreach ($peminjaman as $item) :
              if ($item['status'] == 0 && !$alertDisplayed) {
            ?>
                <div class="alert alert-danger">
                  <strong>Pemberitahuan!</strong> Silahkan kirim bukti transaksi ke nomor yang tertera.
                </div>
            <?php
                $alertDisplayed = true; // Set variabel ini menjadi true agar alert hanya ditampilkan sekali.
              }
            endforeach;
            ?>

            <form class="d-flex" action="" method="post">
              <div style="display: flex;">
                <input class="form-control m-2" type="text" name="keyword" id="keyword" placeholder="Search">
                <button class="btn btn-primary m-2" type="submit" name="search">Search</button>
              </div>
            </form>
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cover</th>
                  <th>Judul</th>
                  <th>Petugas</th>
                  <th>No. Telp</th>
                  <th>Biaya Pembayaran</th>
                  <th>Tgl. Pinjam</th>
                  <th>Tgl. Selesai</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 1; // Nomor urut dimulai dari 1
                $totalHarga = 0; // Inisialisasi total harga
                if (isset($peminjaman) && is_array($peminjaman) && count($peminjaman) > 0) {
                  foreach ($peminjaman as $item) :
                     // Menghapus karakter non-angka seperti "Rp." dan "."
                     $hargaBuku = floatval(preg_replace("/[^0-9]/", "", $item['harga']));
                     $totalHarga += $hargaBuku; // Menambahkan harga buku ke total
                ?>
                    <tr>
                      <td><?= $no++; ?></td>
                      <td><img src="../../assets/imgDB/<?= $item['cover']; ?>" alt="" width="70px" height="100px" style="border-radius: 5px;"></td>
                      <td><?= $item['judul']; ?></td>
                      <td><?= $item['username']; ?></td>
                      <td><?= $item['telp']; ?></td>
                      <td><?= $item['harga']; ?></td>
                      <td><?= $item['tgl_pinjam']; ?></td>
                      <td><?= $item['tgl_kembali']; ?></td>
                      <td>
                        <?php
                        if ($item['status'] == '0') {
                          echo '<b class="badge bg-warning">Menunggu Persetujuan</b>';
                          ?>
                          <div>
                          <a href="batalPinjam.php?id=<?= $item['peminjaman_id']; ?>" class="btn btn-danger mt-2" style="width:100px;" onclick="return confirm('Apakah anda ingin membatalkan peminjaman ini?');">Batalkan</a>
                          </div>
                          <?php
                        } elseif ($item['status'] == '1') {
                        ?>
                          <div>
                            <a href="baca_buku.php?id_buku=<?= $item['id_buku']; ?>" class="btn btn-primary"><i class="fas fa-book-open"></i> Baca</a>
                          </div>
                          <div>
                            <a href="kembalikan.php?id=<?= $item['peminjaman_id']; ?>" class="btn btn-warning mt-2" onclick="return confirm('Apakah anda yakin ingin mengembalikan buku ini?');"><i class="fas fa-outdent"></i> Kembalikan</a>
                          </div>
                        <?php
                        }
                        if ($item['status'] == '2') {
                          echo '<b class="badge bg-danger">Tidak Disetujui</b>';
                        }
                        ?>

                      </td>
                    </tr>
                <?php endforeach;
                } else {
                  echo '<tr><td colspan="9">Tidak ada data buku yang anda pinjam.</td></tr>';
                } ?>
                <!-- Tambahkan baris data lainnya sesuai kebutuhan -->
              </tbody>
            </table>
          </div>
          <div class="card-footer">
            <strong>Total Harga: </strong>
            Rp. <?= number_format($totalHarga, 0, ',', '.'); ?>
          </div>
        </div>


      </main>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <!-- /.content-wrapper -->

</body>

</html>