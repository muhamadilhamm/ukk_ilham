<?php
// Start the session
session_start();

// Check if 'nama' is set in the session, if not, redirect to the login page
if (!isset($_SESSION['password'])) {
  header("Location: ../login.php");
  exit();
}

if (!isset($_SESSION['nisn'])) {
  header("Location: ../login.php");
  exit();
}

// Access the NIS from the session
$nis = $_SESSION['nisn'];

// Now you can use $nis wherever you need it
?>

<!DOCTYPE html>
<html lang="en">
<?php
require "../config/config.php";
// Pagination
$itemsPerPage = 4;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;
// Query to get the total number of books
$totalItems = queryReadData("SELECT COUNT(*) AS total FROM buku")[0]['total'];
$totalPages = ceil($totalItems / $itemsPerPage);
// query read semua buku
$buku = queryReadData("SELECT * FROM buku order by id_buku DESC LIMIT $offset, $itemsPerPage");//search buku
if (isset($_POST["search"])) {
  $buku = search($_POST["keyword"]);
}
?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Readbooks.com</title>
  <link rel="icon" href="../assets/iconblack.png" type="image/png">
</head>

<body>

  <nav class="navbar navbar-expand-sm navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <div class="dropdown" data-bs-theme="dark">
        <button class="btn btn-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../assets/memberLogo.png" alt="memberLogo" width="40px">
        </button>
        <ul style="margin-right: -7rem;" class="dropdown-menu position-absolute mt-2 p-2">
          <li>
            <a class="dropdown-item text-center" href="#">
              <img src="../assets/memberLogo.png" alt="adminLogo" width="30px">
            </a>
          </li>
          <li>
            <a class="dropdown-item text-center" href="#"> <span class="text-capitalize"><?= $_SESSION['nama']; ?></span></a>
            <a class="dropdown-item text-center mb-2" href="#">Siswa</a>
          </li>
          <li>
            <a class="dropdown-item text-center p-2 bg-danger text-light rounded" href="logout.php" onclick="return confirm('Apakah anda ingin keluar dari aplikasi ini?');">Logout <i class="fa-solid fa-right-to-bracket"></i></a>
          </li>
        </ul>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mynavbar">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" href="dashboard.php">Daftar Buku</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="peminjaman/daftar_pinjam.php">Daftar Pinjam</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="riwayat/history.php">History</a>
          </li>
        </ul>
        <form class="d-flex" action="" method="post">
          <input class="form-control me-2" type="text" name="keyword" id="keyword" placeholder="Search">
          <button class="btn btn-primary" type="submit" name="search">Search</button>
        </form>
      </div>
    </div>
  </nav>
  <style>
    .layout-card-custom {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1.5rem;
    }

    .card {
      background-color: #212529;
      /* Ganti dengan warna yang diinginkan */
      color: #fff;
      /* Ganti dengan warna teks yang sesuai */
      text-align: center;
      /* Atur posisi teks */
      width: 400px;
      /* Ganti ukuran sesuai kebutuhan */
      padding: 10px;
      /* Ganti padding sesuai kebutuhan */
      border-radius: 20px;
      /* Ganti border-radius sesuai kebutuhan */
    }

    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background-image: url(../assets/perpus.jpg);
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      background-attachment: relative;
    }
  </style>
  <section class="p-2">
    <div class="d-flex flex-wrap justify-content-center">
      <div class="col mt-5">

        <!--Card buku-->
        <div class="layout-card-custom mt-4">
          <?php foreach ($buku as $item) : ?>
            <div class="card" style="width: 10rem;">
              <img src="../assets/imgDB/<?= $item["cover"]; ?>" class="card-img-top" alt="coverBuku" height="200px">
              <div class="card-body">
                <h6 class="card-title text-center"><?= $item["judul"]; ?></h6>
              </div>
              <div div class="d-grid gap-3">
                <button type="button" class="btn btn-primary btn-block text-center" data-bs-toggle="modal" data-bs-target="#myModal<?= $item["id_buku"]; ?>">detail</button>
              </div>
            </div>

            <!-- The Modal -->
            <div class="modal" id="myModal<?= $item["id_buku"]; ?>">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <form method="post" enctype="multipart/form-data" action="">
                    <!-- Modal Header -->
                    <div class="modal-header bg-dark">
                      <h5 class="modal-title text-white">Detail Buku</h5>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body bg-dark">
                      <div class="row">
                        <div class="col-md-6">
                          <img src="../assets/imgDB/<?= $item["cover"]; ?>" class="img-thumbnail mb-4" alt="Mobil Image">
                        </div>
                        <div class="col-md-6">
                          <div>
                            <strong class="text-white">Judul:</strong>
                            <p class="text-white"><?php echo $item['judul']; ?></p>
                          </div>
                          <div>
                            <strong class="text-white">Kategori:</strong>
                            <p class="text-white"><?php echo $item['kategori']; ?></p>
                          </div>
                          <div>
                            <strong class="text-white">Pengarang:</strong>
                            <p class="text-white"><?php echo $item['pengarang']; ?></p>
                          </div>
                          <div>
                            <strong class="text-white">Penerbit:</strong>
                            <p class="text-white"><?php echo $item['penerbit']; ?></p>
                          </div>
                          <div>
                            <strong class="text-white">Tahun Terbit:</strong>
                            <p class="text-white"><?php echo $item['thn_terbit']; ?></p>
                          </div>
                          <div>
                            <strong class="text-white">Deskripsi:</strong>
                            <p class="text-white"><?php echo $item['deskripsi']; ?></p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer bg-dark">
                      <a href="peminjaman/pinjam.php?id=<?= $item["id_buku"]; ?>" class="btn btn-primary">Pinjam</a>
                      <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
          </div>
        <!-- Pagination links -->
        <div class="d-flex justify-content-center mt-3">
          <nav aria-label="Page navigation example">
            <ul class="pagination">
              <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                  <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>
            </ul>
          </nav>
        </div>
      </div>
    </div>
  </section>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>