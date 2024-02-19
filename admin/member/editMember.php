<?php
session_start();
if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'petugas') {
        header("Location: petugas/index.php");
        exit;
    }
}

include "../../config/config.php";
$nisn = $_GET['nisn'];
$datasiswa = queryReadData("SELECT * FROM member where nisn = '$nisn' ");

if (isset($_POST["edit"])) {

    if (updateMember($_POST) > 0) {
        echo "<script>alert('Data member berhasil diubah.');window.location='data-member.php';</script>";
    } else {
        echo "<script>
        alert('Data member gagal diubah!');
        </script>";
    }
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
            <a class="navbar-brand" href="index.php"><img src="../../assets/readbook.png" width="150px"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar" id="sidebarCollapse">
                <span class="fas fa-bars"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-brand text-center">Data Member</span>
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
                            <a class="nav-link active" href="data-member.php">
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
                        <h2 class="mt-2">Data Member</h2>
                    </div>
                    <?php foreach ($datasiswa as $item) : ?>
                        <div class="card-body">
                            <form action="" method="post" enctype="multipart/form-data" class="mt-3 p-2">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">NISN</label>
                                    <input type="text" class="form-control" name="nisn" id="nisn" value="<?= $item['nisn']; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Nama</label>
                                    <input type="text" class="form-control" name="nama" id="nama" value="<?= $item['nama']; ?>">
                                </div>

                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="inputGroupSelect01">Kelas</label>
                                    <select class="form-select" id="kelas" name="kelas">
                                        <option selected><?= $item["kelas"]; ?></option>
                                        <option value="X">X</option>
                                        <option value="XI">XI</option>
                                        <option value="XII">XII</option>
                                    </select>
                                </div>

                                <div class="input-group mb-3">
                                    <label class="input-group-text" for="inputGroupSelect01">Jurusan</label>
                                    <select class="form-select" id="jurusan" name="jurusan">
                                        <option selected><?= $item["jurusan"]; ?></option>
                                        <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                                        <option value="Otomatisasi Tata Kelola Perkantoran">Otomatisasi Tata Kelola Perkantoran</option>
                                        <option value="BDP">BDP</option>
                                        <option value="Multi Media">Multi Media</option>
                                        <option value="AKL">AKL</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Password</label>
                                    <input type="text" class="form-control" name="password" id="password" value="<?= $item['password']; ?>">
                                </div>

                                <div class="form-floating mt-3 mb-3">
                                    <textarea class="form-control" placeholder="sinopsis tentang buku ini" name="alamat" id="alamat" style="height: 100px"><?= $item['alamat']; ?></textarea>
                                    <label for="floatingTextarea2">Alamat</label>
                                </div>

                                <div class="custom-css-form">
                                    <button class="btn btn-success" type="submit" name="edit" style="width:100px;">Edit</button>
                                    <input type="reset" class="btn btn-warning text-light" value="Reset" style="width:100px;">
                                    <a class="btn btn-secondary" href="data-member.php" style="width:100px;">Kembali</a>
                                </div>
                            </form>
                        <?php endforeach; ?>
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