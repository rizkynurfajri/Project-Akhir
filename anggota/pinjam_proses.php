<?php
include '../koneksi.php';

$proses = $_GET['prabowo'] ?? null;

if ($proses === 'tambah') {
    // Ambil data dari form POST dengan validasi awal
    $kd_pinjam = $_POST['kd_pinjam'] ?? '';
    $nis = $_POST['nis'] ?? '';
    $kd_buku = $_POST['kd_buku'] ?? '';
    $tgl_pinjam = $_POST['tgl_pinjam'] ?? '';
    $status_pinjam = $_POST['status_pinjam'] ?? '';
    $jumlah = isset($_POST['jmlh_pinjam']) ? intval($_POST['jmlh_pinjam']) : 0;

    // Validasi input wajib
    if (empty($kd_pinjam) || empty($nis) || empty($kd_buku) || empty($tgl_pinjam) || empty($status_pinjam) || $jumlah <= 0) {
        echo "<script>
                alert('Data input tidak lengkap atau jumlah pinjam harus lebih dari 0');
                window.location='buku.php';
              </script>";
        exit;
    }

    try {
        // Mulai transaksi supaya aman
        $koneksi->beginTransaction();

        // Cek stok buku saat ini
        $query = $koneksi->prepare("SELECT stok FROM buku WHERE kode_buku = :kd_buku FOR UPDATE");
        $query->execute(['kd_buku' => $kd_buku]);
        $buku = $query->fetch();

        if (!$buku) {
            throw new Exception("Buku dengan kode $kd_buku tidak ditemukan.");
        }

        $stok_sekarang = intval($buku['stok']);
        $stok_baru = $stok_sekarang - $jumlah;

        if ($stok_baru < 0) {
            throw new Exception("Stok buku tidak mencukupi. Stok tersedia: $stok_sekarang.");
        }

        // Insert data peminjaman
        $insertPeminjaman = $koneksi->prepare("INSERT INTO peminjaman(kode_pinjam, nis, kode_buku, tgl_pinjam, status_pinjam, jumlah) VALUES (?, ?, ?, ?, ?)");
        $insertPeminjaman->execute([$kd_pinjam, $nis, $kd_buku, $tgl_pinjam, $status_pinjam, $jumlah]);

        // Update stok buku
        $updateStok = $koneksi->prepare("UPDATE buku SET stok = :stok WHERE kode_buku = :kode_buku");
        $updateStok->execute(['stok' => $stok_baru, 'kode_buku' => $kd_buku]);

        // Commit transaksi
        $koneksi->commit();

        echo "<script>
                alert('Peminjaman berhasil.');
                window.location='buku.php';
              </script>";

    } catch (Exception $e) {
        // Rollback jika ada error
        $koneksi->rollBack();
        $msg = $e->getMessage();
        echo "<script>
                alert('Peminjaman gagal: $msg');
                window.location='buku.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Aksi tidak dikenal.');
            window.location='buku.php';
          </script>";
}
?>
