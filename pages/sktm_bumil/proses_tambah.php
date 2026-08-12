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

if (isset($_POST['simpan'])) {
    require_once __DIR__ . '/../../koneksi.php';
    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }

    require_once __DIR__ . '/../../includes/nomor_surat_helper.php';

    // Reservasi nomor surat definitif
    $nomor_surat     = generateNomorSuratGlobal($koneksi, true);
    $tanggal_surat   = trim($_POST['tanggal_surat'] ?? '');
    $nama_warga      = trim($_POST['nama_warga'] ?? '');
    $jenis_kelamin   = trim($_POST['jenis_kelamin'] ?? '');
    $no_ktp          = trim($_POST['no_ktp'] ?? '');
    $no_kk           = trim($_POST['no_kk'] ?? '');
    $tempat_lahir    = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir   = trim($_POST['tanggal_lahir'] ?? '');
    $agama           = trim($_POST['agama'] ?? '');
    $pekerjaan       = trim($_POST['pekerjaan'] ?? '');
    $kewarganegaraan = trim($_POST['kewarganegaraan'] ?? '');
    $alamat_tinggal  = trim($_POST['alamat_tinggal'] ?? '');
    $keperluan       = trim($_POST['keperluan'] ?? '');
    $berlaku_mulai   = trim($_POST['berlaku_mulai'] ?? '');
    $berlaku_selesai = trim($_POST['berlaku_selesai'] ?? '');
    $keterangan_lain = trim($_POST['keterangan_lain'] ?? '');
    
    // Pastikan id_pejabat bertipe integer
    $id_pejabat      = !empty($_POST['id_pejabat']) ? (int) $_POST['id_pejabat'] : 0;
    $nama_camat      = trim($_POST['nama_camat'] ?? '');

    // Pengaturan Upload Foto Rumah
    $target_dir = "../../assets/img/sktm_bumil/";

    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $daftar_foto = ['foto_depan', 'foto_ruang_tamu', 'foto_kamar', 'foto_dapur', 'foto_toilet'];
    $nama_file_baru = [];

    foreach ($daftar_foto as $key_foto) {
        if (isset($_FILES[$key_foto]) && $_FILES[$key_foto]['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES[$key_foto]['tmp_name'];
            $file_name = $_FILES[$key_foto]['name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            $ekstensi_diperbolehkan = ['jpg', 'jpeg', 'png'];
            if (!in_array($file_ext, $ekstensi_diperbolehkan)) {
                echo "<script>
                        alert('Gagal! Format file $key_foto harus berupa JPG, JPEG, atau PNG.');
                        window.history.back();
                      </script>";
                exit;
            }

            $new_name    = $key_foto . "_" . uniqid() . "." . $file_ext;
            $target_file = $target_dir . $new_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                $nama_file_baru[$key_foto] = $new_name;
            } else {
                $nama_file_baru[$key_foto] = "";
            }
        } else {
            $nama_file_baru[$key_foto] = "";
        }
    }

    // Query SQL Prepared Statement (23 Kolom)
    $query_insert = "INSERT INTO tb_sktm_bumil (
        nomor_surat, tanggal_surat, nama_warga, jenis_kelamin, no_ktp, no_kk, 
        tempat_lahir, tanggal_lahir, agama, pekerjaan, kewarganegaraan, 
        alamat_tinggal, keperluan, berlaku_mulai, berlaku_selesai, keterangan_lain, 
        id_pejabat, nama_camat, foto_depan, foto_ruang_tamu, foto_kamar, foto_dapur, foto_toilet
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $query_insert);

    if ($stmt) {
        // Tipe parameter: 16 string ('s'), 1 integer ('i'), 6 string ('s') = Total 23 parameter
        mysqli_stmt_bind_param(
            $stmt,
            "ssssssssssssssssissssss",
            $nomor_surat,
            $tanggal_surat,
            $nama_warga,
            $jenis_kelamin,
            $no_ktp,
            $no_kk,
            $tempat_lahir,
            $tanggal_lahir,
            $agama,
            $pekerjaan,
            $kewarganegaraan,
            $alamat_tinggal,
            $keperluan,
            $berlaku_mulai,
            $berlaku_selesai,
            $keterangan_lain,
            $id_pejabat,
            $nama_camat,
            $nama_file_baru['foto_depan'],
            $nama_file_baru['foto_ruang_tamu'],
            $nama_file_baru['foto_kamar'],
            $nama_file_baru['foto_dapur'],
            $nama_file_baru['foto_toilet']
        );

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Data SKTM Ibu Hamil berhasil disimpan!');
                    window.location.href = '../../index.php?page=sktm-bumil';
                  </script>";
        } else {
            $error_msg = mysqli_stmt_error($stmt);
            echo "<script>
                    alert('Gagal menyimpan data ke database! Error: " . addslashes($error_msg) . "');
                    window.history.back();
                  </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        $error_msg = mysqli_error($koneksi);
        echo "<script>
                alert('Gagal menyiapkan query database! Error: " . addslashes($error_msg) . "');
                window.history.back();
              </script>";
    }
} else {
    header("Location: ../../index.php?page=sktm-bumil");
    exit;
}
?>