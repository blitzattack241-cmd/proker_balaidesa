<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Query hapus data
$query = mysqli_query($koneksi, "DELETE FROM tb_surat_domisili WHERE id_domisili = '$id'");

if ($query) {
    // SEBELUMNYA: window.location='tampil.php';
    // UBAH MENJADI:
    echo "<script>
            alert('Surat berhasil dihapus!'); 
            window.location='index.php?page=surat-domisili';
          </script>";
} else {
    echo "<script>
            alert('Gagal menghapus surat: " . mysqli_error($koneksi) . "'); 
            window.location='index.php?page=surat-domisili';
          </script>";
}
?>