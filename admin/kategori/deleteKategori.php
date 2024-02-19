<?php
require "../../config/config.php";

$kategori = $_GET["kategori"];
if(deleteKategori($kategori) > 0) {
    echo "<script>
    alert('Kategori berhasil dihapus!');
    document.location.href = 'data-kategori.php';
    </script>";
  }else {
    echo "<script>
    alert('Kategori gagal dihapus!');
    document.location.href = 'data-kategori.php';
    </script>";
}
?>