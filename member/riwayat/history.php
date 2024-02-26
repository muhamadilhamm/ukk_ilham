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
$nisn = $_SESSION['nisn'];
$statusArray = [0, 1, 2];
$statusString = implode(',', $statusArray);  // Mengubah array menjadi string terpisah koma
// Assuming $id is the specific value you want to match
$peminjaman = queryReadData("SELECT * FROM peminjaman
INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
INNER JOIN member ON peminjaman.nisn = member.nisn
INNER JOIN user ON peminjaman.id_user = user.id
WHERE peminjaman.nisn = '$nisn' AND status = '3' 
order by peminjaman.id DESC");

//search Peminjaman
if (isset($_POST["search"])) {
  $peminjaman = searchHistory($_POST["keyword"]);
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
  margin-top: 60px; /* Adjust the margin-top based on your navbar height */
}

#content {
  margin-top: 20px; /* Adjust the margin-top for spacing below the navbar */
}

.navbar {
  margin-bottom: 20px; /* Adjust the margin-bottom for spacing below the navbar */
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
    max-height: 400px;
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
            <a class="nav-link" href="../peminjaman/daftar_pinjam.php">Daftar Pinjam</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="history.php">History</a>
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
        <h2 class="mt-2">History</h2>
      </div>
      <div class="card-body scrollable-card">
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
              <th>Nama Petugas</th>
              <th>Tgl. Pinjam</th>
              <th>Tgl. Selesai</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $no = 1; // Nomor urut dimulai dari 1
            if (isset($peminjaman) && is_array($peminjaman) && count($peminjaman) > 0) {
              foreach ($peminjaman as $item) :
            ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><img src="../../assets/imgDB/<?= $item['cover']; ?>" alt="" width="70px" height="100px" style="border-radius: 5px;"></td>
                  <td><?= $item['judul']; ?></td>
                  <td><?= $item['username']; ?></td>
                  <td><?= $item['tgl_pinjam']; ?></td>
                  <td><?= $item['tgl_kembali']; ?></td>
                </tr>
            <?php endforeach;
            } else {
              echo '<tr><td colspan="7">Tidak ada data buku yang anda pinjam.</td></tr>';
            } ?>
            <!-- Tambahkan baris data lainnya sesuai kebutuhan -->
          </tbody>
        </table>
      </div>
    </div>

  </main>
  </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <!-- /.content-wrapper -->

</body>

</html>