<?php
require "../../config/config.php";

$userId = $_GET["id"];
if(deleteUser($userId) > 0) {
    echo "<script>
    alert('Buku berhasil dihapus!');
    document.location.href = 'pengguna.php';
    </script>";
  }else {
    echo "<script>
    alert('Buku gagal dihapus!');
    document.location.href = 'pengguna.php';
    </script>";
}
?>