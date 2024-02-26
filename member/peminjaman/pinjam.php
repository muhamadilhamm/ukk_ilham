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
// Tangkap id buku dari URL (GET)
$idBuku = $_GET["id"];
$query = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");
//Menampilkan data siswa yg sedang login
$nisnSiswa = $_SESSION['nisn'];
$dataSiswa = queryReadData("SELECT * FROM member WHERE nisn = $nisnSiswa");
$admin = queryReadData("SELECT * FROM user where sebagai='petugas'");

// Peminjaman 
if (isset($_POST["pinjam"])) {

  if (pinjamBuku($_POST) > 0) {
    echo "<script>
    alert('Buku berhasil dipinjam');
    window.location='daftar_pinjam.php';
    </script>";
  } else {
    echo "<script>
    alert('Buku gagal dipinjam!');
    </script>";
  }
} ?>

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
    display: flex;
    background-color: #3d4852;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    background-attachment: fixed;
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
            <a class="nav-link" href="daftar_pinjam.php">Daftar Pinjam</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../riwayat/history.php">History</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>


  <div class="container-xxl p-5 my-5">
    <div class="">
      <div class="alert alert-dark" role="alert">Form Peminjaman Buku</div>
      <!-- Default box -->
      <div class="card mb-auto">
        <h5 class="card-header bg-dark text-white">Data lengkap Buku</h5>
        <div class="card-body scrollable-card">
          <?php foreach ($query as $item) : ?>
            <div class="row">
              <div class="col-md-4 d-flex justify-content-center mb-3">
                <img src="../../assets/imgDB/<?= $item["cover"]; ?>" width="190px" height="250px" style="border-radius: 5px;">
              </div>
              <div class="col-md-8">
                <div class="table">
                  <table class="table table-borderless">
                    <tbody>
                      <tr>
                        <th scope="row">Id Buku</th>
                        <td>: <?= $item["id_buku"]; ?></td>
                      </tr>
                      <tr>
                        <th scope="row">Kategori</th>
                        <td>: <?= $item["kategori"]; ?></td>
                      </tr>
                      <tr>
                        <th scope="row">Judul</th>
                        <td>: <?= $item["judul"]; ?></td>
                      </tr>
                      <tr>
                        <th scope="row">Pengarang</th>
                        <td>: <?= $item["pengarang"]; ?></td>
                      </tr>
                      <tr>
                        <th scope="row">Penerbit</th>
                        <td>: <?= $item["penerbit"]; ?></td>
                      </tr>
                      <tr>
                        <th scope="row">Tahun Terbit</th>
                        <td>: <?= $item["thn_terbit"]; ?></td>
                      </tr>
                      <tr>
                        <th scope="row">Jumlah Halaman</th>
                        <td>: <?= $item["jml_halaman"]; ?></td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="form-floating">
                    <textarea class="form-control" placeholder="deskripsi singkat buku" id="floatingTextarea2" style="height: 100px" readonly><?= $item["deskripsi"]; ?></textarea>
                    <label for="floatingTextarea2">Deskripsi Buku</label>
                  </div>
                </div>
                <div class="alert alert-danger mt-4" role="alert">Silahkan periksa kembali data diatas, pastikan sudah benar sebelum meminjam buku! jika ada kesalahan data harap hubungi admin.</div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="card mt-4">
          <h5 class="card-header bg-dark text-white">Form Pinjam Buku</h5>
          <div class="card-body">
            <form action="" method="post">
              <!--Ambil data id buku-->
              <?php foreach ($query as $item) : ?>
                <div class="input-group mb-3">
                  <span class="input-group">Id Buku</span>
                  <input type="text" name="id_buku" class="form-control" placeholder="id buku" aria-label="id_buku" value="<?= $item["id_buku"]; ?>" readonly>
                </div>
              <?php endforeach; ?>
              <!-- Ambil data NISN user yang login-->
              <div class="input-group mb-3">
                <span class="input-group">Nisn</span>
                <input type="number" name="nisn" class="form-control" placeholder="nisn" aria-label="nisn" value="<?php echo htmlentities($_SESSION["nisn"]); ?>" readonly>
              </div>
              <!--Ambil data id admin-->
              <div class="input-group mb-3">
                <span class="input-group">Nama Petugas</span>
                <select name="id_user" id="id_user" class="form-select" aria-label="Default select example" required>
                  <option value="" selected disabled>Pilih Petugas</option>
                  <?php foreach ($admin as $item) : ?>
                    <option value="<?= $item["id"]; ?>"><?= $item["username"]; ?></option>
                  <?php endforeach;
                  $sekarang  = date("Y-m-d");
                  ?>
                </select>
              </div>
              <div class="input-group mb-3">
                <span class="input-group">Telpon Petugas</span>
                <input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="No. Telepon" aria-label="No. Telepon" aria-describedby="basic-addon1" readonly>
              </div>
              <div class="input-group mb-3">
                <select class="form-select" aria-label="Default select example" name="paket" id="paket" onchange="setReturnDate(this)">
                  <option disabled selected>-- pilih paketan --</option>
                  <option value="">Non paket</option>
                  <option value="1">Paket 1</option>
                  <option value="2">Paket 2</option>
                  <option value="3">Paket 3</option>
                </select>
              </div>
              
              <div class="input-group mb-3 d-flex">
                <div class="col-md-4 col-12 me-5">
                  <span class="input-group">Tanggal pinjam</span>
                  <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" value="<?= $sekarang; ?>" placeholder="tgl_pinjam" aria-label="tgl_pinjam" onchange="setReturnDate()" required>
                </div>

                <div class="col-md-4 col-12 me-5">
                  <span class="input-group">Sampai Tanggal</span>
                  <input type="date" name="tgl_kembali" id="tgl_kembali" class="form-control" placeholder="tgl_kembali" aria-label="tgl_kembali" required>
                </div>
                <div class="col-md-3 col-12">
                <span class="input-group">Harga</span>
                <input type="text" name="harga" onchange="setPrice()" class="form-control" placeholder="harga" aria-label="harga" aria-describedby="basic-addon1" readonly>
              </div>
              </div>
              <a class="btn btn-danger" href="../dashboard.php"> Batal</a>
              <button type="submit" class="btn btn-success" name="pinjam">Pinjam</button>
            </form>
          </div>
        </div>

      </div>
      <!-- /.card -->
    </div>
  </div>
  </div>

  <!--JAVASCRIPT -->
  <script src="../style/js/script.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

  <script>
    function setReturnDate() {
      const tglpinjam = document.getElementById('tgl_pinjam');
      const tglkembali = document.getElementById('tgl_kembali');
      const currentDate = new Date();
      let returnDate = new Date();

      const selectedPackage = document.getElementById('paket').value;
      let daysToAdd = 1; // Default return date if no package is selected

      // Adjust days to add based on the selected package
      switch (selectedPackage) {
        case "1":
          daysToAdd = 5; // Change to the duration of Paket 1
          break;
        case "2":
          daysToAdd = 7; // Change to the duration of Paket 2
          break;
        case "3":
          daysToAdd = 10; // Change to the duration of Paket 3
          break;
        default:
          daysToAdd = 1; // Default return date if no package is selected
      }

      returnDate.setDate(currentDate.getDate() + daysToAdd);

      // Format tanggal untuk input HTML
      const formattedReturnDate = returnDate.toISOString().split('T')[0];
      tglkembali.value = formattedReturnDate;

      setPrice(); // Call setPrice() after setting return date

      // Enable or disable tgl_kembali input based on whether a package is selected
      if (selectedPackage === "") {
        tglkembali.removeAttribute('readonly');
        tglpinjam.removeAttribute('readonly');
      } else {
        tglkembali.setAttribute('readonly', 'readonly');
        tglpinjam.setAttribute('readonly', 'readonly');
      }

    }


    function setPrice() {
      const priceInput = document.getElementsByName('harga')[0];
      const isPackageSelected = document.getElementById('paket').value !== ""; // Check if a package is selected

      // Get the selected dates
      const tglPinjam = new Date(document.getElementById('tgl_pinjam').value);
      const tglKembali = new Date(document.getElementById('tgl_kembali').value);

      // Get the difference in days between tgl_pinjam and tgl_kembali
      const diffTime = Math.abs(tglKembali - tglPinjam);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

      let pricePerDay;

      if (isPackageSelected) {
        // Adjust price calculation based on package selection
        const selectedPackage = parseInt(document.getElementById('paket').value);
        // Assuming different packages have different prices
        // You can set prices based on the selected package
        // Here, we are just setting some arbitrary values
        switch (selectedPackage) {
          case 1:
            pricePerDay = 10000; // Price for Paket 1
            break;
          case 2:
            pricePerDay = 9000; // Price for Paket 2
            break;
          case 3:
            pricePerDay = 8000; // Price for Paket 3
            break;
          default:
            pricePerDay = 11000; // Default price if no package selected
        }
      } else {
        // If no package is selected, set default price per day
        pricePerDay = 11000; // Default price per day for non-package
      }

      // Calculate total price
      const totalPrice = diffDays * pricePerDay;
      priceInput.value = "Rp. " + totalPrice.toLocaleString('id-ID');
    }

    // Fungsi untuk mengatur tanggal pinjam dengan hari ini
    function setTodayDate() {
      const todayDateInput = document.getElementById('tgl_pinjam');
      const currentDate = new Date();

      // Format tanggal untuk input HTML
      const formattedTodayDate = currentDate.toISOString().split('T')[0];
      todayDateInput.value = formattedTodayDate;

      setReturnDate(); // Call setReturnDate() after setting today's date
    }

    // Panggil fungsi setTodayDate saat halaman dimuat
    window.onload = function() {
      setTodayDate();
    };

    // Panggil setPrice() saat tgl_pinjam atau tgl_kembali berubah
    document.getElementById('tgl_pinjam').addEventListener('change', setPrice);
    document.getElementById('tgl_kembali').addEventListener('change', setPrice);

    // Validasi tanggal tenggat pengembalian
    document.getElementById('tgl_kembali').addEventListener('change', function() {
      var tglPinjam = document.getElementById('tgl_pinjam').value;
      var tglPengembalian = this.value;

      // Bandingkan tanggal tenggat pengembalian dengan tanggal pinjam
      if (tglPengembalian <= tglPinjam) {
        alert('Tanggal tenggat pengembalian tidak boleh sebelum atau sama dengan tanggal pinjam');
        this.value = '';
      }
    });
    var adminData = <?php echo json_encode($admin); ?>;

    document.getElementById('id_user').addEventListener('change', function() {
      var id_user = this.value;

      for (var i = 0; i < adminData.length; i++) {
        if (adminData[i].id === id_user) { // Assuming 'id' is the correct property to match
          document.getElementById('no_telp').value = adminData[i].no_telp;
          break;
        }
      }
    });
  </script>

</body>

</html>