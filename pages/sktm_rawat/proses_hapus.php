<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Verifikasi Hak Akses Admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak! Anda tidak memiliki izin untuk menghapus data.');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// 2. Hubungkan ke Database
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . mysqli_connect_error() . "');
            window.location.href = '../../index.php?page=sktm-rawat';
          </script>";
    exit;
}

// 3. Tangkap Parameter ID
$id_sktm = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_sktm > 0) {
    // A. Ambil nama-nama file foto yang tersimpan di database sebelum datanya dihapus
    $query_foto = mysqli_query($koneksi, "SELECT foto_depan, foto_ruang_tamu, foto_kamar, foto_dapur, foto_toilet FROM tb_sktm_rawat WHERE id_sktm = $id_sktm");
    
    if ($query_foto && mysqli_num_rows($query_foto) > 0) {
        $data_foto = mysqli_fetch_assoc($query_foto);
        $upload_dir = "../../assets/img/sktm_rawat/"; // Menyesuaikan path folder dari dalam subfolder pages/sktm_rawat/

        // B. Hapus file fisik foto dari server (jika file tersebut ada)
        $foto_fields = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];
        foreach ($foto_fields as $field) {
            $nama_file = $data_foto[$field];
            if (!empty($nama_file)) {
                $path_file = $upload_dir . $nama_file;
                if (file_exists($path_file)) {
                    unlink($path_file); // Hapus file fisik secara permanen
                }
            }
        }
    }

    // C. Hapus Data Pasien (Tabel Detail) terlebih dahulu untuk menghindari integritas data error
    $hapus_detail = mysqli_query($koneksi, "DELETE FROM tb_sktm_rawat_pasien WHERE id_sktm = $id_sktm");

    if ($hapus_detail) {
        // D. Hapus Data Utama SKTM Rawat
        $hapus_utama = mysqli_query($koneksi, "DELETE FROM tb_sktm_rawat WHERE id_sktm = $id_sktm");

        if ($hapus_utama) {
            require_once __DIR__ . '/../../includes/nomor_surat_helper.php';
            renumerasiNomorSuratGlobal($koneksi);
            echo "<script>
                    alert('Data surat pembebasan rawat beserta seluruh lampiran berhasil dihapus!');
                    window.location.href = '../../index.php?page=sktm-rawat';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data utama: " . mysqli_error($koneksi) . "');
                    window.location.href = '../../index.php?page=sktm-rawat';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Gagal menghapus data detail pasien: " . mysqli_error($koneksi) . "');
                window.location.href = '../../index.php?page=sktm-rawat';
              </script>";
    }
} else {
    echo "<script>
            alert('ID Surat tidak valid!');
            window.location.href = '../../index.php?page=sktm-rawat';
          </script>";
}
?>