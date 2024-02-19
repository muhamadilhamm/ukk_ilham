<?php
$host = "127.0.0.1";
$username = "root";
$password = "";
$database_name = "ukk_ilham";
$connection = mysqli_connect($host, $username, $password, $database_name);

// === FUNCTION KHUSUS ADMIN START ===

// MENAMPILKAN DATA KATEGORI BUKU
function queryReadData($dataKategori)
{
  global $connection;
  $result = mysqli_query($connection, $dataKategori);
  $items = [];
  while ($item = mysqli_fetch_assoc($result)) {
    $items[] = $item;
  }
  return $items;
}

// Menambahkan data buku 
function tambahBuku($dataBuku)
{
  global $connection;

  $cover = upload();
  $idBuku = htmlspecialchars($dataBuku["id_buku"]);
  $kategoriBuku = $dataBuku["kategori"];
  $judulBuku = htmlspecialchars($dataBuku["judul"]);
  $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
  $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
  $tahunTerbit = date('Y-m-d', strtotime($dataBuku["thn_terbit"])); // Format date as needed
  $jumlahHalaman = $dataBuku["jml_halaman"];
  $deskripsiBuku = htmlspecialchars($dataBuku["deskripsi"]);
  $isi_buku = upload_isi();

  if (!$cover || !$isi_buku) {
    return 0;
  }

  $queryInsertDataBuku = "INSERT INTO buku (cover, id_buku, kategori, judul, pengarang, penerbit, thn_terbit, jml_halaman, deskripsi, isi_buku) 
                          VALUES('$cover', '$idBuku', '$kategoriBuku', '$judulBuku', '$pengarangBuku', '$penerbitBuku', '$tahunTerbit', $jumlahHalaman, '$deskripsiBuku', '$isi_buku')";

  mysqli_query($connection, $queryInsertDataBuku);
  return mysqli_affected_rows($connection);
}


// Function upload gambar 
function upload()
{
  $namaFile = $_FILES["cover"]["name"];
  $ukuranFile = $_FILES["cover"]["size"];
  $error = $_FILES["cover"]["error"];
  $tmpName = $_FILES["cover"]["tmp_name"];

  // cek apakah ada gambar yg diupload
  if ($error === 4) {
    echo "<script>
    alert('Silahkan upload cover buku terlebih dahulu!')
    </script>";
    return 0;
  }

  // cek kesesuaian format gambar
  $jpg = "jpg";
  $jpeg = "jpeg";
  $png = "png";
  $svg = "svg";
  $bmp = "bmp";
  $psd = "psd";
  $tiff = "tiff";
  $formatGambarValid = [$jpg, $jpeg, $png, $svg, $bmp, $psd, $tiff];
  $ekstensiGambar = explode('.', $namaFile);
  $ekstensiGambar = strtolower(end($ekstensiGambar));

  if (!in_array($ekstensiGambar, $formatGambarValid)) {
    echo "<script>
    alert('Format file tidak sesuai');
    </script>";
    return 0;
  }

  // batas ukuran file
  if ($ukuranFile > 2000000) {
    echo "<script>
    alert('Ukuran file terlalu besar!');
    </script>";
    return 0;
  }

  //generate nama file baru, agar nama file tdk ada yg sama
  $namaFileBaru = uniqid();
  $namaFileBaru .= ".";
  $namaFileBaru .= $ekstensiGambar;

  move_uploaded_file($tmpName, '../../assets/imgDB/' . $namaFileBaru);
  return $namaFileBaru;
}

//upload isi buku dengan format pdf
function upload_isi()
{
  $namaFile = $_FILES['isi_buku']['name'];
  $x = explode('.', $namaFile);
  $ekstensiFile = strtolower(end($x));
  $ukuranFile = $_FILES['isi_buku']['size'];
  $file_tmp = $_FILES['isi_buku']['tmp_name'];

  // Lokasi Penempatan file
  $dirUpload = "../../assets/isi-buku/";
  $linkBerkas = $dirUpload . $namaFile;

  // Validasi Format File (contoh: hanya menerima format PDF)
  if ($ekstensiFile !== 'pdf') {
    echo "<script>
      alert('Format file tidak sesuai. Hanya file PDF yang diperbolehkan.');
      </script>";
    return 0;
  }

  // Kontrol Ukuran File (contoh: maksimum 2MB)
  if ($ukuranFile > 20000000000) {
    echo "<script>
      alert('Ukuran file terlalu besar. Maksimum 2MB.');
      </script>";
    return 0;
  }

  // Menyimpan file
  if (move_uploaded_file($file_tmp, $linkBerkas)) {
    return $namaFile;
  } else {
    echo "<script>
      alert('Gagal mengunggah file. Silakan coba lagi.');
      </script>";
    return 0;
  }
}

