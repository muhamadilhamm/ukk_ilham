<?php  

include '../../config/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Periksa apakah harga sudah lunas
    $check_query = mysqli_query($connection, "SELECT harga FROM peminjaman WHERE id='$id'");
    $result = mysqli_fetch_assoc($check_query);
    $harga = $result['harga'];

    // Periksa apakah harga belum lunas (tidak mengandung kata "(Lunas)")
    if (strpos($harga, '(Lunas)') === false) { 
        $query = mysqli_query($connection, "UPDATE peminjaman SET status=2 WHERE id='$id' ");

        if($query) {
            echo '<script>alert("Buku Tidak Disetujui."); window.location.href = "data-peminjaman.php";</script>';
        } else {
            echo "error : " . mysqli_error($connection);
        }
    } else {
        // Jika harga sudah lunas, arahkan pengguna ke halaman sebelumnya dan tampilkan pesan
        echo '<script>alert("Buku sudah lunas. Anda tidak bisa menolak peminjaman."); window.location.href = "data-peminjaman.php";</script>';
    }
} 
?>