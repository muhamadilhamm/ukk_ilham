<?php
session_start();
if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'petugas') {
        header("Location: petugas/index.php");
        exit;
    }
}

include "../../config/config.php";
if (isset($_POST["tambah"])) {

    if (tambahkategori($_POST) > 0) {
        echo "<script>
      alert('Data berhasil ditambahkan!');
      window.location='data-kategori.php';
      </script>";
    } else {
        echo "<script>
      alert('Data gagal ditambahkan!');
      window.location='data-kategori.php';
      </script>";
    }
}

$datakategori = queryReadData("SELECT * FROM kategori_buku order by kategori ASC");
//search Kategori
if (isset($_POST["search"])) {
    $datakategori = searchKategori($_POST["keyword"]);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Readbooks.com</title>
    <link rel="icon" href="../../assets/iconblack.png" type="image/png">

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
            max-height: 460px;
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
            <a class="navbar-brand" href="index.php"><img src="../../assets/readbook.png" width="150px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar" id="sidebarCollapse">
                <span class="fas fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-brand text-center">Data Kategori</span>
                    </li>
                    <li class="nav-item">
                        <i id="profile-icon" class="bi bi-person-circle text-light" data-bs-toggle="dropdown">
                            <img src="../../assets/adminLogo.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill"></i>
                        <ul class="dropdown-menu bg-primary dropdown-menu-end">
                            <li><img src="../../assets/adminLogo.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill mx-auto d-block"></i></li>
                            <li><a class="btn mx-auto d-block mt-2" href="#"><?= $_SESSION['username']; ?></a></li>
                            <li><a class="btn mx-auto d-block" href="#"><?= $_SESSION['sebagai']; ?></a></li>
                            <li><a class="btn mx-auto d-block bg-danger" href="../logout.php" onclick="return confirm('Apakah anda ingin keluar dari aplikasi ini?');">Logout</a></li>
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
                            <a class="nav-link" href="../index.php">
                               <i class="fa fa-dashboard" style="margin: 5px;"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../buku/data-buku.php">
                            <i class="fa fa-book" style="margin: 5px;"></i>
                                Data Buku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="data-kategori.php">
                            <i class="fa fa-list" style="margin: 5px;"></i>
                                Data Kategori
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../peminjaman/data-peminjaman.php">
                            <i class="fas fa-clipboard-list" style="margin: 5px;"></i>
                                Data Peminjaman
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../member/data-member.php">
                            <i class="fa fa-address-card" style="margin: 5px;"></i>
                                Data Member
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../akun/pengguna.php">
                            <i class="fas fa-user-tie" style="margin: 5px;"></i>
                                Data Pengguna
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
                        <h2 class="mt-2">Data Kategori
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" style="float: right;">
                                Tambah Kategori
                            </button>
                        </h2>
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
                                    <th style="width:50px;">#</th>
                                    <th>Kategori</th>
                                    <th style="width:200px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1; // Nomor urut dimulai dari 1
                                foreach ($datakategori as $item) :
                                ?>
                                    <tr>
                                        <td style="width:50px;"><?= $no++; ?></td>
                                        <td><?= $item['kategori']; ?></td>
                                        <td style="width:200px;">
                                            <a href="deleteKategori.php?kategori=<?= $item['kategori']; ?>" class="btn btn-danger mt-2" style="width:100px;" onclick="return confirm('Apakah <?= $item['kategori']; ?> ingin anda hapus? Jika anda hapus maka data buku dengan kategori ini juga akan terhapus!');">Hapus</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                <!-- Tambahkan baris data lainnya sesuai kebutuhan -->
                            </tbody>
                        </table>

                    </div>
                </div>


            </main>
        </div>
    </div>

    <!-- The Modal -->
    <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="post" class="">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Form Tambah Kategori</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Kategori</label>
                            <input type="text" class="form-control" name="kategori" id="kategori" placeholder="kategori" required>
                        </div>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button class="btn btn-success" type="submit" name="tambah">Tambah</button>
                        <input type="reset" class="btn btn-warning text-light" value="Reset">
                    </div>
                </form>

            </div>
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