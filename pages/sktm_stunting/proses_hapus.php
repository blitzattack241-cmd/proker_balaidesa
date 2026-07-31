<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Cek Hak Akses Admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki otoritas.');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// 2. Koneksi ke Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . mysqli_connect_error() . "');
            window.history.back();
          </script>";
    exit;
}

// 3. Validasi Parameter ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    // Ambil ID dan amankan dari SQL Injection
    $id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Cek apakah data memang ada di database sebelum dihapus
    $cek_data = mysqli_query($koneksi, "SELECT id_sktm FROM tb_sktm_stunting WHERE id_sktm = '$id_sktm'");
    
    if (mysqli_num_rows($cek_data) > 0) {
        // 4. Jalankan Query Hapus Data
        $query_hapus = "DELETE FROM tb_sktm_stunting WHERE id_sktm = '$id_sktm'";

        if (mysqli_query($koneksi, $query_hapus)) {
            require_once __DIR__ . '/../../includes/nomor_surat_helper.php';
            renumerasiNomorSuratGlobal($koneksi);
            echo "<script>
                    alert('Sukses! Data SKTM Stunting berhasil dihapus.');
                    window.location.href = '../../index.php?page=sktm-stunting';
                  </script>";
            exit;
        } else {
            echo "<script>
                    alert('Gagal menghapus data! Error: " . mysqli_error($koneksi) . "');
                    window.location.href = '../../index.php?page=sktm-stunting';
                  </script>";
            exit;
        }
    } else {
        echo "<script>
                alert('Data tidak ditemukan atau sudah dihapus sebelumnya.');
                window.location.href = '../../index.php?page=sktm-stunting';
              </script>";
        exit;
    }
} else {
    // Jika diakses tanpa parameter ID yang valid
    echo "<script>
            alert('ID Surat tidak valid!');
            window.location.href = '../../index.php?page=sktm-stunting';
          </script>";
    exit;
}
?>