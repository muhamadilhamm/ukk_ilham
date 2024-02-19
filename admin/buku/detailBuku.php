<?php
session_start();
if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'petugas') {
        header("Location: ../petugas/index.php");
        exit;
    }
}

include "../../config/config.php";
$id = $_GET['id_buku'];
$databuku = queryReadData("SELECT * FROM buku where id_buku = '$id' ");

//search buku
if (isset($_POST["search"])) {
    $databuku = search($_POST["keyword"]);
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
                        <span class="navbar-brand text-center">Detail Buku</span>
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
                            <a class="nav-link active" href="data-buku.php">
                            <i class="fa fa-book" style="margin: 5px;"></i>
                                Data Buku
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../kategori/data-kategori.php">
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
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">

                        <div class="col-md-8">
                            <div class="card card-info">
                            <div class="card-header bg-dark text-white">
                                    <h2 class="mt-2">Detail Buku</h2>
                                    <!-- Button to Open the Modal -->
                                </div>
                                <div class="card-body">
                                    <?php
                                    $no = 1;
                                    foreach ($databuku as $item) :
                                    ?>
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>ID Buku</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['id_buku']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Kategori</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['kategori']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Judul</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['judul']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Pengarang</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['pengarang']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Penerbit</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['penerbit']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Tahun Terbit</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['thn_terbit']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Jumlah Halaman</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['jml_halaman']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Deskripsi</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['deskripsi']; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 150px">
                                                        <b>Isi Buku</b>
                                                    </td>
                                                    <td>:
                                                        <?php echo $item['isi_buku']; ?>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                        <div>
                                            <a class="btn btn-sm btn-secondary" href="data-buku.php" style="width:100px;"></i> Kembali</a>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-success">
                            <div class="card-header bg-dark text-white">
                                    <center>
                                        <h2 class="m-0 font-weight-bold">Cover</h2>
                                    </center>
                                    <div class="card-tools"></div>
                                </div>
                                <div class="card-body">
                                    <div class="text-center">
                                        <td><img src="../../assets/imgDB/<?= $item['cover']; ?>" alt="" width="160px" style="border-radius: 5px;"></td>
                                    </div>
                                    <h6 class="m-2 font-weight-bold text-center">
                                        <?php echo $item['judul']; ?>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                    </div>

                </div>
                <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

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