<?php
require "../../config/config.php";

$bukuId = $_GET["id_buku"];
if(deleteBuku($bukuId) > 0) {
    echo "<script>
    alert('Buku berhasil dihapus!');
    document.location.href = 'data-buku.php';
    </script>";
  }else {
    echo "<script>
    alert('Buku gagal dihapus!');
    document.location.href = 'data-buku.php';
    </script>";
}
?>