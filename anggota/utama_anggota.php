<?php
include '../koneksi.php';
include '../proteksi.php';
include 'sidebar.php';

// Ambil jumlah semua buku
$buku = $koneksi->prepare("SELECT * FROM buku");
$buku->execute();
$jumbuku = $buku->rowCount();

// Ambil jumlah peminjaman berdasarkan NIS pengguna login
$metode = $_SESSION['username']['id'];
$pinjam = $koneksi->prepare("SELECT * FROM peminjaman WHERE nis = ?");
$pinjam->execute([$metode]);
$jumpinjam = $pinjam->rowCount();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard Anggota</title>
    <link rel="stylesheet" href="../img/csskonten.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="main">
    <h1><i class="fa fa-home"></i> Dashboard</h1>
    <hr>

    <div class="row">
        <!-- Kartu Buku -->
        <div class="col-lg-3 col-6">
            <div class="card bg-success text-white">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <i class="fa fa-book fa-2x"></i>
                        </div>
                        <div class="col-md-9 text-right">
                            <div class="h4"><?= $jumbuku; ?></div>
                            <div>Buku</div>
                        </div>
                    </div>
                </div>
                <a href="buku.php">
                    <div class="card-footer bg-light text-dark">
                        <span class="float-left">Lihat Semua</span>
                        <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Kartu Peminjaman -->
        <div class="col-lg-3 col-6">
            <div class="card bg-danger text-white">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-3">
                            <i class="fa fa-folder-open fa-2x"></i>
                        </div>
                        <div class="col-md-9 text-right">
                            <div class="h4"><?= $jumpinjam; ?></div>
                            <div>Pinjam</div>
                        </div>
                    </div>
                </div>
                <a href="pinjam.php">
                    <div class="card-footer bg-light text-dark">
                        <span class="float-left">Lihat Semua</span>
                        <span class="float-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>

</div>

</body>
</html>
