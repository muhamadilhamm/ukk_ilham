<?php
require "../../config/config.php";

$membernisn = $_GET["nisn"];
if(deleteMember($membernisn) > 0) {
    echo "<script>
    alert('Buku berhasil dihapus!');
    document.location.href = 'data-member.php';
    </script>";
  }else {
    echo "<script>
    alert('Buku gagal dihapus!');
    document.location.href = 'data-member.php';
    </script>";
}
?>