<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// SOLUSI PERMANEN: Menggunakan jalur absolut berbasis direktori root XAMPP
// Ini akan otomatis mencari: C:/xampp/htdocs/proker_balaidesa/koneksi.php
$root_path = $_SERVER['DOCUMENT_ROOT'] . "/proker_balaidesa/koneksi.php";

if (file_exists($root_path)) {
    include $root_path;
} else {
    // Fallback jika nama folder project di htdocs bukan 'proker_balaidesa'
    // Ia akan mundur 3 tingkat ke belakang secara dinamis
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

if (isset($_POST['simpan'])) {
    // Memastikan koneksi database tersedia sebelum melakukan escape string
    if (!isset($koneksi) || !$koneksi) {
        die("Koneksi database gagal dimuat. Pastikan file 'koneksi.php' di folder utama sudah benar.");
    }

    // Mengamankan data inputan dari sql injection
    $nomor_surat   = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $jenis_dikirim = mysqli_real_escape_string($koneksi, $_POST['jenis_dikirim']);
    $banyaknya     = mysqli_real_escape_string($koneksi, $_POST['banyaknya']);
    $keterangan    = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $created_by    = mysqli_real_escape_string($koneksi, $_SESSION['nama'] ?? ($_SESSION['email'] ?? 'Admin'));

    // Query insert data
    $query = "INSERT INTO tb_surat_dukcapil (nomor_surat, tanggal_surat, jenis_dikirim, banyaknya, keterangan, created_by) 
              VALUES ('$nomor_surat', '$tanggal_surat', '$jenis_dikirim', '$banyaknya', '$keterangan', '$created_by')";

    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {
        echo "<script>
                alert('Data surat berhasil disimpan!');
                window.location.href = '../../index.php?page=surat-pengantar-dukcapil';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menyimpan data: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../../index.php?page=dashboard");
}
?>