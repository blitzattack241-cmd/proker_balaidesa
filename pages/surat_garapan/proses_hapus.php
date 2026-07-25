<?php
// 1. Koneksi ke Database (Sesuaikan dengan config Anda jika ada)
$koneksi = mysqli_connect("localhost", "root", "", "db_balaidesa");

// 2. Cek apakah ada parameter 'id' yang dikirim melalui URL
if (isset($_GET['id'])) {
    $id_garapan = mysqli_real_escape_string($koneksi, $_GET['id']);

    // 3. Lakukan query hapus pada tabel utama (tb_surat_garapan)
    // Karena foreign key diatur CASCADE, rincian sawah di tb_surat_garapan_detail otomatis ikut terhapus!
    $query_hapus = mysqli_query($koneksi, "DELETE FROM tb_surat_garapan WHERE id_garapan = '$id_garapan'");

    if ($query_hapus) {
        echo "<script>
                alert('Data Surat Garapan Sawah berhasil dihapus!');
                window.location.href = '../../index.php?page=surat-garapan-sawah';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
                window.location.href = '../../index.php?page=surat-garapan-sawah';
              </script>";
    }
} else {
    // Jika diakses langsung tanpa ID, kembalikan ke halaman utama
    header("Location: ../../index.php?page=surat-garapan-sawah");
    exit;
}
?>