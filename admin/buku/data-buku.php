<?php
session_start();
if (isset($_SESSION['sebagai'])) {
    if ($_SESSION['sebagai'] == 'petugas') {
        header("Location: ../petugas/index.php");
        exit;
    }
}

include "../../config/config.php";
$kategori = queryReadData("SELECT * FROM kategori_buku");
$databuku = queryReadData("SELECT * FROM buku order by id_buku DESC");

$query = mysqli_query($connection, "SELECT max(id_buku) as kodeTerbesar FROM buku");
$dataid = mysqli_fetch_array($query);
$kodebuku = $dataid['kodeTerbesar'];
$urutan = (int) substr($kodebuku, -4, 4);
$urutan++;
$huruf = "KB";
$kodebuku = $huruf . sprintf("%04s", $urutan);

if (isset($_POST["tambah"])) {

    if (tambahBuku($_POST) > 0) {
        echo "<script>alert('Data berhasil ditambah.');window.location='data-buku.php';</script>";
    } else {
        echo "<script>
        alert('Data buku gagal ditambahkan!');
        </script>";
    }
}


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
                        <span class="navbar-brand text-center">Data Buku</span>
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
                <!-- Content goes here -->
                <div class="card">
                <div class="card-header bg-dark text-white">
                        <h2 class="mt-2">Data Buku <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal" style="float:right;">
                                Tambah Buku
                            </button></h2>
                        <!-- Button to Open the Modal -->

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
                                    <th>Cover</th>
                                    <th>Judul</th>
                                    <th>Kategori</th>
                                    <th>Pengarang</th>
                                    <th>Penerbit</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1; // Nomor urut dimulai dari 1
                                foreach ($databuku as $item) : ?>
                                    <tr>
                                        <td style="width:50px;"><?= $no++; ?></td>
                                        <td><img src="../../assets/imgDB/<?= $item['cover']; ?>" alt="" width="70px" height="100px" style="border-radius: 5px;"></td>
                                        <td><?= $item["judul"]; ?></td>
                                        <td><?= $item["kategori"]; ?></td>
                                        <td><?= $item["pengarang"]; ?></td>
                                        <td><?= $item["penerbit"]; ?></td>
                                        <td>
                                            <div>
                                                <a href="detailBuku.php?id_buku=<?= $item['id_buku']; ?>" class="btn btn-success mt-2" style="width:100px;">Detail</a>
                                            </div>
                                            <div>
                                                <a href="editBuku.php?id_buku=<?= $item['id_buku']; ?>" class="btn btn-warning mt-2" style="width:100px;">Edit</a>
                                            </div>
                                            <div>
                                                <a href="deleteBuku.php?id_buku=<?= $item['id_buku']; ?>" class="btn btn-danger mt-2" style="width:100px;" onclick="return confirm('Apakah Buku <?= $item['judul']; ?> ingin anda hapus?');">Hapus</a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="post" enctype="multipart/form-data">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Form Tambah Buku</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="custom-css-form">
                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Cover Buku</label>
                                <input class="form-control" type="file" name="cover" id="cover" required>
                            </div>

                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Id Buku</label>
                                <input type="text" class="form-control" name="id_buku" id="id_buku" value="<?= $kodebuku; ?>" placeholder="example inf01" readonly style="background-color: #f0f0f0;">
                            </div>
                        </div>

                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori">
                                <option value="" disabled selected>Pilih Kategori</option>
                                <?php foreach ($kategori as $item) : ?>
                                    <option><?= $item["kategori"]; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-book"></i></span>
                            <input type="text" class="form-control" name="judul" id="judul" placeholder="Judul Buku" aria-label="Username" aria-describedby="basic-addon1" required>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Pengarang</label>
                            <input type="text" class="form-control" name="pengarang" id="pengarang" placeholder="nama pengarang" required>
                        </div>

                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">Penerbit</label>
                            <input type="text" class="form-control" name="penerbit" id="penerbit" placeholder="nama penerbit" required>
                        </div>

                        <label for="validationCustom01" class="form-label">Tahun Terbit</label>
                        <div class="input-group mt-0">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-calendar-days"></i></span>
                            <input type="date" class="form-control" name="thn_terbit" id="thn_terbit" required>
                        </div>

                        <label for="validationCustom01" class="form-label">Jumlah Halaman</label>
                        <div class="input-group mt-0">
                            <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-book-open"></i></span>
                            <input type="number" class="form-control" name="jml_halaman" id="jml_halaman" required>
                        </div>

                        <div class="form-floating mt-3 mb-3">
                            <textarea class="form-control" placeholder="sinopsis tentang buku ini" name="deskripsi" id="deskripsi" style="height: 100px"></textarea>
                            <label for="floatingTextarea2">Deskripsi</label>
                        </div>

                        <div class="custom-css-form">
                            <div class="mb-3">
                                <label for="formFileMultiple" class="form-label">Isi Buku</label>
                                <input class="form-control" type="file" name="isi_buku" id="isi_buku" required>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button class="btn btn-success" type="submit" name="tambah">Tambah</button>
                            <input type="reset" class="btn btn-warning text-light" value="Reset">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
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