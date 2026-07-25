<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Cek Hak Akses Admin (Sesuai Standar Keamanan Aplikasi Anda)
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki otoritas untuk menghapus data.');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// 2. Konek ke Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . mysqli_connect_error() . "');
            window.location.href = '../../index.php?page=surat-undangan';
          </script>";
    exit;
}

// 3. Validasi Parameter ID yang Dikirim via URL
if (!isset($_GET['id']) || empty(trim($_GET['id']))) {
    echo "<script>
            alert('ID Surat Undangan tidak valid atau tidak ditemukan!');
            window.location.href = '../../index.php?page=surat-undangan';
          </script>";
    exit;
}

$id_undangan = mysqli_real_escape_string($koneksi, $_GET['id']);

// 4. Eksekusi Proses Penghapusan Data Utama
// Catatan: Relasi tabel ON DELETE CASCADE akan otomatis menghapus baris penerima di tb_undangan_tujuan
$sql_hapus = "DELETE FROM `tb_surat_undangan` WHERE `id_undangan` = '$id_undangan'";

if (mysqli_query($koneksi, $sql_hapus)) {
    // Jika ada record yang terpengaruh/berhasil dihapus
    if (mysqli_affected_rows($koneksi) > 0) {
        echo "<script>
                alert('Surat undangan beserta daftar penerima berhasil dihapus secara permanen.');
                window.location.href = '../../index.php?page=surat-undangan';
              </script>";
    } else {
        echo "<script>
                alert('Data gagal dihapus. Surat undangan mungkin sudah tidak ada atau telah dihapus sebelumnya.');
                window.location.href = '../../index.php?page=surat-undangan';
              </script>";
    }
} else {
    // Jika query SQL error
    echo "<script>
            alert('Terjadi kesalahan sistem saat mencoba menghapus data: " . mysqli_real_escape_string($koneksi, mysqli_error($koneksi)) . "');
            window.location.href = '../../index.php?page=surat-undangan';
          </script>";
}

// Tutup koneksi database
mysqli_close($koneksi);
?>