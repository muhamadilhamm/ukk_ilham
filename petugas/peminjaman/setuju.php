<?php  

include '../../config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Mendapatkan harga saat ini dari database
    $get_harga_query = mysqli_query($connection, "SELECT harga FROM peminjaman WHERE id='$id'");
    $row = mysqli_fetch_assoc($get_harga_query);
    $harga = $row['harga']; // Mengambil harga saat ini

    // Memperbarui status menjadi Lunas dan harga tetap sama
    $query = mysqli_query($connection, "UPDATE peminjaman SET status=1, harga='$harga (Lunas)' WHERE id='$id' ");

    if($query) {
        echo '<script>alert("Buku Telah Disetujui."); window.location.href = "data-peminjaman.php";</script>';
    } else {
        echo "error : " . mysqli_error($connection);
    }
}
?>