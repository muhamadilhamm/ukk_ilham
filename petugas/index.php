<?php
session_start();
if (isset($_SESSION['sebagai'])) {
} elseif ($_SESSION['sebagai'] == 'admin') {
  header("Location: admin/index.php");
  exit;
}

include "../config/config.php";

$id = $_SESSION['id'];
$peminjaman = queryReadData("SELECT peminjaman.id AS peminjaman_id,
buku.cover AS cover,
buku.id_buku AS id_buku, 
buku.judul AS judul,
member.nisn AS nisn, 
member.nama AS nama, 
user.username AS username,
peminjaman.tgl_pinjam AS tgl_pinjam,
peminjaman.tgl_kembali AS tgl_kembali,
peminjaman.status AS status
FROM peminjaman
INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
INNER JOIN member ON peminjaman.nisn = member.nisn
INNER JOIN user ON peminjaman.id_user = user.id
WHERE peminjaman.id_user = '$id' order by peminjaman_id DESC");

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
  <title>Readbooks.com</title>
  <link rel="icon" href="../assets/iconblack.png" type="image/png">
  <!-- Tampilan untuk di hp -->
  <style>
        @media (max-width: 768px) {
            #sidebar {
                position: fixed;
                width: 100%;
                height: 100%;
                display: none;
                z-index: 1000;
                background-color: #212529;
                overflow-y: auto;
            }

            #content {
                margin-left: 0;
            }

            #sidebar.active {
                display: block;
            }

            #sidebarCollapse {
                display: block;
            }

            .navbar-toggler-icon {
                background-color: #fff;
            }

            .navbar-nav {
                flex-direction: column;
            }

            .navbar-brand {
                margin-right: 0;
            }

            .nav-link {
                padding: 10px;
                text-align: center;
            }

        }
        </style>
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    #sidebar {
      background-color: #212529;
      color: #fff;
    }

    #content {
      transition: margin-left 0.5s;
      padding: 15px;
    }

    #sidebarCollapse {
      background-color: #343a40;
      border: none;
      color: #fff;
    }

    #sidebarCollapse:hover {
      background-color: #212529;
    }

    .navbar {
      background-color: #212529;
    }

    .navbar-brand {
      color: #fff;
    }

    .navbar-toggler-icon {
      background-color: #fff;
    }

    .nav-link:hover {
      color: #f8f9fa;
    }

    /* Tombol pada Sidebar */
    .nav-link {
      padding: 10px;
      /* Padding untuk memberikan ruang di sekitar teks tombol */
      text-decoration: none;
      /* Menghilangkan garis bawah default */
      color: #dee2e6;
      /* Warna teks tombol */
      transition: background-color 0.3s, color 0.3s;
      /* Efek transisi hover */
    }

    /* Efek Hover pada Tombol */
    .nav-link:hover {
      background-color: #3d4852;
      /* Warna latar belakang saat dihover */
      color: #fff;
      /* Warna teks saat dihover */
    }

    /* Tombol Aktif pada Sidebar */
    .nav-link.active {
      background-color: #3d4852;
      /* Warna latar belakang tombol aktif */
      color: #fff;
      /* Warna teks tombol aktif */
    }

    .center-text {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100%;
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
      max-height: 600px;
      /* Atur tinggi maksimum sesuai kebutuhan */
      overflow-y: auto;
      /* Aktifkan scrolling vertikal jika kontennya melebihi tinggi maksimum */
    }
  </style>

</head>

<body>
  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php"><img src="../assets/readbook.png" width="150px"></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar" id="sidebarCollapse">
                <span class="fas fa-bars"></span>
            </button>
      <div class="collapse navbar-collapse" id="mynavbar">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item text-center">
            <span class="navbar-brand">Dashboard</span>
          </li>
          <li class="nav-item">
            <i id="profile-icon" class="bi bi-person-circle text-light" data-bs-toggle="dropdown">
              <img src="../assets/adminLogo.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill"></i>
            <ul class="dropdown-menu bg-primary dropdown-menu-end">
              <li><img src="../assets/adminLogo.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill mx-auto d-block"></i></li>
              <li><a class="btn mx-auto d-block mt-2" href="#"><?= $_SESSION['username']; ?></a></li>
              <li><a class="btn mx-auto d-block" href="#"><?= $_SESSION['sebagai']; ?></a></li>
              <li><a class="btn mx-auto d-block bg-danger" href="logout.php" onclick="return confirm('Apakah anda ingin keluar dari aplikasi ini?');">Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block position-fixed vh-100">
        <div class="position-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="index.php">
                <i class="fa fa-dashboard" style="margin:5px;"></i>
                Dashboard
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="peminjaman/data-peminjaman.php">
                <i class="fas fa-clipboard-list" style="margin:5px;"></i>
                Data Peminjaman
              </a>
            </li>
          </ul>
        </div>
      </nav>

      <!-- Main Content Area -->
      <main id="content" class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <!-- Content goes here -->
        <div class="card">
        <div class="card-header bg-dark text-white">
            <h2 class="mt-2">Data Peminjaman</h2>
          </div>
          <div class="card-body scrollable-card">
            <table>
              <thead>
                <tr>
                  <th>#</th>
                  <th>Cover</th>
                  <th>Judul</th>
                  <th>NISN</th>
                  <th>Nama Peminjam</th>
                  <th>Nama Petugas</th>
                  <th>Tgl. Pinjam</th>
                  <th>Tgl. Akhir</th>
                  <th>Status</th>
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
                      <td><img src="../assets/imgDB/<?= $item['cover']; ?>" alt="" width="70px" height="100px" style="border-radius: 5px;"></td>
                      <td><?= $item['judul']; ?></td>
                      <td><?= $item['nisn']; ?></td>
                      <td><?= $item['nama']; ?></td>
                      <td><?= $item['username']; ?></td>
                      <td><?= $item['tgl_pinjam']; ?></td>
                      <td><?= $item['tgl_kembali']; ?></td>
                      <td><?php
                          $statusClass = '';
                          if ($item['status'] == 0) {
                            $statusText = 'Menunggu Persetujuan';
                            $statusClass = 'text-warning';
                          } elseif ($item['status'] == 1) {
                            $statusText = 'Telah Disetujui';
                            $statusClass = 'text-primary';
                          } else {
                            $statusText = 'Tidak Disetujui';
                            $statusClass = 'text-danger';
                          }
                          ?>
                        <span class="<?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                    </tr>
                <?php endforeach;
                } else {
                  echo '<tr><td colspan="9">Tidak ada data peminjaman.</td></tr>';
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
  <script>
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
      document.getElementById('sidebar').classList.toggle('active');
      document.getElementById('content').classList.toggle('active');
    });
  </script>
</body>

</html>