<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==========================================
// 1. PROTEKSI HALAMAN ADMIN (SECURITY LOCK)
// ==========================================
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki hak akses untuk menghapus data.');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// ==========================================
// 2. KONEKSI DATABASE
// ==========================================
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . mysqli_connect_error() . "');
            window.location.href = '../../index.php?page=surat-pengantar';
          </script>";
    exit;
}

// ==========================================
// 3. VALIDASI PARAMETER ID SURAT
// ==========================================
// Memastikan parameter 'id' dikirimkan melalui URL (Contoh: hapus.php?id=5)
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>
            alert('ID Surat tidak valid atau tidak ditemukan!');
            window.location.href = '../../index.php?page=surat-pengantar';
          </script>";
    exit;
}

// SQL Injection Prevention
$id_surat = mysqli_real_escape_string($koneksi, $_GET['id']);

// ==========================================
// 4. PROSES HAPUS DATA
// ==========================================
// Query hapus berdasarkan Primary Key 'id_surat' sesuai struktur phpMyAdmin Anda
$query_hapus = mysqli_query($koneksi, "DELETE FROM `tb_surat_pengantar` WHERE `id_surat` = '$id_surat'");

if ($query_hapus) {
    require_once __DIR__ . '/../../includes/nomor_surat_helper.php';
    renumerasiNomorSuratGlobal($koneksi);
    // Jika berhasil dihapus, arahkan kembali dengan notifikasi sukses
    echo "<script>
            alert('Data surat pengantar berhasil dihapus!');
            window.location.href = '../../index.php?page=surat-pengantar';
          </script>";
} else {
    // Jika gagal, tampilkan pesan error dari MySQL
    echo "<script>
            alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
            window.location.href = '../../index.php?page=surat-pengantar';
          </script>";
}

// Tutup koneksi ke database
mysqli_close($koneksi);
?>