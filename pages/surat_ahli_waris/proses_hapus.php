<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki hak akses.');
            window.location.href = '../../index.php?page=surat_waris';
          </script>";
    exit;
}

$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_waris = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Memulai Transaksi Database agar aman
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Hapus data detail anak yang berelasi dengan id_waris
        $query_hapus_anak = mysqli_query($koneksi, "DELETE FROM tb_waris_detail_anak WHERE id_waris = '$id_waris'");
        
        // 2. Hapus data detail saksi yang berelasi dengan id_waris
        $query_hapus_saksi = mysqli_query($koneksi, "DELETE FROM tb_waris_detail_saksi WHERE id_waris = '$id_waris'");

        // 3. Hapus data utama surat waris
        $query_hapus_utama = mysqli_query($koneksi, "DELETE FROM tb_surat_waris WHERE id_waris = '$id_waris'");

        // Jika semua query berhasil, lakukan commit ke database
        mysqli_commit($koneksi);

        echo "<script>
                alert('Data arsip surat ahli waris berhasil dihapus secara permanen!');
                window.location.href = '../../index.php?page=surat_waris';
              </script>";
    } catch (Exception $e) {
        // Jika ada query yang gagal, batalkan semua perubahan (rollback)
        mysqli_rollback($koneksi);

        echo "<script>
                alert('Gagal menghapus data arsip! Terjadi kesalahan sistem.');
                window.location.href = '../../index.php?page=surat_waris';
              </script>";
    }
} else {
    echo "<script>
            alert('ID Data tidak valid atau tidak ditemukan!');
            window.location.href = '../../index.php?page=surat_waris';
          </script>";
}
?>