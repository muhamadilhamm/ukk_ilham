<?php
// FILE LOGIN SYSTEM 
$host = "127.0.0.1";
$username = "root";
$password = "";
$database = "ukk_ilham";
$connect = mysqli_connect($host, $username, $password, $database);


/* SIGN UP Member */
function signUp($data) {
  global $connect;
  
  $nisn = htmlspecialchars($data["nisn"]);
  $nama = htmlspecialchars(strtolower($data["nama"]));
  $password = htmlspecialchars($data["password"]);
  $kelas = htmlspecialchars($data["kelas"]);
  $jurusan = htmlspecialchars($data["jurusan"]);
  $alamat = htmlspecialchars($data["alamat"]);
  
    // cek nisn sudah ada / belum 
  $nisnResult = mysqli_query($connect, "SELECT nisn FROM member WHERE nisn = $nisn");
  if(mysqli_fetch_assoc($nisnResult)) {
    echo "<script>
    alert('Nisn sudah terdaftar, silahkan gunakan nisn lain!');
    </script>";
    return 0;
  }
  

  $querySignUp = "INSERT INTO member VALUES('$nisn', '$nama', '$password', '$kelas', '$jurusan', '$alamat')";
  mysqli_query($connect, $querySignUp);
  return mysqli_affected_rows($connect);
  
}

?>
