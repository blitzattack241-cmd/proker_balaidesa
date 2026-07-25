<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cek hak akses admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!$isAdmin) {
    echo "<script>
            alert('Akses ditolak!');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// Pastikan ada parameter ID yang dilempar untuk dihapus
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");
    $id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 1. Ambil nama file foto lama dari database sebelum datanya dihapus
    $query_foto = mysqli_query($koneksi, "SELECT foto_depan, foto_ruang_tamu, foto_kamar, foto_dapur, foto_toilet FROM tb_sktm_bumil WHERE id_sktm = '$id_sktm'");
    
    if ($query_foto && mysqli_num_rows($query_foto) > 0) {
        $data_foto = mysqli_fetch_assoc($query_foto);
        
        // Definisikan path target folder foto
        $target_dir = "../../assets/img/sktm_bumil/";

        // Array daftar kolom foto
        $daftar_kolom_foto = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];

        // Loop untuk menghapus file fisik di server jika file tersebut ada
        foreach ($daftar_kolom_foto as $kolom) {
            $nama_file = $data_foto[$kolom];
            if (!empty($nama_file)) {
                $path_file_lengkap = $target_dir . $nama_file;
                // Cek apakah file benar-benar ada di folder sebelum dihapus
                if (file_exists($path_file_lengkap)) {
                    unlink($path_file_lengkap); // Menghapus file gambar dari server
                }
            }
        }
    }

    // 2. Hapus data arsip surat dari database
    $query_delete = mysqli_query($koneksi, "DELETE FROM tb_sktm_bumil WHERE id_sktm = '$id_sktm'");

    if ($query_delete) {
        echo "<script>
                alert('Arsip SKTM Bumil beserta berkas foto rumah berhasil dihapus secara permanen!');
                window.location.href = '../../index.php?page=sktm-bumil';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data dari database.');
                window.location.href = '../../index.php?page=sktm-bumil';
              </script>";
    }
} else {
    // Jika diakses tanpa parameter ID langsung diredirect
    header("Location: ../../index.php?page=sktm-bumil");
    exit;
}
?>