function tambahadmin()
{
  global $connection;

  $nama = $_POST['nama'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $sebagai = $_POST['sebagai'];


  //cek dulu jika ada foto produk jalankan coding ini
  if ($username != "") {
    $query = "INSERT INTO user ( nama, username, password, sebagai) VALUES ( '$nama', '$username', '$password', '$sebagai')";
    $result = mysqli_query($connection, $query);
    // periska query apakah ada error
    if (!$result) {
      die("Query gagal dijalankan: " . mysqli_errno($connection) .
        " - " . mysqli_error($connection));
    } else {
      //tampil alert dan akan redirect ke halaman index.php
      //silahkan ganti index.php sesuai halaman yang akan dituju
      echo "<script>alert('Data berhasil ditambah.');window.location='pengguna.php';</script>";
    }
  }
}

function tambahkategori()
{
  global $connection;

  $kategori = $_POST['kategori'];

  $query = "INSERT INTO kategori_buku (kategori) VALUES ('$kategori')";
  $result = mysqli_query($connection, $query);
  // periska query apakah ada error
  if (!$result) {
    die("Query gagal dijalankan: " . mysqli_errno($connection) .
      " - " . mysqli_error($connection));
  } else {
    //tampil alert dan akan redirect ke halaman index.php
    //silahkan ganti index.php sesuai halaman yang akan dituju
    echo "<script>alert('Data berhasil ditambah.');window.location='data-kategori.php';</script>";
  }
}

// MENAMPILKAN SESUATU SESUAI DENGAN INPUTAN USER PADA * SEARCH ENGINE *
function search($keyword)
{
  // search data buku
  $querySearch = "SELECT * FROM buku 
  WHERE
  judul LIKE '%$keyword%' OR
  kategori LIKE '%$keyword%'
  ";
  return queryReadData($querySearch);
}

function searchKategori($keyword)
{
  // search data kategori
  $querySearch = "SELECT * FROM kategori_buku 
  WHERE
  kategori LIKE '%$keyword%'
  ";
  return queryReadData($querySearch);
}

function searchPinjamAdmin($keyword)
{
  // search Peminjaman Admin
  $searchPinjam = "SELECT peminjaman.id, peminjaman.id_buku, buku.cover, buku.judul, peminjaman.nisn, member.nama, user.username, peminjaman.tgl_pinjam, peminjaman.tgl_kembali, peminjaman.status
        FROM peminjaman
        INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
        INNER JOIN member ON peminjaman.nisn = member.nisn
        INNER JOIN user ON peminjaman.id_user = user.id
        WHERE 
            member.nisn LIKE '%$keyword%' OR 
            member.nama LIKE '%$keyword%' OR 
            user.username LIKE '%$keyword%' OR 
            buku.judul LIKE '%$keyword%' OR 
            peminjaman.tgl_pinjam LIKE '%$keyword%' OR 
            peminjaman.tgl_kembali LIKE '%$keyword%'
        ORDER BY peminjaman.id DESC";

  return queryReadData($searchPinjam);
}

function searchPinjamPetugas($keyword)
{
  $id = $_SESSION['id'];
    // Search Peminjaman Petugas
    $searchPinjam = "SELECT peminjaman.id AS peminjaman_id,
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
        WHERE peminjaman.id_user = '$id' AND 
            (member.nisn LIKE '%$keyword%' OR 
            member.nama LIKE '%$keyword%' OR 
            user.username LIKE '%$keyword%' OR 
            buku.judul LIKE '%$keyword%' OR 
            peminjaman.tgl_pinjam LIKE '%$keyword%' OR 
            peminjaman.tgl_kembali LIKE '%$keyword%')
        ORDER BY peminjaman.id DESC";

    return queryReadData($searchPinjam);
}


function searchPinjamMember($keyword)
{
    $nisn = $_SESSION['nisn'];
    $statusArray = [0, 1, 2];
    $statusString = implode(',', $statusArray);

    // Search Peminjaman Member
    $searchPinjam = "SELECT peminjaman.id, peminjaman.id_buku, buku.cover, buku.judul, peminjaman.nisn, member.nama, user.username, peminjaman.tgl_pinjam, peminjaman.tgl_kembali, peminjaman.status
        FROM peminjaman
        INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
        INNER JOIN member ON peminjaman.nisn = member.nisn
        INNER JOIN user ON peminjaman.id_user = user.id
        WHERE peminjaman.nisn = '$nisn' AND status IN ($statusString) AND (
            member.nisn LIKE '%$keyword%' OR 
            member.nama LIKE '%$keyword%' OR 
            user.username LIKE '%$keyword%' OR 
            buku.judul LIKE '%$keyword%' OR 
            peminjaman.tgl_pinjam LIKE '%$keyword%' OR 
            peminjaman.tgl_kembali LIKE '%$keyword%'
        )
        ORDER BY peminjaman.id DESC";

    return queryReadData($searchPinjam);
}

function searchHistory($keyword)
{
    $nisn = $_SESSION['nisn'];

    // Search Peminjaman Member
    $searchPinjam = "SELECT peminjaman.id, peminjaman.id_buku, buku.cover, buku.judul, peminjaman.nisn, member.nama, user.username, peminjaman.tgl_pinjam, peminjaman.tgl_kembali, peminjaman.status
        FROM peminjaman
        INNER JOIN buku ON peminjaman.id_buku = buku.id_buku
        INNER JOIN member ON peminjaman.nisn = member.nisn
        INNER JOIN user ON peminjaman.id_user = user.id
        WHERE peminjaman.nisn = '$nisn' AND status = '3' AND (
            member.nisn LIKE '%$keyword%' OR 
            member.nama LIKE '%$keyword%' OR 
            user.username LIKE '%$keyword%' OR 
            buku.judul LIKE '%$keyword%' OR 
            peminjaman.tgl_pinjam LIKE '%$keyword%' OR 
            peminjaman.tgl_kembali LIKE '%$keyword%'
        )
        ORDER BY peminjaman.id DESC";

    return queryReadData($searchPinjam);
}

function searchMember($keyword)
{
  // search member
  $searchMember = "SELECT * FROM member WHERE 
   nisn LIKE '%$keyword%' OR 
   nama LIKE '%$keyword%' OR 
   kelas LIKE '%$keyword%' OR 
   jurusan LIKE '%$keyword%' OR 
   alamat LIKE '%$keyword%'
   ";
  return queryReadData($searchMember);
}

function searchAkun($keyword)
{
  // search admin
  $searchAkun = "SELECT * FROM user WHERE 
   nama LIKE '%$keyword%' OR 
   username LIKE '%$keyword%' OR 
   sebagai LIKE '%$keyword%'
   ";
  return queryReadData($searchAkun);
}

// DELETE DATA Buku
function deleteBuku($bukuId)
{
  global $connection;

  $queryDeletePinjam = "DELETE FROM peminjaman WHERE id_buku = '$bukuId'
  ";
  mysqli_query($connection, $queryDeletePinjam);

  $queryDeleteBuku = "DELETE FROM buku WHERE id_buku = '$bukuId'
  ";
  mysqli_query($connection, $queryDeleteBuku);

  return mysqli_affected_rows($connection);
}

// DELETE DATA Kategori
function deleteKategori($kategori)
{
  global $connection;

  $queryDeleteBuku = "DELETE FROM buku WHERE kategori = '$kategori'
  ";
  mysqli_query($connection, $queryDeleteBuku);

  $queryDeleteKategori = "DELETE FROM kategori_buku WHERE kategori = '$kategori'
  ";
  mysqli_query($connection, $queryDeleteKategori);

  return mysqli_affected_rows($connection);
}

// DELETE DATA User
function deleteUser($userId)
{
  global $connection;

  $queryDeletePinjam = "DELETE FROM peminjaman WHERE id_user = '$userId'
  ";
  mysqli_query($connection, $queryDeletePinjam);

  $queryDeleteUser = "DELETE FROM user WHERE id = '$userId'
  ";
  mysqli_query($connection, $queryDeleteUser);

  return mysqli_affected_rows($connection);
}

// DELETE DATA Member
function deleteMember($membernisn)
{
  global $connection;

  $queryDeletePinjam = "DELETE FROM peminjaman WHERE nisn = '$membernisn'
  ";
  mysqli_query($connection, $queryDeletePinjam);

  $queryDeletemember = "DELETE FROM member WHERE nisn = '$membernisn'
  ";
  mysqli_query($connection, $queryDeletemember);

  return mysqli_affected_rows($connection);
}

// UPDATE || EDIT DATA BUKU 
function updateBuku($dataBuku)
{
  global $connection;

  $gambarLama = htmlspecialchars($dataBuku["coverLama"]);
  $idBuku = htmlspecialchars($dataBuku["id_buku"]);
  $kategoriBuku = $dataBuku["kategori"];
  $judulBuku = htmlspecialchars($dataBuku["judul"]);
  $pengarangBuku = htmlspecialchars($dataBuku["pengarang"]);
  $penerbitBuku = htmlspecialchars($dataBuku["penerbit"]);
  $tahunTerbit = $dataBuku["thn_terbit"];
  $jumlahHalaman = $dataBuku["jml_halaman"];
  $deskripsiBuku = htmlspecialchars($dataBuku["deskripsi"]);


  // pengecekan mengganti gambar || tidak
  if ($_FILES["cover"]["error"] === 4) {
    $cover = $gambarLama;
  } else {
    $cover = upload();
  }
  // 4 === gagal upload gambar
  // 0 === berhasil upload gambar

  $queryUpdate = "UPDATE buku SET 
  cover = '$cover',
  id_buku = '$idBuku',
  kategori = '$kategoriBuku',
  judul = '$judulBuku',
  pengarang = '$pengarangBuku',
  penerbit = '$penerbitBuku',
  thn_terbit = '$tahunTerbit',
  jml_halaman = $jumlahHalaman,
  deskripsi = '$deskripsiBuku'
  WHERE id_buku = '$idBuku'
  ";

  mysqli_query($connection, $queryUpdate);
  return mysqli_affected_rows($connection);
}

// UPDATE || EDIT DATA Member 
function updateMember($dataMember)
{
  global $connection;

  $nisn = htmlspecialchars($dataMember["nisn"]);
  $nama = htmlspecialchars($dataMember["nama"]);
  $kelas = $dataMember["kelas"];
  $jurusan = $dataMember["jurusan"];
  $password = htmlspecialchars($dataMember["password"]);
  $alamat = htmlspecialchars($dataMember["alamat"]);

  $queryUpdateMember = "UPDATE member SET 
  nisn = '$nisn',
  nama = '$nama',
  password = '$password',
  kelas = '$kelas',
  jurusan = '$jurusan',
  alamat = '$alamat'
  WHERE nisn = '$nisn'
  ";

  mysqli_query($connection, $queryUpdateMember);
  return mysqli_affected_rows($connection);
}

// UPDATE || EDIT DATA Member 
function updatePengguna($dataPengguna)
{
  global $connection;

  $id = htmlspecialchars($dataPengguna["id"]);
  $nama = htmlspecialchars($dataPengguna["nama"]);
  $username = htmlspecialchars($dataPengguna["username"]);
  $password = htmlspecialchars($dataPengguna["password"]);
  $sebagai = $dataPengguna["sebagai"];

  $queryUpdatePengguna = "UPDATE user SET 
  id = '$id',
  nama = '$nama',
  username = '$username',
  password = '$password',
  sebagai = '$sebagai'
  WHERE id = '$id'
  ";

  mysqli_query($connection, $queryUpdatePengguna);
  return mysqli_affected_rows($connection);
}

// === FUNCTION KHUSUS ADMIN END ===


// === FUNCTION KHUSUS MEMBER START ===
// Peminjaman BUKU
function pinjamBuku($dataBuku)
{
  global $connection;

  $idBuku = $dataBuku["id_buku"];
  $nisn = $dataBuku["nisn"];
  $idAdmin = $dataBuku["id_user"];
  $tglPinjam = $dataBuku["tgl_pinjam"];
  $tglKembali = $dataBuku["tgl_kembali"];
  $status = 0;

  $queryPinjam = "INSERT INTO peminjaman (id, id_buku, nisn, id_user, tgl_pinjam, tgl_kembali, status) VALUES(null, '$idBuku', $nisn, $idAdmin, '$tglPinjam', '$tglKembali', '$status')";
  mysqli_query($connection, $queryPinjam);
  return mysqli_affected_rows($connection);
}

// === FUNCTION KHUSUS MEMBER END ===
