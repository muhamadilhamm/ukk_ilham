<?php
require "../loginSystem/connect.php";
if (isset($_POST["signUp"])) {

    if (signUp($_POST) > 0) {
        echo "<script>
    alert('Sign Up berhasil!');
    window.location.href='../login/login.php';
    </script>";
    } else {
        echo "<script>
    alert('Sign Up gagal!')
    </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://kit.fontawesome.com/de8de52639.js" crossorigin="anonymous"></script>
    <title>Readbooks.com</title>
    <link rel="icon" href="../assets/iconblack.png" type="image/png">
    <style>
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

        .container {
            max-width: 800px;
            margin-top: 0 none;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            border-radius: 15px 15px 0 0;
            padding: 20px;
            text-align: center;
        }

        .card-header img {
            width: 100%;
            /* Make the image full-width */
            max-width: 400px;
            /* Set the maximum width of the image if needed */
            margin: 0 auto 10px;
            /* Center the image horizontally */
            display: block;
            /* Ensure that the image is treated as a block element */
        }

        .card-header h3 {
            margin: 0;
        }


        .form-group {
            margin-bottom: 20px;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .bg-black {
            color: #000000;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h3 class="">Daftar Akun Siswa</h3>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nisn" class="form-label">NISN</label>
                                <input type="number" class="form-control" id="nisn" name="nisn" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control" id="nama" name="nama" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="kelas" class="form-label">Kelas</label>
                                <select class="form-select" id="kelas" name="kelas" required>
                                    <option value="" disabled selected>Pilih Kelas</option>
                                    <option value="X">X</option>
                                    <option value="XI">XI</option>
                                    <option value="XII">XII</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="jurusan" class="form-label">Jurusan</label>
                                <select class="form-select" id="jurusan" name="jurusan" required>
                                    <option value="" disabled selected>Pilih Jurusan</option>
                                    <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                                    <option value="Otomatisasi Tata Kelola Perkantoran">Otomatisasi Tata Kelola Perkantoran</option>
                                    <option value="BDP">BDP</option>
                                    <option value="Multi Media">Multi Media</option>
                                    <option value="AKL">AKL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="alamat" class="form-label">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-center button-right">
                        <button type="submit" name="signUp" class="btn btn-primary mt-2" style="width:200px; height:50px;">Daftar</button>
                        </div>
                        </div>
                        <div class="card-footer text-center">
                        <p class="mt-2">Sudah punya akun? <a href="../login/login.php" class="btn-link text-black">Login</a></p>
                    </div>
                </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>