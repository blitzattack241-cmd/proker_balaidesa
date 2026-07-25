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

// 3. Memproses Data ketika Form Disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Ambil dan bersihkan data input (Mencegah SQL Injection)
    $nomor_surat      = mysqli_real_escape_string($koneksi, $_POST['nomor_surat']);
    $tanggal_surat    = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);
    $nama_warga       = mysqli_real_escape_string($koneksi, $_POST['nama_warga']);
    $no_ktp           = mysqli_real_escape_string($koneksi, $_POST['no_ktp']);
    $no_kk            = mysqli_real_escape_string($koneksi, $_POST['no_kk']);
    $tempat_lahir     = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir    = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin    = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $agama            = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $pekerjaan        = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $alamat_tinggal   = mysqli_real_escape_string($koneksi, $_POST['alamat_tinggal']);
    $nama_anak        = mysqli_real_escape_string($koneksi, $_POST['nama_anak']);
    $kewarganegaraan  = mysqli_real_escape_string($koneksi, $_POST['kewarganegaraan']);
    $keperluan        = mysqli_real_escape_string($koneksi, $_POST['keperluan']);
    $id_pejabat       = mysqli_real_escape_string($koneksi, $_POST['id_pejabat']);

    // Validasi input wajib agar tidak kosong
    if (empty($nomor_surat) || empty($nama_warga) || empty($nama_anak) || empty($id_pejabat)) {
        echo "<script>
                alert('Gagal! Mohon isi semua kolom yang wajib diisi.');
                window.history.back();
              </script>";
        exit;
    }

    // 4. Query Insert ke Tabel tb_sktm_stunting
    $query = "INSERT INTO tb_sktm_stunting (
                nomor_surat, 
                tanggal_surat, 
                nama_warga, 
                no_ktp, 
                no_kk, 
                tempat_lahir, 
                tanggal_lahir, 
                jenis_kelamin, 
                agama, 
                pekerjaan, 
                alamat_tinggal, 
                nama_anak, 
                kewarganegaraan, 
                keperluan, 
                id_pejabat
              ) VALUES (
                '$nomor_surat', 
                '$tanggal_surat', 
                '$nama_warga', 
                '$no_ktp', 
                '$no_kk', 
                '$tempat_lahir', 
                '$tanggal_lahir', 
                '$jenis_kelamin', 
                '$agama', 
                '$pekerjaan', 
                '$alamat_tinggal', 
                '$nama_anak', 
                '$kewarganegaraan', 
                '$keperluan', 
                '$id_pejabat'
              )";

    // 5. Eksekusi Query & Berikan Umpan Balik (Alert)
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
                alert('Berhasil! Data SKTM Stunting baru telah disimpan.');
                window.location.href = '../../index.php?page=sktm-stunting';
              </script>";
        exit;
    } else {
        // Jika terjadi error pada SQL syntax / database
        echo "<script>
                alert('Gagal menyimpan data ke database! Error: " . mysqli_error($koneksi) . "');
                window.history.back();
              </script>";
        exit;
    }

} else {
    // Jika file diakses langsung tanpa melalui method POST
    echo "<script>
            window.location.href = '../../index.php?page=sktm-stunting';
          </script>";
    exit;
}
?>