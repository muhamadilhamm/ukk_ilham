<?php
session_start();
include "../loginSystem/connect.php";

if (isset($_SESSION['sebagai'])) {
  if ($_SESSION['sebagai'] == 'petugas') {
    header("Location: ../petugas/index.php");
    exit;
  } elseif ($_SESSION['sebagai'] == 'admin') {
    header("Location: ../admin/index.php");
    exit;
  }
}

if (isset($_POST['btn-login'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query to check user credentials
  $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
  $result = $connect->query($query);

  if (mysqli_num_rows($result) === 1) {
    $rows = mysqli_fetch_assoc($result);
    $_SESSION['sebagai'] = $rows['sebagai'];
    $_SESSION['username'] = $rows['username'];
    $_SESSION['nama'] = $rows['nama'];
    $_SESSION['id'] = $rows['id'];

    if ($rows['sebagai'] == 'petugas') {
      // Sesuaikan pesan alert sesuai kebutuhan
      echo "<script>alert('Login berhasil sebagai Petugas!'); window.location.href='../petugas/index.php';</script>";
      exit;
    } elseif ($rows['sebagai'] == 'admin') {
      // Sesuaikan pesan alert sesuai kebutuhan
      echo "<script>alert('Login berhasil sebagai Admin!'); window.location.href='../admin/index.php';</script>";
      exit;
    }
  } else {
    // Login failed
    echo "<script>alert('Username atau Password Anda salah. Silahkan coba lagi!')</script>";
  }
}
$connect->close();
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
      max-width: 400px;
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
        <h3 class="">Login Admin</h3>
      </div>
      <div class="card-body">
        <form action="" method="post">
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
          </div>
          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
          </div>
          <div class="text-center">
            <button type="submit" name="btn-login" class="btn btn-primary mt-2" style="width:200px; height:50px;">Login</button>
            </div>
            </div>
            <div class="card-footer text-center">
            <p class="mt-2">Anda siswa? <a href="login.php" class="btn-link text-black">Login siswa</a></p>
          </div>
        </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>