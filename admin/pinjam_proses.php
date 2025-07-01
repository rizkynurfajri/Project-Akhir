<?php
include '../koneksi.php';

$proses = $_GET['prabowo'] ?? null;

if ($proses === 'tambah') {
    // Cek apakah data POST tersedia
    if (!isset($_POST['kd_pinjam'], $_POST['nis'], $_POST['kd_buku'], $_POST['tgl_pinjam'], $_POST['jmlh_pinjam'])) {
        echo "<script>
                alert('Form tidak lengkap.');
                window.location='peminjaman.php';
              </script>";
        exit;
    }

    // Ambil data dari form POST
    $kd_pinjam = $_POST['kd_pinjam'];
    $nis = $_POST['nis'];
    $kd_buku = $_POST['kd_buku'];
    $tgl_pinjam = $_POST['tgl_pinjam'];
    $jumlah = intval($_POST['jmlh_pinjam']);

    // Validasi input
    if (empty($kd_pinjam) || empty($nis) || empty($kd_buku) || empty($tgl_pinjam) || $jumlah <= 0) {
        echo "<script>
                alert('Data tidak lengkap atau jumlah pinjam harus lebih dari 0.');
                window.location='peminjaman.php';
              </script>";
        exit;
    }

    try {
        // Mulai transaksi
        $koneksi->beginTransaction();

        // Cek stok buku
        $query = $koneksi->prepare("SELECT stok FROM buku WHERE kode_buku = :kd_buku FOR UPDATE");
        $query->execute(['kd_buku' => $kd_buku]);
        $buku = $query->fetch();

        if (!$buku) {
            throw new Exception("Buku dengan kode $kd_buku tidak ditemukan.");
        }

        $stok_sekarang = intval($buku['stok']);
        $stok_baru = $stok_sekarang - $jumlah;

        if ($stok_baru < 0) {
            throw new Exception("Stok buku tidak mencukupi. Tersisa: $stok_sekarang.");
        }

        // Simpan data peminjaman
        $insert = $koneksi->prepare("INSERT INTO peminjaman(kode_pinjam, nis, kode_buku, tgl_pinjam, jumlah) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$kd_pinjam, $nis, $kd_buku, $tgl_pinjam, $jumlah]);

        // Update stok buku
        $update = $koneksi->prepare("UPDATE buku SET stok = :stok WHERE kode_buku = :kode_buku");
        $update->execute(['stok' => $stok_baru, 'kode_buku' => $kd_buku]);

        // Selesai transaksi
        $koneksi->commit();

        echo "<script>
                alert('Peminjaman berhasil.');
                window.location='peminjaman.php';
              </script>";
    } catch (Exception $e) {
        $koneksi->rollBack();
        $msg = $e->getMessage();
        echo "<script>
                alert('Peminjaman gagal: $msg');
                window.location='peminjaman.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Aksi tidak dikenal.');
            window.location='peminjaman.php';
          </script>";
}
?>
