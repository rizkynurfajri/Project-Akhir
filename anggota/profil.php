<?php
include '../koneksi.php';
include '../proteksi.php';
include 'sidebar.php';

// Ambil ID user dari session
$metode = $_SESSION['username']['id'];

// Ambil data anggota berdasarkan nis
$edit = $koneksi->prepare("SELECT * FROM anggota WHERE nis = ?");
$edit->execute([$metode]);
$hasil = $edit->fetch();

// Ambil data level user untuk select option default
$a = $hasil['id_level'];
$asd = $koneksi->prepare("SELECT * FROM level WHERE id_level = ?");
$asd->execute([$a]);
$lvl = $asd->fetch();

// Ambil semua level untuk pilihan di dropdown
$zxc = $koneksi->prepare("SELECT * FROM level");
$zxc->execute();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Profil Anggota</title>
  <link rel="stylesheet" href="../img/csskonten.css">
  <link rel="stylesheet" href="../css/bootstrap.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="main">
  <button type="button" onclick="history.back()" class="btn btn-danger btn-lg">
    <i class="fa fa-fw fa-reply"></i> Kembali
  </button>
  <div class="container col-md-8">
    <h1 class="text-center">Profil</h1>
    <br>
    <form action="profil_proses.php?prabowo=ubah" method="post">

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">NIS</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="nis" value="<?= htmlspecialchars($hasil['nis']) ?>" readonly>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Nama</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="nama" value="<?= htmlspecialchars($hasil['nama_anggota']) ?>" required>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Jenis Kelamin</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="jk" value="<?= htmlspecialchars($hasil['jk']) ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Alamat</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="alamat" value="<?= htmlspecialchars($hasil['alamat']) ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">No Telp</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="no_telp" value="<?= htmlspecialchars($hasil['no_telp']) ?>">
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Level</label>
        <div class="col-sm-9">
          <select class="form-control" name="level" required>
            <option value="<?= $lvl['id_level'] ?>"><?= htmlspecialchars($lvl['nama_level']) ?></option>
            <?php while($cat = $zxc->fetch()) { 
              if ($cat['id_level'] != $lvl['id_level']) { ?>
                <option value="<?= $cat['id_level'] ?>"><?= htmlspecialchars($cat['nama_level']) ?></option>
            <?php } } ?>
          </select>
        </div>
      </div>

      <div class="row mb-3">
        <label class="col-sm-3 col-form-label">Status</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="status" value="<?= htmlspecialchars($hasil['status']) ?>">
        </div>
      </div>

      <button type="submit" class="btn btn-success">Edit</button>
    </form>
  </div>
</div>

</body>
</html>
