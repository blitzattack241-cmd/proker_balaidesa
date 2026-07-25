<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Menggunakan jalur absolut berbasis direktori root XAMPP
$root_path = $_SERVER['DOCUMENT_ROOT'] . "/proker_balaidesa/koneksi.php";

if (file_exists($root_path)) {
    include $root_path;
} else {
    // Fallback mundur secara dinamis jika path absolut tidak terbaca
    include dirname(__DIR__, 2) . "/koneksi.php";
}

// Proteksi file aksi: Pastikan hanya dijalankan oleh admin
$role = $_SESSION['role'] ?? '';
$isAdmin = is_numeric($role) ? ((int) $role === 1) : (strtolower(trim($role)) === 'admin');
if (!isset($_SESSION['role']) || !$isAdmin) {
    echo "<script>
            alert('Akses Ilegal!');
            window.location.href = '../../index.php?page=dashboard';
          </script>";
    exit;
}

// Memastikan parameter ID dikirim melalui URL
if (isset($_GET['id'])) {
    if (!isset($koneksi) || !$koneksi) {
        die("Koneksi database gagal dimuat. Pastikan file 'koneksi.php' di folder utama sudah benar.");
    }

    // Mengubah ID menjadi integer agar aman dari SQL Injection
    $id_surat = (int)$_GET['id'];

    // Menghapus data berdasarkan id_surat di tabel tb_surat_dukcapil
    $query = "DELETE FROM tb_surat_dukcapil WHERE id_surat = $id_surat";
    $hapus = mysqli_query($koneksi, $query);

    if ($hapus) {
        echo "<script>
                alert('Data surat berhasil dihapus!');
                window.location.href = '../../index.php?page=surat-pengantar-dukcapil';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
    }
} else {
    // Jika diakses langsung tanpa mengirim ID lewat URL
    header("Location: ../../index.php?page=surat-pengantar-dukcapil");
}
?>