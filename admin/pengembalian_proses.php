<?php
include '../koneksi.php';

$proses = $_GET['prabowo'];

if ($proses == 'tambah') {
    $kd_kembali   = $_POST['kd_kembali'];
    $kd_pinjam    = $_POST['kd_pinjam'];
    $nis          = $_POST['nis'];
    $kd_buku      = $_POST['kd_buku'];
    $status       = $_POST['status_kembali'];
    $tgl_kembali  = $_POST['tgl_kembali'];
    $jumlah       = $_POST['jumlah'];

    // Ambil data buku berdasarkan kode_buku
    $query = $koneksi->prepare("SELECT * FROM buku WHERE kode_buku = :kode_buku");
    $query->execute([':kode_buku' => $kd_buku]);
    $sql = $query->fetch();

    $stok_awal = $sql['stok'];
    $stok_baru = $stok_awal + $jumlah;

    // Tambah data ke tabel pengembalian
    $tambah = $koneksi->prepare("INSERT INTO pengembalian(kode_kembali, kode_pinjam, nis, kode_buku, status_pengembalian, tgl_pengembalian) VALUES(?,?,?,?,?,?)");
    $tambah->execute([$kd_kembali, $kd_pinjam, $nis, $kd_buku, $status, $tgl_kembali]);
    $insert = $tambah->rowCount();

    // Update stok buku
    $ubah = $koneksi->prepare("UPDATE buku SET stok = :stok WHERE kode_buku = :kode_buku");
    $ubah->execute([
        ':stok' => $stok_baru,
        ':kode_buku' => $kd_buku
    ]);

    // Update status peminjaman
    $ubah2 = $koneksi->prepare("UPDATE peminjaman SET status_pinjam = :status WHERE kode_pinjam = :kode_pinjam");
    $ubah2->execute([
        ':status' => $status,
        ':kode_pinjam' => $kd_pinjam
    ]);

    if ($insert > 0) {
        echo "<script>
            alert('Pengembalian berhasil');
            window.location='pengembalian.php';
        </script>";
    } else {
        echo "<script>
            alert('Pengembalian gagal');
            window.location='pengembalian.php';
        </script>";
    }
}
?>
