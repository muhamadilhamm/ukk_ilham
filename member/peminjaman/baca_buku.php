<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="../tpm-logo.png">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Readbooks.com</title>
    <link rel="icon" href="../../assets/iconblack.png" type="image/png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="../assets/images/icon/logo-tpm.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- amchart css -->
</head>

<body>
    <center>
        <?php

        include '../../config/config.php';

        $idBuku = $_GET["id_buku"];
        $query = queryReadData("SELECT * FROM buku WHERE id_buku = '$idBuku'");
        ?>
        <?php foreach ($query as $item) : ?>
            <a href="daftar_pinjam.php" class="btn btn-dark btn-block w-100" style="display: inline-block; padding: 10px; text-align: center; text-decoration: none; font-size: 16px; border: 1px solid #000; background-color: #333; color: #fff; cursor: pointer; border-radius: 0;">
                Kembali
            </a>

            <embed type="application/pdf" src="../../assets/isi-buku/<?php echo $item['isi_buku']; ?>#toolbar=0" width="100%" height="585px"></embed>
        <?php
        endforeach;
        ?>
    </center>


</body>

</html>