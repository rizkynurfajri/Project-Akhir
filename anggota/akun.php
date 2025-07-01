<?php
include '../koneksi.php';
include '../proteksi.php';
include 'sidebar.php';
?>

<link rel="stylesheet" href="../img/csskonten.css">
<link rel="stylesheet" href="../css/bootstrap.css">

<?php
$metode = $_SESSION['username']['id'];

// Gunakan placeholder ? untuk mencegah SQL Injection
$edit = $koneksi->prepare("SELECT * FROM user WHERE id = ?");
$edit->execute([$metode]);
$hasil = $edit->fetch();
?>

<div class="main">
  <button type="button" onclick="history.back()" class="btn btn-danger btn-lg">
    <i class="fa fa-fw fa-reply"></i> Kembali
  </button>

  <div class="container col-md-8 mt-4">
    <h1 class="text-center">Profil</h1>
    <form action="akun_proses.php?prabowo=ubah" method="post" class="mt-3">

      <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="nis">ID</label>
        <div class="col-sm-9">
          <input type="text" id="nis" class="form-control" name="nis" value="<?= htmlspecialchars($hasil['id']); ?>" readonly>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="nama">Nama</label>
        <div class="col-sm-9">
          <input type="text" id="nama" class="form-control" name="nama" value="<?= htmlspecialchars($hasil['nama']); ?>" required>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="pw">Password</label>
        <div class="col-sm-9">
          <input type="text" id="pw" class="form-control" name="pw" value="<?= htmlspecialchars($hasil['password']); ?>">
        </div>
      </div>

      <div class="form-group row">
        <label class="col-sm-3 col-form-label" for="hak">Hak</label>
        <div class="col-sm-9">
          <input type="text" id="hak" class="form-control" name="hak" value="<?= htmlspecialchars($hasil['hak']); ?>" readonly>
        </div>
      </div>

      <button type="submit" class="btn btn-success">Edit</button>
    </form>
  </div>
</div>
