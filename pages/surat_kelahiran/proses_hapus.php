<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman Admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>alert('Akses ditolak!'); window.location.href = 'index.php?page=dashboard';</script>";
    exit;
}

// Koneksi Database
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Ambil parameter ID
$id = $_GET['id'] ?? 0;
$id = (int) $id;

if ($id > 0) {
    // Cari nama tabel aktif
    $tableTarget = 'tb_surat_kelahiran';
    $checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_kelahiran'");
    if ($checkTable && mysqli_num_rows($checkTable) > 0) {
        $tableTarget = 'surat_kelahiran';
    }

    // Eksekusi Hapus Data
    $sqlDelete = "DELETE FROM `$tableTarget` WHERE id_surat = $id";

    if (mysqli_query($koneksi, $sqlDelete)) {
        require_once __DIR__ . '/../../includes/nomor_surat_helper.php';
        renumerasiNomorSuratGlobal($koneksi);
        echo "<script>alert('Arsip Surat Kelahiran berhasil dihapus!'); window.location.href = 'index.php?page=surat-kelahiran';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . mysqli_real_escape_string($koneksi, mysqli_error($koneksi)) . "'); window.location.href = 'index.php?page=surat-kelahiran';</script>";
    }
} else {
    echo "<script>alert('ID Data tidak valid!'); window.location.href = 'index.php?page=surat-kelahiran';</script>";
}
?>