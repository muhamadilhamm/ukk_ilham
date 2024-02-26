<?php
require "../../config/config.php";

$idPeminjaman = $_GET["id"];

// Perbarui status peminjaman menjadi "Sudah dikonfirmasi"
$query = "UPDATE peminjaman SET status = '3' WHERE id = $idPeminjaman ";
if (mysqli_query($connection, $query)) {
    // Redirect kembali ke halaman petugas
    echo "<script>alert('Buku berhasil dikembalikan!'); window.location.href='../riwayat/history.php';</script>";
    exit;
} else {
    echo "Error updating record: " . mysqli_error($connection);
}
?>
