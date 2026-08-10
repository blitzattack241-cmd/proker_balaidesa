<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi Halaman: Hanya Admin yang boleh mengeksekusi proses hapus
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki otoritas untuk menghapus data.'); 
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// Koneksi Database
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . mysqli_connect_error() . "'); 
            window.location.href = '../../index.php?page=surat-kematian';
          </script>";
    exit;
}

// Validasi Parameter ID
if (isset($_GET['id']) && !empty(trim($_GET['id']))) {
    $id_surat = intval($_GET['id']); // Casting ke integer untuk keamanan tambahan

    // Target nama tabel dinamis (Cek apakah menggunakan tb_surat_kematian atau surat_kematian)
    $tableTarget = 'tb_surat_kematian';
    $checkTable = mysqli_query($koneksi, "SHOW TABLES LIKE 'surat_kematian'");
    if ($checkTable && mysqli_num_rows($checkTable) > 0) {
        $tableTarget = 'surat_kematian';
    }

    // Menggunakan Prepared Statement untuk keamanan dari SQL Injection
    $sqlDelete = "DELETE FROM `$tableTarget` WHERE id_surat = ?";
    $stmt = mysqli_prepare($koneksi, $sqlDelete);

    if ($stmt) {
        // Bind parameter id ke statement
        mysqli_stmt_bind_param($stmt, "i", $id_surat);

        // Eksekusi query
        if (mysqli_stmt_execute($stmt)) {
            require_once __DIR__ . '/../../includes/nomor_surat_helper.php';
            renumerasiNomorSuratGlobal($koneksi);
            echo "<script>
                    alert('Data Surat Kematian Berhasil Dihapus!'); 
                    window.location.href = '../../index.php?page=surat-kematian';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data: " . mysqli_error($koneksi) . "'); 
                    window.location.href = '../../index.php?page=surat-kematian';
                  </script>";
        }

        // Tutup statement
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
                alert('Gagal mempersiapkan query hapus.'); 
                window.location.href = '../../index.php?page=surat-kematian';
              </script>";
    }

} else {
    // Jika diakses langsung tanpa ID yang valid
    echo "<script>
            alert('ID Data tidak ditemukan atau tidak valid!'); 
            window.location.href = '../../index.php?page=surat-kematian';
          </script>";
}

// Tutup koneksi database
mysqli_close($koneksi);
?>