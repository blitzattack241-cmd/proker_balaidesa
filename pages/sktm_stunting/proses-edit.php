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
require_once __DIR__ . '/../../koneksi.php';
if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . mysqli_connect_error() . "');
            window.history.back();
          </script>";
    exit;
}

// 3. Memproses Data ketika Form Disubmit lewat POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pastikan ID Surat dikirimkan
    if (!isset($_POST['id_sktm']) || empty($_POST['id_sktm'])) {
        echo "<script>
                alert('Gagal! ID Surat tidak valid.');
                window.location.href = '../../index.php?page=sktm-stunting';
              </script>";
        exit;
    }

    // Ambil dan bersihkan data input (Mencegah SQL Injection)
    $id_sktm = mysqli_real_escape_string($koneksi, $_POST['id_sktm']);
    $nomor_surat = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_warga = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $no_ktp = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $no_kk = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $agama = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $alamat_tinggal = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $nama_anak = mysqli_real_escape_string($koneksi, $_POST['nama_anak']);
    $kewarganegaraan = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $keperluan = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $id_pejabat = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);

    // Validasi data krusial agar tidak kosong
    if (empty($nomor_surat) || empty($nama_warga) || empty($nama_anak) || empty($id_pejabat)) {
        echo "<script>
                alert('Gagal! Mohon lengkapi seluruh kolom yang wajib diisi.');
                window.history.back();
              </script>";
        exit;
    }

    // 4. Query Update Data ke Tabel tb_sktm_stunting berdasarkan id_sktm
    $query = "UPDATE tb_sktm_stunting SET 
                nomor_surat     = '$nomor_surat', 
                tanggal_surat   = '$tanggal_surat', 
                nama_warga      = '$nama_warga', 
                no_ktp          = '$no_ktp', 
                no_kk           = '$no_kk', 
                tempat_lahir    = '$tempat_lahir', 
                tanggal_lahir   = '$tanggal_lahir', 
                jenis_kelamin   = '$jenis_kelamin', 
                agama           = '$agama', 
                pekerjaan       = '$pekerjaan', 
                alamat_tinggal  = '$alamat_tinggal', 
                nama_anak       = '$nama_anak', 
                kewarganegaraan = '$kewarganegaraan', 
                keperluan       = '$keperluan', 
                id_pejabat      = '$id_pejabat' 
              WHERE id_sktm     = '$id_sktm'";

    // 5. Eksekusi Query & Respons Feedback
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Sukses! Perubahan data SKTM Stunting berhasil disimpan.');
                window.location.href = '../../index.php?page=sktm-stunting';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('Gagal memperbarui data! Error: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
        exit;
    }

} else {
    // Jika file diakses langsung secara ilegal tanpa form POST
    echo "<script>
            window.location.href = '../../index.php?page=sktm-stunting';
          </script>";
    exit;
}
?>