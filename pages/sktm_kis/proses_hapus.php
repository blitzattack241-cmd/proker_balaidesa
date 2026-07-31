<?php
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

// Pastikan parameter ID ada dan merupakan angka untuk mencegah SQL Injection basic
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_sktm = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 1. Ambil nama file foto yang terkait dengan data ini sebelum dihapus
    $query_select = mysqli_query($koneksi, "
        SELECT foto_depan, foto_ruang_tamu, foto_kamar_tidur, foto_dapur, foto_kamar_mandi 
        FROM tb_sktm_kis 
        WHERE id_sktm = '$id_sktm'
    ");

    if ($query_select && mysqli_num_rows($query_select) > 0) {
        $data = mysqli_fetch_assoc($query_select);

        // Path direktori tempat foto disimpan
        $target_dir = "../../uploads/sktm_kis/"; // Sesuaikan relative path menuju folder uploads

        // Array nama kolom foto
        $foto_fields = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar_tidur', 'foto_dapur', 'foto_kamar_mandi'];

        // Looping untuk menghapus berkas fisik file dari server jika file tersebut ada
        foreach ($foto_fields as $field) {
            if (!empty($data[$field])) {
                $file_path = $target_dir . $data[$field];
                if (file_exists($file_path)) {
                    unlink($file_path); // Menghapus file fisik
                }
            }
        }

        // 2. Hapus data record dari database setelah berkas fisik bersih
        $query_delete = mysqli_query($koneksi, "DELETE FROM tb_sktm_kis WHERE id_sktm = '$id_sktm'");

        if ($query_delete) {
            require_once __DIR__ . '/../../includes/nomor_surat_helper.php';
            renumerasiNomorSuratGlobal($koneksi);
            echo "<script>
                    alert('Data SKTM KIS beserta seluruh dokumentasi foto berhasil dihapus!');
                    window.location.href = '../../index.php?page=sktm-kis';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus data dari database: " . mysqli_real_escape_string($koneksi, mysqli_error($koneksi)) . "');
                    window.location.href = '../../index.php?page=sktm-kis';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Data tidak ditemukan!');
                window.location.href = '../../index.php?page=sktm-kis';
              </script>";
    }
} else {
    echo "<script>
            alert('Akses ditolak! ID tidak valid.');
            window.location.href = '../../index.php?page=sktm-kis';
          </script>";
}
?>