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
require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

if (mysqli_connect_errno()) {
    echo "<script>
            alert('Koneksi database gagal: " . addslashes(mysqli_connect_error()) . "');
            window.history.back();
          </script>";
    exit;
}

// 3. Memproses Data ketika Form Disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Generasi / Reservasi nomor surat definitif
    $nomor_surat = generateNomorSuratGlobal($koneksi, true);
    $tanggal_surat = $_POST['tanggal_surat'] ?? '';
    $nama_warga = $_POST['nama_warga'] ?? '';
    $no_ktp = $_POST['no_ktp'] ?? '';
    $no_kk = $_POST['no_kk'] ?? '';
    $tempat_lahir = $_POST['tempat_lahir'] ?? '';
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = $_POST['jenis_kelamin'] ?? '';
    $agama = $_POST['agama'] ?? '';
    $pekerjaan = $_POST['pekerjaan'] ?? '';
    $alamat_tinggal = $_POST['alamat_tinggal'] ?? '';
    $nama_anak = $_POST['nama_anak'] ?? '';
    $kewarganegaraan = $_POST['kewarganegaraan'] ?? '';
    $keperluan = $_POST['keperluan'] ?? '';
    $id_pejabat = $_POST['id_pejabat'] ?? '';

    // Validasi input wajib agar tidak kosong
    if (empty($nomor_surat) || empty($nama_warga) || empty($nama_anak) || empty($id_pejabat)) {
        echo "<script>
                alert('Gagal! Mohon isi semua kolom yang wajib diisi.');
                window.history.back();
              </script>";
        exit;
    }

    // 4. Query Insert menggunakan Prepared Statement
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
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        // 's' = string, 'i' = integer (id_pejabat)
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssi",
            $nomor_surat,
            $tanggal_surat,
            $nama_warga,
            $no_ktp,
            $no_kk,
            $tempat_lahir,
            $tanggal_lahir,
            $jenis_kelamin,
            $agama,
            $pekerjaan,
            $alamat_tinggal,
            $nama_anak,
            $kewarganegaraan,
            $keperluan,
            $id_pejabat
        );

        // 5. Eksekusi Statement
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Berhasil! Data SKTM Stunting baru telah disimpan.');
                    window.location.href = '../../index.php?page=sktm-stunting';
                  </script>";
            exit;
        } else {
            $error_msg = mysqli_stmt_error($stmt);
            echo "<script>
                    alert('Gagal menyimpan data ke database! Error: " . addslashes($error_msg) . "');
                    window.history.back();
                  </script>";
            exit;
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_msg = mysqli_error($koneksi);
        echo "<script>
                alert('Gagal menyiapkan query database! Error: " . addslashes($error_msg) . "');
                window.history.back();
              </script>";
        exit;
    }

} else {
    // Jika file diakses langsung tanpa method POST
    header("Location: ../../index.php?page=sktm-stunting");
    exit;
}
?>