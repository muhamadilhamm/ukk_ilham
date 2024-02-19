<?php

include '../../config/config.php';

    // Mendapatkan waktu saat ini
    $currentDate = date('Y-m-d');

    // Mengupdate status peminjaman yang sudah melewati tanggal akhir
    $sql = "UPDATE peminjaman SET status='3' WHERE tgl_kembali < '$currentDate' AND status = '1' ";

    if ($connection->query($sql) === TRUE) {
        echo '<script>alert("Peminjaman berhasil dinonaktifkan."); window.location.href="data-peminjaman.php";</script>';
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
