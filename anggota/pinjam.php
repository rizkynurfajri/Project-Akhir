<?php
include '../koneksi.php';
include '../proteksi.php';
include 'sidebar.php';

// Ambil data pinjaman berdasarkan pencarian atau ID pengguna
if (isset($_POST['search']) && !empty(trim($_POST['search']))) {
    $param = "%" . trim($_POST['search']) . "%";
    $query = $koneksi->prepare("
        SELECT * FROM peminjaman 
        WHERE kode_pinjam LIKE :param OR kode_buku LIKE :param
    ");
    $query->bindParam(':param', $param);
} else {
    $userId = $_SESSION['username']['id'];
    $query = $koneksi->prepare("
        SELECT * FROM peminjaman 
        WHERE nis = :nis
    ");
    $query->bindParam(':nis', $userId);
}
$query->execute();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../img/csskonten.css">
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Data Peminjaman</title>
</head>
<body>

<div class="main">
    <b><i class="fa fa-folder-open"></i> Data Peminjaman</b>
    <hr>

    <form method="post" action="">
        <div class="input-group input-group-sm" style="width: 250px; margin-left: auto;">
            <input type="text" name="search" class="form-control float-right" placeholder="Cari kode pinjam atau buku">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>

    <table class="table table-hover mt-3">
        <thead class="thead-dark" align="center">
            <tr>
                <th>No</th>
                <th>Kode Pinjam</th>
                <th>NIS</th>
                <th>Kode Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Jumlah</th>
                <th>Status Pinjam</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php 
        $i = 1;
        while ($data = $query->fetch(PDO::FETCH_ASSOC)): ?>
            <tr align="center">
                <td><?= $i++ ?></td>
                <td><?= htmlspecialchars($data['kode_pinjam']) ?></td>
                <td><?= htmlspecialchars($data['nis']) ?></td>
                <td><?= htmlspecialchars($data['kode_buku']) ?></td>
                <td><?= htmlspecialchars($data['tgl_pinjam']) ?></td>
                <td><?= htmlspecialchars($data['jumlah']) ?></td>
                <td><?= htmlspecialchars($data['status_pinjam']) ?></td>
                <td><?= htmlspecialchars($data['status']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
